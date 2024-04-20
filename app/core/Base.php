<?php
class Base
{
	private $host = DB_HOST;
	private $usuario = DB_USUARIO;
	private $password = DB_PASSWORD;
	private $nombre_base = DB_NOMBRE;
	private $puerto = DB_PUERTO;

	private $dbh; //Database Handler

	protected $tabla;
	protected $id;
	private $resource;
	protected $attributes;

	//Al inicializar la variable creamos la conexion a la BD
	public function __construct($tabla = '', $id = 'id')
	{
		//Crear una instancia de MySQLi Orientado a Objetos
		$this->dbh = new mysqli($this->host, $this->usuario, $this->password, $this->nombre_base, $this->puerto);

		// verificar la conexión
		if ($this->dbh->connect_errno) {
			die('Connect Error: ' . $this->dbh->connect_errno);
		}

		// cambiar el conjunto de caracteres a utf8
		if (!$this->dbh->set_charset("utf8")) {
			printf("Error cargando el conjunto de caracteres utf8: %s\n", $this->dbh->error);
			exit();
		}

		//Asignar tabla Base
		$this->tabla = (string) $tabla;
		$this->id = (string) $id;
	}

	/**
	 * Escapa las variables para la BD
	 */
	public function escapar($variable)
	{
		return $this->dbh->real_escape_string(trim($variable));
	}

	/**
	 * Realizar una consulta a la BD
	 */
	public function consultar($sql)
	{
		$retorno = true;
		$this->resource = $this->dbh->query($sql);
		if ($this->resource === false) {
			$retorno = false;
		}
		return $retorno;
	}

	/**
	 * Realiza varias consultas en la BD
	 */
	public function consultarGrupo($sql)
	{
		$retorno = true;
		$respu = $this->dbh->multi_query($sql);
		if ($respu === false) {
			$retorno = false;
		}
		return $retorno;
	}

	/**
	 * Devuleve el ultimo id insertado en la base
	 */
	public function insertId()
	{
		return $this->dbh->insert_id;
	}

	/**
	 * Obtiene el ultimo error emitido en la Base de datos
	 */
	public function obtenerError()
	{
		return $this->dbh->error;
	}

	/**
	 * Obtiene el ultimo error en la base de datos
	 */
	public function obtenerErrorNro()
	{
		return $this->dbh->errno;
	}

	/**
	 * Devuelve la cantidad de filas de la ultima consulta en la BD
	 */
	public function contarFilas()
	{
		$retorno = 0;
		if ($this->resource) {
			$retorno = $this->resource->num_rows;
		}
		return $retorno;
	}

	/**
	 * Devuelve la cantidad de filas afectadas en las consultas INSERT, UPDATE, DELETE
	 */
	public function contarFilasAfectadas()
	{
		return $this->dbh->affected_rows;
	}

	/**
	 * Obtiene un registro de la consulta efectuada
	 * devuelve NULL en caso de no tener mas registros
	 */
	public function obtenerRegistro()
	{
		$retorno = array();
		if ($this->resource) {
			$retorno = $this->resource->fetch_assoc();
		}
		return $retorno;
	}

	/**
	 * Devuelve un array con todos los registros de la ultima consulta.
	 * devuelve un array vacio en caso de no tener ningun registro
	 */
	public function obtenerRegistros()
	{
		$retorno = array();
		while ($f = $this->obtenerRegistro()) {
			$retorno[] = $f;
		}
		return $retorno;
	}

	/** se refactorizo la funcion
	 * Devuelve un array con toda la tabla ordenado por el la columna clave ASC
	 * Devuelve un array vacio en caso de no tener registros
	 */
	public function listar()
	{
		$this->consultar("SELECT * FROM " . $this->tabla . " ORDER BY " . $this->id . " ASC ");
		$retorno = array();
		while ($f = $this->obtenerRegistro()) {
			$retorno[] = $f;
		}
		return $retorno;
	}

	/**
	 * Devuelve un registro buscando por id de tabla, se puede cambiar el nombre de la columna id de la misma con el segundo parametro;
	 * devuelve false en caso de no encontrar un registro
	 */
	public function obtenerPorId($id, $columna = '')
	{
		if ($columna == '') {
			$columna = $this->id;
		}
		$id = $this->escapar($id);
		$this->consultar("SELECT * FROM " . $this->tabla . " WHERE " . $columna . " = '" . $id . "' ");
		$retorno = array();
		if ($this->contarFilas() > 0) {
			if ($f = $this->obtenerRegistro()) {
				$retorno = $f;
			}
		} else {
			$retorno = false;
		}
		return $retorno;
	}

