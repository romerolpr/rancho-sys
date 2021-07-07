<?php

session_start();

// var_dump($_SESSION['objfile']);

if (!isset($_SESSION["objfile"]) || !isset($_SESSION["user_login"]) || $_SESSION["user_login"]['nvl_access'] != 1):
	header("location: index.php");
endif;

include_once 'Inc/geral.inc.php';

$Render = new Render();
$Alert = new DisplayAlert();
$Db = new ObjectDB();
$Db->setter(HOST, USER, PASS, DBNAME);

$Resp = $Db->return_query($Db->connect_db(), TB_RESP, null, false, null);
$Conf = $Db->return_query($Db->connect_db(), TB_CONF, null, false, null);
$fromDb = $Db->return_query($Db->connect_db(), TB_RESP, null, false, null);

$myResp = array();
$myConf = array();

$varPush = "";

// Construct and organize data
foreach ($Resp as $key => $value):

	if (isset($_SESSION["objfile"]["name"]) && $value["datasheet"] == $_SESSION["objfile"]["name"]):

		$myRespValues = array(

			explode("&&", utf8_decode($value["segunda_feira"])),
			explode("&&", utf8_decode($value["terca_feira"])),
			explode("&&", utf8_decode($value["quarta_feira"])),
			explode("&&", utf8_decode($value["quinta_feira"])),
			explode("&&", utf8_decode($value["sexta_feira"])),
			explode("&&", utf8_decode($value["sabado"])),
			explode("&&", utf8_decode($value["domingo"])),
		);

		$build = array(
			'hash_id' => $value["hash"],
			'data_json' => 
			array(
				'hash' => $value["hash"],
				'values' => array()
			)
		);

		
		for ($i=0; $i <= 6 ; $i++):

			$arranchamento = explode(",", $myRespValues[$i][0]);

			if (isset($arranchamento[0]) && !empty($arranchamento[0]))
				array_push($build["data_json"]["values"], resizeString($arranchamento[0], 10) . ";" . $myRespValues[$i][1]);

			if (isset($arranchamento[1]) && !empty($arranchamento[1]))
				array_push($build["data_json"]["values"], resizeString($arranchamento[1], 10) . ";" . $myRespValues[$i][1]);


			if (isset($arranchamento[2]) && !empty($arranchamento[2]))
				array_push($build["data_json"]["values"], resizeString($arranchamento[2], 10) . ";" . $myRespValues[$i][1]);

		endfor;

		// $build["data_json"] = json_encode($build["data_json"], true);

		array_push($myResp, $build);

	endif;

endforeach;

foreach ($Conf as $key => $value):
	if (isset($_SESSION["objfile"]["name"]) && $value["datasheet"] == $_SESSION["objfile"]["name"])
		array_push($myConf, json_decode($value["data_json"], true));
endforeach;

// var_dump($myResp);
// var_dump($myConf[1]);

$listPendent = array();
$listComplete = array();
$listPresent = array();
$listMissing = array();

/**
 Starting comparation beetwen tables
**/

foreach ($myResp as $keyresp => $resp):

	foreach ($myConf as $keyconf => $conf):
		
		if ($conf["hash"] == $resp["hash_id"]):

			// var_dump($conf["hash"]);
			// var_dump(testListValues($resp["data_json"]["values"], $conf[0]["values"]));

			$testArrays = testListValues($resp["data_json"]["values"], $conf[0]["values"]);

			if (!empty($conf[0]["values"]) ):
				array_push($listPresent, array($conf["hash"], $testArrays));

			elseif (!empty($testArrays)):
				array_push($listPendent, array($conf["hash"], $testArrays));

			endif;

			if (empty($testArrays))
				array_push($listComplete, array($conf["hash"], $resp));

			// var_dump($listComplete);

		endif;

	endforeach;


endforeach;

$bodyTable = "<table></table>";


/*

{"hash":"2dd2e543b120292a1cc6cff79d97ec49","0":{"timestamp":1622393871740,"values":["Almo\u00e7o;24\/05\/2021"]}}

*/

// array_unique($listPendent);

$myList = array(
	"Complete" => array(),
	"Missing" => array(),
	"Present" => array(),
	"Pendent" => array()
);

# Pushing data

