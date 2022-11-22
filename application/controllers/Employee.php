<?php

class Employee extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper(['form', 'url', 'string', 'date']);
        $this->load->library(['form_validation', 'session', 'pagination',]);
        $this->load->model('Employee_model');
    }

    public function index() //Borrowable Device List
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('Login');
        }

        if ($this->session->userdata('role') == 'admininistrator') {
            redirect('Admin');
        }

        if ($this->session->userdata('role') == 'executive') {
            redirect('Executive');
        }

        $page_config = array(
            'base_url' => site_url('Employee/index'),
            'total_rows' => $this->Employee_model->count_devModel(),
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
        $data['total'] = $this->Employee_model->borrowableDev_count();
        $data['stocks'] = $this->Employee_model->get_devModel($page_config['per_page'], $page, NULL);
        $this->load->view('include/employee_header', $data);
        $this->load->view('employee/employee_borrowDev_view');
        $this->load->view('include/footer');
    }


    public function search_BorrowableDev()
    { //Temporary Search Function
        $search = ($this->input->post("searchTerm")) ? $this->input->post("searchTerm") : "NIL";
        $search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;

        $page_config = array(
            'base_url' => site_url('Employee/search_BorrowableDev/$search'),
            'total_rows' => $this->Employee_model->count_devModel($search),
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

        $data['title'] = 'Calibr8 - Borrowable Device List';
        $data['stocks'] = $this->Employee_model->get_devModel($page_config['per_page'], $page, $search);
        $data['total'] = $this->Employee_model->borrowableDev_count();
        $this->load->view('include/employee_header', $data);
        $this->load->view('employee/employee_borrowDev_view');
        $this->load->view('include/footer');
    }

    public function reserveDev($dev_name)
    {

        $data['title'] = 'Calibr8 - Borrow This Device';
        $dev_name = str_replace('%20', ' ', $dev_name);
        $data['stocks'] = $this->Employee_model->reserveDev($dev_name);
        $id = $this->session->userdata('id');
        $data['employee'] = $this->Employee_model->get_emp_row($id);
        $this->load->view('include/employee_header', $data);
        $this->load->view('employee/employee_reservation_view', $data);
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
                    'transaction_status' => 'Pending',
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
                    'cur_status' => 'Reserved',
                    'prev_status' => 'Available'
                );

                $this->Employee_model->set_reserveDate($info, $status_info, $unique_num);
                $success = "Reserve Date is set successfully. Please wait for approval.";
                $this->session->set_flashdata('success', $success);
                redirect('Employee');
            }
        }

        $cancel = $this->input->post('cancel-button');

        if (isset($cancel)) {
            redirect('Employee');
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


    //Device Masterlist
    public function dev_masterlist_view()
    {
        $page_config = array(
            'base_url' => site_url('Employee/dev_masterlist_view'),
            'total_rows' => $this->Employee_model->get_dCount(),
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
        $data['devices'] = $this->Employee_model->get_devices_table($page_config['per_page'], $page);
        $data['total'] = $this->Employee_model->get_dCount();
        $this->load->view('include/employee_header', $data);
        $this->load->view('employee/employee_dev_masterlist');
        $this->load->view('include/footer');
    }

    public function searchDev()
    { //Temporary Search Function
        $search = ($this->input->post("searchTerm")) ? $this->input->post("searchTerm") : "NIL";
        $search = ($this->uri->segment(3)) ? $this->uri->segment(3) : $search;

        $page_config = array(
            'base_url' => site_url('Employee/searchDev/$search'),
            'total_rows' => $this->Employee_model->get_devices_count($search),
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
        $data['devices'] = $this->Employee_model->get_devices_table($page_config['per_page'], $page, $search);
        $data['total'] = $this->Employee_model->get_dCount();
        $this->load->view('include/employee_header', $data);
        $this->load->view('employee/employee_dev_masterlist');
        $this->load->view('include/footer');
    }

    public function device_view($id)
    { //Under device masterlist
        $data['title'] = "Calibr8 - View Device Details";
        $data['device'] = $this->Employee_model->get_dev_row($id);

        $this->load->view('include/employee_header', $data);
        $this->load->view('employee/employee_device_view', $data);
        $this->load->view('include/footer');
    }


    //Profile View
    public function profile_view() {

        $data['title'] = 'Calibr8 - My Profile';
        $data['employee'] = $this->Employee_model->get_emp_row($this->session->userdata('id'));
        $this->load->view('include/employee_header', $data);
        $this->load->view('employee/employee_profile_view', $data);
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

                $this->Employee_model->update_employee($id, $info);

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
        $currPass = $this->Employee_model->get_emp_row($id)->password;

        if ($oldPassword != $currPass) {
            $this->form_validation->set_message('validate_password', '%s field does not match your current password.');
            return FALSE;
        }

        return TRUE;
    }
}

?>