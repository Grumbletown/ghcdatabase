<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        
    }
	
	public function index()
	{
		
        $this->load->view('templates/header.php');
        $this->load->view('templates/navbar.php');
        $this->load->view('home');
        $this->load->view('templates/footer.php');

	}
	
	public function hotut()
	{
	$this->load->view('templates/header.php');
        $this->load->view('templates/navbar.php');
        $this->load->view('hotut');
        $this->load->view('templates/footer.php');
	}
}