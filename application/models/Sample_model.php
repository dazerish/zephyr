<?php

    class Sample_model extends CI_model {
        public function __construct() {
            parent:: __construct();

            $this->load->database();
        }

        //Display Employee API
        public function display_emp() {
            $query = $this->db->get('users')->result_array();
            return $query;
        }

        //Display Devices API
        public function display_dev() {
            $query = $this->db->get('devices')->result_array();
            return $query;
        }

        //Display Devices under Specialized Category 
        public function display_specialized() {
            $query = $this->db->get_where('devices', ['category' => 'Specialized'])->result_array();
            return $query;
        }

        //Display Devices under Networking Category 
        public function display_networking() {
            $query = $this->db->get_where('devices', ['category' => 'Networking'])->result_array();
            return $query;
        }

        //Display Devices under Peripherals Category 
        public function display_peripherals() {
            $query = $this->db->get_where('devices', ['category' => 'Peripherals'])->result_array();
            return $query;
        }

        //Display Devices under Output Category 
        public function display_output() {
            $query = $this->db->get_where('devices', ['category' => 'Output'])->result_array();
            return $query;
        }

        //Display Devices under Processing Category 
        public function display_processing() {
            $query = $this->db->get_where('devices', ['category' => 'Processing'])->result_array();
            return $query;
        }
        
        //Display Devices - Employee
        public function emp_display_dev() {
            $query = $this->db->get_where('devices', ['allowed_roles' => 'Employee']);
            return $query->result_array();
        }

        //Locaiton API
        public function send_devLoc($info, $unique_num) {
            $this->db->update('devices', $info, ['unique_num' => $unique_num]);
        }
        //Device with Lat and Long
        public function specialized_devLatLong() {
            $sql = "SELECT * FROM devices WHERE category = 'Specialized'
            AND (latitude != '' AND longitude != '')";
            $query = $this->db->query($sql);
            return $query->result_array();
        }
        public function networking_devLatLong() {
            $sql = "SELECT * FROM devices WHERE category = 'Networking' 
            AND (latitude != '' AND longitude != '')";
            $query = $this->db->query($sql);
            return $query->result_array();
        }

        //Profile API
        public function transacted_dev($emp_name) {
            // return $this->db->get_where('transaction', ['transaction_status' => 'Approved','borrower' => $emp_name])->result();
            $sql = "SELECT * FROM transaction 
            WHERE borrower = '$emp_name' AND transaction_status IN ('Approved','Deployed','Lost','Broken','Maintenance')
            ORDER BY transaction_id DESC LIMIT 5";
            $query = $this->db->query($sql);
            return $query->result_array();
        }

        //Transaction Logs API
        public function transaction_logs() {
            $query = $this->db->get('transaction')->result_array();
            return $query;
        }
        //Admin Transaction Logs
        public function admin_trans_logs() {
            $sql = "SELECT * FROM transaction WHERE transaction_status IN ('Lost','Broken','Maintenance')";
            $query = $this->db->query($sql);
            return $query->result_array();
        }

        //Report API
        // public function check_transaction_status($unique_num) {
        //     $query = $this->db->get_where('transaction', ['transaction_status' => 'Issued', 'borrowedDev_id' => $unique_num]);
        //     return $query;
        // }
        public function report($trans_info, $status_info, $unique_num) {
            $this->db->update('transaction', $trans_info, ['borrowedDev_id' => $unique_num]);
            $this->db->update('devices', $status_info, ['unique_num' => $unique_num]);
        }

        //Report -- Admin
        public function report_dev($transaction_info, $status_info, $unique_num) {
            $this->db->update('transaction', $transaction_info, ['borrowedDev_id' => $unique_num]);
            $this->db->update('devices', $status_info, ['unique_num' => $unique_num]);
        }

        //Device Approval API
        public function get_transaction_table() {
            $sql = "SELECT * FROM transaction
            WHERE transaction_status = 'Pending'
            ORDER BY transaction_id DESC";
            $query = $this->db->query($sql);
            return $query->result_array();
        }
        public function reject_device($transaction_status, $status_info, $unique_num) {
            $this->db->update('transaction', $transaction_status, ['borrowedDev_id' => $unique_num]);
            $this->db->update('devices', $status_info, ['unique_num' => $unique_num]);
        }
        public function approve_device($transaction_status, $status_info, $unique_num) {
            $this->db->update('transaction', $transaction_status, ['borrowedDev_id' => $unique_num]);
            $this->db->update('devices', $status_info, ['unique_num' => $unique_num]);
        }

        //Device Details - Admin Side
        public function get_dev_details($unique_num) {
            $sql = "SELECT * FROM transaction
            WHERE borrowedDev_id = '$unique_num' 
            AND transaction_status IN ('Approved','Deployed','Lost','Broken','Maintenance')";
            $query = $this->db->query($sql);
            return $query->result();
        }

        //Notification API 
        public function get_admin_notif_status() {
            $sql = "SELECT * FROM transaction WHERE notif_status = 0 AND transaction_status IN ('Pending','Broken','Lost','Maintenance')";
            $query = $this->db->query($sql);
            return $query->result_array();
        }
        public function get_exec_notif_status() {
            $sql = "SELECT * FROM transaction WHERE notif_status = 0 AND transaction_status IN ('Deployed','Overdue')";
            $query = $this->db->query($sql);
            return $query->result_array();
        }
        public function get_employee_notif_status() {
            $sql = "SELECT * FROM transaction WHERE notif_status = 0 AND transaction_status IN ('Pending','Approved','Rejected','Overdue')";
            $query = $this->db->query($sql);
            return $query->result_array();
        }
        public function upd_notif_status($notif_status, $trans_id) {
            $this->db->update('transaction', $notif_status, ['transaction_id' => $trans_id]);
        }


        //Employee Borrowable Device List
        public function emp_borrowable_list() {
            $query = $this->db->get_where('devices', ['cur_status' => 'Available', 'allowed_roles' => 'Employee']);
            return $query->result_array();
        }


        //Executive Borrowable Device List
        public function exec_borrowable_list() {
            $query = $this->db->get_where('devices', ['cur_status' => 'Available', 'allowed_roles' => 'Executive']);
            return $query->result_array();
        }

    }
?>
