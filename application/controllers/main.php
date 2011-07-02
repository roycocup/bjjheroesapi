<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class main extends CI_Controller {

	public function index(){
		//check whats the update
		//present the options
		$this->load->view('apiMenu');
	}
	
	public function updateDb(){
		$this->load->model('model_website', '', true);
		$updated = $this->model_website->update();
		print_r($updated); die;
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */