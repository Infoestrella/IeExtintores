<?php

namespace FacturaScripts\Plugins\IeExtintores\Controller;

use FacturaScripts\Core\Lib\ExtendedController\EditController;

class EditExtinguisher extends EditController
{
    public function getModelClassName(): string
    {
        return 'Extinguisher';
    }

    public function getPageData(): array
    {
        $data = parent::getPageData();
        $data['menu'] = 'services';
        $data['title'] = 'extinguisher';
        $data['icon'] = 'fa-solid fa-fire-extinguisher';
        return $data;
    }

    protected function createViews()
    {
        parent::createViews();
    }

    protected function loadData($viewName, $view)
    {
		switch ($viewName) {
			case 'EditExtinguisher':
                parent::loadData($viewName, $view);
                break;
        }
    }
}
