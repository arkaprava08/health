<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Health extends REST_Controller
{
	function __construct()
    {
        // Construct our parent class
        parent::__construct();
        
        $this->load->database();
        $this->load->library('session');

        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php
        //$this->methods['user_get']['limit'] = 500; //500 requests per hour per user/key
        //$this->methods['user_post']['limit'] = 100; //100 requests per hour per user/key
        //$this->methods['user_delete']['limit'] = 50; //50 requests per hour per user/key
    }
    
    //login service
    public function login_post()
    {
        //var_dump($this->request->body);

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
                $this->response(array('userid' => $userid),200);

                $this->session->set_userdata('userid', $userid);
                $this->session->set_userdata('loggedin', 'true');

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
        
        
        if (isset($this->session)) {

            $this->session->set_userdata('loggedin', 'false');

            $this->response($this->session->all_userdata()) ;



            //$this->response(array('error' => 'Successfully Logged Out'),200);
        }
        else
        {
            $this->response(array('error' => 'No session exists to logout'),400);
        }
        
    }
}