


var Highscore={
	load:function(){
		$.getJSON('http://138.232.66.90/Zend/public/game/highscoreJSON', function(data) {	
			var count=1;
			$.each(data, function(index, value) {
				var tablebody=$('.table tbody');
				$(tablebody).append('<tr><td>'+count+'</td><td>'+index+'</td><td>'+value+'</td></tr>');
				count++;
				});
			});
	},
	removeold:function(){
		var tablebody=$('.table tbody tr');
		tablebody.remove();
	}
	

};