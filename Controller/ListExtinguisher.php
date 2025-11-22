<?php

namespace FacturaScripts\Plugins\IeExtintores\Controller;

use FacturaScripts\Core\Lib\ExtendedController\ListController;

class ListExtinguisher extends ListController
{
    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data['menu'] = 'warehouse';
        $data['title'] = 'extinguishers';
        $data['icon'] = 'fa-solid fa-fire-extinguisher';
        return $data;
    }

    protected function createViews(string $viewName = 'ListExtinguisher')
    {
        $this->addView($viewName, 'Extinguisher');
    }
}
