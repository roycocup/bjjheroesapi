<?php


class Model_website extends CI_Model{

	public function getFighters(){
		$t = $this->db->get('fighters')->result();
		print_r($t);die;
	}
	
}