<?php 

include_once 'config/daily-voucher.config.php'; 
?>


<table>
	
	<tr class="bar-table">
		<td>Cassino</td>
		<td>Café</td>
		<td>Almoço</td>
		<td>Jantar</td>
	</tr>

	<tr>
		<td>Oficial</td>
		<td><?php echo $arrayNumVoucher["valor_final"]["oficial"][2][1][0]?></td>
		<td><?php echo $arrayNumVoucher["valor_final"]["oficial"][2][2][0]?></td>
		<td><?php echo $arrayNumVoucher["valor_final"]["oficial"][2][3][0]?></td>
	</tr>	


	<tr>
		<td>St/ Sgt</td>
		<td><?php echo $arrayNumVoucher["valor_final"]["st_sgt"][2][1][0]?></td>
		<td><?php echo $arrayNumVoucher["valor_final"]["st_sgt"][2][2][0]?></td>
		<td><?php echo $arrayNumVoucher["valor_final"]["st_sgt"][2][3][0]?></td>
	</tr>
	<tr>
		<td>Cb/ Sd</td>
		<td><?php echo $arrayNumVoucher["valor_final"]["cb_sd"][2][1][0]?></td>
		<td><?php echo $arrayNumVoucher["valor_final"]["cb_sd"][2][2][0]?></td>
		<td><?php echo $arrayNumVoucher["valor_final"]["cb_sd"][2][3][0]?></td>
	</tr>

	<tr>
		<td>Total</td>
		<td colspan="3"><?php echo $arrayNumVoucher["valor_final"]["total"]["refc"]["total"]?></td>
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

		foreach ($arrayNumVoucher["valor_final"][$param][2][1][1] as $key => $value):
			
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

		// var_dump($arrayNumVoucher);

		?>

</table>

<script>
	$("select[name=change_cassino]").on("change", function(){
		location.replace($(this).attr("data-href") + $(this).val()); 
	});
</script>