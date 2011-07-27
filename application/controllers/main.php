<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('model_website', '', true);
		$this->load->model('model_fighter', '', true);
	}
	
	

	function index(){
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$config['functions']['update'] = array('function' => 'Main.updateDB');
		$config['functions']['getFullList'] = array('function' => 'Main.getFullList');
		$this->xmlrpcs->initialize($config);
		$this->xmlrpcs->serve();
	}

	public function updateDb(){
		$updated = $this->model_website->update();
	}


	public function getFullList($request = null){
		$results = $this->model_fighter->getFullFightersList();
		foreach ($results as $result){
			$list[] = array($result, 'struct');
		}
		//print_r($list); die;
		if (@$request) $parameters = $request->output_parameters();
		$response = array($list,'struct');
		return $this->xmlrpc->send_response($response);
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */