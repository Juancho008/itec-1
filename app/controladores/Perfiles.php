<?php

/**
 * 
 */
class Perfiles extends Controlador
{
	function __construct()
	{
		parent::__construct(array('perfiles'));
		$this->verificarAcceso();

		$this->perfil = $this->modelo('Perfil');
	}

	function index()
	{
		$this->listados();
	}

	function nuevo($parametros = [])
	{
		$this->verificarPermiso('L');
		$p = null;
		if (isset($parametros[0]))
			$p = $this->perfil->buscarPerfil($parametros[0]);
		$datos = [
			'perfil' => []
		];
		if (!empty($p))
			$datos['perfil'] = $p;
		$this->header();
		$this->vista('paginas/perfiles/nuevo', $datos);
		$this->footer();
	}

	function listados($parametros = [])
	{
		$this->verificarPermiso('L');
		$datos = [
			'perfiles' => $this->perfil->listarPerfiles()
		];
		$this->header();
		$this->vista('paginas/perfiles/listado', $datos);
		$this->footer();
	}

	function permisos($parametros = [])
	{
		$this->verificarPermiso('L');
		$p = null;
		if (isset($parametros[0]))
			$p = $this->perfil->buscarPerfil($parametros[0]);
		if ($p != null) {
			$datos = [
				'parametros' => $parametros,
				'perfil' => $this->perfil->buscarPerfil($parametros[0]),
				'permisos_obtenidos' => $this->perfil->listarPermisos($parametros[0]),
				'permisos' => $this->perfil->listarPermisosRestantes($parametros[0])
			];
			$this->header();
			$this->vista('paginas/perfiles/permisos', $datos);
			$this->footer();
		} else {
			$this->redir('Perfiles');
		}
	}

	function moverPermisos()
	{
		$this->verificarPermiso('M');
		$retorno = [];
		$retorno['cod'] = 0;
		$retorno['msj'] = 'Iniciando';
		if (isset($_POST)) {
			$retorno['msj'] = "no tiene valores";
			if (isset($_POST['menu_item']) && @count($_POST['menu_item']) > 0 && isset($_POST['perfil']) && $_POST['perfil'] != '' && isset($_POST['movimiento']) && $_POST['movimiento'] != '') {
				if ($_POST['movimiento'] == 'permisos_obtenidos') {
					$this->perfil->agregarPermiso($_POST['perfil'], $_POST['menu_item']);
					$retorno['msj'] = "Agregado correctmante.";
				} else {
					$this->perfil->quitarPermiso($_POST['perfil'], $_POST['menu_item']);
					$retorno['msj'] = "eliminado correctmante.";
				}
			}
		}
		echo json_encode($retorno);
	}


	function guardar()
	{
		$this->verificarPermiso('A');
		$retorno = [];
		$retorno['cod'] = 0;
		$retorno['msj'] = 'Iniciando';
		if (isset($_POST['nombre']) && $_POST['nombre'] != '') {
			if (empty($_POST['id'])) {
				$respuesta = $this->perfil->agregar($_POST['nombre'], $_POST['descripcion']);
				$retorno['msj'] = "Registro agregado correctamente.";
			} else {
				$respuesta = $this->perfil->modificar($_POST['id'], $_POST['nombre'], $_POST['descripcion']);
				$retorno['msj'] = "Registro Modificado correctamente.";
			}

			if (!$respuesta) {
				$retorno['msj'] = $this->perfil->obtenerError();
			}
			$this->alerta($retorno['msj']);
		}
		$this->redir('Perfiles');
	}

	public function eliminar($parametros = [])
	{
		$this->verificarPermiso('B');
		if ($this->perfil->eliminar($parametros[0])) {
			$retorno['cod'] = 1;
			$retorno['msj'] = "Eliminado correctamente.";
			$this->alerta($retorno['msj']);
		} else {
			$retorno['cod'] = -1;
			$retorno['msj'] = $this->perfil->obtenerError();
			$this->alerta($retorno['msj']);
		}
		$this->redir('Perfiles');
	}
}
