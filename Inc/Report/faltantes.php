<?php 

$days = array();
$daysNonSorted = array();
foreach ($Resp as $key => $value):
	if ($value["datasheet"] == $_SESSION['objfile']['name']):

		$seg = explode("&&", $value["segunda_feira"]);
		$ter = explode("&&", $value["terca_feira"]);
		$qua = explode("&&", $value["quarta_feira"]);
		$qui = explode("&&", $value["quinta_feira"]);
		$sex = explode("&&", $value["sexta_feira"]);
		$sab = explode("&&", $value["sabado"]);
		$dom = explode("&&", $value["domingo"]);

		$arr = array(
			"seg" => end($seg),
			"ter" => end($ter),
			"qua" => end($qua),
			"qui" => end($qui),
			"sex" => end($sex),
			"sab" => end($sab),
			"dom" => end($dom),
		);

		$days = $arr;
		$daysNonSorted = $arr;

	endif;
endforeach;
sort($days);

$sheetBody = null;

$maxMissingByRefc = array(
	"Café" => 0,
	"Almoço" => 0,
	"Jantar" => 0,
	"Todos" => 0
);

if (isset($get["gpdf-view"])): 

	if (count($listPresent) > 0):

	aasort($Resp, "A-Z", "nome");
	$countItem=0;

	foreach ($Resp as $key => $value):
		
		if ($value["datasheet"] == $_SESSION['objfile']['name']):

			// if (!in_array($value["hash"], $myList["Present"])):

			$contagemRefc = array();
			$contagemRefcDays = array();

			foreach ($listPresent as $keyRefc => $valueRefc):

				if ($valueRefc[0] == $value["hash"]):

					foreach ($valueRefc[1] as $refckey => $refcvalue) {

						$newValue = explode(";", $refcvalue);

						foreach ($newValue as $keynewValue => $valor) {

							if (!$keynewValue % 2) {
								array_push($contagemRefc, trim($valor));
							} else {
								array_push($contagemRefcDays, trim($valor));
							}
						}

					}

				endif;

			endforeach;

			$contagem = array_combine_($contagemRefcDays, $contagemRefc);

			if (isset($get["rel"])):


				$str = array(
					"Café" => 0,
					"Almoço" => 0,
					"Jantar" => 0,
				);

				foreach ($contagem as $keycontagem => $contagemVal) {
					if ($keycontagem == decode_to_url($get["rel"])){

						if (!is_array($contagemVal)):
							if (trim($contagemVal) == "Café"):
								$str["Café"] += 1;
							endif;

							if (trim($contagemVal) == "Almoço"):
								$str["Almoço"] += 1;
							endif;

							if (trim($contagemVal) == "Jantar"):
								$str["Jantar"] += 1;
							endif;
						else:
							foreach ($contagemVal as $keycontagemVal => $valuecontagemVal) {
								if (trim($valuecontagemVal) == "Café"):
									$str["Café"] += 1;
								endif;

								if (trim($valuecontagemVal) == "Almoço"):
									$str["Almoço"] += 1;
								endif;

								if (trim($valuecontagemVal) == "Jantar"):
									$str["Jantar"] += 1;
								endif;
							}
						endif;
				
					}
				}

			endif;


			preg_match_all("/Café/", implode(",", $contagemRefc), $myCafe);
			preg_match_all("/Almoço/", implode(",", $contagemRefc), $myAlmoco);
			preg_match_all("/Jantar/", implode(",", $contagemRefc), $myJantar);

			$myInfo = array(
				"Café" => count($myCafe[0]),
				"Almoço" => count($myAlmoco[0]),
				"Jantar" => count($myJantar[0]),
			);


			$maxMissingByRefc["Café"] += count($myCafe[0]);
			$maxMissingByRefc["Almoço"] += count($myAlmoco[0]);
			$maxMissingByRefc["Jantar"] += count($myJantar[0]);

			$arrayNumStatus["posto_graduacao"][encodeRegexPg($value["posto_graduacao"])][0] += 1;
			$arrayNumStatus["organizacao_militar"][encodeRegexPg($value["organizacao_militar"])][0] += 1;

			$ObjDecoded = array(
				"nome" => ucfirst(strtolower(utf8_decode(trim($value["nome"])))),
				"posto_graduacao" => utf8_decode($value["posto_graduacao"]),
				"organizacao_militar" => utf8_decode($value["organizacao_militar"]),
			);

			if ($get["rel"] != "semanal"):

				if ($str["Café"] == 0 && $str["Almoço"] == 0 && $str["Jantar"] == 0):
					// nothing
				else:

					$countItem++;

					$sheetBody .= "<tr style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\" data-hash=\"".$value["hash"]."\" ". ((in_array($value["hash"], $myList["Complete"])) ? "class=\"d-none\"" : null) .">";

					$sheetBody .= "<td style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\" data-content=\"organizacao_militar\">" . $ObjDecoded["organizacao_militar"] . "</td>";
					$sheetBody .= "<td style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\" data-content=\"posto_graduacao\">" . $ObjDecoded["posto_graduacao"] . "</td>";
					$sheetBody .= "<td style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\" data-content=\"nome\">" . $ObjDecoded["nome"] . "</td>";

					$sheetBody .= "<td style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\">";
					if ($str["Café"] > 0)
						$sheetBody .= "X";
					$sheetBody .= "</td>";

					$sheetBody .= "<td style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\">";
						if ($str["Almoço"] > 0)
							$sheetBody .= "X";
					$sheetBody .= "</td>";
					
					$sheetBody .= "<td style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\">";
						if ($str["Jantar"] > 0) 
							$sheetBody .= "X";
					$sheetBody .= "</td>";

				endif;

			else:

				if (!empty($contagemRefc)):

					$countItem++;
				
					$sheetBody .= "<tr style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\" data-hash=\"".$value["hash"]."\" ". ((in_array($value["hash"], $myList["Complete"])) ? "class=\"d-none\"" : null) .">";

					$sheetBody .= "<td style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\" data-content=\"organizacao_militar\">" . $ObjDecoded["organizacao_militar"] . "</td>";
					$sheetBody .= "<td style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\" data-content=\"posto_graduacao\">" . $ObjDecoded["posto_graduacao"] . "</td>";
					$sheetBody .= "<td style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\" data-content=\"nome\">" . $ObjDecoded["nome"] . "</td>";

					$sheetBody .= "<td style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\">";
					if ($myInfo["Café"] > 0)
						$sheetBody .= "X";
					$sheetBody .= "</td>";

					$sheetBody .= "<td style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\">";
						if ($myInfo["Almoço"] > 0)
							$sheetBody .= "X";
					$sheetBody .= "</td>";
					
					$sheetBody .= "<td style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\">";
						if ($myInfo["Jantar"] > 0) 
							$sheetBody .= "X";
					$sheetBody .= "</td>";
				else:

					if (!isset($get["none"])):

						$sheetBody .= "<tr style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\" data-hash=\"".$value["hash"]."\" ". ((in_array($value["hash"], $myList["Complete"])) ? "class=\"d-none\"" : null) .">";

						$sheetBody .= "<td style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\" data-content=\"organizacao_militar\">" . $ObjDecoded["organizacao_militar"] . "</td>";
						$sheetBody .= "<td style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\" data-content=\"posto_graduacao\">" . $ObjDecoded["posto_graduacao"] . "</td>";
						$sheetBody .= "<td style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\" data-content=\"nome\">" . $ObjDecoded["nome"] . "</td>";

						$sheetBody .= "<td colspan=\"3\" style=\"text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;\">";
						$sheetBody .= "Restando todos";
						$sheetBody .= "</td>";

					endif;


				endif;

			endif;

			$sheetBody .= "</tr>";

		endif;

	endforeach;



