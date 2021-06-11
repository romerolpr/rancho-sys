<?php if (!isset($nonTitle) or $nonTitle !== true): ?>
	<h1>Arquivos recentes</h1>
<?php 
endif;

if (!empty($Files)):
	echo "<table>";
	echo "<tr class=\"bar-table\">";
	echo "<td>Alocação</td>";
	echo "<td>Última modificação</td>";
	echo "<td>Tamanho</td>";
	echo "<td>Ação</td>";
	echo "</tr>";
	
		foreach ($Files as $key => $value):

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
			if (isset($_SESSION["objfile"]) && $_SESSION["objfile"]["name"] == $value["name"]):
				echo "<td><a class=\"btn\" href=\"index.php?new=".$value["name"]."\"><strong>",$value["name"],"</strong></a></td>";
			else:
				echo "<td><a class=\"btn\" href=\"index.php?new=".$value["name"]."\">",$value["name"],"</a></td>";
			endif;
			echo "<td><i>",$dataFinal,"</i></td>";
			echo "<td><i>",round($value["file"]["size"])," KB</i></td>";
			echo "<td><a data-action='excluir' class='btn btn_click_consult red' href='".$url."?unlink&path=".$value["tmp_name"]."' title='Excluir'><i class='fa fa-times'></i></a></td>";
			echo "</tr>";

		endforeach;

echo "</table>";

else:
	echo "<table>";
	echo "<tr class=\"bar-table\">";
	echo "<td>Alocação</td>";
	echo "<td>Última modificação</td>";
	echo "<td>Tamanho</td>";
	echo "<td>Ação</td>";
	echo "</tr>";
	echo "</table>";
	echo "<p class=\"txt-center p-label\">Não existem arquivos recententes no sistema.</p>";
endif;