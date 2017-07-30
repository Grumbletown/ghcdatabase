<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form','url','html'));
        $this->load->model('user_model');
        
    }
    
    function index()
    {
    	$sperrzeit = array(
           0 => 0,
           1 => 0,
           2 => 0,
           3 => 30,
           4 => 30,
           5 => 30,
           6 => 180,
           7 => 180,
           8 => 180,
           9 => 1800,
           10 => 1800,
           11 => 1800,
           12 => 18000
    
        );
        $data['error'] = FALSE;
                    $data['errormsg'] = '';
        $ip = $this->input->ip_address();
        if($this->input->valid_ip($ip))
        {
        	$result = $this->user_model->ip_check($ip);
            if($result)
            {
            	$attempt = $result[0]->Attempts;
                if($attempt > 12)
                {
                	$attempt = 12;
                }
            	$lastattempt = $result[0]->LastAttempt;
            }
            else
            {
            	
            $attempt = 0;
        	$insert = array(
                'UIP' => $ip,
                'Attempts' => 0
             );
             $this->user_model->ip_add($insert);
             
            }
        }
        else
        {
        	$data['error'] = TRUE;
            $data['errormsg'] = "Ungültige IP!";
        }
    	$this->load->view('templates/header.php');
        $this->load->view('templates/navbar.php');
        $email = $this->input->post("email");
        $password = $this->input->post("password");
        $error = '';
        
        
        // form validation
       $this->form_validation->set_rules("email", "Username", "trim|required");
       $this->form_validation->set_rules("password", "Password", "trim|required|callback_check_database");
        if ($this->form_validation->run() == FALSE)
        {
            // validation fail
           
            if(isset($lastattempt))
            {
            
                $now = strtotime(date("Y-m-d H:i:s"));
                $sperre = strtotime($lastattempt) + $sperrzeit[$attempt] * 60;
                $minute = floor(($sperre - $now) / 60);
                $second = fmod($sperre, $now);
                //$timeleft2 = date("i:s", $timeleft);
                if($now > $sperre)
                {
                	$data['error'] = FALSE;
                    $data['errormsg'] = '';
                   $this->user_model->ip_update($ip);
                }
                else
                {
                	$data['error'] = TRUE;
                    $data['errormsg'] = 'Zu viele fehlgeschlagene Login versuche!';
                }
                
                $this->load->view('logintut', $data);
            
            }
            
            
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
  $result = $this->user_model->login($username, $password);

  if($result)
  {
    $sess_array = array();
    foreach($result as $row)
    {
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
     $sess_array = array(
        'uid' => $row->ID,
        'uname' => $row->Username,
        'Role' => $row->Role,
        'Expired' => $expired,
        'login' => TRUE,
        'Rep' => $row->Reputation
      );
      $this->session->set_userdata($sess_array);
    }
    return TRUE;
  }
  else
  {
     $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Benutzername oder Passwort falsch!</div>');
     
    $this->form_validation->set_message('check_database', '');
    return false;
  }
}
    
    function logout()
    {
    	session_unset();
        session_destroy();
        redirect('/home/index/');
     }
        
}
