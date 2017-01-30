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

	function add_outlets($data)
	{
		$this->db->insert('tbl_outlets',$data);
	}

	function add_story($data)
	{
		$this->db->insert('tbl_story',$data);
	}

	function all_outlets()
	{
		$this->db->select('*');
		$q=$this->db->get('tbl_outlets');
		if($q->num_rows()>0)
		{
			return $q->result_array();
		}
	}

	function add_manage_pins($data)
	{
		// if($data['outlet_id'])
		$this->db->where('outlet_id',$data['outlet_id']);
		$q=$this->db->get('tbl_pins');
		if($q->num_rows()>0)
		{
			$this->db->where('outlet_id',$data['outlet_id']);
			$this->db->update('tbl_pins',$data);
		}
		else
		{
			$this->db->insert('tbl_pins',$data);	
		}
	}
}