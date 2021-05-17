<div class="sidebar">
    <div class="top">
        <div class="container">
            
            <?php if(isset($_SESSION["user_login"])): ?>
                <a href="logout.php" title="Sair">
                    <div class="navgation-bar">
                        <span class="btn"><?php echo $_SESSION["user_login"]["username"]?><br>Sair</span>
                    </div>
                </a>
            <?php else: ?>
                <div class="navgation-bar">
                    <span>Brasil</span>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="middle">
        <div class="container">
            <a href="<?php echo $url?>" title="Exército brasileiro">
                <img class="image-bar" src="<?php echo $url?>Dist/image/brasao-exercito.png" alt="Exército brasileiro" title="Exército brasileiro">
                <div class="title-bar">
                    <span>Ministério da Defesa</span>
                    <span>Exército Brasileiro</span>    
                    <span>Braço forte - Mão amiga</span>
                </div>
            </a>
        </div>
    </div>
    <div class="bottom">
        <div class="container"></div>
    </div>
</div>