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

// usernames which are currently connected to the chat
var usernames = {};
var countUser = 0;

var chatTopic = "!_42_!";

var commands = {};
commands['help'] = '/help - zeigt alle m&ouml;glichen Befehle mit Erkl&Auml;rung an<br>';
commands['name'] = '/name:myname - &Auml;ndert den Namen des aktuellen Benutzers auf myname<br>';
commands['super'] = '/super:myname - der Benutzer myname erh&auml;lt Superrechte<br>';
commands['kick'] = '/kick:myname - Kickt den Benutzer myname aus dem Chatserver und schliesst damit die Verbindung<br>';
commands['quit'] = '/quit - Beendet die Verbindung zum Server<br>';
//commands['users'] = '/users - Zeigt alle Benutzernamen an, die derzeit mit dem Chatserver verbunden sind<br>';
commands['topic:'] = '/topic:mytopic - &Auml;ndert das Chattopic auf mytopic<br>';
commands['topic'] = '/topic - zeigt das aktuelle Chattopic an<br>';
commands['usage'] = 'F&uuml;r folgende Befehle sind Superrechte n&ouml;tig: super, kick, topic:<br>';

io.sockets.on('connection', function (socket) {

	//Wenn nachricht von user geschickt wird
	socket.on('sendMsg', function (data) {
		var msg;
		
		// auf Befehle überprüfen, ansonsten normale Chatnachricht
		if(data.indexOf("/help") === 0){
			msg = '<br>';
			for(var key in commands){
				msg+=commands[key];
			}
			socket.emit('updateChat', 'Hilfe', msg);

		}else if(data.indexOf('/name:') === 0){
			
			var newName = data.split(':')[1];
			if(usernames[newName] !== undefined){
				socket.emit('updateChat', 'SERVER', 'Benutzername bereits vergeben!');
			}else if(newName !== undefined && newName !== ''){
				var oldUser = usernames[socket.username];
				delete usernames[socket.username];
				oldUser.name=newName;
				usernames[newName] = oldUser;
				socket.emit('updateChat', 'SERVER', 'Dein neuer Benutzername ist '+newName);
				socket.broadcast.emit('','SERVER', socket.username +' hat sich umbenannt in '+newName);
				socket.username=newName;
				io.sockets.emit('updateUsers', usernames);
				
			}else{
				
				socket.emit('updateChat', 'SERVER', 'Bitte gib einen neuen Benutzernamen an!');
			}
		}else if(data.indexOf('/quit') === 0){
			
			delete usernames[socket.username];
			
			io.sockets.emit('updateUsers', usernames);
			socket.broadcast.emit('updateChat', 'SERVER', socket.username + ' hat den Chat verlassen!');
			socket.disconnect('unauthorized');
			
		}else if(data.indexOf('/topic:') === 0){
			
			//auf Superuser überprüfen
			if(usernames[socket.username].superUser === true){
				
				var newTopic = data.split(':')[1];
				
				// überprüfen ob ein Topic angegeben wurde
				if(newTopic !== undefined && newTopic !== ''){
					chatTopic = newTopic;
					socket.emit('updateChat', 'Topic', 'Chattopic ver&auml;ndert auf '+chatTopic);
				}else{
					socket.emit('updateChat', 'SERVER', 'Bitte eine Chattopic angeben!');
				}
				
			}else{
				socket.emit('updateChat', 'SERVER', 'Du ben&ouml;tigst den Status eines Superusers um diesen Befehl auszuf&uuml;hren');
			}
		}else if(data.indexOf('/topic') === 0){
			
			socket.emit('updateChat', 'Topic', chatTopic);
			
		}else if(data.indexOf('/super:') === 0){
			//auf Superuser überprüfen
			if(usernames[socket.username].superUser === true){
				
				var superUser = data.split(':')[1];
				
				// überprüfen ob ein richtiger User angegeben wurde
				if(superUser !== undefined && superUser !== '' && usernames[superUser]!== undefined){
					usernames[superUser].superUser = true;
					socket.emit('updateChat', 'SERVER', 'User '+superUser+' besitzt nun den Status eines Superusers!');
					io.sockets.socket(usernames[superUser].socketID).emit('updateChat', 'SERVER', 'Du wurdes von '+ socket.username + ' zum Superuser ernannt!');
				}else{
					socket.emit('updateChat', 'SERVER', 'Bitte einen existierenden User angeben!');
				}
				
			}else{
				socket.emit('updateChat', 'SERVER', 'Du ben&ouml;tigst den Status eines Superusers um diesen Befehl auszuf&uuml;hren');
			}
		}else if(data.indexOf('/kick:') === 0){
			//auf Superuser überprüfen
			if(usernames[socket.username].superUser === true){
				
				var kickUser = data.split(':')[1];
				
				// überprüfen ob ein richtiger User angegeben wurde
				if(kickUser !== undefined && kickUser !== '' && usernames[kickUser] !== undefined){
					
					var socketID = usernames[kickUser].socketID;
					
					delete usernames[kickUser];
					
					io.sockets.emit('updateUsers', usernames);
					
					socket.emit('updateChat', 'SERVEr', 'User '+ kickUser + 'gekickt!');
					
					socket.broadcast.emit('updateChat', 'SERVER', kickUser + ' wurde von '+ socket.username +' aus den Chat gekickt!');
					
					io.sockets.socket(socketID).disconnect('unauthorized');
				}else{
					socket.emit('updateChat', 'SERVER', 'Bitte einen existierenden User angeben!');
				}
				
			}else{
				socket.emit('updateChat', 'SERVER', 'Du ben&ouml;tigst den Status eines Superusers um diesen Befehl auszuf&uuml;hren');
			}
		}else{
			
			// allen Clients mitteilen, sie sollen ihren chat updaten
			io.sockets.emit('updateChat', socket.username, data);
		}
	});
	
	//neuer User wird angemeldet (direkt beim Aufruf der Seite)
	socket.on('newUser', function(){
		
		var username = 'user'+(++countUser);
		
		socket.username = username;
		
		usernames[username] = {name: username, socketID: socket.id};
		
		if(countUser === 1 || Object.keys(usernames).length === 1){
			usernames[username].superUser = true;
		}
		
		socket.emit('updateChat', 'SERVER', 'Du hast den Chat mit Namen "'+username+'" betreten!<br> Gib /help ein, um m&ouml;gliche Optionen einzusehen!');
		
		socket.broadcast.emit('updateChat', 'SERVER', username + ' hat den Chat betreten!');
		
		io.sockets.emit('updateUsers', usernames);
	});


	// wenn ein User den Browser schließt
	socket.on('disconnect', function(){
		delete usernames[socket.username];
		
		io.sockets.emit('updateUsers', usernames);
		socket.broadcast.emit('updateChat', 'SERVER', socket.username + ' hat den Chat verlassen!');
	});
});