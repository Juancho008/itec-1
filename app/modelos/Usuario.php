<?php

/**
 * Gestionar los datos de los usuarios
 */
class Usuario extends Base
{
	public function __construct()
	{
		parent::__construct('usuarios', 'id');
		$this->tabla_perfiles = 'perfiles_usuarios';
	}

	/**
	 * Busca un usuario por id
	 * Retorna: array con el registro de usuario
	 */
	public function buscarUsuario($id = '')
	{
		$dato = $this->obtenerPorId($id);
		return $dato;
	}

	/**
	 * Verifica si existe el nombre de usuario
	 * Retorna: true o false;
	 */
	public function existeUsuario($usuario)
	{
		$retorno = false;
		$usuario = $this->escapar($usuario);
		$this->consultar("SELECT id FROM $this->tabla WHERE usuario = '$usuario' ");
		if ($this->contarFilas() > 0) {
			$retorno = true;
		}
		return $retorno;
	}

	/**
	 * Obtiene todos los permisos por un perfil seleccionado
	 */
	public function obtenerPermisos($id_perfil){
		$id_perfil = $this->escapar($id_perfil);
		$this->consultar("SELECT * FROM perfiles_permisos pp WHERE id_perfil = $id_perfil ");
		return $this->obtenerRegistros();
	}

	/**
	 * Lista todos los usuarios del sistema
	 * Retorna: array con los usuarios del sistema
	 */
	public function listarUsuarios()
	{
		$this->consultar("SELECT * FROM $this->tabla ");
		return $this->obtenerRegistros();
	}

	/**
	 * Lista todos los usuarios y sus perfiles
	 * Retorna: array con los perfiles de usuarios
	 */
	public function listarUsuariosPerfiles()
	{
		$this->consultar("SELECT us.*, pu.nombre as perfil, pu.descripcion as perfil_descripcion FROM $this->tabla us INNER JOIN $this->tabla_perfiles pu ON us.id_perfil = pu.id ");
		return $this->obtenerRegistros();
	}

	/**
	 * Cambia la clave del usuario seleccionado por id
	 * retorna true o false.
	 */
	function cambiarClave($id, $clave)
	{
		$retorno = false;
		$id = $this->escapar($id);
		$clave = $this->escapar($clave);
		if ($clave != '' && $id != '') {
			$login = $this->modelo('Login');
			$clave = $this->escapar($clave);
			$clave = $this->login->encriptarClave($clave);
			$retorno = $this->consultar("UPDATE $this->tabla SET clave = '$clave' WHERE $this->id = '$id' ");
		}
		return $retorno;
	}

	/**
	 * Da de baja Logica un usuario || estado = 1
	 */
	public function baja($id)
	{
		$retorno = false;
		$id = $this->escapar($id);
		if ($id != '') {
			$c = "UPDATE $this->tabla SET estado = 1 WHERE $this->id = '$id' ";
			$this->consultar($c);
			if ($this->contarFilasAfectadas() > 0)
				$retorno = true;
		}
		return $retorno;
	}

	/**
	 * Da de Alta logica un usuario || estado = 0
	 */
	public function alta($id)
	{
		$retorno = false;
		$id = $this->escapar($id);
		if ($id != '') {
			$c = "UPDATE $this->tabla SET estado = 0 WHERE $this->id = '$id' ";
			$this->consultar($c);
			if ($this->contarFilasAfectadas() > 0)
				$retorno = true;
		}
		return $retorno;
	}

	public function auditoriaUsuario($id)
	{
		$retorno = false;
		if ($this->consultar("UPDATE usuarios SET hora_ultimo_acceso = '" . date('Y-m-d H:i:s') . "', cantidad_acceso = cantidad_acceso + 1 WHERE id = '$id' ")) {
			$retorno = true;
		}
		return $retorno;
	}

	public function aplicarFiltros($columna, $valor)
	{
		$login = $this->modelo('Login');
		$retorno = '';
		switch ($columna) {
			case 'clave':
				$retorno = "'" . $login->encriptarClave($valor) . "'";
				break;
			case 'id_municipio':
				if($valor==''){
					$valor=0;
				}
				$retorno = "'" . $valor . "'";
				break;
			default:
				$retorno = "'" . $this->escapar($valor) . "'";
				break;
		}
		return $retorno;
	}

	public function buscarPorDni($dni)
	{
		$dni = $this->escapar($dni);
		$this->consultar("SELECT * from $this->tabla where dni={$dni}");
		return $this->obtenerRegistro();
	}

	public function buscarPorNombreDeUsuario($nombre)
	{
		$nombre = $this->escapar($nombre);
		$this->consultar("SELECT * from $this->tabla where usuario='$nombre'");
		return $this->obtenerRegistro();
	}
}
