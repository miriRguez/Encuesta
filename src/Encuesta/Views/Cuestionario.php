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
	
	public function iniciar ($request, $match) {
		$cuestionario = new Encuesta_Cuestionario ();
		
		if (false === ($cuestionario->getCuestionario ($match[1]))) {
			return new Gatuf_HTTP_Response_NotFound ($request);
		}
		
		$url = Gatuf_HTTP_URL_urlForView ('Encuesta_Views_Pregunta::verPregunta', array ($cuestionario->id, 0));
		return new Gatuf_HTTP_Response_Redirect ($url);
	}
	
	public $fin_precond = array ('Gatuf_Precondition::loginRequired');
	public function fin ($request, $match) {
		
		$cuestionario = new Encuesta_Cuestionario ();
		if (false === ($cuestionario->getCuestionario ($match[1]))) {
			return new Gatuf_HTTP_Response_NotFound ($request);
		}
		
		/* Vamos a contar la cantidad de respuestas, si no coincide, le hace falta alguna pregunta */
		$sql = new Gatuf_SQL ('cuestionario=%s', $cuestionario->id);
		$total_cuestionario = Gatuf::factory('Encuesta_Pregunta')->getList (array ('filter' => $sql->gen (), 'count' => true));
		$sql = new Gatuf_SQL ('cuestionario=%s AND usuario=%s', array ($cuestionario->id, $request->user->codigo));
		$total_respuestas = Gatuf::factory('Encuesta_Respuesta')->getList (array ('filter' => $sql->gen (), 'count' => true));
		
		if ($total_respuestas < $total_cuestionario) {
			/* El cuestionario NO está completo */
			$url = Gatuf_HTTP_URL_urlForView ('Encuesta_Views_Cuestionario::iniciar', $cuestionario->id);
			return new Gatuf_HTTP_Response_Redirect ($url);
		}
		
		/* Listo, este cuestionario está finalizado */
		return Gatuf_Shortcuts_RenderToResponse ('encuesta/cuestionario/fin.html',
		                                         array ('title' => 'Cuestionario finalizado',
		                                                'cuestionario' => $cuestionario),
		                                         $request);
	}
}
