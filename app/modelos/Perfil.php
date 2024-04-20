<?php

/**
 * 
 */
class Perfil extends Base
{
	public function __construct()
	{
		parent::__construct('perfiles_usuarios', 'id');
	}

	public function agregar($nombre, $descripcion)
	{
		$retorno = false;
		$nombre = $this->escapar($nombre);
		$descripcion = $this->escapar($descripcion);
		$c = "INSERT INTO $this->tabla (nombre, descripcion, estado) VALUES ('$nombre', '$descripcion', '0')";
		if ($this->consultar($c)) {
			$retorno = true;
		}
		return $retorno;
	}

	public function modificar($id, $nombre, $descripcion)
	{
		$retorno = false;
		$id = $this->escapar($id);
		$nombre = $this->escapar($nombre);
		$descripcion = $this->escapar($descripcion);
		$c = "UPDATE $this->tabla SET nombre = '$nombre', descripcion = '$descripcion' WHERE $this->id = '$id' ";
		if ($this->consultar($c)) {
			$retorno = true;
		}
		return $retorno;
	}

	/* public function guardar()
	{
		$id = $this->escapar($this->id);
		$nombre = $this->escapar($this->nombre);
		$descripcion = $this->escapar($this->descripcion);
		if ($id != '') {
			$c = "UPDATE $this->tabla SET nombre = '$nombre', descripcion = '$descripcion' WHERE $this->id = '$id' ";
		} else {
			$c = "INSERT INTO $this->tabla (nombre, descripcion, estado) VALUES ('$nombre', '$descripcion', '0')";
		}
		return $this->consultar($c);
	} */

	public function eliminar($id)
	{
		$retorno = false;
		$id = $this->escapar($id);
		if ($id != '') {
			$c = "DELETE FROM $this->tabla WHERE $this->id = '$id' ";
			$this->consultar($c);
			if ($this->contarFilasAfectadas() > 0) {
				$retorno = true;
			} else {
			}
		}
		return $retorno;
	}

	public function buscarPerfil($id = '')
	{
		return $this->obtenerPorId($id);
	}

	public function listarPerfiles()
	{
		$this->consultar("SELECT id, nombre, estado FROM $this->tabla ");
		return $this->obtenerRegistros();
	}

	public function listarPermisos($id = '')
	{
		$this->consultar("SELECT * FROM menu_items mi WHERE EXISTS (SELECT id_menu_item FROM permisos_perfiles pp WHERE pp.id_perfil = '$id' AND mi.id = pp.id_menu_item)");
		return $this->obtenerRegistros();
	}

	public function listarPermisosRestantes($id = '')
	{
		$this->consultar("SELECT * FROM menu_items mi WHERE NOT EXISTS (SELECT id_menu_item FROM permisos_perfiles pp WHERE pp.id_perfil = '$id' AND mi.id = pp.id_menu_item)");
		return $this->obtenerRegistros();
	}

	public function agregarPermiso($id_perfil, $id_permiso)
	{
		$retorno = false;
		if (isset($id_perfil) && isset($id_permiso)) {
			$id_perfil = $this->escapar($id_perfil);
			$id_permiso = $this->escapar($id_permiso);
			$c = "INSERT INTO permisos_perfiles (id_perfil, id_menu_item) VALUES ('$id_perfil', '$id_permiso')";
			$retorno = $this->consultar($c);
		}
		return $retorno;
	}
	public function quitarPermiso($id_perfil, $id_permiso)
	{
		$retorno = false;
		if (isset($id_perfil) && isset($id_permiso)) {
			$id_perfil = $this->escapar($id_perfil);
			$id_permiso = $this->escapar($id_permiso);
			$c = "DELETE FROM permisos_perfiles WHERE id_perfil = '$id_perfil' AND id_menu_item = '$id_permiso' ";
			$retorno = $this->consultar($c);
		}
		return $retorno;
	}
}
