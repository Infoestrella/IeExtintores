<?php

namespace FacturaScripts\Plugins\IeExtintores\Extension\Model;

use Closure;
use FacturaScripts\Core\Where;
use FacturaScripts\Plugins\IeExtintores\Model\Extinguisher;

class TrabajoAT
{
    protected function test(): Closure
    {
        return function () {
            $extinguisher = new Extinguisher();
            $where = [
                Where::column('id', $this->idextinguisher),
            ];
            
            if ($extinguisher->loadWhere($where)) {
                $this->laststampdate = $extinguisher->laststampdate;
                $this->manufacturedate = $extinguisher->manufacturedate;
            }
        };
    }
}
