<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller{
	
	
	public function main(){
		$this->load->model('model_website');
		$this->load->view('main');
	}//end of function
	

}
