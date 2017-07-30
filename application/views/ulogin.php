<?php
//require_once 'dbconfig.php';
$accountexpired = "";
$error = "";
/*if($user->is_loggedin()){
    $user->redirect('index.php');
}
if(isset($_GET["login"])){
    $uname = $_POST['name'];
    $password = $_POST['password'];

    if($user->loginDataCorrect($uname, $password)){


        $userid = $user->findUserWithName($uname);        
        if($user->isExpired($userid)){
            $accountexpired = true;


        } else {
            if($user->login($uname, $password)){
            	$user->GetUserRole($userid, $userid);
                $user->GetUserRep($userid);
                $user->GetUserFav($userid);
               ?>
                
                	
                <?php
                 $weiterleitung = "ipdatabase.php?fav=".$_SESSION['fav'];
                $user->redirect($weiterleitung);
            } else {
                $error = true;
            }
        }
    } else {
        $error = true;
    }
}

include 'templates/header.php';
include "templates/navbar.php";*/
?>

<div class="container">
    <div class="col-md-12">
    <?php
    // If the login has an error:
    if($error){
    ?>

    <div class='alert alert-danger alert-dismissible fade in' role='alert'> 
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span></button> 
        <h4>3RR0R!</h4> 
        <p>Nutzername und/oder Passwort inkorrekt!</p> 
    </div>

    <?php
    }
    ?>

    <?php
    // If the login has an error:
    if($accountexpired == true && $user->loginDataCorrect($uname, $password)){
    ?>

    <div class='alert alert-danger alert-dismissible fade in' role='alert'> 
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span></button> 
        <h4>3RR0R!</h4> 
        <p>Dieser Account ist abgelaufen! Du kannst ihn aber auf unserem Discord wieder reaktivieren! Keine Angst, deine Accountdaten sind noch da!<br>Sollte jedoch deine IP in unserer Datenbank sein, könnte diese nun vielleicht angezeigt werden!</p> 
    </div>

    <?php
    }
    ?>

        <form action="?login=1" method="post" class="">
            <div class="form-group">
                <label for="inputUsername">Username</label>
                <input type="text" name="name" id="inputUsername" class="form-control" placeholder="Username">
            </div>

            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" name="password" class="form-control" id="inputPassword">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <label style="margin-top: 10px;"><p>Noch keinen Account? Regestriere dich auf unserem Discordserver mit unserem Bot!</label>
    </div> <!-- Col Md 12 -->
</div> <!-- Container -->
	
</head>
<body>

