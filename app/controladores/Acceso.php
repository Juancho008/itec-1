<?php

/**
 * Clase que se encarga de gestionar el acceso al sistema
 */
class Acceso extends Controlador
{
	function __construct()
	{
		$this->sesion = $this->modelo('Sesion');
		$this->login = $this->modelo('Login');
		$this->usuario = $this->modelo('Usuario');
	}

	function index()
	{
		$usuario = $this->usuario->buscar('id', 1);
		if(empty($usuario)){
			$this->usuario->guardar([
				'usuario' => 'admin',
				'clave' => 'admin',
				'apellido' => 'Administrador',
				'nombre' => 'Usuario',
				'email' => '',
				'id_perfil' => 1
			]);
		}
		if (!$this->login->verificarLogin()) {
			$this->vista('paginas/login');
		} else {
			$this->redir();
		}
		exit();
	}

	function ingresar()
	{
		if (isset($_POST)) {
			$usuario = '';
			if (isset($_POST['usuario'])) {
				$usuario = $_POST['usuario'];
			}
			$clave = '';
			if (isset($_POST['clave'])) {
				$clave = $_POST['clave'];
			}
			if ($this->login->iniciarSesion($usuario, $clave)) {
				$this->sesion->iniciar();
				$this->redir();
			} else {
				echo "<script> alert('Usuario y/o Contraseña Incorrectos.');</script>";
			}
		}
		$this->redir('Acceso');
	}

	function salir()
	{
		$this->sesion->terminar();
		$this->redir('Acceso');
	}
}
