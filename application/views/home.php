<div class="container">
    <?php


    if(isset($_GET["editsuccess"])){

        echo '<div class="alert alert-success" role="alert">
                        <a href="#" class="alert-link">Daten erfolgreich bearbeitet!</a>
                    </div>';
    }
    if(isset($_SESSION['Rep']) && $_SESSION['Rep'] ==  0){
        ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <p>Gib deine Reputation in den Einstellungen an, damit wir für dich relevante IPs anzeigen können!</p>
        </div>
    <?php }
    ?>
    <div id="welcome" style="margin-top: 5%;">
        <div class="row">
            <div class="col-md-3 col-lg-3 hidden-sm hidden-xs">
                <img src="<?php echo base_url('assets/images/icon.png') ?>" width="100%" alt="GHC Logo">
            </div>
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                <h1>Willkommen bei der offiziellen Website der<br/>German Hackerz Community - GHC</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-offset-3 col-xs-12 col-sm-12 col-md-9 col-lg-9">
                <p style="margin-top: 25px;">Wir sind eine Discord Community zum Spiel "Hackers - Hacking simulator" von Okitoo Networks.</p>
                <p>Wenn du noch nicht unserem Discord Server beigetreten bist, kannst du das <a href="https://discord.gg/BW9fuPw">jetzt</a> tun.</p>
                <p style="margin-top: 50px;">Das GHC-Team freut sich auf dich.</p>
            </div>
        </div>
    </div>
</div>