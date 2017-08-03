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
    <div id="welcome" style="display: flex; margin-top: 5%;"></div>
    <script>
        if ($(window).width() < 600) {
            try {
                document.getElementById("welcome").innerHTML =
                    '<div id="welcomeText">' +
                    '<h1>Willkommen bei der offiziellen Website der <br /> German Hackerz Community - GHC</h1>' +
                    '<h4 style="margin-top: 25px;">Wir sind eine Discord Community zum Spiel "Hackers - Hacking simulator" von Okitoo Networks.</h4>' +
                    '<h4>Wenn du noch nicht unserem Discord Server beigetreten bist, kannst du das <a href="https://discord.gg/BW9fuPw">jetzt</a> tun.</h4>' +
                    '<p style="margin-top: 50px;">Das GHC-Team freut sich auf dich.</p>' +
                    '</div>';
            } catch (e) {
                console.log(e);
            }
        } else {
            try {
                document.getElementById("welcome").innerHTML =
                    '<img src="<?php echo base_url('assets/images/icon.png') ?>" width="25%" height="25%" style="margin-right: 20px; margin-top: 21px;" />' +
                    '<div id="welcomeText">' +
                    '<h1>Willkommen bei der offiziellen Website der <br /> German Hackerz Community - GHC</h1>' +
                    '<h4 style="margin-top: 25px;">Wir sind eine Discord Community zum Spiel "Hackers - Hacking simulator" von Okitoo Networks.</h4>' +
                    '<h4>Wenn du noch nicht unserem Discord Server beigetreten bist, kannst du das <a href="https://discord.gg/BW9fuPw">jetzt</a> tun.</h4>' +
                    '<p style="margin-top: 50px;">Das GHC-Team freut sich auf dich.</p>' +
                    '</div>';
            } catch (e) {
                console.log(e);
            }
        }
    </script>

</div>