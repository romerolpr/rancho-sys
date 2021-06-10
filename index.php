<?php

session_start();

include_once 'Inc/geral.inc.php';

$Render = new Render();
$Alert 	= new DisplayAlert();
$Db 	= new ObjectDB();

$Db->setter(HOST, USER, PASS, DBNAME);

$breadcrumbTitle = isset($_SESSION["user_login"]) ? "Painel" : "Início";


if (isset($_SESSION["user_login"])):
	foreach ($Db->return_query($Db->connect_db(), TB_USERS) as $key => $values):
		if ($_SESSION["user_login"]["username"] == $values["username"] && $_SESSION["user_login"]["password"] == $values["password"]) $_SESSION["user_login"]["nome"] = $values["nome"];
	endforeach; 
endif;

if (isset($get["action"]) && $get["action"] == "generateReport"):

	if (isset($_SESSION["objfile"])):
		header("location: report.php?datasheetFile=" . $_SESSION["objfile"]["name"] . "&h=1");
	endif;

endif;

if (!is_dir("./Transfer/load")):
	mkdir("./Transfer/load", 0700);
endif;

if (isset($get["path"]) && isset($get["unlink"]) && !empty($get["path"])):
	foreach ($Files as $key => $value):
		if ($value["tmp_name"] == $get["path"])
			unlink($arquivo);
			header("location: index.php?unlink=true");
	endforeach;
endif;

if (isset($get["new"]) && !empty($get["new"])):
	foreach ($Files as $key => $value):
		if ($value["name"] == $get["new"])
			$_SESSION["objfile"] = $value;
			header("location: index.php");
	endforeach;
endif;


// var_dump($_GET);
// unset($_SESSION);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>SisA - Sistema de Arranchamento</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $url?>Dist/css/style.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $url?>Dist/css/fontawesome.css">

	<script><?php include "Dist/js/jquery.js"; ?></script>

</head>
<body>

	<div class="modal"></div>

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

				if(isset($_SESSION["objfile"]) && !empty($_SESSION["objfile"]) && isset($_SESSION["user_login"]) && !empty($_SESSION["user_login"])):

					if (file_exists($_SESSION["objfile"]["tmp_name"])):

						// Setting the class
						$Render->setTableName($_SESSION["objfile"]["name"]);
						$Render->setDatasheetName($_SESSION["objfile"]["worksheetName"]);

						$inputFileName = $_SESSION["objfile"]["ExcelFileType"];
						$inputTmp = $_SESSION["objfile"]["tmp_name"];

						$objReader = PHPExcel_IOFactory::createReader($inputFileName);
						$worksheetData = $objReader->listWorksheetInfo($inputTmp);

						/* Load datasheets */
						include RENDER . 'datasheet.php';

					else:

						$Alert->setConfig("danger", "<strong>404</strong>: Não foi possível localizar o arquivo em: \"/Transfer/load/".str_replace(" ", "_", $_SESSION["objfile"]["name"])."\". Adicione o arquivo novamente.</span>");
						echo ($Alert->displayPrint());
						include FRONT . 'form.php';

					endif;


				else: 

					if (isset($_SESSION["objfile"])):
						$Alert->setConfig("warning", "<strong>Aviso</strong>: O arquivo \"".$_SESSION["objfile"]["name"]."\" permanece aberto. <a href='index.php?exit=session_obj' title='Fechar arquivo' class='btn btn_click_consult'>Fechar arquivo</a></span>");
						echo ($Alert->displayPrint());
					endif;
					include FRONT . 'form.php';

				endif;

				?>

		</div>

	</article>

		</div>

	</section>

	<!-- Events, functions js -->
	<script><?php include 'Dist/js/Filter.js'; ?></script>
	<script><?php include 'Dist/js/geral.js'; ?></script>

	<script>
		// $('body').addClass("loading");
		// $(document).ready(function(){
		// 	$('body').removeClass("loading");
		// });
	</script>

</body>
</html>