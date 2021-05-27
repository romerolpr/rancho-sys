<?php

/**
Send and load data
**/

$filterSubset = new MyReadFilter();
$objReader = PHPExcel_IOFactory::createReader($inputFileName);
$objReader->setLoadSheetsOnly($_SESSION["objfile"]["worksheetName"]);
$objReader->setReadFilter($filterSubset);
$objPHPExcel = $objReader->load($inputTmp);
$sheetData = $objPHPExcel->getActiveSheet()->toArray();

// var_dump($sheetData);
$Render->setSheetData($sheetData);
$Render->setUnset(true);


/* Get object data and render*/
$Objects = $Render->getObject();
$Render->pushAppropriateData();
$Render->powerOffInsereData(false);

var_dump($Render->getInsereData());


// echo $Render->constructTable($sheetData);



?>

