<?php

namespace FacturaScripts\Plugins\IeExtintores\Extension\Model;

use Closure;

class ServicioAT
{
    protected function test(): Closure
    {
        return function () {
            $correctitems = 0;
            $wrongitems = 0;

            foreach ($this->getTrabajos() as $work) {
                switch ($work->verification) {
                    case '1':
                        $correctitems = $correctitems + 1;
                        break;
                    case '0':
                        $wrongitems = $wrongitems + 1;
                        break;
                }
            }
            $this->correctitems= $correctitems;
            $this->wrongitems = $wrongitems;
            $this->totalitems = $correctitems + $wrongitems;
        };
    }
}
