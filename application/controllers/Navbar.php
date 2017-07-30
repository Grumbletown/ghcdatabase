<?php
//ob_implicit_flush(true);
defined('BASEPATH') OR exit('No direct script access allowed');

class Navbar extends CI_Controller
{
    public $sql = "Users";
    public $table = "Users";


    public function __construct()
    {
        parent::__construct();

        $this->load->model('table_ajax');
        $this->load->helper('url');
        $this->load->helper('date');

    }


    public function ajax_update()
    {

        $data = array(

            'Username' => $this->input->post('name'),
            'Reputation' => $this->input->post('reputation'),
            'ExpireDate' => $this->input->post('expire'),
            'DiscordName' => $this->input->post('discord'),
            'Role' => $this->input->post('role'),
            'Email' => $this->input->post('email'),
        );
        $this->table_ajax->update(array('id' => $this->input->post('id')), $data, $this->table);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete()
    {
        $this->table_ajax->delete_by_id($this->input->post('id'), $this->table);
        echo json_encode(array("status" => TRUE));
    }

    public function user_edit($id)
    {
        $data = $this->table_ajax->get_by_id($id, $this->table);

        echo json_encode($data);
    }


}