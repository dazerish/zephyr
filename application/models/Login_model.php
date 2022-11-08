<?php

    class Login_model extends CI_model {
        public function __construct() {
            parent:: __construct();

            $this->load->database();
        }

        public function login($email, $password) {
            $query = $this->db->get_where('users', ['emp_email' => $email, 'password' => md5($password)]);
            return $query->row();
        }

        public function forgot_password($email) {
            $query = $this->db->get_where('users', ['emp_email' => $email]);
            return $query->row();
        }

        public function reset_password($newpass, $email) {
            $this->db->update('users', $newpass, ['emp_email' => $email]);
        }

        
    }
?>