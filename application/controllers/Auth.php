<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("User_model", "um", TRUE);
    }

    public function login()
    {
        if ($this->session->userdata('user_id')) {
            redirect('admin/dashboard');
        }

        $data['error'] = '';
        if ($this->input->post()) {
            $email = trim($this->input->post('email', true));
            $password = $this->input->post('password', true);
            $user = $this->um->find_by_email($email);

            if ($user && $user->status == 1 && password_verify($password, $user->password)) {
                $this->session->set_userdata(array(
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role
                ));
                $this->um->update_last_login_ip($user->id, $this->input->ip_address());
                redirect('admin/dashboard');
            }

            $data['error'] = 'Invalid email or password';
        }

        $data['title'] = "Login";
        $data['content'] = $this->load->view('auth/login', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}

