<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class table_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }
/*

SELECT * FROM (SELECT `HackersIP`.`ID`, `IP`, `HackersIP`.`Name`, `HackersIP`.`Reputation`, `Last_Updated`, `Description`, `Miners`, `Clan`, `Adder`.`Username`, COALESCE(`CountsIPRepo`.`CountIPRepo`, 0) AS `CountIPRepo`, COALESCE(`UsersIPRepo`.`UserIPRepo`, 0) AS `UserIPRepo`, COALESCE(`UsersIPFav`.`UserIPFav`, 0) AS `UserIPFav` FROM `HackersIP` 
LEFT JOIN `Users` ON `HackersIP`.`Name` = `Users`.`Username` 
JOIN `Users` AS `Adder` ON `HackersIP`.`Added_By` = `Adder`.`ID` 

LEFT JOIN (SELECT COUNT(1) AS `CountIPRepo`, `IPUserReport`.`IPID` FROM `IPUserReport` GROUP BY `IPUserReport`.`IPID`) AS `CountsIPRepo` ON `CountsIPRepo`.`IPID` = `HackersIP`.`ID`
LEFT JOIN (SELECT COUNT(1) AS `UserIPRepo`, `IPUserReport`.`IPID` FROM `IPUserReport` WHERE `IPUserReport`.`UserID` = :uid GROUP BY `IPUserReport`.`IPID`) AS `UsersIPRepo` ON `UsersIPRepo`.`IPID` = `HackersIP`.`ID`
LEFT JOIN (SELECT COUNT(1) AS `UserIPFav`, `IPUserFav`.`IPID` FROM `IPUserFav` WHERE `IPUserFav`.`UserID` = :uid GROUP BY `IPUserFav`.`IPID`) AS `UsersIPFav` ON `UsersIPFav`.`IPID` = `HackersIP`.`ID`
WHERE `Users`.`Last_Login` < DATE_SUB( now(), INTERVAL 30 DAY) OR `Users`.`Last_Login` IS NULL) AS T ORDER BY `T`.`Name` ASC LIMIT :a, :limit







*/
   function ip_table($uid, $exclude)
   {
   	$this->db->cache_on();
   	$sql = "SELECT * FROM (SELECT STRAIGHT_JOIN `HackersIP`.`ID`, `IP`, `HackersIP`.`Name`, `HackersIP`.`Reputation`, `Last_Updated`, `Description`, `Miners`, `Clan`, COALESCE(`CountsIPRepo`.`CountIPRepo`, 0) AS `CountIPRepo`, COALESCE(`UsersIPRepo`.`UserIPRepo`, 0) AS `UserIPRepo`, COALESCE(`UsersIPFav`.`UserIPFav`, 0) AS `UserIPFav`FROM `HackersIP` 
LEFT JOIN `Users` ON `HackersIP`.`Name` = `Users`.`Username` 

LEFT JOIN (SELECT COUNT(1) AS `CountIPRepo`, `IPUserReport`.`IPID` FROM `IPUserReport` GROUP BY `IPUserReport`.`IPID`) AS `CountsIPRepo` ON `CountsIPRepo`.`IPID` = `HackersIP`.`ID`
LEFT JOIN (SELECT COUNT(1) AS `UserIPRepo`, `IPUserReport`.`IPID` FROM `IPUserReport` WHERE `IPUserReport`.`UserID` = ".$uid." GROUP BY `IPUserReport`.`IPID`) AS `UsersIPRepo` ON `UsersIPRepo`.`IPID` = `HackersIP`.`ID`
LEFT JOIN (SELECT COUNT(1) AS `UserIPFav`, `IPUserFav`.`IPID` FROM `IPUserFav` WHERE `IPUserFav`.`UserID` = ".$uid." GROUP BY `IPUserFav`.`IPID`) AS `UsersIPFav` ON `UsersIPFav`.`IPID` = `HackersIP`.`ID`
WHERE `Users`.`Last_Login` < DATE_SUB( now(), INTERVAL 30 DAY) OR `Users`.`Last_Login` IS NULL) AS T ORDER BY `T`.`Name` ASC LIMIT 1,1";

   $query = $this->db->query($sql);
   $row = $query->result();
   return json_encode($row);
   }
   
   function ip_exclude()
   {
   	$sql = "SELECT `h`.`ID` FROM `HackersIP` AS `h`, `Users` AS `u` WHERE `h`.`Name` = `u`.`Username`";
          $query = $this->db->query($sql);
   $row = $query->result();
   return $row;
   }








}