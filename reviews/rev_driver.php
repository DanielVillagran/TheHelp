<?php

require_once('rev_product.php');
require_once('rev_evaluator.php');

class rev_driver{

	public $obj;
	public $products;
	public $resultJSON;
	public $result;
	private $revision_id;
	private $logger;
	private $Description;
	private $user_data;

	public function __construct()
	{
		$this->obj = "";
		$this->products = array();
		$this->result = false;
		$this->resultJSON = "";
		$this->Description = "";
		$this->revision_id = 0;
		$this->user_data = array();
		//$this->logger = new logger;
	}

	public function process($obj, $array_data = array()){
		$this->obj = $obj;
		//Get Product
		$product= $obj->product;
		//data for user validations
		if($product == 'user' || $product == 'agency' || $product == 'endorsement'){
			$this->user_data = $array_data;
		}

		//getProducts that should be reviewed with logic ($ProductID)
		$this->getProducts($product); 
		//Evaluate Rules (All Types)
		$this->evaluateRules();
		
		//Set results to JSON Response
		$this->setJSON($this->products);
		return $this->resultJSON;
		
	}
	
	public function getProducts($product){
		// Go to DB and get products that match.
		$rev_product = new rev_product;
		$link = $this->postgresConnect();
		$data = "select rev_products.id as product_id, rev_products.product as product, rev_products_logic.id as productlogic_id, rev_products_logic.logic as productlogic from rev_products inner join rev_products_logic on rev_products_logic.rev_product_id = rev_products.id where product = '".$product."'";
		
		//Debug Section
		/*if($this->obj->debug){
			echo $data ."</br>";
		}*/
		
		$result = pg_query($link, $data);
		if(pg_numrows($result) == 0){
			$TempLogic = false;
		} else {
			$TempLogic = array();
			while ($row = pg_fetch_row($result)) {
				$TempLogic[] = $row;
			}
		}
		
		//Find if the products should be validated
		foreach ($TempLogic as $product_logic) {          
		      eval($product_logic[3]);
		      if($result == true){
		      			$TempProcess = $rev_product->process($product_logic[2]);
		                $this->products [] = (object) array(
                                'product' => $TempProcess->product,
                                'rules' => $TempProcess->rules,
		                        'seccion' => $TempProcess->product->name
                              );
		      }
        }
        
        //Debug Section 
        /*if($this->obj->debug){
        	var_dump($this->products);
        }*/
        
		return true;
	}
	
	public function evaluateRules(){
		//Call Rev Evaluator and eval the rules  
		//return TRUE o FALSE, assigned to $this->result = true;
		$rev_evaluator = new rev_evaluator;
        $rev_evaluator->products= $this->products;
        if($this->obj->product == 'user' || $this->obj->product == 'endorsement' || $this->obj->product == 'agency'){
        	$this->result = $rev_evaluator->evaluateRules($this->obj, $this->user_data);
        }else{
        	$this->result = $rev_evaluator->evaluateRules($this->obj);	
        }        
        $this->revision_id = $rev_evaluator->revision_id;
		return true;
	}
	
	private function setJSON(){
		$this->resultJSON = (object) array(
		                           'Result' => $this->result,
		                           'Revisions' => ''
		                           );
		//Get Reviewed Products

	    foreach ($this->products as $product) {
	    	//Get Rules for that Review
        	foreach ($product->rules as $rule) {    
        			$link = $this->postgresConnect();

        			$data = "Select rule_identifier, message, description, type from rev_revisions ". 
							"inner join rev_revision_detail on rev_revision_detail.rev_revision_id = rev_revisions.id ". 
							"inner join rev_rules on rev_revision_detail.rev_rule_id = rev_rules.id ". 
							"inner join rev_products_have_rules on rev_revisions.rev_product_id = rev_products_have_rules.rev_product_id  and rev_rules.id = rev_products_have_rules.rev_rule_id ".
							"where rev_revision_id = ".$this->revision_id." and rev_rules.id = ".$rule[0];
        			
        			//Debug Section
        			/*if($this->obj->debug){
        				echo $data ."</br>";
        			}*/

        			$result = pg_query($link, $data);

                    if(pg_numrows($result) == 0){
                    	//return "";
                    } else {
                         	while ($row = pg_fetch_row($result)) {
                         		$this->resultJSON->Revisions[] = (object) array(
	                                             'RuleIdentifier' => $row[0],
	                                             'Message' => $row[1],
	                                             'Description' => $row[2],
	                                             'type' => $row[3]
	                                            ); 
                         }
                 	}

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