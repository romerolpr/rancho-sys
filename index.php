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
		if ($_SESSION["user_login"]["username"] == $values["username"] && $_SESSION["user_login"]["password"] == $values["password"]):
			$_SESSION["user_login"]["nome"] = $values["nome"];
			$_SESSION["user_login"]["nvl_access"] = $values["nvl_access"];
		endif;
	endforeach; 
endif;

if (isset($get["action"]) && $get["action"] == "generateReport"):

	if (isset($_SESSION["objfile"])):
		header("location: report.php?datasheetFile=" . $_SESSION["objfile"]["name"]);
	endif;

endif;

if (!is_dir("./Transfer/load")):
	mkdir("./Transfer/load", 0700);
endif;

if (isset($get["path"]) && isset($get["unlink"]) && !empty($get["path"])):
	foreach ($Files as $key => $value):
		if ($value["tmp_name"] == $get["path"])
			unlink($arquivo);
			header("location: index.php?exb=recents&unlink=true");
	endforeach;
endif;

if (isset($get["new"]) && !empty($get["new"])):
	foreach ($Files as $key => $value):
		if ($value["name"] == $get["new"])
			$_SESSION["objfile"] = $value;
			header("location: index.php");
	endforeach;
endif;

// var_dump($Db::$host);

// var_dump($_GET);
// unset($_SESSION);

if(!isset($get["aba"])):
	unset($_SESSION['objfile']['aba']);
endif;

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

	<link rel=icon type=image/png href="Dist/image/brasao-exercito.png">

	<script><?php include "Dist/js/jquery.js"; ?></script>

</head>
<body class="loading">

	<div class="modal" id="window_loading"></div>

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


				// var_dump($_SESSION["objfile"]);

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

						if ($_SESSION["user_login"]["nvl_access"] == 1):
							$Alert->setConfig("danger", "<strong>404</strong>: Não foi possível localizar o arquivo em: \"/Transfer/load/".str_replace(" ", "_", $_SESSION["objfile"]["name"])."\". Adicione o arquivo novamente.</span>");
							echo ($Alert->displayPrint());
							include FRONT . 'form.php';
						else:
							$Alert->setConfig("danger", "<strong>404</strong>: Não foi possível localizar o arquivo em: \"/Transfer/load/".str_replace(" ", "_", $_SESSION["objfile"]["name"])."\". Adicione o arquivo novamente.</span>");
							echo ($Alert->displayPrint());
						endif;

					endif;


				else: 

					if (isset($_SESSION["user_login"]) && $_SESSION["user_login"]["nvl_access"] != 1):
						$myFileatime = array();
						foreach ($Files as $key => $value):
							array_push($myFileatime, $value["file"]["fileatime"]);
						endforeach;

						foreach ($Files as $key => $value):
							if ($value["file"]["fileatime"] == min($myFileatime)):
								$header = "index.php?worksheetName=" . $value["name"];
								$_SESSION["objfile"] = $value;
								break;
							endif;
						endforeach;
					endif;	

					include FRONT . 'form.php';
					if (isset($_SESSION["objfile"]["name"])):
						$Alert->setConfig("warning", "<strong>Aviso</strong>: Não existem arquivos disponíveis no sistema. Solicite ao administrador.");
						echo ($Alert->displayPrint());
					endif;	

				endif;

				if(isset($header) && isset($_SESSION["user_login"])) echo "<script>window.location.replace('$header')</script>";

				?>

		</div>

	</article>

		</div>

	</section>


	<script>
			
		function searchOnTable(){
			var $rows = $('#table-filter tr:not(.bar-table)');
			$("#searchbar").on("keyup", function(){
				var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
				$rows.show().filter(function() {
			        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
			        return !~text.indexOf(val);
			    }).hide();
			});
		}

		var exbAll = "<?php echo ( isset($get['exb_all']) ? true : false ) ?>";

		// Events, functions js
		<?php include 'Dist/js/Filter.js'; ?>
		<?php include 'Dist/js/geral.js'; ?>

		searchOnTable();
		var windowFixed = localStorage.getItem("window");
		if (windowFixed != "false"){
			$(".box-table").addClass("window_fixed");
			$(".btn_expand").addClass("btn_active");
			click_btn = true;
		} else {
			$(".box-table").removeClass("window_fixed");
			$(".btn_expand").removeClass("btn_active");
			click_btn = false;
		}

		$(document).ready(function(){
			$("body").removeClass("loading");
		});

	</script>


</body>
</html>