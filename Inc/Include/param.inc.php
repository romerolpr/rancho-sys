<?php

// Setting configs for url param
if (isset($get["worksheetName"]) && !empty($get["worksheetName"])):
	switch ($get["worksheetName"]):
		case 'None':
			$_SESSION["objfile"]["worksheetName"] = null;
			break;
		
		default:
			$_SESSION["objfile"]["worksheetName"] = $get["worksheetName"];
			break;
	endswitch;
endif;