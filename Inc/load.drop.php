<?php

include 'Classes/DisplayAlert.class.php';
include 'Classes/ObjectDB.class.php';
include 'Classes/Reader.class.php';
include 'define.inc.php';

$Alert = new DisplayAlert();
$Db = new ObjectDB();
$Render = new Render();
$Db->setter(HOST, USER, PASS, DBNAME);

$content = $_POST["content"];

foreach ($Filter["buttons"][$content] as $keybuttons => $button) {

	$arrayDrop = array();

	$db = $Db->connect_db();
	$query = $db->prepare("SELECT $content FROM `tb_resp`");
	$query->execute();

	foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $keyreturn => $returnValue) {
		// array_unique($arrayDrop, SORT_REGULAR);
		if (!in_array(trim(utf8_decode($returnValue[$content])), $arrayDrop))
			array_push($arrayDrop, trim(utf8_decode($returnValue[$content])));
	}

	$dropRender = null;

	if ($button["drop"]):

		if ($Filter["drop"][$content]["hide"]):
			$dropRender .= "<p class=\"txt-left\"><a class=\"btn txt-left\" href=\"index.php?hide=".urlencode($content)."\" title=\"Ocultar\">Ocultar</a></p>";
		endif;

		foreach ($Filter["drop"][$content]["order"] as $keyorder => $order) {
			$dropRender .= "<p class=\"txt-left\"><a class=\"btn txt-left\" href=\"index.php?sort=".$order.":".urlencode($content)."\" title=\"Classificar por ".$order."\">Classificar por ".$order."</a></p>";
		}

		$dropRender .= "<div class=\"navdrop\">";

		foreach ($Filter["drop"][$content] as $keydrop => $drop) {

			$dropRender .= "<li><div class=\"d-width\"><input type=\"checkbox\" name=\"filter\" value=\"(Selecionar tudo)\" id=\"".md5($content . "_" . $keybuttons.$keydrop). "_all" ."\" class=\"input_checked_drop\"></div><div class=\"d-width\"><a href=\"index.php?filter=".urlencode($content).":All"."\" title=\"(Selecionar tudo)\">(Selecionar tudo)</a></div></li>";
			foreach ($arrayDrop as $keyArrayDrop => $arraydrop) {
				$idInput = md5($content . "_" . $keybuttons.$keydrop."_".preg_replace("/(-|\/|:| )/", "_", $arraydrop));
				if ($keyArrayDrop == 0)
					$dropRender .= "<br>";
				$dropRender .= "<li><div class=\"d-width\"><input type=\"checkbox\" name=\"filter\" value=\"".urlencode($arraydrop)."\" id=\"".$idInput."\" class=\"input_checked_drop\"></div><div class=\"d-width\"><a href=\"index.php?filter=".urlencode($arraydrop).":".urlencode($content)."\" title=\"".$arraydrop."\" class=\"text_input_label\"><label for=\"".$idInput."\">" . $arraydrop . "</label></a></div></li>";
			}

			break;
		}
		$dropRender .= "</div>"; 

	endif;

	echo $dropRender;

	break;
}
