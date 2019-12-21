<?php echo 'hi'; exit;
defined('BASEPATH') OR exit('No direct script access allowed');
session_start();
class Home extends CI_Controller {

public function __construct() {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('Database_model');
    }

	public function index(){ 
		$this->load->view('home/index');
	}

	public function search(){
		$_POST = $_GET = $this->security->xss_clean($_GET);
		echo '<pre>sf'; print_r($_POST); exit;
		$this->form_validation->set_rules('journey_type', 'Journey Type', 'trim|required|min_length[3]|max_length[16]');
		$this->form_validation->set_rules('from_city', 'From City', 'trim|required|min_length[3]|max_length[64]');
		$this->form_validation->set_rules('to_city', 'To City', 'trim|required|min_length[3]|max_length[64]');
		$this->form_validation->set_rules('adult', 'ADT Count', 'trim|required|is_natural|min_length[1]|max_length[1]');
		$this->form_validation->set_rules('child', 'CHD Count', 'trim|is_natural|max_length[1]');
		$this->form_validation->set_rules('infant', 'INF Count', 'trim|is_natural|max_length[1]');
		if($this->form_validation->run() !== false ) {			
			if ($_GET['from_city'] == '' || $_GET['to_city'] =='' || $_GET['fcheckin'] =='' || $_GET['from_city'] == $_GET['to_city']) { redirect('home','refresh'); }
			if(($_GET['journey_type'] == 'ROUNDTRIP') && ($_GET['fcheckout'] == '')) { redirect('home'); }
			if($_GET['journey_type'] == 'MULTICITY') {
				for ($i=0; $i < count($_GET['multi_from_city']) ; $i++) { 
					if($_GET['multi_from_city'][$i] == ""){ redirect('home'); } 
					if($_GET['multi_to_city'][$i] == ""){ redirect('home'); }
					if($_GET['mutli_fcheckin'][$i] == ""){ redirect('home'); }
				}
			}	
			
			if (in_array($_GET['journey_type'], array('ONEWAY','ROUNDTRIP','MULTICITY'))){ }else{ redirect('home'); }	 // Check the Journey Type Validation
			if($_GET['adult'] + $_GET['child'] > 9){ redirect('home'); } // Check the Number for Traveller Count Validation			
			if($_GET['infant'] > $_GET['adult']){ redirect('home'); } // Check the Infant Count
			
			// Travel Date Validation
			$fcheckin	= date("Y-m-d",strtotime($_GET['fcheckin']));
			if($_GET['journey_type'] == 'ROUNDTRIP'){ 
				$fcheckout 	= date("Y-m-d",strtotime($_GET['fcheckout']));
				if(strtotime($fcheckout) >= strtotime($fcheckin)){}else{ redirect('home'); }
			}else{
				$fcheckout = date("Y-m-d");
				if(strtotime($fcheckin) >= strtotime($fcheckout)){}else{ redirect('home'); }
			}
			// Set Cookie for Flight Search Form
			$this->input->set_cookie('flight_search', json_encode($_GET), time()+3600);
			$search_id 				= $xml_response = $this->Flight_Model->insert_input_parameters($_GET);
			$data['input_details']	= $this->Home_Model->get_recent_search_list($search_id);
			$cityCode 				= explode(",",$_GET['from_city']);
			$data['flash_details']	= $this->Home_Model->get_flash_list(trim($cityCode[1]));
			$data['adv_details']    = $this->General_Model->get_adv_details();
			$this->load->view('flight/results',$data);
		}else {
			redirect('home');
		}
		$this->load->view('home/index');
	}
}
?>