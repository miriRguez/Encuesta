<?php

Gatuf::loadFunction('Gatuf_Shortcuts_RenderToResponse');
Gatuf::loadFunction('Gatuf_HTTP_URL_urlForView');

class Encuesta_Views_Pregunta {
	public $verPregunta_precond = array ('Gatuf_Precondition::loginRequired');
	public function verPregunta ($request, $match) {
		if ($match[2] == count ($request->preguntas)) {
			/* Este cuestionario ya finalizó */
			$url = Gatuf_HTTP_URL_urlForView ('Encuesta_Views_Cuestionario::fin', array ($match[1]));
			return new Gatuf_HTTP_Response_Redirect ($url);
		}
		
		if (!isset ($request->preguntas[$match[2]])) {
			return new Gatuf_HTTP_Response_NotFound($request);
		}
		
		$esta_pregunta = $request->preguntas[$match[2]];
		/* Hacer aquí la validación de si ya se contestó o no */
		$sql = new Gatuf_SQL ('usuario=%s AND pregunta=%s', array ($request->user->codigo, $esta_pregunta->id));
		$total_resp = Gatuf::factory ('Encuesta_Respuesta')->getList (array ('filter' => $sql->gen (), 'count' => true));
		
		if ($total_resp != 0) {
			/* Respuesta ya contestada, brincar a la siguiente pregunta */
			$url = Gatuf_HTTP_URL_urlForView ('Encuesta_Views_Pregunta::verPregunta', array ($match[1], $match[2] + 1));
			return new Gatuf_HTTP_Response_Redirect ($url);
		}
		
		$extra = array ('pregunta' => $esta_pregunta);
		$tipo = new Encuesta_Tipo ();
		$tipo->getTipo ($esta_pregunta->tipo);
		if ($request->method == 'POST') {
			$form = new $tipo->model ($request->POST, $extra);
			
			if ($form->isValid ()) {
				$respuesta = $form->save ();
				
				$respuesta->usuario = $request->user->codigo;
				$respuesta->create ();
				
				$url = Gatuf_HTTP_URL_urlForView ('Encuesta_Views_Pregunta::verPregunta', array ($match[1], $match[2] + 1));
				return new Gatuf_HTTP_Response_Redirect ($url);
			}
		} else {
			$form = new $tipo->model (null, $extra);
		}
		
		return Gatuf_Shortcuts_RenderToResponse ('encuesta/pregunta/pregunta.html',
		                                         array ('page_title' => 'Pregunta',
		                                                'texto' => $esta_pregunta->nombre,
		                                                'form' => $form),
		                                         $request);
	}
}
