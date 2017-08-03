<?php
require_once __DIR__ . '/../dbconfig.php';

$ipstring = $_GET['q'];

if($ip->nameCount($ipstring) >= 1){
    echo "true";
} else {
    echo "false";
}
?>