<?php 

$arrayNum = array(

	"posto_graduacao" =>
		array(
			"of_capten" => array(0, 0),
			"1_sgt" => array(0, 0),
			"2_sgt" => array(0, 0),
			"st" => array(0, 0),
			"of_sup" => array(0, 0),
			"3_sgt" => array(0, 0),
			"civil" => array(0, 0),
			"cb_sd" => array(0, 0),
			"total" => 0
		),

	"valor_final" => array(
		"oficial" => array(0, 0, array(
			array("of_capten", "of_sup"),
			0, 0, 0
		)),
		"st_sgt" => array(0, 0, array(
			array("1_sgt", "2_sgt", "3_sgt", "st"),
			0, 0, 0
		)),
		"cb_sd" => array(0, 0, array(
			array("cb_sd"),
			0, 0, 0
		)),
		"total" => array(
			0,
			"refc" => array(
				0, 0, 0, 
				"total" => 0
			)
		)
	),


);



// Calc by pt
foreach ($Resp as $key => $value):
	if ($value["datasheet"] == $_SESSION["objfile"]["name"]):
		$pgreal = strtolower(preg_replace(array("/(º|\/)/"), explode(" ",""), str_replace(" ", "_", utf8_decode(trim($value["posto_graduacao"])))));
		$arrayNum["posto_graduacao"][$pgreal][0] += 1;
		$arrayNum["posto_graduacao"]["total"] += 1;

		$arrayNum["posto_graduacao"][$pgreal][1] = round(($arrayNum["posto_graduacao"][$pgreal][0] * 100) / $arrayNum["posto_graduacao"]["total"]);
	endif;	
endforeach;

// Calc total values by pt
foreach ($arrayNum["posto_graduacao"] as $key => $value) $arrayNum["valor_final"]["total"][0] += $value[0];

// Oficial
$arrayNum["valor_final"]["oficial"][0] = ($arrayNum["posto_graduacao"]["of_capten"][0] + $arrayNum["posto_graduacao"]["of_sup"][0]);

$arrayNum["valor_final"]["oficial"][1] = round(($arrayNum["valor_final"]["oficial"][0] * 100) / $arrayNum["valor_final"]["total"][0]);

// St Sgt
$arrayNum["valor_final"]["st_sgt"][0] = ($arrayNum["posto_graduacao"]["1_sgt"][0] + $arrayNum["posto_graduacao"]["2_sgt"][0] + $arrayNum["posto_graduacao"]["3_sgt"][0]) + ($arrayNum["posto_graduacao"]["st"][0]);
$arrayNum["valor_final"]["st_sgt"][1] = round(($arrayNum["valor_final"]["st_sgt"][0] * 100) / $arrayNum["valor_final"]["total"][0]);

// Cb/ Sd
$arrayNum["valor_final"]["cb_sd"][0] = $arrayNum["posto_graduacao"]["cb_sd"][0];
$arrayNum["valor_final"]["cb_sd"][1] = round(($arrayNum["valor_final"]["cb_sd"][0] * 100) / $arrayNum["valor_final"]["total"][0]);

// $test = getRefc($arrayNum["valor_final"]["oficial"][2][0], "Café");

$arrayNum["valor_final"]["oficial"][2][1] = getRefc($arrayNum["valor_final"]["oficial"][2][0], "Café da manhã");
$arrayNum["valor_final"]["oficial"][2][2] = getRefc($arrayNum["valor_final"]["oficial"][2][0], "Almoço");
$arrayNum["valor_final"]["oficial"][2][3] = getRefc($arrayNum["valor_final"]["oficial"][2][0], "Jantar");

$arrayNum["valor_final"]["st_sgt"][2][1] = getRefc($arrayNum["valor_final"]["st_sgt"][2][0], "Café da manhã");
$arrayNum["valor_final"]["st_sgt"][2][2] = getRefc($arrayNum["valor_final"]["st_sgt"][2][0], "Almoço");
$arrayNum["valor_final"]["st_sgt"][2][3] = getRefc($arrayNum["valor_final"]["st_sgt"][2][0], "Jantar");

$arrayNum["valor_final"]["cb_sd"][2][1] = getRefc($arrayNum["valor_final"]["cb_sd"][2][0], "Café da manhã");
$arrayNum["valor_final"]["cb_sd"][2][2] = getRefc($arrayNum["valor_final"]["cb_sd"][2][0], "Almoço");
$arrayNum["valor_final"]["cb_sd"][2][3] = getRefc($arrayNum["valor_final"]["cb_sd"][2][0], "Jantar");


