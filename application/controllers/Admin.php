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
        $data['total'] = $this->Admin_model->get_dCount();
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
            'transaction_status' => 'Rejected'
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
            'transaction_status' => 'Approved'
        );

        $status_info = array(
            'cur_status' => 'Borrowed',
            'prev_status' => 'Reserved'
        );

        $transaction_id = $this->uri->segment(3);
        $borrowedDev_id = $this->uri->segment(4);

        $this->Admin_model->reject_device($transaction_status, $status_info, $transaction_id, $borrowedDev_id);
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


    //System Logs
    public function system_logs() {

        $data['title'] = 'Calibr8 - System Logs';
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_sysLogs_view');
        $this->load->view('include/footer');
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

                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                
                foreach(range('A','H') as $coulumID) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($coulumID)->setAutosize(true);

                }
                $sheet->getStyle('A:H')->getAlignment()->setHorizontal('center');
                
                $sheet->setCellValue('A1','Report for the date of '.$s_date.' to '.$e_date);
                $sheet->setCellValue('A2','Transaction ID');
                $sheet->setCellValue('B2','Transaction Status');
                $sheet->setCellValue('C2','Borrower');
                $sheet->setCellValue('D2','Device ID');
                $sheet->setCellValue('E2','Device Name');
                $sheet->setCellValue('F2','Request Time');
                $sheet->setCellValue('G2','Decision Time');
                $sheet->setCellValue('H2','Return Date');

                $system_data = $this->Admin_model->fetch_data($start_date, $end_date);
                $x=3; //start from row 2
                foreach($system_data as $row)
                {
                    $sheet->setCellValue('A'.$x, $row['transaction_id']);
                    $sheet->setCellValue('B'.$x, $row['transaction_status']);
                    $sheet->setCellValue('C'.$x, $row['borrower']);
                    $sheet->setCellValue('D'.$x, $row['borrowedDev_id']);
                    $sheet->setCellValue('E'.$x, $row['borrowedDev_name']);
                    $sheet->setCellValue('F'.$x, $row['request_time']);
                    $sheet->setCellValue('G'.$x, $row['decision_time']);
                    $sheet->setCellValue('H'.$x, $row['return_date']);
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





    //Registration Section
    public function empReg_view()
    {
        $data['title'] = 'Calibr8 - Employee Registration';
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_empReg_view');
        $this->load->view('include/footer');
    }

    public function employee_registration()
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

        // $this->form_validation->set_rules('rfid-num', 'RFID Number', 'required', array(
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
                $info = array(
                    'emp_id' => $this->input->post('empid'),
                    'emp_name' => $this->input->post('empname'),
                    'emp_email' => $this->input->post('email'),
                    'superior' => $this->input->post('superior'),
                    'emp_role' => $this->input->post('roles'),
                    'password' => md5($this->input->post('init-pass')),
                    'emp_image' => $image_name,
                    'rfid' => 'None'
                );

                $this->Admin_model->employee_registration($info);

                $success = "Employee is registered successfully";
                $this->session->set_flashdata('success', $success);
                redirect('Admin/employee_registration');
            }
        }
    }

    public function devReg_view()
    {

        $data['title'] = 'Calibr8 - Device Registration';
        $this->load->view('include/admin_header', $data);
        $this->load->view('admin/admin_devReg_view');
        $this->load->view('include/footer');
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

        // $this->form_validation->set_rules('rfid-num', 'RFID Number', 'required', array(
        //     'required' => '%s is required.',
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

                $info = array(
                    'unique_num' => $this->input->post('uniquenum'),
                    'dev_name' => $this->input->post('devicename'),
                    'dev_model' => $this->input->post('model'),
                    'allowed_roles' => $this->input->post('roles'),
                    'manufacturer' => $this->input->post('manuf'),
                    'specs' => nl2br($this->input->post('specs')),
                    'dev_image' => $image_name,
                    'rfid' => 'None',
                    'cur_status' => 'Available',
                    'prev_status' => 'None'
                );

                $this->Admin_model->device_registration($info);

                $success = "Device is registered successfully";
                $this->session->set_flashdata('success', $success);
                redirect('Admin/device_registration');
            }
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
