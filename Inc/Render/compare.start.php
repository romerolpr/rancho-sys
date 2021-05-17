<?php

$Objects = $Compare->getCompareDatasheets();
// var_dump($Objects);

if (is_null($_SESSION["objfile_to_compare"][0]["worksheetName"]) or is_null($_SESSION["objfile_to_compare"][1]["worksheetName"])):

	if (is_null($_SESSION["objfile_to_compare"][0]["worksheetName"])):
		echo '<span class="alert warning"><strong>Atenção</strong>: Você precisa selecionar o Datasheet do arquivo 1.</span>';
		echo "<a class='btn' href='index.php?compare' title='Voltar'>Voltar</a> ";
		echo "<a class='btn btn-bg' href='index.php?compare=true&choiceDatasheet=0' title='Selecionar Datasheet 1'>Selecionar Datasheet 1</a>";
	elseif (is_null($_SESSION["objfile_to_compare"][1]["worksheetName"])):
		echo '<span class="alert warning"><strong>Atenção</strong>: Você precisa selecionar o Datasheet do arquivo 2.</span>';
		echo "<a class='btn' href='index.php?compare' title='Voltar'>Voltar</a> ";
		echo "<a class='btn btn-bg' href='index.php?compare=true&choiceDatasheet=1' title='Selecionar Datasheet 2'>Selecionar Datasheet 2</a>";
	else:
		echo '<span class="alert warning"><strong>Atenção</strong>: Você precisa selecionar o Datasheet de ambos os arquivos.</span>';
	endif;

else:

	echo "<br><p>Analisando dados...</p>";

	$Compare->start_compare();

endif;

?>