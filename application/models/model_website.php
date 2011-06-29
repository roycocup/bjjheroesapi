<?php


class Model_website extends CI_Model{

	public function getUsers(){
		$t = $this->db->get('users');
		var_dump($t);die;
	}
	
}