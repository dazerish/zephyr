<?php

class Rfid_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    //DEVICE RFID
    public function get_device_uid($device_token) {
       $query = $this->db->get_where('arduino', ['device_uid' => $device_token]);
       return $query->row();
    }

    public function get_dev_tbldata($card_uid) {
        $sql = "SELECT * FROM devices WHERE rfid = '$card_uid'";
        $query = $this->db->query($sql);
        return $query->row();
    }
    public function check_dev_rfid($card_uid) {
        $sql = "SELECT * FROM devices WHERE rfid = '$card_uid'";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }
    public function register_dev($info) {
        $this->db->insert('devices', $info);
    }

    public function get_dev_logs($card_uid) {
        $query = $this->db->get_where('device_logs', ['rfid' => $card_uid, 'card_out' => 0]);
        return $query->row();
    }

    public function dev_time_in($info) {
        $this->db->insert('device_logs', $info);
    }

    public function dev_time_out($info, $card_uid) {
        $this->db->update('device_logs', $info, ['rfid' => $card_uid, 'card_out' => 0]);
    }



// ***********************************************************************************************


    //EMPLOYEE RFID
    public function get_emp_tbldata($card_uid) {
        $sql = "SELECT * FROM users WHERE rfid = '$card_uid'";
        $query = $this->db->query($sql);
        return $query->row();
    }
    public function check_emp_rfid($card_uid) {
        $sql = "SELECT * FROM users WHERE rfid = '$card_uid'";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }
    public function register_emp($info) {
        $this->db->insert('users', $info);
    }

    public function get_emp_logs($card_uid) {
        $query = $this->db->get_where('employee_logs', ['rfid' => $card_uid, 'card_out' => 0]);
        return $query->row();
    }

    public function emp_time_in($info) {
        $this->db->insert('employee_logs', $info);
    }

    public function emp_time_out($info, $card_uid) {
        $this->db->update('employee_logs', $info, ['rfid' => $card_uid, 'card_out' => 0]);
    }


}
?>