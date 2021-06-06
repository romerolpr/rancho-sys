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
</head>
<body>

	<section class="content">

		<div class="container">

			<div class="main">

	<h1>Relatório de arranchamento</h1>
	<br>

	<div class="box-table">

		<h2>Informações gerais</h2>
		<ul class="list">
			<li>Pendente: <?php echo (count($listPendent)) ?>, Realizado: <?php echo (count($listComplete)) ?></li>
			<li>Total: <?php echo (count($listPendent) + count($listComplete)) ?></li>
		</ul>

		<p class="fleft d-center-items">
			<span class="fleft"><strong><?php echo $_SESSION["objfile"]["name"] ?></strong></span>

			<span class="tab-pagination">
			    
			    <span>Ordernar por: </span>
			    <select name="order_by">
			    	<option value="<?php echo urlencode("Nenhum") ?>" <?php if(isset($get["order_by"]) && $get["order_by"] == "Nenhum") echo "selected" ?>>Nenhum</option>
>
			    	<option value="<?php echo urlencode("B ADM AP IBIRAPUERA")?>" <?php if(isset($get["order_by"]) && $get["order_by"] == "B ADM AP IBIRAPUERA") echo "selected" ?>>B ADM AP IBIRAPUERA</option>
			    	<option value="<?php echo urlencode("8º BPE")?>" <?php if(isset($get["order_by"]) && $get["order_by"] == "8º BPE") echo "selected" ?>>8º BPE</option>
			    	<option value="<?php echo urlencode("AGSP") ?>" <?php if(isset($get["order_by"]) && $get["order_by"] == "AGSP") echo "selected" ?>>AGSP</option>
			    </select>

			    <script>
			    	$(function(){

			    		$("select[name=order_by]").on("change", function(){

			    			var name = $(this).val();

			    			url = "report.php?order_by=" + name + "<?php if(isset($get["h"])) echo "&h=".$get["h"]?>";
			    			document.location = url;

			    		});

			    	});
			    </script>

			</span>

			<span class="fright head_table">
				<a href="<?php echo $url?>report.php?datasheetFile=<?php echo $_SESSION["objfile"]["name"]?>&h=1" class="btn btn_link btn_manage <?php echo (isset($get["h"]) && $get["h"] == 1 ? "btn_active" : null) ?>" title="Pendentes">Pendentes</a>
				<a href="<?php echo $url?>report.php?datasheetFile=<?php echo $_SESSION["objfile"]["name"]?>&h=2" class="btn btn_link btn_manage <?php echo (isset($get["h"]) && $get["h"] == 2 ? "btn_active" : null) ?>" title="Realizados">Realizados</a>
			</span>
		</p>

		<table>
			
			<tr class="bar-table">
				<td <?php if(!isset($get["hash"])) echo "class='d-none'"; ?>>hash</td>
				<td>Organização Militar</td>
				<td>Posto/ Graduação</td>
				<td>Nome</td>
				<td>Arranchamento</td>

			</tr>

			<?php 

			$trmike = "";

			if (isset($get["h"]) && $get["h"] == 2):
					
				foreach ($listComplete as $key => $value):

					foreach ($Resp as $i => $mike):
						if ($mike["hash"] == $value[0]):
							$nome = utf8_decode(trim($mike["nome"]));
							$posto_graduacao = utf8_decode($mike["posto_graduacao"]);
							$organizacao_militar = utf8_decode($mike["organizacao_militar"]);
						endif;
					endforeach;

					$trmike .= "<td " . (!isset($get["hash"]) ? "class=\"d-none\"" : null ) .">$value[0]</td>";
					if (!isset($get["order_by"]) or $get["order_by"] == "Nenhum" or $organizacao_militar == $get["order_by"]):
						$trmike .= "<tr>";
						$trmike .= "<td " . (!isset($get["hash"]) ? "class=\"d-none\"" : null ) ." >$value[0]</td>";
						$trmike .= "<td>$organizacao_militar</td>";
						$trmike .= "<td>$posto_graduacao</td>";
						$trmike .= "<td>$nome</td>";
						$trmike .= "<td>Realizado <i class='btn_link fa fa-check'></i></td>";

					endif;

				endforeach;

			else:

				foreach ($listPendent as $key => $value):

					foreach ($Resp as $i => $mike):
						if ($mike["hash"] == $value[0]):
							$nome = utf8_decode(trim($mike["nome"]));
							$posto_graduacao = utf8_decode($mike["posto_graduacao"]);
							$organizacao_militar = utf8_decode($mike["organizacao_militar"]);
						endif;
					endforeach;

					if (!isset($get["order_by"]) or $get["order_by"] == "Nenhum" or $organizacao_militar == $get["order_by"]):
						$trmike .= "<tr>";
						$trmike .= "<td " . (!isset($get["hash"]) ? "class=\"d-none\"" : null ) ." >$value[0]</td>";
						$trmike .= "<td>$organizacao_militar</td>";
						$trmike .= "<td>$posto_graduacao</td>";
						$trmike .= "<td>$nome</td>";
						$trmike .= "<td>";

						foreach ($value[1] as $arrkey => $arr):
							$Item = explode(";", $arr); 

							$trmike .= $Item[1] . " [$Item[0]]<br>";

						endforeach;

					endif;

				endforeach;

			endif;

			if (!isset($get["order_by"])):
				$trmike .= "</td>";

			endif;

			$trmike .= "</tr>";


			echo $trmike;

			?>

		</table>

	</div>

</div>

</div>

	</section>

</body>
</html>