$arrayNum["valor_final"]["total"]["refc"][0] = ($arrayNum["valor_final"]["oficial"][2][1][0] + $arrayNum["valor_final"]["st_sgt"][2][1][0] + $arrayNum["valor_final"]["cb_sd"][2][1][0]);
$arrayNum["valor_final"]["total"]["refc"][1] = ($arrayNum["valor_final"]["oficial"][2][2][0] + $arrayNum["valor_final"]["st_sgt"][2][2][0] + $arrayNum["valor_final"]["cb_sd"][2][2][0]);
$arrayNum["valor_final"]["total"]["refc"][2] = ($arrayNum["valor_final"]["oficial"][2][3][0] + $arrayNum["valor_final"]["st_sgt"][2][3][0] + $arrayNum["valor_final"]["cb_sd"][2][3][0]);

$arrayNum["valor_final"]["total"]["refc"]["total"] = $arrayNum["valor_final"]["total"]["refc"][0] + $arrayNum["valor_final"]["total"]["refc"][1] + $arrayNum["valor_final"]["total"]["refc"][2];



?>

<!-- <p>Daily Voucher</p> -->



<table>
	
	<tr class="bar-table">
		<td>Cassino</td>
		<td>Café</td>
		<td>Almoço</td>
		<td>Jantar</td>
	</tr>

	<tr>
		<td>Oficial</td>
		<td><?php echo $arrayNum["valor_final"]["oficial"][2][1][0]?></td>
		<td><?php echo $arrayNum["valor_final"]["oficial"][2][2][0]?></td>
		<td><?php echo $arrayNum["valor_final"]["oficial"][2][3][0]?></td>
	</tr>	


	<tr>
		<td>St/ Sgt</td>
		<td><?php echo $arrayNum["valor_final"]["st_sgt"][2][1][0]?></td>
		<td><?php echo $arrayNum["valor_final"]["st_sgt"][2][2][0]?></td>
		<td><?php echo $arrayNum["valor_final"]["st_sgt"][2][3][0]?></td>
	</tr>
	<tr>
		<td>Cb/ Sd</td>
		<td><?php echo $arrayNum["valor_final"]["cb_sd"][2][1][0]?></td>
		<td><?php echo $arrayNum["valor_final"]["cb_sd"][2][2][0]?></td>
		<td><?php echo $arrayNum["valor_final"]["cb_sd"][2][3][0]?></td>
	</tr>

	<tr>
		<td>Total</td>
		<td colspan="3"><?php echo $arrayNum["valor_final"]["total"]["refc"]["total"]?></td>
	</tr>

</table>

<br>

<p>Exibir militar por Cassino</p>
<select name="change_cassino" data-href="<?php echo "report.php?aba=daily-voucher&cassino="?>">
	<option value="oficial" selected <?php echo (isset($get["cassino"]) && $get["cassino"] == "oficial" ? "selected" : null ) ?> >Oficial</option>
	<option value="st_sgt" <?php echo (isset($get["cassino"]) && $get["cassino"] == "st_sgt" ? "selected" : null )?> >St/ Sgt</option>
	<option value="cb_sd" <?php echo (isset($get["cassino"]) && $get["cassino"] == "cb_sd" ? "selected" : null )?> >Cb/ Sd</option>
</select>

<table>
	<tr class="bar-table">
		<td>#</td>
		<td>Nome</td>
		<td>Organização Militar</td>
		<td>Posto/ Graduação</td>
		<td>Refeição</td>
	</tr>

		<?php

		$sheetBody = null;

		$param = (isset($get["cassino"]) && !empty($get["cassino"]) ? $get["cassino"] : "oficial");

		foreach ($arrayNum["valor_final"][$param][2][1][1] as $key => $value):
			
			$refeicoes = array();
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

			$sheetBody .= "<td>" . ++$key . "</td>";
			$sheetBody .= "<td data-content=\"nome\">" . $ObjDecoded["nome"] . "</td>";
			$sheetBody .= "<td data-content=\"organizacao_militar\">" . $ObjDecoded["organizacao_militar"] . "</td>";
			$sheetBody .= "<td data-content=\"posto_graduacao\">" . $ObjDecoded["posto_graduacao"] . "</td>";
			

			

			// Creating the button checkbox and text
			foreach ($ObjDecoded["refc"] as $keyRefc => $valueRefc):

				$newValue = explode(",", $valueRefc[0]);

				foreach ($newValue as $keynewValue => $valor) {
					if (!in_array(trim($valor), $refeicoes))
						array_push($refeicoes, trim($valor));
				}

			endforeach;

			// var_dump($refeicoes);

			$sheetBody .= "<td data-content=\"posto_graduacao\">" . $refeicoes[0] . (isset($refeicoes[1]) && !empty($refeicoes[1]) ? ", " . $refeicoes[1] : null) . (isset($refeicoes[2]) && !empty($refeicoes[2]) ? ", " . $refeicoes[2] : null) . "</td>";

			$sheetBody .= "</tr>";

			// break;

		endforeach;

		echo $sheetBody;

		// var_dump($arrayNum);

		?>

</table>

<script>
	$("select[name=change_cassino]").on("change", function(){
		location.replace($(this).attr("data-href") + $(this).val()); 
	});
</script>