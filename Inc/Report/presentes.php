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

$maxMissingByRefc = array(
	"Café" => 0,
	"Almoço" => 0,
	"Jantar" => 0,
	"Todos" => 0
);


if (count($myList["Present"]) > 0):


?>

	<div class="box-bg-board center">

<!-- 		<div class="box-board">
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

$sheetBody = null;

echo "<div id=\"myTable\" class=\"box-table ". (isset($get["filter"]) || isset($get["exb_all"]) ? 'exbAll' : null) ."\">";
echo "<p class=\"fleft d-center-items sticky\">";
echo "<span class=\"fleft\"><strong>Tabela: Presentes</strong></span>";
echo "<span class=\"fright head_table\">";

if (!isset($get["filter"])):
	echo "<a href=\"".$url."report.php?aba=gift&filter".(isset($get["exb_all"]) ? "&exb_all" : null)."\" class=\"btn btn_link btn_manage\" title=\"Visualização de filtro\" id=\"power_filter\"><i class=\"fas fa-filter\"></i></a>";
else:
	echo "<a href=\"".$url."report.php?aba=gift".(isset($get["exb_all"]) ? "&exb_all" : null)."\" class=\"btn btn_link btn_manage btn_active\" title=\"Desativar modo: Visualização de filtro\"><i class=\"fas fa-filter\"></i></a>";
endif;

// echo "<a href=\"".$url."index.php\" class=\"btn btn_link btn_manage btn_expand\" title=\"Expandir tabela\"><i class=\"fas fa-expand\"></i></a>";

echo "<table id=\"table-filter\" data-exb=\"".((!isset($get["exb_all"])) ? "default" : "exb_all")."\">";

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
<select name="change_relatorio" data-href="<?php echo "report.php?aba=gift&rel="?>">
	<option value="semanal">Semanal</option>
	<?php foreach ($days as $abrDay => $day): ?>
		<option value="<?php echo encode_to_url($day)?>" <?php if (isset($get["rel"]) && $get["rel"] == encode_to_url($day)) echo "selected" ?> ><?php echo $day?> (<?php echo convertDayName($day, true)?>)</option>
	<?php endforeach; ?>
</select>

<script>
	$("select[name=change_relatorio]").on("change", function(){
		location.replace($(this).attr("data-href") + $(this).val() + "#myTable"); 
	});
</script>

	<tr class="bar-table">
		<!-- <td>#</td> -->
		<?php if(isset($get["filter"])): ?>
			<td class="td-button">
				<div><span data-filter="nome" class="btn btn_order fright"><i class="fas fa-sort-down"></i><div class="sub-dropdown drop-render"></div></span></div>
				<div>
					<span>Nome</span>
				</div>
			</td>
		<?php else: ?>
			<td>Nome</td>
		<?php endif; ?>
		<?php if(isset($get["filter"])): ?>
			<td class="td-button">
				<div><span data-filter="organizacao_militar" class="btn btn_order fright"><i class="fas fa-sort-down"></i><div class="sub-dropdown drop-render"></div></span></div>
				<div>
					<span>Organização Militar</span>
				</div>
			</td>
			<td class="td-button">
				<div><span data-filter="posto_graduacao" class="btn btn_order fright"><i class="fas fa-sort-down"></i><div class="sub-dropdown drop-render"></div></span></div>
				<div>
					<span>Posto/ Graduação</span>
				</div>
			</td>
		<?php else: ?>
			<td>Organização Militar</td>
			<td>Posto/ Graduação</td>
		<?php endif; ?>
		<td><?php if (isset($get["rel"]) && $get["rel"] !== "semanal") echo decode_to_url($get["rel"]) . "<br>" ?>Refeição (qnt. Realizado)</td>
	</tr>

<?php 


sortByColumns($Resp);
$countItem=0;
$id = 0;

