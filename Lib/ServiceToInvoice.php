<?php

namespace FacturaScripts\Plugins\IeExtintores\Lib;

use FacturaScripts\Core\Model\Base\SalesDocument;
use FacturaScripts\Dinamic\Model\AlbaranCliente;
use FacturaScripts\Dinamic\Model\ServicioAT;
use FacturaScripts\Dinamic\Model\Cliente;
use FacturaScripts\Core\Base\DataBase;
use FacturaScripts\Core\Tools;
use FacturaScripts\Core\Where;
use FacturaScripts\Plugins\Servicios\Lib\ServiceToInvoice as ParentClass;

class ServiceToInvoice extends ParentClass
{
    public static function deliveryNote(ServicioAT &$service): bool
    {
        $customer = new Cliente();
        if (false === $customer->load($service->codcliente)) {
            return false;
        }

        $db = new DataBase();
        $db->beginTransaction();

        $newAlbaran = new AlbaranCliente();

        $where = [
            Where::column('idservicio', $service->idservicio)
        ];

        if ($newAlbaran->loadWhere($where)) {
            Tools::log()->warning('already-exists-delivery-note');
            $db->rollback();
            return false;
        }

        $newAlbaran->setSubject($customer);
        $newAlbaran->codagente = $service->codagente ?? $newAlbaran->codagente;
        $newAlbaran->codalmacen = $service->codalmacen;
        $newAlbaran->idempresa = $service->idempresa;
        $newAlbaran->idservicio = $service->idservicio;
        $newAlbaran->nick = $service->nick;

        $pipe = new self();
        $pipeAlbaran = $pipe->pipe('deliveryNote', $service, $newAlbaran);
        if ($pipeAlbaran) {
            $newAlbaran = $pipeAlbaran;
        }

        if (false === $newAlbaran->save()) {
            $db->rollback();
            return false;
        }

        if (false === static::addLineService($newAlbaran, $service)) {
            $db->rollback();
            return false;
        }

        $found = false;
        $counts = [];

        foreach ($service->getTrabajos() as $work) {
            $found = true;

            $reference = $work->referencia;
            $counts[$reference] = ($counts[$reference] ?? 0) + 1;
        }

        if (false === $found) {
            Tools::log()->warning('no-works-to-delivery-note');
            $db->rollback();
            return false;
        }

        foreach ($counts as $reference => $units) {
            if (false === static::addLineWorkByReference($newAlbaran, $reference, $units)) {
                $db->rollback();
                return false;
            }
        }

        return static::recalculate($newAlbaran, $db);
    }

    protected static function addLineWorkByReference(SalesDocument &$doc, string $reference, int $cantidad): bool
    {
        $newLine = $doc->getNewProductLine($reference);
        $newLine->cantidad = $cantidad;

        if (false === $newLine->save()) {
            return false;
        }

        return true;
    }
}