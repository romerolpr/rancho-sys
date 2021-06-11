<?php 

session_start();

$newLimit = $_POST["newLimit"];
$countElem = $_POST["countElem"];

// var_dump($countElem);

include 'Classes/ObjectDB.class.php';
include 'Classes/Reader.class.php';
include 'define.inc.php';
include 'functions.inc.php';

$Render = new Render();
$Db = new ObjectDB();
$Db->setter(HOST, USER, PASS, DBNAME);
$fromDb = $Db->return_query($Db->connect_db(), TB_RESP, null, false, array($countElem, $newLimit));
$date = new DateTime();
$Today  = $date->format("d/m/Y");

$sheetBody = null;

foreach ($fromDb as $key => $value):

	// var_dump($value["datasheet"]);

	if ($_SESSION["objfile"]["name"] == $value["datasheet"]):

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

			$sheetBody .= "<tr data-hash=\"".$value["hash"]."\">";

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

				if ($Today == $valueRefc[1] || isset($_GET["exb_all"])):
					$arranchamento = explode(",", $valueRefc[0]);
					if (is_array($arranchamento)):

						$idItem = $keyRefc.$key."_".strtolower(clearString(str_replace(" ", "_", $ObjDecoded["nome"])))."_".$value["hash"];

						if (empty($arranchamento[0])):

							$sheetBody .= "<td data-hash-id=\"".$value["hash"]."\" data-date=\"".$valueRefc[1]."\">-</td>";

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

		endif;

endforeach;	

echo $sheetBody;

?>