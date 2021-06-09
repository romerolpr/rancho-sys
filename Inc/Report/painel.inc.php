<table>	
	<!-- <span class="divider"></span> -->
	<!-- <input type="search" name="searchByName" class="searchbar" placeholder="Buscar militar"> -->
	<tr class="bar-table">

		<?php if(isset($get["filter"])): ?>
			<td class="td-button">
				<div><span data-filter="organizacao_militar" class="btn btn_order fright"><i class="fas fa-sort-down"></i><div class="sub-dropdown drop-render"></div></span></div>
				<div>
					<span>Organização Militar</span>
				</div>
			</td>
			<td class="td-button">
				<div><span data-filter="posto_graduacao" class="btn btn_order fright"><i class="fas fa-sort-down"></i><div class="sub-dropdown drop-render"></div></span></div>
				<div>
					<span>Posto/ Graduação</span>
				</div>
			</td>
		<?php else: ?>
			<td>Organização Militar</td>
			<td>Posto/ Graduação</td>
		<?php endif; ?>
		<td>Nome</td>
		<td>Arranchamento da semana</td>
		<td>Relatório</td>

	</tr>

	<?php 

	$trmike = null;
	$listByHash = array();
			
	foreach ($Resp as $key => $value):

		$nome = ucfirst(strtolower(utf8_decode($value["nome"])));
		$posto_graduacao = utf8_decode($value["posto_graduacao"]);
		$organizacao_militar = utf8_decode($value["organizacao_militar"]);
	
		$trmike .= "<tr data-hash=\"".$value["hash"]."\">";

		$trmike .= "<td data-content=\"organizacao_militar\">".$organizacao_militar."</td>";
		$trmike .= "<td data-content=\"posto_graduacao\">".$posto_graduacao."</td>";
		$trmike .= "<td>".$nome."</td>";
		foreach ($listComplete as $keylist => $list) array_push($listByHash, $list[0]);
		$trmike .= "<td>".(in_array($value["hash"], $listByHash) ? "Completo" : "Pendente" )."</td>";
		
		$trmike .= "<td><a href=\"report.php\" class=\"btn btn_open_window\" data-hash=\"".$value["hash"]."\">Abrir relatório</a></td>";

	endforeach;

	$trmike .= "</tr>";

	echo $trmike;

	// var_dump($listComplete[2]);



	?>

</table>