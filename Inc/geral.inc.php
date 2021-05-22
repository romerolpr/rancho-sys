<?php

/** Url base **/
$url = "";

/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . 'Inc/Classes/');

/** PHPExcel_IOFactory */
include 'Inc/Classes/PHPExcel/IOFactory.php';

/** Classes globals **/
include 'Inc/Classes/Reader.class.php';
include 'Inc/Classes/DisplayAlert.class.php';

/** Define includes **/
include 'define.inc.php';

$get = filter_input_array(INPUT_GET, FILTER_DEFAULT);

$dataAtual = new DateTime();
$dataAtual = $dataAtual->format("d-m-Y H:i:s");

/** Exit session **/
if (isset($get["exit"])):
	switch ($get["exit"]):
		case 'session':
			unset($_SESSION["objfile"]);
			break;

		case 'session_login':
			unset($_SESSION["user_login"]);
			break;
		
		default:
			unset($_SESSION);
			break;
	endswitch;
endif;

/** Define include errors lists **/
include 'err.list.php';

?>