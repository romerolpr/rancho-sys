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
		echo "<p>Arquivos recentes</p>";
		echo "<span class=\"divider\"></span>";

		$Files = array();
		foreach (glob(TRANSFER . "*.*") as $arquivo):
			array_push(
				$Files, array(
					"name" 		=> basename($arquivo),
					"tmp_name"	=> $arquivo, 
					"ExcelFileType" => "Excel2007",
					"inputFileType" => filetype($arquivo),
					"worksheetName" => null,
					"file" => array(
						"mtime" 	=> filemtime($arquivo), 
						"size" 		=> filesize($arquivo)
					)
				));


			if (isset($get["path"]) && isset($get["unlink"]) && !empty($get["path"])):
				foreach ($Files as $key => $value):
					if ($value["tmp_name"] == $get["path"])
						unlink($arquivo);
						header("location: index.php?unlink=true");
				endforeach;
			endif;


		endforeach;

		if (isset($get["new"]) && !empty($get["new"])):
			foreach ($Files as $key => $value):
				if ($value["name"] == $get["new"])
					$_SESSION["objfile"] = $value;
					header("location: index.php");
			endforeach;
		endif;

		echo "<table>";
		echo "<tr class=\"bar-table\">";
		echo "<td>Alocação</td>";
		echo "<td>Última modificação</td>";
		echo "<td>Tamanho</td>";
		echo "<td>Ação</td>";
		echo "</tr>";
			if (!empty($Files)):
				foreach ($Files as $key => $value):

					// var_dump($value);

					$dateFile = date("d-m-Y H:i:s", $value["file"]["mtime"]);

					$data1 = implode('-', array_reverse(explode('/', $dateFile)));
					$data2 = implode('-', array_reverse(explode('/', $dataAtual)));
					$d1 = strtotime($data1); 
					$d2 = strtotime($data2);

					$conta = ($d2 - $d1) /86400;

					if ($conta == 0):
						$dataFinal = "Agora mesmo";
					elseif($conta < 1):
						$dataFinal = "Hoje";
					else:
						$dataFinal = "Há aprox. " . round($conta) . " " . ($conta == 1 ? "dia" : "dias");
					endif;

					echo "<tr>";
					echo "<td><a class=\"btn\" href=\"index.php?new=".$value["name"]."\">",$value["name"],"</a></td>";
					echo "<td><i>",$dataFinal,"</i></td>";
					echo "<td><i>",round($value["file"]["size"])," KB</i></td>";
					echo "<td><a data-action='excluir' class='btn btn_click_consult red' href='".$url."?unlink&path=".$value["tmp_name"]."' title='Excluir'><i class='fa fa-times'></i></a></td>";
					echo "</tr>";

				endforeach;
			else:
				echo "<tr>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "</tr>";
			endif;
		echo "</ul>";

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

	$Alert->setConfig("warning", "<strong>Aviso</strong>: Ao adicionar um novo arquivo o sistema fechará automaticamente o arquivo atual.</span>");
	echo ($Alert->displayPrint());

	$nonTitle = true;
	include PAGES . 'add.files.php';

endif;