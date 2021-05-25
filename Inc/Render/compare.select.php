<br>
<p>A comparação de dados analisa qual registro possui pendência nos arranchamentos cadastrados.</p>
<br>

<ul class="list">
	<?php 

	if (!isset($_SESSION["objfile_to_compare"])):

		echo '<p>Selecione pelo menos dois arquivos para comparar.</p><br><li>Arquivo 1: <br><strong>',$_SESSION["objfile"]["tmp_name"],'</strong> (',$_SESSION["objfile"]["name"],') <i><span title="Datasheet">',$_SESSION["objfile"]["worksheetName"],'</span></i></li><br>'; ?>

		<form method="POST" enctype="multipart/form-data" class="form-post">
			<div class="form-box">
				<div class="box">
					<!-- <label for="file">Arquivo 2</label><br> -->
					<input type="file" name="file" id="file">
				</div>
			</div>
			<div class="buttons">
				<input type="submit" value="Adicionar arquivo" name="Adicionar">
			</div>
		</form>

	<?php else: 

		foreach ($Compare->getCompareDatasheets() as $key => $files) {
			$btnRemove = ($key > 0) ? '<a href="'.$url.'index.php?compare&removeDatasheet=2" class="btn" title="Remover">/ Remover arquivo</a>' : null;
			$worksheetName = (!is_null($files["worksheetName"]) ? "[" .$files["worksheetName"] . "]" : "<a href='index.php?compare=true&choiceDatasheet=".$key."' title='Selecionar Datasheet'>Selecionar Datasheet</a>");
			echo '<li>Arquivo ',$key,': <br><strong>',$files["tmp_name"],'</strong> (',$files["name"],') <i><span title="Datasheet">',$worksheetName,'</span></i> '.$btnRemove.'</li><br>';
		} ?>

		<br>
		<a href="<?php echo $url?>index.php?compare=true" class="btn btn-bg">Iniciar comparação</a>

	<?php endif; ?>
</ul>