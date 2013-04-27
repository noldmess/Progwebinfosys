<?php
namespace  Wiki;
session_start();
class Login{
	
	
	public static function  checklogin($name,$pass){
		$db=DB::getInstance();
		$user=$db->getUser($name,$pass);
		if(sizeof($user)===3){
			return $user['id'];
		}else 
			return false;
	}
	
	public static function  checkloginFirst($name,$pass){
		$pass=hash('sha256',$name.$pass.$name);
		$db=DB::getInstance();
		$user=$db->getUser($name,$pass);
		if(sizeof($user)===3){
			return $user['id'];
		}else
			return false;
	}
	
	public static function  newUser($name,$pass){
		$db=DB::getInstance();
		$pass=hash('sha256',$name.$pass.$name);
		$user=$db->insertUser($name,$pass);
	}
	
	public static function  checkloginSession(){
		return self::checklogin($_SESSION['user'],$_SESSION['pass']);
	}
	
	public static function illegaltry(){
		if(self::checkloginSession()===false)
			header("Location:".dirname($_SERVER['SCRIPT_NAME'])."/login/");
	}
	
	public static function createSession($name,$pass){
		$_SESSION['user']=$name;
		$_SESSION['pass']=hash('sha256',$name.$pass.$name);
	}
	
	public static function destructedSession(){
		unset($_SESSION['user']);
		unset($_SESSION['pass']);
	}
}