<?php

/**
 * En este archivo se crearan todas las constantes del sistema.
 * todas las constantes deben estar definidas EN MAYUSCULAS
 * Aca se debe defin como constantes a utilizar las carpetas de archivos adjuntos, expedientes, otras referencias estaticas
 * RECORDAR: No debe existir referencia a una carpeta que no sea una CONSTANTE predefinida.
 */

//Configuracion de acceso a la BD
define('DB_HOST', 'localhost');
define('DB_PUERTO', '3306');
define('DB_USUARIO', 'root');
define('DB_PASSWORD', '');
define('DB_NOMBRE', 'mvcbase');

//Ruta de la aplicación
define('RUTA_APP', dirname(dirname(__FILE__)));
define('RUTA_PUBLIC', dirname(dirname(dirname(__FILE__))) . '/public');

$protocol = 'https://';
if (!isset($_SERVER['HTTPS']) || empty($_SERVER['HTTPS'])) {
    $protocol = 'http://';
}

$servidor = $_SERVER["SERVER_NAME"] . ':' . $_SERVER['SERVER_PORT'];

/**
 * Se utiliza para las referencias a las llamadas de todos los archivos
 */
define('RUTA_URL', $protocol . $servidor . '/mvcbase');
//Definir las carpetas a utilizar de las librerias a incorporar
define('URL_LIB', RUTA_URL . '/lib');
define('URL_JS', RUTA_URL . '/public/js');
define('URL_CSS', RUTA_URL . '/public/css');
define('URL_IMG', RUTA_URL . '/public/img');
define('URL_COMPONENTES', RUTA_URL . '/vendor/components');

//Definir dominio para las cookies
define('DOMINIO', 'mvcbase.sistema.com');

//Pagina de inicio de la Web o del sistema.
define('PAGINA_INICIO', $protocol . $servidor . '/');

// Variable para el title de la pagina general
define('NOMBRESITIO', 'Sistema Base');
define('TITULO_SISTEMA', 'Sistema Base');

//Variable para manejar en que version del softwawre se encuentra
define('AUTOR', 'CRM Developers');
define('VERSIONDELSOFTWARE', '0.7.0');

//nombre del sistema para el manejo de sesiones, debe ser nombre unico por sistema, para que no compartan sesiones
define('NOMBRESISTEMA', 'basemvc'); //esta variable no puede llevar .
define('SECURE', false);

//Cantidad de segundos que puede estar inactivo
define('MAX_IDLE_TIME', 144000);

//Hash que define como se comprueban y se guardan las claves
define('HASH_CLAVE', PASSWORD_DEFAULT);

//CONFIGURACION PARA ENVIAR CORREOS
define('MAILER_HOST', 'tuhost.com');
define('MAILER_PUERTO', '465');
define('MAILER_USUARIO', 'no-reply@tuhost.com.ar');
define('MAILER_CLAVE', '3x@7bW');

//Datos a utilizar para pruebas o debug
define('DEBUG', true);
define('ENVIAR_CORREOS', true);

if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}
