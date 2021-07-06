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

// var_dump($_SESSION);
$datasheet = $_SESSION['objfile']['name'];
$content = $_POST["content"];
$exbAll = $_POST["exbAll"];

// var_dump($exbAll);
foreach ($Filter["buttons"][$content] as $keybuttons => $button) {

	$arrayDrop = array();

	$db = $Db->connect_db();
	$query = $db->prepare("SELECT $content FROM `tb_resp` WHERE `datasheet`= \"$datasheet\"");
	$query->execute();

	foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $keyreturn => $returnValue) {
		// array_unique($arrayDrop, SORT_REGULAR);
		if (!in_array(trim(utf8_decode(strtolower($returnValue[$content]))), $arrayDrop))
			array_push($arrayDrop, trim(utf8_decode(strtolower($returnValue[$content]))));
	}

	$dropRender = null;

	if (isset($button) && $button["drop"]):

		$dropRender .= "<p class=\"txt-center display-buttons\">";
		$dropRender .= "<button class=\"btn-bg btn-display btn-transparent btn_cancel\" data-exec=\"cancel\" title=\"\"></button>";
		$dropRender .= "<button class=\"btn-bg btn-display btn_apply\" data-content=\"".$content."\" title=\"Aplicar\">Aplicar</button>";
		$dropRender .= "</p>";
		$dropRender .= "<span class=\"divider\"></span>";

		if ($Filter["drop"][$content]["hide"]):

			$dropRender .= "<p class=\"txt-left\"><a class=\"btn txt-left\" href=\"?filter&hide=".urlencode($content).(isset($_SESSION['objfile']['aba']) ? "&aba=" . $_SESSION['objfile']['aba'] . "#table-filter" : null )."\" title=\"Ocultar coluna\" data-exec=\"hide\" data-content=\"".$content."\">Ocultar coluna</a></p>";
		endif;

		foreach ($Filter["drop"][$content]["order"] as $keyorder => $order) {

			$getSort = (isset($_GET["sort"]) ? $_GET["sort"] : null);

			if (isset($_SESSION["objfile"]["aba"])):
				$dropRender .= "<p class=\"txt-left\"><a class=\"btn txt-left sort-".strtolower($order)."\" href=\"?filter&sort=".(!is_null($getSort) ? $getSort . ";" . $order : $order ).":".urlencode($content).(isset($_SESSION['objfile']['aba']) ? "&aba=" . $_SESSION['objfile']['aba'] . ($exbAll == 1 ? "&exb_all" : null ) . "#table-filter" : null )."\" data-content=\"".$content."\" data-sort=\"".strtolower($order)."\" title=\"Classificar por ".$order."\">Classificar por ".$order."</a></p>";
			else:
				$dropRender .= "<p class=\"txt-left\"><a class=\"btn txt-left sort-".strtolower($order)."\" href=\"?filter&sort=".(!is_null($getSort) ? $getSort . ";" . $order : $order ).":".urlencode($content).($exbAll == 1 ? "&exb_all" : null )."\" data-content=\"".$content."\" data-sort=\"".strtolower($order)."\" title=\"Classificar por ".$order."\">Classificar por ".$order."</a></p>";
			endif;
		}

		if ($Filter["buttons"][$content]["drop"]):
			$dropRender .= "<div class=\"navdrop\">";

			foreach ($Filter["drop"][$content] as $keydrop => $drop) {

				// Seleciona tudo
				$dropRender .= "<li><div class=\"d-width\"><input checked data-content=\"".$content."\" type=\"checkbox\" name=\"filter\" value=\"(Selecionar tudo)\" id=\"".md5($content . "_" . $keybuttons.$keydrop). "_all" ."\" class=\"input_checked_drop_all\"></div><div class=\"d-width\"><a href=\"?filter=".urlencode($content).":All"."\" title=\"(Selecionar tudo)\">(Selecionar tudo)</a></div></li>";

				foreach ($arrayDrop as $keyArrayDrop => $arraydrop) {
					$idInput = md5($content . "_" . $keybuttons.$keydrop."_".preg_replace("/(-|\/|:| )/", "_", $arraydrop));

					// List
					$dropRender .= "<li><div class=\"d-width\"><input checked data-content=\"".$content."\" type=\"checkbox\" name=\"filter\" value=\"".urlencode($arraydrop)."\" id=\"".$idInput."\" class=\"input_checked_drop\"></div><div class=\"d-width\"><a href=\"?filter=".urlencode($arraydrop).":".urlencode($content)."\" title=\"".$arraydrop."\" class=\"text_input_label\"><label for=\"".$idInput."\">" . ucfirst($arraydrop) . "</label></a></div></li>";
				}

				break;
			}
			$dropRender .= "</div>"; 
		endif;

	endif;

	echo $dropRender;

	break;
}