<?php

namespace FacturaScripts\Plugins\IeExtintores\Extension\Controller;

use Closure;

class EditServicioAT
{
    public function createViews(): Closure
    {
        return function(){
    
            $this->addButton('EditServicioAT', [
                'action' => 'make-delivery-note',
                'color' => 'warning',
                'confirm' => true,
                'icon' => 'fa-solid fa-magic',
                'label' => 'make-delivery-note'
            ]);

            unset($this->views['EditServicioCategoriaAT']);
            unset($this->views['EditServicioCheckAT']);
            unset($this->views['EditCrmNota']);
            unset($this->views['ListFacturaCliente']);
            unset($this->views['ListPresupuestoCliente']);
        };
    }
}