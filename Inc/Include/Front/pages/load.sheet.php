<?php

// var_dump($_SESSION["objfile"]["name"]);

foreach ($fromDb as $key => $value):

	if ($_SESSION["objfile"]["name"] == $value["datasheet"]):
	// var_dump($value);

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

			// Td base, header
			$sheetHeader = null;
			$sheetHeader .= "<tr class=\"bar-table\">";

			// var_dump($Filter["buttons"]);

			foreach ($Filter["buttons"] as $keybuttons => $button) {

				$content = $button["content"];

				$sheetHeader .= "<td ".($Render->getFilter() ? "class=\"td-button\" data-content-children=\"".$content."\"><div><span data-filter=\"".$content."\" class=\"btn btn_order fright\"><i class=\"fas fa-sort-down\"></i><div class=\"sub-dropdown drop-render\"></div></span></div><div>" : ">" );

				$sheetHeader .= "<span class=\"title\">" . $button["text"] . "</span>";

				$sheetHeader .= ($Render->getFilter() ? "</div></td>" : "</td>" );


			}


			// All days
			for ($i=0; $i < 7; $i++):

				$diasemana_numero = date('w', strtotime(str_replace("/", "-", $ObjDecoded["refc"][$i][1])));
				if ($Today == $ObjDecoded["refc"][$i][1] || isset($get["exb_all"]))
					# 

					$sheetHeader .= "<td ".($Render->getFilter() ? "class=\"td-button\" data-content-children=\"".$content."\"><div><span data-filter=\"refc-".strtolower(clearString($diasemana[$diasemana_numero]))."\" class=\"btn btn_order fright\" data-filter-extract=\"".$ObjDecoded["refc"][$i][1]."\"><i class=\"fas fa-sort-down\"></i><div class=\"sub-dropdown drop-render\"></div></span></div><div>" : ">" )."<span class=\"title\">[" . $diasemana[$diasemana_numero] . "] " .  $ObjDecoded["refc"][$i][1] . "".($Render->getFilter() ? "</span></div>" : null)."</td>";

			endfor;

			if ($key == 0)
				$sheetBody .= $sheetHeader;

			$sheetBody .= "</tr>";

			$sheetBody .= "<tr data-hash=\"".$value["hash"]."\">";
			// $sheetBody .= "<td>" . $value["hash"] . "</td>";
			$sheetBody .= "<td data-content=\"carimbo\">" . $value["carimbo"] . "</td>";
			$sheetBody .= "<td data-content=\"email\">" . $value["email"] . "</td>";
			$sheetBody .= "<td data-content=\"organizacao_militar\">" . $ObjDecoded["organizacao_militar"] . "</td>";
			$sheetBody .= "<td data-content=\"posto_graduacao\">" . $ObjDecoded["posto_graduacao"] . "</td>";
			$sheetBody .= "<td data-content=\"nome\">" . $ObjDecoded["nome"] . "</td>";

			// Creating the button checkbox and text
			for ($i=0; $i < 7 ; $i++):
				if ($Today == $ObjDecoded["refc"][$i][1] || isset($get["exb_all"])):
					$arranchamento = explode(",", $ObjDecoded["refc"][$i][0]);
					if (is_array($arranchamento)):

						$idItem = $i.$key."_".strtolower(clearString(str_replace(" ", "_", $ObjDecoded["nome"])))."_".$value["hash"];

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

							foreach ($DataJSON as $key => $v):
								$valor = $v["values"];

							endforeach;

							array_unique($valor);

							foreach ($valor as $key => $V) {

								$checks = explode(";", $V);

								if (isset($arranchamento[0]) && in_array(trim(resizeString($arranchamento[0], 10)), $checks) && $checks[1] == $ObjDecoded["refc"][$i][1]):
									$Checked[0] = true;
									// var_dump($arranchamento);

								elseif (isset($arranchamento[1]) && in_array(trim(resizeString($arranchamento[1], 10)), $checks) && $checks[1] == $ObjDecoded["refc"][$i][1]):
									$Checked[1] = true; 

								elseif (isset($arranchamento[2]) && in_array(trim(resizeString($arranchamento[2], 10)), $checks) && $checks[1] == $ObjDecoded["refc"][$i][1]):
									$Checked[2] = true; 
								endif;
							}

							// var_dump($valor);

							$sheetBody .= "<td data-hash-id=\"".$value["hash"]."\" data-date=\"".$ObjDecoded["refc"][$i][1]."\">" . 

								(isset($arranchamento[0]) ? "<div><label for=\"1".$idItem."\">" .resizeString($arranchamento[0], 10). "</label><input ".($Checked[0] !== false ? "checked" : null)." data-date=\"".$ObjDecoded["refc"][$i][1]."\" class=\"input_checked\" type=\"checkbox\" name=\"check_refc\" value=\"".resizeString($arranchamento[0], 10)."\" id=\"1".$idItem."\"></div>" : "</div>") . 
								
								(isset($arranchamento[1]) ? "<div><label for=\"2".$idItem."\">" . resizeString($arranchamento[1], 10) . "</label><input ".($Checked[1] !== false ? "checked" : null)." data-date=\"".$ObjDecoded["refc"][$i][1]."\" class=\"input_checked\" type=\"checkbox\" name=\"check_refc\" value=\"".resizeString($arranchamento[1], 10)."\" id=\"2".$idItem."\"></div>" : "</div>") . 
								
								(isset($arranchamento[2]) ? "<div><label for=\"3".$idItem."\">" .resizeString($arranchamento[2], 10). "</label><input ".($Checked[2] !== false ? "checked" : null)." data-date=\"".$ObjDecoded["refc"][$i][1]."\" class=\"input_checked\" type=\"checkbox\" name=\"check_refc\" value=\"".resizeString($arranchamento[2], 10)."\" id=\"3".$idItem."\"></div>" : "</div>") . 

							"</td>";
		
						endif;

					endif;	

				endif;
			endfor;

			$sheetBody .= "</tr>";

		// endif;

	else:

		$sheetBody .= "<tr>";

		$Alert->setConfig("warning", "<strong>Aviso</strong>: Não foi possível importar os dados da planilha. <a href='index.php?exb=add_new' title='Adicionar novo arquivo' class='btn'>Adicionar novo arquivo</a></span>");
		$sheetBody .= "<br class=\"clear\">";
		$sheetBody .= $Alert->displayPrint();

		$sheetBody .= "</tr>";

		$Render->setStatus(false);

		break;

	endif;

endforeach;

?>