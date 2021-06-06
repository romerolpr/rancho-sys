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
	"RENDER", "Inc/Render/");
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
			"drop" 		=> false
		),

		"email" => array(
			"text" 		=> "Endereço de e-mail",
			"content"	=> "email",
			"drop" 		=> false
		),

		"organizacao_militar" => array(
			"text" 		=> "Organização Militar",
			"content" 	=> "organizacao_militar",
			"drop" 		=> true
		),

		"posto_graduacao" => array(
			"text" 		=> "Posto/Graduação",
			"content" 	=> "posto_graduacao",
			"drop" 		=> true
		),

		"nome" => array(
			"text" 		=> "Nome de guerra",
			"content" 	=> "nome",
			"drop" 		=> false
		),

		// "refc-segunda_feira" => array(
		// 	"text" 		=> "Segunda-feira",
		// 	"content" 	=> "nome",
		// 	"drop" 		=> false
		// ),

		// "refc-terca_feira" => array(
		// 	"text" 		=> "Terça-feira",
		// 	"content" 	=> "nome",
		// 	"drop" 		=> false
		// ),

		// "refc-quarta_feira" => array(
		// 	"text" 		=> "Quarta-feira",
		// 	"content" 	=> "nome",
		// 	"drop" 		=> false
		// ),

		// "refc-quinta_feira" => array(
		// 	"text" 		=> "Quinta-feira",
		// 	"content" 	=> "nome",
		// 	"drop" 		=> false
		// ),

		// "refc-sexta_feira" => array(
		// 	"text" 		=> "Sexta-feira",
		// 	"content" 	=> "nome",
		// 	"drop" 		=> false
		// ),

		// "refc-sabado" => array(
		// 	"text" 		=> "Sábado",
		// 	"content" 	=> "nome",
		// 	"drop" 		=> false
		// ),

		// "refc-domingo" => array(
		// 	"text" 		=> "Domingo",
		// 	"content" 	=> "nome",
		// 	"drop" 		=> false
		// ),

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