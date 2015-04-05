<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

ini_set('display_errors', 'On');

class Health extends REST_Controller {

    function __construct() {
        parent::__construct();

        $this->load->database();
        $this->load->library('session');
    }

    public function test_get() {
        echo "testing";
    }

    public function getAction($data) {

        $age = $data["age"];
        $bodyTemperature = $data["bodyTemperature"];
        $bodyTemperatureTypeId = $data["bodyTemperatureTypeId"];
        $bp_sp = $data["bp_sp"];
        $bp_dp = $data["bp_dp"];

        $action['BodyTemperature'] = "All fine";
        $action['BloodPressure'] = "All fine";
        $consultation = 0;

        switch ($bodyTemperatureTypeId) {
            case 1:
                if ($bodyTemperature < 95) {
                    $action['BodyTemperature'] = [
                        'detail' => "May have hypothermia. Please urgently visit doctor",
                        'status' => 2
                    ];
                } else if ($bodyTemperature < 99) {
                    $action['BodyTemperature'] = [
                        'detail' => "Normal Body Temperature",
                        'status' => 0
                    ];
                } else if ($bodyTemperature <= 102.2) {
                    $action['BodyTemperature'] = [
                        'detail' => "Low Grade Fever. Please visit doctor",
                        'status' => 1
                    ];
                } else {
                    $action['BodyTemperature'] = [
                        'detail' => "High Grade Fever. Please Urgently Visit Doctor",
                        'status' => 2
                    ];
                }

                break;

            case 2:
                if ($bodyTemperature < 95) {
                    $action['BodyTemperature'] = [
                        'detail' => "May have hypothermia. Please urgently visit doctor",
                        'status' => 2
                    ];
                } else if ($bodyTemperature < 98.5) {
                    $action['BodyTemperature'] = [
                        'detail' => "Normal Body Temperature",
                        'status' => 0
                    ];
                } else if ($bodyTemperature <= 102.2) {
                    $action['BodyTemperature'] = [
                        'detail' => "Low Grade Fever. Please visit doctor",
                        'status' => 1
                    ];
                } else {
                    $action['BodyTemperature'] = [
                        'detail' => "High Grade Fever. Please Urgently Visit Doctor",
                        'status' => 2
                    ];
                }

                break;

            default:
                break;
        }

        if ($age > 17) {
            //for BP SP
            if ($bp_sp < 120) {
                $action['BloodPressure'] = [
                    'detail' => "Hypotension. Please urgently visit doctor",
                    'status' => 4
                ];
            } else if ($bp_sp < 140) {
                $action['BloodPressure'] = [
                    'detail' => "Normal Pressure",
                    'status' => 0
                ];
            } else if ($bp_sp < 160) {
                $action['BloodPressure'] = [
                    'detail' => "Grade 1  Hypertension Please visit doctor",
                    'status' => 1
                ];
            } else if ($bp_sp < 180) {
                $action['BloodPressure'] = [
                    'detail' => "Grade 2 Hypertension. Please visit doctor",
                    'status' => 2
                ];
            } else {
                $action['BloodPressure'] = [
                    'detail' => "Grade 3 Hypertension. Please visit doctor",
                    'status' => 3
                ];
            }

            //for BP DP
            if ($bp_dp >= 90 && $bp_dp < 100) {
                if ($action['BloodPressure']["status"] < 1) {
                    $action['BloodPressure'] = [
                        'detail' => "Grade 1 Hypertension. Please visit doctor",
                        'status' => 3
                    ];
                }
            } else if ($bp_dp < 110) {
                if ($action['BloodPressure']["status"] < 2) {
                    $action['BloodPressure'] = [
                        'detail' => "Grade 2  Hypertension Please visit doctor",
                        'status' => 2
                    ];
                }
            } else {
                if ($action['BloodPressure']["status"] < 3) {
                    $action['BloodPressure'] = [
                        'detail' => "Grade 3 Hypertension Please urgently visit doctor",
                        'status' => 3
                    ];
                }
            }
        }

        return ['action' => $action, 'consultation' => $consultation];
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



            $this->response(array('error' => 'Successfully Logged Out'), 200);
        } else {
            $this->response(array('error' => 'No session exists to logout'), 400);
        }
    }

