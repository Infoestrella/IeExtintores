<?php

namespace FacturaScripts\Plugins\IeExtintores\Lib\Export;

use FacturaScripts\Plugins\Servicios\Lib\Export\PlantillasPDFserviciosExport as ParentClass;
use FacturaScripts\Core\Tools;
use FacturaScripts\Core\Where;
use FacturaScripts\Dinamic\Model\ServicioAT;
use FacturaScripts\Plugins\IeExtintores\Model\Extinguisher;

class PlantillasPDFserviciosExport extends ParentClass
{
    public function addModelPage($model, $columns, $title = ''): bool
    {
        $this->setFileName($title);
        if (isset($model->idempresa)) {
            $this->template->setEmpresa($model->idempresa);
        }
        $this->template->setHeaderTitle($title);

        $this->template->initMpdf();
        $this->template->initHtml();

        $this->headerText();
        $this->serviceData($model, $columns);
        $this->workData($model);
        $this->footerText();
        $this->footerData($model);
        return false;
    }

    public function headerText(): void
    {
        $html = '<h2 style="text-align:center; margin-bottom: 5px;">CERTIFICADO DE REVISIÓN DE EXTINTORES</h2>';

        $html .= '<p style="margin-top:0;">La empresa SEGURIDAD CONTRA INCENDIO, S.C, empresa de seguridad inscrita en el Registro
            de Empresas Instaladoras y Mantenedoras de Sistemas de Protección Contra Incendios, y Recargadora de Extintores con 
            número DR-437-J06203178 y con CIF: J-06203178, ha efectuado con fecha 3 de Julio 2025 la Revisión Anual del sistema 
            de protección contra incendios al cliente:</p>';

        $html .= '<h2 style="text-align:center; margin: 10px 0 5px 0;">CERTIFICA</h2>';

        $html .= '<p style="margin-top:0;">Que en la fecha reseñada al pie de este documento, atendiendo a la normativa y reglamentaria, ha sido 
            realizada la revisión del sistema contra incendios que protege las instalaciones del cliente, cuya composición 
            es detallada a continuación...
            </p>';

        $html .= '<p style="margin-top:0;">Las operaciones realizadas son las exigidas por el Reglamento de Instalaciones de Protección Contra 
            Incendios, según el Real Decreto 513/2017 RIPCI, y la ITC-MIE-AP5 de los Extintores de la instalación del cliente. La garantía de 
            la revisión tiene 1 año de validez, contado a partir de la fecha de emisión de  este certificado. La empresa SEGURIDAD CONTRA 
            INCENDIOS, S.C, certifica el funcionamiento de los elementos revisados durante el periodo de garantía, salvo si son manipulados 
            por personal ajeno a nuestra empresa.</p>';

        $this->template->writeHTML($html);

        $headers = [
            'Observaciones del certificado',
            'El responsable técnico',
            'Dpto. técnico'
        ];
        $rows = [
            ['',
            'CERTIFICO: Que se ha llevado a cabo la inspección, de acuerdo con la vigente reglamento, con resultado SATISFACTORIO, 
                salvo indicación expresa de lo contrario. Para que conste se entrega el presente certificado.',
            '']
        ];
        $this->addTablePage($headers, $rows, [], '');
    }

    protected function workData(ServicioAT $model): void
    {
        if (false === Tools::settings('servicios', 'print_pdf_works', false)) {
            return;
        }

        $headers = [
            Tools::trans('reference'),
            Tools::trans('description'),
            Tools::trans('serial-number'),
            Tools::trans('manufacture-date'),
            Tools::trans('laststamp-date'),
            Tools::trans('operation'),
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'
        ];

        $rows = [];
        foreach ($model->getTrabajos() as $work) {

            $extinguisher = new Extinguisher();
            $where = [
                Where::column('id', $work->idextinguisher),
            ];

            if ($extinguisher->loadWhere($where)) {
                $serialnumber = $extinguisher->serialnumber;
            }

            $ver = $work->verification ? 'v' : 'x';

            $dataWork = [
                $work->referencia,
                $work->descripcion,
                $serialnumber,
                $work->manufacturedate,
                $work->laststampdate,
                $work->operation,
                $ver, $ver, $ver, $ver, $ver, $ver, $ver, $ver, $ver, $ver, $ver
            ];
            
            $rows[] = $dataWork;
        }

        $this->addTablePage($headers, $rows, [], Tools::trans('works'));
    }

    public function footerText(): void
    {
        $html = '<p style="margin-top:0;"><strong>Comprobaciones:</strong> A: Presión, B: Precinto, C: Peso, D: Manguera y boquilla 
            E: Estado exterior, F: Instrucciones visibles y legibles, G: Altura, H: Accesibilidad, I: Señalización, 
            J:Adecuación, K: Etiqueta cumplimentada</p>';

        $html .= '<p style="margin-top:0;"><strong>Resultado comprobaciones:</strong> V: Correcto, X: Incorrecto, -: No aplica </p>';

        $html .= '<p style="margin-top:0;">NORMATIVA APLICABLE: <br></p><ul>
            <li>RIPCI:  Reglamento de instalaciones de protección contra incendios ( Real Decreto 513/2017 y modificaciones posteriores).</li>
            <li>UNE 23120: Mantenimiento de extintores de incendios.</li>
            <li>UNE 23033-1: Seguridad contra incendios. Señalización.</li>
            <li>REP: Reglamento de equipos a presión ( Real Decreto 2060/2008 y modificaciones posteriores).</li>
            <li>CTE-DB SI: Código técnico de la Edificación- Documento básico de seguridad en caso de incendio ( Real Decreto 314/2006 y modificaciones</p></li>
            </ul>';

        $this->template->writeHTML($html);

        $headers = [
            'Nombre, fecha y firma<br>del responsable tecnico',
            'Deficiencias',
            'Conforme el cliente'
        ];
        $rows = [
            ['<br><br><br><br><br>', '', '']
        ];
        $this->addTablePage($headers, $rows, [], '');
    }
}