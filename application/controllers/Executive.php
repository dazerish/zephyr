<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Executive extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper(['form', 'url', 'string', 'date']);
        $this->load->library(['form_validation', 'session', 'pagination',]);
        $this->load->model('Executive_model');
    }

    public function index() //Borrowable Device List
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('Login');
        }

        if ($this->session->userdata('role') == 'admininistrator') {
            redirect('Admin');
        }

        if ($this->session->userdata('role') == 'employee') {
            redirect('Employee');
        }


        $page_config = array(
            'base_url' => site_url('Executive/index'),
            'total_rows' => $this->Executive_model->borrowableDev_count(),
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

        $data['title'] = 'Calibr8 - Borrowable Device Masterlist';
        $data['total'] = $this->Executive_model->borrowableDev_count();
        $data['stocks'] = $this->Executive_model->get_devModel($page_config['per_page'], $page, NULL);
        $this->load->view('include/executive_header', $data);
        $this->load->view('executive/executive_borrowDev_view');
        $this->load->view('include/footer');
        
    }

    public function search_BorrowableDev()
    { //Temporary Search Function
        $search = ($this->input->post("searchTerm")) ? $this->input->post("searchTerm") : "NIL";
        $search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;

        $page_config = array(
            'base_url' => site_url('Executive/search_BorrowableDev/$search'),
            'total_rows' => $this->Executive_model->count_devModel($search),
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

        $data['title'] = 'Calibr8 - Borrowable Device Masterlist';
        $data['stocks'] = $this->Executive_model->get_devModel($page_config['per_page'], $page, $search);
        $data['total'] = $this->Executive_model->borrowableDev_count();
        $this->load->view('include/executive_header', $data);
        $this->load->view('executive/executive_borrowDev_view');
        $this->load->view('include/footer');
    }

    public function reserveDev($dev_name)
    {

        $data['title'] = 'Calibr8 - Borrow This Device';
        $dev_name = str_replace('%20', ' ', $dev_name);
        $data['stocks'] = $this->Executive_model->reserveDev($dev_name);
        $id = $this->session->userdata('id');
        $data['executive'] = $this->Executive_model->get_emp_row($id);
        $this->load->view('include/executive_header', $data);
        $this->load->view('executive/executive_reservation_view', $data);
        $this->load->view('include/footer');
    }

    public function set_reserveDate()
    {
        $this->form_validation->set_rules('reason', 'Reason', 'required', array(
            'required' => 'Please set a %s'
        ));
        $this->form_validation->set_rules('reservation_date', 'Reservation Date', 'required|callback_validate_reserveDate', array(
            'required' => 'Please set a %s'
        ));

        if ($this->form_validation->run() == FALSE) {
            $dev_name = $this->input->post('dev-name');
            $device_name = str_replace('%20', ' ', $dev_name);
            $this->reserveDev($device_name);
        } else {
            $borrow = $this->input->post('borrow-device');

            if (isset($borrow)) {
                $dev_name = $this->input->post('dev-name');
                $device_name = str_replace('%20', ' ', $dev_name);
                $unique_num = $this->input->post('unique-num');
                $reservation_date = $this->input->post('reservation_date');

                //Reserved Date Info
                $info = array(
                    'transaction_status' => 'Approved',
                    'borrower' => $this->input->post('borrower'),
                    'borrowedDev_id' => $this->input->post('unique-num'),
                    'borrowedDev_name' => $dev_name,
                    'reason' => $this->input->post('reason'),
                    'request_time' => date("Y-m-d H:i:s", strtotime('now')),
                    'decision_time' => date("Y-m-d H:i:s", strtotime($reservation_date)),
                    'return_date' => date("Y-m-d H:i:s", strtotime($reservation_date. '+1 month'))
                );

                //Device Status Info
                $status_info = array(
                    'cur_status' => 'Borrowed',
                    'prev_status' => 'Available'

                );

                $this->Executive_model->set_reserveDate($info, $status_info, $unique_num);
                $success = "Reserve Date is set successfully";
                $this->session->set_flashdata('success', $success);
                redirect('Executive');
            }
        }

        $cancel = $this->input->post('cancel-button');

        if (isset($cancel)) {
            redirect('Executive');
        }
    }

    public function validate_reserveDate($reservation_date) {

        $startDate = date("Y-m-d H:i:s", strtotime($reservation_date));
        $currDate = date("Y-m-d H:i:s");

        if($startDate < $currDate) {
            $this->form_validation->set_message('validate_reserveDate', 'Please enter a valid date.');
            return FALSE;
        }

        return TRUE;
    }



    //Employee Masterlist
    public function emp_masterlist_view()
    {
        $page_config = array(
            'base_url' => site_url('Executive/emp_masterlist_view'),
            'total_rows' => $this->Executive_model->get_uCount(),
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
        $data['employees'] = $this->Executive_model->get_users_table($page_config['per_page'], $page, NULL);
        $data['total'] = $this->Executive_model->get_uCount();
        $this->load->view('include/executive_header', $data);
        $this->load->view('executive/executive_emp_masterlist');
        $this->load->view('include/footer');
    }

    public function searchEmp()
    { //Temporary Search Function
        $search = ($this->input->post("searchTerm")) ? $this->input->post("searchTerm") : "NIL";
        $search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;

        $page_config = array(
            'base_url' => site_url('Executive/searchEmp/$search'),
            'total_rows' => $this->Executive_model->get_users_count($search),
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
        $data['employees'] = $this->Executive_model->get_users_table($page_config['per_page'], $page, $search);
        $data['total'] = $this->Executive_model->get_uCount();
        $this->load->view('include/executive_header', $data);
        $this->load->view('executive/executive_emp_masterlist');
        $this->load->view('include/footer');
    }

    public function employee_view($id)
    { //Under Employee Masterlist
        $data['title'] = "Calibr8 - View Employee Details";
        $data['employee'] = $this->Executive_model->get_emp_row($id);
        $emp_name = $data['employee']->emp_name;
        $data['transacted_dev'] = $this->Executive_model->transacted_dev($emp_name);

        $this->load->view('include/executive_header', $data);
        $this->load->view('executive/executive_employee_view', $data);
        $this->load->view('include/footer');
    }


    

    //Device Masterlist
    public function dev_masterlist_view()
    {
        $page_config = array(
            'base_url' => site_url('Executive/dev_masterlist_view'),
            'total_rows' => $this->Executive_model->get_dCount(),
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
        $data['devices'] = $this->Executive_model->get_devices_table($page_config['per_page'], $page, NULL);
        $data['total'] = $this->Executive_model->get_dCount();
        $this->load->view('include/executive_header', $data);
        $this->load->view('executive/executive_dev_masterlist');
        $this->load->view('include/footer');
    }

    public function searchDev()
    { //Temporary Search Function
        $search = ($this->input->post("searchTerm")) ? $this->input->post("searchTerm") : "NIL";
        $search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;

        $page_config = array(
            'base_url' => site_url('Executive/searchDev/$search'),
            'total_rows' => $this->Executive_model->get_devices_count($search),
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

        $data['title'] = 'Calibr8 - View Device Masterlist';
        $data['devices'] = $this->Executive_model->get_devices_table($page_config['per_page'], $page, $search);
        $data['total'] = $this->Executive_model->get_dCount();
        $this->load->view('include/executive_header', $data);
        $this->load->view('executive/executive_dev_masterlist');
        $this->load->view('include/footer');
    }

    public function device_view($id) //Under device masterlist
    { 
        $data['title'] = "Calibr8 - View Device Details";
        $data['device'] = $this->Executive_model->get_dev_row($id);

        $this->load->view('include/executive_header', $data);
        $this->load->view('executive/executive_device_view', $data);
        $this->load->view('include/footer');
    }


    //Profile Section
    public function profile_view() {

        $data['title'] = 'Calibr8 - My Profile';
        $data['executive'] = $this->Executive_model->get_emp_row($this->session->userdata('id'));
        $this->load->view('include/executive_header', $data);
        $this->load->view('executive/executive_profile_view', $data);
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
                $this->index();
            } else {
                $id = $this->session->userdata('id');
                $info = array(
                    'password' => $newPass
                );

                $this->Executive_model->update_employee($id, $info);

                $success = "Password is updated successfully";
                $this->session->set_flashdata('success', $success);
                $this->index();
            }
        }
    }

    public function validate_password($oldPass)
    {
        $id = $this->session->userdata('id');
        $oldPassword = md5($oldPass);
        $currPass = $this->Executive_model->get_emp_row($id)->password;

        if ($oldPassword != $currPass) {
            $this->form_validation->set_message('validate_password', '%s field does not match your current password.');
            return FALSE;
        }

        return TRUE;
    }

    
    //Dashboard
    public function dashboard_view() {
        $data['title'] = 'Calibr8 - Executive Dashboard';
        $data['dashboard_data'] = $this->Executive_model->executive_dashboard();
        $this->load->view('include/executive_header', $data);
        $this->load->view('executive/executive_dashboard_view', $data);
        $this->load->view('include/footer');
    }


    //Generate Reports
    public function generate_reports() {

        $data['title'] = 'Calibr8 - Generate Reports';
        $this->load->view('include/executive_header', $data);
        $this->load->view('executive/executive_generate_reports');
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
                // $system_data = $this->Executive_model->fetch_data($start_date, $end_date);

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

                $system_data = $this->Executive_model->fetch_data($start_date, $end_date);
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

                $system_data1 = $this->Executive_model->fetch_dev_logs($start_date, $end_date);
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

                $system_data2 = $this->Executive_model->fetch_emp_logs($start_date, $end_date);
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
    
}
?>