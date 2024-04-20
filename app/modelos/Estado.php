<?php

class Estado extends Base{
    public function __construct(){
        parent::__construct('estados', 'id_estado');
    }
}