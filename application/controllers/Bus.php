<?php
defined('BASEPATH') OR exit('No direct script access allowed');
session_start();
class Home extends CI_Controller {

}

    public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Database_model');
    }

	public function index(){
		$this->load->view('home/index');
	}

	public function search(){
		$this->load->view('home/index');
	}

?>