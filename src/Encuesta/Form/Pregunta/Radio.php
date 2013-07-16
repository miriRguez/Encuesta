<?php

class Encuesta_Form_Pregunta_Radio extends Gatuf_Form {
	public $pregunta;
	
	public function initFields($extra=array()) {
		$this->pregunta = $extra['pregunta'];
		$sql = new Gatuf_SQL ('pregunta=%s', $this->pregunta->id);
		$local_opciones = Gatuf::factory ('Encuesta_Opcion')->getList (array ('filter' => $sql->gen ()));
		
		if (count ($local_opciones) == 0) {
			throw new Exception ('Pregunta de opciones sin opciones');
		}
		
		$choices = array ();
		foreach ($local_opciones as $opc) {
			$choices [$opc->texto] = $opc->id;
		}
		
		$this->fields['pregunta'] = new Gatuf_Form_Field_Integer (
			array (
				'required' => true,
				'label' => 'Seleccione una opción',
				'initial' => '',
				'widget' => 'Gatuf_Form_Widget_RadioInput',
				'widget_attrs' => array (
					'choices' => $choices,
				)
		));
	}
	
	public function save ($commit=true) {
		if (!$this->isValid ()) {
			throw new Exception ('Cannot save the model from and invalid form.');
		}
		
		$respuesta = new Encuesta_Respuesta ();
		
		$respuesta->pregunta = $this->pregunta->id;
		$respuesta->cuestionario = $this->pregunta->cuestionario;
		
		$opcion = new Encuesta_Opcion ();
		$opcion->getOpcion ($this->cleaned_data['pregunta']);
		$respuesta->data['value'] = $opcion->texto;
		return $respuesta;
	}
}
