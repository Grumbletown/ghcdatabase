<?php
require_once __DIR__ . '/../dbconfig.php';

$ipstring = $_GET['q'];

if($ip->ipAvailable($ipstring)){
    echo "true";
} else {
    echo "false";
}