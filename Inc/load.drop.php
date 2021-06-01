<?php

session_start();

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

	$dropRender = "<ul class=\"navdrop\">";

	if ($button["drop"]):

		if ($Filter["drop"][$content]["hide"]):
			$dropRender .= "<li><a href=\"index.php?hide=".urlencode($content)."\" title=\"Ocultar\">Ocultar</a></li>";
		endif;

		foreach ($Filter["drop"][$content]["order"] as $keyorder => $order) {
			$dropRender .= "<li><a href=\"index.php?sort=".urlencode($content).";".$order."\" title=\"Classificar por ".$order."\">Classificar por ".$order."</a></li>";
		}

		foreach ($Filter["drop"][$content] as $keydrop => $drop) {

			foreach ($arrayDrop as $keyArrayDrop => $arraydrop) {
				if ($keyArrayDrop == 0)
					$dropRender .= "<br>";
				$dropRender .= "<li><input type=\"checkbox\" name=\"filter\" value=\"".urlencode($arraydrop)."\" id=\"".$keybuttons.$keydrop."_".str_replace(" ", "_", $arraydrop)."\"><a href=\"index.php?filter=".urlencode($arraydrop)."\" title=\"".$arraydrop."\">" . $arraydrop . "</a></li>";
			}

			break;
		}
		$dropRender .= "</ul>"; 
	endif;

	echo $dropRender;

	break;
}
