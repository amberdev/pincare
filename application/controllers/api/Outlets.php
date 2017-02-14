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

                // $rest_details

/*                for($i=0;$i<count($data);$i++)
                {
                    for($j=0;$j<count($rest_details);$j++)
                    {
                        

                       if($data[$i]['outlet_name']==$rest_details[$j]['name'])
                       {
                            $data[$i]['place_id']=$rest_details[$j]['id'];
                            continue;
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

 */              

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


    function abc_get()
    {

        $fb_data='{"data":[{"category":"Arts & Entertainment","category_list":[{"id":"197871390225897","name":"Cafe"},{"id":"133436743388217","name":"Arts & Entertainment"}],"location":{"city":"New Delhi","country":"India","latitude":28.630196561235,"longitude":77.220399381042,"zip":"110001"},"name":"Junkyard Cafe","id":"435590626609942"},{"category":"Local Business","category_list":[{"id":"128673187201735","name":"Coffee Shop"}],"location":{"city":"New Delhi","country":"India","latitude":28.630342,"longitude":77.220586,"street":"N 16, N BlockOuter Circle,Connaught place","zip":"110001"},"name":"Starbucks India","id":"559184244255140"},{"category":"Restaurant","category_list":[{"id":"273819889375819","name":"Restaurant"},{"id":"197871390225897","name":"Cafe"},{"id":"282281972123162","name":"Live Music Venue"}],"location":{"city":"New Delhi","country":"India","latitude":28.630116729465,"longitude":77.220417479396,"street":"N\/57 - N\/60, First Floor, Opp. KFC, Outer Circle, Connaught Place","zip":"1100001"},"name":"FLYP at MTV","id":"406806526196750"},{"category":"Local Business","category_list":[{"id":"150534008338515","name":"Barbecue Restaurant"},{"id":"129539913784760","name":"Indian Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.630164121659,"longitude":77.220796766915,"street":"N-96, 2nd Floor, Munshi Lal Building, Connaught Place,","zip":"110001"},"name":"Barbeque Nation","id":"1450366765272902"},{"category":"Local Business","category_list":[{"id":"273819889375819","name":"Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.6303538,"longitude":77.22055},"name":"Desi Vibes Cannought Place","id":"319913498355436"},{"category":"Local Business","category_list":[{"id":"150108431712141","name":"Grocery Store"},{"id":"134015953331113","name":"Convention Center"},{"id":"200600219953504","name":"Shopping\/Retail"}],"location":{"city":"New Delhi","country":"India","latitude":28.631056687911,"longitude":77.220725226339},"name":"New Delhi City Center","id":"257848957703507"},{"category":"Local Business","category_list":[{"id":"273819889375819","name":"Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.630136267083,"longitude":77.220776519583,"street":"Connaught place"},"name":"Town House Cafe Cp","id":"1504591343111993"},{"category":"Restaurant","category_list":[{"id":"129539913784760","name":"Indian Restaurant"},{"id":"192831537402299","name":"Family Style Restaurant"}],"location":{"city":"Noida","country":"India","latitude":28.573441895719,"longitude":77.230067253113,"street":"G-50, 1st & 2nd Flr, Sector-18, Noida, Noida","zip":"201301"},"name":"DESI Vibes","id":"188255497887368"},{"category":"Restaurant","category_list":[{"id":"233804719972590","name":"Bar & Grill"},{"id":"184460441595855","name":"Sports Bar"}],"location":{"city":"New Delhi","country":"India","latitude":28.562334709402,"longitude":77.268887903125,"street":"8, Community Centre, New Friends Colony","zip":"110065"},"name":"Pebble Street & Bar","id":"146931255367665"},{"category":"Restaurant","category_list":[{"id":"129539913784760","name":"Indian Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.630021091204,"located_in":"250960578287660","longitude":77.220294791296,"street":"cp","zip":"110002"},"name":"Rajdhani Restaurant","id":"294725887206556"},{"category":"Restaurant","category_list":[{"id":"135930436481618","name":"Lounge"},{"id":"273819889375819","name":"Restaurant"},{"id":"197871390225897","name":"Cafe"}],"location":{"city":"New Delhi","country":"India","latitude":28.630085448623,"longitude":77.21969415481,"street":"conaught place taddy boy","zip":"110001"},"name":"Teddy Boy","id":"1644901302434775"},{"category":"Restaurant","category_list":[{"id":"135930436481618","name":"Lounge"},{"id":"263451080680156","name":"Beer Bar"},{"id":"273819889375819","name":"Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.630091228437,"longitude":77.220325469971,"street":"N 55 56 Connought Place, Outer Circle, Next to Fitness First,","zip":"110001"},"name":"Ardor 2.1","id":"360424217326319"},{"category":"Local Business","category_list":[{"id":"1562965077339698","name":"Food & Beverage"}],"location":{"city":"New Delhi","country":"India","latitude":28.629771,"longitude":77.220673,"street":"Shop No. 9, Scindia House, Outer Circle, Connaught Place","zip":"110001"},"name":"Biryani Blues","id":"328549194158319"},{"category":"Local Business","category_list":[{"id":"273819889375819","name":"Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.630408513602,"longitude":77.22006678125,"street":"N-91, 2nd Floor, Outer Circle, Connaught Place, Opp KG Marg","zip":"110001"},"name":"The Junkyard cafe","id":"1540731116216826"},{"category":"Restaurant","category_list":[{"id":"273819889375819","name":"Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.630086719091,"longitude":77.220430851364,"street":"N - 12 Connaught Place","zip":"110001"},"name":"Blues The Cafe & Bar","id":"269690239746164"},{"category":"Restaurant","category_list":[{"id":"218693881483234","name":"Pub"},{"id":"197871390225897","name":"Cafe"},{"id":"273819889375819","name":"Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.630745422609,"longitude":77.220094123913,"street":"N-33\/4, Middle Circle, Connaught Place","zip":"110001"},"name":"Do Not Disturb","id":"545196405664574"},{"category":"Local Business","category_list":[{"id":"185855984789970","name":"Asian Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.631113165204,"longitude":77.219099841405,"street":"Connaught Place","zip":"110001"},"name":"KFC , Connaught Place","id":"149918038404675"},{"category":"Bar","category_list":[{"id":"135930436481618","name":"Lounge"},{"id":"197871390225897","name":"Cafe"}],"location":{"city":"New Delhi","country":"India","latitude":28.63396,"longitude":77.21979,"street":"Connaught Place"},"name":"ZAI Unplugged Connaught Place","id":"1556007254699611"},{"category":"Restaurant","category_list":[{"id":"214976388519648","name":"Cantonese Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.630160867314,"longitude":77.220733465277,"street":"First Floor, N18, Connaught Place","zip":"110001"},"name":"Taste of China, Connaught Place","id":"577895045595344"},{"category":"Local Business","category_list":[{"id":"203462172997785","name":"Tea Room"}],"location":{"city":"New Delhi","country":"India","latitude":28.630009487311,"longitude":77.220359466401,"street":"Cp","zip":"110001"},"name":"Tea Trails cp delhi","id":"1142092082501671"},{"category":"Local Business","category_list":[{"id":"129539913784760","name":"Indian Restaurant"},{"id":"174483852595760","name":"Chinese Restaurant"},{"id":"197227066968500","name":"Continental Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.630185398763,"located_in":"529533190459519","longitude":77.220196723938,"street":"N-19, Connaught Place","zip":"110001"},"name":"Amber Restaurant and Bar","id":"1778255509080407"},{"category":"Local Business","category_list":[{"id":"164243073639257","name":"Hotel"}],"location":{"city":"New Delhi","country":"India","latitude":28.630073114444,"longitude":77.220222395556},"name":"Adore, Cp","id":"418417768197677"},{"category":"Shopping\/Retail","category_list":[{"id":"128753240528981","name":"Women\'s Clothing Store"},{"id":"200600219953504","name":"Shopping\/Retail"}],"location":{"city":"Dubai","country":"United Arab Emirates","latitude":28.6300985,"longitude":77.219996,"street":"The Dubai Mall, Downtown"},"name":"La Perla - UAE - Dubai Mall","id":"1498641507116948"},{"category":"Restaurant","category_list":[{"id":"164049010316507","name":"Gastropub"},{"id":"110290705711626","name":"Bar"}],"location":{"city":"New Delhi","country":"India","latitude":28.631190922388,"longitude":77.221173788452,"street":"N 18 Connaught Place Outer Circle Opposite Scindia House","zip":"110001"},"name":"Blues Delhi","id":"288416037986342"},{"category":"Fast Food Restaurant","category_list":[{"id":"192803624072087","name":"Fast Food Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.63118,"longitude":77.2196,"street":"N3, Radial One"},"name":"Johnny Rockets, Connaught Place","id":"1558888581102186"},{"category":"Bar","category_list":[{"id":"197227066968500","name":"Continental Restaurant"},{"id":"193831710644458","name":"Italian Restaurant"},{"id":"110290705711626","name":"Bar"}],"location":{"city":"New Delhi","country":"India","latitude":28.630039503635,"longitude":77.220134890001,"street":"N-12, Outer Circle, Connaught Place","zip":"110001"},"name":"Attitude Kitchen & Bar","id":"228181514049809"},{"category":"Local Business","category_list":[{"id":"188296324525457","name":"Sandwich Shop"}],"location":{"city":"New Delhi","country":"India","latitude":28.630122609351,"longitude":77.220050233333,"street":"rajiv chowk"},"name":"Subway","id":"200144386678351"},{"category":"Local Business","category_list":[{"id":"203743122984241","name":"Beer Garden"}],"location":{"city":"New Delhi","country":"India","latitude":28.630204925,"longitude":77.220591095714},"name":"Cocoa Cafe Bar Cp","id":"533687590097749"},{"category":"Local Business","category_list":[{"id":"150534008338515","name":"Barbecue Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.630198256323,"longitude":77.221064075149,"street":"Cannought Place"},"name":"Bar Be Que Nation","id":"357107034398723"},{"category":"Local Business","category_list":[{"id":"186004504854452","name":"Country Club \/ Clubhouse"}],"location":{"city":"New Delhi","country":"India","latitude":28.63020279,"longitude":77.22042968},"name":"Mtv Bar Cp","id":"1735614779995194"},{"category":"Restaurant","category_list":[{"id":"214976388519648","name":"Cantonese Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.63016347191,"longitude":77.220791909004,"street":"Middle Circle, N Block, Connaught Place","zip":"110001"},"name":"Taste Of China Delhi","id":"173928055959687"},{"category":"Local Business","category_list":[{"id":"205479252799194","name":"Lebanese Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.630214763846,"longitude":77.22093688,"street":"Connaught Place","zip":"110001"},"name":"Arabica by Moets","id":"895238623857060"},{"category":"Local Business","category_list":[{"id":"179576078750378","name":"Fashion Designer"},{"id":"2250","name":"Education"}],"location":{"city":"New Delhi","country":"India","latitude":28.630321045,"longitude":77.22070583,"street":"A-21\/13, Naraina Industrial Area, Phase II, Near Shadipur Metro Station,","zip":"110028"},"name":"Pearl Academy Of Fashion, Naraina","id":"102020349950883"},{"category":"Restaurant","category_list":[{"id":"273819889375819","name":"Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.63071,"longitude":77.22089,"street":"33\/8, N Block, Munshilal Building, Connaught Place","zip":"110001"},"name":"Protein Bar - New Delhi","id":"208386836234842"},{"category":"Local Business","category_list":[{"id":"174177802634376","name":"Hair Salon"}],"location":{"city":"New Delhi","country":"India","latitude":28.62998019375,"longitude":77.2203362675},"name":"Geetanjali Salon, Connaught Place, New Delhi 01","id":"1819400571624250"},{"category":"Local Business","category_list":[{"id":"180256082015845","name":"Pizza Place"}],"location":{"city":"New Delhi","country":"India","latitude":28.647144906429,"longitude":77.118491130629,"street":"J-2\/20, Ground Floor, BK Dutta Market, Rajouri Garden","zip":"110027"},"name":"Domino\'s","id":"194029093949344"},{"category":"Local Business","category_list":[{"id":"233804719972590","name":"Bar & Grill"}],"location":{"city":"New Delhi","country":"India","latitude":28.630171196667,"longitude":77.221066086667},"name":"BBQ Nation CP","id":"860549114014406"},{"category":"Local Business","category_list":[{"id":"135930436481618","name":"Lounge"},{"id":"191478144212980","name":"Dance & Night Club"}],"location":{"city":"Delhi","country":"India","latitude":28.630507976311,"located_in":"921123617965935","longitude":77.219552369098,"street":"Middle Circle","zip":"110001"},"name":"F-BAR","id":"257944470942578"},{"category":"Restaurant","category_list":[{"id":"233804719972590","name":"Bar & Grill"},{"id":"197871390225897","name":"Cafe"}],"location":{"city":"New Delhi","country":"India","latitude":28.62956757604,"longitude":77.219466019679,"street":"Outer Circle, Connaught Place","zip":"110011"},"name":"My Bar Headquarters","id":"1569085870026036"},{"category":"Restaurant","category_list":[{"id":"273819889375819","name":"Restaurant"},{"id":"197871390225897","name":"Cafe"},{"id":"135930436481618","name":"Lounge"}],"location":{"city":"New Delhi","country":"India","latitude":28.62487,"longitude":77.21924,"street":"Connaught Place","zip":"110001"},"name":"The Town House Cafe","id":"457345074398219"},{"category":"Local Business","category_list":[{"id":"142590562472824","name":"Singaporean Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.630146530675,"longitude":77.220814583594},"name":"\u0040Live Blues Bar CP","id":"210859228968131"},{"category":"Local Business","category_list":[{"id":"128673187201735","name":"Coffee Shop"}],"location":{"city":"New Delhi","country":"India","latitude":28.634462,"longitude":77.22227,"street":"L 12, Cannaught Place, Delhi Outer Circle","zip":"110001"},"name":"Cafe Coffee Day","id":"558286827545136"},{"category":"Local Business","category_list":[{"id":"128673187201735","name":"Coffee Shop"}],"location":{"city":"New Delhi","country":"India","latitude":28.630019130123,"longitude":77.220201877491,"street":"janpath"},"name":"CCD Lounge,Janpath","id":"223881744343785"},{"category":"Local Business","category_list":[{"id":"185900881450649","name":"Personal Coach"}],"location":{"city":"New Delhi","country":"India","latitude":28.630244089688,"located_in":"195943220443214","longitude":77.220518972439,"street":"Connaught place","zip":"110001"},"name":"Fitness First Cp","id":"450474201671609"},{"category":"Local Business","category_list":[{"id":"191478144212980","name":"Dance & Night Club"}],"location":{"city":"New Delhi","country":"India","latitude":28.630014337032,"longitude":77.220329503355,"zip":"11000"},"name":"Party Meter","id":"341763109738"},{"category":"Local Business","category_list":[{"id":"178680352174443","name":"Toy Store"}],"location":{"city":"New Delhi","country":"India","latitude":28.63012174,"longitude":77.22051222,"zip":"110051"},"name":"Teddy World","id":"164896377040868"},{"category":"Local Business","category_list":[{"id":"180256082015845","name":"Pizza Place"}],"location":{"city":"New Delhi","country":"India","latitude":28.630126973217,"longitude":77.219419871218,"street":"Outer Cir, M-42, Connaught Circus Radial Road Number 6, Block M, Connaught Plac New Delhi, Delhi","zip":"110001"},"name":"Domino\'s, C.P., New Delhi","id":"222739434468430"},{"category":"Shopping\/Retail","category_list":[{"id":"200600219953504","name":"Shopping\/Retail"},{"id":"186230924744328","name":"Clothing Store"},{"id":"186982054657561","name":"Recreation & Fitness"}],"location":{"city":"New Delhi","country":"India","latitude":28.630769252899,"longitude":77.219939231873,"street":"N Block Cannaught Place","zip":"110001"},"name":"Adidas","id":"252464245111162"},{"category":"Local Business","category_list":[{"id":"128673187201735","name":"Coffee Shop"}],"location":{"city":"New Delhi","country":"India","latitude":28.629975956,"longitude":77.220149344,"street":"P 23\/90, P Block, Cannaught Place","zip":"110001"},"name":"Cafe Coffee Day","id":"608327142530456"},{"category":"Religious Center","category_list":[{"id":"139460309451166","name":"Religious Center"}],"location":{"city":"New Delhi","country":"India","latitude":28.62997085,"longitude":77.22036081,"street":"Ramakrishna Marg"},"name":"Ram Krishna Ashram","id":"1395067687400723"},{"category":"Arts & Entertainment","category_list":[{"id":"197384240287028","name":"Art Gallery"}],"location":{"city":"New Delhi","country":"India","latitude":28.63075199,"longitude":77.22040353,"street":"G-42, Connaught Place, Outer Circle","zip":"110001"},"name":"Dhoomimal Gallery","id":"397953820283359"},{"category":"Arts & Entertainment","category_list":[{"id":"191478144212980","name":"Dance & Night Club"},{"id":"135930436481618","name":"Lounge"}],"location":{"city":"New Delhi","country":"India","latitude":28.6299896,"longitude":77.2204437,"street":"F Bar & Lounge, N-49, Connaught Place","zip":"110001"},"name":"Delhi Night Parties","id":"440701036121545"},{"category":"Local Business","category_list":[{"id":"169896676390222","name":"Library"},{"id":"197048876974331","name":"Book Store"}],"location":{"city":"New Delhi","country":"India","latitude":28.630893255,"longitude":77.220573795,"zip":"110016"},"name":"Oxford Library  Barakhamba Road  CP","id":"114750611940332"},{"category":"Local Business","category_list":[{"id":"273819889375819","name":"Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.63021512,"longitude":77.221290933333},"name":"CCD, N Block, CP Outer Circle","id":"198648583586215"},{"category":"Local Business","category_list":[{"id":"110290705711626","name":"Bar"},{"id":"2607","name":"Religion"}],"location":{"city":"New Delhi","country":"India","latitude":28.63044544,"longitude":77.219580953529},"name":"Mybar HQ","id":"1088596067836215"},{"category":"Restaurant","category_list":[{"id":"192803624072087","name":"Fast Food Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.630331362601,"longitude":77.219786345959,"street":"N-5, Gate No. 5, Opposite Palika Bazaar, Connaught Place","zip":"110001"},"name":"Kebab Xpress","id":"452222661491223"},{"category":"Local Business","category_list":[{"id":"128673187201735","name":"Coffee Shop"}],"location":{"city":"New Delhi","country":"India","latitude":28.63180744714,"longitude":77.217842969138,"street":"Janpath","zip":"110001"},"name":"Ccd, Cp","id":"220000181472861"},{"category":"Food & Beverage Company","category_list":[{"id":"129539913784760","name":"Indian Restaurant"},{"id":"2252","name":"Food & Beverage Company"}],"location":{"city":"New Delhi","country":"India","latitude":28.63476,"longitude":77.2204299,"street":"N 33\/10 middle circle connaught place","zip":"110001"},"name":"Mughlai junction restaurant","id":"453554511521487"},{"category":"Bar","category_list":[{"id":"110290705711626","name":"Bar"},{"id":"133436743388217","name":"Arts & Entertainment"}],"location":{"city":"New Delhi","country":"India","latitude":28.62516,"longitude":77.2193099,"street":"Janpath Road","zip":"110001"},"name":"F Bar N Lounge,Delhi","id":"1548973138746844"},{"category":"Local Business","category_list":[{"id":"193831710644458","name":"Italian Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.63007332,"longitude":77.21968239},"name":"Dominoz Cp","id":"464311703591385"},{"category":"Local Business","category_list":[{"id":"200863816597800","name":"Ice Cream Shop"}],"location":{"city":"New Delhi","country":"India","latitude":28.63014605,"longitude":77.220717355,"street":"86, Municipal Market, Connaught Circus"},"name":"Pabrai Naturap Icecream","id":"1465690897004132"},{"category":"Landmark & Historical Place","category_list":[{"id":"209889829023118","name":"Landmark & Historical Place"}],"location":{"city":"New Delhi","country":"India","latitude":28.629752214564,"longitude":77.221097946167,"street":"K.G. Marg","zip":"110001"},"name":"Master of Malts","id":"1766792763539120"},{"category":"Local Business","category_list":[{"id":"129539913784760","name":"Indian Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.630869221878,"longitude":77.215595778481,"street":"cannaught place"},"name":"Kake da Dhaba Canaught Place","id":"354424161239181"},{"category":"Local Business","category_list":[{"id":"164243073639257","name":"Hotel"},{"id":"273819889375819","name":"Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.6298819,"longitude":77.2200442},"name":"Ambar Bar & Resturent Connaught Place","id":"401927323205142"},{"category":"Local Business","category_list":[{"id":"128673187201735","name":"Coffee Shop"},{"id":"192803624072087","name":"Fast Food Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.6306771,"longitude":77.2184378,"street":"N 6, Connaught Place","zip":"110001"},"name":"Dunkin\'Donuts","id":"1083162578406325"},{"category":"Local Business","category_list":[{"id":"180256082015845","name":"Pizza Place"}],"location":{"city":"New Delhi","country":"India","latitude":28.6308128,"longitude":77.2196589,"street":"Outer Cir, M-42, Connaught Circus Radial Road Number 6, Block M, Connaught Plac New Delhi, Delhi"},"name":"Dominos Block-N,CP","id":"392494630812243"},{"category":"Local Business","category_list":[{"id":"163090273743732","name":"Commercial Bank"}],"location":{"city":"New Delhi","country":"India","latitude":28.63009,"longitude":77.22136},"name":"Axis Bank Delhi Circle Office","id":"180172125398603"},{"category":"Local Business","category_list":[{"id":"108051929285833","name":"College & University"}],"location":{"city":"Bangalore","country":"India","latitude":28.630263609467,"longitude":77.221115798817},"name":"IIM Banglore","id":"553137604798590"},{"category":"Organization","category_list":[{"id":"198503866828628","name":"Organization"}],"location":{"city":"New Delhi","country":"India","latitude":28.6299896,"longitude":77.2204437,"street":"E-9, Connaught House, Connaught Place","zip":"110001"},"name":"Namaskar","id":"1175813225803151"},{"category":"Local Business","category_list":[{"id":"161516070564222","name":"Financial Service"}],"location":{"city":"New Delhi","country":"India","latitude":28.630241085581,"longitude":77.221592835699},"name":"Deutsche bank CP","id":"153579474717462"},{"category":"Aerospace Company","category_list":[{"id":"128232937246338","name":"Travel & Transportation"}],"location":{"city":"New Delhi","country":"India","latitude":28.6299896,"longitude":77.2204437,"street":"E-9, Connaught House, Connaught Place","zip":"110001"},"name":"Bird ExecuJet Airport Services Pvt Ltd.","id":"1247682418597253"},{"category":"Local Business","category_list":[{"id":"164243073639257","name":"Hotel"}],"location":{"city":"New Delhi","country":"India","latitude":28.618865989103,"longitude":77.218084839404,"street":"Windsor Place","zip":"110001"},"name":"Le Meridien New Delhi","id":"165393040169523"},{"category":"Political Organization","category_list":[{"id":"369730359717478","name":"Other"},{"id":"373543049350668","name":"Political Organization"}],"location":{"city":"New Delhi","country":"India","latitude":28.6299896,"longitude":77.2204437,"street":"1206, 12th Floor, Kailash Building K.G. Marg. Connaught Place New Delhi-110001","zip":"110001"},"name":"I Support Agriculture Minister","id":"485057398325728"},{"category":"Company","category_list":[{"id":"192985434070895","name":"Private Investigator"}],"location":{"city":"New Delhi","country":"India","latitude":28.6299896,"longitude":77.2204437,"street":"p-6\/90 second floor connaught place new delhi","zip":"110001"},"name":"Black Bat Detective & Security Solution Pvt.ltd","id":"612420338860714"},{"category":"Local Business","category_list":[{"id":"110249975723427","name":"Office Supplies"},{"id":"194127957280024","name":"Vintage Store"},{"id":"187679647929203","name":"Collectibles Store"}],"location":{"city":"New Delhi","country":"India","latitude":28.6299896,"longitude":77.2204437,"street":"13, Regal Building Connaught Circus","zip":"110001"},"name":"N. R. Pen Store","id":"901484629926695"},{"category":"Organization","category_list":[{"id":"180428515332153","name":"Airline Industry Service"},{"id":"162914327091136","name":"Travel Agency"},{"id":"198503866828628","name":"Organization"}],"location":{"city":"New Delhi","country":"India","latitude":28.6299896,"longitude":77.2204437,"street":"E-9 Connaught House, Connaught Place","zip":"110001"},"name":"APG India - Network of Airlines","id":"1040870815936853"},{"category":"Legal Company","category_list":[{"id":"179672275401610","name":"Business Consultant"},{"id":"192985434070895","name":"Private Investigator"},{"id":"150944301629503","name":"Real Estate Investment Firm"}],"location":{"city":"New Delhi","country":"India","latitude":28.6299896,"longitude":77.2204437,"street":"New Delhi Airport Express Line, Bhavbhuti Marg, Connaught Place","zip":"110001"},"name":"First Indian Detective Agency","id":"1536579256625510"},{"category":"Internet Company","category_list":[{"id":"187393124625179","name":"Web Designer"},{"id":"170992992946914","name":"Marketing Consultant"},{"id":"179672275401610","name":"Business Consultant"}],"location":{"city":"New Delhi","country":"India","latitude":28.63071,"longitude":77.22089,"street":"N Block, Outer Circle, Connaught Place","zip":"110001"},"name":"Enqodle","id":"510744432430015"},{"category":"Travel Company","category_list":[{"id":"124861974254366","name":"Tour Agency"},{"id":"2258","name":"Travel Company"}],"location":{"city":"New Delhi","country":"India","latitude":28.6299896,"longitude":77.2204437,"street":"Suite no 44 Indira Palace H block Connaught Place New Delhi","zip":"110001"},"name":"Platinum Tours","id":"785855384762094"},{"category":"Automotive","category_list":[{"id":"180410821995109","name":"Automotive"}],"location":{"city":"New Delhi","country":"India","latitude":28.6300695,"longitude":77.2202803,"street":"1617\/32  no street, naiwala road, opp hotel kabila, Karol bagh","zip":"110005"},"name":"Mxs Moto Sport","id":"556305341069255"},{"category":"Company","category_list":[{"id":"187133811318958","name":"Business Service"},{"id":"133436743388217","name":"Arts & Entertainment"}],"location":{"city":"New Delhi","country":"India","latitude":28.6299896,"longitude":77.2204437,"street":"L-4 Connaught Circus, Connaught Lodge, Near Haldiram","zip":"110001 , 90064"},"name":"Aura 9 Enterprises","id":"1389940024557759"},{"category":"Professional Service","category_list":[{"id":"2500","name":"Local Business"}],"location":{"city":"New Delhi","country":"India","latitude":28.62998249,"longitude":77.22019875,"street":"Delhi","zip":"110059"},"name":"Apple Repair","id":"368239646709885"},{"category":"College & University","category_list":[{"id":"2602","name":"College & University"}],"location":{"city":"New Delhi","country":"India","latitude":28.63071,"longitude":77.22089,"street":"v.c.block,university of delhi","zip":"110007"},"name":"Delhi University","id":"1029656990393477"},{"category":"Education","category_list":[{"id":"145296352197250","name":"Tutor"},{"id":"162237190493977","name":"Computer Training School"}],"location":{"city":"Delhi","country":"India","latitude":28.62995,"longitude":77.22092,"street":"Connaught Palace","zip":"110001"},"name":"Home Tuition in Delhi - Call Tutor","id":"333886593452702"},{"category":"Local Business","category_list":[{"id":"129539913784760","name":"Indian Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.630913344527,"longitude":77.219696789616},"name":"Punjabi By Nature - Quickie, CP","id":"556270517796547"},{"category":"Phone\/Tablet","category_list":[{"id":"210979565595898","name":"Mobile Phone Shop"}],"location":{"city":"New Delhi","country":"India","latitude":28.630194439602,"longitude":77.221141099006,"street":"N-22, Connaught Circus","zip":"110001"},"name":"Samsung Smart Cafe","id":"213431258860827"},{"category":"Shopping\/Retail","category_list":[{"id":"200600219953504","name":"Shopping\/Retail"},{"id":"186230924744328","name":"Clothing Store"}],"location":{"city":"New Delhi","country":"India","latitude":28.630085757576,"longitude":77.221015281385,"zip":"110001"},"name":"Artistic India Overseas","id":"304605796349866"},{"category":"Restaurant","category_list":[{"id":"192831537402299","name":"Family Style Restaurant"},{"id":"129539913784760","name":"Indian Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.629695712145,"longitude":77.222900390625,"street":"Connaught Place","zip":"110001"},"name":"Parikrama - The Revolving Restaurant","id":"172121972898300"},{"category":"Travel Company","category_list":[{"id":"2258","name":"Travel Company"}],"location":{"city":"New Delhi","country":"India","latitude":28.63089,"longitude":77.2207899,"street":"N-Block, Midlle Circle, Counnaught Place","zip":"110001"},"name":"Manalideals.com","id":"421506038050568"},{"category":"Company","category_list":[{"id":"2200","name":"Company"}],"location":{"city":"New Delhi","country":"India","latitude":28.6299896,"longitude":77.2204437,"street":"S-V, 9 Scindhia house, Cannaught Circus, Cannaught place","zip":"110001"},"name":"Blue Orions Group","id":"425963664115116"},{"category":"Arts & Entertainment","category_list":[{"id":"133436743388217","name":"Arts & Entertainment"},{"id":"162183907165552","name":"Graphic Designer"},{"id":"197888373555475","name":"Printing Service"}],"location":{"city":"New Delhi","country":"India","latitude":28.62995,"longitude":77.22092,"zip":"110001"},"name":"Graphic Villa","id":"1449271521975642"},{"category":"Consulting Agency","category_list":[{"id":"214297118585256","name":"Consulate & Embassy"},{"id":"179672275401610","name":"Business Consultant"},{"id":"164080003645425","name":"Surveyor"}],"location":{"city":"New Delhi","country":"India","latitude":28.63089,"longitude":77.2207899,"street":"404-405, 4th Floor, F Block, Middle Circle, Connaught Place, New Delhi","zip":"110001"},"name":"Government Approved Valuers","id":"525827004194205"},{"category":"Company","category_list":[{"id":"2200","name":"Company"}],"location":{"city":"New Delhi","country":"India","latitude":28.6299896,"longitude":77.2204437,"street":"E-9, Connaught House, Connaught Place,","zip":"110001"},"name":"Birdres.Com","id":"143433345752279"},{"category":"Government Organization","category_list":[{"id":"161422927240513","name":"Government Organization"}],"location":{"city":"New Delhi","country":"India","latitude":28.63071,"longitude":77.22089,"street":"Ministry of Finance, North Block","zip":"110001"},"name":"Indian Revenue Service","id":"351184908293191"},{"category":"Apparel","category_list":[{"id":"1086422341396773","name":"Apparel"},{"id":"2239","name":"Retail Company"}],"location":{"city":"New Delhi","country":"India","latitude":28.6299896,"longitude":77.2204437,"street":"E-21, Connaught Place , New Delhi","zip":"110001"},"name":"M.Ram & Sons","id":"142347179170491"},{"category":"Restaurant","category_list":[{"id":"273819889375819","name":"Restaurant"}],"location":{"city":"New Delhi","country":"India","latitude":28.63089,"longitude":77.2207899,"street":"33\/6 N Block middle circle Connaught place","zip":"110001"},"name":"Kabab Khan","id":"776697389095852"},{"category":"Retail Company","category_list":[{"id":"2239","name":"Retail Company"}],"location":{"city":"New Delhi","country":"India","latitude":28.6299896,"longitude":77.2204437,"street":"E-9, Connaught House, Connaught Place","zip":"110001"},"name":"Bwfs India","id":"135241569862032"},{"category":"Education","category_list":[{"id":"2250","name":"Education"},{"id":"147714868971098","name":"Public Services & Government"}],"location":{"city":"New Delhi","country":"India","latitude":28.629846385194,"longitude":77.221012115479,"street":"12, Scindia House, Connaught Place","zip":"110001"},"name":"TIME Education Pvt Ltd","id":"162849890540223"},{"category":"Travel Company","category_list":[{"id":"124861974254366","name":"Tour Agency"},{"id":"2258","name":"Travel Company"}],"location":{"city":"New Delhi","country":"India","latitude":28.62961,"longitude":77.21997,"street":"74, Amrit Chmaber, Scindia House","zip":"110001"},"name":"Bookmysight","id":"563237583777676"},{"category":"Finance Company","category_list":[{"id":"213577718658733","name":"Investing Service"},{"id":"2234","name":"Finance Company"}],"location":{"city":"New Delhi","country":"India","latitude":28.62961,"longitude":77.21997,"street":"76-77, Scindia House","zip":"110001"},"name":"KK Securities Limited","id":"282466875240833"},{"category":"Travel Company","category_list":[{"id":"2258","name":"Travel Company"}],"location":{"city":"Delhi","country":"India","latitude":28.63089,"longitude":77.2207899,"street":"N-33\/14, Middle Circle, Connaught Place","zip":"110001"},"name":"Himachalbusservice","id":"110738099048782"},{"category":"Tour Agency","category_list":[{"id":"124861974254366","name":"Tour Agency"},{"id":"124947834245370","name":"Tourist Information Center"}],"location":{"city":"New Delhi","country":"India","latitude":28.630051844058,"longitude":77.21968740967,"street":"L-36 Madangir","zip":"110062"},"name":"India Tourism","id":"297769396989088"}],"paging":{"cursors":{"before":"MAZDZD","after":"MTAx"}}}';

        $fb_data=json_decode($fb_data,true);


            

            for($i=0;$i<count($fb_data['data']);$i++)
            {
                $rest_details[$i]['name']=$fb_data['data'][$i]['name'];
                $rest_details[$i]['id']=$fb_data['data'][$i]['id'];                
            }
            


            $data=$this->outletsapi->search_outlets();



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
                            continue;
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

            }
            echo "<pre>";
            echo print_r($data);

        
    }}
