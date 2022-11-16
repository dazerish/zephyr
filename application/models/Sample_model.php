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

        //Locaiton API
        public function send_devLoc($info, $unique_num) {
            $this->db->update('devices', $info, ['unique_num' => $unique_num]);
        }

        //Profile API
        public function transacted_dev($emp_name) {
            // return $this->db->get_where('transaction', ['transaction_status' => 'Approved','borrower' => $emp_name])->result();
            $sql = "SELECT * FROM transaction
            WHERE borrower = '$emp_name' AND (transaction_status = 'Approved' OR transaction_status = 'Issued')
            ORDER BY transaction_id DESC
            LIMIT 5";
            $query = $this->db->query($sql);
            return $query->result();
        }

        //Transaction Logs API
        public function transaction_logs() {
            $query = $this->db->get('transaction')->result_array();
            return $query;
        }

        //Report API
        public function report($info, $status_info) {

        }
    }
?>
