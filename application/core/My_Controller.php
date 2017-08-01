<?php
/**
 * Created by PhpStorm.
 * User: zeroc
 * Date: 31.07.2017
 * Time: 01:56
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->helper('url');


        // If the user has not logged in will redirect back to login
        if (!$this->session->userdata('login') == TRUE) {
            $this->session->unset_userdata('uid');
            redirect('Home');
        }
    }
}