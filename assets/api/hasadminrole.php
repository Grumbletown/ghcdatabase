<?php
require_once __DIR__ . '/../dbconfig.php';

$userid = $_GET['q'];

if($user->hasRole($userid, "Admin")){
    echo "true";
} else {
    echo "false";
}