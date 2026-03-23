<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Tickets_History_Model extends ANT_Model
{

	protected static $table = 'tickets_history';
	protected static $uk = array('id');

	function __construct()
	{
		parent::__construct();
	}
}
