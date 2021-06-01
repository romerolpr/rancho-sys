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
			"drop" 		=> true
		),

		"email" => array(
			"text" 		=> "Endereço de e-mail",
			"content"	=> "email",
			"drop" 		=> true
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
			"drop" 		=> true
		),

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
		)

	)
);

?>