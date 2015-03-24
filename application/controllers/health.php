<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Health extends CI_Controller {

    public function index() {
        $this->load->view('welcome_message');
    }

    public function admin() {

        $this->load->database();

        //print_r($_POST);

        if (isset($_POST['user'])) {
            if (isset($_POST['patients'])) {

                $sql = "UPDATE `consultation` SET `assignedToUser`=1 WHERE `patientId` IN (" . implode(",", $_POST['patients']) . ")";

                $query = $this->db->simple_query($sql);

                if ($query == 1) {
                    echo "<h2>Assigned successfully !!</h2>";
                } else {
                    echo "<h2>Sorry, couldnot be assigned !!!!</h2>";
                }
            } else {
                echo "<h2>Please select patients !!!!</h2>";
            }
        }

        $this->load->view('admin');
    }

}
