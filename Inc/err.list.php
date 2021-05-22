<?php

$filename = isset($get["filename"]) ? $get["filename"] : null;
$ErrList = array(

	"form" => array(
		// danger
		"username_or_pass" 	=> array(
			"<strong>Dados incorretos</strong>: Existem dados informados incorretos", "danger"),
		"file"				=> array(
			"<strong>Atenção</strong>: Adicione um arquivo válido.", "danger"),
		// warning
		"not_supported"		=> array(
			"<strong>Falha</strong>: Arquivo não suportado no sistema: ".$filename."", "warning"),
		"not_uploaded"		=> array("<strong>Falha</strong>: Não foi possível adicionar o arquivo: ".$filename."", "warning"),
	),

	"session" => array(
		// success	
		"session_login" 	=> array(
			"<strong>Sessão finalizada</strong>: Você encerrou a sessão com sucesso.", "success"),
		"session_obj"		=> array(
			"<strong>Sucesso</strong>: Você removeu o arquivo da caixa de afazeres.", "success"),

	),

);