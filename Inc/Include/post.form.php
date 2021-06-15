<?php

// Add file
if (isset($_POST["Envia"]) && !empty($_POST["Envia"])):

	if (isset($_FILES["file"]) && !empty($_FILES["file"])):

		$ObjLoad = array(
			"name" => (!empty($_FILES["file"]["name"]) ? $_FILES["file"]["name"] : "undefined"),
			"tmp_name" => $_FILES["file"]["tmp_name"],
			"inputFileType" => $_FILES["file"]["type"],
			"ExcelFileType" => "Excel2007",
			"worksheetName" => null,
			"file" => array("size" => $_FILES["file"]["size"], filemtime($_FILES["file"]))
		);

		$format = explode(".", $ObjLoad["name"]);

		if (in_array($ObjLoad["inputFileType"], array('application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/x-excel', 'application/x-msexcel',)) && end($format) !== "csv"):
			/** Set a new name for file **/
			$temp = explode(".", $ObjLoad["name"]);
			$newfilename = clearString(str_replace(" ", "_", $temp[0])) . '.' . end($temp);

			$timestamp = strtotime(implode('-', array_reverse(explode('/', $dataAtual))));

			$ObjLoad["name"] = $timestamp . "_" . $newfilename;
			$dir = TRANSFER . $ObjLoad["name"];

			if(move_uploaded_file($ObjLoad["tmp_name"], $dir)):
				$ObjLoad["tmp_name"] = $dir;

				if (isset($_SESSION["objfile"]))
					unset($_SESSION["objfile"]);
				
				$_SESSION["objfile"] = $ObjLoad;
				header("location: ".$url."index.php");
			else:
				$header = $url."index.php?err=not_uploaded&filename=" . $ObjLoad["name"];
				// echo "<span class='alert danger'><strong>Falha</strong>: Não foi possível adicionar o arquivo: ".$ObjLoad["name"]."</span>";
			endif;

		else:
			$header = $url."index.php?err=not_supported&filename=" . $ObjLoad["name"];
			// echo "<span class='alert warning'><strong>Atenção</strong>: Formato de arquivo não permitido.</span>";
		endif;

	else:
		$header = $url."index.php?err=file&filename=" . $ObjLoad["name"];
		// echo "<span class='alert warning'><strong>Atenção</strong>: Adicione um arquivo válido.</span>";
	endif;
endif;

// Login
if (isset($_POST["Login"]) && !empty($_POST["Login"])):

	$myInput = array(
	    "username" => md5(trim($_POST["user"])),
	    "password" => md5($_POST["pass"])
	);

	// var_dump($myInput);

	if (in_array('', $_POST)):

	    $header = "index.php?err=username_or_pass";
	else:

	    $Obj = $Db->return_query($Db->connect_db(), TB_USERS);

	    foreach ($Obj as $key => $values):

	        if ($myInput["username"] == $values["username"] && $myInput["password"] == $values["password"]):
	            $_SESSION["user_login"] = $myInput;
	            $header = $url . "index.php";
	            break;
	        else:
	            // echo "<script>alert('Nome de usuário ou senha inválido.')</script>";
	            $header = "index.php?err=username_or_pass";
	            break;
	        endif;
	    endforeach;

	    // header("location: index.php");


	endif;

endif;