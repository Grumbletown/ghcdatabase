<?php
//ob_implicit_flush(true);
defined('BASEPATH') OR exit('No direct script access allowed');

class Table extends MY_Controller {
    public $sql = 0;
    public $table = "HackersIP";


    public function __construct()
    {
        parent::__construct();
        $this->load->model('webhook_model');
        $this->load->model('table_ajax');
		$this->load->helper('url');
        $this->load->helper('date');
        $this->sql = "(SELECT STRAIGHT_JOIN `HackersIP`.`ID`, `IP`, `HackersIP`.`Name`, `HackersIP`.`Reputation`, `Last_Updated`, `Description`, `Miners`, `Clan`, COALESCE(`CountsIPRepo`.`CountIPRepo`, 0) AS `CountIPRepo`, COALESCE(`UsersIPRepo`.`UserIPRepo`, 0) AS `UserIPRepo`, COALESCE(`UsersIPFav`.`UserIPFav`, 0) AS `UserIPFav`FROM `HackersIP` LEFT JOIN `Users` ON `HackersIP`.`Name` = `Users`.`Username` LEFT JOIN (SELECT COUNT(1) AS `CountIPRepo`, `IPUserReport`.`IPID` FROM `IPUserReport` GROUP BY `IPUserReport`.`IPID`) AS `CountsIPRepo` ON `CountsIPRepo`.`IPID` = `HackersIP`.`ID` LEFT JOIN (SELECT COUNT(1) AS `UserIPRepo`, `IPUserReport`.`IPID` FROM `IPUserReport` WHERE `IPUserReport`.`UserID` = ".$_SESSION['uid']." GROUP BY `IPUserReport`.`IPID`) AS `UsersIPRepo` ON `UsersIPRepo`.`IPID` = `HackersIP`.`ID` LEFT JOIN (SELECT COUNT(1) AS `UserIPFav`, `IPUserFav`.`IPID` FROM `IPUserFav` WHERE `IPUserFav`.`UserID` = ".$_SESSION['uid']." GROUP BY `IPUserFav`.`IPID`) AS `UsersIPFav` ON `UsersIPFav`.`IPID` = `HackersIP`.`ID` WHERE `Users`.`Last_Login` < DATE_SUB( now(), INTERVAL 30 DAY) OR `Users`.`Last_Login` IS NULL) AS T";
    }



	public function iptable()
	{

        //$this->output->enable_profiler(TRUE);
        $data = array(
          'title' => 'IPs',
          'switch' => 'ip'
        );
        $this->load->view('templates/header.php');
        $this->load->view('templates/navbar.php');
        $this->load->view('table_view', $data);
        $this->load->view('templates/footer.php');

	}

    public function favtable()
    {

        //$this->output->enable_profiler(TRUE);
        $data = array(
            'title' => 'Favourites',
            'switch' => 'fav'
        );
        $this->load->view('templates/header.php');
        $this->load->view('templates/navbar.php');
        $this->load->view('table_view', $data);
        $this->load->view('templates/footer.php');

    }

    public function repotable()
    {

        //$this->output->enable_profiler(TRUE);
        $data = array(
            'title' => 'Reported IPs',
            'switch' => 'repo'
        );
        $this->load->view('templates/header.php');
        $this->load->view('templates/navbar.php');
        $this->load->view('table_view', $data);
        $this->load->view('templates/footer.php');

    }

