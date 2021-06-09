<?php

session_start();

// var_dump($_SESSION['objfile']);

include_once 'Inc/geral.inc.php';

$Render = new Render();
$Alert = new DisplayAlert();
$Db = new ObjectDB();

$Db->setter(HOST, USER, PASS, DBNAME);

$Resp = $Db->return_query($Db->connect_db(), TB_RESP);
$Conf = $Db->return_query($Db->connect_db(), TB_CONF);
$fromDb = $Db->return_query($Db->connect_db(), TB_RESP);

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

function testListValues($lista1, $lista2){	
	$clearArray = array(
		0 => array(),
		1 => array()
	);
	foreach ($lista1 as $key => $value) 
		array_push($clearArray[0], trim($value));
	foreach ($lista2 as $key => $value) 
		array_push($clearArray[1], trim($value));
	return array_diff($clearArray[0], $clearArray[1]);
}

/**
 Starting comparation beetwen tables
**/
foreach ($myResp as $keyresp => $resp):
	
	foreach ($myConf as $keyconf => $conf):
		
		if ($conf["hash"] == $resp["hash_id"]):

			// echo $resp["hash_id"];
			// var_dump($resp["data_json"]["values"]);
			// var_dump($conf[0]["values"]);

			// echo "<br><br>";

			$testArrays = testListValues($resp["data_json"]["values"], $conf[0]["values"]);

			// if ($conf["hash"] == "2acdb3e034ef5540cbc448f6f9433590"):
			// 	echo $conf["hash"];
			// 	echo "<br>Resp:";
			// 	var_dump($resp["data_json"]["values"]);
			// 		echo "<br><br>";
			// 	echo "Conf:";
			// 	var_dump($conf[0]["values"]);
			// 		echo "<br><br>";
			// 		echo "Tester:";
			// 	var_dump($testArrays);
			// 		echo "<br><br>";
			// endif;

			if (!empty($testArrays)):
				array_push($listPendent, array($conf["hash"], $testArrays));
			else:
				array_push($listComplete, array($conf["hash"], $resp));
			endif;

		endif;

	endforeach;

endforeach;

$bodyTable = "<table></table>";

// var_dump($listPendent);

/*

{"hash":"2dd2e543b120292a1cc6cff79d97ec49","0":{"timestamp":1622393871740,"values":["Almo\u00e7o;24\/05\/2021"]}}

*/

// array_unique($listPendent);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Relatório de arranchamento</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $url?>Dist/css/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $url?>Dist/css/fontawesome.css">
	<script><?php include "Dist/js/jquery.js"; ?></script>
	<style>
		.box-table { max-height: 100%; }
	</style>

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

	<div class="modal"></div>

	<section class="content">

		<div class="container">

			<div class="main">

	<h1>Relatório de arranchamento</h1>
	<br>

	<div class="box-table">

		<h2>Informações gerais</h2>
		<ul class="list">
			<li>Pendente: <?php echo (count($listPendent)) ?>, Realizado: <?php echo (count($listComplete)) ?></li>
			<li>Arranchamentos totais: <?php echo (count($listPendent) + count($listComplete)) ?></li>
		</ul>

		<p class="fleft d-center-items">
			<span class="fleft"><strong><?php echo $_SESSION["objfile"]["name"] ?></strong></span>
		</p>

		<aside>
		    <nav>
		        <ul>
		            <li><a href="<?php echo $url?>report.php" title="Planilha de relatório individual">Planilha de relatório individual</a></li>
		            <li><a href="<?php echo $url?>report.php?aba=dashboard" title="Indices gerais: Dashboard">Indices gerais: Dashboard</a></li>
		            <br>
		            <li><a href="<?php echo $url?>index.php" title="Voltar">Voltar</a></li>
		        </ul>
		    </nav>
		</aside>

		<article>

			<div class="box-table">

				<p class="fleft d-center-items sticky">
					<?php if (!isset($get["aba"])): ?>
						<span class="fleft"><strong>Planilha de relatório individual</strong></span>
						<span class="fright head_table">
							<?php if(isset($get["filter"])): ?>
								<a href="report.php" class="btn btn_link btn_manage btn_active" title="Desativar modo: Visualização de filtro"><i class="fas fa-filter"></i></a>
							<?php else: ?>
								<a href="report.php?filter" class="btn btn_link btn_manage" title="Visualização de filtro" id="power_filter"><i class="fas fa-filter"></i></a>
							<?php endif; ?>
							<a href="report.php" class="btn btn_link btn_manage btn_expand" title="Expandir tabela"><i class="fas fa-expand"></i></a>
							<!-- <input type="search" name="searchByName" class="search"> -->
					<?php else: ?>
						<span class="fleft"><strong>Índices gerais: Dashboard</strong></span>
					<?php endif; ?>
					</span>
				</p>

				<?php 

				if (!isset($get["aba"])):

					include REPORT . 'painel.inc.php';

				else:


					include REPORT . 'dashboard.inc.php';


				endif;

				?>

			</div>


		</article>

	</div>

