<?php

Gatuf::loadFunction('Gatuf_Shortcuts_RenderToResponse');
Gatuf::loadFunction('Gatuf_HTTP_URL_urlForView');

class Encuesta_Views_Cuestionario {
	public $index_precond = array ('Gatuf_Precondition::loginRequired');
	public function index ($request, $match) {
		/* Recuperar y listar los cuestionarios disponibles */
		$cuestionarios = Gatuf::factory ('Encuesta_Cuestionario')->getList ();
		
		return Gatuf_Shortcuts_RenderToResponse ('encuesta/cuestionario/index.html',
		                                  array ('cuestionarios' => $cuestionarios,
		                                         'page_title' => 'Cuestionarios'),
		                                  $request);
	}
}
