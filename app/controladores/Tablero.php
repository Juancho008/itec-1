<?php

/**
 * 
 */
class Tablero extends Controlador
{
    function __construct()
    {
        parent::__construct(array('tablero'));
        $this->verificarAcceso();
    }

    function index()
    {
        $this->principal();
    }

    public function principal()
    {
        $this->header();
        $this->vista('paginas/tablero/general');
        $this->footer();

    }
}
