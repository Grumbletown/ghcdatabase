<nav class="navbar navbar-default navbar-static-top navbar-inverse">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand"  href="index.php" id="navbarBrandText"></a>
      <script>
        if ($(window).width() < 400) {
        try {
            document.getElementById("navbarBrandText").innerHTML = '<img src="images/icon.svg" width="30" height="30" style="margin-right: 10px; display: inline-block!important; vertical-align: top!important" />GHC';
        } catch (e) {
            console.log("Nicht wichtiger Fehler!: " + e);
        }
    } else {
        try {
            document.getElementById("navbarBrandText").innerHTML = '<img src="images/icon.svg" width="30" height="30" style="margin-right: 10px; display: inline-block!important; vertical-align: top!important" />German Hacker Community';
        } catch (e) {
            console.log("Nicht wichtiger Fehler!: " + e);
        }
    }
      </script>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <!--<li><a href="index.php">Home</a></li>-->
        <li id='ipdatabase.php?fav=0' class=''><a href="ipdatabase.php?fav=0"><i class='fa fa-laptop fa-lg' aria-hidden='true'></i>&nbsp; IPs</a></li>
        <li id='ipdatabase.php?fav=1' class=''><a href="ipdatabase.php?fav=1"><i class='fa fa-star fa-lg' aria-hidden='true'></i>&nbsp; Favoriten</a></li>
      
      

      
    </div>
  </div><!-- /.container-fluid -->
</nav>