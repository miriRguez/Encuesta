<?php
$base = Gatuf::config('encuesta_base');
$ctl = array ();

/* Bloque base:
$ctl[] = array (
	'regex' => '#^/ /$#',
	'base' => $base,
	'model' => 'Encuesta_',
	'method' => '',
);
*/

$ctl[] = array (
	'regex' => '#^/$#',
	'base' => $base,
	'model' => 'Encuesta_Views',
	'method' => 'index',
	'name' => 'login_view'
);

$ctl[] = array (
	'regex' => '#^/logout/$#',
	'base' => $base,
	'model' => 'Encuesta_Views',
	'method' => 'logout'
);

$ctl[] = array (
	'regex' => '#^/cuestionarios/$#',
	'base' => $base,
	'model' => 'Encuesta_Views_Cuestionario',
	'method' => 'index',
);

$ctl[] = array (
	'regex' => '#^/cuestionarios/(\d+)/(\d+)/$#',
	'base' => $base,
	'model' => 'Encuesta_Views_Pregunta',
	'method' => 'verPregunta',
);

return $ctl;