?>

<div class="btn_export">
	<a id="dlink"  style="display:none;"></a>
	<input type="button" class="btnExport btn" id="btnExport" value="Exportar para Excel" />
	<input type="button" class="btnExport btn" id="btnExportPdf" value="Exportar para PDF" />
	<input type="button" class="btnExport btn <?php echo (isset($get["none"]) ? "btn_active_input" : null) ?>" id="btnRefc" value="<?php echo (!isset($get["none"]) ? "Apenas refeições" : "Mostrar tudo") ?>" data-href="<?php echo "report.php?aba=missing&gpdf-view=1&doc=faltantes&rel=" . (isset($get["rel"]) ? $get["rel"] : "semanal") . (!isset($get["none"]) ? "&none" : null)?>"/>

	<p>Relatório</p>
	<select name="change_relatorio" data-href="<?php echo "report.php?aba=missing&gpdf-view=1&doc=faltantes&rel="?>">
		<option value="semanal">Semanal</option>
		<?php foreach ($days as $abrDay => $day): ?>
			<option value="<?php echo encode_to_url($day)?>" <?php if (isset($get["rel"]) && $get["rel"] == encode_to_url($day)) echo "selected" ?> ><?php echo $day?> (<?php echo convertDayName($day, true)?>)</option>
		<?php endforeach; ?>
	</select>

	<script>
		$("select[name=change_relatorio]").on("change", function(){
			location.replace($(this).attr("data-href") + $(this).val()); 
		});
	</script>
