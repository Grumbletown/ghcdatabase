<?php
/**
 * Created by PhpStorm.
 * User: zeroc
 * Date: 04.07.2017
 * Time: 05:19
 */




class Table_Ajax extends CI_Model
{

    /**
     * @return string
     */
    var $column_order = array(
        "`T`.`IP`",
        "`T`.`Name`",
        "`T`.`Reputation`",
        "`T`.`Description`",
        "`T`.`Miners`",
        "`T`.`Clan`",
        "`T`.`Last_Updated`"
    );
    var $column_order_user = array(
        "ID",
        "Username",
        "Role",
        "Reputation",
        "Last_Login",



    );
    var $column_search = array('`T`.`IP`','`T`.`Name`','`T`.`Description`','`T`.`Clan`'); //set column field database for datatable searchable
    var $column_search_user = array('Username', 'Role'); //set column field database for datatable searchable
    var $order = array('id' => 'asc'); // default order
    public function _get_ips($page)
    {




            $i = 0;
            if($page == 'user'){
            $searchwhat = $this->column_search_user;

            }
            else
            {
                $searchwhat = $this->column_search;
            }
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
                $this->db->order_by($this->column_order_user[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);

            }
            else
            {
                $this->db->order_by($this->column_order[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
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


    public function get_ips($sql,$page){
//        $this->db->cache_on();
        $this->_get_ips($page);
        if($_GET['length'] != -1)
            $this->db->limit($_GET['length'], $_GET['start']);

        switch ($page){
            case 'ip':
                        $query = $this->db->get($sql);
                        break;
            case 'user':
                        $query = $this->db->get($sql);
                        break;

            case 'fav':
                        $query = $this->db->where('UserIPFav', 1)->get($sql);
                        break;
            case 'repo':
                $query = $this->db->where('UserIPRepo', 1)->get($sql);
                break;
        }
        return $query;
    }
    public function count_filtered($sql, $page)
    {

        
        $this->_get_ips($page);
        switch ($page){
            case 'ip':
                $query = $this->db->get($sql);
                break;
            case 'user':
                $query = $this->db->get($sql);
                break;

            case 'fav':
                $query = $this->db->where('UserIPFav', 1)->get($sql);
                break;
            case 'repo':
                $query = $this->db->where('UserIPRepo', 1)->get($sql);
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
        //$this->db->join('APIToken', 'APIToken.UserID = Users.ID');
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

?>