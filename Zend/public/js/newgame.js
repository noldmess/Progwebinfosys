
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
			$.post('http://localhost/PvW/Zend/public/game/newJSON',{choice1: form.choice1.value,
					email1:form.email1.value,
					email2:form.email2.value,
					submit:	"New GAME",
					user1: form.user1.value,
					user2: form.user2.value	},
					function(data) {
					if(data.data==='sucess'){
						$('#succes').append("email sand");
						document.forms[0].email2.value="";
						document.forms[0].user2.value="";
					}
				}, "json");
			}
		return false;
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