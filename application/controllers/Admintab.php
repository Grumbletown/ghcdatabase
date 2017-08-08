<?php
//ob_implicit_flush(true);
defined('BASEPATH') OR exit('No direct script access allowed');

class Admintab extends MY_Controller {
    public $sql = "Users";
    public $table = "Users";


    public function __construct()
    {
        parent::__construct();

        $this->load->model('table_ajax');
        $this->load->helper('url');
        $this->load->helper('date');
        $this->load->model('user_model');
        if(!$_SESSION['Role'] === "Admin"){
            redirect('Home');
        }
    }



    public function index()
    {



        $this->load->view('templates/header.php');
        $this->load->view('templates/navbar.php');
        $this->load->view('admin_view');
        $this->load->view('templates/footer.php');

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
    
 
    public function generate_key($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $key = '';
        for ($i = 0; $i <= $length; $i++) {
            $key .= $characters[rand(0, $charactersLength - 1)];
        }
        return $key;
    }
    
    public function pwr_gen_admin($id)
    {
        $key = $this->generate_key(25);
        if($this->user_model->get_user_by_id($this->input->post('id')))
        {
            $this->user_model->insert_key($id, 'update', $key);
            echo json_encode(array("status" => TRUE,
                                   "key" => $key,
                ));
            
        }
        else
        {
            $this->user_model->insert_key($id, 'neu', $key);
            echo json_encode(array("status" => TRUE,
                                   "key" => $key,
            ));
        }
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
    public function user_settings($id)
    {
        $data = $this->table_ajax->get_settings($id, $this->table);

        echo json_encode($data);
    }

    public function user_page()
    {

        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $search = intval ($this->input->get("search[value]"));
        $total_users = $this->table_ajax->get_total_ips($this->table);

        $order = $this->input->get("order");

        $col = 0;
        $dir = "";
        if(!empty($order)) {
            foreach($order as $o) {
                $col = $o['column'];
                $dir= $o['dir'];
            }
        }

        if($dir != "asc" && $dir != "desc") {
            $dir = "asc";
        }

        $columns_valid = array(
            "Username",
            "Role",
            "Last_Login",
            "ExpireDate"

        );

        if(!isset($columns_valid[$col])) {
            $order = null;
        } else {
            $order = $columns_valid[$col];
        }


        $ips = $this->table_ajax->get_ips('user');
        $x = 0;
        $data = array();
        $expired = "";
        $today = date("Y-m-d");
        foreach($ips->result() as $row) {
            if ($row->ExpireDate < $today ){
                $expired = "Abgelaufen";
             
            } else {
                $expired = "Gültig";
             
            }
            if($row->Role == "Admin"){
                $expired = "Admin";
             
            }
            if($row->Role == "Moderator"){
                $expired = "Moderator";
             
            }
            $data[] = array(
                $row->ID,
                $row->Username,
                $row->Role,
                $row->Reputation,
                $row->Last_Login,
                $expired,
                
                $row->DiscordName,
                $x,
            );

        }


        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_users,
            "recordsFiltered" => $this->table_ajax->count_filtered('user'),
            "data" => $data
        );

        $json = json_encode($output);

        echo $json;

        exit();
    }

}