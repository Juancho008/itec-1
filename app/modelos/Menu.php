<?php

/**
 * Se encarga de agrega, modificar, eliminar y listar el menu
 */
class Menu extends Base
{
	function __construct()
	{
		parent::__construct('menu_items', 'id');
	}

	/**
	 * Lista los grupos del menu en orden
	 * Retorna: array con los elementos de los grupos del menu
	 */
	public function listarGrupos()
	{
		$this->consultar("SELECT * FROM menu_grupos ORDER BY orden ASC ");
		return $this->obtenerRegistros();
	}

	/**
	 * Buscar un grupo del menu por el id enviado
	 * Retorna: array con el item del menu
	 */
	public function buscarGrupo($id = '')
	{
		$id = $this->escapar($id);
		$this->consultar("SELECT * FROM menu_grupos WHERE id = '$id' ");
		return $this->obtenerRegistro();
	}

	/**
	 * Guarda un grupo, si id == "" INSERTA UN REGISTRO, sino lo MODIFICA
	 * Retorna: true en caso de eliminar o 
	 */
	public function guardarGrupo($id, $nombre, $orden, $img = '')
	{
		$retorno = false;
		if (empty($id)) {
			$this->consultar("INSERT INTO menu_grupos (nombre, orden, img) VALUES ('$nombre', '$orden', '$img') ");
		} else {
			$this->consultar("UPDATE menu_grupos SET nombre = '$nombre', orden = '$orden', img = '$img' WHERE id = '$id' ");
		}
		if ($this->contarFilasAfectadas() > 0) {
			$retorno = true;
		}
		return $retorno;
	}

	/**
	 * Elimina un grupo del menu por el id
	 * Retorna: true en caso de exito, false en caso de error
	 */
	public function eliminarGrupo($id)
	{
		$retorno = false;
		if (!empty($id)) {
			$this->consultar("DELETE FROM menu_grupos WHERE id = '$id' ");
		}
		if ($this->contarFilasAfectadas() > 0) {
			$retorno = true;
		}
		return $retorno;
	}

	/**
	 * Lista los items del menu, con el nombre de grupo al que pertenece
	 * Retorna: array con todos los registros
	 */
	public function listarItems()
	{
		$this->consultar("SELECT mi.*, mg.nombre as grupo FROM menu_items mi LEFT JOIN menu_grupos mg ON mi.id_grupo = mg.id ORDER BY mg.nombre , mi.nombre");
		return $this->obtenerRegistros();
	}

	/**
	 * Busca un item especifico por id
	 * Retorno: array con el registro
	 */
	public function buscarItem($id = '')
	{
		$id = $this->escapar($id);
		$this->consultar("SELECT * FROM menu_items WHERE id = '$id' ");
		return $this->obtenerRegistro();
	}

	/**
	 * Guarda un item, si id es vacio INSERTA, si id es diferente de MODIFICA el registro con el id 
	 * Retorna: true en caso de existo, false en caso de fallar
	 */
	public function guardarItem($id, $id_grupo, $nombre, $url, $orden = 0, $img = '')
	{
		$retorno = false;
		if (empty($id)) {
			$this->consultar("INSERT INTO menu_items (id_grupo, nombre, url, orden, img) VALUES ('$id_grupo', '$nombre', '$url', '$orden', '$img') ");
		} else {
			$this->consultar("UPDATE menu_items SET id_grupo = '$id_grupo', nombre = '$nombre', url = '$url', orden = '$orden', img = '$img' WHERE id = '$id' ");
		}
		if ($this->contarFilasAfectadas() > 0) {
			$retorno = true;
		}
		return $retorno;
	}

	/**
	 * Elimina un item del menu
	 * Retorna: true en caso de exito, false en caso de error.
	 */
	public function eliminarItem($id)
	{
		$retorno = false;
		if (!empty($id)) {
			$this->consultar("DELETE FROM menu_items WHERE id = '$id' ");
		}
		if ($this->contarFilasAfectadas() > 0) {
			$retorno = true;
		}
		return $retorno;
	}

	/**
	 * Carga el menu segun el perfil de usuario, si no se pasa valor carga todo el menu.
	 * Retorna: array['menu_grupos'] con todos los grupos del menu
	 * 			array['menu_items'] con los items del menu
	 */

