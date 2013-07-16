<?php

class Encuesta_Form_Pregunta_MultipleCheckbox extends Gatuf_Form {
	public $pregunta;
	
	public function initFields($extra=array()) {
		$this->pregunta = $extra['pregunta'];
		$sql = new Gatuf_SQL ('pregunta=%s', $this->pregunta->id);
		$local_opciones = Gatuf::factory ('Encuesta_Opcion')->getList (array ('filter' => $sql->gen ()));
		
		if (count ($local_opciones) == 0) {
			throw new Exception ('Pregunta de opciones sin opciones');
		}
		
		foreach ($local_opciones as $opc) {
			$this->fields['check'.$opc->id] = new Gatuf_Form_Field_Boolean (
				array (
					'required' => false,
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
		
		$values = array ();
		foreach ($local_opciones as $opc) {
			if ($this->cleaned_data ['check'.$opc->id]) {
				$values[] = $opc->texto;
			}
		}
		$respuesta->data['value'] = $values;
		
		return $respuesta;
	}
}