</div>

<table class="gpdf <?php echo (isset($get["gpdf-view"]) && $get["gpdf-view"] == 1) ? 'window_fixed m-0' : null ?>" cellspacing="0" id="table-gpdf" <?php echo (isset($get["gpdf-view"]) && $get["gpdf-view"] == 1) ? 'style="position:absolute!important;width: auto;"' : "style='width: auto;'" ?>>
	
	<tr style="text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;">
		<td style="text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;text-transform: uppercase;" colspan="6">DATA: 
		<?php 
		if (isset($get["rel"]) && $get["rel"] != "semanal"):
			$dataEmissao = new DateTime($get["rel"]);
			echo $dataEmissao->format("d/ M/ Y");
		else:
			// var_dump($daysNonSorted);
			$dataEmissaoInicial = new DateTime(encode_to_url($daysNonSorted["seg"]));
			$dataEmissaoFinal = new DateTime(encode_to_url($daysNonSorted["dom"]));
			echo $dataEmissaoInicial->format("d/ M/ Y") . " - " . $dataEmissaoFinal->format("d/ M/ Y");
		endif;
		?></td>
	</tr>
	<tr style="text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;">
		<td style="text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;" colspan="6">Militares que faltaram nas Refeições</td>
	</tr>
	<tr style="text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;">
		<td style="text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;">OM</td>
		<td style="text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;">Graduação</td>
		<td style="text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto; width: 100%;">Nome</td>
		<td style="text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;">Café</td>
		<td style="text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;">Almoço</td>
		<td style="text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: 'Times New Roman', 'Arial'; font-weight: bold; color: #000; height: auto;">Janta</td>
	</tr>

	<?php 

	if($countItem > 0):

		echo $sheetBody;

	else:

		echo '<td colspan="6" style="text-align:center;border: 1px solid #000; background: #fff; font-size: 1em; font-family: \'Times New Roman\', \'Arial\'; font-weight: bold; color: #000; height: auto;">Não existem dados registrados no dia selecionado</td>';
			// $Alert->setConfig("warning", "<strong>Aviso</strong>: Não existem dados registrados no dia selecionado.");
			// echo $Alert->displayPrint();

	endif;


	?>

</table>

<iframe id="txtArea1" style="display:none"></iframe>

<script>
	$("#btnExport").click(function (e) {
	    // exportReportToExcel(this);
	    tableToExcel('table-gpdf', '<?php echo $title?>');
	    e.preventDefault();
	});

	$("#btnExportPdf").click(function (e) {
	    tableToPDF('table-gpdf', '<?php echo $title?>');
	    e.preventDefault();
	});

	$("#btnRefc").click(function (e) {
	    window.location.replace($(this).attr("data-href"));
	    e.preventDefault();
	});
</script>

<?php 

	else:

		$Alert->setConfig("warning", "<strong>Aviso:</strong> Não existem dados suficientes para gerar tabela. <a href=\"report.php?aba=missing\" title=\"Voltar\" class=\"link\">Voltar</a>");
		echo $Alert->displayPrint();

		$showSimpleView = true;

	endif;

