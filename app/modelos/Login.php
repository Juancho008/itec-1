<?php

/**
 * Modelo que se encaga del Logueo de los usuarios
 */
class Login extends Base
{
	public function __construct()
	{
		parent::__construct('usuarios', 'id');
		$this->sesion = Controlador::modelo('Sesion');
		$this->usuario = Controlador::modelo('Usuario');
	}

	/**
	 * Obtiene el usuario logueado
	 */
	public function obtenerUsuario()
	{
		$this->consultar("SELECT * FROM " . $this->tabla . " WHERE " . $this->id . " = '" . $this->sesion->obtenerValor('id') . "'");
		return $this->obtenerRegistro();
	}

	/**
	 * Verifica el usuario y la clave sean correcta
	 */
	public function verificarUsuario($usuario, $clave, $email = '', $dni = '')
	{
		$retorno = false;
		$usuario = $this->escapar($usuario);
		$c = "SELECT * FROM " . $this->tabla . " WHERE usuario = '$usuario' ";
		$this->consultar($c);
		$f = $this->obtenerRegistro();
		if ($this->verificarClave($clave, $f['clave'])) {
			$retorno = true;
		}
		return $retorno;
	}

	public function encriptarClave($clave)
	{
		return md5($clave);
	}

	public function verificarClave($clave, $clave_encriptada)
	{
		return md5($clave) === $clave_encriptada;
	}

	/**
	 * verifica que el usuario y la clave sean correctos y guarda los valores de inicio en la sesion
	 */
	public function iniciarSesion($usuario = '', $clave = '')
	{
		$retorno = false;
		if ($this->verificarUsuario($usuario, $clave)) {
			$f = $this->usuario->buscar('usuario', $usuario);
			$this->usuario->auditoriaUsuario($f['id']);
			$permisos = $this->usuario->obtenerPermisos($f['id_perfil']);
			$sesion = new Sesion();
			$sesion->agregar('logged', true);
			$sesion->agregar('id', $f['id']);
			$sesion->agregar('usuario', $f['usuario']);
			$sesion->agregar('email', $f['email']);
			$sesion->agregar('apellido', $f['apellido']);
			$sesion->agregar('nombre', $f['nombre']);
			$sesion->agregar('id_perfil', $f['id_perfil']);
			$sesion->agregar('permisos', $permisos);
			$sesion->agregar('dni', $f['dni']);
			$sesion->agregar('imagen', ($f['imagen'] != '') ? $f['imagen'] : 'fotos_perfil/foto_vacia.jpg');
			$sesion->agregar('timeout_idle', time() + MAX_IDLE_TIME);
			$retorno = true;
		}
		return $retorno;
	}

	/**
	 * Verifica que esta logueado el usuario
	 * Retorna: true o false
	 */
	public function verificarLogin()
	{
		require_once 'Sesion.php';
		$sesion = new Sesion();
		$retorno = false;
		//print_r($sesion->obtenerSesion());
		//die;
		if ($sesion->obtenerValor('logged') != null && $sesion->obtenerValor('logged') === true) {
			$retorno = true;
		}
		return $retorno;
	}
}
