function reply_click(clicked_id)
{
   var  x= document.forms[0];
   if (x.choice1)
	   x.choice1.value=clicked_id;
   else
	   x.choice2.value=clicked_id;
  }

var Revange={
		start:function(hashcode){
			$.post('/zend3/game/resultJSON',{hash:hashcode,player:2}, function(data) {
				if(data.game.result===2){
				 $('#new] form input[name="user1"]').attr("value",data.game.user1);
				 $('#new form input[name="email1"]').attr("value",data.game.email1);
				 $('#new form input[name="user2"]').attr("value",data.game.user2);
				 $('#new form input[name="email2"]').attr("value",data.game.email2);
				}else{
					 $('#new form input[name="user1"]').attr("value",data.game.user2);
					 $('#new form input[name="email1"]').attr("value",data.game.email2);
					 $('#new form input[name="user2"]').attr("value",data.game.user1);
					 $('#new form input[name="email2"]').attr("value",data.game.email1);
				}
				 
				}
				, "json");
		},
		submit:function(){
			var returnval=true;
			var  form = document.forms[0];
		  	if(form.user1.value===''){
				returnval=false;
				form.user1.parentNode.parentNode.setAttribute('class','control-group error');
				var s=form.user1.parentNode.parentNode.childNodes[0];
				s.innerHTML='Your username is missing';
			}else{
				form.user1.parentNode.parentNode.setAttribute('class','');
				var s=form.user1.parentNode.parentNode.childNodes[0];
				s.innerHTML="";
			}
			if(form.user2.value===''){
				returnval=false;
				form.user2.parentNode.parentNode.setAttribute('class','control-group error');
				var s=form.user2.parentNode.parentNode.childNodes[0];
				s.innerHTML='Your opponents username missing';
			}else{
				form.user2.parentNode.setAttribute('class','');
				var s=form.user2.parentNode.parentNode.childNodes[0];
				s.innerHTML="";
			}
		  	if(form.email1.value==='' || !checkEmailFormat(form.email1.value)){
				returnval=false;
			   	form.email1.parentNode.parentNode.setAttribute('class','control-group error');
			   	var s=form.email1.parentNode.parentNode.childNodes[0];
			   	s.innerHTML='Your email is missing';
			}else{
				form.email1.parentNode.setAttribute('class','');
				var s=form.email1.parentNode.parentNode.childNodes[0];
				s.innerHTML="";
			}
			if(form.email2.value===''  || !checkEmailFormat(form.email2.value)){
				returnval=false;
				form.email2.parentNode.parentNode.setAttribute('class','control-group error');
				var s=form.email2.parentNode.parentNode.childNodes[0];
				s.innerHTML='Your opponents email is missing';
			}else{
				form.email2.parentNode.setAttribute('class','');
				form.email2.parentNode.parentNode.childNodes[0]='';
				var s=form.email2.parentNode.parentNode.childNodes[0];
				s.innerHTML=""
			}
		  	if(form.choice1.value==='' || form.choice1.value === null || form.choice1.value === undefined){
				returnval=false;
				var el = document.getElementById('weapons');
				el.innerHTML='Please select a weapon!';
			}else{
				var el = document.getElementById('weapons');
				el.innerHTML='';
			}
			if( returnval){
				$('input[type="submit"]').hide();
				$.post('/zend3/game/newJSON',{choice1: form.choice1.value,
						email1:form.email1.value,
						email2:form.email2.value,
						submit:	"New GAME",
						user1: form.user1.value,
						user2: form.user2.value,
						msg1: form.msg1.value},
						function(data) {
						if(data.data==='sucess'){
								$('#succes *').remove();
								$('#succes').append("<h1>Email sent</h1>");
								$('#succes').append("<h2>Inviting opponent...</h2> An invitation to your game has been sent to:<br />" +document.forms[0].user2.value+", "+document.forms[0].email2.value);
								$('input[type="submit"]').show();
						}
					}, "json");
				}
			return false;
		}
		


}

