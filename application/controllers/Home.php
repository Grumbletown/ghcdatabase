<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	
	public function index()
	{
		
        $this->load->view('templates/header.php');
        $this->load->view('templates/navbar.php');
       // $this->load->view('welcome_message');
        $this->load->view('templates/footer.php');
		//phpinfo ();
	}
	
	public function hotut()
	{
		$this->load->view('templates/header.php');
        $this->load->view('templates/navbar.php');
       // $this->load->view('welcome_message');
        $this->load->view('templates/footer.php');
	}
}