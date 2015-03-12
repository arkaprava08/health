<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

ini_set('display_errors', 'On');

class Health extends REST_Controller
{
	function __construct()
    {
        parent::__construct();
        
        $this->load->database();
        $this->load->library('session');
    }
    
    public function test_get()
    {
        echo "testing";
    }

    //check if loggedin or not
    public function isLoggedIn()
    {
        if($this->session->userdata('loggedin'))
            return true;

        return false;
    }
    //login service
    public function login_post()
    {

        if(isset($this->request->body['username']) && isset($this->request->body['password']))
        {
            $username = $this->request->body['username'];
            $password = $this->request->body['password'];



            $query = $this->db->query('SELECT * FROM users WHERE username = "'.$username.'" AND password = "'.$password.'"');

            $userid;
            foreach ($query->result() as $row)
            {
                $userid = $row->id;
            }

            if($query->num_rows() == 1)
            {

                $this->session->set_userdata('userid', $userid);
                $this->session->set_userdata('loggedin', true);

                $this->response(array('userid' => $userid),200);


                //print_r($this->session->all_userdata()) ;
            }
            else
            {
                $this->response(array('error' => 'Username/Password invalid'),401);
            }

        }
        else
        {
            $this->response(array('error' => 'Username/Password not provided'),400);
        }
        
    }

    //logout service
    public function logout_get()
    {

        
        if ($this->isLoggedIn()) {

            $this->session->set_userdata('loggedin', false);

            $this->session->sess_destroy();

            //$this->response($this->session->all_userdata()) ;



            $this->response(array('error' => 'Successfully Logged Out'),200);
        }
        else
        {
            $this->response(array('error' => 'No session exists to logout'),400);
        }
        
    }

    //insert data
    public function insert_post()
    {
        if(!$this->isLoggedIn())
        {
            $this->response(array('error' => 'User not logged in '),401);   
        }

        
        //get userid from session
        if($this->session->userdata('userid'))
            $userid = $this->session->userdata('userid');
        else
            $this->response(array('error' => 'User not logged in '),401); 



        //if id of patient is passed
        if(isset($this->request->body['id']))
        {
            if(isset($this->request->body['patientid']) 
                && isset($this->request->body['bodytemperature'])
                && isset($this->request->body['bloodpressure'])
                && isset($this->request->body['symptoms'])
                && isset($this->request->body['comment']))
            {

                $patientid = $this->request->body['patientid'];
                $bodytemperature = $this->request->body['bodytemperature'];
                $bloodpressure = $this->request->body['bloodpressure'];
                $symptoms = $this->request->body['symptoms'];
                $comment = $this->request->body['comment'];

            }
            else
            {
                $this->response(array('error' => 'Data not provided'),400); 
            }
        }
        //else id is not passed i.e. new data
        else
        {
            if (isset($this->request->body['name']) 
                && isset($this->request->body['age'])
                && isset($this->request->body['gender'])
                && isset($this->request->body['address'])
                && isset($this->request->body['bodytemperature'])
                && isset($this->request->body['bloodpressure'])
                && isset($this->request->body['symptoms'])
                && isset($this->request->body['comment'])) {


                $name = $this->request->body['name'];
                $age = $this->request->body['age'];
                $gender = $this->request->body['gender'];
                $address = $this->request->body['address'];
                $bodytemperature = $this->request->body['bodytemperature'];
                $bloodpressure = $this->request->body['bloodpressure'];
                $symptoms = $this->request->body['symptoms'];
                $comment = $this->request->body['comment'];

                $patientid;

                $checkDataRedundancy = "SELECT `id` FROM `patients` WHERE  name ='"
                                        .$name."' and age =".$age." and gender='"
                                        .$gender."' and address='".$address."' and userid=".$userid;

                $query = $this->db->query($checkDataRedundancy);

                if($query->num_rows() > 0)
                {
                        $this->response(array('error' => 'Data already exists'),400); 
                }
                else
                {
                    $this->db->trans_start();

                    $insertData = "INSERT INTO `patients`( `name`, `age`, `gender`, `address`, `userid`) "
                        ."VALUES ('".$name."', ".$age.",'".$gender."','".$address."',".$userid.");";

                    $query = $this->db->query($insertData);

                    $getUniqueId = "SELECT `id` FROM `patients` WHERE  name ='".$name."' and age =".$age." and gender='".$gender."' and address='".$address."' and userid=".$userid;

                    $query = $this->db->query($getUniqueId);

                    foreach ($query->result() as $row)
                    {
                        $patientid = $row->id;
                    }
                    //we have $patientid
                    $sql = "INSERT INTO `patientdata`( `patientid`, `userid`, `bodytemperature`, `bloodpressure`, `symptoms`, `comment`, `insertedDate`) "
                                                ."VALUES (".$patientid.",".$userid.",".$bodytemperature.",".$bloodpressure.",'".$symptoms."','".$comment."', NOW())";


                    $query = $this->db->query($sql);

                    $this->db->trans_complete();
                }

                
            }
            else
            {
                $this->response(array('error' => 'Data not provided'),400); 
            }
        }





    }
}