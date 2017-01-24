<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth
 * @author    Dipanshu Gupta
 * date 	  2017-01-24
 */

class Dashboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->model('admin');
		$this->load->library('form_validation');

		if($_SESSION['admin']['id']=='' && $_SESSION['admin']['username']=='')
		{
			redirect('admin/welcome');
		}
		
	}
	public function index()
	{
		$this->load->view('admin/add_outlets');
	}


	public function add_outlets()
	{
		if($this->input->post('submit')!='')
		{

			$this->form_validation->set_rules('outlet_id', 'Outlet ID', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			$this->form_validation->set_rules('outlet_name', 'Outlet Name', 'required');
			$this->form_validation->set_rules('address', 'Address', 'required');
			$this->form_validation->set_rules('city', 'City', 'required');
			$this->form_validation->set_rules('zip', 'Zip', 'required');
			$this->form_validation->set_rules('country', 'Country', 'required');
			$this->form_validation->set_rules('logni', 'Longitude', 'required');
			$this->form_validation->set_rules('lati', 'Latitude', 'required');
			
			if ($this->form_validation->run() == FALSE)
            {

                $this->load->view('admin/add_outlets');
            }
            else
            {

            	$base_url=$_SERVER['DOCUMENT_ROOT']."/pincare.in/pincare/public/upload/";
            	echo $base_url;
				$config['upload_path']   = $base_url; 
				$config['allowed_types'] = 'gif|jpg|png'; 
				$config['max_size']      = 100; 
				$config['max_width']     = 1024; 
				$config['max_height']    = 1024; 
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('logo')) 
				{
					$error = array('error' => $this->upload->display_errors()); 

					print_r($error);
					//$this->load->view('admin/upload_image', $error); 
				}
				else 
				{ 
					$data = array('upload_data' => $this->upload->data()); 

					print_r($data);die;


					// $data_array=array('event_id'=>$events_id,'photo'=>$data['upload_data']['file_name'],'caption'=>$_POST['caption']);
					// $this->eventmodel->saveImage($data_array);
					
				}
            }
		}
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
