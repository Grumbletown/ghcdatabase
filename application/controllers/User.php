<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
    public $sql = "Users";
    public $table = "Users";

    public function __construct()
    {
        parent::__construct();

        $this->load->model('table_ajax');
        $this->load->helper('url');
        $this->load->helper('date');
        $this->load->model('user_model');

    }

public function ajax_updaterep()
{

    $data = array(


        'Reputation' => $this->input->post('reputation'),

    );
    $this->table_ajax->update(array('id' => $_SESSION['uid']), $data, $this->table);
    $_SESSION['Rep'] = $this->input->post('reputation');
    echo json_encode(array("status" => TRUE));
}



/* public function ajax_updatemail()
 {

     $data = array(


         'Email' => $this->input->post('email'),

     );
     $this->table_ajax->update(array('id' => $_SESSION['uid']), $data, $this->table);
     echo json_encode(array("status" => TRUE));
 }
*/
public function ajax_updatepw()
{

    $result = $this->user_model->login($_SESSION['uname'], $this->input->post('oldpw'));
    if($result){
        if($this->input->post('newpw') == $this->input->post('newpwrepeat')){
            $new_password = password_hash($this->input->post('newpw'), PASSWORD_DEFAULT);
            $data = array(


                'Password' => $new_password,
            );
        }






    }
    $this->table_ajax->update(array('id' => $_SESSION['uid']), $data, $this->table);
    echo json_encode(array("status" => TRUE));
}

public function generate_token(){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ*-_*.()';
    $string = '';

    for ($i = 0; $i < 50; $i++) {
        $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    $data = array(

        'UserID' => $_SESSION['uid'],
        'Token' => $string,
    );
    if($this->user_model->get_token($_SESSION['uid']) == false){

        $this->table_ajax->save($data, 'APIToken');
        echo json_encode(array("status" => TRUE));

    } else {
        $this->table_ajax->update(array('UserID' => $_SESSION['uid']), $data, 'APIToken');
        echo json_encode(array("status" => TRUE));
    }



}

}
