<?php
class ANT_Model extends CI_Model
{
	/**
	 * $table
	 * Sets name of the linked table
	 * @var string
	 */
	protected static $table = null;
	/**
	 * $uk
	 * Sets unique keys
	 * @var array
	 */
	protected static $uk = array();
	/**
	 * $required
	 * Sets required fields
	 * @var array
	 */
	protected static $required = array();
	public $inserted = null;
	public $insertedBy = null;
	public $modified = null;
	public $modifiedBy = null;
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	/**
	 * _required method returns false if the $data array does not contain all of the keys assigned by the $required array.
	 *
	 * @param array $required
	 * @param array $data
	 * @return bool
	 */
	function _required($required, $data)
	{
		foreach ($required as $field)
		{
			if (!isset($data[$field]))
				return false;
		}
		return true;
	}

	/**
	 * _default method combines the options array with a set of defaults giving the values in the options array priority.
	 *
	 * @param array $defaults
	 * @param array $options
	 * @return array
	 */
	private static function _default($defaults, $options)
	{
		//echo "options:".print_r($options,1).">>>";
		return array_merge($defaults, $options);
	}

	/**
	 * Load method returns an array of qualified record objects
	 *
	 * table:			the name of the table
	 * clauses:			an associative array containing the clauses (i.e. "user"=>"john")
	 * limit:			limits the number of returned records
	 * offset:			how many records to bypass before returning a record (limit required)
	 * sortBy:			determines which column the sort takes place
	 * sortDirection:	(asc, desc) sort ascending or descending (sortBy required)
	 *
	 * Returns (array of objects)
	 *
	 * @param array $options
	 * @return array result()
	 */
	public static function Load($options = array())
	{
		$self = new static();
		//$options=(object)$params;
		$table = static::$table;
		if (is_int($options) || is_string($options))
		{
			$query = $self->db->get_where($table, array('id' => $options));
			return $query->row(0,get_class($self));
		}
			//Select 	
	    if (isset($options["select"]))
		{
		       $self->db->select($options["select"]);
		}
		
		if (isset($options["table"]))
		{
			$table = $options["table"];
		}
		// default values
		$options = self::_default(array('sortDirection' => 'asc'), $options);
		// If limit / offset are declared (usually for pagination) then we need to take them into account
		if (isset($options['limit']) && isset($options['offset']))
		{
			$self->db->limit($options['limit'], $options['offset']);
		}
		else
		if (isset($options['limit']))
		{
			$self->db->limit($options['limit']);
		}
		// sort
		if (isset($options['sortBy']))
		{
			if (is_array($options['sortBy']))
			{
				foreach ($options['sortBy'] as $field=>$direction)
				{
					$self->db->order_by($field, $direction);
				}
			}
			else
			{
				$self->db->order_by($options['sortBy'], $options['sortDirection']);
			}
		}
        // like
		if (isset($options['like']))
		{
			$self->db->like($options['like']);
		}
	        // or_like
		if (isset($options['or_like']))
		{
			$self->db->or_like($options['or_like']);
		}
		
		        // distinct
		if (isset($options['distinct']))
		{
			$self->db->distinct();
		}
		        // ilike
		if (isset($options['ilike']))
		{
			$self->db->ilike($options['ilike']);
		}
			// group
		if (isset($options['groupBy']))
		{
			$self->db->group_by($options['groupBy']);
		}
		
		//WHERE clauses
		$clauses = array();
		if (isset($options["clauses"]))
		{
			$clauses = $options["clauses"];
		}
		foreach ($clauses as $clause => $value)
		{
					if ($clause == 'in' || $clause == 'not_in')
			{
				if (is_array($value))
				{
					foreach ($value as $keyin => $valuesin)
					{
						if ($clause == 'not_in')
						{
							$self->db->where_not_in($keyin, $valuesin);
						}
						else
						{
							$self->db->where_in($keyin, $valuesin);
						}
					}
				}
			}
			elseif ($clause == 'or')
			{
				if (is_array($value))
				{
					foreach ($value as $keyor => $valueor)
					{
						$self->db->or_where($keyor, $valueor);
					}
				}
			}
			else
			{
				$self->db->where($clause, $value);
			}
		}
			//WHERE where
		if (isset($options["where"]))
		{
			$self->db->where($options["where"]);
			
		}
		//Join 
		$joins = array();
		if (isset($options["joins"]))
		{
			$joins =$options["joins"];
		}
		if(isset($options['joinsLeft']))
		{
			foreach ($options['joinsLeft'] as $tableJoin => $whereJoin)
			{
				$self->db->join($tableJoin,$whereJoin,'LEFT');
			}
		}
		else
		{
			foreach ($joins as $tableJoin => $whereJoin)
			{
				$self->db->join($tableJoin,$whereJoin);
			}
		}
		
		//perform select
		//echo "Will result with this class: ".get_class($self);exit;
		$query = $self->db->get($table);
		if ($query->num_rows() == 0)
		{
			return false;
		}
		//Unique identification
		//echo ">>".print_r(static::$uk,1).">>".print_r($clauses,1);
		//echo "Will result with this class: ".get_class($self);
		$uniqueKeys = static::$uk;
		foreach ($uniqueKeys as $uk)
		{
			if (isset($clauses[$uk]))
			{
				//echo "Will result with this class: ".get_class($self);exit;
				return $query->row(0,get_class($self));
			}
		}
		//echo "Will result with this class: ".get_class($self);exit;
		if(isset($options["result"])){
			 if($options["result"]=="array"){
			 	 return $query->result_array(get_class($self));
			 }elseif($options["result"]=="1row"){
			 	 return $query->row(0,get_class($self));
			 }else{
			 	return $query->result(get_class($self));
			 }
			
		}else{
		        return $query->result(get_class($self));
		}
	}

