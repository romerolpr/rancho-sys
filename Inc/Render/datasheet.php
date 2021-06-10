<h1><?php echo $_SESSION["objfile"]["name"]; ?></h1>

<?php

if(!isset($get["exb"])):

	if (is_null($_SESSION["objfile"]["worksheetName"])):

		echo "<br><p>Selecione o Datasheet:</p>";
		echo "<ul class='list'>";

		foreach ($worksheetData as $worksheet):
			echo '<li><a href="',$url,'?worksheetName=',$worksheet['worksheetName'],'">', $worksheet['worksheetName'],'</a></li>';
		endforeach;

		echo "</ul>";
		echo "<br>";

		// echo "<p>Arquivos recentes</p>";
		// echo "<span class=\"divider\"></span>";

		include PAGES . 'recents.inc.php';

		// var_dump($Files);

	else:

		if (isset($get["exb"])):
			switch ($get["exb"]):
				
				case 'add_new':
					/* Including the render file */
					$Alert->setConfig("warning", "<strong>Aviso</strong>: Ao adicionar um novo arquivo o sistema fechará automaticamente o arquivo atual.</span>");
					echo ($Alert->displayPrint());

					$nonTitle = true;
					include PAGES . 'add.files.php';
					break;
				
				default:
					include RENDER . 'load.php';
					break;

			endswitch;
		else:
			include RENDER . 'load.php';
		endif;

	endif;

else:

	if ($get["exb"] == "recents"):
		include PAGES . 'recents.inc.php';
	else:

		$Alert->setConfig("warning", "<strong>Aviso</strong>: Ao adicionar um novo arquivo o sistema fechará automaticamente o arquivo atual.</span>");
		echo ($Alert->displayPrint());

		$nonTitle = true;
		include PAGES . 'add.files.php';

	endif;

endif;