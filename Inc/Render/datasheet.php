<h1>
	<?php
	if (isset($get["exb"])):
		if ($get["exb"] != "users"):
			echo $_SESSION["objfile"]["name"];
		else:
			echo "Gerenciar usuários";
		endif;
	else:
		echo $_SESSION["objfile"]["name"];
	endif;
	?>
</h1>

<?php

if ($_SESSION["user_login"]["nvl_access"] == 1):

	if(!isset($get["exb"])):

		$listWorksheet = null;
		if (is_null($_SESSION["objfile"]["worksheetName"])):

			$Alert->setConfig("warning", "<strong>Dica</strong>: Para que você consiga fazer uso de todos os recursos, selecione a página recomendada pelo sistema.");
			echo $Alert->displayPrint();

			$listWorksheet .= "<br><p>Selecione a página desejada:</p>";
			$listWorksheet .= "<ul class='list'>";
			$exists = 0;

			foreach ($worksheetData as $worksheet):
				if (!preg_match("/respostas/", strtolower(trim($worksheet['worksheetName'])))):
					$listWorksheet .= '<li ' . ( isset($get["more"]) && $get["more"] == "listComplete"  ? null : "class=\"none\"" ) . '><a href="'.	$url.	'?worksheetName='.	$worksheet['worksheetName'].	'">'.	 $worksheet['worksheetName'].	'</a></li>';
				else:
					$listWorksheet .= '<li><a href="'.	$url.	'?worksheetName='.	$worksheet['worksheetName'].	'">'.	 $worksheet['worksheetName'].	' <strong>(Maior compatibilidade com sistema)</strong></a></li>';
				endif;
			endforeach;

			$listWorksheet .= "<br>";
			if (!isset($get["more"])):
				$listWorksheet .= "<li><a href='".$url."index.php?more=listComplete' title=\"Mostrar todos\">Mostrar todos</a><li>";
			else:
				$listWorksheet .= "<li><a href='".$url."index.php' title=\"Mostrar menos\">Mostrar menos</a><li>";
			endif;
			$listWorksheet .= "</ul>";
			$listWorksheet .= "<br>";

			echo $listWorksheet;

			// echo "<p>Arquivos recentes</p>";
			// echo "<span class=\"divider\"></span>";

			// $nonTitle = true;
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
			$nonTitle = true;
			include PAGES . 'recents.inc.php';
		elseif ($get["exb"] == "users"):
			include PAGES . 'users.php';
		else:

			$Alert->setConfig("warning", "<strong>Aviso</strong>: Ao adicionar um novo arquivo o sistema fechará automaticamente o arquivo atual.</span>");
			echo ($Alert->displayPrint());

			$nonTitle = true;
			include PAGES . 'add.files.php';

		endif;

	endif;

else:

	include RENDER . 'load.php';

endif;

