var express = require('express'),
	app = express(),
	server = require('http').createServer(app),
	io = require('socket.io').listen(server);

server.listen(8080);

// routing
app.get('/', function (req, res) {
	res.sendfile(__dirname + '/index.html');
});

app.use(express.static(__dirname + '/'));

// lokale Speicherung aller User die zur Zeit im Chat sind
var usernames = {};
// Anzahl der User welche im Chat sind
var countUser = 0;

var msgSuperNeeded = 'Du ben&ouml;tigst den Status eines Superusers um diesen Befehl auszuf&uuml;hren';

var chatTopic = "!_42_!";

/*
 * Alle Befehle welche dem User zur Verfügung stehen
 */
var commands = {};
commands['help'] = '/help - zeigt alle m&ouml;glichen Befehle mit Erkl&auml;rung an<br>';
commands['name'] = '/name:myname - &Auml;ndert den Namen des aktuellen Benutzers auf "myname"<br>';
commands['super'] = '/super:myname - der Benutzer "myname" erh&auml;lt Superrechte<br>';
commands['kick'] = '/kick:myname - Kickt den Benutzer "myname" aus dem Chatserver und schliesst damit die Verbindung<br>';
commands['quit'] = '/quit - Beendet die Verbindung zum Server<br>';
//commands['users'] = '/users - Zeigt alle Benutzernamen an, die derzeit mit dem Chatserver verbunden sind<br>';
commands['topic:'] = '/topic:mytopic - &Auml;ndert das Chattopic auf "mytopic"<br>';
commands['topic'] = '/topic - zeigt das aktuelle Chattopic an<br>';
commands['usage'] = 'F&uuml;r folgende Befehle sind Superrechte n&ouml;tig: super, kick, topic:<br>';

io.sockets.on('connection', function (socket) {

	//Wenn nachricht von user geschickt wird
	socket.on('sendMsg', function (data) {
		
		// auf Befehle überprüfen, ansonsten normale Chatnachricht
		
		if(data.indexOf("/help") === 0){
			
			handleHelp(socket);
			
		}else if(data.indexOf('/name:') === 0){
			
			handleName(socket,data);
			
		}else if(data.indexOf('/quit') === 0){
			
			handleQuit(socket);
			
		}else if(data.indexOf('/topic:') === 0){
			
			handleTopicAdvanced(socket,data);
			
		}else if(data.indexOf('/topic') === 0){
			
			handleTopic(socket);

		}else if(data.indexOf('/super:') === 0){
			
			handleSuper(socket, data);

		}else if(data.indexOf('/kick:') === 0){
			
			handleKick(socket,data);

		}else{
			
			// allen Clients mitteilen, sie sollen ihren chat updaten, da normale Nachricht
			io.sockets.emit('updateChat', socket.username, data);
		}
	});
	
	//neuer User wird angemeldet (direkt beim Aufruf der Seite)
	socket.on('newUser', function(){
		
		/*
		 * Username am Beginn eine Konkatenation von 'user' + die ID des Sockets mit dem er verbunden ist.
		 * Sollte dieser Name bereits vergeben sein, wird anstelle der ID eine Zufallszahl zwischen 1 und 10000 verwendet.
		 */
		var username = 'user'+socket.id.slice(0,5);
		while(usernames[username]!== undefined){
			username = 'user'+Math.ceil(Math.random()*10000);
		}
		
		// Usernamen an Socketsession binden
		socket.username = username;
		
		// User lokal 'speichern'
		usernames[username] = {name: username, socketID: socket.id};
		
		countUser++;
		
		var msg = 'Du hast den Chat mit Namen "'+username+'" betreten!<br> Gib /help ein, um m&ouml;gliche Optionen einzusehen!';
		
		/*
		 * Wenn der User der erste User ist, der den Chat betritt, gib ihm Superrechte
		 */
		if(countUser === 1){
			usernames[username].superUser = true;
			msg += '<br>Du bist ein Superuser!';
		}
		
		/*
		 * Chat updaten, Namen setzen und die Liste der User updaten
		 */
		socket.emit('updateChat', 'SERVER', msg);
		socket.emit('setName', username);
		
		socket.broadcast.emit('updateChat', 'SERVER', '"'+username + '" hat den Chat betreten!');
		
		io.sockets.emit('updateUsers', usernames);
	});


	// wenn ein User den Browser schließt, die Verbindung beendet, oder gekickt wird!
	socket.on('disconnect', function(){
		
		/*
		 * User löschen, counter verringern, Userliste updaten und im Chat mitteilen,
		 * dass der User den Chat verlassen hat!
		 */
		delete usernames[socket.username];
		
		countUser--;

		io.sockets.emit('updateUsers', usernames);
		socket.broadcast.emit('updateChat', 'SERVER', '"'+socket.username + '" hat den Chat verlassen!');
	});
});

