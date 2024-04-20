<?php
class Ajax extends Controlador
{
	function __construct()
	{
		parent::__construct();
		$this->verificarAcceso();
	}

	function existeUsuario()
	{
		$this->usuario = $this->modelo('Usuario');
		$retorno = array();
		$retorno['cod'] = 0;
		if ($this->usuario->existeUsuario($_POST['usuario'])) {
			$retorno['cod'] = 1;
		}
		echo json_encode($retorno);
	}
}
