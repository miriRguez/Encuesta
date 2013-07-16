<?php

class Encuesta_Form_Pregunta_TextArea extends Gatuf_Form {
	public $pregunta;
	
	public function initFields($extra=array()) {
		$this->pregunta = $extra['pregunta'];
		
		$this->fields['pregunta'] = new Gatuf_Form_Field_Varchar (
			array ('required' => true,
				'label' => 'Ingrese su respuesta',
				'widget' => 'Gatuf_Form_Widget_TextareaInput',
				'widget_attrs' => array(
					'cols' => 68, /* FIXME: Cambiar estos valores */
					'rows' => 26,
				),
		));
	}
	
	public function save ($commit=true) {
		if (!$this->isValid ()) {
			throw new Exception ('Cannot save the model from and invalid form.');
		}
		
		$respuesta = new Encuesta_Respuesta ();
		
		$respuesta->pregunta = $this->pregunta->id;
		$respuesta->cuestionario = $this->pregunta->cuestionario;
		
		$respuesta->data['value'] = $this->cleaned_data['pregunta'];
		return $respuesta;
	}
}
