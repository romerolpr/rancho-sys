<div class="sidebar">
    <div class="top">
        <div class="container">
            <?php if(isset($_SESSION["user_login"])): ?>
                <a href="?exit=session_login" title="Sair">
                    <div class="navgation-bar">
                        <span class="btn"><?php echo $_SESSION["user_login"]["nome"]?><br>Sair</span>
                    </div>
                </a>
            <?php else: ?>
                    <div class="navgation-bar">
                        
                        <span>Brasil</span>

                    </div>

            <?php endif; ?>
        </div>
    </div>
</div>