	public static function Insert($options, $insert_batch = false)
	{
		$self = new static();
		$table = static::$table;
		if($insert_batch ==true){
			 $result=$self->db->insert_batch($table, $options);
		}else{
		        $result=$self->db->insert($table, $options);
		}
		if($result===true or $result >= 1)
		{
				$return['result'] = true;
				$return['insert_id'] = $self->db->insert_id();
				return $return;
		}
		if ($error = $self->db->_error_message())
		{
			//file_put_contents("textlogger.txt", "tate".$error."\n", FILE_APPEND);
		    $return['result'] = false;
			$return['error'] = $error;
			return $return;
		}
		$return['error'] = 'Error desconocido';
		return $return;
	}

	public static function Update($options, $where, $batch =  false, $batchWhere = false)
	{
		$self = new static();
		$table = static::$table;
	    if($batch ==true){
	    	 if($batchWhere==false){
	    	    if( $result= $self->db->update_batch($table, $options, $where)){ 
			            $return['result'] = true;
			            return $return;
			     }
	    	 }else{
		     	 if( $result= $self->db->update_batch($table, $options, $where['index'],$where['where'])){ 
			            $return['result'] = true;
		  	            return $return;
			     }
	    	 }
			     $return['result'] = true;
			     return $return;
		}else{
		    if( $result=$self->db->update($table, $options, $where)){
		    				     $return['result'] = true;
			     return $return;
		    }
		}
		if ($error = $self->db->_error_message())
		{
			//file_put_contents("textlogger.txt", "tate".$error."\n", FILE_APPEND);
		    $return['result'] = false;
			$return['error'] = $error;
			return $return;
		}
		$return['result'] = false;
		$return['error'] = $result;
		return $return;
	}
	public static function Delete($options)
	{
		$self = new static();
		$table = static::$table;
		if($self->db->delete($table, $options)){
			$return['result'] = true;
			return $return;
		}		
		if ($error = $self->db->_error_message())
		{
			//file_put_contents("textlogger.txt", "tate".$error."\n", FILE_APPEND);
		    $return['result'] = false;
			$return['error'] = $error;
			return $return;
		}
		$return['error'] = 'Error desconocido';
		return $return;
	}	
	public static function Empty_table()
	{
		$self = new static();
		$table = static::$table;
		if($self->db->empty_table($table)){
			$return['result'] = true;
			return $return;
		}		
		if ($error = $self->db->_error_message())
		{
			//file_put_contents("textlogger.txt", "tate".$error."\n", FILE_APPEND);
		    $return['result'] = false;
			$return['error'] = $error;
			return $return;
		}
		$return['error'] = 'Error desconocido';
		return $return;
	}

