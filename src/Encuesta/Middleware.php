<?php

class Encuesta_Middleware {
	public function process_request (&$request) {
		$match = array();
		if (preg_match ('#^/cuestionarios/(\d+)/?#', $request->query, $match)) {
			$request->cuestionario = new Encuesta_Cuestionario ();
			
			if (false === ($request->cuestionario->getCuestionario ($match[1]))) {
				return new Gatuf_HTTP_Response_NotFound($request);
			}
			
			$sql = new Gatuf_SQL ('cuestionario=%s', $request->cuestionario->id);
			$preguntas = Gatuf::factory ('Encuesta_Pregunta')->getList (array ('filter' => $sql->gen ()));
			
			$request->preguntas = array ();
			$g = 0;
			foreach ($preguntas as $preg) {
				$request->preguntas[$g] = $preg;
				$preg->numero_local = $g;
				$g++;
			}
		}
		
		return false;
	}
}
