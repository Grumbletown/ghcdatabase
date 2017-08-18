<?php
/**
 * Created by PhpStorm.
 * User: zeroc
 * Date: 04.07.2017
 * Time: 05:19
 */




class Table_ajax extends CI_Model
{

    /**
     * @return string
     */

    public $ipsql = 0;
    public $adminsql = 0;
    public $favsql = 0;
    public $reposql = 0;



    var $column_order = array(
        'ip' =>array(
        "`T`.`IP`",
        "`T`.`Name`",
        "`T`.`Reputation`",
        "`T`.`Description`",
        "`T`.`Miners`",
        "`T`.`Clan`",
        "`T`.`Last_Updated`"
    ),
        'user' => array(
            "ID",
            "Username",
            "Role",
            "Reputation",
            "Last_Login",



        ),
        'attempts' => array(
            "UIP",
        ),
    );

    
    var $column_search = array(
        'ip' => array('`T`.`IP`','`T`.`Name`','`T`.`Description`','`T`.`Clan`'),
        'user' => array('Username', 'Role', 'DiscorName'),
        'attempts' => array('UIP'),
    );
     //set column field database for datatable searchable
    var $order = array('id' => 'asc'); // default order


    function __construct()
    {
        parent::__construct();
        $this->ipsql = "(SELECT STRAIGHT_JOIN `HackersIP`.`ID`, `IP`, `HackersIP`.`Name`, `HackersIP`.`Reputation`, `Last_Updated`, `Description`, `Miners`, `Clan`, COALESCE(`CountsIPRepo`.`CountIPRepo`, 0) AS `CountIPRepo`, COALESCE(`UsersIPRepo`.`UserIPRepo`, 0) AS `UserIPRepo`, COALESCE(`UsersIPFav`.`UserIPFav`, 0) AS `UserIPFav`FROM `HackersIP` LEFT JOIN `Users` ON `HackersIP`.`Name` = `Users`.`Username` LEFT JOIN (SELECT COUNT(1) AS `CountIPRepo`, `IPUserReport`.`IPID` FROM `IPUserReport` GROUP BY `IPUserReport`.`IPID`) AS `CountsIPRepo` ON `CountsIPRepo`.`IPID` = `HackersIP`.`ID` LEFT JOIN (SELECT COUNT(1) AS `UserIPRepo`, `IPUserReport`.`IPID` FROM `IPUserReport` WHERE `IPUserReport`.`UserID` = ".$_SESSION['uid']." GROUP BY `IPUserReport`.`IPID`) AS `UsersIPRepo` ON `UsersIPRepo`.`IPID` = `HackersIP`.`ID` LEFT JOIN (SELECT COUNT(1) AS `UserIPFav`, `IPUserFav`.`IPID` FROM `IPUserFav` WHERE `IPUserFav`.`UserID` = ".$_SESSION['uid']." GROUP BY `IPUserFav`.`IPID`) AS `UsersIPFav` ON `UsersIPFav`.`IPID` = `HackersIP`.`ID` WHERE `Users`.`Last_Login` < DATE_SUB( now(), INTERVAL 30 DAY) OR `Users`.`Last_Login` IS NULL) AS T";
        $this->adminsql = "Users";
        $this->favsql = "(SELECT STRAIGHT_JOIN `HackersIP`.`ID`, `IP`, `HackersIP`.`Name`, `HackersIP`.`Reputation`, `Last_Updated`, `Description`, `Miners`, `Clan`, `Adder`.`Username`, COALESCE(`CountsName`.`CountName`,0) AS `CountName`, COALESCE(`CountsIPRepo`.`CountIPRepo`, 0) AS `CountIPRepo`, COALESCE(`UsersIPRepo`.`UserIPRepo`, 0) AS `UserIPRepo`, COALESCE(`UsersIPFav`.`UserIPFav`, 0) AS `UserIPFav` FROM `HackersIP` 
LEFT JOIN `Users` ON `HackersIP`.`Name` = `Users`.`Username` 
JOIN `Users` AS `Adder` ON `HackersIP`.`Added_By` = `Adder`.`ID` 
LEFT JOIN (SELECT COUNT(1) AS `CountName`, `HackersIP`.`Name` FROM `HackersIP` GROUP BY `HackersIP`.`Name`) AS `CountsName` ON `CountsName`.`Name` = `HackersIP`.`Name`
LEFT JOIN (SELECT COUNT(1) AS `CountIPRepo`, `IPUserReport`.`IPID` FROM `IPUserReport` GROUP BY `IPUserReport`.`IPID`) AS `CountsIPRepo` ON `CountsIPRepo`.`IPID` = `HackersIP`.`ID`
LEFT JOIN (SELECT COUNT(1) AS `UserIPRepo`, `IPUserReport`.`IPID` FROM `IPUserReport` WHERE `IPUserReport`.`UserID` = ".$_SESSION['uid']." GROUP BY `IPUserReport`.`IPID`) AS `UsersIPRepo` ON `UsersIPRepo`.`IPID` = `HackersIP`.`ID`
LEFT JOIN (SELECT COUNT(1) AS `UserIPFav`, `IPUserFav`.`IPID` FROM `IPUserFav` WHERE `IPUserFav`.`UserID` =".$_SESSION['uid']." GROUP BY `IPUserFav`.`IPID`) AS `UsersIPFav` ON `UsersIPFav`.`IPID` = `HackersIP`.`ID`
WHERE `Users`.`Last_Login` < DATE_SUB( now(), INTERVAL 30 DAY) OR `Users`.`Last_Login` IS NULL) AS T";
        $this->reposql = "(SELECT STRAIGHT_JOIN `HackersIP`.`ID`, `IP`, `HackersIP`.`Name`, `HackersIP`.`Reputation`, `Last_Updated`, `Description`, `Miners`, `Clan`, `Adder`.`Username`, COALESCE(`CountsName`.`CountName`,0) AS `CountName`, COALESCE(`CountsIPRepo`.`CountIPRepo`, 0) AS `CountIPRepo`, COALESCE(`UsersIPRepo`.`UserIPRepo`, 0) AS `UserIPRepo`, COALESCE(`UsersIPFav`.`UserIPFav`, 0) AS `UserIPFav` FROM `HackersIP` 
LEFT JOIN `Users` ON `HackersIP`.`Name` = `Users`.`Username` 
JOIN `Users` AS `Adder` ON `HackersIP`.`Added_By` = `Adder`.`ID` 
LEFT JOIN (SELECT COUNT(1) AS `CountName`, `HackersIP`.`Name` FROM `HackersIP` GROUP BY `HackersIP`.`Name`) AS `CountsName` ON `CountsName`.`Name` = `HackersIP`.`Name`
LEFT JOIN (SELECT COUNT(1) AS `CountIPRepo`, `IPUserReport`.`IPID` FROM `IPUserReport` GROUP BY `IPUserReport`.`IPID`) AS `CountsIPRepo` ON `CountsIPRepo`.`IPID` = `HackersIP`.`ID`
LEFT JOIN (SELECT COUNT(1) AS `UserIPRepo`, `IPUserReport`.`IPID` FROM `IPUserReport` WHERE `IPUserReport`.`UserID` = ".$_SESSION['uid']." GROUP BY `IPUserReport`.`IPID`) AS `UsersIPRepo` ON `UsersIPRepo`.`IPID` = `HackersIP`.`ID`
LEFT JOIN (SELECT COUNT(1) AS `UserIPFav`, `IPUserFav`.`IPID` FROM `IPUserFav` WHERE `IPUserFav`.`UserID` =".$_SESSION['uid']." GROUP BY `IPUserFav`.`IPID`) AS `UsersIPFav` ON `UsersIPFav`.`IPID` = `HackersIP`.`ID`
WHERE `Users`.`Last_Login` < DATE_SUB( now(), INTERVAL 30 DAY) OR `Users`.`Last_Login` IS NULL) AS T";
    }



    public function _get_ips($page)
    {
        $i = 0;
        if($page == 'fav' || $page == 'repo'){
           $page = 'ip';
        }

        $searchwhat = $this->column_search[$page];
        foreach ($searchwhat as $item) // loop column
        {
            if($_GET['search']['value']){
                if($i === 0){
                    //$sql .= " WHERE `T`.`$item` LIKE = '" . $_GET['search']['value']."'";
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_GET['search']['value']);
                }
                else{
                    //$sql .= " OR `T`.`$item` LIKE = '" . $_GET['search']['value']."'";
                    $this->db->or_like($item, $_GET['search']['value']);
                }
                if(count($searchwhat) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        if(isset($_GET['order'])) // here order processing
        {
            if($page == 'user'){
                $this->db->order_by($this->column_order_user[$page][$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
            }
            else
            {
                $this->db->order_by($this->column_order[$page][$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
            }
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function debug_ips($sql){

        $query = $this->db->get($sql);

        return $query;
    }


    public function get_ips($page){
//        $this->db->cache_on();
        $this->_get_ips($page);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);
        switch ($page){
            case 'ip':
                $query = $this->db->get($this->ipsql);
                break;
            case 'user':
                $query = $this->db->get($this->adminsql);
                break;
            case 'fav':
                $query = $this->db->where('UserIPFav', 1)->get($this->favsql);
                break;
            case 'repo':
                $query = $this->db->where('UserIPRepo', 1)->get($this->reposql);
                break;
        }
        return $query;
    }
    public function count_filtered($page)
    {

        $this->_get_ips($page);
        switch ($page){
            case 'ip':
                $query = $this->db->get($this->ipsql);
                break;
            case 'user':
                $query = $this->db->get($this->adminsql);
                break;
            case 'fav':
                $query = $this->db->where('UserIPFav', 1)->get($this->favsql);
                break;
            case 'repo':
                $query = $this->db->where('UserIPRepo', 1)->get($this->reposql);
                break;
        }
        return $query->num_rows();
    }
    public function get_total_ips($table)
    {
        $query = $this->db->select("COUNT(*) as num")->get($table);
        $result = $query->row();
        if(isset($result)) return $result->num;
        return 0;
    }
    public function delete_by_id($id, $table)
    {
        $this->db->delete($table, array('ID' => $id));

    }
    public function get_settings($id, $table)
    {
        $this->db->from($table);
        $this->db->where('ID',$id);
        $this->db->join('APIToken', 'APIToken.UserID = Users.ID', 'Left');
        $query = $this->db->get();

        return $query->row();
    }

    public function get_by_id($id, $table)
    {
        $this->db->from($table);
        $this->db->where('ID',$id);
        $query = $this->db->get();

        return $query->row();
    }

    public function save($data, $table)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data, $table)
    {
        $this->db->update($table, $data, $where);
        return $this->db->affected_rows();
    }
    
    public function get_ip($ip, $table){
        $this->db->from($table);
        $this->db->where('IP', $ip);
        return $this->db->count_all_results();
    }

    public function get_name($name, $table){
        $this->db->from($table);
        $this->db->where('Name', $name);
        return $this->db->count_all_results();
    }

    public function favrepo($ipid, $switch, $table){
        if($switch == 1){
            $data = array(
                'IPID' => $ipid,
                'UserID' => $_SESSION['uid'],

            );

            $this->db->insert($table, $data);

        }
        else{
            $this->db->where('IPID', $ipid);
            $this->db->where('UserID', $_SESSION['uid']);
            $this->db->delete($table);
        }
    }
}