foreach ($listComplete as $key => $value)
	if (!in_array($value[0], $myList["Complete"]))
		array_push($myList["Complete"], $value[0]);

foreach ($listPendent as $key => $value)
	if (!in_array($value[0], $myList["Pendent"]))
		array_push($myList["Pendent"], $value[0]);

foreach ($listPresent as $key => $value)
	if (!in_array($value[0], $myList["Present"]))
		array_push($myList["Present"], $value[0]);

foreach ($listPendent as $key => $value)
	if (!in_array($value[0], $myList["Missing"]))
		array_push($myList["Missing"], $value[0]);

// var_dump($myList);

$arrayNumStatus = array(

	"posto_graduacao" =>
		array(
			"civil" => array(0, 0),
			"cb_sd" => array(0, 0),
			"3_sgt" => array(0, 0),
			"2_sgt" => array(0, 0),
			"1_sgt" => array(0, 0),
			"st" => array(0, 0),
			"sub_ten" => array(0, 0),
			"asp_of" => array(0, 0),
			"al" => array(0, 0),
			"al_cfc" => array(0, 0),
			"al_cfst" => array(0, 0),
			"1_ten" => array(0, 0),
			"2_ten" => array(0, 0),
			"cap" => array(0, 0),
			"maj" => array(0, 0),
			"ten_cel" => array(0, 0),
			"cel" => array(0, 0),
			"of_capten" => array(0, 0),
			"of_sup" => array(0, 0),
		),

	"organizacao_militar" =>
		array(
			"b_adm_ap_ibirapuera" => array(0, 0),
			"8_bpe" => array(0, 0),
			"apoio_direto" => array(0, 0),
			"agsp" => array(0, 0),
			"base" => array(0, 0),
			"cmdo_2_rm" => array(0, 0),
			"2rm" => array(0, 0),
		),

	"total" => 0
);

$backgroundColors = array(
	'rgb(140, 212, 148)',
	'rgb(140, 169, 212)',
	'rgb(220, 151, 102)',
	'rgb(186, 102, 220)',
	'rgb(220, 178, 102)',
	'rgb(220, 102, 102)',
	'rgb(98, 193, 74)',
	'rgb(186, 193, 74)',
	'rgb(193, 74, 74)',
);

if(isset($get["aba"])):
	$_SESSION['objfile']['aba'] = $get["aba"];
else:
	unset($_SESSION['objfile']['aba']);
endif;

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php 

	$nameDoc = (isset($get["doc"]) ? $get["doc"] : "doc") . "_";
	$title = ( isset($get["gpdf-view"]) ? strtoupper( $nameDoc . $date->format('d_M_Y_h_i_s') ) : "Relatório de arranchamento" );

	?>

	<title><?php echo $title?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo $url?>Dist/css/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $url?>Dist/css/fontawesome.css">
	<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
	<script><?php include "Dist/js/jquery.js"; ?></script>
	<style>
		.box-table { max-height: 100%; }
	</style>

	<link rel=icon type=image/png href="Dist/image/brasao-exercito.png">

	<script src="Dist/js/chart.js"></script>

	<style>
		.main {
			position: relative;
			box-sizing: border-box;
			padding: 1em 0;
		}
	</style>
</head>
<body>

	<div class="modal"></div>

	<section class="content">

		<div class="container">

			<div class="main">

	<h1>Relatório de arranchamento</h1>
	<br>

	<div class="box-table">

