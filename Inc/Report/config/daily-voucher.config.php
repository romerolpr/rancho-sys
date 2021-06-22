<?php 

$arrayNumVoucher = array(

	"posto_graduacao" =>
		array(
			"of_capten" => array(0, 0),
			"1_sgt" => array(0, 0),
			"2_sgt" => array(0, 0),
			"st" => array(0, 0),
			"of_sup" => array(0, 0),
			"3_sgt" => array(0, 0),
			"civil" => array(0, 0),
			"cb_sd" => array(0, 0),
			"total" => 0
		),

	"valor_final" => array(
		"oficial" => array(0, 0, array(
			array("of_capten", "of_sup"),
			0, 0, 0
		)),
		"st_sgt" => array(0, 0, array(
			array("1_sgt", "2_sgt", "3_sgt", "st"),
			0, 0, 0
		)),
		"cb_sd" => array(0, 0, array(
			array("cb_sd"),
			0, 0, 0
		)),
		"total" => array(
			0,
			"refc" => array(
				0, 0, 0, 
				"total" => 0
			)
		)
	),
);

// Calc by pt
foreach ($Resp as $key => $value):
	if ($value["datasheet"] == $_SESSION["objfile"]["name"]):
		$pgreal = strtolower(preg_replace(array("/(º|\/)/"), explode(" ",""), str_replace(" ", "_", utf8_decode(trim($value["posto_graduacao"])))));
		$arrayNumVoucher["posto_graduacao"][$pgreal][0] += 1;
		$arrayNumVoucher["posto_graduacao"]["total"] += 1;

		$arrayNumVoucher["posto_graduacao"][$pgreal][1] = round(($arrayNumVoucher["posto_graduacao"][$pgreal][0] * 100) / $arrayNumVoucher["posto_graduacao"]["total"]);
	endif;	
endforeach;

// Calc total values by pt
foreach ($arrayNumVoucher["posto_graduacao"] as $key => $value) $arrayNumVoucher["valor_final"]["total"][0] += $value[0];

// Oficial
$arrayNumVoucher["valor_final"]["oficial"][0] = ($arrayNumVoucher["posto_graduacao"]["of_capten"][0] + $arrayNumVoucher["posto_graduacao"]["of_sup"][0]);

$arrayNumVoucher["valor_final"]["oficial"][1] = round(($arrayNumVoucher["valor_final"]["oficial"][0] * 100) / $arrayNumVoucher["valor_final"]["total"][0]);

// St Sgt
$arrayNumVoucher["valor_final"]["st_sgt"][0] = ($arrayNumVoucher["posto_graduacao"]["1_sgt"][0] + $arrayNumVoucher["posto_graduacao"]["2_sgt"][0] + $arrayNumVoucher["posto_graduacao"]["3_sgt"][0]) + ($arrayNumVoucher["posto_graduacao"]["st"][0]);
$arrayNumVoucher["valor_final"]["st_sgt"][1] = round(($arrayNumVoucher["valor_final"]["st_sgt"][0] * 100) / $arrayNumVoucher["valor_final"]["total"][0]);

// Cb/ Sd
$arrayNumVoucher["valor_final"]["cb_sd"][0] = $arrayNumVoucher["posto_graduacao"]["cb_sd"][0];
$arrayNumVoucher["valor_final"]["cb_sd"][1] = round(($arrayNumVoucher["valor_final"]["cb_sd"][0] * 100) / $arrayNumVoucher["valor_final"]["total"][0]);

// $test = getRefc($arrayNumVoucher["valor_final"]["oficial"][2][0], "Café");

$arrayNumVoucher["valor_final"]["oficial"][2][1] = getRefc($arrayNumVoucher["valor_final"]["oficial"][2][0], "Café da manhã");
$arrayNumVoucher["valor_final"]["oficial"][2][2] = getRefc($arrayNumVoucher["valor_final"]["oficial"][2][0], "Almoço");
$arrayNumVoucher["valor_final"]["oficial"][2][3] = getRefc($arrayNumVoucher["valor_final"]["oficial"][2][0], "Jantar");

$arrayNumVoucher["valor_final"]["st_sgt"][2][1] = getRefc($arrayNumVoucher["valor_final"]["st_sgt"][2][0], "Café da manhã");
$arrayNumVoucher["valor_final"]["st_sgt"][2][2] = getRefc($arrayNumVoucher["valor_final"]["st_sgt"][2][0], "Almoço");
$arrayNumVoucher["valor_final"]["st_sgt"][2][3] = getRefc($arrayNumVoucher["valor_final"]["st_sgt"][2][0], "Jantar");

$arrayNumVoucher["valor_final"]["cb_sd"][2][1] = getRefc($arrayNumVoucher["valor_final"]["cb_sd"][2][0], "Café da manhã");
$arrayNumVoucher["valor_final"]["cb_sd"][2][2] = getRefc($arrayNumVoucher["valor_final"]["cb_sd"][2][0], "Almoço");
$arrayNumVoucher["valor_final"]["cb_sd"][2][3] = getRefc($arrayNumVoucher["valor_final"]["cb_sd"][2][0], "Jantar");


$arrayNumVoucher["valor_final"]["total"]["refc"][0] = ($arrayNumVoucher["valor_final"]["oficial"][2][1][0] + $arrayNumVoucher["valor_final"]["st_sgt"][2][1][0] + $arrayNumVoucher["valor_final"]["cb_sd"][2][1][0]);
$arrayNumVoucher["valor_final"]["total"]["refc"][1] = ($arrayNumVoucher["valor_final"]["oficial"][2][2][0] + $arrayNumVoucher["valor_final"]["st_sgt"][2][2][0] + $arrayNumVoucher["valor_final"]["cb_sd"][2][2][0]);
$arrayNumVoucher["valor_final"]["total"]["refc"][2] = ($arrayNumVoucher["valor_final"]["oficial"][2][3][0] + $arrayNumVoucher["valor_final"]["st_sgt"][2][3][0] + $arrayNumVoucher["valor_final"]["cb_sd"][2][3][0]);

$arrayNumVoucher["valor_final"]["total"]["refc"]["total"] = $arrayNumVoucher["valor_final"]["total"]["refc"][0] + $arrayNumVoucher["valor_final"]["total"]["refc"][1] + $arrayNumVoucher["valor_final"]["total"]["refc"][2];

?>