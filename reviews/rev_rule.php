<?php


class rev_rule
{
	public $ruleid;
	public $rev_rules;
	public $message;
	
	public function __construct(){	
		 $this->ruleid = null ;
		 $this->rev_rules = array();
		 $this->message = "";
		
	}

	public function __toString(){
		return $this->message;
	}

	public function setRule($rule) {
		 //Get Rule
         $this->rev_rules = array();
         $TempRule = '';
		 
         $link = $this->postgresConnect();
         $data = "Select * from rev_rules where id = ".$rule[0]." and to_char(expiration_date, 'yyyymmdd')::numeric > ".date("Ymd")." AND to_char(effective_date, 'yyyymmdd')::numeric < ".date("Ymd");
         $result = pg_query($link, $data);
         if(pg_numrows($result) == 0){
         	$this->rev_products_have_rules = false;
         } else {
         	while ($row = pg_fetch_row($result)) {
         		$TempRule  = $row;
         		$TempRule['type'] = $rule[0];
         	}
         }
        $this->rev_rules =  $TempRule;
		return $this->rev_rules;
	}
	
	//DB Connect
	private function postgresConnect(){
		$link = pg_Connect("host=tron.antfarm.mx dbname=alexandria user=postgres password=@p0stgr3s!");
		return $link;
	}

}
?>
