
var Result={
		result:function(hashcode,container){
			var container=container;
			$.post('http://localhost/PvW/Zend/public/game/resultJSON',{hash:hashcode}, function(data) {
					$('h1').remove();
					$(container).append("<h1>Result</h1>");
					$(container).append("<p>Player 1: "+data.game.user1+""+data.game.email1+"<br  />");
					$(container).append("Player 1: "+data.game.choice1+" <br  /></p>");
					$(container).append("<p>Player 2: "+data.game.user2+""+data.game.email2+" <br  />");
					$(container).append("Player 2: "+data.game.choice2+" <br  /></p>");
					$(container).append("<p>Result : "+data.game.result+" <br  /></p>");
					$(container).append('<a class="btn btn-primary">Revenge</a><br> ');
					$(container+" a").click(function(){
						Revange.result(hashcode,'#div');
					});
			}
			, "json");
		}


}