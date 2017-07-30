<?php
require_once __DIR__ . "/../dbconfig.php";

// THIS SITE REQUIRES: TOKEN, DiscordName

$error = false;
if(!isset($_POST["token"]) || !$user->verifyToken($_POST["token"])){
	$error = "wrong token";
}

if(isset($_POST["discorduser"])){
	$discord = $_POST["discorduser"];
} else {
	$discord = "";
	$error = "no discord";
}

if($error == false){
	if($user->findUserWithDiscord($discord)){
		$user->refreshAccount($user->findUserWithDiscord($discord));
		echo "success";
	} else {
		echo "user not found";
	}
} else {
	$error;
}