<?php

/**
 *  Clase que controla los usuarios
 */
class Usuarios extends Controlador
{

	function __construct($parametros = [])
	{

		parent::__construct(array('usuarios'));
		$this->verificarAcceso();
		$this->usuario = $this->modelo('Usuario');
		$this->login = $this->modelo('Login');
		$this->perfil = $this->modelo('Perfil');
		$this->municipio = $this->modelo('Municipio');
	}

	function index()
	{
		$this->listados();
	}

	/**
	 * Listado de todos los usuarios
	 */
	function listados($parametros = [])
	{
		$this->verificarPermiso('L');
		//$this->verificarModulo('Usuarios');
		$datos = [
			'usuarios' => $this->usuario->listarUsuariosPerfiles()
		];
		$this->header();
		$this->vista('paginas/usuarios/listado', $datos);
		$this->footer();
	}

	/**
	 * Vista para ver o modificar un usuario
	 */
	function nuevo($parametros = [])
	{
		$this->verificarPermiso('L');
		//$this->verificarModulo('Usuarios');
		$parametros[0] = isset($parametros[0]) ? $parametros[0] : 0;
		$datos = [
			'usuario' => $this->usuario->buscarUsuario($parametros[0]),
			'perfiles' => $this->perfil->listarPerfiles(),
            'municipios' => $this->municipio->listar()
        ];
		$this->header();
		$this->vista('paginas/usuarios/nuevo', $datos);
		$this->footer();
	}

	function clave()
	{
		$this->verificarPermiso('M');
		$datos = [
			'usuario' => $this->login->obtenerUsuario(),
			'perfiles' => $this->perfil->listarPerfiles()
		];
		$this->header();
		$this->vista('paginas/usuarios/cambiar_clave', $datos);
		$this->footer();
	}


	/**
	 * Cambia la clave del usuario Logueado
	 */
	function cambiarClave()
	{
		$this->verificarPermiso('M');
		if (isset($_POST) && isset($_POST['clave_actual']) && isset($_POST['clave'])) {
			$retorno = array();
			$usuario = $this->login->obtenerUsuario();
			if ($this->login->verificarClave($_POST['clave_actual'], $usuario['clave'])) {
				if ($this->usuario->cambiarClave($usuario['id'], $_POST['clave'])) {
					$retorno['msj'] = "Clave modificada correctamente";
				} else {
					$retorno['msj'] = "ERROR AL MODIFICAR LA CLAVE";
				}
			} else {
				$retorno['msj'] = "Clave actual ingresada es incorrecta";
			}
			echo "<script> alert('" . $retorno['msj'] . "');</script>";
		}
		$this->redir('Usuarios');
	}

	function misDatos()
	{
		$this->verificarPermiso('L');
		$datos = [
			'usuario' => $this->login->obtenerUsuario(),
			'perfiles' => $this->perfil->listarPerfiles()
		];
		$this->header();
		$this->vista('paginas/usuarios/mis_datos', $datos);
		$this->footer();
	}

	/**
	 * Actualiza los datos del usuario logueado
	 */
	function actualizarMisDatos($parametros = [])
	{
		$this->verificarPermiso('M');
		if (isset($_POST)) {
			$retorno = array();
			$usuario = $this->login->obtenerUsuario();
			if (count($usuario) > 0) {
				$us_guardar = array();
				$us_guardar['id'] = $usuario['id'];
				$us_guardar['nombre'] = $_POST['nombre'];
				$us_guardar['apellido'] = $_POST['apellido'];
				$us_guardar['email'] = $_POST['correo'];
				$us_guardar['telefono'] = $_POST['telefono'];
				if ($this->usuario->guardar($us_guardar, $us_guardar['id'])) {
					$retorno['msj'] = "Datos Personales modificados correctamente";
				} else {
					$retorno['msj'] = "No se pudo modificar los datos";
				}
				echo "<script> alert('" . $retorno['msj'] . "');</script>";
			}
		}
		$this->redir('Usuarios');
	}

	function guardar()
	{
		$this->verificarPermiso('A');
		$retorno = [];
		$retorno['cod'] = 0;
		$retorno['msj'] = 'Iniciando';
		if (isset($_POST['id']) && isset($_POST['usuario'])) {
			$u = null;
			//$this->usuario = $this->modelo('Usuario');
			$resultado = array();
			$usuario = array();
			$u = null;

			if ($_POST['id'] != '') {
				$u = $this->usuario->buscarUsuario($_POST['id']);
				$usuario['id'] = $_POST['id'];
			}
			if (!empty($_POST['clave'])) {
				$usuario['clave'] = $_POST['clave'];
			}
			$usuario['nombre'] = $_POST['nombre'];
			$usuario['apellido'] = $_POST['apellido'];
			$usuario['id_perfil'] = $_POST['id_perfil'];
			$usuario['usuario'] = $_POST['usuario'];
			$usuario['email'] = $_POST['email'];
			$usuario['telefono'] = $_POST['telefono'];
			$usuario['dni'] = $_POST['documento'];
			$usuario['id_municipio'] = $_POST['id_municipio'];

			$resultado = false;
			if ($u != null) {
				$resultado = $this->usuario->guardar($usuario);
				$retorno['msj'] = "Registro Modificado correctamente.";
			} else {
				if ($this->usuario->existeUsuario($_POST['usuario'])) {
					$retorno['msj'] = "El nombre de usuario se encuentra registrado.";
				} else {
					unset($usuario['id']);
					$resultado = $this->usuario->guardar($usuario);
					$retorno['msj'] = "Registro agregado correctamente.";
				}
			}

			if (!$resultado) {
				$retorno['msj'] = "Error al guardar el registro." . $this->usuario->obtenerError();
			}

			$this->alerta($retorno['msj']);
		}
		$this->redir('Usuarios');
	}

	public function eliminar($parametros = [])
	{
		if ($this->usuario->baja($parametros[0])) {
			$retorno['cod'] = 1;
			$retorno['msj'] = "Usuario dado de BAJA correctamente.";
		} else {
			$retorno['cod'] = -1;
			$retorno['msj'] = "Error en la operacion.";
		}
		$this->alerta($retorno['msj']);
		$this->redir('Usuarios');
	}

	public function alta($parametros = [])
	{
		if ($this->usuario->alta($parametros[0])) {
			$retorno['cod'] = 1;
			$retorno['msj'] = "Usuario dado de ALTA correctamente.";
		} else {
			$retorno['cod'] = -1;
			$retorno['msj'] = "Error en la operacion.";
		}
		$this->alerta($retorno['msj']);
		$this->redir('Usuarios');
	}

	public function buscar()
	{
		if (isset($_POST)) {
			$usuario = ($_POST['campo'] == 'documento') ? $this->usuario->buscarPorDni($_POST['dato']) : $this->usuario->buscarPorNombreDeUsuario($_POST['dato']);
		} else {
			$usuario = null;
		}
		echo json_encode($usuario);
	}
}
