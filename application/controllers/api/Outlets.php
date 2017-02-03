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
            $data=$this->outletsapi->search_outlets();

            $latitude=$postArray['latitude'];
            $longitude=$postArray['longitude'];
            $fb_data="https://graph.facebook.com/search?type=place&center=$latitude,$longitude&distance=100&access_token=EAADUw5iRRFgBAP6Lt2yf6asi4dZBwna75ZA7ytVZAs8BPZBPp43J8ZBILocpcC1JuwuDM1DJDdkNY18ZB051ZAtw78Luf0juDjmIqQW8nXF5riRCYVAX033oHI8CZCdd2U46IPDKgAKT89TdwkcaUhklfCFNWiTUiotXxKH26Szp4O7rv8Cn3UJlG8D5u4KyIX2yIdA31DqrDeVq7sgfpSjZCO2OnON0iumEZD";


            $fb_data=file_get_contents($fb_data);

            
           
            $fb_data=json_decode($fb_data,true);

            echo "<pre>";
            print_r($fb_data);die;

            for($i=0;$i<count($fb_data['data']);$i++)
            {
                $rest_details[$i]['name']=$fb_data['data'][$i]['name'];
                $rest_details[$i]['id']=$fb_data['data'][$i]['id'];                
            }
           
            

            if(!empty($data) && !empty($rest_details))
            {

                // $rest_details

                for($i=0;$i<count($data);$i++)
                {
                    for($j=0;$j<count($rest_details);$j++)
                    {
                        

                       if($data[$i]['outlet_name']==$rest_details[$j]['name'])
                       {
                            $data[$i]['place_id']=$rest_details[$j]['id'];
                       }
                       else
                       {
                             $data[$i]['place_id']='empty';
                       }
                        // if($data[$i]['outlet_name']==$rest_details[$j]['name'])
                        // {
                        //     $data[$i]['place_id']=$rest_details[$j]['id'];
                        // }
                        // else
                        // {
                        //     unset($data[$i]);
                        // }
                    }
                }

               

                $data_result['data']=$data;
                $data_result['status']='success';
                $data_result['token']=$postArray['token'];
                $this->response($data_result, REST_Controller::HTTP_OK);
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


    function abc_get()
    {
        $fb_data='{
   "data": [
      {
         "category": "Local Business",
         "category_list": [
            {
               "id": "150534008338515",
               "name": "Barbecue Restaurant"
            }
         ],
         "location": {
            "city": "Noida",
            "country": "India",
            "latitude": 28.626641,
            "longitude": 77.384803,
            "street": "H-27/1A, Sector 63, Noida",
            "zip": "201307"
         },
         "name": "The Ancient Barbeque",
         "id": "1098864233541584"
      },
      {
         "category": "Company",
         "category_list": [
            {
               "id": "1706730532910578",
               "name": "Internet Marketing Service"
            },
            {
               "id": "1751954525061797",
               "name": "Digital / Online Marketing Agency"
            }
         ],
         "location": {
            "city": "Noida",
            "country": "India",
            "latitude": 28.626569197572,
            "longitude": 77.384004592896,
            "street": "D-115, Sector 63",
            "zip": "201301"
         },
         "name": "Treenet IT Business Solutions",
         "id": "368079396877666"
      }
   ],
   "paging": {
      "cursors": {
         "before": "MAZDZD",
         "after": "MQZDZD"
      }
   }
}';



            echo "<Pre>";
            $fb_data=json_decode($fb_data,true);
            for($i=0;$i<count($fb_data['data']);$i++)
            {
                $rest_details[$i]['name']=$fb_data['data'][$i]['name'];
                $rest_details[$i]['id']=$fb_data['data'][$i]['id'];                
            }
            print_r($rest_details);die;
            die;
    }

    function facebook_get()
    {
        $url="https://graph.facebook.com/search?type=place&center=28.6266412,77.3848031&distance=100&access_token=EAADUw5iRRFgBAP6Lt2yf6asi4dZBwna75ZA7ytVZAs8BPZBPp43J8ZBILocpcC1JuwuDM1DJDdkNY18ZB051ZAtw78Luf0juDjmIqQW8nXF5riRCYVAX033oHI8CZCdd2U46IPDKgAKT89TdwkcaUhklfCFNWiTUiotXxKH26Szp4O7rv8Cn3UJlG8D5u4KyIX2yIdA31DqrDeVq7sgfpSjZCO2OnON0iumEZD";

            $facebook_data=file_get_contents($url);

            echo "<Pre>";
            $data=json_decode($facebook_data,true);
            for($i=0;$i<count($data['data']);$i++)
            {
                print_r($data['data'][$i]);
            }
            die;
    }



    public function users_post()
    {
        // $this->some_model->update_user( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'message' => 'Added a resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function users_delete()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

}
