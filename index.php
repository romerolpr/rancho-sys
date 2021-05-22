<?php

session_start();

include_once 'Inc/geral.inc.php';

$Render = new Render();
$Alert 	= new DisplayAlert();
$Db 	= new ObjectDB();

$breadcrumbTitle = isset($_SESSION["user_login"]) ? "Painel" : "Início";
$Db->setter(HOST, USER, PASS, DBNAME);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>SisA - Sistema de Arranchamento</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $url?>Dist/css/style.css">
</head>
<body>

	<header>
	    <?php include 'Inc/topo.inc.php' ?>
	</header>

	<section class="content">

		<div class="container">

			<div class="breadcrumb">
			    <strong>Sistema de Arranchamento Virtual</strong> > <?php echo $breadcrumbTitle; ?></span>
			</div>

			<aside>
			    <nav>
			        <ul>
			            <?php include FRONT . 'nav.inc.php'; ?>
			        </ul>
			    </nav>
			</aside>

			<article>

				<div class="main">

		<?php

		// display Error
		include FRONT . 'err.inc.php';

		// Post form file and login
		include INC . 'post.form.php';

		// Config params
		include INC . 'param.inc.php';

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
				include FRONT . 'form.php';

			endif;


		else: 

			include FRONT . 'form.php';

		endif;

		?>

		</div>

	</article>

		</div>

	</section>

</body>
</html>