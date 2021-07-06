<?php 

if ($_SESSION["user_login"]["nvl_access"] == 1):

	if(!isset($nonTitle)): ?>
		<h1>Adicionar arquivo</h1>

<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="form-post">
	<p>Ao selecionar o arquivo de planilha desejado, você poderá selecionar qual <span title="Página">"Datasheet"</span> deve ser carregada no sistema.</p><br>

	<div class="form-box">
		<div class="box">
			<label for="file">Selecione o arquivo</label><br>
			<input type="file" name="file" id="file" required>
		</div>
		<div class="box">
			<label for="nameFile">Alterar nome do arquvo (Opcional)</label><br>
			<input type="checkbox" name="checkboxname" id="nameFile" style="width: auto">
		</div>
		<div class="box none" id="change_name_file" style="box-sizing: border-box; padding-left: 1em; border-left: 1px solid #c1c1c1; margin-bottom: 1em;">
			<label for="nameFile">Novo nome para o arquivo</label><br>
			<input type="text" name="name" id="nameFile" placeholder="Defina um novo nome para o arquivo">
		</div>
	</div>
	<div class="buttons">
		<input type="submit" value="Adicionar arquivo" name="Envia">
	</div>
</form>

<?php 

else:

?>

<h1>Sem arquivos disponíveis</h1>

<?php 

$Alert->setConfig("warning", "<strong>Aviso</strong>: Não existem arquivos disponíveis no sistema. Solicite acesso para o administrador.");
echo ($Alert->displayPrint());

endif;

?>

<script>
	

	$("#nameFile").prop("disabled", true);
	$(document).ready(function(){
		$("#nameFile").on("change", function(){
			console.log($(this));
			if ($(this)[0].checked == true) {
				$("#change_name_file").removeClass("none");
			} else {
				$("#change_name_file").addClass("none");
			}
		}).prop("disabled", false);
	});

</script>