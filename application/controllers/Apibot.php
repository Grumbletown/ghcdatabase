<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Apibot extends CI_Controller {
    var $data;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form','url','html', 'date'));
        $this->load->model('bot_model');
        $this->load->model('user_model');
        $this->data = array(
            'error' => FALSE
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
            'name' => 'aaa',
            'rep' => 12,
            'desc' => '???',
            'clan' => 'clan',
            'miners' => 3,
            'discorduser' => '145348181880733697',
            'token' => 'vnlTNf3aE*0C(8i6maOLk_8GiU-OH*zY388rlJEk)UrjuqpB9C',
            'update' => FALSE,

        );
        /*$datafind = array(
            'name' => 'Dad',
            'token' => '9QAi(POGA3Gexu9jThSVLK1rtT0Q6MT9a_1SXEJRpkumTDgOL.',

        );

        $datauser = array(
            'name' => 'Dad',
            'token' => '9QAi(POGA3Gexu9jThSVLK1rtT0Q6MT9a_1SXEJRpkumTDgOL.',
            'discorduser' => '123456',
            'password' => 'adsadsadas',
        );*/
        $test = json_encode($data);
        $url = base_url('index.php/apibot/addip/');
        $urlencoded = rawurlencode($test);
        echo $url . $urlencoded;
    }


    public function addip($json)
    {


        $decode = urldecode($json);
        $decode = json_decode($decode);
        if(!empty($decode->token))
        {
            $counttoken = $this->bot_model->get_token($decode->token);
            if(!$counttoken < 1)
            {
                if(!empty($decode->discorduser))
                {
                    $result = $this->bot_model->get_user_by_discord($decode->discorduser);
                    if(!empty($result))
                    {
                        if(!empty($decode->ip))
                        {
                            if($this->input->valid_ip($decode->ip))
                            {
                                $ipcount = $this->bot_model->get_ip('IP',$decode->ip);
                                if(!empty($ipcount))
                                {
                                    $this->data['msgIP'] = 'IP bereits vorhanden!';
                                    $this->data['IPID'] = $ipcount;
                                    $this->data['error'] = TRUE;

                                }
                                if(!empty($decode->name))
                                {
                                    $namecount = $this->bot_model->get_ip('IP',$decode->ip);
                                    if(!empty($namecount))
                                    {

                                        $this->data['msgName'] = 'Spielername bereits vorhanden!';
                                        $this->data['IPID'] = $namecount;
                                        $this->data['error'] = TRUE;


                                    }


                                }
                                else
                                {
                                    $this->data['msgName'] = 'Spielername nicht angegeben!';
                                    $this->data['error'] = TRUE;

                                }

                            }
                            else
                            {
                                $this->data['msgIP'] = 'IP ungültig!';
                                $this->data['error'] = TRUE;
                            }
                        }else{
                            $this->data['msgIP'] = 'IP benötigt!';
                            $this->data['error'] = TRUE;
                        }
                    }else{
                        $this->data['msgDiscord'] = 'Discord User nicht gefunden!';
                        $this->data['error'] = TRUE;
                    }
                }
                else
                {
                    $this->data['msgDiscord'] = 'Discord User nicht angegeben!';
                    $this->data['error'] = TRUE;
                }

            }else{
                $this->data['msgToken'] = 'Token ungültig!';
                $this->data['error'] = TRUE;
            }
        }
        else{
            $this->data['msgToken'] = 'Token ungültig!';
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
        if($decode->update == FALSE)
        {
            $ergebnis = $this->bot_model->add_ip($data);
        }

        if($decode->update == TRUE)
        {
            $ergebnis2 = $this->bot_model->update_ip($decode->IPID, $data);
        }

        if($ergebnis || $ergebnis2 == 1)
        {
            if($decode->update == FALSE)
            {
                $this->data['msgIP'] = 'IP erfolgreich hinzugefügt!';
            }

            if($decode->update == TRUE)
            {
                $this->data['msgIP'] = 'IP erfolgreich geändert!';
            }

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
            $this->data['msgIP'] = 'Fehler beim hinzufügen der IP!';
            $this->data['error'] = TRUE;
        }
}


echo json_encode($this->data);
    }

    public function getip($json)
    {
        $decode = urldecode($json);
        $decode = json_decode($decode);
        if(empty($decode->token))
        {
            $this->data['msgToken'] = 'Token ungültig!';
            $this->data['error'] = TRUE;
        }
        else
        {
            $counttoken = $this->bot_model->get_token($decode->token);
            if($counttoken < 1)
            {
                $this->data['msgToken'] = 'Token ungültig!';
                $this->data['error'] = TRUE;

            }
        }

        if(empty($decode->name))
        {
            $this->data['msgName'] = 'Spielername nicht angegeben!';
            $this->data['error'] = TRUE;
        }
        else
        {

            $namecount = $this->bot_model->get_nick($decode->name);
            if(!empty($namecount))
            {

                $this->data['msgName'] = 'Spieler ist Mitglied der GHC-Community';

                $this->data['error'] = TRUE;


            }else{
            $what = 'Name';
            $search = $decode->name;

            }
        }

        if(!$this->data['error'])
        {
            $result = $this->bot_model->find_ip($what, $search);
            $count = count($result);

            if($count < 1)
            {
                $this->data['msgName'] = 'Keine Übereinstimmung gefunden!';
                $this->data['error'] = TRUE;
            }

            if($count == 1)
            {
                $namecount = $this->bot_model->get_nick($result[0]->Name);
                if(empty($namecount)) {
                    $funde = array(
                        'IP' => $result[0]->IP,
                        'Name' => $result[0]->Name,
                        'Rep' => $result[0]->Reputation,
                        'Miners' => $result[0]->Miners,
                        'Clan' => $result[0]->Clan,
                        'Desc' => $result[0]->Description,

                    );

                    $this->data['IPFunde'] = $funde;
                    $this->data['msgName'] = 'Ein Eintrag gefunden!';
                    $this->data['error'] = FALSE;
                }
                else{

                    $this->data['msgName'] = 'Spieler ist Mitglied der GHC-Community';

                    $this->data['error'] = TRUE;
                }
            }

            if($count > 1 && $count <= 10)
            {

                $this->data['msgName'] = 'Mehrere Einträge gefunden! Bitte Suche einschränken!';
                $this->data['error'] = TRUE;
            }

            if($count > 10)
            {
                $this->data['msgName'] = 'Zuviele Einträge gefunden! Bitte Suche einschränken!';
                $this->data['error'] = TRUE;
            }

        }
        echo json_encode($this->data);
    }

    public function registeruser($json)
    {
        $decode = urldecode($json);
        $decode = json_decode($decode);
        if(empty($decode->token))
        {
            $this->data['msgToken'] = 'Token ungültig!';
            $this->data['error'] = TRUE;
        }
        else
        {
            $counttoken = $this->bot_model->get_token($decode->token);
            if($counttoken < 1)
            {
                $this->data['msgToken'] = 'Token ungültig!';
                $this->data['error'] = TRUE;

            }
        }

        if(empty($decode->discorduser))
        {
            $this->data['msgDiscord'] = 'Discord User nicht angegeben!';
            $this->data['error'] = TRUE;
        }
        else
        {
            $result = $this->bot_model->get_user_by_discord($decode->discorduser);
        }

        if($result)
        {
            $this->data['msgDiscord'] = 'Discord User bereits gefunden!';
            $this->data['error'] = TRUE;
        }

        if(empty($decode->name))
        {
            $this->data['msgName'] = 'Spielername nicht angegeben!';
            $this->data['error'] = TRUE;
        }else{

            $namecount = $this->bot_model->get_user('Username',$decode->name);
            if($namecount < 1 )
            {
                $this->data['msgName'] = 'Spielername bereits vorhanden!';
                $this->data['error'] = TRUE;
            }
        }



        if(empty($decode->password))
        {
            $this->data['msgPassword'] = 'Passwort nicht angegeben!';
            $this->data['error'] = TRUE;
        }

        if(!$this->data['error'])
        {
            $expires = date('Y-m-d', strtotime("+30 days"));
            $registerdate = $this->now();
            $data = array(
                'Username' => $decode->name,
                'Password' => password_hash($decode->password, PASSWORD_DEFAULT),
                'DiscordName' => $decode->discorduser,
                'Reputation' => 0,
                'Role' => 'User',
                'Last_Updated' => $registerdate,
                'ExpireDate' => $expires,

            );
            $adden = $this->bot_model->register_user($data);
            if($adden)
            {
                $this->data['msgUser'] = 'User erfolgreich hinzugefügt!';
                $this->data['error'] = FALSE;
            }
            else
            {
                $this->data['msgUser'] = 'Fehler beim adden des Users!';
                $this->data['error'] = TRUE;
            }
        }

        echo json_encode($this->data);
    }

    public function refreshuser($json)
    {
        $decode = urldecode($json);
        $decode = json_decode($decode);

        if(empty($decode->token))
        {
            $this->data['msgToken'] = 'Token ungültig!';
            $this->data['error'] = TRUE;
        }
        else
        {
            $counttoken = $this->bot_model->get_token($decode->token);
            if($counttoken < 1)
            {
                $this->data['msgToken'] = 'Token ungültig!';
                $this->data['error'] = TRUE;

            }
        }

        if (empty($decode->discorduser)) {
            $this->data['msgDiscord'] = 'Discord User nicht angegeben!';
            $this->data['error'] = TRUE;
        } else {
            $result = $this->bot_model->get_user_by_discord($decode->discorduser);
        }

        if (!$result && !$this->data['error']) {
            $this->data['msgDiscord'] = 'Discord User nicht gefunden!';
            $this->data['error'] = TRUE;
        } else {
            $expires = date('Y-m-d', strtotime("+30 days"));
            $result = $this->bot_model->refresh_user($result[0]->ID, $expires);
            if ($result < 1) {
                $this->data['msgDiscord'] = 'Account konnte nicht erneuert werden!';
                $this->data['error'] = TRUE;
            } else {
                $this->data['msgDiscord'] = 'Account erfolgreich erneuert!';
                $this->data['error'] = FALSE;
            }

        }
        echo json_encode($this->data);
    }

    /**
     * @return array
     */
    public function banuser($json)
    {
        $decode = urldecode($json);
        $decode = json_decode($decode);
        if (empty($decode->token)) {
            $this->data['msgToken'] = 'Token ungültig!';
            $this->data['error'] = TRUE;
        } else {
            $counttoken = $this->bot_model->get_token($decode->token);
            if ($counttoken < 1) {
                $this->data['msgToken'] = 'Token ungültig!';
                $this->data['error'] = TRUE;

            }
        }
        if (empty($decode->discorduser)) {
            $this->data['msgDiscord'] = 'Discord User nicht angegeben!';
            $this->data['error'] = TRUE;
        } else {
            $result = $this->bot_model->get_user_by_discord($decode->discorduser);
        }

        if (!$result && !$this->data['error']) {
            $this->data['msgDiscord'] = 'Discord User nicht gefunden!';
            $this->data['error'] = TRUE;
        } else {
            $data = array(
                'Banned' => TRUE,

            );
            $result = $this->bot_model->ban_user($result[0]->ID, $data);
            if ($result < 1) {
                $this->data['msgDiscord'] = 'Account konnte nicht gesperrt werden!';
                $this->data['error'] = TRUE;
            } else {
                $this->data['msgDiscord'] = 'Account erfolgreich gesperrt!';
                $this->data['error'] = FALSE;
            }
        }
        echo json_encode($this->data);
    }
        public function pwreset($json)
    {
        $decode = urldecode($json);
        $decode = json_decode($decode);
        if(empty($decode->token))
        {
            $this->data['msgToken'] = 'Token ungültig!';
            $this->data['error'] = TRUE;
        }
        else
        {
            $counttoken = $this->bot_model->get_token($decode->token);
            if($counttoken < 1)
            {
                $this->data['msgToken'] = 'Token ungültig!';
                $this->data['error'] = TRUE;

            }
        }
        if(empty($decode->password))
        {
            $this->data['msgPassword'] = 'Passwort nicht angegeben!';
            $this->data['error'] = TRUE;
        }
        if (empty($decode->discorduser)) {
            $this->data['msgDiscord'] = 'Discord User nicht angegeben!';
            $this->data['error'] = TRUE;
        } else {
            $result = $this->bot_model->get_user_by_discord($decode->discorduser);
        }

        if (!$result ) {
            $this->data['msgDiscord'] = 'Discord User nicht gefunden!';
            $this->data['error'] = TRUE;
        } else {
            if(!$this->data['error']){
            $data = array(
                'Password' => password_hash($decode->password, PASSWORD_DEFAULT),

            );

            $result = $this->bot_model->ban_user($result[0]->DiscordName, $data);

            if (empty($result)) {
                $this->data['msgDiscord'] = 'Passwort konnte nicht geändert werden!';
                $this->data['error'] = TRUE;
            } else {
                $this->data['msgDiscord'] = 'Passwort erfolgreich geändert!';
                $this->data['error'] = FALSE;
            }
            }
        }
        echo json_encode($this->data);
    }
}