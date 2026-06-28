<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Visitor extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Visitor_model", "vm", TRUE);
        $this->load->model("Visitor_user_model", "vum", TRUE);
        date_default_timezone_set('Asia/Dhaka');
    }

    public function index()
    {
        if ($this->session->userdata('visitor_id')) {
            redirect('visitor_panel/dashboard');
        }

        redirect('visitor_auth/register');
    }

    public function apply()
    {
        $visitor = $this->require_visitor();
        $data['title'] = "Visitor Apply";
        $data['visitor'] = $visitor;
        $data['departments'] = $this->vm->active_departments();
        $data['content'] = $this->load->view('visitor/apply', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function submit()
    {
        $res = array('success' => false, 'message' => '');
        $visitor = $this->require_visitor();

        try {
            $application = json_decode($this->input->post('data'));
            if (!$application) {
                throw new Exception('Invalid request data.');
            }

            $phone = $visitor->phone;
            if (!preg_match('/^01[13-9][\d]{8}$/', $phone)) {
                throw new Exception('Visitor profile phone number is not valid.');
            }

            $photo = $visitor->photo;
            if (!$photo) {
                throw new Exception('Visitor profile photo not found. Please upload a photo in your profile setup first.');
            }

            $file_ext = pathinfo($photo, PATHINFO_EXTENSION);
            $app_photo = 'uploads/visitors/app_' . uniqid() . '.' . $file_ext;
            if (file_exists($photo)) {
                if (!file_exists('uploads/visitors')) {
                    mkdir('uploads/visitors', 0777, true);
                }
                copy($photo, $app_photo);
                $photo = $app_photo;
            }

            $payload = array(
                'visitor_id' => $visitor->id,
                'name' => $visitor->name,
                'phone' => $phone,
                'nid' => $visitor->nid,
                'address' => $visitor->address,
                'purpose' => trim($application->purpose),
                'visit_to' => trim($application->visit_to),
                'department_id' => (int) $application->department_id,
                'visit_date' => $application->visit_date,
                'photo' => $photo,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            );

            $id = $this->vm->create($payload);
            $res = array(
                'success' => true,
                'message' => 'Application submitted',
                'application_id' => $id,
                'redirect' => site_url('visitor_panel/dashboard')
            );
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage());
        }

        echo json_encode($res);
    }

    public function success($id)
    {
        $data['application'] = $this->vm->find($id);
        if (!$data['application']) {
            show_404();
        }

        $data['title'] = "Application Submitted";
        $data['content'] = $this->load->view('visitor/success', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function status()
    {
        $data['application'] = null;
        $data['error'] = '';

        if ($this->input->post()) {
            $tracking_id = trim($this->input->post('tracking_id', true));
            $phone = trim($this->input->post('phone', true));
            $application = $this->vm->find_for_visitor($tracking_id, $phone);

            if ($application) {
                $data['application'] = $application;
            } else {
                $data['error'] = 'Application not found. Please check your tracking ID and phone number.';
            }
        }

        $data['title'] = "Check Application Status";
        $data['content'] = $this->load->view('visitor/status', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function my_card($id)
    {
        $visitor = $this->require_visitor();
        $data['application'] = $this->vm->find_for_logged_visitor((int) $id, $visitor->id);

        if (!$data['application'] || $data['application']->status !== 'approved') {
            show_404();
        }

        $data['title'] = "Visitor Card";
        $data['content'] = $this->load->view('visitor/card', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    private function require_visitor()
    {
        if (!$this->session->userdata('visitor_id')) {
            redirect('visitor_auth/login');
        }

        $visitor = $this->vum->find_active($this->session->userdata('visitor_id'));
        if (!$visitor) {
            $this->session->unset_userdata(array('visitor_id', 'visitor_name', 'visitor_email', 'visitor_phone'));
            redirect('visitor_auth/login');
        }

        return $visitor;
    }

    public function card($token)
    {
        $data['application'] = $this->vm->find_by_token($token);
        if (!$data['application'] || $data['application']->status !== 'approved') {
            show_404();
        }

        $data['title'] = "Visitor Card";
        $data['content'] = $this->load->view('visitor/card', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }
}
