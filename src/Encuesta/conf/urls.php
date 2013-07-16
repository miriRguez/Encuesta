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
);


return $ctl;