	/**
	 * Busca un registro por el nombre de columna y el valor seleccionado.
	 */
	public function buscar($columna, $id)
	{
		$columna = $this->escapar($columna);
		$id = $this->escapar($id);
		$this->consultar("SELECT * FROM " . $this->tabla . " WHERE $columna = '" . $id . "' ");
		$retorno = array();
		if ($this->contarFilas() > 0) {
			if ($f = $this->obtenerRegistro()) {
				$retorno = $f;
			}
		} else {
			$retorno = false;
		}
		return $retorno;
	}

	/**
	 * Empezar una transaccion en la BD
	 */
	public function iniciarTransaccion()
	{
		$this->dbh->begin_transaction(MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);
	}

	/**
	 * Finalizar la transaccion y guardar los cambios
	 */
	public function commit()
	{
		$this->dbh->commit();
	}

	public function aplicarFiltros($columna, $valor)
	{
		$retorno = '';
		if (!empty($columna)) {
			$retorno = "'" . $this->escapar($valor) . "'";
		}
		return $retorno;
	}

	public function guardar($objeto = array(), $id = null)
	{
		$retorno = array();
		$retorno['codigo'] = 0;
		$retorno['mensaje'] = '';
		$retorno['accion'] = '';
		$retorno['insert_id'] = 0;
		$columnas = array();
		$datos = array();

		if (is_null($id) && isset($objeto[$this->id])) {
			$id = $objeto[$this->id];
		}

		if ($id) {
			foreach ($objeto as $columna => $valor) {
				$datos[] = $columna . "=" . $this->aplicarFiltros($columna, $valor);
			}
			$consulta = "UPDATE " . $this->tabla . " SET " . implode(", ", $datos) . " WHERE " . $this->id . " = " . $id;
			$retorno['accion'] = 'update';
			//se agrega una auditoria general
		} else {
			foreach ($objeto as $columna => $valor) {
				$columnas[] = $columna;
				$datos[] = $this->aplicarFiltros($columna, $valor);
			}
			$consulta = "INSERT INTO " . $this->tabla . " (" . implode(", ", $columnas) . ") VALUES (" . implode(", ", $datos) . ")";
			$retorno['accion'] = 'insert';
		}
		$this->agregarAuditoria($objeto, $id, $retorno['accion']);
		if ($this->consultar($consulta)) {
			$retorno['codigo'] = 1;
			$retorno['mensaje'] = 'Consulta correcta';
			$retorno['insert_id'] = $this->insertId(); //retorno 0 en caso de no insertar
		} else {
			echo "Error en la consulta: " . $consulta;
			echo "<br>";
			echo "Error: " . $this->obtenerErrorNro() . " - " . $this->obtenerError();
			echo "<br>";
			exit();
		}
		return $retorno;
	}

	private function agregarAuditoria($objeto, $id, $accion = 'update')
	{
		$auditoria = array();
		$registro_anterior = $this->obtenerPorId($id);
		$auditoria_modelo = $this->modelo('Auditoria');
		$sesion = $this->modelo('Sesion');
		$id_usuario = $sesion->obtenerValor('id');
		$auditoria['id_usuario'] = $id_usuario;
		$auditoria['accion'] = $accion;
		$auditoria['tabla'] = $this->tabla;
		$auditoria['id_registro'] = $id;
		if(!empty($registro_anterior)){
			foreach ($registro_anterior as $key => $valor) {
				if (isset($objeto[$key]) && $valor != $objeto[$key]) {
					$auditoria['atributo'] = $key;
					$auditoria['valor_anterior'] = $valor;
					$auditoria['valor_actual'] = $objeto[$key];
					$auditoria_modelo->guardar($auditoria);
				}
			}
		}
	}

	public function modelo($modelo)
	{
		require_once RUTA_APP . '/modelos/' . $modelo . '.php';
		return new $modelo();
	}

	public function helper($helper)
	{
		require_once RUTA_APP . '/helpers/' . $helper . '.php';
	}

	public function insertar($objeto = array())
	{
		$retorno = array();
		$retorno['codigo'] = 0;
		$retorno['mensaje'] = '';
		$retorno['accion'] = 'insert';
		$retorno['insert_id'] = 0;
		$columnas = array();
		$datos = array();

		foreach ($objeto as $columna => $valor) {
			$columnas[] = $columna;
			$datos[] = "'" . $this->escapar($valor) . "'";
		}
		$consulta = "INSERT INTO " . $this->tabla . " (" . implode(", ", $columnas) . ") VALUES (" . implode(", ", $datos) . ")";

		if ($this->consultar($consulta)) {
			$retorno['codigo'] = 1;
			$retorno['mensaje'] = 'Consulta correcta';
			$retorno['insert_id'] = $this->insertId(); //retorno 0 en caso de no insertar
		} else {
			echo "Error en la consulta: " . $consulta;
			echo "<br>";
			echo "Error: " . $this->obtenerErrorNro() . " - " . $this->obtenerError();
			echo "<br>";
			exit();
		}
		return $retorno;
	}
}
