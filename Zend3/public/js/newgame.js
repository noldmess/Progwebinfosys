var NewGame={
	
		
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
						$('#succes').empty();
						$('#succes').append("<h1>Email sent</h1>");
						$('#succes').append("<h2>Inviting opponent...</h2> An invitation to your game has been sent to:<br />" +document.forms[0].user2.value+", "+document.forms[0].email2.value);
						$('#succes').css({'border-style': 'solid', 'border-width': '3px', 'border-color': 'green', 'padding': '10px'});
						document.forms[0].email2.value="";
						document.forms[0].user2.value="";
						$('input[type="submit"]').show();
						$('html, body').animate( { scrollTop: 0 }, 'slow' );
					}
				}, "json");
				
			}
		return false;
		},
		
		reply_click:function(clicked_id){
			
		   var  x= document.forms[0].choice1;
		   x.value=clicked_id;
		   
		   $('.new'+clicked_id).removeClass('btn-danger');
		   $('.new'+clicked_id).removeClass('btn-success');
		   $('.new'+clicked_id).addClass('btn-primary');
		   clicked_id *=1;
		   var lose = clicked_id+1>5?1:clicked_id+1;
		   $('.new'+lose).removeClass('btn-primary');
		   $('.new'+lose).removeClass('btn-success');
		   $('.new'+lose).addClass('btn-danger');
		   lose = lose+2>5?(lose+2)%5:lose+2;
		   $('.new'+lose).removeClass('btn-primary');
		   $('.new'+lose).removeClass('btn-success');
		   $('.new'+lose).addClass('btn-danger');
		   
		   var win = clicked_id+2>5?(clicked_id+2)%5:clicked_id+2;
		   $('.new'+win).removeClass('btn-primary');
		   $('.new'+win).removeClass('btn-danger');
		   $('.new'+win).addClass('btn-success');
		   win = win+2>5?(win+2)%5:win+2;
		   $('.new'+win).removeClass('btn-primary');
		   $('.new'+win).removeClass('btn-danger');
		   $('.new'+win).addClass('btn-success');

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