else:

	if (count($myList["Missing"]) > 0 || isset($showSimpleView)): 

?>

<div class="box-bg-board center">

	<!-- <div class="box-board">
		<div class="bg-shadow">
			<canvas id="chart_faltantes_pg" width="400" height="400"></canvas>
		</div>
	</div>
	<div class="box-board">
		<div class="bg-shadow">
			<canvas id="chart_faltantes_om" width="400" height="400"></canvas>
		</div>
	</div> -->

	<div class="box-board">
		<div class="bg-shadow">
			<canvas id="chart_faltantes_by_refc" width="400" height="400"></canvas>
		</div>
	</div>

</div>

<?php 

	echo "<div class=\"box-table ". (isset($get["filter"]) || isset($get["exb_all"]) ? 'exbAll' : null) ."\">";
	echo "<p class=\"fleft d-center-items sticky\">";
	echo "<span class=\"fleft\"><strong>Tabela: Faltantes</strong></span>";
	echo "<span class=\"fright head_table\">";

	if (!isset($get["filter"])):
		echo "<a href=\"".$url."report.php?aba=missing&filter".(isset($get["exb_all"]) ? "&exb_all" : null)."#table-filter\" class=\"btn btn_link btn_manage\" title=\"Visualização de filtro\" id=\"power_filter\"><i class=\"fas fa-filter\"></i></a>";
	else:
		echo "<a href=\"".$url."report.php?aba=missing".(isset($get["exb_all"]) ? "&exb_all" : null)."#table-filter\" class=\"btn btn_link btn_manage btn_active\" title=\"Desativar modo: Visualização de filtro\"><i class=\"fas fa-filter\"></i></a>";
	endif;

	echo "<a href=\"".$url."report.php?aba=missing&gpdf-view=1&doc=faltantes&rel=".(!isset($get["rel"]) ? "semanal" : $get["rel"] )."\" class=\"btn btn_link btn_manage\" title=\"Exportar tabela\" target=\"_blank\">Exportar tabela</a>";

	// echo "<a href=\"".$url."report.php?aba=missing\" class=\"btn btn_link btn_manage btn_expand\" title=\"Expandir tabela\"><i class=\"fas fa-expand\"></i></a>";

	echo "<table id=\"table-filter\" data-exb=\"".((!isset($get["exb_all"])) ? "default" : "exb_all")."\" class=\"min-h\">";

	if(isset($get["filter"])): 
		echo '<div class="fright mb-2">';
			echo '<div class="box">';
				echo '<span class="label">Filtrar por: </span>';
				echo '<input type="text" name="searchByName" class="searchbar" id="searchbar" placeholder="Qualquer valor...">';
			echo '</div>';
		echo '</div>';
	endif;

	echo "</span></p>";

?>

<p>Exibir relatório</p>
<select name="change_relatorio" data-href="<?php echo "report.php?aba=missing&rel="?>">
	<option value="semanal">Semanal</option>
	<?php foreach ($days as $abrDay => $day): ?>
		<option value="<?php echo encode_to_url($day)?>" <?php if (isset($get["rel"]) && $get["rel"] == encode_to_url($day)) echo "selected" ?> ><?php echo $day?> (<?php echo convertDayName($day, true)?>)</option>
	<?php endforeach; ?>
</select>

<script>
	$("select[name=change_relatorio]").on("change", function(){
		location.replace($(this).attr("data-href") + $(this).val()); 
	});
</script>

