<?php

date_default_timezone_set('America/Sao_Paulo');

/**
	Define for includes on project
**/
define(
	"TRANSFER", "Transfer/load/");
define(
	"FRONT", "Inc/Include/Front/");
define(
	"INC", "Inc/Include/");
define(
	"CLASS", "Inc/Classes/");
define(
	"RENDER", "Inc/Render/");
define(
	"REPORT", "Inc/Report/");
define(
	"PAGES", "Inc/Include/Front/pages/");

/** 
	Db config
**/

define(
	"HOST", "127.0.0.1");
define(
	"USER", "root");
define(
	"PASS", "");
define(
	"DBNAME", "dbranch");
define(
	"ALIAS", "dbranch");
define(
	"LIMIT", 50);

/** 
	Db TABLES
**/
define(
	"TB_USERS", "tb_users");
define(
	"TB_RESP", "tb_resp");
define(
	"TB_SHEET", "tb_sheet");
define(
	"TB_CONF", "tb_conf");

/**
	Options filter
**/
$Filter = array(

	"buttons" => array(

		"carimbo" => array(
			"text" 		=> "Carimbo de data/hora",
			"content"	=> "carimbo",
			"drop" 		=> false,
			"hided"		=> (!isset($_GET["exb_all"]) ? true : false),
		),

		"email" => array(
			"text" 		=> "Endereço de e-mail",
			"content"	=> "email",
			"drop" 		=> false,
			"hided"		=> (!isset($_GET["exb_all"]) ? true : false),
		),

		"organizacao_militar" => array(
			"text" 		=> "Organização Militar",
			"content" 	=> "organizacao_militar",
			"drop" 		=> true,
			"hided"		=> (!isset($_GET["exb_all"]) ? false : false),
		),

		"posto_graduacao" => array(
			"text" 		=> "Posto/Graduação",
			"content" 	=> "posto_graduacao",
			"drop" 		=> true,
			"hided"		=> (!isset($_GET["exb_all"]) ? false : false),
		),

		"nome" => array(
			"text" 		=> "Nome de guerra",
			"content" 	=> "nome",
			"drop" 		=> false,
			"hided"		=> (!isset($_GET["exb_all"]) ? false : false),
		),


		// "refc" => array()

	),

	"drop" => array(

		"carimbo" => array(
			"hide" 	=> true,
			"order" => array("A-Z", "Z-A"),
		),

		"email" => array(
			"hide" 	=> true,
			"order" => array("A-Z", "Z-A"),
		),

		"organizacao_militar" => array(
			"hide" 	=> true,
			"order" => array("A-Z", "Z-A"),
		),

		"posto_graduacao" => array(
			"hide" 	=> true,
			"order" => array("A-Z", "Z-A"),
		),

		"nome" => array(
			"hide" 	=> true,
			"order" => array("A-Z", "Z-A"),
		),

	)
);

$Files = array();
foreach (glob(TRANSFER . "*.*") as $arquivo):
	array_push(
		$Files, array(
			"name" 		=> basename($arquivo),
			"tmp_name"	=> $arquivo, 
			"ExcelFileType" => "Excel2007",
			"inputFileType" => filetype($arquivo),
			"worksheetName" => null,
			"file" => array(
				"mtime" 	=> filemtime($arquivo), 
				"size" 		=> filesize($arquivo)
			)
		));
endforeach;

?>