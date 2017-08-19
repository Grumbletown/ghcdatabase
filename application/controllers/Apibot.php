<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apibot extends CI_Controller {
    var $data;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form','url','html'));
        $this->load->model('bot_model');
        $this->load->model('user_model');
        $this->data = array(
            'error' => FALSE,
            'errormsg' => '',


        );
    }

    public function index()
    {
        $this->load->view('templates/header.php');
        $this->load->view('templates/navbar.php');
        // $this->load->view('welcome_message');
        $this->load->view('templates/footer.php');
        $data = array(
            'ip' => '1.51.1.12',
            'name' => 'tada',
            'rep' => '12',
            'desc' => '',
            'clan' => 'clan',
            'miners' => '3',
            'discorduser' => '123456',
            'token' => '9QAi(POGA3Gexu9jThSVLK1rtT0Q6MT9a_1SXEJRpkumTDgOL.',
        
        );
        $test = json_encode($data);
        $url = base_url('index.php/apibot/addip/');
        $urlencoded = rawurlencode($test);
        echo $url . $urlencoded;
    }


    public function addip($json)
    {


        $decode = urldecode($json);
        $decode = json_decode($decode);

        if($this->bot_model->get_token($decode->token) < 1)
        {
            $this->data['errormsg'] = 'Token ungültig!';
            $this->data['error'] = TRUE;

        }
        if(empty($decode->discorduser) && !$this->data['error'])
        {
            $this->data['errormsg'] = 'Discord User nicht angegeben!';
            $this->data['error'] = TRUE;
        }
        else
        {
            $result = $this->bot_model->get_user_by_discord($decode->discorduser);
        }

        if(!$result && !$this->data['error'])
        {
            $this->data['errormsg'] = 'Discord User nicht gefunden!';
            $this->data['error'] = TRUE;
        }

        if(empty($decode->ip) && !$this->data['error'])
        {
            $this->data['errormsg'] = 'IP benötigt!';
            $this->data['error'] = TRUE;
        }
        if(!$this->input->valid_ip($decode->ip) && !$this->data['error'])
        {
            $this->data['errormsg'] = 'IP ungültig!';
            $this->data['error'] = TRUE;

        }
        if($this->bot_model->get_ip($decode->ip) > 0 && !$this->data['error'])
        {
            $this->data['errormsg'] = 'IP bereits vorhanden!';
            $this->data['error'] = TRUE;

        }
        
        if(empty($decode->name))
        {
            $this->data['errormsg'] = 'Spielername nicht angegeben!';
            $this->data['error'] = TRUE;
            
            
        }
        if($this->bot_model->get_nickname($decode->name) > 0 && !$this->data['error'])
        {
            $this->data['errormsg'] = 'Spielername bereits vorhanden!';
            $this->data['error'] = TRUE;

        }

        if(!$this->data['error'])
        {
            $today = strtotime(date("Y/m/d"));
            $data = array(
                'IP' => $decode->ip,
                'Name' => $decode->name,
                'Added_By' => $result[0]->ID,
                'Reputation' => $decode->rep,
                'Clan' => $decode->clan,
                'Last_Updated' => $today,
                'Miners' => $decode->miners,
                'Description' => $decode->desc,
            );
            $ergebnis = $this->bot_model->add_ip($data);
            if($ergebnis)
            {
                $this->data['errormsg'] = 'IP erfolgreich hinzugefügt!';
                $this->data['error'] = FALSE;
                $expireDate = $result[0]->ExpireDate;
                $expireDate = strtotime(str_replace("-","/", $expireDate));
                $today = strtotime(date("Y/m/d"));
                if($today < $expireDate)
                {
                    $expires = date('Y-m-d', strtotime("+30 days"));
                    $this->bot_model_refresh_user($result[0]->ID, $expires);
                }

            }
            else
            {
                $this->data['errormsg'] = 'Fehler beim hinzufügen der IP!';
                $this->data['error'] = TRUE;
            }
        }


        echo json_encode($this->data);




        
        //var_dump($decode);
    }

    public function getip($json)
    {


    }

    public function registeruser($json)
    {


    }

    public function refreshuser($json)
    {


    }
}