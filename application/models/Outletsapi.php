<?php
if (!defined('BASEPATH'))    exit('No direct script access allowed');

/**
 * Auth
 * @author    Dipanshu Gupta
 * date 	  2017-01-24
 */

class Outletsapi extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	
	function search_outlets()
	{
		$this->db->select('*');
		$q=$this->db->get('tbl_outlets');
		 
		if($q->num_rows()>0)
		{
			return $q->result_array();
		}
	}

}