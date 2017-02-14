<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Outlets extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // $this->load->model('outlets');
        $this->load->model('outletsapi');
        $this->load->model('usersapi');
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }


    public function outlets_post()
    {
        // Users from a data store e.g. database

        // $postData='{"latitude":"28.6266412","longitude":"77.3848031","token":"2fee45f97a12a3f1a9dd410c7b5f35a59c186eb8"}';

       $postData = file_get_contents("php://input");
        $postArray=json_decode($postData,true); 

       
        $check=$this->usersapi->checkAuth($postArray['token']);

        if($check)
        {
            

            $latitude=$postArray['latitude'];
            $longitude=$postArray['longitude'];
            $fb_data="https://graph.facebook.com/search?type=place&center=$latitude,$longitude&distance=500&access_token=EAADUw5iRRFgBAP6Lt2yf6asi4dZBwna75ZA7ytVZAs8BPZBPp43J8ZBILocpcC1JuwuDM1DJDdkNY18ZB051ZAtw78Luf0juDjmIqQW8nXF5riRCYVAX033oHI8CZCdd2U46IPDKgAKT89TdwkcaUhklfCFNWiTUiotXxKH26Szp4O7rv8Cn3UJlG8D5u4KyIX2yIdA31DqrDeVq7sgfpSjZCO2OnON0iumEZD";


            $fb_data=file_get_contents($fb_data);

            
           
            $fb_data=json_decode($fb_data,true);

            

            for($i=0;$i<count($fb_data['data']);$i++)
            {
                //$rest_details[$i]['name']=$fb_data['data'][$i]['name'];
                $rest_details[]=$fb_data['data'][$i]['id'];                
            }
	    
            $place_ids=implode(",",$rest_details);
	    
            $data=$this->outletsapi->search_outlets($place_ids);

            if(!empty($data) && !empty($rest_details))
            {

              

                $data_result['data']=$data;
                $data_result['status']='success';
                $data_result['token']=$postArray['token'];
                echo json_encode($data_result,true);                
                //$this->response($data_result, REST_Controller::HTTP_OK);
            }
            else
            {
                $data['status']='success';
                $data['message']='No data found';
                $this->response($data, REST_Controller::HTTP_OK);
            }    
        }
        else
        {
            $message['status']='error';
            $message['message']='Bad Request';
            $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    

    public function story_get()
    {
        // Users from a data store e.g. database

        // $postData='{"token":"2fee45f97a12a3f1a9dd410c7b5f35a59c186eb8"}';

        $postData = file_get_contents("php://input");
        $postArray=json_decode($postData,true); 

       
        $check=$this->usersapi->checkAuth($postArray['token']);

        
            $data=$this->outletsapi->get_story();
            

            if(!empty($data))
            {
                $data_result['data']=$data;
                $data_result[]['status']='success';
                $data_result[]['token']=$postArray['token'];
                $this->response($data_result, REST_Controller::HTTP_OK);
            }
            else
            {
                $data[]['status']='No stroy found';

                $this->response($data, REST_Controller::HTTP_OK);
            }  
       
    }


    


}
