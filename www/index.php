<?php

require dirname(__FILE__).'/../src/Encuesta/conf/path.php';

# Cargar Gatuf
require 'Gatuf.php';

# Inicializar las configuraciones
Gatuf::start(dirname(__FILE__).'/../src/Encuesta/conf/encuesta.php');

Gatuf_Despachador::loadControllers(Gatuf::config('encuesta_views'));

Gatuf_Despachador::despachar(Gatuf_HTTP_URL::getAction());
