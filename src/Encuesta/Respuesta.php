<?php

class Encuesta_Respuesta extends Gatuf_Model {
	public $usuario, $pregunta;
	public $data, $respuesta_data;
	
	public function __construct () {
		$this->_getConnection ();
		$this->table = 'Respuestas';
		$this->data = array ();
	}
	
	public function create () {
		$this->preSave ();
		$req = sprintf ('INSERT INTO %s (usuario, pregunta, valor) VALUES (%s, %s ,%s)', $this->getSqlTable (), Gatuf_DB_IdentityToDb ($this->usuario, $this->_con), Gatuf_DB_IntegerToDb ($this->pregunta, $this->_con), Gatuf_DB_IdentityToDb ($this->respuesta_data, $this->_con));
		
		$this->_con->execute($req);
		
		return true;
	}
	
	public function preSave () {
		$this->respuesta_data = serialize ($this->data);
	}
}
