<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Login extends CI_Controller
{
    
    var $data;
    public $now;
    public $sperre;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form','url','html'));
        $this->load->model('user_model');
        //$this->load->model('table_ajax');
        $this->data = array(
            'error' => '',
            'errormsg' => '',
            'time' => '0'

        );
    }
    
    function index()
    {
    	$sperrzeit = array(
           0 => 0,
           1 => 0,
           2 => 30,
           3 => 30,
           4 => 30,
           5 => 180,
           6 => 180,
           7 => 180,
           8 => 1800,
           9 => 1800,
           10 => 1800,
           11 => 18000,
           12 => 18000
    
        );
        $this->data['error'] = FALSE;
                    $this->data['errormsg'] = '';
        $this->session->set_flashdata('msg', '');
    	$this->load->view('templates/header.php');
        $this->load->view('templates/navbar.php');
        $email = $this->input->post("email");
        $password = $this->input->post("password");
        $error = '';
        $ip = $this->input->ip_address();
        $this->now = strtotime(date("Y-m-d H:i:s"));

        if($this->input->valid_ip($ip)) {
            $result = $this->user_model->ip_check($ip);

            if ($result) {
                $attempt = $result[0]->Attempts;
                if ($attempt > 12) {
                    $attempt = 12;
                }
                $lastattempt = $result[0]->LastAttempt;
            } else {

                $attempt = 0;
                $insert = array(
                    'UIP' => $ip,
                    'Attempts' => 0
                );
                $this->user_model->ip_add($insert);
                $this->data['error'] = FALSE;
                $this->data['errormsg'] = '';
                $lastattempt = $this->now;

            }
        }
        else
        {
            $this->data['error'] = TRUE;
            $this->data['errormsg'] = "Ungültige IP!";
        }



            $this->sperre = strtotime($lastattempt) + $sperrzeit[$attempt] * 60;
            $this->data['time'] = floor(($this->sperre - $this->now));


        if($this->now < $this->sperre)
        {

            $this->data['error'] = TRUE;
            $this->data['errormsg'] = 'Zu viele fehlgeschlagene versuche!';
        }




        // form validation
       $this->form_validation->set_rules("email", "Username", "trim|required");
       $this->form_validation->set_rules("password", "Password", "trim|required|callback_check_database");
        if ($this->form_validation->run() == FALSE)
        {
            // validation fail
            
            $this->load->view('login_view', $this->data);


            
            
        }
        else
        {

            redirect('home');

        } //end von form validation
       
          $this->load->view('templates/footer.php');
    } 
    

function check_database($password)
{
  //Field validation succeeded.  Validate against database
  $username = $this->input->post('email');

  //query the database
  if(!empty($username)){
    $role = $this->user_model->get_role($username);
      $role = $role[0]->Role;

      $result = $this->user_model->login($username, $password);

  }
  if(!empty($result))
  {
    $sess_array = array();
    foreach($result as $row)
    {

        if($row->Role != 'Banned'){
        $expireDate =$row->ExpireDate;
        $expireDate = strtotime(str_replace("-","/", $expireDate));
        $today = strtotime(date("Y/m/d"));
     if($today > $expireDate)
     {
     	$expired = FALSE;
     }
     else
     {
     	$expired = TRUE;
     }
        $data = array(
            'Last_Login' => strtotime(date("Y-m-d H:i:s"))


        );
        $sess_array = array(
        'uid' => $row->ID,
        'uname' => $row->Username,
        'Role' => $row->Role,
        'Expired' => $expired,
        'login' => TRUE,
        'Rep' => $row->Reputation
      );
      $this->session->set_userdata($sess_array);
        $this->user_model->update(array('ID' => $row->ID), $data, 'Users');

      $this->user_model->delete_ip_attmepts($this->input->ip_address());


    return TRUE;
     }
    }
  }
  else
  {
      if($this->now >= $this->sperre && !empty($username))
      {
          $this->data['error'] = FALSE;
          $this->data['errormsg'] = '';
          $this->user_model->ip_update($this->input->ip_address());
      }
      else
      {
          if(!empty($username) && !$role == "Banned") {
              $this->data['error'] = TRUE;
              $this->data['errormsg'] = 'Zu viele fehlgeschlagene versuche!';
          }else{
              if($role == "Banned"){
                  $this->data['error'] = TRUE;
                  $this->data['errormsg'] = 'Account gesperrt!';
              }

          }

      }

     if(!$this->data['error'] === TRUE){
         
      $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Benutzername oder Passwort falsch!</div>');
     }

    $this->form_validation->set_message('check_database', '');
    return false;
  }
}
    
    function logout()
    {
    	session_unset();
        session_destroy();
        redirect('home');
     }

    public function set_pw()
    {

        if($this->input->post('newpw') == $this->input->post('newpwrepeat')){
            $new_password = password_hash($this->input->post('newpw'), PASSWORD_DEFAULT);
            $data = array(


                'Password' => $new_password,
            );
            $this->user_model->update(array('ID' => $this->input->post('id')), $data, 'Users');
            echo json_encode(array("status" => TRUE));
            
        }
        else
        {
            $data['inputerror'][] = 'pass';
            $data['error_string'][] = 'Passw�rter stimmen nicht �berein!';
            $data['status'] = FALSE;
        }

    }
        
}
