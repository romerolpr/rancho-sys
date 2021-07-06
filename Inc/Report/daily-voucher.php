<?php 

include_once 'config/daily-voucher.config.php'; 

$maxMissingByRefc = array(
	"Café" => 0,
	"Almoço" => 0,
	"Jantar" => 0,
	"Todos" => 0
);


?>
<!-- 
<div class="box-bg-board center">

	<div class="box-board">
		<div class="bg-shadow">
			<canvas id="chart_faltantes_by_refc" width="400" height="400"></canvas>
		</div>
	</div>

</div> -->


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

<div class="box-table <?php echo (isset($get["filter"]) || isset($get["exb_all"]) ? 'exbAll' : null) ?>">
<p class="fleft d-center-items sticky">
<span class="fleft"><strong>Tabela: Vale diário</strong></span>
<span class="fright head_table">

<?php if (!isset($get["filter"])): ?>
	<a href="<?php echo $url?>report.php?aba=daily-voucher&filter<?php echo (isset($get["exb_all"]) ? "&exb_all" : null)?>#table-filter" class="btn btn_link btn_manage" title="Visualização de filtro" id="power_filter"><i class="fas fa-filter"></i></a>
<?php else: ?>
	<a href="<?php echo $url?>report.php?aba=daily-voucher<?php echo (isset($get["exb_all"]) ? "&exb_all" : null)?>#table-filter" class="btn btn_link btn_manage btn_active" title="Desativar modo: Visualização de filtro"><i class="fas fa-filter"></i></a>
<?php endif; ?>

<!-- <a href="<?php echo $url?>report.php?aba=daily-voucher&gpdf-view=1&doc=presentes" class="btn btn_link btn_manage" title="Exportar tabela" target="_blank"><i class="fas fa-file-download"></i></a> -->

<!-- <a href="<?php echo $url?>report.php?aba=daily-voucher" class="btn btn_link btn_manage btn_expand" title="Expandir tabela"><i class="fas fa-expand"></i></a> -->

</span>
</p>


<table id="table-filter" data-exb="<?php ((!isset($get["exb_all"])) ? "default" : "exb_all")?>">
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
		<td>Refeição (qnt. Arranchado)</td>
		<td>Dia arranchado</td>
	</tr>

		<?php

		$sheetBody = null;

		// if (isset($get["sort"])):
		// 	$getSort = explode(":", $get["sort"]);
		// 	aasort($arrayNumVoucher["valor_final"][$param][2][1][1], $getSort[0], $getSort[1]);
		// endif;

		$param = (isset($get["cassino"]) && !empty($get["cassino"]) ? $get["cassino"] : "oficial");
		$loopArray = $arrayNumVoucher["valor_final"][$param][2][1][1];
		
		sortByColumns($loopArray);

		foreach ($loopArray as $key => $value):
			// var_dump($myConf[3]);
			
			$contagemRefc = array();

			foreach ($myResp as $keyRefc => $valueRefc):

				if ($valueRefc["hash_id"] == $value["hash"]):

					foreach ($valueRefc["data_json"] as $refckey => $refcvalue) {

						// var_dump($refcvalue);

						if (is_array($refcvalue) || is_object($refcvalue))
						{
							foreach ($refcvalue as $keyrefcvalue => $valuerefcVal) {

								$newValue = explode(";", $valuerefcVal);

								foreach ($newValue as $keynewValue => $valor) {
									if (!$keynewValue % 2)
										array_push($contagemRefc, trim($valor));
								}

							}

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

			// var_dump($myInfo);

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

			$diasArray = array();
			foreach ($ObjDecoded["refc"] as $keyrefckey => $valuerefc) {
				
				if (!empty($valuerefc[0]) || $valuerefc[0] != '')
					array_push($diasArray, $valuerefc[1]);

			}

			// var_dump($diasArray);


				$sheetBody .= "<tr data-hash=\"".$value["hash"]."\">";

				// $sheetBody .= "<td>" . ++$key . "</td>";
				$sheetBody .= "<td data-content=\"nome\">" . $ObjDecoded["nome"] . "</td>";
				$sheetBody .= "<td data-content=\"organizacao_militar\">" . $ObjDecoded["organizacao_militar"] . "</td>";
				$sheetBody .= "<td data-content=\"posto_graduacao\">" . $ObjDecoded["posto_graduacao"] . "</td>";

				// var_dump($listPresent);


			if (!empty($contagemRefc))
			{

				$sheetBody .= "<td>";
				if ($myInfo["Café"] > 0)
					$sheetBody .= "Café (" . $myInfo["Café"] . ")" . ( ($myInfo["Almoço"] > 0) ? ", " : null );

				if ($myInfo["Almoço"] > 0)

					$sheetBody .= "Almoço (" . $myInfo["Almoço"] . ")" . ( ($myInfo["Jantar"] > 0) ? ", " : null );

				
				if ($myInfo["Jantar"] > 0) 

					$sheetBody .= " Jantar (" . $myInfo["Jantar"] . ")";

				$sheetBody .= "</td>";

				$sheetBody .= "<td>";

				foreach ($diasArray as $keydiasArray => $valuediasArray) {
					$diasemana_numero = date('w', strtotime(str_replace("/", "-", $valuediasArray)));

					$sheetBody .= "<span title=\"".$valuediasArray."\">" . convertDayName($diasemana_numero) . "</span>" . ($keydiasArray == count($diasArray) - 1 ? null : ", ");
				}

				// $sheetBody .= str_replace("/2021", "", implode(", ", $diasArray));
				
				$sheetBody .= "</td>";


			} else {

				$sheetBody .= "<td>";
				$sheetBody .= "Restando todos";
				$sheetBody .= "</td>";

			}


			$sheetBody .= "</tr>";

			// break;

		endforeach;


		echo $sheetBody;


		

		?>

</table>

</div>

<script>
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
	        tooltips: {
	                enabled: false
	            },
            plugins: {
            	title: {
            	    display: true,
            	    text: '<?php echo (isset($get["cassino"]) ? ucfirst(str_replace("_", "/ ", $get["cassino"])) : "Presentes" )?>: Por refeição'
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


		// new Chart(ctx, {
		//     type: 'pie',
		//     data: data,
		//     options: options
		// });

	});
			

	$("select[name=change_cassino]").on("change", function(){
		location.replace($(this).attr("data-href") + $(this).val()); 
	});
</script>

<?php 

// var_dump($maxMissingByRefc);


?>