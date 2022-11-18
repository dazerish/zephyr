<?php 

class Login extends CI_Controller{
    public function __construct() {
        parent::__construct();

        $this->load->helper(['form', 'url']);
        $this->load->library('session');
        $this->load->model('Login_model');
    }
        
    public function index() {
        if ($this->session->userdata('logged_in')) {
            redirect('Admin');
        }
        if ($this->session->userdata('logged_in')) {
            redirect('Employee');
        }
        if ($this->session->userdata('logged_in')) {
            redirect('Executive');
        }

        $data['title'] = 'Calibr8 - Login';
        $this->load->view('include/header', $data);
        $this->load->view('login_view');
        $this->load->view('include/footer');
    }

    public function login_validate() {
        $submit = $this->input->post('login');

        if(isset($submit)) {
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            // $this->load->model('Login_model');
            $account = $this->Login_model->login($email, $password);

            if(isset($account)) {
                if($account->emp_role == 'administrator') {
                    $sess_data = array(
                        'id' => $account->id,
                        'role' => $account->emp_role,
                        'logged_in' => TRUE
                    );

                    $this->session->set_userdata($sess_data);
                    redirect('Admin');
                }

                
                if($account->emp_role == 'executive') {
                    $sess_data = array(
                        'id' => $account->id,
                        'role' => $account->emp_role,
                        'logged_in' => TRUE
                    );

                    $this->session->set_userdata($sess_data);
                    redirect('Executive');
                }

                if($account->emp_role == 'employee') {
                    $sess_data = array(
                        'id' => $account->id,
                        'role' => $account->emp_role,
                        'logged_in' => TRUE
                    );

                    $this->session->set_userdata($sess_data);
                    redirect('Employee');
                }
            }

            $error = 'Invalid username or password';
            $this->session->set_flashdata('error', $error);
            redirect('Login');
        }
    }

    public function forgot_password_view() {

        $data['title'] = 'Calibr8 - Forgot Password';
        $this->load->view('include/header', $data);
        $this->load->view('forgot_pass_form');
        $this->load->view('include/footer');
    }
    public function forgot_password() {
        $reset = $this->input->post('reset-pw');

        if(isset($reset)) {
            $email = $this->input->post('email');
            $findEmail = $this->Login_model->forgot_password($email);

            if(isset($findEmail)) {
                // $this->Login_model->send_password($findEmail);
                if($findEmail->emp_email == $email) {
                    //email settings
                    $this->load->library('email');
                    $config_email = array(
                        'protocol' => 'smtp',
                        'smtp_host' => 'ssl://smtp.googlemail.com',
                        'smtp_port' => 465,
                        'smtp_user' => $this->config->item('email'), //Active gmail
                        'smtp_pass' => $this->config->item('password'), //Password
                        'mailtype' => 'html',
                        'starttls' => TRUE,
                        'newline' => "\r\n",
                        'charset' => $this->config->item('charset'),
                        'wordwrap' => TRUE
                    );
                    $this->email->initialize($config_email);

                    $passwordplain = "";
                    $passwordplain = rand(999999999,9999999999);
                    $newpass = md5($passwordplain);
                    // $this->db->where('emp_email', $email);
                    // $this->db->update('users', ['password' => $newpass]);
                    $info = array('password' => $newpass);
                    $this->Login_model->reset_password($info, $email);


                    $this->email->from('zephyr.devin@gmail.com','Calibr8 - DEVIN');
                    $this->email->to($email);
                    $this->email->subject('Forgot Password');
                    $this->email->message('Hi '.$findEmail->emp_name.',<br><br>
                                        Your password has been reset to <b>'.$passwordplain.'</b>. Kindly change your password upon logging in. <br> 
                                        Thank you & have a great day!<br>
                                        Calibr8 - DEVIN');
                    // $this->email->send();
                    if(!$this->email->send()) {
                        $message = 'Failed to send password. Please try again!';
                        $this->session->set_flashdata('message', $message);

                    } else {
                        $success = 'Your password has been reset. Please check your email.';
                        $this->session->set_flashdata('success', $success);
                    }

                    redirect('Login/forgot_password_view');
                    }

            }else {
                
                $message = 'The email you entered is invalid.';
                $this->session->set_flashdata('message', $message);
                redirect('Login/forgot_password_view');
            
            }
        }
    }


    public function logout() {
        $this->session->sess_destroy();
        redirect('Login');
    }

}


?>