    public function ip_edit($id)
    {
        $data = $this->table_ajax->get_by_id($id, $this->table);

        echo json_encode($data);
    }
    public function fav_repo($id, $switch, $tables)
    {
        $data = $this->table_ajax->favrepo($id, $switch, $tables);

        echo json_encode(array("status" => TRUE));
    }
    public function ajax_add()
    {
        
        $datestring = '%Y-%m-%d';
        $time = time();
        $date = mdate($datestring, $time);
        $this->_validate("add");
        $data = array(
            'IP' => $this->input->post('IP'),
            'Name' => $this->input->post('name'),
            'Reputation' => $this->input->post('reputation'),
            'Miners' => $this->input->post('miners'),
            'Clan' => $this->input->post('clan'),
            'Description' => $this->input->post('description'),
            'Added_By' => $_SESSION['uid'],
            'Last_Updated' => $date,
        );
        $insert = $this->table_ajax->save($data, $this->table);
        $incount = $this->table_ajax->addipcounter($_SESSION['uid']);
        $added = array(
            'event' => 'addip',
            'addedby' => $_SESSION['Discord'],

        );


        $this->webhook_model->send(json_encode($added));
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update()
    {
        $result = $this->table_ajax->get_reported_ip($this->input->post('id'));
        $i=0;

        for($i; $i < count($result); $i++){

            $userid[] = $result[$i]->UserID;
            $discord = $this->table_ajax->get_discord($userid[$i]);
            $discordid[] = $discord[0]->Discordname;
        }
        $datestring = '%Y-%m-%d';
        $time = time();
        $date = mdate($datestring, $time);
        $this->_validate("edit");
        $data = array(
            'IP' => $this->input->post('IP'),
            'Name' => $this->input->post('name'),
            'Reputation' => $this->input->post('reputation'),
            'Miners' => $this->input->post('miners'),
            'Clan' => $this->input->post('clan'),
            'Description' => $this->input->post('description'),
            'Added_By' => $_SESSION['uid'],
            'Last_Updated' => $date,
        );
        if(empty($discordid)){
            $discordid[0] = "Niemand hat die IP gemeldet";
        }
        $added = array(
            'event' => 'editip',
            'editedby' => $_SESSION['Discord'],
            'reportedby' => $discordid
        );
        $this->webhook_model->send(json_encode($added));
        $update = $this->table_ajax->update(array('id' => $this->input->post('id')), $data, $this->table);
        echo json_encode(array("status" => TRUE, "message" => $this->input->post('id')));
    }

    public function ajax_delete()
    {
        $result = $this->table_ajax->get_reported_ip($this->input->post('id'));
        $i=0;

        for($i; $i < count($result); $i++){

            $userid[] = $result[$i]->UserID;
            $discord = $this->table_ajax->get_discord($userid[$i]);
            $discordid[] = $discord[0]->Discordname;
        }
        if(empty($discordid)){
            $discordid[0] = "Niemand hat die IP gemeldet";
        }
        $this->table_ajax->delete_by_id($this->input->post('id'), $this->table);
        $added = array(
            'event' => 'deleteip',
            'deletedby' => $_SESSION['Discord'],
            'reportedby' => $discordid
        );
        $this->webhook_model->send(json_encode($added));
        echo json_encode(array("status" => TRUE));
    }


    private function _validate($what)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('IP') == '')
        {
            $data['inputerror'][] = 'IP';
            $data['error_string'][] = 'IP benötigt';
            $data['status'] = FALSE;
        }
        else{
            if($this->input->valid_ip($this->input->post('IP'))) {
                if ($what === 'add') {
                    if ($this->table_ajax->get_ip($this->input->post('IP'), $this->table) > 0) {
                        $data['inputerror'][] = 'IP';
                        $data['error_string'][] = 'IP bereits vorhanden!';
                        $data['status'] = FALSE;
                    }
                }
            }
            else{
                $data['inputerror'][] = 'IP';
                $data['error_string'][] = 'IP ungültig!';
                $data['status'] = FALSE;

            }
            
        }

        if($this->input->post('name') == '')
        {
            $data['inputerror'][] = 'name';
            $data['error_string'][] = 'Nickname benötigt';
            $data['status'] = FALSE;
        }
        else{
            if($what === 'add'){
                if($this->table_ajax->get_name($this->input->post('name'), $this->table) > 0) {
                    $data['inputerror'][] = 'name';
                    $data['error_string'][] = 'Nickname bereits vorhanden!';
                    $data['status'] = FALSE;
                }
            }


        }


        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }
    public function ips_page($tableswitch)
    {

        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $search = intval ($this->input->get("search[value]"));
        $total_ips = $this->table_ajax->get_total_ips($this->table);

        $order = $this->input->get("order");

        $col = 0;
        $dir = "";
        if(!empty($order)) {
            foreach($order as $o) {
                $col = $o['column'];
                $dir= $o['dir'];
            }
        }

        if($dir != "asc" && $dir != "desc") {
            $dir = "asc";
        }

        $columns_valid = array(
            "`T`.`IP`",
            "`T`.`Name`",
            "`T`.`Reputation`",
            "`T`.`Description`",
            "`T`.`Miners`",
            "`T`.`Clan`",
            "`T`.`Last_Updated`"
        );

        if(!isset($columns_valid[$col])) {
            $order = null;
        } else {
            $order = $columns_valid[$col];
        }


        $ips = $this->table_ajax->get_ips($tableswitch);

        $data = array();

        foreach($ips->result() as $row) {

            $data[] = array(

                $row->IP,
                $row->Name,
                $row->Reputation,
                $row->Description,
                $row->Miners,
                $row->Clan,
                $row->Last_Updated,
                $row->UserIPFav,
                $row->ID,
                $row->UserIPRepo,
                $row->CountIPRepo,
            );

        }


        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_ips,
            "recordsFiltered" => $this->table_ajax->count_filtered($tableswitch),
            "data" => $data
        );

        $json = json_encode($output);

        echo $json;
        
        exit();
    }
    
}
