<?php

class Encuesta_Form_Pregunta_Radio extends Gatuf_Form {
	public $pregunta;
	public $opciones;
	
	public function initFields($extra=array()) {
		$this->pregunta = $extra['pregunta'];
		$sql = new Gatuf_SQL ('pregunta=%s', $this->pregunta->id);
		$this->opciones = Gatuf::factory ('Encuesta_Opcion')->getList (array ('filter' => $sql->gen ()));
		
		if (count ($this->opciones) == 0) {
			throw new Exception ('Pregunta de opciones sin opciones');
		}
		
		$choices = array ();
		
		foreach ($this->opciones as $opc) {
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
	
	public function save ($commit) {
		if (!$this->isValid ()) {
			throw new Exception ('Cannot save the model from and invalid form.');
		}
		
		$respuesta = new Encuesta_Respuesta ();
		
		$respuesta->pregunta = $this->pregunta->id;
		
		$respuesta->data['value'] = $this->opciones [$this->cleaned_data['pregunta']]->texto;
		return $respuesta;
	}
}
