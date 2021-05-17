<?php

if (isset($_POST["Adicionar"]) && !empty($_POST["Adicionar"])):

	if (isset($_FILES["file"]) && !empty($_FILES["file"])):

		$ObjLoad = array(
			"name" => $_FILES["file"]["name"],
			"tmp_name" => $_FILES["file"]["tmp_name"],
			"inputFileType" => $_FILES["file"]["type"],
			"ExcelFileType" => "Excel2007",
			"worksheetName" => null
		);

		if (in_array($ObjLoad["inputFileType"], array('application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/x-excel', 'application/x-msexcel',))):

			/** Set a new name for file **/
			$temp = explode(".", $ObjLoad["name"]);
			$newfilename = round(microtime(true)) . '.' . end($temp);
			$dir = TRANSFER . $newfilename;

			if(move_uploaded_file($ObjLoad["tmp_name"], $dir)):
				$ObjLoad["tmp_name"] = $dir;
				$_SESSION["objfile_to_compare"] = array($ObjLoad, $_SESSION["objfile"]);
				header("location: ".$url."index.php?compare");
		else:
			echo "<span class='alert danger'><strong>Falha</strong>: Não foi possível adicionar o arquivo: ".$ObjLoad["name"]."</span>";
		endif;

		else:
			echo "<span class='alert warning'><strong>Atenção</strong>: Formato de arquivo não permitido.</span>";
		endif;

	else:
		echo "<span class='alert warning'><strong>Atenção</strong>: Adicione um arquivo válido.</span>";
	endif;

endif;


?>