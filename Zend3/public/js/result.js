var Result={
		result:function(hashcode,player){
			help=player;
			$('#result').show();
			var container=container;
			$.post('/zend3/game/resultJSON',{hash:hashcode,player:player}, function(data) {
				$('#resultuser1').empty();
				$('#resultuser2msg').empty();
				$('#resultuser2').empty();
				$('#resultchoice1').empty();
				$('#resultchoice2').empty();
				$('#resulttxt').empty();
				$('#resultuser1').append("Player 1:"+data.game.user1+", "+data.game.email1);
				$('#resultuser2msg').append(data.game.msg2);
				$('#resultuser2').append("Player 2:"+data.game.user2+", "+data.game.email2);
				$('#resultchoice1').append("Player 1: "+data.game.choice1);
				$('#resultchoice2').append("Player 2: "+data.game.choice2);
				$('#resulttxt').append("Result : Player "+data.game.result+" has won the game.");
				$('#resultbotten').hide();
				$('#resultnew').hide();
				if(data.player!=data.game.result){
					$('#resultbotten').show();
					$('#resultbotten').attr("href","#revange/"+data.game.hash+"/player/"+player);
				}else{
					$('#resultnew').show();
					$('#resultnew').attr("href","#revange/"+data.game.hash+"/player/"+player);
				}
				}, "json");
		}


}
