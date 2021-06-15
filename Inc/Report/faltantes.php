<?php if (count($myList["Missing"]) > 0): ?>

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

</div>

<?php 

$sheetBody = null;

echo "<div class=\"box-table ". (isset($get["filter"]) || isset($get["exb_all"]) ? 'exbAll' : null) ."\">";
echo "<p class=\"fleft d-center-items sticky\">";
echo "<span class=\"fleft\"><strong>Tabela: Faltantes</strong></span>";
echo "<span class=\"fright head_table\">";

if (!isset($get["filter"])):
	echo "<a href=\"".$url."report.php?aba=missing&filter".(isset($get["exb_all"]) ? "&exb_all" : null)."#table-filter\" class=\"btn btn_link btn_manage\" title=\"Visualização de filtro\" id=\"power_filter\"><i class=\"fas fa-filter\"></i></a>";
else:
	echo "<a href=\"".$url."report.php?aba=missing".(isset($get["exb_all"]) ? "&exb_all" : null)."#table-filter\" class=\"btn btn_link btn_manage btn_active\" title=\"Desativar modo: Visualização de filtro\"><i class=\"fas fa-filter\"></i></a>";
endif;

echo "<a href=\"".$url."index.php\" class=\"btn btn_link btn_manage btn_expand\" title=\"Expandir tabela\"><i class=\"fas fa-expand\"></i></a>";

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
	<td>Refeição</td>
</tr>
	
<?php

if (isset($get["sort"])):
	$getSort = explode(":", $get["sort"]);
	aasort($Resp, $getSort[0], $getSort[1]);
endif; 

$id = 0;

foreach ($Resp as $key => $value):
	
	if (in_array($value["hash"], $myList["Missing"]) && $value["datasheet"] == $_SESSION['objfile']['name']):

		$arrayNumStatus["posto_graduacao"][encodeRegexPg($value["posto_graduacao"])][0] += 1;
		$arrayNumStatus["organizacao_militar"][encodeRegexPg($value["organizacao_militar"])][0] += 1;

		$id++;

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

		$sheetBody .= "<td>" . $id . "</td>";
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

	endif;

endforeach;


echo $sheetBody;

foreach ($arrayNumStatus["posto_graduacao"] as $key => $value)
	$arrayNumStatus["total"] += $value[0];

?>

</table></div>



<script>
	$(document).ready(function(){
		var ctx = [
				document.getElementById('chart_faltantes_pg'), 
				document.getElementById('chart_faltantes_om'), 
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
		}
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

	});
			
</script>

<?php 

else:

	$Alert->setConfig("warning", "<strong>Aviso</strong>: Não possui registros de militares faltantes no momento.");
	echo $Alert->displayPrint();

endif; 

?>