	function obtenerMenu($id_perfil = '-1')
	{
		$parametros = $this->modelo('Parametro');
		$retorno = array();
		if ($id_perfil == $parametros->obtener('id_perfil_admin')) {
			$ci = "SELECT mi.*, mg.nombre as grupo_nombre, mg.img as grupo_img
				FROM menu_items mi
				LEFT JOIN menu_grupos mg on mi.id_grupo = mg.id
				ORDER BY mg.orden, mi.orden";
			$cg = "SELECT * 
				FROM menu_grupos mg 
				ORDER BY mg.orden";
		} else {
			$ci = "SELECT mi.*, mg.nombre as grupo_nombre, mg.img as grupo_img
				FROM menu_items mi
				LEFT JOIN menu_grupos mg on mi.id_grupo = mg.id
				INNER JOIN permisos_perfiles pp ON pp.id_menu_item = mi.id
				INNER JOIN perfiles_usuarios pu ON pu.id = pp.id_perfil
				WHERE pu.id = '" . $id_perfil . "'
				ORDER BY mg.orden, mi.orden ";
			$cg = "SELECT distinct(mg.id), mg.*
				FROM menu_grupos mg 
				LEFT JOIN menu_items mi ON mg.id = mi.id_grupo 
				INNER JOIN permisos_perfiles pp ON pp.id_menu_item = mi.id
				INNER JOIN perfiles_usuarios pu ON pu.id = pp.id_perfil
				WHERE pu.id = '" . $id_perfil . "'
				ORDER BY mg.orden ";
		}
		$this->consultar($ci);
		$retorno['menu_items'] = $this->obtenerRegistros();
		$this->consultar($cg);
		$retorno['menu_grupos'] = $this->obtenerRegistros();
		return $retorno;
	}

		/**
	 * Carga el menu segun el perfil de usuario, si no se pasa valor carga todo el menu.
	 * Retorna: array['menu_grupos'] con todos los grupos del menu
	 * 			array['menu_items'] con los items del menu
	 */

	function cargarMenuBK($id_perfil = '-1')
	{
		$parametros = $this->modelo('Parametro');
		$retorno = array();
		if ($id_perfil == $parametros->obtener('id_perfil_admin')) {
			$cg = "SELECT * 
				FROM menu_grupos mg 
				ORDER BY mg.orden";
			$ci = "SELECT *
				FROM menu_items mi
				ORDER BY mi.orden";
		} else {
			$cg = "SELECT distinct(mg.id), mg.*
				FROM menu_grupos mg 
				RIGHT JOIN menu_items mi ON mg.id = mi.id_grupo 
				INNER JOIN permisos_perfiles pp ON pp.id_menu_item = mi.id
				INNER JOIN perfiles_usuarios pu ON pu.id = pp.id_perfil
				WHERE pu.id = '" . $id_perfil . "'
				ORDER BY mg.orden ";
			$ci = "SELECT mi.* 
				FROM menu_items mi
				INNER JOIN permisos_perfiles pp ON pp.id_menu_item = mi.id
				INNER JOIN perfiles_usuarios pu ON pu.id = pp.id_perfil
				WHERE pu.id = '" . $id_perfil . "'
				ORDER BY mi.orden ";
		}
		$this->consultar($cg);
		$rg = $this->obtenerRegistros();
		if (count($rg) > 0) {
			for ($i = 0; $i < count($rg); $i++) {
				if ($rg[$i]['id'] == null) {
					$rg[$i]['id'] = 0;
				}
				$retorno['menu_grupos'][$rg[$i]['id']] = $rg[$i];
			}

			$this->consultar($ci);
			$ri = $this->obtenerRegistros();
			for ($i = 0; $i < count($ri); $i++) {
				if ($ri[$i]['id'] == null) {
					$ri[$i]['id'] = 0;
				}
				$retorno['menu_items'][$ri[$i]['id']] = $ri[$i];
			}
		}
		return $retorno;
	}

	/**
	 * Verifica que tenga el permiso para ese item del menu
	 * Retorna: true o false.
	 */
	public function verificarPermiso($id_perfil, $item = '')
	{
		$retorno = false;
		if ($id_perfil == '-1') {
			$retorno = true;
		} else {
			$c = "SELECT mg.nombre as grupo, 
						mg.img as grupo_img, 
						mi.* 
					FROM menu_grupos mg 
					RIGHT JOIN menu_items mi ON mg.id = mi.id_grupo 
					INNER JOIN permisos_perfiles pp ON pp.id_menu_item = mi.id
					INNER JOIN perfiles_usuarios pu ON pu.id = pp.id_perfil
					WHERE pu.id = '" . $id_perfil . "' AND mi.url = '$item'
					ORDER BY mg.orden, mi.orden ";
			$this->consultar($c);
			if ($this->contarFilas() > 0)
				$retorno = true;
		}
		return $retorno;
	}
}
