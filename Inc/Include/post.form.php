<?php

// Add file
if (isset($_POST["Envia"]) && !empty($_POST["Envia"])):


	if (isset($_FILES["file"]) && !empty($_FILES["file"])):


		$ObjLoad = array(
			"name" => (!empty($_FILES["file"]["name"]) ? $_FILES["file"]["name"] : "undefined"),
			"tmp_name" => $_FILES["file"]["tmp_name"],
			"inputFileType" => $_FILES["file"]["type"],
			"ExcelFileType" => "Excel2007",
			"worksheetName" => null
		);

		if (in_array($ObjLoad["inputFileType"], array('application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/x-excel', 'application/x-msexcel',))):

			/** Set a new name for file **/
			$temp = explode(".", $ObjLoad["name"]);
			$newfilename = clearString(str_replace(" ", "_", $temp[0])) . '.' . end($temp);
			$ObjLoad["name"] = $newfilename;
			$dir = TRANSFER . $newfilename;

			if(move_uploaded_file($ObjLoad["tmp_name"], $dir)):
				$ObjLoad["tmp_name"] = $dir;
				$_SESSION["objfile"] = $ObjLoad;
				header("location: ".$url."index.php");
			else:
				header("location: ".$url."index.php?err=not_uploaded&filename=" . $ObjLoad["name"]);
				// echo "<span class='alert danger'><strong>Falha</strong>: Não foi possível adicionar o arquivo: ".$ObjLoad["name"]."</span>";
			endif;

		else:
			header("location: ".$url."index.php?err=not_supported&filename=" . $ObjLoad["name"]);
			// echo "<span class='alert warning'><strong>Atenção</strong>: Formato de arquivo não permitido.</span>";
		endif;

	else:
		header("location: ".$url."index.php?err=file&filename=" . $ObjLoad["name"]);
		// echo "<span class='alert warning'><strong>Atenção</strong>: Adicione um arquivo válido.</span>";
	endif;
endif;

// Login
if (isset($_POST["Login"]) && !empty($_POST["Login"])):

	$myInput = array(
	    "username" => trim($_POST["user"]),
	    "password" => md5($_POST["pass"])
	);

	if (in_array('', $_POST)):
	    header("location: index.php?err=username_or_pass");
	else:

	    $Obj = $Db->return_query($Db->connect_db(), "users");

	    // var_dump($myInput);
	    // var_dump($Obj);

	    foreach ($Obj as $key => $values):
	        // var_dump($myInput);
	        if ($myInput["username"] == $values["username"] && $myInput["password"] == $values["password"]):
	            $_SESSION["user_login"] = $myInput;
	            $header = $url . "index.php?exb=painel";
	        else:
	            // echo "<script>alert('Nome de usuário ou senha inválido.')</script>";
	            $header = "index.php?err=username_or_pass";
	            break;
	        endif;
	    endforeach;

	    header("location: $header");

	endif;

endif;