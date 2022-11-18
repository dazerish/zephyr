<?php

class Rfid extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Rfid_model');
    }

    //DEVICE RFID
    public function get_dev_data()
    {

        if (isset($_GET['card_uid']) && isset($_GET['device_token'])) {

            $card_uid = $this->input->get('card_uid');
            $device_token = $this->input->get('device_token');


            $arduino = $this->Rfid_model->get_device_uid($device_token);

            if (isset($arduino)) {
                if ($arduino->device_mode == 0) {
                    $rfid = $this->Rfid_model->check_dev_rfid($card_uid);

                    if($rfid) {
                        if($rfid->rfid == $card_uid) {
                            echo 'RFID is already in database';
                        }
                    } else {
                        $info = array(
                            'rfid' => $card_uid,
                            'device_uid' => $device_token
                        );
    
                        $this->Rfid_model->register_dev($info);
                        echo 'Success';
                    }
                }

                if ($arduino->device_mode == 1) {

                    $device = $this->Rfid_model->get_dev_tbldata($card_uid);

                    if (isset($device)) {
                        if($device->registered == 1) {
                            if($device->device_uid == $device_token || $device->device_uid == 0) {

                                $devLogs = $this->Rfid_model->get_dev_logs($card_uid);
                                if(!$devLogs) {
                                    $info = array(
                                        'unique_num' => $device->unique_num,
                                        'dev_name' => $device->dev_name,
                                        'rfid' => $card_uid,
                                        'device_uid' => $device_token,
                                        'date_issued' => date("Y-m-d H:i:s", strtotime('now')),
                                        'card_out' => 0 
                                    );
        
                                    $this->Rfid_model->dev_time_in($info, $card_uid);
                                    echo 'Time in: '. $card_uid;

                                } else {
                                    $info = array(
                                        'date_returned' => date("Y-m-d H:i:s", strtotime('now')),
                                        'card_out' => 1
                                    );

                                    $this->Rfid_model->dev_time_out($info, $card_uid);
                                    echo 'Time out: '. $card_uid;
                                }
                                
                            }
                                        
                        } else {
                            echo 'Rfid is not registered';
                        }
                        
                    } else {
                        echo 'Rfid is not in database';
                    }
                }
            }
        }
    }





    //EMPLOYEE RFID
    public function get_emp_data()
    {

        if (isset($_GET['card_uid']) && isset($_GET['user_token'])) {

            $card_uid = $this->input->get('card_uid');
            $user_token = $this->input->get('user_token');


            $arduino = $this->Rfid_model->get_device_uid($user_token);

            if (isset($arduino)) {
                if ($arduino->device_mode == 0) {
                    $rfid = $this->Rfid_model->check_emp_rfid($card_uid);

                    if($rfid) {
                        if($rfid->rfid == $card_uid) {
                            echo 'RFID is already in database';
                        }
                    } else {
                        $info = array(
                            'rfid' => $card_uid,
                            'emp_uid' => $user_token
                        );
    
                        $this->Rfid_model->register_emp($info);
                        echo 'Success';
                    }
                }

                if ($arduino->device_mode == 1) {

                    $employee = $this->Rfid_model->get_emp_tbldata($card_uid);

                    if (isset($employee)) {
                        if($employee->registered == 1) {
                            if($employee->emp_uid == $user_token || $employee->emp_uid == 0) {

                                $empLogs = $this->Rfid_model->get_emp_logs($card_uid);
                                if(!$empLogs) {
                                    $info = array(
                                        'emp_id' => $employee->emp_id,
                                        'emp_name' => $employee->emp_name,
                                        'rfid' => $card_uid,
                                        'emp_uid' => $user_token,
                                        'time_in' => date("Y-m-d H:i:s", strtotime('now')),
                                        'card_out' => 0 
                                    );
        
                                    $this->Rfid_model->emp_time_in($info, $card_uid);
                                    echo 'Time in: '. $card_uid;

                                } else {
                                    $info = array(
                                        'time_out' => date("Y-m-d H:i:s", strtotime('now')),
                                        'card_out' => 1
                                    );

                                    $this->Rfid_model->emp_time_out($info, $card_uid);
                                    echo 'Time out: '. $card_uid;
                                }
                                
                            }
                                        
                        } else {
                            echo 'Rfid is not registered';
                        }
                        
                    } else {
                        echo 'Rfid is not in database';
                    }
                }
            }
        }
    }

}
?>