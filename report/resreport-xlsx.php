<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

include "../core/autoload.php";
include "../core/app/model/SellData.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/DData.php";
include "../core/app/model/PData.php";
include "../core/app/model/ConfigurationData.php";
$symbol = ConfigurationData::getByPreffix("currency")->val;
/** Include PHPExcel */
include "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$objPHPExcel = new Spreadsheet();
$products = array();
if($_GET["client_id"]==""){
			$products = SellData::getAllByDateOp($_GET["sd"],$_GET["ed"],1);
			}
			else{
			$products = SellData::getAllByDateBCOp($_GET["client_id"],$_GET["sd"],$_GET["ed"],1);
			} 

// Set document properties
$objPHPExcel->getProperties()->setCreator("Evilnapsis")
							 ->setLastModifiedBy("Evilnapsis")
							 ->setTitle("Inventio Max v9.0")
							 ->setSubject("Inventio Max v9.0")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");


// Add some data
$sheet = $objPHPExcel->setActiveSheetIndex(0);

$sheet->setCellValue('A1', 'Reporte de Compras - Inventio Max')
->setCellValue('A2', 'Id')
->setCellValue('B2', 'Pago')
->setCellValue('C2', 'Entrega')
->setCellValue('D2', 'Total')
->setCellValue('E2', 'Fecha');

$start = 3;
foreach($products as $product){
$total=0;
$ops = OperationData::getAllProductsBySellId($product->id);
foreach($ops as $op){
	//$product  = $op->getProduct();
	$total += $op->q*$op->price_in;
}

$sheet->setCellValue('A'.$start, $product->id)
->setCellValue('B'.$start, $product->getP()->name)
->setCellValue('C'.$start, $product->getD()->name)
->setCellValue('D'.$start, $symbol." ".$total)
->setCellValue('E'.$start, $product->created_at);
$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
////////////////////////////////////////////////////
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="resreport-'.time().'.xlsx"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
//////////////////////////////////////////////////////
$writer = new Xlsx($objPHPExcel);
$writer->save('php://output');