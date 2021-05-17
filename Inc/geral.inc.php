<?php

/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . 'Inc/Classes/');

/** PHPExcel_IOFactory */
include 'Inc/Classes/PHPExcel/IOFactory.php';

/** Classes globals **/
include 'Inc/Classes/Reader.class.php';

$url = "";
$get = filter_input_array(INPUT_GET, FILTER_DEFAULT);

define("TRANSFER", "Transfer/load/");

$dataAtual = new DateTime();
$dataAtual = $dataAtual->format("d-m-Y H:i:s");

define("FORM_INC", "form/inc/");

/** Exit session **/
if (isset($get["exit"])):
	switch ($get["exit"]):
		case 'session':
			unset($_SESSION["objfile"]);
			break;
		
		default:
			unset($_SESSION);
			break;
	endswitch;
endif;

?>