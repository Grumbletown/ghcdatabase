<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BotCI extends MY_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');

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

    public function writeBotCommands() 
    {
        $botCommands = $this->input->post("botCommandJSON");

        if (json_decode($botCommands) != null)
        {
            $file = fopen(FCPATH."assets/json/botCommands/de/botCommands.json",'w+');

            fwrite($file, $botCommands);
            fclose($file);
            echo "<p>Written to file!\n";
        }
        echo json_encode(array("status" => TRUE));
    }

    public function writeBotCommandsBackup() 
    {
        $botCommandsBackup = $this->input->post("botCommandJSONBackup");

        if (json_decode($botCommandsBackup) != null)
        {
            $file = fopen(FCPATH."assets/json/botCommands/de/botCommandsBackup.json",'w+');

            fwrite($file, $botCommandsBackup);
            fclose($file);
            echo "<p>Written to file!\n";
        }
        echo json_encode(array("status" => TRUE));
    }
}