/**
 * Bearbeitet den /help-Befehl
 */
var handleHelp = function(socket){
	var msg = '<br>';
	for(var key in commands){
		msg+=commands[key];
	}
	socket.emit('updateChat', 'Hilfe', msg);
};

/**
 * Bearbeitet den /name:myname-Befehl
 */
var handleName = function(socket, data){
	
	var newName = data.split(':')[1];
	/*
	 * Wenn Username bereits vergeben ist, User darauf hinweisen,
	 * ansonsten überprüfen ob der Username angeben wurde und darauf setzen.
	 */
	if(usernames[newName] !== undefined){
		socket.emit('updateChat', 'SERVER', 'Benutzername bereits vergeben!');
	}else if(newName !== undefined && newName !== ''){
		
		var oldUser = usernames[socket.username];
		delete usernames[socket.username];
		
		oldUser.name=newName;
		usernames[newName] = oldUser;
		
		socket.emit('updateChat', 'SERVER', 'Dein neuer Benutzername ist "'+newName+'"!');
		socket.emit('setName', newName);
		socket.broadcast.emit('updateChat','SERVER', '"'+socket.username +'" hat sich umbenannt in "'+newName+'"!');
		
		socket.username=newName;
		io.sockets.emit('updateUsers', usernames);
		
	}else{
		
		socket.emit('updateChat', 'SERVER', 'Bitte gib einen neuen Benutzernamen an!');
	}
};

/**
 * Bearbeitet den /quit-Befehl
 */
var handleQuit = function(socket, data){
	socket.emit('quitConnection', 'Du hast die Verbindung zum Chat beendet!');
	socket.disconnect();
};

/**
 * Bearbeitet den /super:myname-Befehl
 */
var handleSuper = function(socket, data){
	//auf Superuser überprüfen
	if(usernames[socket.username].superUser === true){
		
		var superUser = data.split(':')[1];
		
		// überprüfen ob ein richtiger User angegeben wurde
		if(superUser !== undefined && superUser !== '' && usernames[superUser]!== undefined){
			usernames[superUser].superUser = true;
			socket.emit('updateChat', 'SERVER', 'User "'+superUser+'" besitzt nun den Status eines Superusers!');
			io.sockets.socket(usernames[superUser].socketID).emit('updateChat', 'SERVER', 'Du wurdest von "'+ socket.username + '" zum Superuser ernannt!');
		}else{
			socket.emit('updateChat', 'SERVER', 'Bitte einen existierenden User angeben!');
		}
		
	}else{
		socket.emit('updateChat', 'SERVER', msgSuperNeeded);
	}
};

/**
 * Bearbeitet den /topic-Befehl
 */
var handleTopic = function(socket){
	socket.emit('updateChat', 'Topic', chatTopic);
};

/**
 * Bearbeitet den /topic:mytopic-Befehl
 */
var handleTopicAdvanced = function(socket, data){
	//auf Superuser überprüfen
	if(usernames[socket.username].superUser === true){
		
		var newTopic = data.split(':')[1];
		
		// überprüfen ob ein Topic angegeben wurde
		if(newTopic !== undefined && newTopic !== ''){
			chatTopic = newTopic;
			socket.emit('updateChat', 'Topic', 'Chattopic ver&auml;ndert auf "'+chatTopic+'"!');
			socket.broadcast.emit('updateChat','Topic', '"'+socket.username +'" hat das Chattopic auf "'+newTopic+'" gesetzt!');
		}else{
			socket.emit('updateChat', 'SERVER', 'Bitte eine Chattopic angeben!');
		}
		
	}else{
		socket.emit('updateChat', 'SERVER', msgSuperNeeded);
	}
};

/**
 * Bearbeitet den /kick-Befehl
 */
var handleKick = function(socket, data){
	//auf Superuser überprüfen
	if(usernames[socket.username].superUser === true){
		
		var kickUser = data.split(':')[1];
		
		// überprüfen ob ein richtiger User angegeben wurde
		if(kickUser !== undefined && kickUser !== '' && usernames[kickUser] !== undefined){
			
			var socketID = usernames[kickUser].socketID;
			
			io.sockets.socket(socketID).emit('quitConnection', 'Du wurdest von "'+socket.username+'" von dem Chat ausgeschlossen!');
			
			io.sockets.socket(socketID).disconnect();
			
			socket.broadcast.emit('updateChat', 'SERVER', '"' +kickUser + '" wurde von "'+ socket.username +'" von dem Chat ausgeschlossen!');
			
			socket.emit('updateChat', 'SERVER', 'User "'+ kickUser + '" wurde von Dir gekickt!');
		}else{
			socket.emit('updateChat', 'SERVER', 'Bitte einen existierenden User angeben!');
		}
		
	}else{
		socket.emit('updateChat', 'SERVER', msgSuperNeeded);
	}
};