<?php
require_once __DIR__ . "/../dbconfig.php";
echo "<title>Stats 1.0</title>";
echo "Datum Updated<br>";
$lastUpdatedInDays = 0;
for($i = 0; $i <= 9; $i++){

	$date = date('Y-m-d', strtotime("-{$i} days"));
	$ipCount = $ip->returnDateCount($date);
	$lastUpdatedInDays = $lastUpdatedInDays + intval($ipCount);
	echo "<{$date}>";
	echo " $ipCount<br>";
}
$tableCount = intval($ip->getTableCount());
$prior = $tableCount - $lastUpdatedInDays;
echo "Vorher: $prior<br>";
echo "Gesamt: $tableCount";