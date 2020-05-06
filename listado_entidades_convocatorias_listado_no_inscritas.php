<?php
//============================================================+
// File name   : example_061.php
// Begin       : 2010-05-24
// Last Update : 2014-01-25
//
// Description : Example 061 for TCPDF class
//               XHTML + CSS
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: XHTML + CSS
 * @author Nicola Asuni
 * @since 2010-05-25
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');
include 'config/config.php';

class MYPDF extends TCPDF {

        //Page header
        public function Header() {
                // Logo
                $image_file = 'http://sicon.scrd.gov.co/admin_SCRD_pv/dist/img/scrd_logo.png';
                
                $this->Image($image_file, 200, 5, 17, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
                

                // set style for barcode
                $style = array(
                        'border' => 2,
                        'vpadding' => 'auto',
                        'hpadding' => 'auto',
                        'fgcolor' => array(0,0,0),
                        'bgcolor' => false, //array(255,255,255)
                        'module_width' => 1, // width of a single module in points
                        'module_height' => 1 // height of a single module in points
                );
                $this->Ln();
                $this->write2DBarcode("https://www.culturarecreacionydeporte.gov.co/convocatorias", 'QRCODE,L', 320, 6, 10, 10, $style, 'N');


        }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SCRD');
$pdf->SetTitle('LISTADO DE PROPUESTAS GUARDADA - NO INSCRITA ');
$pdf->SetSubject('SECTORIAL');
$pdf->SetKeywords('PDE, PDAC, BANCO DE JURADOS');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------


// set font
$pdf->SetFont('helvetica', '', 8);

// add a page
$pdf->AddPage('L', 'A3');

/* NOTE:
 * *********************************************************
 * You can load external XHTML using :
 *
 * $html = file_get_contents('/path/to/your/file.html');
 *
 * External CSS files will be automatically loaded.
 * Sometimes you need to fix the path of the external CSS.
 * *********************************************************
 */

// abrimos la sesión cURL
$ch = curl_init();
 
// definimos la URL a la que hacemos la petición
curl_setopt($ch, CURLOPT_URL,$url_api."/crud_SCRD_pv/api/ConvocatoriasFormatos/reporte_listado_entidades_convocatorias_no_inscritas/");
// indicamos el tipo de petición: POST
curl_setopt($ch, CURLOPT_POST, TRUE);
// definimos cada uno de los parámetros
curl_setopt($ch, CURLOPT_POSTFIELDS, "token=".$_GET["token"]."&entidad=".$_GET["entidad"]."&anio=".$_GET["anio"]."&convocatoria=".$_GET["convocatoria"]);
 
// recibimos la respuesta y la guardamos en una variable
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec ($ch);
 
 
// cerramos la sesión cURL
curl_close ($ch);

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('listado_entidades_convocatorias_listado_no_inscritas_'.$_GET["entidad"].'_'.$_GET["anio"].'_'.$_GET["convocatoria"].'.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
