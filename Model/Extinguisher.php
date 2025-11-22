<?php

namespace FacturaScripts\Plugins\IeExtintores\Model;

use FacturaScripts\Core\Template\ModelClass;
use FacturaScripts\Core\Template\ModelTrait;

class Extinguisher extends ModelClass
{
    use ModelTrait;

    public $id;
    public $idproducto;
    public $lastreviewdate;
    public $laststampdate;
    public $manufacturedate;
    public $referencia;
    public $serialnumber;
    
    public static function primaryColumn(): string
    {
        return "id";
    }

    public function primaryDescriptionColumn(): string
    {
        return 'numserie';
    }

    public static function tableName(): string
    {
        return "extinguishers";
    }
}
