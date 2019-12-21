<?php
defined('BASEPATH') OR exit('No direct script access allowed');
session_start();
class Home extends CI_Controller {
 	public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Database_model');
    }

	public function index(){
		echo "asfasd";
		$this->load->view('home/index');
	}
	
	public function about_us(){
		$this->load->view('home/about_us');
	}
	
	public function faqs(){
		$this->load->view('faqs/index');
	}
	
	public function not_found(){
		$data['page'] = 'not_found';
		$this->load->view('errors/404',$data);
	}

	

}
