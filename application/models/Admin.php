<?php
if (!defined('BASEPATH'))    exit('No direct script access allowed');

/**
 * Auth
 * @author    Dipanshu Gupta
 * date 	  2017-01-24
 */

class Admin extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function admin_login($username,$password)
	{
		if(isset($username) && isset($password))
		{
			$this->db->where('username',$username);
			$this->db->where('password',md5($password));
			$q=$this->db->get('tbl_admin');
			if($q->num_rows()>0)
			{
				return $q->result_array();
			}
		}
	}


}