<?php 
class rev_evaluator{

	public $products;
	public $doc;	
	private $status;
	private $rules_fired;
	private $rules_checked;
	public $revision_id;
	private $logger;
	public $result;
	
	public function __construct()
	{
		$this->obj = "";
		$this->products = array();
		$this->status = 'false';
		$this->result = false;
		$this->rules_fired = 0;
		$this->rules_checked = 0;
		$this->revision_id = 0;
		
	}

	public function evaluateRules($obj, $array_data = array()){
		$this->obj = $obj;
		//Get Products, Then Get Rules... and Evaluate
	    foreach ($this->products as $product) {
	    	
        	foreach ($product->rules as $rule) {
        		
        		$this->rules_checked++;  
        		//Run Script , Log only rules fired                       
                if($this->runRule($rule[1], $array_data)){
                   $this->result = true;
                   $this->rules_fired++;	
                   $this->status = 'true';
                   $revision_details[] = array (
                                               'rev_rule_id' => $rule[0],
                                               'status' => 'true',
                                               'rule_identifier' => $rule[3],
                                               'message' => $rule[4],
                                               'description' => $rule[2],
                                               'modifiedby' => '1'
                                              );
                }
                         
        	}

        	//Insert Review
        	$revisions  = array(
        			'rev_product_id' => $product->product->id,
        			'status' => $this->status,
        			'order' => '',
        			'rules_fired' => $this->rules_fired,
        			'rules_checked' => $this->rules_checked,
        			'modifiedby' => '1'
        	);
        	
        	$link = $this->postgresConnect();
        	$data = "insert into rev_revisions (rev_product_id, status, \"order\", rules_fired, rules_checked, rule_type, modifiedby, created, modified) values ( "
        		.$revisions['rev_product_id']
        		.",". $revisions['status'] .",'',".$revisions['rules_fired'].",". $revisions['rules_checked'].",0,1,'".date('Y-m-j h:i:s')."','".date('Y-m-j h:i:s')."') ";
        	
        	$result = pg_query($link, $data);
        	
        	$data = "SELECT currval('rev_revisions_id_seq') AS lastinsertid;";
        	$result = pg_query($link, $data);
        	if(pg_numrows($result) == 0){
        		return false;
        	} else {
        		while ($row = pg_fetch_row($result)) {
        			$rev_revisions['insert_id'] = $row[0];
        		}
        	}
        	
           
           if($this->result == true && $this->rules_fired >= 1 ){
			      $this->revision_id = $rev_revisions['insert_id'];
			      foreach ($revision_details as $clave){
			      		$data = "insert into rev_revision_detail (rev_revision_id, rev_rule_id, status, modifiedby, created, modified) values (".
			      				$this->revision_id.",".$clave['rev_rule_id'].",".$clave['status'].",1,'".date('Y-m-j h:i:s')."','".date('Y-m-j h:i:s')."') ";
				        $result = pg_query($link, $data);
			            
			      }     	
		  		}                     
		}               

		return $this->result;
	}
		
	private function runRule($script, $data = array()){
		$script = 'require_once BASEPATH."../reviews/rev_rule_functions.php";'.$script;
		eval($script);
		//Run Script 
		//Eject against JSON
		//Return Result from script ... True or False
		return  $result;
	}
	
	//DB Connect
	private function postgresConnect(){
		$link = pg_Connect("host=tron.antfarm.mx dbname=alexandria user=postgres password=@p0stgr3s!");
		return $link;
	}
	
}

?>