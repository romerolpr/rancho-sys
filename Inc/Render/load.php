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
$Render->getObject();
$Render->pushData();

$fromDb = $Db->return_query($Db->connect_db(), TB_RESP);
$getSheetBody = $Render->getSheetData();
$getSheetBody = count($getSheetBody[0]) - 2;
$Today  = $date->format("d/m/Y");
$current_url = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=="on") ? "https" : "http") . '://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING'];

$sheetBody = "<div class=\"box-table>\"";
?>

<p class="fleft d-center-items">
<span class="fleft"><strong><?php echo $Render->getDatasheetName(); ?></strong></span>
<span class="fright head_table">

	<a href="index.php" class="btn btn_link btn_manage" title="Restaurar tabela"><i class="fas fa-undo-alt"></i></a>

	<a href="<?php echo (isset($get) ? str_replace("window=expanded", "", $current_url) . "&window=expanded" : "?window=expanded")?>" class="btn btn_link btn_manage btn_expand" title="Expandir tabela"><i class="fas fa-expand"></i></a>

	<a href="<?php echo (isset($get) ? str_replace("worksheetName=None", "", $current_url) . "&worksheetName=None" : "?worksheetName=None") ?>" class="btn btn_link btn_manage" title="Alterar Datasheet"><i class="fas fa-exchange-alt"></i></a>

	<?php if(!isset($get["exb_all"])): ?>
		<a href="<?php echo (isset($get) ? str_replace("exb_all", "", $current_url) . "&exb_all" : "?exb_all" ) ?>" class="btn btn_link btn_manage" title="Todas as colunas"><i class="fas fa-globe"></i></a>
	<?php else: ?>
		<a href="<?php echo (isset($get) ? str_replace("exb_all", "", $current_url) . "" : "" ) ?>" class="btn btn_link btn_manage btn_active" title="Desativar modo: Todas as colunas"><i class="fas fa-globe"></i></a>
	<?php endif; ?>

	<a href="index.php?exit=session_obj" class="btn btn_link btn_manage btn_click_consult" title="Fechar arquivo" data-action="fechar"><i class="fas fa-power-off"></i></a>

	<button class="btn btn_link btn_manage" title="Salvar alterações" disabled id="saveAll">Salvar alterações</button>

</span>
</p>

<?php

// $sheetBody .= "<span>Buttons are here.</span>";
$sheetBody .= "</p>";
$sheetBody .= "<table>";

$diasemana = array('Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sabado');

