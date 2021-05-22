<?php if(!isset($_SESSION["user_login"])): ?>

	<li><a href="<?php echo $url?>index.php" title="Acessar painel">Acessar painel</a></li>
	<li><a href="<?php echo $url?>index.php?exb=lastsheet" title="Últimos arranchamentos">Últimos arranchamentos</a></li>
	<li><a href="<?php echo $url?>index.php?exb=consult" title="Consultar arranchamento">Consultar arranchamento</a></li>

<?php else: ?>

	<li><a href="<?php echo $url?>index.php" title="Ver planilha">Ver planilha</a></li>
	<li><a href="<?php echo $url?>index.php?compare" title="Realizar análise de dados">Realizar análise de dados</a></li>
	<li><a href="<?php echo $url?>index.php?exit=session_obj" title="Fechar arquivo">Fechar arquivo</a></li>
	<!-- <span class="divider"></span> -->
	<li><a href="<?php echo $url?>index.php?exit=session_login" title="Sair">Sair</a></li>

<?php endif; ?>