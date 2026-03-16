<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Asistencias_Validas_Model extends ANT_Model
{

	protected static $table = 'asistencias_validas';
	protected static $uk = array('id');

	function __construct()
	{
		parent::__construct();
	}
}