</div>

</div>

	</section>

	<script><?php include 'Dist/js/Filter.js'; ?></script>

	<script>

		$(".btn_expand").on("click", function(e){
			e.preventDefault();
			// if (!clicked){
			$(".box-table").addClass("window_fixed");
			// window.history.pushState({url: "" + $(this).attr('href') + ""}, $(this).attr('title') , $(this).attr('href'));
				// clicked = true;
			//}
		});

		document.addEventListener('keydown', function (event) {
		    if (event.keyCode == 27){
		     	$(".box-table").removeClass("window_fixed");
		   		$(".btn_expand").removeClass("btn_active"); 
		    }
		});
		 
		$(window).bind("popstate", function(e) {
		  $('.main').load(e.state.url);
		});

		var click_btn = false;
		$(".btn_expand").click(function(e){ 
			e.preventDefault();
			if (click_btn === false){
				$(this).addClass("btn_active");
					click_btn = true;
			} else {
				$(".box-table").removeClass("window_fixed");
				$(this).removeClass("btn_active"); 
				click_btn = false;
			}
		});

		$(".btn_open_window").on("click", function(e){
			e.preventDefault();
			var listByHash = '<?php echo json_encode($listByHash); ?>';
			var hash = $(this).attr("data-hash"),
				$body = $("body"),
				request = $.ajax({
				    url: "Inc/window.modal.php",
				    type: "POST",
				    data: "hash=" + hash + "&listByHash=" + listByHash,
				    dataType: "html"
				});
			// console.log(hash);
			$body.addClass("loading");
			request.done(function(data){
				if ($(".window-user").length == 0)
					$body.css({"overflow":"hidden"}).append(data);
				$body.removeClass("loading");
			});
			request.fail(function(jqXHR, textStatus) {
			    console.log("Request failed: " + textStatus);
			});

		});
		$(".td-button span[data-filter]").on("click", function(e){



			// Build dropdown
			var divdrop = $(this).children("div.sub-dropdown"),
				request = $.ajax({
				    url: "Inc/load.drop.php",
				    type: "POST",
				    data: "content=" + $(this)[0].dataset.filter,
				    dataType: "html"
				}),
				myselfFilter = {
					filter: $(this)[0].dataset.filter,
					extract: $(this)[0].dataset.filterExtract
				},
				inputDate = [];

			$("div.sub-dropdown").hide();
			$(".td-button span i").removeClass("rotate180deg");

			$(this).children("i").addClass("rotate180deg");
			divdrop.show();

			// divdrop.addClass("loading");
			if (myselfFilter.filterExtract !== undefined){
				// $(this)
			} else {
				request.done(function(data){
					if (divdrop.html().length <= 0){
						divdrop.append(data);
					}
					divdrop.css({"background":"#fff"});
					initializeFilter(myselfFilter.filter);
				});
				request.fail(function(jqXHR, textStatus) {
						divdrop.append("<p>Request failed: " + textStatus + "</p>");
				    console.log("Request failed: " + textStatus);
				});
			}

		});
	</script>

	<script>
		$('body').addClass("loading");
		$(document).ready(function(){
			$('body').removeClass("loading");
		});
	</script>


</body>
</html>