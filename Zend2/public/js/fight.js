var Fight={
	
		submit:function(){
			var returnval=true;
			var  form = document.forms[0];
		  	if(form.choice2.value==='' || form.choice2.value === null || form.choice2.value === undefined){
				returnval=false;
				var el = document.getElementById('weapons');
				el.innerHTML='Please select a weapon!';
			}else{
				var el = document.getElementById('weapons');
				el.innerHTML='';
			}
		if( returnval){
			$.post('/zend2/game/fightJSON',{choice2: form.choice2.value,id: form.id.value,
					hash:form.hash.value, msg2: form.msg2.value}, function(data) {
					$('#div *').remove();
					Result.result(form.hash.value,'#div');	
				}, "json");
			}
		return false;
		},
		
		reply_click:function(clicked_id){
			
		   var  x= document.forms[0].choice2;
		   x.value=clicked_id;

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
