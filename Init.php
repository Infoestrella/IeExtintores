<?php

namespace FacturaScripts\Plugins\IeExtintores;

use FacturaScripts\Core\Template\InitClass;

final class Init extends InitClass
{
    public function init(): void
    {
        $this->loadExtension(new Extension\Controller\EditServicioAT());
        $this->loadExtension(new Extension\Model\TrabajoAT());
        $this->loadExtension(new Extension\Model\ServicioAT());
    }

    public function uninstall(): void
    {
    }

    public function update(): void
    {
    }
}
