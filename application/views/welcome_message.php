<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
	<?php 
	
	
	$this->load->view('templates/navbar.php');
	
	?>
 <div class="container">
        <div id="welcome" style="display: flex; margin-top: 5%;"></div>
        <script>
            if ($(window).width() < 600) {
        try {
            document.getElementById("welcome").innerHTML = 
            '<div id="welcomeText">' +
                '<h1>Willkommen bei der offiziellen Website der <br /> German Hacker Community - GHC</h1>' +
                '<h4 style="margin-top: 25px;">Wir sind eine Discord Community zum Spiel "Hackers - Hacking simulator" von Okitoo Networks.</h4>' +
                '<h4>Wenn du noch nicht unserem Discord Server beigetreten bist, kannst du das <a href="https://discord.gg/3bQ5hE5">jetzt</a> tun.</h4>' +
                '<p style="margin-top: 50px;">Das GHC-Team freut sich auf dich.</p>' +
            '</div>';
        } catch (e) {
            console.log(e);
        }
    } else {
        try {
            document.getElementById("welcome").innerHTML = 
            '<img src="images/icon.png" width="25%" height="25%" style="margin-right: 20px; margin-top: 21px;" />' +
            '<div id="welcomeText">' +
            '<h1>Willkommen bei der offiziellen Website der <br /> German Hacker Community - GHC</h1>' +
            '<h4 style="margin-top: 25px;">Wir sind eine Discord Community zum Spiel "Hackers - Hacking simulator" von Okitoo Networks.</h4>' +
            '<h4>Wenn du noch nicht unserem Discord Server beigetreten bist, kannst du das <a href="https://discord.gg/3bQ5hE5">jetzt</a> tun.</h4>' +
            '<p style="margin-top: 50px;">Das GHC-Team freut sich auf dich.</p>' +
            '</div>';
        } catch (e) {
            console.log(e);
        }
    }
        </script>
<?php echo base_url();?>
    </div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>
