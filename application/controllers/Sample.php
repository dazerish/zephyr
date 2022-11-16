<?php 
    class Sample extends CI_Controller {
        public function __construct() {
            parent::__construct();
    
            $this->load->helper(['form', 'url', 'string']);
            $this->load->library(['form_validation', 'session', 'pagination',]);
            $this->load->model(['Sample_model','Admin_model','Employee_model']);
        }

        public function index() {
            header('Content-Type: application/json');
            echo json_encode(['name' => 'John Doe']);
    
        }

        

        //Login API
        public function login() {
            header('Content-Type: application/json');


            $email = $this->input->post('email');
            $password = $this->input->post('password');
    
            $this->load->model('Login_model');
            $account = $this->Login_model->login($email, $password);


            if(isset($account)) {
                $jwt = new JWT();

                $JwtSecretKey = "DEVIN-Calibr8";
                $data = array(
                    'id' => $account->id,
                    'employee_id' => $account->emp_id,
                    'name' => $account->emp_name,
                    'email' => $account->emp_email,
                    'superior' => $account->superior,
                    'role' => $account->emp_role,
                    'image' => $account->emp_image,
                    'expiration' => (time() * 1000) + 3600 * 1000
                    // (time() * 1000) + 3600 * 1000 - 1hr expiration
                );

                $token = $jwt->encode($data, $JwtSecretKey, 'HS256');
                echo json_encode(['token' => $token, 'message' => 'Success!']);
            } else {
                echo json_encode(['error' => 'Invalid username/password', 'message' => 'Failed!']);
            }

        }

        //Token Reference
        public function token() { //JWT Token
            $jwt = new JWT();

            $JwtSecretKey = "DEVIN-Calibr8";
            $data = array(
                'userId' => 568,
                'email' => 'admin@gmail.com',
                'userType' => 'admin'
            );

            $token = $jwt->encode($data, $JwtSecretKey, 'HS256');
            echo json_encode($token);
        }

        //Decode Token for Login API
        public function login_decode() { //Error Handling - status code(403)
            $headers = apache_request_headers();
            $token = $headers['Authorization'];
            // echo json_encode($token);

            $jwt = new JWT();
            $JwtSecretKey = "DEVIN-Calibr8";
            
            $decoded_token = $jwt->decode($token, $JwtSecretKey, 'HS256');

            //this will return std_object
            // echo "<pre>";
            // print_r($decoded_token);

            //it will return JSON
            $token1 = $jwt->jsonEncode($decoded_token);
            // return $token1;

            $value = json_decode(json_encode($decoded_token), true);
            $expiration = $value['expiration'];
            // echo json_encode($expiration);
            // echo json_encode($decoded_token);

            if(time() * 1000 >= $expiration) { //Error Handling
                echo 'Token is expired';
            } else {
                echo json_encode($decoded_token);
            }
            return $token1;
        }


        //Decode Token
        public function decode_token() { //Error Handling - status code(403)
            $headers = apache_request_headers();
            $token = $headers['Authorization'];
            // echo json_encode($token);

            $jwt = new JWT();
            $JwtSecretKey = "DEVIN-Calibr8";
            
            $decoded_token = $jwt->decode($token, $JwtSecretKey, 'HS256');

            //this will return std_object
            // echo "<pre>";
            // print_r($decoded_token);

            //it will return JSON
            $token1 = $jwt->jsonEncode($decoded_token);
            // return $token1;

            $value = json_decode(json_encode($decoded_token), true);
            $expiration = $value['expiration'];
            // echo json_encode($expiration);
            // echo json_encode($decoded_token);

            if(time() * 1000 >= $expiration) { //Error Handling
                echo 'Token is expired';
            }
            return $token1;
        }


        //Display Employee API
        public function display_emp() {
            header('Content-Type: application/json');
            $token = $this->decode_token();

            if(isset($token)) {
                $this->load->model('Sample_model');
                $response = $this->Sample_model->display_emp();
                echo json_encode($response);

            }

        }

        //Display Devices API
        public function display_dev() {
            header('Content-Type: application/json');
            $token = $this->decode_token();

            if(isset($token)) {
                $this->load->model('Sample_model');
                $response = $this->Sample_model->display_dev();
                echo json_encode($response);
            }
        }

        public function display_specialized() {
            header('Content-Type: application/json');
            $token = $this->decode_token();

            if(isset($token)) {
                $response = $this->Sample_model->display_specialized();
                echo json_encode($response);
            }
        }

        public function display_networking() {
            header('Content-Type: application/json');
            $token = $this->decode_token();

            if(isset($token)) {
                $response = $this->Sample_model->display_networking();
                echo json_encode($response);
            }
        }

        public function display_peripherals() {
            header('Content-Type: application/json');
            $token = $this->decode_token();

            if(isset($token)) {
                $response = $this->Sample_model->display_peripherals();
                echo json_encode($response);
            }
        }

        public function display_output() {
            header('Content-Type: application/json');
            $token = $this->decode_token();

            if(isset($token)) {
                $response = $this->Sample_model->display_output();
                echo json_encode($response);
            }
        }

        public function display_processing() {
            header('Content-Type: application/json');
            $token = $this->decode_token();

            if(isset($token)) {
                $response = $this->Sample_model->display_processing();
                echo json_encode($response);
            }
        }


        //Borrow API
        public function set_reserveDate() {
            header('Content-Type: application/json');
            $token = $this->decode_token();
            
            
            try {
                $this->form_validation->set_rules('reservation_date', 'Reservation Date', 'required|callback_validate_reserveDate', array(
                    'required' => 'Please set a %s'
                ));
                
                if (isset($token)) {

                    if ($this->form_validation->run() == FALSE) {
                        throw new \Exception('Please enter a valid date');
    
                    } else {
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
    
                        echo json_encode(['message' => TRUE ]);
    
                    }
                }    

            } catch(\Exception $error) {
                echo json_encode(['message' => $error->getMessage()]);
            }
        
    
        }
        public function exec_reserveDate() {
            header('Content-Type: application/json');
            $token = $this->decode_token();
            
            
            try {
                $this->form_validation->set_rules('reservation_date', 'Reservation Date', 'required|callback_validate_reserveDate', array(
                    'required' => 'Please set a %s'
                ));
                
                if (isset($token)) {

                    if ($this->form_validation->run() == FALSE) {
                        throw new \Exception('Please enter a valid date');
    
                    } else {
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
                            'request_time' => date("Y-m-d H:i:s", strtotime('now')),
                            'decision_time' => date("Y-m-d H:i:s", strtotime($reservation_date)),
                            'return_date' => date("Y-m-d H:i:s", strtotime($reservation_date. '+1 month'))
                        );
    
                        //Device Status Info
                        $status_info = array(
                            'cur_status' => 'Borrowed',
                            'prev_status' => 'Available'
                        );
    
                        $this->Employee_model->set_reserveDate($info, $status_info, $unique_num);
    
                        echo json_encode(['message' => TRUE ]);
    
                    }
                }    

            } catch(\Exception $error) {
                echo json_encode(['message' => $error->getMessage()]);
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

        //Location API
        public function send_devLoc() {
            header('Content-Type: application/json');
            $token = $this->decode_token();

            if(isset($token)) {
                $unique_num = $this->input->post('unique-num');

                $info = array(
                    'latitude' => $this->input->post('latitude'),
                    'longitude' => $this->input->post('longitude')
                );

                $this->Sample_model->send_devLoc($info, $unique_num);

                echo json_encode(['message' => TRUE ]);
            }

        }

        //Profile API
        public function transacted_dev() {
            header('Content-Type: application/json');
            $token = $this->decode_token();

            if(isset($token)) {
                $emp_name = $this->input->post('emp_name');
                $response = $this->Sample_model->transacted_dev($emp_name);

                echo json_encode($response);

            }
        }

        //Transaction Logs 
        public function transaction_logs() {
            header('Content-Type: application/json');
            $token = $this->decode_token();

            if(isset($token)) {
                $response = $this->Sample_model->transaction_logs();
                echo json_encode($response);
            }
        }


        //Report API 
        public function report_transaction() {
            header('Content-Type: application/json');
            $token = $this->decode_token();

            if(isset($token)) {
                $unique_num = $this->input->post('unique_num');
                $dev_status = $this->input->post('device_status');
                // $borrower = $this->input->post('borrower');

                $transaction_status = $this->Sample_model->check_transaction_status();

                if($transaction_status == $dev_status) { //Check if pending/approved
                    if($dev_status == 'Lost') {
                        $trans_info = array(
                            'transaction_status' => 'Lost',
                            'request_time' => date("Y-m-d H:i:s", strtotime('now'))
                        );
                        $status_info = array(
                            'cur_status' => 'Lost',
                            'prev_status' => 'Issued'
                        );

                        $this->Sample_model->report($trans_info, $status_info, $unique_num);
                        echo json_encode(['message' => TRUE]);
                    }

                    if($dev_status == 'Broken') {
                        $trans_info = array(
                            'transaction_status' => 'Broken',
                            'request_time' => date("Y-m-d H:i:s", strtotime('now'))
                        );
                        $status_info = array(
                            'cur_status' => 'Broken',
                            'prev_status' => 'Issued'
                        );

                        $this->Sample_model->report($trans_info, $status_info, $unique_num);
                        echo json_encode(['message' => TRUE]);
                    }

                    if($dev_status == 'Maintenance') {
                        $trans_info = array(
                            'transaction_status' => 'Maintenance',
                            'request_time' => date("Y-m-d H:i:s", strtotime('now'))
                        );
                        $status_info = array(
                            'cur_status' => 'Maintenance',
                            'prev_status' => 'Issued'
                        );

                        $this->Sample_model->report($trans_info, $status_info, $unique_num);
                        echo json_encode(['message' => TRUE]);
                    }
                } else {
                    echo json_encode(['message' => 'Device is not yet Issued']);
                }
            }
        }
        
        //Device Approval API
        public function device_approval_list() {
            header('Content-Type: application/json');
            $token = $this->decode_token();

            if(isset($token)) {
                $response = $this->Sample_model->get_transaction_table();
                echo json_encode($response);
            }
        }

        public function device_approval() {
            header('Content-Type: application/json');
            $token = $this->decode_token();

            if(isset($token)) {
                $unique_num = $this->input->post('unique_num');
                $button = $this->input->post('button');
                

                if($button == 'Reject') {
                    $transaction_status = array(
                        'transaction_status' => 'Rejected',
                        'request_time' => date("Y-m-d H:i:s", strtotime('now'))
                    );
            
                    $status_info = array(
                        'cur_status' => 'Available',
                        'prev_status' => 'Reserved'
                    );

                    $this->Sample_model->reject_device($transaction_status, $status_info, $unique_num);
                    echo json_encode(['message' => TRUE]);
                }

                if($button == 'Approve') {
                    $transaction_status = array(
                        'transaction_status' => 'Approved',
                        'request_time' => date("Y-m-d H:i:s", strtotime('now'))
                    );
            
                    $status_info = array(
                        'cur_status' => 'Borrowed',
                        'prev_status' => 'Reserved'
                    );

                    $this->Sample_model->approve_device($transaction_status, $status_info, $unique_num);
                    echo json_encode(['message' => TRUE]);
                }
            }
        }


        //Device Details
        public function device_details() {
            header('Content-Type: application/json');
            $token = $this->decode_token();

            if(isset($token)) {
                $unique_num = $this->input->post('unique_num');
                $responses = $this->Sample_model->get_dev_details($unique_num);

                // echo json_encode($response);
                foreach($responses as $response) {
                    echo json_encode($response);
                }

            }
        }
    }
?>