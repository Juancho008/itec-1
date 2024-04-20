<?php

/**
 * Gestionar los parametros del sistema
 */
class Parametro extends Base
{
	public function __construct()
	{
		parent::__construct('parametros', 'clave');
	}

	public function existe($clave)
	{
		$retorno = false;
		if ($this->obtenerPorId($clave) !== false) {
			$retorno = true;
		}
		return $retorno;
	}

	/**
	 * Agrega un parametro a la BD
	 * $clave: unica por parametro
	 * $valor: valor del parametro
	 * Retorna: true en caso de exito, false en caso de error
	 */
	public function agregar($clave, $valor)
	{
		$retorno = false;
		$clave = $this->escapar($clave);
		$valor = $this->escapar($valor);
		$c = "INSERT INTO $this->tabla (clave, valor) VALUES ('$clave', '$valor')";
		if ($this->consultar($c)) {
			$retorno = true;
		}
		return $retorno;
	}

	/**
	 * Modifica un parametro a la BD
	 * $clave: unica por parametro
	 * $valor: valor del parametro
	 * Retorna: true en caso de exito, false en caso de error
	 */
	public function modificar($clave, $valor)
	{
		$retorno = false;
		$clave = $this->escapar($clave);
		$valor = $this->escapar($valor);
		$c = "UPDATE $this->tabla SET valor = '$valor' WHERE clave = '$clave' ";
		if ($this->consultar($c)) {
			$retorno = true;
		}
		return $retorno;
	}

	/**
	 * Elimina un parametro de la BD por la clave
	 * Retorna: true en caso de exito, false en caso de error
	 */
	public function eliminar($clave)
	{
		$retorno = false;
		$clave = $this->escapar($clave);
		if ($clave != '') {
			$c = "DELETE FROM $this->tabla WHERE $this->id = '$clave' ";
			$this->consultar($c);
			if ($this->contarFilasAfectadas() > 0) {
				$retorno = true;
			} else {
			}
		}
		return $retorno;
	}

	/**
	 * Obtener un paramtro de la base
	 */
	public function obtener($clave){
		$parametro = $this->buscar('clave', $clave);
		return $parametro['valor'];
	}

	/**
	 * Lista todos los parametros del sistema
	 * Retorna: array con los parametros ['clave', 'valor']
	 */
	public function listar()
	{
		$this->consultar("SELECT clave, valor FROM $this->tabla ");
		return $this->obtenerRegistros();
	}

    public function obtenerClasesCss()
    {
        $this->consultar("select * from estados");
        return $this->obtenerRegistros();
    }

    public function obtenerClaseCss($clase)
    {
        $css = $this->escapar($clase);
        $this->consultar("select * from estados where css_class = '$css'");
        return $this->obtenerRegistro();
    }
}