<!-- 		<h2>Informações gerais</h2>
		<ul class="list">
			<li>Pendente: <?php echo (count($listPendent)) ?>, Realizado: <?php echo (count($listComplete)) ?></li>
			<li>Arranchamentos totais: <?php echo (count($listPendent) + count($listComplete)) ?></li>
		</ul> -->

		<p class="fleft d-center-items">
			<span class="fleft"><strong><?php echo $_SESSION["objfile"]["name"] ?></strong></span>
		</p>

		<aside>
		    <nav>
		        <ul>
		            <li><a href="<?php echo $url?>report.php?aba=daily-voucher" title="Vale diário">Vale diário</a></li>
		            <li><a href="<?php echo $url?>report.php?aba=missing" title="Relatório: Faltantes">Relatório: Faltantes</a></li>
		            <li><a href="<?php echo $url?>report.php?aba=gift" title="Relatório: Presentes">Relatório: Presentes</a></li>
		            <!-- <li><a href="<?php echo $url?>report.php" title="Planilha de relatório individual">Planilha de relatório individual</a></li> -->
		            <li><a href="<?php echo $url?>report.php?aba=dashboard" title="Relatórios gerais: Dashboard">Relatórios gerais: Dashboard</a></li>
		            <br>
		            <li><a href="<?php echo $url?>index.php" title="Voltar">Voltar</a></li>
		        </ul>
		    </nav>
		</aside>

		<article>

			<div class="box-table">

				<p class="fleft d-center-items sticky">
					<?php if (!isset($get["aba"])): ?>
						<span class="fleft"><strong>Índices gerais: Dashboard</strong></span>
						<!-- <span class="fright head_table">
							<?php if(isset($get["filter"])): ?>
								<a href="report.php" class="btn btn_link btn_manage btn_active" title="Desativar modo: Visualização de filtro"><i class="fas fa-filter"></i></a>
							<?php else: ?>
								<a href="report.php?filter" class="btn btn_link btn_manage" title="Visualização de filtro" id="power_filter"><i class="fas fa-filter"></i></a>
							<?php endif; ?>
							<a href="report.php" class="btn btn_link btn_manage btn_expand" title="Expandir tabela"><i class="fas fa-expand"></i></a>
							<input type="search" name="searchByName" class="search"> -->
					<?php elseif ($get["aba"] == "dashboard"): ?>
						<span class="fleft"><strong>Índices gerais: Dashboard</strong></span>
					<?php elseif ($get["aba"] == "daily-voucher"): ?>
						<span class="fleft"><strong>Vale diário</strong></span>
					<?php elseif ($get["aba"] == "missing"): ?>
						<span class="fleft"><strong>Relatório: Faltantes</strong></span>
					<?php elseif ($get["aba"] == "gift"): ?>
						<span class="fleft"><strong>Relatório: Presentes</strong></span>
					<?php else: ?>
						<span class="fleft"><strong>404: Não encontrado</strong></span>
					<?php endif; ?>
					<!-- </span> -->
				</p>

				<?php 

				if (!isset($get["aba"])):

					// include REPORT . 'painel.inc.php';
					include REPORT . 'dashboard.inc.php';

				else:

					if ($get["aba"] == "daily-voucher"):
						include REPORT . 'daily-voucher.php';

					elseif ($get["aba"] == "dashboard"):
						include REPORT . 'dashboard.inc.php';

					elseif ($get["aba"] == "missing"):
						include REPORT . 'faltantes.php';

					elseif ($get["aba"] == "gift"):
						include REPORT . 'presentes.php';

					// elseif (in_array($get["aba"], array('faltantes', 'presentes'))):
						// include REPORT . 'relatorios.inc.php';

					else:
						include REPORT . '404.php';

					endif;


				endif;

				?>

			</div>


		</article>

	</div>

</div>

