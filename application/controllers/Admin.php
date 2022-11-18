<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper(['form', 'url', 'string']);
        $this->load->library(['form_validation', 'session', 'pagination']);
        $this->load->model('Admin_model');
    }

    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('Login');
        }

        if ($this->session->userdata('role') == 'employee') {
            redirect('Employee');
        }

        if ($this->session->userdata('role') == 'executive') {
            redirect('Executive');
        }

        $data['title'] = 'Calibr8 - Admin Dashboard';
        $data['dashboard_data'] = $this->Admin_model->admin_dashboard();
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_dashboard_view', $data);
        $this->load->view('include/footer');
    }

    //Employee Masterlist
    public function emp_masterlist_view()
    {
        $page_config = array(
            'base_url' => site_url('Admin/emp_masterlist_view'),
            'total_rows' => $this->Admin_model->get_uCount(),
            'num_links' => 3,
            'per_page' => 5,

            'full_tag_open' => '<div class="d-flex justify-content-center"><ul class="pagination">',
            'full_tag_close' => '</ul></div>',

            'first_link' => FALSE,
            'last_link' => FALSE,

            'next_link' => '&rsaquo;',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',

            'prev_link' => '&lsaquo;',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',

            'cur_tag_open' => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close' => '</span></li>',

            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',

            'attributes' => ['class' => 'page-link']
        );

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($page_config);

        $data['title'] = 'Calibr8 - Employee Masterlist';
        $data['employees'] = $this->Admin_model->get_users_table($page_config['per_page'], $page, NULL);
        $data['total'] = $this->Admin_model->get_uCount();
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_emp_masterlist');
        $this->load->view('include/footer');
    }

    public function searchEmp()
    { //Temporary Search Function
        $search = ($this->input->post("searchTerm")) ? $this->input->post("searchTerm") : "NIL";
        $search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;

        $page_config = array(
            'base_url' => site_url('Admin/searchEmp/$search'),
            'total_rows' => $this->Admin_model->get_users_count($search),
            'num_links' => 3,
            'per_page' => 5,

            'full_tag_open' => '<div class="d-flex justify-content-center"><ul class="pagination">',
            'full_tag_close' => '</ul></div>',

            'first_link' => FALSE,
            'last_link' => FALSE,

            'next_link' => '&rsaquo;',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',

            'prev_link' => '&lsaquo;',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',

            'cur_tag_open' => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close' => '</span></li>',

            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',

            'attributes' => ['class' => 'page-link']
        );

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $this->pagination->initialize($page_config);

        $data['title'] = 'Calibr8 - Employee Masterlist';
        $data['employees'] = $this->Admin_model->get_users_table($page_config['per_page'], $page, $search);
        $data['total'] = $this->Admin_model->get_uCount();
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_emp_masterlist');
        $this->load->view('include/footer');
    }

    public function employee_view($id)
    { //Under Employee Masterlist
        $data['title'] = "Calibr8 - View Employee Details";
        $data['employee'] = $this->Admin_model->get_emp_row($id);
        $emp_name = $data['employee']->emp_name;
        $data['transacted_dev'] = $this->Admin_model->transacted_dev($emp_name);

        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_employee_view', $data);
        $this->load->view('include/footer');
    }

    public function remove_employee($id)
    { //Temporary remove func?
        $this->Admin_model->remove_employee($id);
        redirect('Admin/emp_masterlist_view');
    }

    public function editEmp_view($id)
    { //Under Employee Masterlist
        $data['title'] = "Calibr8 - Edit Employee Details";
        $data['employee'] = $this->Admin_model->get_emp_row($id);

        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_editEmp_view', $data);
        $this->load->view('include/footer');
    }

    public function editEmp_details()
    {
        $image_config = array(
            'upload_path' => './assets/user_image',
            'allowed_types' => 'gif|jpg|png',
            'max_size' => 5000000000,
            'max_width' => 204800,
            'max_height' => 204800
        );

        $this->load->library('upload', $image_config);
        $this->upload->initialize($image_config);

        $this->form_validation->set_rules('empname', 'Employee Name', 'required', array(
            'requied' => '%s is required.'
        ));

        $this->form_validation->set_rules('roles', 'Employee Roles', 'required', array(
            'requied' => '%s is required.'
        ));

        $this->form_validation->set_rules('rfid', 'RFID', 'required', array(
            'requied' => '%s is required.'
        ));

        if ($this->upload->do_upload('employee_image') == FALSE) {
            $this->form_validation->set_rules('employee_image', 'Employee Image', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $id = $this->input->post('emp-id');
            $this->editEmp_view($id);
        } else {
            $image_name = (!$this->upload->do_upload('employee_image')) ? null : $this->upload->data('file_name');
            $save = $this->input->post('reg-dev');

            if (isset($save)) {

                $id = $this->input->post('emp-id');
                $info = array(
                    'emp_name' => $this->input->post('empname'),
                    'emp_role' => $this->input->post('roles'),
                    'emp_image' => $image_name,
                    'rfid' => $this->input->post('rfid')
                );

                $this->Admin_model->update_employee($id, $info);

                $success = "Employee details is updated successfully";
                $this->session->set_flashdata('success', $success);
                $this->editEmp_view($id);
            }
        }

        $cancel = $this->input->post('cancel-btn');

        if (isset($cancel)) {
            redirect('Admin/emp_masterlist_view');
        }
    }


    //Device Masterlist
    public function dev_masterlist_view()
    {
        $page_config = array(
            'base_url' => site_url('Admin/dev_masterlist_view'),
            'total_rows' => $this->Admin_model->get_dCount(),
            'num_links' => 3,
            'per_page' => 5,

            'full_tag_open' => '<div class="d-flex justify-content-center"><ul class="pagination">',
            'full_tag_close' => '</ul></div>',

            'first_link' => FALSE,
            'last_link' => FALSE,

            'next_link' => '&rsaquo;',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',

            'prev_link' => '&lsaquo;',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',

            'cur_tag_open' => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close' => '</span></li>',

            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',

            'attributes' => ['class' => 'page-link']
        );

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($page_config);

        $data['title'] = 'Calibr8 - Device Masterlist';
        $data['devices'] = $this->Admin_model->get_devices_table($page_config['per_page'], $page, NULL);
        $data['total'] = $this->Admin_model->total_dev();
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_dev_masterlist');
        $this->load->view('include/footer');
    }

    public function searchDev()
    { //Temporary Search Function
        $search = ($this->input->post("searchTerm")) ? $this->input->post("searchTerm") : "NIL";
        $search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;

        $page_config = array(
            'base_url' => site_url('Admin/searchDev/$search'),
            'total_rows' => $this->Admin_model->get_devices_count($search),
            'num_links' => 3,
            'per_page' => 5,

            'full_tag_open' => '<div class="d-flex justify-content-center"><ul class="pagination">',
            'full_tag_close' => '</ul></div>',

            'first_link' => FALSE,
            'last_link' => FALSE,

            'next_link' => '&rsaquo;',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',

            'prev_link' => '&lsaquo;',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',

            'cur_tag_open' => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close' => '</span></li>',

            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',

            'attributes' => ['class' => 'page-link']
        );

        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $this->pagination->initialize($page_config);

        $data['title'] = 'Calibr8 - Employee Masterlist';
        $data['devices'] = $this->Admin_model->get_devices_table($page_config['per_page'], $page, $search);
        $data['total'] = $this->Admin_model->get_dCount();
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_dev_masterlist');
        $this->load->view('include/footer');
    }

    public function device_view($id)
    { //Under device masterlist
        $data['title'] = "Calibr8 - View Device Details";
        $data['device'] = $this->Admin_model->get_dev_row($id);

        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_device_view', $data);
        $this->load->view('include/footer');
    }

    public function device_status() {
        $deployed = $this->input->post('Deployed');
        $returned = $this->input->post('Returned');
        $overdue = $this->input->post('Overdue');
        $lost = $this->input->post('Lost');
        $broken = $this->input->post('Broken');
        $repaired = $this->input->post('Repaired');
        $recovered = $this->input->post('Recovered');
        $maintenance = $this->input->post('Maintenance');
        $decommissioned = $this->input->post('Decommissioned');
        // $unique_num = $this->input->post('unique-num');

        if(isset($deployed)) { //Deployed
            $id = $this->input->post('dev-id');
            $unique_num = $this->input->post('unique_num');
            $device_info = array(
                'cur_status' => 'Deployed',
                'prev_status' => 'Borrowed'
            );

            $trans_info = array(
                'transaction_status' => 'Deployed',
                'request_time' => date("Y-m-d H:i:s", strtotime('now'))
            );

            $this->Admin_model->update_status($device_info, $trans_info, $unique_num, $id);
            $updated = "Device status was updated";
            $this->session->set_flashdata('updated', $updated);
            $this->device_view($id);
        }

        if(isset($returned)) { //Returned
            $id = $this->input->post('dev-id');
            $unique_num = $this->input->post('unique_num');
            $device_info = array(
                'cur_status' => 'Returned',
                'prev_status' => 'Deployed'
            );

            $trans_info = array(
                'transaction_status' => 'Returned',
                'request_time' => date("Y-m-d H:i:s", strtotime('now'))
            );

            $this->Admin_model->update_status($device_info, $trans_info, $unique_num, $id);
            $updated = "Device status was updated";
            $this->session->set_flashdata('updated', $updated);
            $this->device_view($id);
        }

        if(isset($overdue)) { //Overdue
            $id = $this->input->post('dev-id');
            $unique_num = $this->input->post('unique_num');
            $device_info = array(
                'cur_status' => 'Overdue',
                'prev_status' => 'Deployed'
            );

            $trans_info = array(
                'transaction_status' => 'Overdue',
                'request_time' => date("Y-m-d H:i:s", strtotime('now'))
            );

            $this->Admin_model->update_status($device_info, $trans_info, $unique_num, $id);
            $updated = "Device status was updated";
            $this->session->set_flashdata('updated', $updated);
            $this->device_view($id);
        }

        if(isset($lost)) { //Lost
            $id = $this->input->post('dev-id');
            $unique_num = $this->input->post('unique_num');
            $device_info = array(
                'cur_status' => 'Lost',
                'prev_status' => 'Deployed'
            );

            $trans_info = array(
                'transaction_status' => 'Lost',
                'request_time' => date("Y-m-d H:i:s", strtotime('now')),
                'decision_time' => '00-00-00 00:00:00',
                'return_date' => '00-00-00 00:00:00'
            );

            $this->Admin_model->update_status($device_info, $trans_info, $unique_num, $id);
            $updated = "Device status was updated";
            $this->session->set_flashdata('updated', $updated);
            $this->device_view($id);
        }

        if(isset($broken)) { //Broken
            $id = $this->input->post('dev-id');
            $unique_num = $this->input->post('unique_num');
            $device_info = array(
                'cur_status' => 'Broken',
                'prev_status' => 'Deployed'
            );

            $trans_info = array(
                'transaction_status' => 'Broken',
                'request_time' => date("Y-m-d H:i:s", strtotime('now')),
                'decision_time' => '00-00-00 00:00:00',
                'return_date' => '00-00-00 00:00:00'
            );

            $this->Admin_model->update_status($device_info, $trans_info, $unique_num, $id);
            $updated = "Device status was updated";
            $this->session->set_flashdata('updated', $updated);
            $this->device_view($id);
        }

        if(isset($repaired)) { //Repaired
            $id = $this->input->post('dev-id');
            $unique_num = $this->input->post('unique_num');
            $device_info = array(
                'cur_status' => 'Available',
                'prev_status' => 'Maintenance'
            );

            $trans_info = array(
                'transaction_status' => 'Repaired',
                'request_time' => date("Y-m-d H:i:s", strtotime('now')),
                'decision_time' => '00-00-00 00:00:00',
                'return_date' => '00-00-00 00:00:00'
            );

            $this->Admin_model->update_status($device_info, $trans_info, $unique_num, $id);
            $updated = "Device status was updated";
            $this->session->set_flashdata('updated', $updated);
            $this->device_view($id);
        }

        if(isset($recovered)) { //Recovered
            $id = $this->input->post('dev-id');
            $unique_num = $this->input->post('unique_num');
            $device_info = array(
                'cur_status' => 'Available',
                'prev_status' => 'Lost'
            );

            $trans_info = array(
                'transaction_status' => 'Recovered',
                'request_time' => date("Y-m-d H:i:s", strtotime('now')),
                'decision_time' => '00-00-00 00:00:00',
                'return_date' => '00-00-00 00:00:00'
            );

            $this->Admin_model->update_status($device_info, $trans_info, $unique_num, $id);
            $updated = "Device status was updated";
            $this->session->set_flashdata('updated', $updated);
            $this->device_view($id);
        }

        if(isset($maintenance)) { //Maintenance
            $id = $this->input->post('dev-id');
            $unique_num = $this->input->post('unique_num');
            $device_info = array(
                'cur_status' => 'Maintenance',
                'prev_status' => 'Broken'
            );

            $trans_info = array(
                'transaction_status' => 'Maintenance',
                'request_time' => date("Y-m-d H:i:s", strtotime('now')),
                'decision_time' => '00-00-00 00:00:00',
                'return_date' => '00-00-00 00:00:00'
            );

            $this->Admin_model->update_status($device_info, $trans_info, $unique_num, $id);
            $updated = "Device status was updated";
            $this->session->set_flashdata('updated', $updated);
            $this->device_view($id);
        }

        if(isset($decommissioned)) { //Decommisioned
            $id = $this->input->post('dev-id');
            $unique_num = $this->input->post('unique_num');
            $device_info = array(
                'cur_status' => 'Decommissioned',
                'prev_status' => 'None'
            );

            $this->Admin_model->status_decommissioned($device_info, $unique_num);
            $decom = "Device was decommissioned";
            $this->session->set_flashdata('decom', $decom);
            $this->device_view($id);
        }

    }

    public function remove_device($id)
    { //Temporary remove func?
        $this->Admin_model->remove_device($id);
        redirect('Admin/dev_masterlist_view');
    }

    public function editDev_view($id)
    { //Under device masterlist
        $data['title'] = "Calibr8 - Edit Device Details";
        $data['device'] = $this->Admin_model->get_dev_row($id);

        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_editDev_view', $data);
        $this->load->view('include/footer');
    }

    public function editDev_details()
    {
        $image_config = array(
            'upload_path' => './assets/device_image',
            'allowed_types' => 'gif|jpg|png',
            'max_size' => 5000000000,
            'max_width' => 204800,
            'max_height' => 204800
        );

        $this->load->library('upload', $image_config);
        $this->upload->initialize($image_config);

        $this->form_validation->set_rules('devicename', 'Device Name', 'required', array(
            'required' => '%s is required.'
        ));

        $this->form_validation->set_rules('roles', 'Allowed Roles', 'required', array(
            'required' => '%s is required.'
        ));

        $this->form_validation->set_rules('rfid', 'RFID', 'required', array(
            'required' => '%s is required.'
        ));

        $this->form_validation->set_rules('prev_device_status', 'Previous Device Status', 'required', array(
            'required' => '%s is required.'
        ));

        $this->form_validation->set_rules('cur_device_status', 'Current Device Status', 'required', array(
            'required' => '%s is required.'
        ));

        if ($this->upload->do_upload('device_image') == FALSE) {
            $this->form_validation->set_rules('device_image', 'Device Image', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $id = $this->input->post('dev-id');
            $this->editDev_view($id);
        } else {
            $image_name = (!$this->upload->do_upload('device_image')) ? null : $this->upload->data('file_name');
            $save = $this->input->post('reg-dev');

            if (isset($save)) {

                $id = $this->input->post('dev-id');
                $info = array(
                    'dev_name' => $this->input->post('devicename'),
                    'allowed_roles' => $this->input->post('roles'),
                    'rfid' => $this->input->post('rfid'),
                    'prev_status' => $this->input->post('prev_device_status'),
                    'cur_status' => $this->input->post('cur_device_status'),
                    'dev_image' => $image_name
                );

                $this->Admin_model->update_device($id, $info);

                $success = "Device details is updated successfully";
                $this->session->set_flashdata('success', $success);
                $this->editDev_view($id);
            }
        }

        $cancel = $this->input->post('cancel-btn');

        if (isset($cancel)) {
            redirect('Admin/dev_masterlist_view');
        }
    }


    //Device Approval List
    public function devApproval_view() 
    {
        $page_config = array(
            'base_url' => site_url('Admin/devApproval_view'),
            'total_rows' => $this->Admin_model->get_transaction_count(),
            'num_links' => 3,
            'per_page' => 5,

            'full_tag_open' => '<div class="d-flex justify-content-center"><ul class="pagination">',
            'full_tag_close' => '</ul></div>',

            'first_link' => FALSE,
            'last_link' => FALSE,

            'next_link' => '&rsaquo;',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',

            'prev_link' => '&lsaquo;',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',

            'cur_tag_open' => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close' => '</span></li>',

            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',

            'attributes' => ['class' => 'page-link']
        );

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($page_config);

        $data['title'] = 'Calibr8 - Device Approval List';
        $data['transactions'] = $this->Admin_model->get_transaction_table($page_config['per_page'], $page);
        $data['total'] = $this->Admin_model->pending_count();
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_devApproval_view');
        $this->load->view('include/footer');
    }

    public function reject_device() 
    {
        $transaction_status = array(
            'transaction_status' => 'Rejected',
            'request_time' => date("Y-m-d H:i:s", strtotime('now'))
        );

        $status_info = array(
            'cur_status' => 'Available',
            'prev_status' => 'Reserved'
        );

        $transaction_id = $this->uri->segment(3);
        $borrowedDev_id = $this->uri->segment(4);

        $this->Admin_model->reject_device($transaction_status, $status_info, $transaction_id, $borrowedDev_id);
        $rejected = "The device was rejected.";
        $this->session->set_flashdata('rejected', $rejected);
        redirect('Admin/devApproval_view');
    }

    public function approve_device()
    {
        $transaction_status = array(
            'transaction_status' => 'Approved',
            'request_time' => date("Y-m-d H:i:s", strtotime('now'))
        );

        $status_info = array(
            'cur_status' => 'Borrowed',
            'prev_status' => 'Reserved'
        );

        $transaction_id = $this->uri->segment(3);
        $borrowedDev_id = $this->uri->segment(4);

        $this->Admin_model->approve_device($transaction_status, $status_info, $transaction_id, $borrowedDev_id);
        $approved = "The device was approved.";
        $this->session->set_flashdata('approved', $approved);
        redirect('Admin/devApproval_view');
    }


    //Transaction Logs
    public function transaction_logs() {

        $page_config = array(
            'base_url' => site_url('Admin/transaction_logs'),
            'total_rows' => $this->Admin_model->transaction_count(),
            'num_links' => 3,
            'per_page' => 10,

            'full_tag_open' => '<div class="d-flex justify-content-center"><ul class="pagination">',
            'full_tag_close' => '</ul></div>',

            'first_link' => FALSE,
            'last_link' => FALSE,

            'next_link' => '&rsaquo;',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',

            'prev_link' => '&lsaquo;',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',

            'cur_tag_open' => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close' => '</span></li>',

            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>',

            'attributes' => ['class' => 'page-link']
        );

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($page_config);

        $data['title'] = 'Calibr8 - Transaction Logs';
        $data['transactions'] = $this->Admin_model->transaction_table($page_config['per_page'], $page);
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_transaction_logs_view');
        $this->load->view('include/footer');
    }


    //Device Logs - Try to implement pagination
    public function device_logs() {
        $data['title'] = 'Calibr8 - Device Logs';
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_devLogs_view');
        $this->load->view('include/footer');
        
    }
    public function dev_logs_table() {
        $dev_logs = $this->Admin_model->dev_logs_table();

        foreach ($dev_logs as $logs) {
            echo "<tr class='align-middle'>";
                echo "<td data-label='Device ID'>".$logs->unique_num."</td>";
                echo "<td data-label='Device Name'>".$logs->dev_name."</td>";
                echo "<td data-label='RFID'>".$logs->rfid."</td>";
                echo "<td data-label='Date Issued'>".$logs->date_issued."</td>";
                echo "<td data-label='Date Returned'>".$logs->date_returned."</td>";
            echo "</tr>";
        }

    }

    //Employee Logs
    public function employee_logs() {
        $data['title'] = 'Calibr8 - Employee Logs';
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_empLogs_view');
        $this->load->view('include/footer');
        
    }
    public function emp_logs_table() {
        $emp_logs = $this->Admin_model->emp_logs_table();

        foreach ($emp_logs as $logs) {
            echo "<tr class='align-middle'>";
                echo "<td data-label='Employee ID'>".$logs->emp_id."</td>";
                echo "<td data-label='Employee Name'>".$logs->emp_name."</td>";
                echo "<td data-label='RFID'>".$logs->rfid."</td>";
                echo "<td data-label='Time In'>".$logs->time_in."</td>";
                echo "<td data-label='Time Out'>".$logs->time_out."</td>";
            echo "</tr>";
        }

    }


    //Generate Reports
    public function generate_reports() {

        $data['title'] = 'Calibr8 - Generate Reports';
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_generate_reports');
        $this->load->view('include/footer');
    }

    public function export_csv() {
        //Try to put date validation

        $this->form_validation->set_rules('start_date', 'Start Date', 'required', array(
            'required' => '%s is required.'
        ));

        $this->form_validation->set_rules('end_date', 'End Date', 'required', array(
            'required' => '%s is required.'
        ));

        if($this->form_validation->run() == FALSE) {
            $this->generate_reports();
        } else {
            $generate_report = $this->input->post('generate-report');

            if(isset($generate_report)) {
                $s_date = $this->input->post('start_date');
                $e_date = $this->input->post('end_date');
                $start_date = date("Y-m-d H:i:s", strtotime($s_date));
                $end_date = date("Y-m-d H:i:s", strtotime($e_date));
                // $system_data = $this->Admin_model->fetch_data($start_date, $end_date);

                //Transaction Logs
                $spreadsheet = new Spreadsheet();
                $sheet1 = $spreadsheet->setActiveSheetIndex(0)->setTitle('Transaction Logs');
                
                foreach(range('A','H') as $coulumID) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($coulumID)->setAutosize(true);

                }
                $sheet1->getStyle('A:H')->getAlignment()->setHorizontal('center');
                
                $sheet1->setCellValue('A1','Report for the date of '.$s_date.' to '.$e_date);
                $sheet1->setCellValue('A2','Transaction ID');
                $sheet1->setCellValue('B2','Transaction Status');
                $sheet1->setCellValue('C2','Borrower');
                $sheet1->setCellValue('D2','Device ID');
                $sheet1->setCellValue('E2','Device Name');
                $sheet1->setCellValue('F2','Reserved Date');
                $sheet1->setCellValue('G2','Return Date');
                $sheet1->setCellValue('H2','Timestamp');

                $system_data = $this->Admin_model->fetch_data($start_date, $end_date);
                $x=3; //start from row 2
                foreach($system_data as $row)
                {
                    $sheet1->setCellValue('A'.$x, $row['transaction_id']);
                    $sheet1->setCellValue('B'.$x, $row['transaction_status']);
                    $sheet1->setCellValue('C'.$x, $row['borrower']);
                    $sheet1->setCellValue('D'.$x, $row['borrowedDev_id']);
                    $sheet1->setCellValue('E'.$x, $row['borrowedDev_name']);
                    $sheet1->setCellValue('F'.$x, $row['decision_time']);
                    $sheet1->setCellValue('G'.$x, $row['return_date']);
                    $sheet1->setCellValue('H'.$x, $row['request_time']);
                    $x++;
                }

                //---------------------------------------------------------------
                //Device Logs
                $spreadsheet->createSheet();
                $sheet2 = $spreadsheet->setActiveSheetIndex(1)->setTitle('Device Logs');

                foreach(range('A','G') as $coulumID) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($coulumID)->setAutosize(true);

                }
                $sheet2->getStyle('A:G')->getAlignment()->setHorizontal('center');
                
                $sheet2->setCellValue('A1','Report for the date of '.$s_date.' to '.$e_date);
                $sheet2->setCellValue('A2','Device Logs ID');
                $sheet2->setCellValue('B2','Device Unique ID');
                $sheet2->setCellValue('C2','Device Name');
                $sheet2->setCellValue('D2','RFID');
                $sheet2->setCellValue('E2','Device UID');
                $sheet2->setCellValue('F2','Date Deployed');
                $sheet2->setCellValue('G2','Date Returned');

                $system_data1 = $this->Admin_model->fetch_dev_logs($start_date, $end_date);
                $x=3; //start from row 2
                foreach($system_data1 as $row)
                {
                    $sheet2->setCellValue('A'.$x, $row['id']);
                    $sheet2->setCellValue('B'.$x, $row['unique_num']);
                    $sheet2->setCellValue('C'.$x, $row['dev_name']);
                    $sheet2->setCellValue('D'.$x, $row['rfid']);
                    $sheet2->setCellValue('E'.$x, $row['device_uid']);
                    $sheet2->setCellValue('F'.$x, $row['date_issued']);
                    $sheet2->setCellValue('G'.$x, $row['date_returned']);
                    $x++;
                }

                //---------------------------------------------------------------
                //Employee Logs
                $spreadsheet->createSheet();
                $sheet3 = $spreadsheet->setActiveSheetIndex(2)->setTitle('Employee Logs');

                foreach(range('A','G') as $coulumID) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($coulumID)->setAutosize(true);

                }
                $sheet3->getStyle('A:G')->getAlignment()->setHorizontal('center');
                
                $sheet3->setCellValue('A1','Report for the date of '.$s_date.' to '.$e_date);
                $sheet3->setCellValue('A2','Employee Logs ID');
                $sheet3->setCellValue('B2','Employee ID');
                $sheet3->setCellValue('C2','Employee Name');
                $sheet3->setCellValue('D2','RFID');
                $sheet3->setCellValue('E2','Employee UID');
                $sheet3->setCellValue('F2','Time Deployed');
                $sheet3->setCellValue('G2','Time Returned');

                $system_data2 = $this->Admin_model->fetch_emp_logs($start_date, $end_date);
                $x=3; //start from row 2
                foreach($system_data2 as $row)
                {
                    $sheet3->setCellValue('A'.$x, $row['id']);
                    $sheet3->setCellValue('B'.$x, $row['emp_id']);
                    $sheet3->setCellValue('C'.$x, $row['emp_name']);
                    $sheet3->setCellValue('D'.$x, $row['rfid']);
                    $sheet3->setCellValue('E'.$x, $row['emp_uid']);
                    $sheet3->setCellValue('F'.$x, $row['time_in']);
                    $sheet3->setCellValue('G'.$x, $row['time_out']);
                    $x++;
                }

                $writer = new Xlsx($spreadsheet);
                $fileName = 'DEVIN_system-report.xlsx';
                //$writer->save($fileName);  //this is for save in folder


                /* for force download */
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="'.$fileName.'"');
                $writer->save('php://output');
                /* force download end */
            }
        }
        
    }

    //RFID Mode
    public function rfid_mode_view() {

        $data['title'] = 'Calibr8 - RFID Mode';
        $data['arduino'] = $this->Admin_model->get_arduino_data();
        // $data['employee'] = $this->Admin_model->get_arduino_employee();
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_rfid_mode');
        $this->load->view('include/footer');
    }
    public function arduino_registration() {
        $info = array(
            'device_mode' => 0
        );

        $arduino_id = $this->uri->segment(3);

        $this->Admin_model->registration_mode($info, $arduino_id);
        $registration = "Arduino ".$arduino_id." was set to Registration";
        $this->session->set_flashdata('registration', $registration);
        redirect('Admin/rfid_mode_view');

    }
    public function arduino_attendance() {
        $info = array(
            'device_mode' => 1
        );

        $arduino_id = $this->uri->segment(3);

        $this->Admin_model->attendance_mode($info, $arduino_id);
        $attendance = "Arduino ".$arduino_id." was set to Attendance";
        $this->session->set_flashdata('attendance', $attendance);
        redirect('Admin/rfid_mode_view');
    }




    //Registration Section
    public function empReg_view()
    {
        $data['title'] = 'Calibr8 - Employee Registration';
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_empReg_view');
        $this->load->view('include/footer');
    }
    public function empReg_rfid() {
        $rfid_num = $this->Admin_model->get_empRfid(); 
        if($rfid_num) {
            foreach($rfid_num as $key) {
                $rfid = $key->rfid;
                echo "<input type='text' id='rfid_num' name='rfidNum' value='".$rfid."'><br>";
            }
        } else {
            echo "<input type='text' id='rfid_num' name='rfidNum' value=''><br>";
        }
    }

    public function employee_registration()
    {
        $image_config = array(
            'upload_path' => './assets/users_image',
            'allowed_types' => 'gif|jpg|png',
            'max_size' => 5000000000,
            'max_width' => 204800,
            'max_height' => 204800
        );

        $this->load->library('upload', $image_config);
        $this->upload->initialize($image_config);

        $this->form_validation->set_rules('empid', 'Employee ID', 'required|is_unique[users.emp_id]', array(
            'required' => '%s is required.',
            'is_unique' => 'This %s is already registered.'
        ));
        $this->form_validation->set_rules('empname', 'Employee Name', 'required', array(
            'required' => '%s is required.'
        ));
        $this->form_validation->set_rules('email', 'Employee Email', 'required|valid_email|is_unique[users.emp_email]', array(
            'required' => '%s is required.',
            'valid_email' => 'Please enter a valid %s.',
            'is_unique' => 'This %s is already registered.'
        ));
        $this->form_validation->set_rules('superior', 'Direct Superior', 'required', array(
            'required' => '%s is required.'
        ));
        $this->form_validation->set_rules('roles', 'Employee Role', 'required', array(
            'required' => '%s is required.'
        ));
        $this->form_validation->set_rules('init-pass', 'Initial Password', 'required|min_length[8]', array(
            'required' => '%s is required.',
            'min_length' => '%s should have a minimum of 8 characters'
        ));

        // $this->form_validation->set_rules('rfidNum', 'RFID Number', 'required', array(
        //     'required' => '%s is required.',
        // ));

        // $this->form_validation->set_rules('tap-rfid', 'Tap your RFID', 'required', array(
        //     'required' => 'Please tap your RFID card.',
        // ));

        if ($this->upload->do_upload('employee_image') == FALSE) {
            $this->form_validation->set_rules('employee_image', 'Employee Image', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->empReg_view();
        } else {
            $image_name = (!$this->upload->do_upload('employee_image')) ? null : $this->upload->data('file_name');
            $register = $this->input->post('reg-emp');

            if (isset($register)) {
                $id = $this->session->userdata('id');
                $rfid = $this->input->post('rfidNum'); //Check if still needed
                $info = array(
                    'emp_id' => $this->input->post('empid'),
                    'emp_name' => $this->input->post('empname'),
                    'emp_email' => $this->input->post('email'),
                    'superior' => $this->input->post('superior'),
                    'emp_role' => $this->input->post('roles'),
                    'password' => md5($this->input->post('init-pass')),
                    'emp_image' => $image_name,
                    'rfid' => $rfid,
                    'registered' => 1
                );

                $this->Admin_model->employee_registration($info, $rfid);

                $success = "Employee is registered successfully";
                $this->session->set_flashdata('success', $success);
                redirect('Admin/employee_registration');
            }
        }
    }
    // public function validate_emp_rfid()

    public function devReg_view()
    {

        $data['title'] = 'Calibr8 - Device Registration';
        // $rfid_num = $this->Admin_model->get_devRfid(); 
        // if($rfid_num) {
        //     foreach($rfid_num as $key) {
        //       $data['rfid'] = $key->rfid;
        //     }
        // } else {
        //     $data['rfid'] = "";
        // }
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_devReg_view', $data);
        $this->load->view('include/footer');
    }
    public function devReg_rfid() {
        $rfid_num = $this->Admin_model->get_devRfid(); 
        if($rfid_num) {
            foreach($rfid_num as $key) {
                $rfid = $key->rfid;
                echo "<input type='text' id='rfid_num' name='rfidNum' value='".$rfid."'><br>";
            }
        } else {
            echo "<input type='text' id='rfid_num' name='rfidNum' value=''><br>";
        }
    }

    public function device_registration()
    {
        $image_config = array(
            'upload_path' => './assets/device_image',
            'allowed_types' => 'gif|jpg|png',
            'max_size' => 5000000000,
            'max_width' => 204800,
            'max_height' => 204800
        );

        $this->load->library('upload', $image_config);
        $this->upload->initialize($image_config);

        $this->form_validation->set_rules('uniquenum', 'Device Unique Number', 'required|alpha_numeric|is_unique[devices.unique_num]', array(
            'required' => '%s is required.',
            'alpha_numeric' => '%s should only contain alpha numeric characters.',
            'is_unique' => 'This %s is already registered.'
        ));
        $this->form_validation->set_rules('devicename', 'Device Name', 'required', array(
            'required' => '%s is required.'
        ));
        $this->form_validation->set_rules('model', 'Device Model', 'required|alpha_numeric_spaces', array(
            'required' => '%s is required.',
            'alpha_numeric' => '%s should only contain alpha numeric characters.'
        ));
        $this->form_validation->set_rules('roles', 'Allowed Roles', 'required', array(
            'required' => 'Please set %s',
        ));
        $this->form_validation->set_rules('manuf', 'Manufacturer', 'required|alpha_numeric', array(
            'required' => '%s is required.',
            'alpha_numeric' => '%s should only contain alpha numeric characters.'
        ));
        $this->form_validation->set_rules('specs', 'Specifications', 'required', array(
            'required' => '%s is required.',
        ));

        //Validation if rfid is in database or not
        // $this->form_validation->set_rules('rfidNum', 'RFID Number', 'required|callback_validate_rfid', array(
        //     'required' => '%s is required.',
        //     'is_unique' => 'This %s is already registered.'
        // ));

        // $this->form_validation->set_rules('tap-rfid', 'Tap your RFID', 'required', array(
        //     'required' => 'Please tap your RFID card.',
        // ));

        if ($this->upload->do_upload('device_image') == FALSE) {
            $this->form_validation->set_rules('device_image', 'Device Image', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->devReg_view();
        } else {
            $image_name = (!$this->upload->do_upload('device_image')) ? null : $this->upload->data('file_name');
            $register = $this->input->post('reg-dev');

            if (isset($register)) {
                $rfid = $this->input->post('rfidNum'); //Check if still needed

                $info = array(
                    'unique_num' => $this->input->post('uniquenum'),
                    'dev_name' => $this->input->post('devicename'),
                    'dev_model' => $this->input->post('model'),
                    'allowed_roles' => $this->input->post('roles'),
                    'manufacturer' => $this->input->post('manuf'),
                    'specs' => nl2br($this->input->post('specs')),
                    'category' => $this->input->post('category'),
                    'dev_image' => $image_name,
                    'rfid' => $rfid,
                    'registered' => 1,
                    'cur_status' => 'Available',
                    'prev_status' => 'None'
                );

                $this->Admin_model->device_registration($info, $rfid);

                $success = "Device is registered successfully";
                $this->session->set_flashdata('success', $success);
                redirect('Admin/device_registration');
            }
        }
    }

    public function validate_rfid($rfid_num) {
        $rfid = $this->Admin_model->validate_devRfid();

        if($rfid->rfid == $rfid_num && $rfid->registered == 1) {
            $this->validation->set_message('validate_rfid', 'This RFID Number is already in the database');
        }
    }

    //VIew Profile 
    public function profile_view() 
    {
        $data['title'] = 'Calibr8 - My Profile';
        $data['admin'] = $this->Admin_model->get_emp_row($this->session->userdata('id'));
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_profile_view', $data);
        $this->load->view('include/footer');
    }

    public function reset_password()
    {
        $reset = $this->input->post('reset-btn');

        $this->form_validation->set_rules('oldPass', 'Current Password', 'required|min_length[8]|callback_validate_password', array(
            'required' => 'Please provide your %s.',
            'min_length' => '%s should have a minimum of 8 characters.'
        ));

        $this->form_validation->set_rules('newPass', 'New Password', 'required|min_length[8]', array(
            'required' => 'Please provide your %s.',
            'min_length' => '%s should have a minimum of 8 characters.'
        ));

        $this->form_validation->set_rules('confNewPass', 'Confirm New Password', 'required|min_length[8]|matches[newPass]', array(
            'required' => 'Please confirm your New Password.',
            'min_length' => '%s should have a minimum of 8 characters.',
            'matches' => '%s does not match your New Password.'
        ));

        if (isset($reset)) {
            $newPass = md5($this->input->post('newPass'));

            if ($this->form_validation->run() == FALSE) {
                $this->profile_view();
            } else {
                $id = $this->session->userdata('id');
                $info = array(
                    'password' => $newPass
                );

                $this->Admin_model->update_employee($id, $info);

                $success = "Password is updated successfully";
                $this->session->set_flashdata('success', $success);
                redirect('Admin/profile_view');
            }
        }
    }

    public function validate_password($oldPass)
    {
        $id = $this->session->userdata('id');
        $oldPassword = md5($oldPass);
        $currPass = $this->Admin_model->get_emp_row($id)->password;

        if ($oldPassword != $currPass) {
            $this->form_validation->set_message('validate_password', '%s field does not match your current password.');
            return FALSE;
        }

        return TRUE;
    }
}

?>
