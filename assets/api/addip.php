<?php
require_once __DIR__ . "/../dbconfig.php";


$error = false;
if(isset($_POST["token"]) && $user->verifyToken($_POST["token"])){
	if(isset($_POST["ip"])){
		$editip = $_POST["ip"];
	} else {
		$editip = "";
		$error = "no ip";
	}

	if(isset($_POST["name"])){
		$name = $_POST["name"];
	} else {
		$name = "";
		$error = "no name";
	}

	if(isset($_POST["rep"])){
		$rep = $_POST["rep"];
	} else {
		$rep = "";
	}

	if(isset($_POST["desc"])){
		$description = $_POST["desc"];	
	} else {
		$description = "";
	}

	if(isset($_POST["clan"])){
		$clan = $_POST["clan"];	
	} else {
		$clan = "";
	}

	if(isset($_POST["miners"])){
		$miners = $_POST["miners"];	
	} else {
		$miners = "";
	}

	if(isset($_POST["discorduser"])){
		$discorduser = $_POST["discorduser"];
		if(!$user->findUserwithDiscord($discorduser)){
			$error = "discorduser not found";

		} else {
			$addedbyid = $user->findUserwithDiscord($discorduser);
			if($user->isExpired($addedbyid)){
				$user->refreshAccount($addedbyid);
			}
		}
	} else {
		$discorduser = "";
		$error = "no discord";
	}

	if($error == false){
		if($ip->ipAvailable($editip)){
			$success = $ip->add($editip, $name,  $rep, $description, $miners, $addedbyid, $clan);
			echo $success;
		} else {
			echo "ip already registered";
		}
	} else {
		echo $error;
	}

} else {
	echo "wrong token";
}
