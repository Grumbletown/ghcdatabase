<?php
require_once __DIR__ . "/../dbconfig.php";

$stmt = $DB_con->prepare("SELECT Reputation FROM `Users` WHERE 1");
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	if($row["Reputation"] == NULL){
		echo "leer";
	} else {
		echo $row["Reputation"];
	}
}
echo "ende";