//insert data
    public function insert_post() {
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
            if (isset($this->request->body['bodytemperature']) && isset($this->request->body['bodyTemperatureTypeId']) && isset($this->request->body['bp_sp']) && isset($this->request->body['bp_dp']) && isset($this->request->body['symptoms']) && isset($this->request->body['comment']) && isset($this->request->body['latitude']) && isset($this->request->body['longitude']) && isset($this->request->body['userid'])) {

                $patientid = $this->request->body['patientid'];
                $bodytemperature = $this->request->body['bodytemperature'];
                $bodyTemperatureTypeId = $this->request->body['bodyTemperatureTypeId'];
                $bp_sp = $this->request->body['bp_sp'];
                $bp_dp = $this->request->body['bp_dp'];
                $symptoms = $this->request->body['symptoms'];
                $comment = $this->request->body['comment'];
                $latitude = $this->request->body['latitude'];
                $longitude = $this->request->body['longitude'];
                $userid = $this->request->body['userid'];

                $getAge = $this->db->query("SELECT TIMESTAMPDIFF(YEAR, dob, NOW()) as age FROM `patients` pt WHERE pt.id = " . $patientid);
                if ($getAge->num_rows() > 0) {
                    $row = $getAge->row();
                    $age = $row->age;
                }
                $data = [
                    "age" => $age,
                    "bodyTemperature" => $bodytemperature,
                    "bodyTemperatureTypeId" => $bodyTemperatureTypeId,
                    "bp_sp" => $bp_sp,
                    "bp_dp" => $bp_dp
                ];

                $nextAction = $this->getAction($data);

                $sql = "CALL insertWithPatientId(" . $patientid . ","
                        . $bodytemperature . ","
                        . $bodyTemperatureTypeId . ","
                        . $bp_sp . ","
                        . $bp_dp . ",'" . $symptoms . "','" . $comment . "'," . $latitude . "," . $longitude . "," . $userid . "," . $nextAction['consultation'] . ")";

                $query = $this->db->query($sql);

                if ($query->num_rows() > 0) {
                    $row = $query->row();

                    $patientDataId = $row->patientDataId;



                    $this->response(array('error' => 'No error', 'patientDataId' => $patientDataId, 'action' => $nextAction['action']), 200);
                } else {
                    $this->response(array('error' => 'Server error'), 500);
                }
            } else {
                $this->response(array('error' => 'Data not provided'), 400);
            }
        } else {
            if (isset($this->request->body['name']) && isset($this->request->body['dob']) && isset($this->request->body['gender']) && isset($this->request->body['height']) && isset($this->request->body['weight']) && isset($this->request->body['address']) && isset($this->request->body['bodytemperature']) && isset($this->request->body['bodyTemperatureTypeId']) && isset($this->request->body['bp_sp']) && isset($this->request->body['bp_dp']) && isset($this->request->body['symptoms']) && isset($this->request->body['comment']) && isset($this->request->body['latitude']) && isset($this->request->body['longitude']) && isset($this->request->body['userid'])) {


                $name = $this->request->body['name'];
                $dob = $this->request->body['dob'];
                $gender = $this->request->body['gender'];
                $height = $this->request->body['height'];
                $weight = $this->request->body['weight'];
                $address = $this->request->body['address'];
                $bodytemperature = $this->request->body['bodytemperature'];
                $bodyTemperatureTypeId = $this->request->body['bodyTemperatureTypeId'];
                $bp_sp = $this->request->body['bp_sp'];
                $bp_dp = $this->request->body['bp_dp'];
                $symptoms = $this->request->body['symptoms'];
                $comment = $this->request->body['comment'];
                $latitude = $this->request->body['latitude'];
                $longitude = $this->request->body['longitude'];
                $userid = $this->request->body['userid'];

//                $q = $this->db->query("SELECT * FROM patients WHERE  name ='"
//                        . $name . "' and dob ='" . $dob . "' and gender='"
//                        . $gender . "' and address='" . $address . "' and height="
//                        . $height . " and weight= " . $weight . " and userid=" . $userid);
//
//                if ($q->num_rows() > 0) {
//                    $this->response(array('error' => 'Data already exists'), 400);
//                } else {

                $data = [
                    "age" => date_diff(date_create($dob), date_create('today'))->y,
                    "bodyTemperature" => $bodytemperature,
                    "bodyTemperatureTypeId" => $bodyTemperatureTypeId,
                    "bp_sp" => $bp_sp,
                    "bp_dp" => $bp_dp
                ];

                $nextAction = $this->getAction($data);

                $sql = "CALL insertWithoutPatientId('" . $name . "', '" . $dob . "','" . $gender . "'," . $height . "," . $weight . ",'" . $address . "',"
                        . $bodytemperature . ","
                        . $bodyTemperatureTypeId . ","
                        . $bp_sp . ","
                        . $bp_dp . ",'" . $symptoms . "','" . $comment . "'," . $latitude . "," . $longitude . "," . $userid . "," . $nextAction['consultation'] . ")";

                $query = $this->db->query($sql);

                if ($query->num_rows() > 0) {
                    $row = $query->row();

                    $patientId = $row->patientId;
                    $patientDataId = $row->patientDataId;



                    $this->response(array('error' => 'No error'
                        , 'patientId' => $patientId
                        , 'patientDataId' => $patientDataId, 'action' => $nextAction['action']), 200);
                } else {
                    $this->response(array('error' => 'Server error'), 500);
                }
//}
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

    public function getPatientsListForUser_get($param) {
        if (!$this->get('id')) {
            $this->response(array('error=>id not provided'), 400);
        }

        $id = $this->get('id');
        $fetch = "SELECT p.id, p.name, p.gender, p.address, p.dob, p.height, p.weight FROM `consultation` c
                inner join `patients` p 
                on c.patientid = p.id
                WHERE c.assignedToUser=" . $id . " AND c.isChecked = 0";

        $q = $this->db->query($fetch);

        $this->response($q->result(), 200);
    }

    public function insertPatientActionDetails_post() {
        if (isset($this->request->body['patientdataid']) && isset($this->request->body['actiondetails'])) {

            $patientdataid = $this->request->body['patientdataid'];
            $actiondetails = $this->request->body['actiondetails'];

            $insert = "INSERT INTO `action`(`patientdataid`, `actiondetails`, `date`) "
                    . "VALUES (" . $patientdataid . ",'" . $actiondetails . "',NOW())";

            $q = $this->db->simple_query($insert);

            if ($q == 1) {
                $this->response(['error' => 'No error'], 200);
            } else {
                $this->response(['error' => 'Failed to insert data'], 500);
            }
        } else {
            $this->response(array('error' => 'Data not provided'), 400);
        }
    }

    public function getSymptoms_get() {

        $q = $this->db->query("CALL getSymptoms()");

        $this->response($q->result(), 200);
    }

}
