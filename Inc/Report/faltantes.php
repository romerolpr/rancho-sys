<?php 

$sheetBody = null;

$maxMissingByRefc = array(
	"Café" => 0,
	"Almoço" => 0,
	"Jantar" => 0,
	"Todos" => 0
);

if (isset($get["gpdf-view"])): 


	aasort($Resp, "A-Z", "nome");

	foreach ($Resp as $key => $value):
		
		if ($value["datasheet"] == $_SESSION['objfile']['name']):

			// if (!in_array($value["hash"], $myList["Present"])):

			$contagemRefc = array();

			foreach ($listPresent as $keyRefc => $valueRefc):

				if ($valueRefc[0] == $value["hash"]):

					foreach ($valueRefc[1] as $refckey => $refcvalue) {

						$newValue = explode(";", $refcvalue);

						foreach ($newValue as $keynewValue => $valor) {
							if (!$keynewValue % 2)
								array_push($contagemRefc, trim($valor));
						}

					}

				endif;

			endforeach;

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

			if (!empty($contagemRefc)):

				$sheetBody .= "<tr data-hash=\"".$value["hash"]."\" ". ((in_array($value["hash"], $myList["Complete"])) ? "class=\"d-none\"" : null) .">";

				$sheetBody .= "<td data-content=\"organizacao_militar\">" . $ObjDecoded["organizacao_militar"] . "</td>";
				$sheetBody .= "<td data-content=\"posto_graduacao\">" . $ObjDecoded["posto_graduacao"] . "</td>";
				$sheetBody .= "<td data-content=\"nome\">" . $ObjDecoded["nome"] . "</td>";
				
				$sheetBody .= "<td>";
				if ($myInfo["Café"] > 0)
					$sheetBody .= "X";
				$sheetBody .= "</td>";

				$sheetBody .= "<td>";
					if ($myInfo["Almoço"] > 0)
						$sheetBody .= "X";
				$sheetBody .= "</td>";
				
				$sheetBody .= "<td>";
					if ($myInfo["Jantar"] > 0) 
						$sheetBody .= "X";
				$sheetBody .= "</td>";
				
				$sheetBody .= "</tr>";

			endif;

		endif;

	endforeach;

?>

<table class="gpdf" cellspacing="1">
	
	<tr>
		<td colspan="7" style="text-transform: uppercase;">DATA EMISSÃO: <?php echo $date->format("d/ M/ Y - H:i:s"); ?></td>
	</tr>
	<tr>
		<td colspan="7">Militares que faltaram nas Refeições</td>
	</tr>
	<tr>
		<td>OM</td>
		<td>Graduação</td>
		<td>Nome</td>
		<td>Café</td>
		<td>Almoço</td>
		<td>Janta</td>
		<!-- <td>Não compareceu</td> -->
	</tr>

	<?php echo $sheetBody?>

</table>

<?php 

else:

	if (count($myList["Missing"]) > 0): 

?>

<div class="box-bg-board center">

	<div class="box-board">
		<div class="bg-shadow">
			<canvas id="chart_faltantes_pg" width="400" height="400"></canvas>
		</div>
	</div>
	<div class="box-board">
		<div class="bg-shadow">
			<canvas id="chart_faltantes_om" width="400" height="400"></canvas>
		</div>
	</div>

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

	echo "<a href=\"".$url."report.php?aba=missing&gpdf-view\" class=\"btn btn_link btn_manage\" title=\"Gerar PDF\" target=\"_blank\"><i class=\"fas fa-file-pdf\"></i></a>";

	echo "<a href=\"".$url."report.php?aba=missing\" class=\"btn btn_link btn_manage btn_expand\" title=\"Expandir tabela\"><i class=\"fas fa-expand\"></i></a>";

	echo "<table id=\"table-filter\" data-exb=\"".((!isset($get["exb_all"])) ? "default" : "exb_all")."\" class=\"min-h\">";

	if(isset($get["filter"])): 
		echo '<div class="fright mb-2">';
			echo '<div class="box">';
				echo '<span class="label">Filtrar por: </span>';
				echo '<input type="text" name="searchByName" class="searchbar" id="searchbar" placeholder="Nome, arranchamento...">';
			echo '</div>';
		echo '</div>';
	endif;

	echo "</span></p>";

?>

<tr class="bar-table">
	<td>#</td>
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
	<td>Refeição (Restando)</td>
</tr>
	
<?php

	if (isset($get["sort"])):
		$getSort = explode(":", $get["sort"]);
		aasort($Resp, $getSort[0], $getSort[1]);
	endif; 

	$id = 0;

	foreach ($Resp as $key => $value):
		
		if ($value["datasheet"] == $_SESSION['objfile']['name']):

			// if (!in_array($value["hash"], $myList["Present"])):

			$contagemRefc = array();

			foreach ($listPresent as $keyRefc => $valueRefc):

				if ($valueRefc[0] == $value["hash"]):

					foreach ($valueRefc[1] as $refckey => $refcvalue) {

						$newValue = explode(";", $refcvalue);

						foreach ($newValue as $keynewValue => $valor) {
							if (!$keynewValue % 2)
								array_push($contagemRefc, trim($valor));
						}

					}

				endif;

			endforeach;

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

			$ObjDecoded = array(
				"nome" => ucfirst(strtolower(utf8_decode(trim($value["nome"])))),
				"posto_graduacao" => utf8_decode($value["posto_graduacao"]),
				"organizacao_militar" => utf8_decode($value["organizacao_militar"]),
			);

			$sheetBody .= "<tr data-hash=\"".$value["hash"]."\" ". ((in_array($value["hash"], $myList["Complete"])) ? "class=\"d-none\"" : null) .">";

			$sheetBody .= "<td>" . $id . "</td>";
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
	        plugins: {
	            title: {
	                display: true,
	                text: 'Faltantes: Posto/ Graduação'
	            }
	        }
	    },
	    {
	        plugins: {
	            title: {
	                display: true,
	                text: 'Faltantes: Organização Militar'
	            }
	        }
	    },
	    {
	        plugins: {
	            title: {
	                display: true,
	                text: 'Faltantes: Por refeição'
	            },
	            tooltips: {
                    enabled: false
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

		new Chart(ctx[0], {
		    type: 'pie',
		    data: data[0],
		    options: options[0]
		});

		new Chart(ctx[1], {
		    type: 'pie',
		    data: data[1],
		    options: options[1]
		});

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