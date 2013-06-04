var Fight={
		load:function(hash){
			$.post('/zend3/game/getfightJSON',{hash:hash},
				function(data) {
				if(data.data==='sucess'){
					//var  form = document.forms[0].form.hash.value=hash;
					$('#fight form input[name="hash"]').attr("value",hash);
					$('#user1').empty();
					$('#user2').empty();
					$('#user1msg').empty();
					$('#user1').append(data.game.user1+", "+data.game.email1);
					$('#user1msg').append(data.game.msg1);
					$('#user2').append(data.game.user2+", "+data.game.email2);
				}else{
					$('#fight').hide();
					$('#new').show();
				}
			}, "json");
		},
		submit:function(){
			var returnval=true;
			var  form = document.forms[0];
		  	if($('#fight form input[name="choice2"]').attr("value")==='' || $('#fight form input[name="choice2"]').attr("value") === null ||$('#fight form input[name="choice2"]').attr("value") === undefined){
				returnval=false;
				var el = document.getElementById('weapons');
				el.innerHTML='Please select a weapon!';
			}else{
				var el = document.getElementById('weapons');
				el.innerHTML='';
			}
		if( returnval){
					var hash=$('#fight form input[name="hash"]').attr("value");
					$.post('/zend3/game/fightJSON',{choice2: $('#fight form input[name="choice2"]').attr("value"),id: $('#fight form').attr("id"),
						hash:$('#fight form input[name="hash"]').attr("value"), msg2: $('#fight form textarea').val()}, function(data) {
						}, "json");
					$('#fight').hide();
					window.history.pushState({path:"/zend3/game/new"},"","/zend3/game/new#result/"+hash+"/player/2");
					Result.result(hash,2);
			}
		return false;
		},
		
		reply_click:function(clicked_id){
		   $('#fight form input[name="choice2"]').attr("value",clicked_id);
		   $('.fight'+clicked_id).removeClass('btn-danger');
		   $('.fight'+clicked_id).removeClass('btn-success');
		   $('.fight'+clicked_id).addClass('btn-primary');
		   clicked_id *=1;
		   var lose = clicked_id+1>5?1:clicked_id+1;
		   $('.fight'+lose).removeClass('btn-primary');
		   $('.fight'+lose).removeClass('btn-success');
		   $('.fight'+lose).addClass('btn-danger');
		   lose = lose+2>5?(lose+2)%5:lose+2;
		   $('.fight'+lose).removeClass('btn-primary');
		   $('.fight'+lose).removeClass('btn-success');
		   $('.fight'+lose).addClass('btn-danger');
		   
		   var win = clicked_id+2>5?(clicked_id+2)%5:clicked_id+2;
		   $('.fight'+win).removeClass('btn-primary');
		   $('.fight'+win).removeClass('btn-danger');
		   $('.fight'+win).addClass('btn-success');
		   win = win+2>5?(win+2)%5:win+2;
		   $('.fight'+win).removeClass('btn-primary');
		   $('.fight'+win).removeClass('btn-danger');
		   $('.fight'+win).addClass('btn-success');
		
		}

	
}

function checkEmailFormat(email){

	  if(email.indexOf("@") === -1){
		  return false;
	  }
	  else{
		  email = email.substring(email.indexOf("@") + 1);
		  if(email.indexOf(".") === -1){
			  return false;
		  }
		  return true;
	  }
}
