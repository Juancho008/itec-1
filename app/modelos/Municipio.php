<?php


class Municipio extends Base
{

    public function __construct($base = 'municipios', $id = 'id')
    {
        parent::__construct($base, $id);
    }

    public function guardar($objeto = array(), $id = null)
    {
        $resultado = array();
        if (is_null($id)) {
            $municipio = $this->obtenerPorId($objeto[$this->id]);
        } else {
            $municipio = $this->obtenerPorId($id);
        }
        if (!$municipio) {
            $resultado = parent::insertar($objeto);
        } else {
            $resultado = parent::guardar($objeto);
        }
        return $resultado;
    }

    /**
     * Trae un municipio por su id
     */
    public function buscarPorId($id)
    {
        return $this->obtenerPorId($id);
    }

    public function listar()
    {
        $this->consultar("SELECT mun.* 
            FROM " . $this->tabla . " mun 
            ORDER BY mun.nombre ASC");
        return $this->obtenerRegistros();
    }

    /**
     * Obtiene todos los municipios que se estan usando actualmente
     */
    public function obtenerActivos()
    {
        $this->consultar("SELECT *
                    FROM " . $this->tabla . "
                    WHERE id_municipio != 0
                    ORDER BY nombre ASC ");

        return $this->obtenerRegistros();
    }

    public function obtenerEstado($ids_municipios = [], $id_muestra = 0, $estado = "true")
    {
        //Estado = true devuelve las completas, false devuelve pendientes
        $retorno = array();
        $id_muestra = $this->escapar($id_muestra);

        $status = ' HAVING total - cargadas > 0 ';
        if ($estado == "true") {
            $status = ' HAVING total - cargadas <= 0  ';
        }

        //Debo filtrar por municipio por las planillas_digitales
        foreach ($ids_municipios as $id_municipio) {
            //id, nombre, cargadas, totales

            //Retorna 1 row: id_municipio, nombre
            $this->consultar("SELECT id_municipio, nombre 
                                        FROM municipios 
                                        WHERE id_municipio = $id_municipio");
            $municipio = $this->obtenerRegistro();

            //Retorna 1 row: total
            $this->consultar("SELECT SUM(me2.cantidad) AS total 
                                        FROM muestras_escuelas me2 
                                        INNER JOIN escuelas e2 
                                          ON e2.id = me2.id_escuela
                                        WHERE e2.municipio_id = $id_municipio 
                                          AND id_muestra = $id_muestra");
            $res = $this->obtenerRegistro();
            $municipio['total'] = intval($res['total']);

            //Retorna 1 row: municipio_id, cargadas_bk, cargadas
            $this->consultar("SELECT e.municipio_id, 
                                        sum(me.cantidad) AS cargadas_bk, 
                                        count(distinct pd.id) AS cargadas 
                                        FROM muestras_escuelas me 
                                        LEFT JOIN planillas_digitales pd 
                                          ON me.id_escuela = pd.id_escuela
                                        INNER JOIN escuelas e 
                                          ON e.id = pd.id_escuela
                                        INNER JOIN mesas m 
                                          ON m.id = pd.nromesa
                                        WHERE pd.estado = 3 
                                          AND (pd.muestras = '$id_muestra' 
                                            XOR pd.muestras LIKE '$id_muestra|%' 
                                            XOR pd.muestras LIKE '%|$id_muestra' 
                                            XOR pd.muestras LIKE '%|$id_muestra|%') 
                                          AND m.publicada = 1 
                                          AND e.municipio_id = $id_municipio 
                                          AND me.id_muestra = $id_muestra
                                        GROUP BY e.municipio_id");
            $res = $this->obtenerRegistro();

            $municipio['cargadas'] = 0;
            if ($this->contarFilas()) {
                $municipio['cargadas'] = intval($res['cargadas']);
            }

            if ($estado == "true" && $municipio['total'] <= $municipio['cargadas']) {
                if ($municipio['total'] < $municipio['cargadas']) {
                    $municipio['cargadas'] = $municipio['total'];
                }
                $retorno[] = $municipio;
            }

            if ($estado == "false" && $municipio['total'] > $municipio['cargadas']) {
                $retorno[] = $municipio;
            }
        }

        return $retorno;
    }

    public function obtenerEstadoDetallado($id_municipio = [], $id_muestra = 0, $loc_completas = "true")
    {
        $status = ' HAVING total - cargadas > 0 ';
        if ($loc_completas == "true") {
            $status = ' HAVING total - cargadas <= 0  ';
        }
        $id_muestra = $this->escapar($id_muestra);
        $id_municipio = $this->escapar($id_municipio);

        $this->consultar("SELECT mun.id_municipio, mun.nombre, e.circuito, 
                SUM(me.cantidad-me.actual) AS cargadas,
                (SELECT SUM(me2.cantidad) AS total 
                    FROM muestras_escuelas me2 
                    INNER JOIN escuelas e2 
                        ON e2.id = me2.id_escuela
                    WHERE e2.municipio_id = e.municipio_id 
                      AND id_muestra = $id_muestra 
                      AND e2.circuito = e.circuito) AS total
                FROM muestras_escuelas me 
                INNER JOIN escuelas e 
                  ON e.id = me.id_escuela
                INNER JOIN municipios mun 
                  ON mun.id_municipio = e.municipio_id
                WHERE e.municipio_id = $id_municipio 
                  AND me.id_muestra = $id_muestra
                GROUP BY mun.id_municipio, mun.nombre, e.circuito
                $status");

        $municipio = $this->obtenerRegistros();

        if ($municipio['total'] < $municipio['cargadas']) {
            $municipio['cargadas'] = $municipio['total'];
        }

        return $municipio;
    }

    public function getEstado($id_municipio, $id_muestra, $estado = "true")
    {
        //Estado = true devuelve las completas, false devuelve pendientes
        $retorno = array();
        $id_muestra = $this->escapar($id_muestra);

        $status = ' HAVING total - cargadas > 0 ';
        if ($estado == "true") {
            $status = ' HAVING total - cargadas <= 0  ';
        }

        //id, nombre, cargadas, totales
        $this->consultar("SELECT id_municipio, nombre 
                                    FROM municipios 
                                    WHERE id_municipio = $id_municipio");
        $municipio = $this->obtenerRegistro();

        $this->consultar("SELECT SUM(me2.cantidad) AS total 
                                    FROM muestras_escuelas me2 
                                    INNER JOIN escuelas e2 
                                      ON e2.id = me2.id_escuela 
                                    WHERE e2.municipio_id = $id_municipio 
                                      AND id_muestra = $id_muestra");
        $res = $this->obtenerRegistro();

        if ($res['total']) {
            $municipio['total'] = intval($res['total']);
            $retorno['total_muni'] = intval($res['total']);

            $this->consultar("SELECT e.municipio_id, 
                                        sum(me.cantidad) AS cargadas_bk, 
                                        count(distinct pd.id) AS cargadas 
                                        FROM muestras_escuelas me 
                                        LEFT JOIN planillas_digitales pd 
                                          ON me.id_escuela = pd.id_escuela
                                        INNER JOIN escuelas e 
                                          ON e.id = pd.id_escuela
                                        INNER JOIN mesas m 
                                          ON m.id = pd.nromesa
                                        WHERE pd.estado = 3 
                                          AND (pd.muestras = '$id_muestra' 
                                            XOR pd.muestras LIKE '$id_muestra|%' 
                                            XOR pd.muestras LIKE '%|$id_muestra' 
                                            XOR pd.muestras LIKE '%|$id_muestra|%') 
                                          AND m.publicada = 1 
                                          AND e.municipio_id = $id_municipio 
                                          AND me.id_muestra = $id_muestra
                                        GROUP BY e.municipio_id");
            $res = $this->obtenerRegistro();

            if ($this->contarFilas()) {
                $municipio['cargadas'] = intval($res['cargadas']);
            } else {
                $municipio['cargadas'] = 0;
            }

            if ($estado == "true" && $municipio['total'] <= $municipio['cargadas']) {
                if ($municipio['total'] < $municipio['cargadas']) {
                    $municipio['cargadas'] = $municipio['total'];
                }
                $retorno[] = $municipio;
            }

            if ($estado == "false" && $municipio['total'] > $municipio['cargadas']) {
                $retorno[] = $municipio;
            }
        }
        return $retorno;
    }

    public function obtenerTotalEscuelas($id_municipio = [], $id_muestra = 0)
    {
        $this->consultar("SELECT SUM(me2.cantidad) AS total 
                                    FROM muestras_escuelas me2 
                                    INNER JOIN escuelas e2 
                                      ON e2.id = me2.id_escuela 
                                    WHERE e2.municipio_id = $id_municipio 
                                      AND id_muestra = $id_muestra");
        $res = $this->obtenerRegistro();

        return intval($res['total']);

    }

}
