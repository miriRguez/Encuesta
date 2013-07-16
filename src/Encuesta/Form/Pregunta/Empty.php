<?php

class Encuesta_Form_Pregunta_Empty extends Gatuf_Form {
	public $pregunta;
	
	public function initFields($extra=array()) {
		$this->pregunta = $extra['pregunta'];
	}
	
	public function save ($commit=true) {
		if (!$this->isValid ()) {
			throw new Exception ('Cannot save the model from and invalid form.');
		}
		
		$respuesta = new Encuesta_Respuesta ();
		
		$respuesta->pregunta = $this->pregunta->id;
		$respuesta->cuestionario = $this->pregunta->cuestionario;
		
		$respuesta->data['value'] = 'CONTESTADA. Ignorar valor';
		return $respuesta;
	}
}
