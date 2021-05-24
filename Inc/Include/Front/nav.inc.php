<?php if(!isset($_SESSION["user_login"])): ?>

	<li><a href="<?php echo $url?>index.php" title="Acessar painel">Acessar painel</a></li>
	<li><a href="<?php echo $url?>index.php?exb=lastsheet" title="Últimos arranchamentos">Últimos arranchamentos</a></li>
	<li><a href="<?php echo $url?>index.php?exb=consult" title="Consultar arranchamento">Consultar arranchamento</a></li>

<?php else: ?>
	<?php if(isset($_SESSION["objfile"])):?>
		<li><a href="<?php echo $url?>index.php" title="Ver arquivo atual">Ver arquivo atual</a></li>
	<?php endif;?>
	<?php if(isset($_SESSION["objfile_compare"])):?>
		<li><a href="<?php echo $url?>index.php?exb=compare&item1=<?php echo $_SESSION["objfile_compare"][0]["name"]?>&item2=<?php echo $_SESSION["objfile_compare"][1]["name"]?>" title="Realizar análise de dados">Realizar análise de dados</a></li>
	<?php elseif(isset($_SESSION["objfile"])):?>
		<li><a href="<?php echo $url?>index.php?exb=compare&item1=<?php echo $_SESSION["objfile"]["name"]?>" title="Adicionar arquivo para comparação">Adicionar arquivo para comparação</a></li>
	<?php endif;?>
	<li><a href="<?php echo $url?>index.php?exb=add_new" title="Adicionar arquivo">Adicionar arquivo</a></li>
	<?php if(isset($_SESSION["objfile"])):?>
		<li><a href="<?php echo $url?>index.php?exit=session_obj" title="Fechar arquivo">Fechar arquivo</a></li>
	<?php endif; ?>
	<!-- <span class="divider"></span> -->
	<li><a href="<?php echo $url?>index.php?exit=session_login" title="Sair">Sair</a></li>

<?php endif; ?>