<?php

function copiarArchivo($desde, $hasta)
{
    $retorno = array();
    $retorno['codigo'] = 0;
    $retorno['mensaje'] = 'Iniciando Copia';
    try {
        if (copy($desde, $hasta)) {
            $retorno['codigo'] = 1;
            $retorno['mensaje'] = 'Copia realizada';
        } else {
            $retorno['codigo'] = -1;
            $retorno['mensaje'] = 'Falla en la copia';
        }
    } catch (Exception $th) {
        $retorno['codigo'] = -1;
        $retorno['mensaje'] = $th->getMessage();
    }
    return $retorno;
}

function ordernarPorFecha($a, $b)
{
    return $b['fecha'] - $a['fecha'];
}
