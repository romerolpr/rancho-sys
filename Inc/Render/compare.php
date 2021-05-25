<h1>Comparar dados</h1>

<?php

/* Set the current obj */
include_once 'Inc/Render/compare.inc.php';
if (isset($get["removeDatasheet"]) && $get["removeDatasheet"] == 2) unset($_SESSION["objfile_to_compare"]);

if (isset($_SESSION["objfile_to_compare"])):
	$Compare = new Compare($_SESSION["objfile_to_compare"][0], $_SESSION["objfile_to_compare"][1]);

	if (isset($get["compare"]) && $get["compare"] == "true"):

		if (is_null($_SESSION["objfile_to_compare"][0]["worksheetName"]) or is_null($_SESSION["objfile_to_compare"][1]["worksheetName"])):

			$objReader = PHPExcel_IOFactory::createReader($_SESSION["objfile_to_compare"][$get["choiceDatasheet"]]["ExcelFileType"]);
			$worksheetData = $objReader->listWorksheetInfo($_SESSION["objfile_to_compare"][$get["choiceDatasheet"]]["tmp_name"]);

			echo "<p>Selecine o Datasheet ",$get["choiceDatasheet"],"</p><ul class='list'>";
			foreach ($worksheetData as $key => $worksheet):
				echo '<li><a href="',$url,'?compare=true&choiceDatasheet=',$key,'&worksheetNameCompare=',$worksheet['worksheetName'],'">', $worksheet['worksheetName'],'</a></li>';
			endforeach;
			echo "</ul>";

			if (isset($get["worksheetNameCompare"])):
				if ($get["worksheetNameCompare"] != "None"):
					$_SESSION["objfile_to_compare"][$get["choiceDatasheet"]]["worksheetName"] = $get["worksheetNameCompare"];
				else:
					$_SESSION["objfile_to_compare"][$get["choiceDatasheet"]]["worksheetName"] = null;
				endif;
			endif;

		endif;
		
		include 'compare.start.php';

	else:

		include 'compare.select.php';

	endif;



else:

	include 'compare.select.php';

endif;

?>