<tr class="bar-table">
	<!-- <td>#</td> -->
	<?php if(isset($get["filter"])): ?>
		<td class="td-button" data-content-children="nome">
			<div><span data-filter="nome" class="btn btn_order fright"><i class="fas fa-sort-down"></i><div class="sub-dropdown drop-render"></div></span></div>
			<div>
				<span>Nome</span>
			</div>
		</td>
	<?php else: ?>
		<td>Nome</td>
	<?php endif; ?>
	<?php if(isset($get["filter"])): ?>
		<td class="td-button" data-content-children="organizacao_militar">
			<div><span data-filter="organizacao_militar" class="btn btn_order fright"><i class="fas fa-sort-down"></i><div class="sub-dropdown drop-render"></div></span></div>
			<div>
				<span>Organização Militar</span>
			</div>
		</td>
		<td class="td-button" data-content-children="posto_graduacao">
			<div><span data-filter="posto_graduacao" class="btn btn_order fright"><i class="fas fa-sort-down"></i><div class="sub-dropdown drop-render"></div></span></div>
			<div>
				<span>Posto/ Graduação</span>
			</div>
		</td>
	<?php else: ?>
		<td>Organização Militar</td>
		<td>Posto/ Graduação</td>
	<?php endif; ?>
	<td><?php if (isset($get["rel"]) && $get["rel"] !== "semanal") echo "Relatório: " . decode_to_url($get["rel"]) . "<br>" ?>Refeição (qnt. Restando)</td>
</tr>
	
