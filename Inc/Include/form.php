<?php if(isset($_SESSION["user_login"])): ?>
	<h1>Adicionar arquivo</h1>
	<form method="POST" enctype="multipart/form-data" class="form-post">
		<p>Ao selecionar o arquivo de planilha desejado, você poderá selecionar qual <span title="Página">"Datasheet"</span> deve ser carregada no sistema.</p>
		<br>
		<div class="form-box">
			<div class="box">
				<label for="file">Selecione o arquivo</label><br>
				<input type="file" name="file" id="file">
			</div>
		</div>
		<div class="buttons">
			<input type="submit" value="Adicionar arquivo" name="Envia">
		</div>
	</form>
<?php else: ?>
	<h1>Entrar</h1>
	<form method="POST" enctype="multipart/form-data" class="form-post">
		<p>Acesse o painel para gerenciar os arranchamentos recentes.</p>
		<br>
		<div class="form-box">
			<div class="box">
				<label for="user">Usuário</label><br>
				<input type="text" name="user" id="user" placeholder="Informe seu usuário">
			</div>

			<div class="box">
				<label for="pass">Senha</label><br>
				<input type="password" name="pass" id="pass" placeholder="Informe sua senha">
			</div>
		</div>
		<div class="buttons">
			<input type="submit" value="Entrar no painel" name="Login">
		</div>
	</form>
<?php endif; ?>