<?php

// var_dump($_SESSION["objfile"]["name"]);

foreach ($fromDb as $key => $value):

	if ($_SESSION["objfile"]["name"] == $value["datasheet"]):
	// var_dump($value);

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

			// Td base, header
			$sheetHeader = null;
			// $sheetHeader .= "<tr>";
			// $sheetHeader .= "<td><a title='Esconder coluna' class='btn btn_click_hide red' data-td-hide='0' href='index.php?hideItem=0'><i class='fa fa-eye-slash'></i></a></td>";
			// $sheetHeader .= "<td><a title='Esconder coluna' class='btn btn_click_hide red' data-td-hide='1' href='index.php?hideItem=1'><i class='fa fa-eye-slash'></i></a></td>";
			// $sheetHeader .= "<td><a title='Esconder coluna' class='btn btn_click_hide red' data-td-hide='2' href='index.php?hideItem=2'><i class='fa fa-eye-slash'></i></a></td>";
			// $sheetHeader .= "<td><a title='Esconder coluna' class='btn btn_click_hide red' data-td-hide='3' href='index.php?hideItem=3'><i class='fa fa-eye-slash'></i></a></td>";
			// $sheetHeader .= "<td><a title='Esconder coluna' class='btn btn_click_hide red' data-td-hide='4' href='index.php?hideItem=4'><i class='fa fa-eye-slash'></i></a></td>";
			// $sheetHeader .= "<td><a title='Esconder coluna' class='btn btn_click_hide red' data-td-hide='5' href='index.php?hideItem=5'><i class='fa fa-eye-slash'></i></a></td>";
			// if (isset($get["exb_all"])):
			// 	$sheetHeader .= "<td><a title='Esconder coluna' class='btn btn_click_hide red' data-td-hide='6' href='index.php?hideItem=6'><i class='fa fa-eye-slash'></i></a></td>";
			// 	$sheetHeader .= "<td><a title='Esconder coluna' class='btn btn_click_hide red' data-td-hide='7' href='index.php?hideItem=7'><i class='fa fa-eye-slash'></i></a></td>";
			// 	$sheetHeader .= "<td><a title='Esconder coluna' class='btn btn_click_hide red' data-td-hide='8' href='index.php?hideItem=8'><i class='fa fa-eye-slash'></i></a></td>";
			// 	$sheetHeader .= "<td><a title='Esconder coluna' class='btn btn_click_hide red' data-td-hide='9' href='index.php?hideItem=9'><i class='fa fa-eye-slash'></i></a></td>";
			// 	$sheetHeader .= "<td><a title='Esconder coluna' class='btn btn_click_hide red' data-td-hide='10' href='index.php?hideItem=10'><i class='fa fa-eye-slash'></i></a></td>";
			// 	$sheetHeader .= "<td><a title='Esconder coluna' class='btn btn_click_hide red' data-td-hide='11' href='index.php?hideItem=11'><i class='fa fa-eye-slash'></i></a></td>";
			// endif;
			// $sheetHeader .= "</tr>";

			/**

			Exemple of filters

			?filter=BASE+ADM+AP+IBIRAPUERA!Of+Of+Sup,Of+Cap/Ten&order_by=Nome;A-Z

			filter = ?!(.*)?! => array filter column [0]

			**/

			$sheetHeader .= "<tr class=\"bar-table\">";
			// $sheetHeader .= "<td>Hash</td>";
			/* 
			class=\"td-button\"><div><span data-filter=\"carimbo\" class=\"btn btn_order fright\"><i class=\"fas fa-sort-down\"></i></span></div><div> 
			*/	


			foreach ($Filter["buttons"] as $keybuttons => $button) {

				$sheetHeader .= "<td ".($Render->getFilter() ? "class=\"td-button\"><div><span data-filter=\"".$button["content"]."\" class=\"btn btn_order fright\"><i class=\"fas fa-sort-down\"></i><div class=\"sub-dropdown drop-render\"></div></span></div><div>" : ">" );

				$sheetHeader .= "<span>" . $button["text"] . "</span>";

				$sheetHeader .= ($Render->getFilter() ? "</div></td>" : "</td>" );


			}

			// var_dump($arrayDrop);

			// $sheetHeader .= "<td ".($Render->getFilter() ? "class=\"td-button\"><div><span data-filter=\"carimbo\" class=\"btn btn_order fright\"><i class=\"fas fa-sort-down\"></i><div class=\"sub-dropdown\">".$drop."</div></span></div><div>" : ">" )."<span>Carimbo de data/hora</span>".($Render->getFilter() ? "</div>" : null)."</td>";

			// All days
			for ($i=0; $i < 7; $i++):

				$diasemana_numero = date('w', strtotime(str_replace("/", "-", $ObjDecoded["refc"][$i][1])));
				if ($Today == $ObjDecoded["refc"][$i][1] || isset($get["exb_all"]))
					# 
					$sheetHeader .= "<td ".($Render->getFilter() ? "class=\"td-button\"><div><span data-filter=\"refc-".strtolower(clearString($diasemana[$diasemana_numero]))."\" class=\"btn btn_order fright\"><i class=\"fas fa-sort-down\"></i></span></div><div>" : ">" )."<span>[" . $diasemana[$diasemana_numero] . "] " .  $ObjDecoded["refc"][$i][1] . "".($Render->getFilter() ? "</span></div>" : null)."</td>";

			endfor;

			if ($key == 0)
				$sheetBody .= $sheetHeader;

			$sheetBody .= "</tr>";

			$sheetBody .= "<tr data-hash=\"".$value["hash"]."\">";
			// $sheetBody .= "<td>" . $value["hash"] . "</td>";
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



		break;

	endif;

endforeach;

?>