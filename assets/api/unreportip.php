<?php
require_once __DIR__ . "/../dbconfig.php";

if(!$user->is_loggedin()){
	$user->redirect("../ipdatabase.php");
}

$id = $_GET["id"];
if(isset($_GET["uid"])){
	$uid = $_GET["uid"];
	$nichtajax = true;
}else{
	$uid = $_SESSION["User"];
}
if($user->hasReported($uid, $id)){
	
	if($ip->unreport($id, $uid)){
		
		echo "true";
		$success = true;
	} else {
		echo "false";
	}
} else {
	echo "false";
}
if($nichtajax && $success){
	$user->redirect("../reportedips.php");
	}
	