foreach ($Resp as $key => $valueResp) {

	if ($valueResp["datasheet"] == $_SESSION['objfile']['name']):

		$id++;

		$contagemRefc = array();
		$contagemRefcDays = array();

		foreach ($myConf as $keyRefc => $valueRefc):

			if ($valueRefc["hash"] == $valueResp["hash"]):

				foreach ($valueRefc[0] as $refckey => $refcvalue) {

					// var_dump($refcvalue);
					$foreach = $refcvalue;
					

					if (is_array($foreach) || is_object($foreach))
					{
						foreach ($foreach as $refcValkey => $refcVal) {

							$newValue = explode(";", $refcVal);

							foreach ($newValue as $keynewValue => $valor) {
								if (!$keynewValue % 2) {
									array_push($contagemRefc, trim($valor));
								} else {
									array_push($contagemRefcDays, trim($valor));
								}
							}
						}
					}

				}

			endif;

		endforeach;

		$ObjDecoded = array(
			"nome" => ucfirst(strtolower(utf8_decode(trim($valueResp["nome"])))),
			"posto_graduacao" => utf8_decode($valueResp["posto_graduacao"]),
			"organizacao_militar" => utf8_decode($valueResp["organizacao_militar"])
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

		$arrayNumStatus["posto_graduacao"][encodeRegexPg($valueResp["posto_graduacao"])][0] += 1;
		$arrayNumStatus["organizacao_militar"][encodeRegexPg($valueResp["organizacao_militar"])][0] += 1;

		if (isset($get["rel"]) && $get["rel"] !== "semanal"):

			if ($str["Café"] == 0 && $str["Almoço"] == 0 && $str["Jantar"] == 0):
				// nothing
			else:

				$countItem++;

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
			
			if (!empty($contagemRefc)):

				$countItem++;

				$sheetBody .= "<tr data-hash=\"".$valueResp["hash"]."\">";

				// $sheetBody .= "<td>" . ++$key . "</td>";
				$sheetBody .= "<td data-content=\"nome\">" . $ObjDecoded["nome"] . "</td>";
				$sheetBody .= "<td data-content=\"organizacao_militar\">" . $ObjDecoded["organizacao_militar"] . "</td>";
				$sheetBody .= "<td data-content=\"posto_graduacao\">" . $ObjDecoded["posto_graduacao"] . "</td>";

				$sheetBody .= "<td>";
				if ($myInfo["Café"] > 0)
					$sheetBody .= "Café (" . $myInfo["Café"] . ")" . ( ($myInfo["Almoço"] > 0) ? ", " : null );

				if ($myInfo["Almoço"] > 0)

					$sheetBody .= "Almoço (" . $myInfo["Almoço"] . ")" . ( ($myInfo["Jantar"] > 0) ? ", " : null );

				
				if ($myInfo["Jantar"] > 0) 

					$sheetBody .= " Jantar (" . $myInfo["Jantar"] . ")";
				
				$sheetBody .= "</td>";
			endif;


		endif;
		
		$sheetBody .= "</tr>";

	endif;

	// break;

}


if ($countItem > 0):

	echo $sheetBody;

else:

	echo "<td colspan=\"4\" class=\"txt-center\">Não existem dados a serem exibidos no dia selecionado.</td>";

endif;

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
	                text: 'Posto/ Graduação'
	            }
	        }
	    },
	    {
	        plugins: {
	            title: {
	                display: true,
	                text: 'Organização Militar'
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
		
	});

		$(document).ready(function(){
			var ctx = document.getElementById('chart_faltantes_by_refc');

				labels_refc = [
					'Café',
					'Almoço',
					'Jantar',
					// 'Restando Todos'
				];

			const options = 
		    {
		        plugins: {
		            title: {
		                display: true,
		                text: '<?php echo (isset($get["cassino"]) ? ucfirst(str_replace("_", "/ ", $get["cassino"])) : "Presentes" )?>: Por refeição'
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
		    

		    const data = 
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
			}


			new Chart(ctx, {
			    type: 'pie',
			    data: data,
			    options: options
			});

		});

</script>

<?php 

// var_dump($contagemRefc);
// var_dump($myInfo);

else:

	$Alert->setConfig("warning", "<strong>Aviso</strong>: O sistema não registrou militares presentes ainda. Tente registrar uma presença primeiro.");
	echo $Alert->displayPrint();

endif; 

?>