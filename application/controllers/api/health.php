<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

ini_set('display_errors', 'On');

class Health extends REST_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('session');
    }

    public function test_get() {
        echo "testing";
    }

    //check if loggedin or not
    public function isLoggedIn() {
        if ($this->session->userdata('loggedin')) {
            return true;
        }

        return false;
    }

    //login service
    public function login_post() {

        if (isset($this->request->body['username']) && isset($this->request->body['password'])) {
            $username = $this->request->body['username'];
            $password = $this->request->body['password'];



            $query = $this->db->query('SELECT * FROM users WHERE username = "' . $username . '" AND password = "' . $password . '"');

            $userid;
            foreach ($query->result() as $row) {
                $userid = $row->id;
            }

            if ($query->num_rows() == 1) {

                $this->session->set_userdata('userid', $userid);
                $this->session->set_userdata('loggedin', true);

                $this->response(array('userid' => $userid), 200);


                //print_r($this->session->all_userdata()) ;
            } else {
                $this->response(array('error' => 'Username/Password invalid'), 401);
            }
        } else {
            $this->response(array('error' => 'Username/Password not provided'), 400);
        }
    }

    //logout service
    public function logout_get() {

        if ($this->isLoggedIn()) {

            $this->session->set_userdata('loggedin', false);

            $this->session->sess_destroy();

            //$this->response($this->session->all_userdata()) ;



            $this->response(array('error' => 'Successfully Logged Out'), 200);
        } else {
            $this->response(array('error' => 'No session exists to logout'), 400);
        }
    }

    //insert data
    public function insert_post() {

        $this->load->database();
//        if (!$this->isLoggedIn()) {
//            $this->response(array('error' => 'User not logged in '), 401);
//        }
//
//
//        //get userid from session
//        if ($this->session->userdata('userid')) {
//            $userid = $this->session->userdata('userid');
//        } else {
//            $this->response(array('error' => 'User not logged in '), 401);
//        }
        //if id of patient is passed
        if (isset($this->request->body['patientid'])) {
            if (isset($this->request->body['bodytemperature']) && isset($this->request->body['bp_sp']) && isset($this->request->body['bp_dp']) && isset($this->request->body['symptoms']) && isset($this->request->body['comment']) && isset($this->request->body['latitude']) && isset($this->request->body['longitude']) && isset($this->request->body['userid'])) {

                $patientid = $this->request->body['patientid'];
                $bodytemperature = $this->request->body['bodytemperature'];
                $bp_sp = $this->request->body['bp_sp'];
                $bp_dp = $this->request->body['bp_dp'];
                $symptoms = $this->request->body['symptoms'];
                $comment = $this->request->body['comment'];
                $latitude = $this->request->body['latitude'];
                $longitude = $this->request->body['longitude'];
                $userid = $this->request->body['userid'];

                $sql = "INSERT INTO `patientdata`( `patientid`, `userid`, `bodytemperature`, `bp_sp`, `bp_dp`, `symptoms`, `comment`,`latitude`,`longitude`, `insertedDate`) "
                        . "VALUES (" . $patientid . "," . $userid . "," . $bodytemperature . "," . $bp_sp . "," . $bp_dp . ",'" . $symptoms . "','" . $comment . "'," . $latitude . "," . $longitude . ", NOW())";


                $query = $this->db->simple_query($sql);

                if ($query == 1) {
                    $this->response(array('error' => 'No error'), 200);
                } else {
                    $this->response(array('error' => 'Server error'), 500);
                }
            } else {
                $this->response(array('error' => 'Data not provided'), 400);
            }
        } else {
            if (isset($this->request->body['name']) && isset($this->request->body['dob']) && isset($this->request->body['gender']) && isset($this->request->body['height']) && isset($this->request->body['weight']) && isset($this->request->body['address']) && isset($this->request->body['bodytemperature']) && isset($this->request->body['bp_sp']) && isset($this->request->body['bp_dp']) && isset($this->request->body['symptoms']) && isset($this->request->body['comment']) && isset($this->request->body['latitude']) && isset($this->request->body['longitude']) && isset($this->request->body['userid'])) {


                $name = $this->request->body['name'];
                $dob = $this->request->body['dob'];
                $gender = $this->request->body['gender'];
                $height = $this->request->body['height'];
                $weight = $this->request->body['weight'];
                $address = $this->request->body['address'];
                $bodytemperature = $this->request->body['bodytemperature'];
                $bp_sp = $this->request->body['bp_sp'];
                $bp_dp = $this->request->body['bp_dp'];
                $symptoms = $this->request->body['symptoms'];
                $comment = $this->request->body['comment'];
                $latitude = $this->request->body['latitude'];
                $longitude = $this->request->body['longitude'];
                $userid = $this->request->body['userid'];

                $patientid;

                $q = $this->db->query("SELECT * FROM patients WHERE  name ='"
                        . $name . "' and dob ='" . $dob . "' and gender='"
                        . $gender . "' and address='" . $address . "' and height="
                        . $height . " and weight= " . $weight . " and userid=" . $userid);

                if ($q->num_rows() > 0) {
                    $this->response(array('error' => 'Data already exists'), 400);
                } else {
                    //$this->db->trans_start();
                    $this->db->trans_begin();

                    $query1 = $this->db->query("INSERT INTO `patients`( `name`, `dob`, `gender`,`height`,`weight`, `address`, `userid`) "
                            . "VALUES ('" . $name . "', '" . $dob . "','" . $gender . "'," . $height . "," . $weight . ",'" . $address . "'," . $userid . ");");


                    $query2 = $this->db->query("SELECT * FROM patients WHERE  name ='" . $name . "' and dob ='" . $dob . "' and gender='" . $gender . "' and address='" . $address . "' and userid=" . $userid);



                    foreach ($query2->result() as $row) {
                        $patientid = $row->id;
                    }
                    //we have $patientid
                    $sql = "INSERT INTO `patientdata`( `patientid`, `userid`, `bodytemperature`, `bp_sp`, `bp_dp`, `symptoms`, `comment`,`latitude`,`longitude`, `insertedDate`) "
                            . "VALUES (" . $patientid . "," . $userid . "," . $bodytemperature . "," . $bp_sp . "," . $bp_dp . ",'" . $symptoms . "','" . $comment . "'," . $latitude . "," . $longitude . ", NOW())";

                    $query3 = $this->db->query($sql);

                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                    } else {
                        $this->db->trans_commit();
                    }
                    
                    if ($query3 == 1) {
                        $this->response(array('error' => 'No error', 'patientid' => $patientid), 200);
                    } else {
                        $this->response(array('error' => 'Server error'), 500);
                    }
                    
                    $this->db->trans_complete();
                }
            } else {
                $this->response(array('error' => 'Data not provided'), 400);
            }
        }
    }

    public function getPatientDetails_get() {
        if (!$this->get('id')) {
            $this->response(array('error=>id not provided'), 400);
        }

        $id = $this->get('id');
        $fetch = "SELECT * FROM patientdata WHERE patientid=" . $id;

        $q = $this->db->query($fetch);

        $this->response($q->result(), 200);
    }

}