	//TODO acomodar de aqui en adelante
	/**
	 * AddUser method creates a record in the users table.
	 *
	 * Option: Values
	 * --------------
	 * userEmail            (required)
	 * userPassword
	 * userName
	 * userStatus        active(default), inactive, deleted
	 *
	 * @param array $options
	 */
	function AddUser($options = array())
	{
		// required values
		if (!$this->_required(array('userEmail'), $options))
			return false;
		// default values
		$options = $this->_default(array('userStatus' => 'active'), $options);
		// qualification (make sure that we're not allowing the site to insert data that it shouldn't)
		$qualificationArray = array(
			'userEmail',
			'userName',
			'userStatus'
		);
		foreach ($qualificationArray as $qualifier)
		{
			if (isset($options[$qualifier]))
				$this->db->set($qualifier, $options[$qualifier]);
		}
		// MD5 the password if it is set
		if (isset($options['userPassword']))
			$this->db->set('userPassword', md5($options['userPassword']));
		// Execute the query
		$this->db->insert('users');
		// Return the ID of the inserted row, or false if the row could not be inserted
		return $this->db->insert_id();
	}

	/**
	 * UpdateUser method alters a record in the users table.
	 *
	 * Option: Values
	 * --------------
	 * userId            the ID of the user record that will be updated
	 * userEmail
	 * userPassword
	 * userName
	 * userStatus        active(default), inactive, deleted
	 *
	 * @param array $options
	 * @return int affected_rows()
	 */
	function UpdateUser($options = array())
	{
		// required values
		if (!$this->_required(array('userId'), $options))
		{
			return false;
		}
		// qualification (make sure that we're not allowing the site to update data that it shouldn't)
		$qualificationArray = array(
			'userEmail',
			'userName',
			'userStatus'
		);
		foreach ($qualificationArray as $qualifier)
		{
			if (isset($options[$qualifier]))
			{
				$this->db->set($qualifier, $options[$qualifier]);
			}
		}
		$this->db->where('userId', $options['userId']);
		// MD5 the password if it is set
		if (isset($options['userPassword']))
			$this->db->set('userPassword', md5($options['userPassword']));
		// Execute the query
		$this->db->update('users');
		// Return the number of rows updated, or false if the row could not be inserted
		return $this->db->affected_rows();
	}

	//TODO Acomodar
	/**
	 * DeleteUser method removes a record from the users table
	 *
	 * @param array $options
	 */
	function DeleteUser($options = array())
	{
		// required values
		if (!$this->_required(array('userId'), $options))
			return false;
		$this->db->where('userId', $options['userId']);
		$this->db->delete('users');
	}

	static function Truncate($cascade = false)
	{
		$self = new static();
		$table = static::$table;
		$cascade = ($cascade == true)?' CASCADE':'';
		if ($self->db->truncate($table . $cascade))
		{
			$return['result'] = true;
			return $return;
		}
		if ($error = $self->db->_error_message())
		{
			$return['result'] = false;
			$return['error'] = $error;
			return $return;
		}
		$return['result'] = false;
		$return['error'] = 'Error desconocido';
		return $return;
	}

    static function Query($sql = '', $params = false)
    {
        $self = new static();
        if (!empty($sql) && is_string($sql))
        {
            $result = $self->db->query($sql, $params);
            if (FALSE!==stripos($sql,'update') || FALSE!==stripos($sql,'insert') || FALSE!==stripos($sql,'delete'))
            {

                return TRUE;
            }
            else
            {
                if ($result)
                {
                    return $result->result_array($self);
                }
                else
                {
                    return array();
                }

            }
        }
        return FALSE;
    }

}
