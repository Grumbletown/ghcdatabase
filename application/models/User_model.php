<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }
	
	function login($uname, $pwd)
	{

        $this->db->where('Username', $uname);
		
        $query = $this->db->get('Users');
		$row = $query->row();

		if($row){
        if(password_verify($pwd, $row->Password))
		{
		   $query->free_result();
           //$this->db->select('Username', 'ID', 'Role', 'ExpireDate');
           $this->db->where('Username', $uname);
           $query = $this->db->get('Users');
		   return $query->result();
		   
		}
		else
		{
			return FALSE;
		}
		}
		else
		{
			return FALSE;
		}
	}

    public function delete_ip_attmepts($ip)
    {

        
        $this->db->set('Attempts', 0, FALSE);
        $this->db->where('UIP', $ip);
        $this->db->update('Loginattempt');

    }

	function ip_check($ip)
	{
		$this->db->where('UIP', $ip);
		$query = $this->db->get('Loginattempt');
		return $query->result();
	}
    function get_role($name)
    {
        $this->db->where('Username', $name);
        $query = $this->db->get('Users');
        return $query->result();
    }
	function ip_update($ip)
	{
	    $this->db->set('Attempts', 'Attempts+1', FALSE);
        $this->db->where('UIP', $ip);
        $this->db->update('Loginattempt');
	}
	
	function ip_add($data)
	{
		return $this->db->insert('Loginattempt', $data);
	}
	
	// get user
	function get_user_by_id($id)
	{
		$this->db->where('ID', $id);
        $query = $this->db->get('Users');
		return $query->result();
	}
	
	// insert
	function insert_user($data)
    {
		return $this->db->insert('Users', $data);
	}

	function get_token($id){

        $this->db->where('UserID', $id);
        $query = $this->db->get('APIToken');
        return $query->result();

    }

    public function update($where, $data, $table)
    {
        $this->db->update($table, $data, $where);
        return $this->db->affected_rows();
    }

    function get_key($key){

        $this->db->where('Key', $key);
        $query = $this->db->get('PWReset');
        return $query->result();

    }

	function insert_key($id, $method, $key)
    {
        switch ($method)
        {
            case 'update':
                $this->db->set('Key', $key, FALSE);
                $this->db->where('UID', $id);
                $this->db->update('PWReset');
                break;
            case 'neu':
                $data = array(
                    'UID' => $id,
                    'Key' => $key
                );
                return $this->db->insert('PWReset', $data);
                break;
        }
    }
}
