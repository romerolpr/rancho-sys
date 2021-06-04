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
$Render->setTableName($_SESSION["objfile"]["name"]);

if (isset($get["worksheetName"])):
	$Render->powerOffInsereData(true);
endif;

if (isset($get["filter"]) && empty($get["filter"])):
	$Render->setFilter(null, true);
	// header("location: index.php");
else:
	$Render->manipuleFilter();
endif;

// var_dump($Render->getFilter());

/* Get object data and render*/

if ($Render->pushData()):
	$Render->setStatus(true);
else:
	$Render->setStatus(false);
endif;

$getSheetBody = $Render->getSheetData();
$getSheetBody = count($getSheetBody[0]) - 2;

	// header("location: index.php");
// endif;

// var_dump($Render->getSheetData());

$fromDb = $Db->return_query($Db->connect_db(), TB_RESP);

// var_dump($fromDb);

$Today  = $date->format("d/m/Y");
$diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado');

$sheetBody = "<div class=\"box-table\">";
$sheetBody .= "<p class=\"fleft d-center-items sticky\">";
$sheetBody .= "<span class=\"fleft\"><strong>" . $Render->getDatasheetName() ."</strong></span>";
$sheetBody .= "<span class=\"fright head_table\">";

if ($Render->getFilter() !== true):
	$sheetBody .= "<a href=\"".$url."index.php?filter\" class=\"btn btn_link btn_manage\" title=\"Visualização de filtro\" id=\"power_filter\"><i class=\"fas fa-filter\"></i></a>";
else:
	$sheetBody .= "<a href=\"".$url."index.php?filter=none\" class=\"btn btn_link btn_manage btn_active\" title=\"Desativar modo: Visualização de filtro\"><i class=\"fas fa-filter\"></i></a>";
endif;
$sheetBody .= "<a href=\"".$url."index.php\" class=\"btn btn_link btn_manage\" title=\"Restaurar tabela\"><i class=\"fas fa-undo-alt\"></i></a>";
$sheetBody .= "<a href=\"".$url."index.php\" class=\"btn btn_link btn_manage btn_expand\" title=\"Expandir tabela\"><i class=\"fas fa-expand\"></i></a>";
$sheetBody .= "<a href=\"". (isset($get) ? replaceUrl("&worksheetName=None") : replaceUrl("worksheetName=None"))."\" class=\"btn btn_link btn_manage\" title=\"Alterar Datasheet\"><i class=\"fas fa-exchange-alt\"></i></a>";
$sheetBody .= "<a href=\"". (!isset($get["exb_all"]) ? (isset($get) ? replaceUrl("&exb_all") : replaceUrl("exb_all")) : "") ."\" class=\"btn btn_link btn_manage ". (isset($get["exb_all"]) ? "btn_active" : null) ."\" title=\"Exibir todos os dias\"><i class=\"fas fa-globe\"></i></a>";
$sheetBody .= "<a href=\"".$url."index.php?action=generateReport\" class=\"btn btn_link btn_manage\" title=\"Gerar relatório\" id=\"report\"><i class=\"far fa-file-alt\"></i></a>";
$sheetBody .= "<span class=\"separator\">|</span>";
$sheetBody .= "<a href=\"".$url."index.php?exit=session_obj\" class=\"btn btn_link btn_manage btn_click_consult\" title=\"Fechar arquivo\" data-action=\"fechar\"><i class=\"fas fa-power-off\"></i></a>";
$sheetBody .= "<button class=\"btn btn_link btn_manage\" title=\"Salvar alterações\" disabled id=\"saveAll\">Salvar alterações</button>";

$sheetBody .= "<table data-exb=\"".((!isset($get["exb_all"])) ? "default" : "exb_all")."\">";

$sheetBody .= "<div id=\"load_sheet_data\">";
include FRONT . "pages/load.sheet.php";
$sheetBody .= "</div>";

$sheetBody .= "</table></div>";

var_dump($Render->getStatus());

if ($Render->getStatus()):
	echo $sheetBody;
else:
	$Alert->setConfig("warning", "<strong>Arranchamento indisponível</strong>: O arquivo selecionado não possui compatibiliade com o sistema. <a href='index.php?exb=add_new' title='Alterar arquivo' class='btn btn_click_consult'>Alterar arquivo</a></span>");
	echo $Alert->displayPrint();
	// var_dump($Render->getStatus());
	echo $Render->constructTable($sheetData);
endif;

?>