<?php

	$id = 0;
	sortByColumns($Resp);

	foreach ($Resp as $key => $value):
		
		if ($value["datasheet"] == $_SESSION['objfile']['name']):

			// if (!in_array($value["hash"], $myList["Present"])):

			$contagemRefc = array();
			$contagemRefcDays = array();

			foreach ($listPresent as $keyRefc => $valueRefc):

				if ($valueRefc[0] == $value["hash"]):

					foreach ($valueRefc[1] as $refckey => $refcvalue) {

						$newValue = explode(";", $refcvalue);

						foreach ($newValue as $keynewValue => $valor) {
							// var_dump($valor);

							if (!$keynewValue % 2) {
								array_push($contagemRefc, trim($valor));
							} else {
								array_push($contagemRefcDays, trim($valor));
							}
		
						}

					}

				endif;

			endforeach;

			$ObjDecoded = array(
				"nome" => ucfirst(strtolower(utf8_decode(trim($value["nome"])))),
				"posto_graduacao" => utf8_decode($value["posto_graduacao"]),
				"organizacao_militar" => utf8_decode($value["organizacao_militar"]),
			);

			$contagem = array_combine_($contagemRefcDays, $contagemRefc);


			if (isset($get["rel"])):


				$str = array(
					"Café" => 0,
					"Almoço" => 0,
					"Jantar" => 0,
				);

				foreach ($contagem as $keycontagem => $contagemVal) {
					if ($keycontagem == decode_to_url($get["rel"])){

						if (!is_array($contagemVal)):
							if (trim($contagemVal) == "Café"):
								$str["Café"] += 1;
							endif;

							if (trim($contagemVal) == "Almoço"):
								$str["Almoço"] += 1;
							endif;

							if (trim($contagemVal) == "Jantar"):
								$str["Jantar"] += 1;
							endif;
						else:
							foreach ($contagemVal as $keycontagemVal => $valuecontagemVal) {
								if (trim($valuecontagemVal) == "Café"):
									$str["Café"] += 1;
								endif;

								if (trim($valuecontagemVal) == "Almoço"):
									$str["Almoço"] += 1;
								endif;

								if (trim($valuecontagemVal) == "Jantar"):
									$str["Jantar"] += 1;
								endif;
							}
						endif;
				
					}
				}

			endif;


			preg_match_all("/Café/", implode(",", $contagemRefc), $myCafe);
			preg_match_all("/Almoço/", implode(",", $contagemRefc), $myAlmoco);
			preg_match_all("/Jantar/", implode(",", $contagemRefc), $myJantar);

			$myInfo = array(
				"Café" => count($myCafe[0]),
				"Almoço" => count($myAlmoco[0]),
				"Jantar" => count($myJantar[0]),
			);

			$maxMissingByRefc["Café"] += count($myCafe[0]);
			$maxMissingByRefc["Almoço"] += count($myAlmoco[0]);
			$maxMissingByRefc["Jantar"] += count($myJantar[0]);

			$arrayNumStatus["posto_graduacao"][encodeRegexPg($value["posto_graduacao"])][0] += 1;
			$arrayNumStatus["organizacao_militar"][encodeRegexPg($value["organizacao_militar"])][0] += 1;

			$id++;

			if (isset($get["rel"]) && $get["rel"] !== "semanal"):

				if ($str["Café"] == 0 && $str["Almoço"] == 0 && $str["Jantar"] == 0):
					// nothing
				else:

					$sheetBody .= "<tr data-hash=\"".$value["hash"]."\" ". ((in_array($value["hash"], $myList["Complete"])) ? "class=\"d-none\"" : null) .">";

					// $sheetBody .= "<td>" . $id . "</td>";
					$sheetBody .= "<td data-content=\"nome\">" . $ObjDecoded["nome"] . "</td>";
					$sheetBody .= "<td data-content=\"organizacao_militar\">" . $ObjDecoded["organizacao_militar"] . "</td>";
					$sheetBody .= "<td data-content=\"posto_graduacao\">" . $ObjDecoded["posto_graduacao"] . "</td>";
					$sheetBody .= "<td data-content=\"posto_graduacao\">";


					if ($str["Café"] > 0) {
						$sheetBody .= "Café (" . $str["Café"] . ")" . ( ($str["Almoço"] > 0) ? ", " : null );
					}

					if ($str["Almoço"] > 0) {
						$sheetBody .= "Almoço (" . $str["Almoço"] . ")" . ( ($str["Jantar"] > 0) ? ", " : null );
					}
					
					if ($str["Jantar"] > 0) {
						$sheetBody .= " Jantar (" . $str["Jantar"] . ")";
					}

					if (empty($contagem)):
						if (!in_array($value["hash"], $myList["Present"])):
							$sheetBody .= "Restando todos";
							$maxMissingByRefc["Todos"] += 1;
						else:
							$sheetBody .= "Completo";
						endif;
					endif;

				endif;

			else:

				$sheetBody .= "<tr data-hash=\"".$value["hash"]."\" ". ((in_array($value["hash"], $myList["Complete"])) ? "class=\"d-none\"" : null) .">";

				// $sheetBody .= "<td>" . $id . "</td>";
				$sheetBody .= "<td data-content=\"nome\">" . $ObjDecoded["nome"] . "</td>";
				$sheetBody .= "<td data-content=\"organizacao_militar\">" . $ObjDecoded["organizacao_militar"] . "</td>";
				$sheetBody .= "<td data-content=\"posto_graduacao\">" . $ObjDecoded["posto_graduacao"] . "</td>";
				$sheetBody .= "<td data-content=\"posto_graduacao\">";

				if ($myInfo["Café"] > 0)
					$sheetBody .= "Café (" . $myInfo["Café"] . ")" . ( ($myInfo["Almoço"] > 0) ? ", " : null );

				if ($myInfo["Almoço"] > 0)
					$sheetBody .= "Almoço (" . $myInfo["Almoço"] . ")" . ( ($myInfo["Jantar"] > 0) ? ", " : null );
				
				if ($myInfo["Jantar"] > 0) 
					$sheetBody .= " Jantar (" . $myInfo["Jantar"] . ")";

				if (empty($contagemRefc)):
					if (!in_array($value["hash"], $myList["Present"])):
						$sheetBody .= "Restando todos";
						$maxMissingByRefc["Todos"] += 1;
					else:
						$sheetBody .= "Completo";
					endif;
				endif;
			endif;

			$sheetBody .= "</tr>";


		endif;

	endforeach;

	echo $sheetBody;

	foreach ($arrayNumStatus["posto_graduacao"] as $key => $value)
		$arrayNumStatus["total"] += $value[0];

	$max = max($maxMissingByRefc);
	foreach ($maxMissingByRefc as $key => $value) {
		if ($max == $value)
			$max = $key;
	}

?>

</table></div>

