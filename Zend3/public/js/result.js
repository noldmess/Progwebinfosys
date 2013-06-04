var Result={
		result:function(hashcode,player){
			help=player;
			$('#result').show();
			var container=container;
			$.post('zend3/game/resultJSON',{hash:hashcode,player:player}, function(data) {
				$('#resultuser1').append("Player 1:"+data.game.user1+" "+data.game.email1);
				$('#resultuser1msg').append(data.game.msg1);
				$('#resultuser2').append("Player 2:"+data.game.user2+" "+data.game.email2);
				$('#resultchoice1').append("Player 1: "+data.game.choice1);
				$('#resultchoice2').append("Player 2: "+data.game.choice2);
				$('#resulttxt').append("Result : Player "+data.game.result+" has won the game.");
				$('#resultbotten').hide();
				$('#resultnew').hide();
				if(data.player!=data.game.result){
					$('#resultbotten').show();
					$('#resultbotten').attr("href","#revange/"+data.game.hash);
				}else{
					$('#resultnew').show();
				}
				}, "json");
		}


}
