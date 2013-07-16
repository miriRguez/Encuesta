<?php

class Encuesta_Form_Pregunta_MultipleTextInput extends Gatuf_Form {
	public $pregunta;
	
	public function initFields($extra=array()) {
		$this->pregunta = $extra['pregunta'];
		$sql = new Gatuf_SQL ('pregunta=%s', $this->pregunta->id);
		$local_opciones = Gatuf::factory ('Encuesta_Opcion')->getList (array ('filter' => $sql->gen ()));
		
		if (count ($local_opciones) == 0) {
			throw new Exception ('Pregunta de opciones sin opciones');
		}
		
		foreach ($local_opciones as $opc) {
			$this->fields['text'.$opc->id] = new Gatuf_Form_Field_Varchar (
				array ('required' => true,
					'label' => $opc->texto,
			));
		}
	}
	
	public function save ($commit=true) {
		if (!$this->isValid ()) {
			throw new Exception ('Cannot save the model from and invalid form.');
		}
		
		$respuesta = new Encuesta_Respuesta ();
		
		$respuesta->pregunta = $this->pregunta->id;
		$respuesta->cuestionario = $this->pregunta->cuestionario;
		
		$sql = new Gatuf_SQL ('pregunta=%s', $this->pregunta->id);
		$local_opciones = Gatuf::factory ('Encuesta_Opcion')->getList (array ('filter' => $sql->gen ()));
		
		if (count ($local_opciones) == 0) {
			throw new Exception ('Pregunta de opciones sin opciones');
		}
		
		$values = array ();
		foreach ($local_opciones as $opc) {
			$values[] = array ('input' => $opc->texto, 'value' => $this->cleaned_data['text'.$opc->id]);
		}
		
		$respuesta->data['value'] = $values;
		return $respuesta;
	}
}
