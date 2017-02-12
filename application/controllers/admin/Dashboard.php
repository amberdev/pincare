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
			$this->form_validation->set_rules('fb_page_id', 'Facebook Page ID', 'required');
			$this->form_validation->set_rules('search_params', 'Search Within', 'required');
			if ($this->form_validation->run() == FALSE)
            {

                $this->load->view('admin/add_outlets');
            }
            else
            {

            	$base_url=$_SERVER['DOCUMENT_ROOT']."/public/upload/";
            	
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
						$logo_name="pincare.in/public/upload/".$data['upload_data']['file_name'];
						 
						$outlet_id=$this->input->post('outlet_id');
						$password=$this->input->post('password');
						$outlet_name=$this->input->post('outlet_name');
						$address=$this->input->post('address');
						$city=$this->input->post('city');
						$zip=$this->input->post('zip');
						$country=$this->input->post('country');
						$longitude=$this->input->post('logni');
						$latitude=$this->input->post('lati');
						$fb_page_id=$this->input->post('fb_page_id');
						$search_params=$this->input->post('search_params');
						$post_data=array('login_id'=>$outlet_id,'password'=>$password,'outlet_name'=>$outlet_name,'address'=>$address,'city'=>$city,'country'=>$country,'zip'=>$zip,'longitude'=>$longitude,'latitude'=>$latitude,'fb_page_id'=>$fb_page_id,'search_params'=>$search_params,'logo'=>$logo_name);
						$this->admin->add_outlets($post_data);
						
						redirect('admin/dashboard/add_outlets');
				}
            }
		}
		$this->load->view('admin/add_outlets');
	}

	public function manage_pins()
	{
		if($this->input->post('update')!='')
		{
			$this->form_validation->set_rules('all_outlets', 'Outlet Name', 'required');
			$this->form_validation->set_rules('chk_per_day', 'Password', 'required');
			$this->form_validation->set_rules('amnt_per_day', 'Outlet Name', 'required');
			if ($this->form_validation->run() == FALSE)
            {

                $this->load->view('admin/manage_pins');
            }
            else
            {
				$outlets=$this->input->post('all_outlets');
				$check_per_day=$this->input->post('chk_per_day');
				$amnt_per_day=$this->input->post('amnt_per_day');

				$data=array('outlet_id'=>$outlets,'checkin_per_day'=>$check_per_day,'amount_checkin'=>$amnt_per_day);
				$this->admin->add_manage_pins($data);
				redirect('admin/dashboard/add_outlets');
            }
		}

		$out['outlets']=$this->admin->all_outlets();
		$this->load->view('admin/manage_pins',$out);
	}


	public function add_story()
	{
		if($this->input->post('add')!='')
		{
			$this->form_validation->set_rules('title', 'Title', 'required');		
			$this->form_validation->set_rules('imageurl', 'Title', 'required');
			$this->form_validation->set_rules('description', 'Description', 'required');

			if ($this->form_validation->run() == FALSE)
            {

                $this->load->view('admin/manage_pins');
            }
            else
            {
            	$title=$this->input->post('title');
            	$imageurl=$this->input->post('imageurl');
            	$desc=$this->input->post('description');

            	$base_url=$_SERVER['DOCUMENT_ROOT']."/public/upload/";
            	
				$config['upload_path']   = $base_url; 
				$config['allowed_types'] = 'gif|jpg|png'; 
				// $config['max_size']      = 500; 
				// $config['max_width']     = 1024; 
				// $config['max_height']    = 1024; 
				$this->load->library('upload', $config);

				if (!$this->upload->do_upload('story')) 
				{
					$error = array('error' => $this->upload->display_errors()); 
					print_r($error);
					//$this->load->view('admin/upload_image', $error); 
				}
				else 
				{
					$data = array('upload_data' => $this->upload->data()); 
					$story_name="pincare.in/public/upload/".$data['upload_data']['file_name'];

					$data=array('story_image'=>$story_name,'title'=>$title,'imageurl'=>$imageurl,'description'=>$desc);
					$this->admin->add_story($data);
					redirect('admin/dashboard/add_story');
				}
            }
		}
		$this->load->view('admin/add_story');
	}
}
