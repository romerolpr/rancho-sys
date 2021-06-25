<?php 

$Resp = $Db->return_query($Db->connect_db(), TB_RESP, null, false, null);

?>

<p>Consultar de tabela</p>
<select name="change_cassino" data-href="<?php echo "report.php?aba=daily-voucher&cassino="?>">
	<option value="oficial" selected <?php echo (isset($get["cassino"]) && $get["cassino"] == "oficial" ? "selected" : null ) ?> >Oficial</option>
	<option value="st_sgt" <?php echo (isset($get["cassino"]) && $get["cassino"] == "st_sgt" ? "selected" : null )?> >St/ Sgt</option>
	<option value="cb_sd" <?php echo (isset($get["cassino"]) && $get["cassino"] == "cb_sd" ? "selected" : null )?> >Cb/ Sd</option>
</select>

<table>
	
	<tr class="bar-table">
		<td>Hash</td>
		<td>Nome</td>
		<td>Email</td>
		<td>Posto/ Graduação</td>
		<td>Organização Militar</td>
	</tr>

	<?php

	$tableBody = null;

	foreach ($Resp as $key => $value):

		$myData = array(
			"hash" => resizeString(strval($value["hash"]), 10, 20, true) . "...",
			"nome" => utf8_decode($value["nome"]),
			"email" => utf8_decode($value["email"]),
			"posto_graduacao" => utf8_decode($value["posto_graduacao"]),
			"organizacao_militar" => utf8_decode($value["organizacao_militar"]),
		);

		if (isset($_SESSION["objfile"])):

			if ($value["datasheet"] == $_SESSION["objfile"]["name"]):

				$tableBody .= "<tr>";
				$tableBody .= "<td class=\"hash_click\" title=\"Expandir: ".$value["hash"]."\" data-desc=\"".$value["hash"]."\">".$myData["hash"]."</td>";
				$tableBody .= "<td>".$myData["nome"]."</td>";
				$tableBody .= "<td>".$myData["email"]."</td>";
				$tableBody .= "<td>".$myData["posto_graduacao"]."</td>";
				$tableBody .= "<td>".$myData["organizacao_militar"]."</td>";
				$tableBody .= "</tr>";

			endif;	

		else:

		endif;

	endforeach;

	echo $tableBody;	

	?>

</table>

<script>
	
	let z = [];
	$(".hash_click").on("click", function(e){
		e.preventDefault();
		if (!z.includes($(this).attr("data-desc"))) {
			$(this).text($(this).attr("data-desc"));
			z.push($(this).attr("data-desc"));
		}
	});

	$("select[name=change_cassino]").on("change", function(){
		location.replace($(this).attr("data-href") + $(this).val()); 
	});
</script>