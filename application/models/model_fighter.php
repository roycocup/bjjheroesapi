<?php
class Model_fighter extends CI_Model {
	
	public $_table = 'fighters';
	
	public function __construct(){
		parent::__construct();
	}

	static public function getInstance(){
		$class = __CLASS__;
		return new $class();
	}

	
	public function load($id){
		$this->db->where('id', $id);
		$results = $this->db->get($this->_table)->result();
		
		$object = self::getInstance();
		foreach($results[0] as $k=>$v){
			$object->set($k, $v);
		}
		
		return $object;
	}

	
	public function set($var, $value){
		$this->$var = $value;
	}
	
	
	public function updateFighter($params, $fighter_id = ''){
		$fighter = self::getInstance();
		foreach ($params as $k => $param){
			$fighter->set($k, $param);
		}
		$fighter->save($fighter_id);	
	}
	
	private function save($id = 0){
		//remove private properties
		foreach ($this as $k=>$prop){
			if ('_' == substr($k, 0,1)) continue;
			$data[$k] = $prop;	
		}
		//if id this is an update, otherwise its an insert
		if ($id){ 
			$this->db->where('id', $id);	
			$this->db->update($this->_table, $data);
			return $id;
		} else {
			$this->db->insert($this->_table, $data);
			return $this->db->insert_id();
		}
	
		
	}
	
	public function getFighterByUlr($url){
		$this->db->select('id');
		$this->db->where('url', $url);
		$results = $this->db->get($this->_table)->result();
		
		if (empty($results)) return false;
		
		$fighter = $this->load($results[0]->id);
		return $fighter;
	}


	
	public function getFullFightersList(){
		$this->db->select(array('name', 'id'));
		$results = $this->db->get($this->_table)->result_array();
		return $results;
	}
	
	
}