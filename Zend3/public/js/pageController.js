var Conrtoller={
		test:function(){
			hash=window.location.hash;
			var partsArray = hash.split('#');
			var divs=$('div.container div.one');
			$(divs).each(function( index ) {
				$(this).hide();
			});
			
			if(partsArray.length>=2){
				var partsArray = partsArray[1].split('/');
				var length = partsArray.length;
				switch(partsArray.length)
				{
				case 1:
					$('#new').show();
				  break;
				case 2:
					if(partsArray[0]==="fight"){
						$('#fight').show();
						Fight.load(partsArray[1]);
					}else{
						if(partsArray[0]==="revange"){
							Revange.start(partsArray[1]);
						}
						$('#new').show();
					}
					break;
				case 4:
					if(partsArray[0]==="result" && partsArray[2]==="player"){
						Result.result(partsArray[1],partsArray[3]);
						break;
					}else if(partsArray[0]==="revange" && partsArray[2]==="player"){
						Revange.start(partsArray[1], partsArray[3]);
						$('#new').show();
						break;
					}
				default:
					$('#new').show();
				}
			}else{
				$('#new').show();
			}
		}
};
