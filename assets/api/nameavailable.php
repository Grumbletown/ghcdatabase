<?php

require_once __DIR__ . '/../dbconfig.php';

$name = $_GET['q'];

if($user->nameAvailable($name)){
    echo "true";
} else {
    echo "false";
}