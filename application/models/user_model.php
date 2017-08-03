<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user_model extends CI_Model
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

        echo '<script type="text/javascript">console.log("reset attempts");</script>';
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
}