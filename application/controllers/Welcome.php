<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	 function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android')) {
  		// echo "You are running a mobile webbrowser!";die;
		header('Location: http://m.pincare.in/');
}
	}

	public function index()
	{
		//echo $_SERVER['HTTP_USER_AGENT'];die;
		$this->load->view('home');
	}
}
