<?php
if (!defined('BASEPATH'))    exit('No direct script access allowed');

/**
 * Auth
 * @author    Dipanshu Gupta
 * date 	  2017-01-24
 */

class Usersapi extends CI_Model
{

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function login_check($data)
	{
		if(!empty($data))
		{
			$this->db->where('fb_id',$data['fb_id']);
			$q=$this->db->get('tbl_users');
			if($q->num_rows()==0)
			{
				$token=sha1($data['email'].$data['fb_id']);
				$data['token']=$token;
				$this->db->insert('tbl_users',$data);
				$return_array['toekn']=$token;
				$return_array['user_id']=$this->db->insert_id();
				return $return_array;
			}
			else
			{
				$data['modified_on']=@date('Y-m-d h:i:s');
				$token=sha1($data['email'].$data['fb_id']);
				$data['token']=$token;
				$this->db->where('fb_id',$data['fb_id']);
				$this->db->update('tbl_users',$data);

				$return_array['toekn']=$token;
				$this->db->where('fb_id',$data['fb_id']);
				$q=$this->db->get('tbl_users');
				$user_data=$q->result_array();
				$return_array['user_id']=$user_data[0]['id'];
				return $return_array;
			}
		}
	}

	function checkAuth($token)
	{
		if($token!='')
		{
			$this->db->where('token',$token);
			$q=$this->db->get('tbl_users');
			if($q->num_rows()>0)
			{
				return true;
			}
		}
	}

}