<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth
 * @author    Dipanshu Gupta
 * date 	  2017-01-24
 */

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('admin');
		$this->load->library('form_validation');

		if(isset($_SESSION['admin']['id']) && isset($_SESSION['admin']['username']))
		{
			redirect('admin/dashboard');
		}
		
	}
	public function index()
	{
		$this->load->view('admin/login');
	}

	public function login()
	{
		if($this->input->post('submit')!='')
		{
			$password=$this->input->post('password');

			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			if ($this->form_validation->run() == FALSE)
            {
                $this->load->view('admin/login');
            }
            else
            {
            	$username=$this->input->post('username');
            	$password=$this->input->post('password');
            	$return=$this->admin->admin_login($username,$password);
            	if($return)
            	{
            		$_SESSION['admin']['id']=$return[0]['id'];
            		$_SESSION['admin']['username']=$return[0]['username'];
            		redirect('admin/dashboard');
            	}
            }
		}
	}
}
