<?php

$Id = array();
$fromDbComplete = $Db->return_query($Db->connect_db(), TB_RESP, null, false, null);

if (isset($get["sort"])):
	$getSort = explode(":", $get["sort"]);
	aasort($fromDb, $getSort[0], $getSort[1]);
endif;

foreach ($fromDbComplete as $key => $value):


		// var_dump($value["datasheet"]);
	if ($_SESSION['objfile']['name'] == $value["datasheet"]):


		$Render->setStatus(true);

		$ObjDecoded = array(
			"nome" => ucfirst(strtolower(utf8_decode(trim($value["nome"])))),
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

		// var_dump($ObjDecoded);

		// break;

			// Td base, header
			$sheetHeader = null;
			$sheetHeader .= "<tr class=\"bar-table\">";

			// var_dump($Filter["buttons"]);

			foreach ($Filter["buttons"] as $keybuttons => $button) {

				$content = $button["content"];

				if ($button["hided"] !== true):
					$sheetHeader .= "<td ".($Render->getFilter() ? "class=\"td-button\" data-content-children=\"".$content."\"><div><span data-filter=\"".$content."\" class=\"btn btn_order fright\"><i class=\"fas fa-sort-down\"></i><div class=\"sub-dropdown drop-render\"></div></span></div><div>" : ">" );

					$sheetHeader .= "<span class=\"title\">" . $button["text"] . "</span>";
					$sheetHeader .= ($Render->getFilter() ? "</div></td>" : "</td>" );
				endif;

			}

			// var_dump($ObjDecoded["refc"]);


			// All days
			foreach ($ObjDecoded["refc"] as $keyRefc => $valueRefc):

				// var_dump($ObjDecoded["refc"]);

				$diasemana_numero = date('w', strtotime(str_replace("/", "-", $valueRefc[1])));
				if ($Today == $valueRefc[1] || isset($get["exb_all"]))
					# 

					$sheetHeader .= "<td ". $Render->getFilter() ."><span class=\"title\">[" . $diasemana[$diasemana_numero] . "] " .  $valueRefc[1] . "".($Render->getFilter() ? "</span></div>" : null)."</td>";


			endforeach;

			if (!in_array($value["id"], $Id))
				array_push($Id, array($value["id"], $value["datasheet"]));

			if (!isset($added))
				$sheetBody .= $sheetHeader;
				$added = true;

			$sheetBody .= "</tr>";

			$sheetBody .= "<tr data-hash=\"".$value["hash"]."\">";
			// $sheetBody .= "<td>" . $value["hash"] . "</td>";
			
			if ($Filter["buttons"]["carimbo"]["hided"] !== true)
				$sheetBody .= "<td data-content=\"carimbo\">" . $value["carimbo"] . "</td>";

			if ($Filter["buttons"]["email"]["hided"] !== true)
				$sheetBody .= "<td data-content=\"email\">" . $value["email"] . "</td>";
			
			if ($Filter["buttons"]["organizacao_militar"]["hided"] !== true)
				$sheetBody .= "<td data-content=\"organizacao_militar\">" . $ObjDecoded["organizacao_militar"] . "</td>";

			if ($Filter["buttons"]["posto_graduacao"]["hided"] !== true)
				$sheetBody .= "<td data-content=\"posto_graduacao\">" . $ObjDecoded["posto_graduacao"] . "</td>";

			if ($Filter["buttons"]["nome"]["hided"] !== true)
				$sheetBody .= "<td data-content=\"nome\">" . $ObjDecoded["nome"] . "</td>";

			// Creating the button checkbox and text
			foreach ($ObjDecoded["refc"] as $keyRefc => $valueRefc):

				if ($Today == $valueRefc[1] || isset($get["exb_all"])):
					$arranchamento = explode(",", $valueRefc[0]);
					if (is_array($arranchamento)):

						$idItem = $keyRefc.$key."_".strtolower(clearString(str_replace(" ", "_", $ObjDecoded["nome"])))."_".$value["hash"];

						if (empty($arranchamento[0])):

							$sheetBody .= "<td data-hash-id=\"".$value["hash"]."\" data-date=\"".$valueRefc[1]."\">-</td>";

						else:

							$getDbInputCheck = $Db->return_query($Db->connect_db(), TB_CONF);
							
							$Checked = array(false, false, false);

							foreach ($getDbInputCheck as $key => $item) {
								if ($item["hash_id"] == $value["hash"] && $item["datasheet"] == $Render->getTableName()){
									$DataJSON = json_decode($item["data_json"], true);
									// break;
								}
							}

							foreach ($DataJSON as $v):
								$valor = $v["values"];

							endforeach;

							array_unique($valor);

							foreach ($valor as $key => $V) {

								$checks = explode(";", $V);

								if (isset($arranchamento[0]) && in_array(trim(resizeString($arranchamento[0], 10)), $checks) && $checks[1] == $valueRefc[1]):
									$Checked[0] = true;
									// var_dump($arranchamento);

								elseif (isset($arranchamento[1]) && in_array(trim(resizeString($arranchamento[1], 10)), $checks) && $checks[1] == $valueRefc[1]):
									$Checked[1] = true; 

								elseif (isset($arranchamento[2]) && in_array(trim(resizeString($arranchamento[2], 10)), $checks) && $checks[1] == $valueRefc[1]):
									$Checked[2] = true; 
								endif;
							}

							// var_dump($valor);

							$sheetBody .= "<td data-hash-id=\"".$value["hash"]."\" data-date=\"".$valueRefc[1]."\">" . 

								(isset($arranchamento[0]) ? "<div><label for=\"1".$idItem."\">" .resizeString($arranchamento[0], 10). "</label><input ".($Checked[0] !== false ? "checked" : null)." data-date=\"".$valueRefc[1]."\" class=\"input_checked\" type=\"checkbox\" name=\"check_refc\" value=\"".resizeString($arranchamento[0], 10)."\" id=\"1".$idItem."\"></div>" : "</div>") . 
								
								(isset($arranchamento[1]) ? "<div><label for=\"2".$idItem."\">" . resizeString($arranchamento[1], 10) . "</label><input ".($Checked[1] !== false ? "checked" : null)." data-date=\"".$valueRefc[1]."\" class=\"input_checked\" type=\"checkbox\" name=\"check_refc\" value=\"".resizeString($arranchamento[1], 10)."\" id=\"2".$idItem."\"></div>" : "</div>") . 
								
								(isset($arranchamento[2]) ? "<div><label for=\"3".$idItem."\">" .resizeString($arranchamento[2], 10). "</label><input ".($Checked[2] !== false ? "checked" : null)." data-date=\"".$valueRefc[1]."\" class=\"input_checked\" type=\"checkbox\" name=\"check_refc\" value=\"".resizeString($arranchamento[2], 10)."\" id=\"3".$idItem."\"></div>" : "</div>") . 

							"</td>";
		
						endif;

					endif;	

				endif;

			endforeach;

			$sheetBody .= "</tr>";

		else:

			// $Alert->setConfig("warning", "<strong>Falha na importação</strong>: Não foi possível importar dados da tabela:<i>\"".$Render->getTableName()."\"</i>, de: <i>\"".$_SESSION['objfile']['name']."\"</i>.");
			// echo $Alert->displayPrint();

			// // break;

		endif;

endforeach;	


?>