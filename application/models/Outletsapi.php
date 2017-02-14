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

	
	function search_outlets($place_id)
	{

		//$this->db->select('tbl_outlets.outlet_name,tbl_outlets.address,tbl_outlets.city,tbl_outlets.country,tbl_outlets.zip,tbl_outlets.longitude,tbl_outlets.latitude,tbl_outlets.fb_page_id,tbl_outlets.search_params,tbl_outlets.logo,tbl_pins.checkin_per_day,tbl_pins.amount_checkin')
		//->from('tbl_outlets')
		//->join('tbl_pins','tbl_pins.outlet_id=tbl_outlets.id');
				
		$this->db->select('tbl_outlets.outlet_name,tbl_outlets.address,tbl_outlets.city,tbl_outlets.country,tbl_outlets.zip,tbl_outlets.longitude,tbl_outlets.latitude,tbl_outlets.search_params,tbl_outlets.logo,tbl_pins.checkin_per_day,tbl_pins.amount_checkin,tbl_outlets.place_id');
		$this->db->from('tbl_outlets');
		$this->db->join('tbl_pins', 'tbl_pins.outlet_id=tbl_outlets.id');
		$this->db->where_in('tbl_outlets.place_id',explode(",",$place_id));
		$q=$this->db->get();
		//print_r($this->db->last_query());die; 
		 
		if($q->num_rows()>0)
		{
			return $q->result_array();
		}
	}


	function get_story()
	{
		$this->db->select('*');
		$q=$this->db->get('tbl_story');
		if($q->num_rows()>0)
		{
			return $q->result_array();
		}
	}

}
