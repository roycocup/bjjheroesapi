<?php


class Model_website extends CI_Model{

	protected $_index_table = 'website_index';
	
	public $fighterPages = array();

	public function update(){

		$this->_updateWebsiteIndex($this->config->item('bjjHeroesDbUrl'));
		$string = $this->_getWebsiteIndex();

		//get all the strings with the address of the fighters database only
		$linkMask = '/\<a[\s]+href\=[\'|\"]([htp\:\w\.\/]+\-fighters[\w\d\/\-]+)/';		
		//get all the fighters pages
		preg_match_all($linkMask, $string->website_index, $matches);
		//do a little shortcut
		$fightersPage = $matches[1];
		//free space
		unset($matches);

		$counter = 0;
		//iterate all fighters page
		foreach ($fightersPage as $key => $value){
			$fighter = array();
			$this->load->model('model_fighter');
			$fighter = $this->model_fighter->getFighterByUlr($value); 
			
			//do we need to update?
			$fullPage = file_get_contents($value);
			$dataSignature = md5($fullPage);
			
			//only update if fighter doesnt exist, the signature is different or UPDATEALL constant is true
			if (!$fighter || $dataSignature != $fighter->dataSignature || UPDATEALL){
				$data['url'] 			= $value;
				$data['full_page'] 		= $fullPage;
				$data['dataSignature'] 	= $dataSignature;
				
				//if we already had a fighter update that id
				if ($fighter){
					$this->model_fighter->updateFighter($data, $fighter->id);
					if(CLI){print("Updating fighter ".$fighter->id."\n\r"); ob_flush();}
					//get the new updated fighter
					$fighter = $this->model_fighter->load($fighter->id);
				} else {
					//if we dont have a fighter create a new one
					$this->model_fighter->updateFighter($data);
					$fighter = $this->model_fighter->getFighterByUlr($value);
					if(CLI){print("Created new fighter ".$fighter->id."\n\r");ob_flush();}
				}
				
				//Get some info about this fighter
				$data['name'] = $this->_getFighterName($fighter);
				$data['weight_category'] = $this->_getFighterWeight($fighter);
				//save the info conlected
				$this->model_fighter->updateFighter($data, $fighter->id);
				
				//load the fighter so we can print it back to the screen
				$fighter = $this->model_fighter->load($fighter->id);
				
				if(CLI){print("Fighter ".$fighter->name. "is in the db"."\n\r");ob_flush();}
				
				
			} 
			
			//print_r($fighter); die;
			
			/*
			 //glue them together and form the mask
			$words = implode('|', $lookupWords);
			$mask = "/[\.\w\s]{0,30}\s($words)[\s\w\'\-\/\:\,\<\>\=\"\.\\\]+\./";
			//Search the $lookup words in this string
			preg_match_all($mask, $string, $matches);

			//lets do a string with the results and the name of the page as a heading
			$result = "===".$value."==\n\r\n\r";
			foreach ($matches[0] as $k=>$v){
				$result .= $v."\n\r";
			}


			if($matches[0]){
				//if the array is not empty, put it on the file
				file_put_contents($directory."results.txt", $result, FILE_APPEND);
			}

			print "$value \n\r";
			ob_flush();
			//if ($counter == 50) break;
			 */
		}
		die('done');
	}
	
	protected function _getFighterName($fighter){
		$string = $fighter->full_page;
		$mask = '/title="Permanent Link to ([\x{0030}-\x{007f}\�\�\�\�\�\�\�\�\�\�\�\�\�\�\�\w\s\d\-\(\)\&\#\;]*)/';
		preg_match_all($mask, $string, $matches);
		return $matches[1][0];
	} 

	protected function _getWebsiteIndex(){
		$this->db->select('website_index');
		$result = $this->db->get($this->_index_table)->result(); 
		return $result[0];
	}
	
	protected function _updateWebsiteIndex(){
		$index = file_get_contents($this->config->item('bjjHeroesDbUrl'));
		$now = new DateTime();
		
		$this->db->select('modified');
		$result = $this->db->get($this->_index_table)->result();
		
		if (empty($result)){
			$data = array('website_index'=>$index, 'modified'=>$now->format('d-m-Y h:i:s'));
			$this->db->insert($this->_index_table, $data);
			return true;
		}
		
		$dbmodified = new DateTime($result[0]->modified);
		
		if ($dbmodified < $now){
			$data = array('website_index'=>$index, 'modified'=>$now->format('d-m-Y h:i:s'));
			$this->db->update($this->_index_table, $data);
			return true;
		}
		
		die('done');
	}
	
	protected function _getFighterWeight($fighter){
		$string = $fighter->full_page;
		//$mask = '/(w|W)eigh(t|)\s(C|c)ategory[\w\d\s\(\)]*(\:|)[\w\d\s\<\/]*\>([\xL\w\s\d\(\)\/\"�������]*)/';
		$mask = '/(w|W)eigh(t|)\s(C|c)ategory[\w\d\s\(\)\"\<\>\.]*.*/';
		preg_match_all($mask, $string, $matches);
		if (!$matches[0][0] or empty($matches[0][0])){
			$mask = '/(w|W)eigh(t|)\s(D|d)ivision[\w\d\s\(\)\"\<\>\.]*.*/';
			preg_match_all($mask, $string, $matches);
		}
		//print_r($matches[0]); die;
		return $matches[0][0];
	}
	
	
	
	
}