<?php

session_start();

include_once 'Inc/geral.inc.php';

$Render = new Render();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Rancho</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $url?>Dist/css/style.css">
</head>
<body>

	<header>
	    <?php include 'Inc/topo.inc.php' ?>
	</header>

	<section class="content">

		<div class="container">

			<div class="breadcrumb">
			    <strong>Exército Brasileiro</strong> > Sistema de Arranchamento Virtual</span>
			</div>

			<aside>
			    <nav>
			        <ul>
			            <li><a href="<?php echo $url?>index.php" title="Ver planilha">Ver planilha</a></li>
			            <li><a href="<?php echo $url?>index.php?compare" title="Realizar análise de dados">Realizar análise de dados</a></li>
			            <li><a href="<?php echo $url?>index.php?exit=session" title="Fechar arquivo">Fechar arquivo</a></li>
			            <!-- <br> -->
			            <!-- <li><a href="<?php echo $url?>admin.php" title="Acesso administrador">Acesso administrador</a></li> -->
			            <!-- <li><a href="#" title="Informex">Informex</a></li> -->
			        </ul>
			    </nav>
			</aside>

			<article>

				<div class="main">

					<h1><?php echo (isset($_SESSION["objfile"]) ? $_SESSION["objfile"]["name"] : "Adicionar arquivo"); ?></h1>

		<?php

		if (isset($_POST["Envia"]) && !empty($_POST["Envia"])):


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
						$_SESSION["objfile"] = $ObjLoad;
						header("location: ".$url."index.php");
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

		if(isset($_SESSION["objfile"]) && !empty($_SESSION["objfile"])):

			if (file_exists($_SESSION["objfile"]["tmp_name"])):

				if (!isset($get["compare"])):
					// Setting the class
					$Render->setTableName($_SESSION["objfile"]["name"]);
					$Render->setDatasheetName($_SESSION["objfile"]["worksheetName"]);

					$inputFileName = $_SESSION["objfile"]["ExcelFileType"];
					$inputTmp = $_SESSION["objfile"]["tmp_name"];

					$objReader = PHPExcel_IOFactory::createReader($inputFileName);
					$worksheetData = $objReader->listWorksheetInfo($inputTmp);

					if (is_null($_SESSION["objfile"]["worksheetName"])):
						echo "<br><p>Datasheet:</p>";
						echo "<ul class='list'>";
						foreach ($worksheetData as $worksheet):
							echo '<li><a href="',$url,'?worksheetName=',$worksheet['worksheetName'],'">', $worksheet['worksheetName'],'</a></li>';
						endforeach;
						// echo '<br><li><a href="',$url,'?exit=session">Novo arquivo</a></li>';
						echo "</ul>"; ?>

						<br>
						<p>Arquivos recentes</p>
						<span class="divider"></span>

						<?php

						$Files = array("name" => array(),"size" => array());

						foreach (glob(TRANSFER . "*.*") as $arquivo) {
						    array_push($Files["name"], $arquivo);
						    array_push($Files["size"], filesize($arquivo));
						}

						var_dump($Files);

						?>

					<?php else:

						/* Including the render file */
						include 'Inc/Render/load.php';

					endif;

				else:

					/* Including the compare file */
					include 'Inc/Render/compare.php';

				endif;

			else:

				// unset($_SESSION["objfile"]);
				echo '<span class="alert danger"><strong>404</strong>: Não foi possível localizar o arquivo em: "//'.$_SESSION["objfile"]["tmp_name"].'". Adicione o arquivo novamente.</span>';
				include 'Inc/Include/form.php';

			endif;


		else: 

			include 'Inc/Include/form.php';

		endif;

		?>

		</div>

	</article>

		</div>

	</section>

</body>
</html>