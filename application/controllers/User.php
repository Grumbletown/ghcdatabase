<?php
session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	
	public function index()
	{
		$this->load->view('templates/header.php');
        $this->load->view('templates/navbar.php');
       // $this->load->view('welcome_message');
        $this->load->view('templates/footer.php');
	}
}