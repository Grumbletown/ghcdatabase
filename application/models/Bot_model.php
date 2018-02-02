<?php
class Bot_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();

    }

    public function get_ip($column, $ip){
        $this->db->from('HackersIP');
        $this->db->where($column, $ip);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_datecount($date){
        $this->db->like('Last_Updated', $date);
        $this->db->from('HackersIP');
        return $this->db->count_all_results(); // Produces an integer, like 17
    }

    public function get_datetotalcount(){

        return $this->db->count_all_results('HackersIP');
    }

    public function get_user($column, $ip){
        $this->db->from('Users');
        $this->db->where($column, $ip);
        $query = $this->db->get();
        return $query->result();
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

    public function register_user($data)
    {
        $this->db->insert('Users', $data);
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

    function find_ip($what, $data)
    {
        $this->db->from('HackersIP');
        $this->db->like($what, $data, 'both');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_nick($nick){
        $this->db->from('Users');
        $this->db->where('Username', $nick);
        $query = $this->db->get();
        return $query->result();
    }
    function add_user($data)
    {
        return $this->db->insert('HackersIP', $data);
    }

    function refresh_user($id, $date)
    {

        $this->db->set('ExpireDate', $date, FALSE);
        $this->db->where('ID', $id);
        $this->db->update('Users');
        return $this->db->affected_rows();

    }

    public function update($where, $data)
    {
        $this->db->update('HackersIP', $data, $where);
        return $this->db->affected_rows();
    }

    public function ban_user($where, $data)
    {
        $this->db->update('Users', $data, array("DiscordName" => $where));
        return $this->db->affected_rows();
    }
    public function addipcounter($id){
        $this->db->set('IPprovided', 'IPprovided+1', FALSE);
        $this->db->where('ID', $id);
        $this->db->update('Users');
    }
}