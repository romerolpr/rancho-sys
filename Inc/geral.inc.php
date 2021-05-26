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
include 'Inc/Classes/ObjectDB.class.php';

/** Define includes **/
include 'define.inc.php';

$get = filter_input_array(INPUT_GET, FILTER_DEFAULT);

$date = new DateTime();
$dataAtual = $date->format("d-m-Y H:i:s");
$diaAtual  = $date->format("d-m-Y");

/** Exit session **/
if (isset($get["exit"])):
	switch ($get["exit"]):
		case 'session_obj':
			unset($_SESSION["objfile"]);
			break;

		case 'session_login':
			unset($_SESSION);
			break;
		
		default:
			unset($_SESSION);
			break;
	endswitch;
endif;

/** Define include errors lists **/
include 'err.list.php';

/** Functions include **/
require_once 'functions.inc.php';

?>