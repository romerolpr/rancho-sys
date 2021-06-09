<?php

/** Url base **/
$url = "";

// $teste = "teste";

/** Include path **/
set_include_path(get_include_path() . PATH_SEPARATOR . 'Inc/Classes/');

/** PHPExcel_IOFactory **/
include 'Inc/Classes/PHPExcel/IOFactory.php';

// Extending class
class MyReadFilter implements PHPExcel_Reader_IReadFilter
{
	public function readCell($column, $row, $worksheetName = '') {
		// Read rows 1 to 7 and columns A to E only
		if ($row >= 1) {
			if (in_array($column, range('A','Z'))) {
				return true;
			}
		}
		return false;
	}
}

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
if (isset($get["exit"]) && !empty($get["exit"])):
	if ($get["exit"] == 'session_obj') unset($_SESSION["objfile"]);
	if ($get["exit"] == 'session_login') unset($_SESSION["user_login"]);
endif;

/** Define include errors lists **/
include 'err.list.php';

/** Functions include **/
require_once 'functions.inc.php';

?>