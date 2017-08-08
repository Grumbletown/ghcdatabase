<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reset extends CI_Controller {
    
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->helper('date');
		$this->load->model('user_model');

	}


	public function index($key)
	{
		$this->load->view('templates/header.php');
        $this->load->view('templates/navbar.php');


        $data = $this->user_model->get_key($key);
        $this->userid = $data[0]->UID;
        if(empty($data))
        {
            redirect('home');
        }
        else
        {
            $datecreated = $data[0]->ValidUntil;
            $date = new DateTime($datecreated);
            $date->add(new DateInterval('PT1H'));
            $this->now = date("Y-m-d H:i:s");
            $expires = $date->format('Y-m-d H:i:s');
            if($expires > $this->now)
            {
                
                $this->load->view('reset_view');
            }
            else
            {
                redirect('home');

            }




            $this->load->view('templates/footer.php');

        }

	}

    
}