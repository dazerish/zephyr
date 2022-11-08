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

}
?>