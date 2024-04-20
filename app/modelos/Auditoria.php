<?php

class Auditoria extends Base
{
    public function __construct()
    {
        parent::__construct('auditoria_general', 'id_auditoria');
    }

    public function guardar($objeto = array(), $id = null){
        foreach ($objeto as $columna => $valor) {
            $columnas[] = $columna;
            $datos[] = $this->aplicarFiltros($columna, $valor);
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
			die;
			exit();
		}
    }
}
