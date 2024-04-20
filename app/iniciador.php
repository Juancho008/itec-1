<?php

//Cargamos las librerias
require_once 'config/configurar.php';

//Autoload php
spl_autoload_register(function ($nombre_clase) {
	if (file_exists(RUTA_APP.'/core/' . $nombre_clase . '.php')) {
		require_once RUTA_APP.'/core/' . $nombre_clase . '.php';
	} else if (file_exists(RUTA_APP.'/controladores/' . $nombre_clase . '.php')) {
		require_once RUTA_APP.'/controladores/' . $nombre_clase . '.php';
	} else {
		echo "no se encontro la clase " . $nombre_clase;
		exit();
	}
});
