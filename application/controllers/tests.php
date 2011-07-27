<?php

class Tests extends CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library('xmlrpc');
		$server_url = site_url('main');
		$this->xmlrpc->server($server_url, 80);
		$this->xmlrpc->set_debug(false);
	}

	public function index(){
		$this->xmlrpc->method('getFullList');

		$request = array('How is it going?');
		$this->xmlrpc->request($request);

		if ( ! $this->xmlrpc->send_request())
		{
			echo $this->xmlrpc->display_error();
		}
		else{
			print_r($this->xmlrpc->display_response());
		}
	}
}
?>