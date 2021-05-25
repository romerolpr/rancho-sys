<h1><?php echo $_SESSION["objfile"]["name"]; ?></h1>

<?php

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
	foreach (glob(TRANSFER . "*.*") as $arquivo) array_push($Files, array($arquivo, filesize($arquivo), filemtime($arquivo)));

	echo "<table>";
	echo "<tr class=\"bar-table\">";
	echo "<td>Alocação</td>";
	echo "<td>Última modificação</td>";
	echo "<td>Tamanho</td>";
	echo "<td>Ação</td>";
	echo "</tr>";
		if (!empty($Files)):
			foreach ($Files as $key => $value):

				$dateFile = date("d-m-Y H:i:s", $value[2]);

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
				echo "<td>",$value[0],"</td>";
				echo "<td><i>",$dataFinal,"</i></td>";
				echo "<td><i>",round($value[1])," KB</i></td>";
				echo "<td><a data-action='excluir' class='btn btn_click_consult red' href='".$url."?action=Remove&path=".$value[0]."' title='Excluir'><i class='fa fa-times'></i></a></td>";
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

	/* Including the render file */
	include 'Inc/Render/load.php';

endif;