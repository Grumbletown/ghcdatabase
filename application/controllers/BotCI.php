<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BotCI extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        if(!$_SESSION['Role'] === "Admin"){
            redirect('Home');
        }
    }
	
	public function index()
	{
		$this->load->view('templates/header.php');
        $this->load->view('templates/navbar.php');
        $this->load->view('botCI.php');
        $this->load->view('templates/footer.php');
	}
}