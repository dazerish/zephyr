<?php

class Executive_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    //View Employee Masterlist
    public function get_users_table($limit, $start, $st = NULL)
    {
        if ($st == "NIL") $st = "";
        $sql = "SELECT * FROM users 
        WHERE emp_name LIKE '%$st%' 
        ORDER BY id DESC
        LIMIT " . $start . ", " . $limit;
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function get_users_count($st = NULL)
    {
        if ($st == "NIL") $st = "";
        $sql = "SELECT * FROM users 
        WHERE emp_name LIKE '%$st%'
        ORDER BY id DESC";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }
    public function get_uCount()
    {
        return $this->db->count_all('users');
    }
    public function transacted_dev($emp_name) {
        // return $this->db->get_where('transaction', ['transaction_status' => 'Approved','borrower' => $emp_name])->result();
        $sql = "SELECT * FROM transaction
        WHERE borrower = '$emp_name' AND transaction_status IN ('Approved','Deployed','Lost','Broken','Maintenance')
        ORDER BY transaction_id DESC
        LIMIT 5";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_emp_row($id)
    {
        return $this->db->get_where('users', ['id' => $id])->row();
    }

    //Reset Password
    public function update_employee($id, $info)
    {
        $this->db->update('users', $info, ['id' => $id]);
    }

    //Device Masterlist
    public function get_devices_table($limit, $start, $st = NULL)
    {
        if ($st == "NIL") $st = "";
        $sql = "select * from devices where dev_name like '%$st%' 
                or dev_model like '%$st%'
                or manufacturer like '%$st%'  
                limit " . $start . ", " . $limit;
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_devices_count($st = NULL)
    {
        if ($st == "NIL") $st = "";
        $sql = "select * from devices where dev_name like '%$st%' 
                or dev_model like '%$st%'
                or manufacturer like '%$st%'";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function get_dCount()
    {
        return $this->db->count_all('devices');
    }

    public function get_dev_row($id)
    {
        return $this->db->get_where('devices', ['id' => $id])->row();
    }

    //Borrowable Device List
    public function borrowableDev_count()
    {
        $this->db->where(['cur_status' => 'Available', 'allowed_roles' => 'Executive']);
        $this->db->from('devices');
        return $this->db->count_all_results();
    }


    public function get_devModel($limit, $start, $st = NULL)
    {   
        if ($st == "NIL") $st = "";
        $sql = "SELECT dev_name, COUNT(dev_name) AS stock, cur_status, dev_image
        FROM devices
        WHERE (cur_status = 'Available' AND allowed_roles = 'Executive')
        AND (dev_name LIKE '%$st%' OR dev_model LIKE '%$st%')
        GROUP BY dev_name
        HAVING COUNT(*)>0
        LIMIT $start, $limit";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function count_devModel($st = NULL)
    {
        if ($st == "NIL") $st = "";
        $sql = "SELECT dev_name, COUNT(dev_name) AS stock, cur_status, dev_image
        FROM devices
        WHERE (cur_status = 'Available' AND allowed_roles = 'Executive')
        AND (dev_name LIKE '%$st%' OR dev_model LIKE '%$st%')
        GROUP BY dev_name
        HAVING COUNT(*)>0";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function reserveDev($dev_name)
    {

        $sql = "SELECT * FROM devices 
        WHERE dev_name = '$dev_name' AND cur_status = 'Available'
        ORDER BY RAND()
        LIMIT 1";
        $query = $this->db->query($sql);
        return $query->result();

    }

    public function set_reserveDate($info, $status_info, $unique_num)
    {
        $this->db->insert('transaction', $info);
        $this->db->update('devices', $status_info, ['unique_num' => $unique_num]);
    }


    //Dashboard
    // Made by JL for admin dashboard, please move where appropriate
    public function executive_dashboard()
    {
        // pie chart query
        $pie_sql = "SELECT dev_model, COUNT(dev_model) AS device_count
        FROM devices
        GROUP BY dev_model
        HAVING COUNT(*)>0";
        $pie_query = $this->db->query($pie_sql);
        $pie_data = $pie_query->result();

        // DEVICE IN

        // count the number of devices where the status is available
        $device_in_sql = "SELECT COUNT(*) AS device_count
        FROM devices
        WHERE cur_status='Available'";
        $device_in_query = $this->db->query($device_in_sql);
        $device_in_data = $device_in_query->result();

        // DEVICE OUT

        // count the number of devices where the status is borrowed
        $device_out_sql = "SELECT COUNT(*) AS device_count
        FROM devices
        WHERE cur_status='Borrowed' OR cur_status='Deployed'";
        $device_out_query = $this->db->query($device_out_sql);
        $device_out_data = $device_out_query->result();

        // RESERVED

        // count the number of devices where the status is reserved
        $reserved_sql = "SELECT COUNT(*) AS device_count
        FROM devices
        WHERE cur_status='Reserved'";
        $reserved_query = $this->db->query($reserved_sql);
        $reserved_data = $reserved_query->result();

        // BROKEN DEVICES

        //count the number of devices where the status is broken
        $broken_sql = "SELECT COUNT(*) AS device_count
        FROM devices
        WHERE cur_status='Broken'";
        $broken_query = $this->db->query($broken_sql);
        $broken_data = $broken_query->result();

        // DEVICES IN MAINTENANCE

        //count the number of devices where the status is maintenance
        $maintenance_sql = "SELECT COUNT(*) AS device_count
        FROM devices
        WHERE cur_status='Maintenance'";
        $maintenance_query = $this->db->query($maintenance_sql);
        $maintenance_data = $maintenance_query->result();

        // pass data to dashboard
        $results = array($pie_data, $device_in_data, $device_out_data, $reserved_data, $broken_data, $maintenance_data);
        return $results;
    }


    //Generate Reports
    public function fetch_data($start_date, $end_date) {

        $sql = "SELECT * FROM transaction
        WHERE request_time BETWEEN '$start_date' AND '$end_date'
        ORDER BY transaction_id DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function fetch_dev_logs($start_date, $end_date) {
        $sql = "SELECT * FROM device_logs
        WHERE date_issued BETWEEN '$start_date' AND '$end_date'
        ORDER BY id DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    public function fetch_emp_logs($start_date, $end_date) {
        $sql = "SELECT * FROM employee_logs
        WHERE time_in BETWEEN '$start_date' AND '$end_date'
        ORDER BY id DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
?>