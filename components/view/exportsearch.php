<?php
if(file_exists('../../config.php'))
{
	require('../../config.php');
}
else
{
	echo 'File NOT found';
}

//prevents direct access of these files
defined('LOAD_POINT')or die ('RESTRICTED ACCESS');

require(ABSOLUTE_PATH.'includes/header.php');
include(ABSOLUTE_PATH.'classes/db.class.php');

//initialize the db object
$dbase = new database;
$dbase->loadclasses();

$link = new navigation;

//load the excel class
$dbase->loadextraClass('phpexcel');

$objPHPExcel = new PHPExcel;

//set the document title
$objPHPExcel->getProperties()->setCreator('KFCB Document Manager')->setTitle($_POST['pagetitle']);

//set the document columns
$columns = explode(',', $_POST['columns']);

$col = 0; $row = 1;
foreach($columns as $colvalue)
{
    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $colvalue);
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
    
    $col++;
}

//set the document rows
$rows = explode(';', $_POST['rows']);

//move to the next row
$row++;

$col = 0;
foreach($rows as $rowvalue)
{
    $eachrow = explode(',', $rowvalue);
    
    foreach($eachrow as $eachrowvalue)
    {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $eachrowvalue);
        $col++;
    }
    
    $row++;
    $col = 0;
}

// Redirect output to a clientâ€™s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$_POST['filename'].'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
?>
