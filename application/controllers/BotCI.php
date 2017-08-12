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
        $botCommands = $this->input->input_stream("botCommandJSON");

        if ($botCommands == null) 
        {
            echo "<p>Null value!\n";
        }

        if (json_decode($botCommands) != null)
        {
            $file = fopen("../../assets/json/botCommands/de/botCommands.json",'w+');

            if (!$file) {
                echo "<p>Datei konnte nicht zum schreiben geöffnet werden.\n";
                exit;
            } else {
                echo "<p>Alles super mit der Datei!\n";
            }

            fwrite($file, $botCommands);
            fclose($file);
        }
    }

    public function writeBotCommandsBackup() 
    {
        $botCommandsBackup = $this->input->input_stream("botCommandJSONBackup");

        if ($botCommandsBackup == null) 
        {
            echo "<p>Null value!\n";
        }

        if (json_decode($botCommandsBackup) != null)
        {
            $file = fopen("../../assets/json/botCommands/de/botCommandsBackup.json",'w+');

            if (!$file) {
                echo "<p>Datei konnte nicht zum schreiben geöffnet werden.\n";
                exit;
            } else {
                echo "<p>Alles super mit der Datei!\n";
            }

            fwrite($file, $botCommandsBackup);
            fclose($file);
        }
    }
}