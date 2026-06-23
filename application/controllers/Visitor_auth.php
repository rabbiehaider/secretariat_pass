<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Visitor_auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Visitor_user_model", "vum", TRUE);
        date_default_timezone_set('Asia/Dhaka');
    }

    public function register()
    {
        if ($this->session->userdata('visitor_id')) {
            redirect('visitor_panel/dashboard');
        }

        $data['title'] = "Visitor Registration";
        $data['error'] = '';

        $data['content'] = $this->load->view('visitor_auth/register', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function register_submit()
    {
        $res = array('success' => false, 'message' => '');
        try {
            $visitor = json_decode($this->input->post('data'));
            if (!$visitor) {
                throw new Exception('Invalid request data.');
            }

            $result = $this->create_visitor($visitor);
            if ($result !== true) {
                throw new Exception($result);
            }

            $res = array(
                'success' => true,
                'message' => 'Registration successful',
                'redirect' => site_url('visitor_panel/dashboard')
            );
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage());
        }

        echo json_encode($res);
    }

    public function login()
    {
        if ($this->session->userdata('visitor_id')) {
            redirect('visitor_panel/dashboard');
        }

        $data['title'] = "Visitor Login";
        $data['error'] = '';

        $data['content'] = $this->load->view('visitor_auth/login', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function login_submit()
    {
        $res = array('success' => false, 'message' => '');
        try {
            $request = json_decode($this->input->post('data'));
            if (!$request) {
                throw new Exception('Invalid request data.');
            }

            $login = trim($request->login);
            $password = isset($request->password) ? $request->password : '';
            $visitor = $this->vum->find_by_login($login);

            if (!$visitor || !password_verify($password, $visitor->password)) {
                throw new Exception('Invalid email, phone, or password.');
            }

            $this->login_visitor($visitor);
            $res = array(
                'success' => true,
                'message' => 'Login successful',
                'redirect' => site_url('visitor_panel/dashboard')
            );
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage());
        }

        echo json_encode($res);
    }

    public function logout()
    {
        $this->session->unset_userdata(array('visitor_id', 'visitor_name', 'visitor_email', 'visitor_phone'));
        redirect('visitor_auth/login');
    }

    private function create_visitor($visitor)
    {
        $name = trim($visitor->name);
        $email = strtolower(trim($visitor->email));
        $phone = trim($visitor->phone);
        $password = isset($visitor->password) ? $visitor->password : '';
        $confirm_password = isset($visitor->confirm_password) ? $visitor->confirm_password : '';
        $nid = trim($visitor->nid);
        $address = trim($visitor->address);

        if (!$name || !$email || !$phone || !$password || !$nid || !$address) {
            return 'Please fill up all required fields.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Please enter a valid email address.';
        }

        if (!preg_match('/^01[13-9][\d]{8}$/', $phone)) {
            return 'Please enter a valid Bangladeshi mobile number.';
        }

        if ($password !== $confirm_password) {
            return 'Password confirmation does not match.';
        }

        if (strlen($password) < 6) {
            return 'Password must be at least 6 characters.';
        }

        if ($this->vum->email_exists($email)) {
            return 'This email is already registered.';
        }

        if ($this->vum->phone_exists($phone)) {
            return 'This phone number is already registered.';
        }

        $id = $this->vum->create(array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'nid' => $nid,
            'address' => $address,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'last_login_at' => date('Y-m-d H:i:s')
        ));

        $visitor = $this->vum->find($id);
        $this->login_visitor($visitor);

        return true;
    }

    private function login_visitor($visitor)
    {
        $this->session->set_userdata(array(
            'visitor_id' => $visitor->id,
            'visitor_name' => $visitor->name,
            'visitor_email' => $visitor->email,
            'visitor_phone' => $visitor->phone
        ));

        $this->vum->update_last_login($visitor->id);
    }
}