</div>

	</section>

	<script>

		var exbAll = "<?php echo ( isset($get['exb_all']) ? true : false ) ?>";

		function searchOnTable(){
			var $rows = $('#table-filter tr:not(.bar-table)');
			$("#searchbar").on("keyup", function(){
				var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
				$rows.show().filter(function() {
			        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
			        return !~text.indexOf(val);
			    }).hide();
			});
		}

		var click_btn = false;
		console.log(click_btn);
		$(".btn_expand").click(function(e){ 
			e.preventDefault();
			if (click_btn === false){
				$(this).addClass("btn_active");
				$(".box-table").addClass("window_fixed");
					click_btn = true;
					localStorage.setItem('window', true);
			} else {
				$(".box-table").removeClass("window_fixed");
				$(this).removeClass("btn_active"); 
				localStorage.setItem('window', false);
				click_btn = false;
			}
		});

		document.addEventListener('keydown', function (event) {
		    if (event.keyCode == 27){
		     	$(".box-table").removeClass("window_fixed");
		   		$(".btn_expand").removeClass("btn_active"); 
		   		localStorage.setItem('window', false);
		    }
		});

		<?php include 'Dist/js/Filter.js'; ?>


		function mescleItems(){
			
			var mescleItemsVar = {
				checked	: [],
				empty 	: []
			};

			let elem  = $(".input_checked").parent().parent(),
					trdad   = elem.parent(),
					input = $("td").children("div").children("input");

			for (var i = input.length-1; i >= 0; i--) {
				if (input[i].checked !== false) {
					mescleItemsVar['checked'].push(input[i].id);
				} else {
					mescleItemsVar['empty'].push(input[i].id);
				}
			}

			return [mescleItemsVar, trdad];
		}

		InputChange(exbAll);

		$('#report').on("click", function(e){
			e.preventDefault();
			var url = $(this).attr("href");

			tryStatus();
			if (is_empty.length !== 0){
				if (confirm("Existem campos vazios. Deseja gerar o relatório mesmo assim?")){
					window.open(url, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=150,width=800,height=800")
				}
			} else {

				// document.location = url;
				window.open(url, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=150,width=800,height=800")
			}
		});

		function getMoreItems(){
			var divLoading = $(".bg-loading"),
				 	newLimit = divLoading.attr("data-limit"),
				 	maxElem = divLoading.attr("data-maxElem"),
					countElem = $("#table-filter tr:not(.bar-table)"),
					$body = $('body');
					request = $.ajax({
				    url: "Inc/get.inc.php",
				    type: "POST",
				    data: "newLimit=" + newLimit + "&countElem=" + countElem.length,
				    dataType: "html"
				});

			divLoading.hide();

			if (countElem.length >= maxElem){
				$("body").removeClass("loading");
			} else {
				$("body").addClass("loading");
			}

			// console.log(countElem.length);

			request.done(function(data){
				if (countElem.length < maxElem){

					$("body").removeClass("loading");

					$('#table-filter').append(data);

					InputChange(exbAll);
				}
			});
			request.fail(function(jqXHR, textStatus) {
			    console.log("Request failed: " + textStatus);
			    $(".modal").append("<span class=\"message\">Request failed: "+textStatus+"</span>");
			    setTimeout(function(){
			    	$body.removeClass("loading");
			    }, 2500);
			});

			searchOnTable();
		}

	</script>

	<script>
		$('body').addClass("loading");
		$(document).ready(function(){
			$('body').removeClass("loading");
		});

		// var windowFixed = localStorage.getItem("window");
		// if (windowFixed != "false"){
		// 	$(".box-table").addClass("window_fixed");
		// 	$(".btn_expand").addClass("btn_active");
		// 	click_btn = true;
		// } else {
		// 	$(".box-table").removeClass("window_fixed");
		// 	$(".btn_expand").removeClass("btn_active");
		// 	click_btn = false;
		// }

		var tableToExcel = (function() {
		  // var uri = 'data:application/vnd.ms-excel;base64,'
		  var uri = 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,'
		    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
		    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
		    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
		  return function(table, name) {
		    if (!table.nodeType) table = document.getElementById(table)
		    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
			var filename = name + ".xlsx";
		    document.getElementById("dlink").href = uri + base64(format(template, ctx))
            document.getElementById("dlink").download = filename;
            document.getElementById("dlink").click();
		  }
		})()

		function tableToPDF(table, name) {

	        var sTable = document.getElementById(table).outerHTML, 
	        	style = "<style>";

		        style += "table {width: 100%;font: 17px Calibri;}";
		        style += "table, th, td {border: solid 1px #DDD; border-collapse: collapse;";
		        style += "padding: 2px 3px;text-align: center;}";
		        style += "</style>";

	        // CREATE A WINDOW OBJECT.
	        var win = window.open('', '', 'height=700,width=700');

	        win.document.write('<html><head>');
	        win.document.write('<title>'+name+'</title>');   // <title> FOR PDF HEADER.
	        win.document.write(style);          // ADD STYLE INSIDE THE HEAD TAG.
	        win.document.write('</head>');
	        win.document.write('<body>');
	        win.document.write(sTable); // THE TABLE CONTENTS INSIDE THE BODY TAG.
	        win.document.write('</body></html>');

	        win.document.close(); 	// CLOSE THE CURRENT WINDOW.

	        win.print();    // PRINT THE CONTENTS.
	    }

	 </script>


</body>
</html>