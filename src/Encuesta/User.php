<?php

class Encuesta_User extends Gatuf_Model {
	public $codigo = 0;
	
	public $session_key = '_GATUF_Gatuf_User_auth';
	
	public $admin = false, $active = true;
	public $last_login = null;
	
	function checkPassword ($password) {
		return true;
	}
	
	function update () {
		
	}
	
	function getUser ($codigo) {
		/* Recuperar el alumno o maestro */
		$user_model = new Encuesta_User ();
		$user_model->codigo = $codigo;
		if ($codigo == 'G00000000') $user_model->admin = 1;
		return $user_model;
	}
	
	function isAnonymous () {
		return (0 === (int) $this->codigo);
	}
}
