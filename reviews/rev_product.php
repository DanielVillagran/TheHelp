<?php

require_once('rev_rule.php');

class rev_product
{
	public $productid;
	public $product;
	public $status;
	public $rules;
	public $rev_products_have_rules;
		
	public function __construct(){	
		 $this->productid = null ;
		 $this->product = "";
		 $this->status = true;
		 $this->rules = array();
		 $this->rev_products_have_rules = array();		
	}

	public function __toString(){
		return "Product:".$this->product." Status:".$this->status;
	}

	public function process($productid) {
		//Get Product Rules and Type
		$this->productid = $productid;
		$this->getProductHasRulesID();
		//Get Rules For this Product
		$this->getRules();
		return $this;
	}
	
	private function getProductHasRulesID() {
		$link = $this->postgresConnect();
		$data = "Select product,id from rev_products where id = ".$this->productid;
		$result = pg_query($link, $data);
		while ($row = pg_fetch_row($result)) {
				$this->product = (object) array ('name' => $row[0], 'id' => $row[1]);
		}
		return true;
	}
	
	private function getRules() {
		$link = $this->postgresConnect();
		$data = "Select rev_rule_id, type from rev_products_have_rules where rev_product_id = ".$this->productid;
		$result = pg_query($link, $data);
		if(pg_numrows($result) == 0){
			$this->rev_products_have_rules = false;
		} else {
			while ($row = pg_fetch_row($result)) {
				$this->rev_products_have_rules[] = $row;
			}
		}

		 $rev_rule = new rev_rule();
		 foreach ($this->rev_products_have_rules as $rule){
		 	                
		                $TempRules = $rev_rule->setRule($rule);
		                if($TempRules != false){
		                     $this->rules[] = $TempRules; 
		                }
		       }
		return true;
	}
	
	//DB Connect
	private function postgresConnect(){
		$link = pg_Connect("host=tron.antfarm.mx dbname=alexandria user=postgres password=@p0stgr3s!");
		return $link;
	}

}
?>
