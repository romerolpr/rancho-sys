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
	<link rel="stylesheet" type="text/css" href="<?php echo $url?>Dist/css/fontawesome.css">
	<script><?php include "Dist/js/jquery.js"; ?></script>
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

					/* Load datasheets */
					include RENDER . 'datasheet.php';

				else:

					/* Including the compare file */
					include RENDER . 'compare.php';

				endif;

			else:

				$Alert->setConfig("danger", "<strong>404</strong>: Não foi possível localizar o arquivo em: \"/Transfer/load/".str_replace(" ", "_", $_SESSION["objfile"]["name"])."\". Adicione o arquivo novamente.</span>");
				echo ($Alert->displayPrint());
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

	<script>
		function confirm_clicked(url, action) {
			if (confirm("Você tem certeza que deseja "+action+" este item?")) {
				document.location = url;
			}
		}
		$(".btn_click_consult").on("click", function(e){
			var url = $(this).attr("href"), action = $(this).attr("data-action");
			e.preventDefault();
			confirm_clicked(url, action);
		});

		var clicked = false, popup = false;
		$(".btn_expand").on("click", function(e){
			e.preventDefault();
			if (!clicked){
				if (!popup){
					if (confirm("Aviso: Pressione a tecla \"ESC\" para minimizar a tabela novamente."))
						$(".box-table").addClass("window_fixed");
						popup=true;
				} else {
					$(".box-table").addClass("window_fixed");
				}
				clicked = true;
			}
		});

		document.addEventListener('keydown', function (event) {
		    if (event.keyCode == 27 && clicked){
		     	$(".box-table").removeClass("window_fixed");
		     	clicked = false;	
		    }
		});

	</script>

</body>
</html>