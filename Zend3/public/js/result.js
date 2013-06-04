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
				if(data.game.result == 0){
					$('#resulttxt').append("Result : The game ended in a draw.");	
				}else{
					$('#resulttxt').append("Result : Player "+data.game.result+" has won the game.");	
				}
				
				$('#resultbotten').hide();
				$('#resultnew').hide();
				if(player == 1){
					$.cookie('user1', data.game.user1, { expires: 7, path: '/' });
					$.cookie('email1', data.game.email1, { expires: 7, path: '/' });	
				}else{
					$.cookie('user1', data.game.user2, { expires: 7, path: '/' });
					$.cookie('email1', data.game.email2, { expires: 7, path: '/' });	
				}
				
				if(data.player!=data.game.result){
					$('#resultbotten').attr("href","#revange/"+data.game.hash+"/player/"+player);
					$('#resultbotten').show();
				}else{
					$('#resultnew').attr("href","#revange/"+data.game.hash+"/player/"+player);
					$('#resultnew').show();
				}
				}, "json");
		}


}
