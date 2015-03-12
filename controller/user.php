<?php
class user extends spController{
	public function __construct(){
		parent::__construct();
		$this->ucenter = spClass('spUcenter');
	}
	public function register(){
		if($this->spArgs("submit")){
			$username = $this->spArgs("username");
			$password = $this->spArgs("password");
			$email = $this->spArgs("email");
			$questionid = $this->spArgs("questionid");
			$answer = $this->spArgs("answer");
			$uid = $this->ucenter->uc_user_register($username,$password,$email);
			echo $uid;
			if($uid)
				$this->registersuccess = 1;
		}
		 
		$this->display("front/register.html");
	}
	public function login(){
		if($this->spArgs("submit")){
			$username = $this->spArgs("username");
			$password = $this->spArgs("password");
			$email = $this->spArgs("email");
			$questionid = $this->spArgs("questionid");
			$answer = $this->spArgs("answer");
			$uid = $this->ucenter->uc_user_login($username,$password,$email);
			if($uid){
				$this->loginsuccess = 1;
				$userinfo = $this->ucenter->uc_get_user($username);
				//var_dump($userinfo);
			}
		}
			
		$this->display("front/login.html");
	}
	
}