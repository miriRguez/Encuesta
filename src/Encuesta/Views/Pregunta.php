<?php

Gatuf::loadFunction('Gatuf_Shortcuts_RenderToResponse');
Gatuf::loadFunction('Gatuf_HTTP_URL_urlForView');

class Encuesta_Views_Pregunta {
	public $verPregunta_precond = array ('Gatuf_Precondition::loginRequired');
	public function verPregunta ($request, $match) {
		if (!isset ($request->preguntas[$match[2]])) {
			return new Gatuf_HTTP_Response_NotFound($request);
		}
		
		$esta_pregunta = $request->preguntas[$match[2]];
		/* Hacer aquí la validación de si ya se contestó o no */
		
		$extra = array ('pregunta' => $esta_pregunta);
		if ($request->method == 'POST') {
			if ($esta_pregunta->tipo == 1) {
				$form = new Encuesta_Form_Pregunta_Radio ($request->POST, $extra);
			}
			
			if ($form->isValid ()) {
				$respuesta = $form->save ();
				
				$respuesta->usuario = $request->user->codigo;
				$respuesta->create ();
				
				$url = Gatuf_HTTP_URL_urlForView ('Encuesta_Views_Pregunta::verPregunta', $match[1], $match[2] + 1);
				return new Gatuf_HTTP_Response_Redirect ($url);
			}
		} else {
			if ($esta_pregunta->tipo == 1) {
				$form = new Encuesta_Form_Pregunta_Radio (null, $extra);
			}
		}
		
		return Gatuf_Shortcuts_RenderToResponse ('encuesta/pregunta/pregunta.html',
		                                         array ('page_title' => 'Pregunta',
		                                                'texto' => $esta_pregunta->nombre,
		                                                'form' => $form),
		                                         $request);
	}
}
