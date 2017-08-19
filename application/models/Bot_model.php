<?php
class Bot_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();

    }

    public function get_ip($ip){
        $this->db->from('HackersIP');
        $this->db->where('IP', $ip);
        return $this->db->count_all_results();
    }

    function get_token($token){
        $this->db->from('APIToken');
        $this->db->where('Token', $token);
        return $this->db->count_all_results();

    }

    function get_nickname($nick){
        $this->db->from('HackersIP');
        $this->db->where('Name', $nick);
        return $this->db->count_all_results();

    }

    public function save_ip($data)
    {
        $this->db->insert('HackersIP', $data);
        return $this->db->insert_id();
    }

    function get_user_by_discord($discord)
    {
        $this->db->where('DiscordName', $discord);
        $query = $this->db->get('Users');
        return $query->result();
    }

    function add_ip($data)
    {
        return $this->db->insert('HackersIP', $data);
    }
    
    function refresh_user($id, $date)
    {

            $this->db->set('ExpireDate', $date, FALSE);
            $this->db->where('ID', $id);
            $this->db->update('Users');

        
        
    }

}