<script>
	$(document).ready(function(){
		var ctx = [
				document.getElementById('chart_faltantes_pg'), 
				document.getElementById('chart_faltantes_om'), 
				document.getElementById('chart_faltantes_by_refc'), 
			],
			labels = [
				'Of cap/ten',
				'1º sgt',
				'2º sgt',
				'St',
				'Of sup',
				'3º sgt',
				'Civil'
			],
			labels_om = [
				'B ADM AP IBIRAPUERA',
				'8º BPE',
				'Apoio Direto',
				'AGSP'
			],
			labels_refc = [
				'Café',
				'Almoço',
				'Jantar',
				'Restando Todos'
			];

		const options = [
		{
	        tooltips: {
	                enabled: false
	            },
            plugins: {
            	title: {
            	    display: true,
            	    text: 'Faltantes: Posto/ Graduação'
            	},
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += data;
                        });
                        let percentage = (value*100 / sum).toFixed(2)+"%";
                        return percentage;
                    },
                    color: '#fff',
                }
            }
	    },
	    {
	        tooltips: {
	                enabled: false
	            },
            plugins: {
            	title: {
            	    display: true,
            	    text: 'Faltantes: Organização Militar'
            	},
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += data;
                        });
                        let percentage = (value*100 / sum).toFixed(2)+"%";
                        return percentage;
                    },
                    color: '#fff',
                }
            }
	    },
	    {
	        tooltips: {
		        enabled: false
		    },
            plugins: {
            	title: {
            	    display: true,
            	   	text: 'Faltantes: Por refeição'
            	},
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += data;
                        });
                        let percentage = (value*100 / sum).toFixed(2)+"%";
                        return percentage;
                    },
                    color: '#fff',
                }
            }
	    }
	    ]

	    const data = [
	    {
		  labels: labels,
		  datasets: [{
		    // label: 'Arranchados',
		    data: [
		    <?php 
		    	foreach ($arrayNumStatus['posto_graduacao'] as $key => $value) echo $value[0] . ", ";
		    ?>
		    ],
		    backgroundColor: [
		      <?php 
		      sort($backgroundColors);
		      for ($i=0; $i <= $arrayNumStatus['posto_graduacao'] ; $i++):
		      	if (isset($backgroundColors[$i])):
		      		echo "\"$backgroundColors[$i]\",\n";
		      	else:
		      		echo '"rgb(238, 238, 238)",';
		      		break;
		      	endif;
		      endfor;
		      ?>
		    ],
		    hoverOffset: 4
		  }]
		},
		{
		  labels: labels_om,
		  datasets: [{
		    // label: 'Arranchados',
		    data: [
		    <?php 
		    	foreach ($arrayNumStatus['organizacao_militar'] as $key => $value) echo $value[0] . ", ";
		    ?>
		    ],
		    backgroundColor: [
		      <?php 
		      sort($backgroundColors);
		      for ($i=0; $i <= $arrayNumStatus['organizacao_militar'] ; $i++):
		      	if (isset($backgroundColors[$i])):
		      		echo "\"$backgroundColors[$i]\",\n";
		      	else:
		      		echo '"rgb(238, 238, 238)",';
		      		break;
		      	endif;
		      endfor;
		      ?>
		    ],
		    hoverOffset: 4
		  }]
		},
		{
		  labels: labels_refc,
		  datasets: [{
		    label: 'Valores',
		    data: [
		    <?php 
		    	foreach ($maxMissingByRefc as $key => $value) echo $value . ", ";
		    ?>
		    ],
		    backgroundColor: [
		      <?php 
		      sort($backgroundColors);
		      for ($i=0; $i <= $arrayNumStatus['posto_graduacao'] ; $i++):
		      	if (isset($backgroundColors[$i])):
		      		echo "\"$backgroundColors[$i]\",\n";
		      	else:
		      		echo '"rgb(238, 238, 238)",';
		      		break;
		      	endif;
		      endfor;
		      ?>
		    ],
		    hoverOffset: 4
		  }]
		},
		];

		// new Chart(ctx[0], {
		//     type: 'pie',
		//     data: data[0],
		//     options: options[0]
		// });

		// new Chart(ctx[1], {
		//     type: 'pie',
		//     data: data[1],
		//     options: options[1]
		// });

		new Chart(ctx[2], {
		    type: 'pie',
		    data: data[2],
		    options: options[2]
		});

	});
			
</script>

<?php 

	else:

		$Alert->setConfig("warning", "<strong>Aviso</strong>: Não possui registros de militares faltantes no momento.");
		echo $Alert->displayPrint();

	endif; 

endif; 