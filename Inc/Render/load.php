<br>
<p>Gerenciar:</p>
<ul class='list'>
	<li><a href="<?php echo $url ?>index.php?worksheetName=None" title="Alterar Datasheet">Alterar Datasheet</a></li>
	<li><a href="<?php echo $url ?>index.php?compare" title="Realizar comparação">Realizar comparação</a></li>
	<br><li><a href="<?php echo $url ?>index.php?exit=session_obj" title="Fechar arquivo">Fechar arquivo</a></li>
</ul><br>

<?php


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
echo $Render->construct_table($sheetData);



?>

