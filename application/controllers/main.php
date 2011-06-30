<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class main extends CI_Controller {

	public function index(){
		//$this->load->view('main');
		$this->load->model('model_website', '', true);
		$this->model_website->getFighters();
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */