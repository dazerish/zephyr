<?php

class Employee_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    public function get_emp_row($id)
    {
        return $this->db->get_where('users', ['id' => $id])->row();
    }

    public function update_employee($id, $info)
    {
        $this->db->update('users', $info, ['id' => $id]);
    }



    //Borrowable Device Masterlist - include search function
    public function borrowableDev_count()
    {
        $this->db->where(['cur_status' => 'Available']);
        $this->db->like('allowed_roles', 'Employee');
        $this->db->from('devices');
        return $this->db->count_all_results();
    }


    public function get_devModel($limit, $start)
    {
        $sql = "SELECT dev_name, COUNT(dev_name) AS stock, cur_status, dev_image, dev_model, manufacturer
        FROM devices
        WHERE cur_status = 'Available' AND allowed_roles LIKE '%Employee%'
        GROUP BY dev_name
        HAVING COUNT(*)>0
        LIMIT $start, $limit";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_deviceModel($searchTerm, $model, $manufacturer) {
        $sql = "SELECT dev_name, COUNT(dev_name) AS stock, cur_status, dev_image, dev_model, manufacturer
        FROM devices
        WHERE (cur_status = 'Available' AND allowed_roles LIKE '%Employee%')
        AND (dev_name LIKE '%$searchTerm%' AND dev_model LIKE '%$model%' AND manufacturer LIKE '%$manufacturer%')
        GROUP BY dev_name
        HAVING COUNT(*)>0";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function reserveDev($dev_name)
    {

        $sql = "SELECT * FROM devices 
        WHERE dev_name = '$dev_name' AND cur_status = 'available'
        ORDER BY RAND()
        LIMIT 1";
        $query = $this->db->query($sql);
        return $query->result();

        // the result -> store in array -> count the elements in array -> use the count to pick a random number -> use the number as index to select a random element -> get the unique id of the result
    }

    public function set_reserveDate($info, $status_info, $unique_num)
    {
        $this->db->insert('transaction', $info);
        $this->db->update('devices', $status_info, ['unique_num' => $unique_num]);
    }



    //Device Masterlist
    public function get_devices_table($limit, $start) { //Device Masterlist
        $this->db->where('registered', 1);
        $this->db->limit($limit, $start);
        $this->db->order_by('id', 'DESC');
        return $this->db->get('devices')->result();
    }
    public function get_dCount()
    {
        return $this->db->count_all('devices');
    }
    public function get_dev_table($searchTerm, $model, $manufacturer, $status) { //Device Masterlist - search
        $this->db->like('dev_name', $searchTerm);
        $this->db->like('dev_model', $model);
        $this->db->like('manufacturer', $manufacturer);
        $this->db->like('cur_status', $status);
        $this->db->order_by('id', 'DESC');
        return $this->db->get('devices')->result();
    }
    public function total_dev() {
        $this->db->where('registered', 1);
        $this->db->from('devices');
        return $this->db->count_all_results();
    }
    public function get_dev_row($id)
    {
        return $this->db->get_where('devices', ['id' => $id])->row();
    }


    // public function count_devModel() {
    //     $this->db->select('*');
    //     $this->db->from('devices');
    //     $this->db->like('dev_model', 'Server');
    //     return $this->db->count_all_results();
    // }
}

?>