foreach ($fromDb as $key => $value):

	$ObjDecoded = array(
		"nome" => utf8_decode(trim($value["nome"])),
		"posto_graduacao" => utf8_decode($value["posto_graduacao"]),
		"organizacao_militar" => utf8_decode($value["organizacao_militar"]),
		"refc" => array(
			explode("&&", utf8_decode($value["segunda_feira"])),
			explode("&&", utf8_decode($value["terca_feira"])),
			explode("&&", utf8_decode($value["quarta_feira"])),
			explode("&&", utf8_decode($value["quinta_feira"])),
			explode("&&", utf8_decode($value["sexta_feira"])),
			explode("&&", utf8_decode($value["sabado"])),
			explode("&&", utf8_decode($value["domingo"])),
		)
	);


	if ($key == 0):

		// Td base, header
		$sheetBody .= "<tr class=\"bar-table\">";
		$sheetBody .= "<td>Carimbo de data/hora</td>";
		$sheetBody .= "<td>Endereço de e-mail</td>";
		$sheetBody .= "<td>Organização Militar</td>";
		$sheetBody .= "<td>Posto/Graduação</td>";
		$sheetBody .= "<td>Nome de guerra</td>";

		// All days
		for ($i=0; $i < 7; $i++):

			$diasemana_numero = date('w', strtotime(str_replace("/", "-", $ObjDecoded["refc"][$i][1])));
			if ($Today == $ObjDecoded["refc"][$i][1] || isset($get["exb_all"]))
				$sheetBody .= "<td>Refeições  [" . $diasemana[$diasemana_numero] . "] " .  $ObjDecoded["refc"][$i][1] . "</td>";

		endfor;

		$sheetBody .= "</tr>";

	else:
		$sheetBody .= "<tr>";
		$sheetBody .= "<td>" . $value["carimbo"] . "</td>";
		$sheetBody .= "<td>" . $value["email"] . "</td>";
		$sheetBody .= "<td>" . $ObjDecoded["organizacao_militar"] . "</td>";
		$sheetBody .= "<td>" . $ObjDecoded["posto_graduacao"] . "</td>";
		$sheetBody .= "<td>" . $ObjDecoded["nome"] . "</td>";

		// Creating the button checkbox and text
		for ($i=0; $i <= 6 ; $i++):
			if ($Today == $ObjDecoded["refc"][$i][1] || isset($get["exb_all"])):
				$arranchamento = explode(",", $ObjDecoded["refc"][$i][0]);
				if (is_array($arranchamento)):

					$idItem = $i.$key."_".strtolower(clearString(str_replace(" ", "_", $ObjDecoded["nome"])))."_checked";

					if (empty($arranchamento[0])):

						$sheetBody .= "<td data-hash-id=\"".$value["hash"]."\" data-date=\"".$ObjDecoded["refc"][$i][1]."\">-</td>";

					else:

						$getDbInputCheck = $Db->return_query($Db->connect_db(), TB_CONF);
						
						$Checked = array(false, false, false);

						foreach ($getDbInputCheck as $key => $item) {
							if ($item["hash_id"] == $value["hash"]){
								$DataJSON = json_decode($item["data_json"], true);
								// break;
							}
						}

						foreach ($DataJSON as $key => $v) {
							$valor = $v["values"];

							// if (in_array(resizeString($arranchamento[0], 10), $value["values"])):
							// 	$Checked[0] = true;
							// elseif (in_array(resizeString($arranchamento[1], 10), $value["values"])):
							// 	$Checked[1] = true; 
							// elseif (in_array(resizeString($arranchamento[2], 10), $value["values"])):
							// 	$Checked[2] = true; 
							// endif;
						}

						foreach ($valor as $key => $V) {
							$checks = explode(";", $V);


							if (isset($arranchamento[0]) && in_array(resizeString($arranchamento[0], 10), $checks) && $checks[1] == $ObjDecoded["refc"][$i][1]):
								$Checked[0] = true;
							elseif (isset($arranchamento[1]) && in_array(resizeString($arranchamento[1], 10), $checks)):
								$Checked[1] = true; 
							elseif (isset($arranchamento[2]) && in_array(resizeString($arranchamento[2], 10), $checks)):
								$Checked[2] = true; 
							endif;
						}

						$sheetBody .= "<td data-hash-id=\"".$value["hash"]."\" data-date=\"".$ObjDecoded["refc"][$i][1]."\">" . 

							(isset($arranchamento[0]) ? "<label for=\"1".$idItem."\">" .resizeString($arranchamento[0], 10). "</label> <input ".($Checked[0] !== false ? "checked" : null)." data-date=\"".$ObjDecoded["refc"][$i][1]."\" class=\"input_checked\" type=\"checkbox\" name=\"check_refc\" value=\"".resizeString($arranchamento[0], 10)."\" id=\"1".$idItem."\">" : null) . 
							
							(isset($arranchamento[1]) ? "<br class=\"clear\"><label for=\"2".$idItem."\">" . resizeString($arranchamento[1], 10) . "</label> <input ".($Checked[1] !== false ? "checked" : null)." data-date=\"".$ObjDecoded["refc"][$i][1]."\" class=\"input_checked\" type=\"checkbox\" name=\"check_refc\" value=\"".resizeString($arranchamento[1], 10)."\" id=\"2".$idItem."\">" : null) . 
							
							(isset($arranchamento[2]) ? "<label for=\"3".$idItem."\">" .resizeString($arranchamento[2], 10). "</label> <input ".($Checked[2] !== false ? "checked" : null)." data-date=\"".$ObjDecoded["refc"][$i][1]."\" class=\"input_checked\" type=\"checkbox\" name=\"check_refc\" value=\"".resizeString($arranchamento[2], 10)."\" id=\"3".$idItem."\">" : null) . 

						"</td>";
	
					endif;

				endif;	

			endif;
		endfor;

		$sheetBody .= "</tr>";
	endif;


endforeach;
$sheetBody .= "</table></div>";

echo $sheetBody;

// echo $Render->constructTable($sheetData);