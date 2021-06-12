<?php

/**
Send and load data
**/

$Render->setStatus(true);

if (isset($get["worksheetName"])):

	$filterSubset = new MyReadFilter();
	$objReader = PHPExcel_IOFactory::createReader($inputFileName);
	$objReader->setLoadSheetsOnly($_SESSION["objfile"]["worksheetName"]);
	$objReader->setReadFilter($filterSubset);
	$objPHPExcel = $objReader->load($inputTmp);
	$sheetData = $objPHPExcel->getActiveSheet()->toArray();
	$Render->setSheetData($sheetData);

	
	$Render->setUnset(true);
	$Render->setTableName($_SESSION["objfile"]["name"]);
	$Render->powerOffInsereData(true);

	if ($Render->pushData() !== false):
		$Render->setStatus(true);
		header("location: index.php");

	else:
		$Render->setStatus(false);

		$Alert->setConfig("danger", "<strong>Erro inesperado</strong>: Não foi possível importar os dados da planilha.</span>");
		echo $Alert->displayPrint();

	endif;

endif;


if (isset($get["filter"]) && empty($get["filter"])):
	$Render->setFilter(null, true);
	// header("location: index.php");
else:
	$Render->manipuleFilter();
endif;

$paramLimit = (isset($get["filter"]) || isset($get["exb_all"]) ? null : LIMIT);
$fromDb = $Db->return_query($Db->connect_db(), TB_RESP, null, false, $paramLimit);

// var_dump($fromDb);

$Today  = $date->format("d/m/Y");
$diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado');

$sheetBody = "<div class=\"box-table ". (isset($get["filter"]) || isset($get["exb_all"]) ? 'exbAll' : null) ."\">";
$sheetBody .= "<p class=\"fleft d-center-items sticky\">";
$sheetBody .= "<span class=\"fleft\"><strong>" . $Render->getDatasheetName() ."</strong></span>";
$sheetBody .= "<span class=\"fright head_table\">";

if (!isset($get["filter"])):
	$sheetBody .= "<a href=\"".$url."index.php?filter".(isset($get["exb_all"]) ? "&exb_all" : null)."\" class=\"btn btn_link btn_manage\" title=\"Visualização de filtro\" id=\"power_filter\"><i class=\"fas fa-filter\"></i></a>";
else:
	$sheetBody .= "<a href=\"".$url."index.php".(isset($get["exb_all"]) ? "?exb_all" : null)."\" class=\"btn btn_link btn_manage btn_active\" title=\"Desativar modo: Visualização de filtro\"><i class=\"fas fa-filter\"></i></a>";
endif;

$sheetBody .= "<a href=\"".$url."index.php\" class=\"btn btn_link btn_manage\" title=\"Restaurar tabela\"><i class=\"fas fa-undo-alt\"></i></a>";

$sheetBody .= "<a href=\"".$url."index.php\" class=\"btn btn_link btn_manage btn_expand\" title=\"Expandir tabela\"><i class=\"fas fa-expand\"></i></a>";

$sheetBody .= "<a href=\"". (isset($get) ? replaceUrl("&worksheetName=None") : replaceUrl("worksheetName=None"))."\" class=\"btn btn_link btn_manage\" title=\"Alterar Datasheet\"><i class=\"fas fa-exchange-alt\"></i></a>";

$sheetBody .= "<a href=\"". (!isset($get["exb_all"]) ? (isset($get) ? replaceUrl("&exb_all") : replaceUrl("exb_all")) : (isset($get["filter"]) ? '?filter' : "index.php")) ."\" class=\"btn btn_link btn_manage ". (isset($get["exb_all"]) ? "btn_active" : null) ."\" title=\"Exibir todos os dias\"><i class=\"fas fa-globe\"></i></a>";

$sheetBody .= "<a target=\"_blank\" href=\"".$url."index.php?action=generateReport\" class=\"btn btn_link btn_manage\" title=\"Gerar relatório\"><i class=\"far fa-file-alt\"></i></a>";
$sheetBody .= "<span class=\"separator\">|</span>";

$sheetBody .= "<a href=\"".$url."index.php?exit=session_obj\" class=\"btn btn_link btn_manage btn_click_consult\" title=\"Fechar arquivo\" data-action=\"fechar\"><i class=\"fas fa-power-off\"></i></a>";

$sheetBody .= "<button class=\"btn btn_link btn_manage\" title=\"Salvar alterações\" disabled id=\"saveAll\">Salvar alterações</button>";

$sheetBody .= "<table id=\"table-filter\" data-exb=\"".((!isset($get["exb_all"])) ? "default" : "exb_all")."\">";

if(isset($get["filter"])): 
	$sheetBody .= '<div class="fright mb-2">';
		$sheetBody .= '<div class="box">';
			$sheetBody .= '<span class="label">Filtrar por: </span>';
			$sheetBody .= '<input type="text" name="searchByName" class="searchbar" id="searchbar" placeholder="Nome, arranchamento...">';
		$sheetBody .= '</div>';
	$sheetBody .= '</div>';
endif;

$sheetBody .= "<div id=\"load_sheet_data\">";
include FRONT . "pages/load.sheet.php";
$sheetBody .= "</div>";

$sheetBody .= "</table></div>";

// var_dump($Render->getStatus());
// var_dump($Render->getStatus());

if ($Render->getStatus()):

	echo $sheetBody;
else:
	$Alert->setConfig("warning", "<strong>Falha na importação</strong>: O arquivo selecionado não possui compatibiliade com o sistema ou não foi possível importar os dados. <a href='index.php?exb=add_new' title='Alterar arquivo' class='btn btn_click_consult'>Alterar arquivo</a></span>");
	echo $Alert->displayPrint();
	// var_dump($Render->getStatus());
	echo $Render->constructTable($sheetData);
endif;

$countElem 		= count($fromDb);
$countElemMax 	= array($Db->return_query($Db->connect_db(), TB_RESP, null, false, null), $Db->return_query($Db->connect_db(), TB_RESP, null, false, LIMIT));
$row = 0;
foreach ($countElemMax[0] as $key => $value) {
	if ($value["datasheet"] == $Render->getTableName())
		$row += 1;
}
$resultCount = $countElem + LIMIT;
$newLimit = ( $resultCount <= $countElem ? $resultCount : count($countElemMax[1]) );
echo "<div class=\"d-center bg-loading\" data-maxElem=\"".$row."\" data-limit=\"". $newLimit ."\"></div>";



?>