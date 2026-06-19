<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Visitor extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Visitor_model", "vm", TRUE);
        date_default_timezone_set('Asia/Dhaka');
    }

    public function index()
    {
        $data['title'] = "Visitor Apply";
        $data['departments'] = $this->vm->active_departments();
        $data['content'] = $this->load->view('visitor/apply', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function apply()
    {
        $data['title'] = "Visitor Apply";
        $data['departments'] = $this->vm->active_departments();
        $data['content'] = $this->load->view('visitor/apply', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function submit()
    {
        if (!$this->input->post()) {
            redirect('/');
        }

        $phone = trim($this->input->post('phone', true));
        if (!preg_match('/^01[13-9][\d]{8}$/', $phone)) {
            $this->session->set_flashdata('apply_error', 'Please enter a valid Bangladeshi mobile number.');
            redirect('/');
        }

        $photo = $this->upload_photo();

        $payload = array(
            'name' => trim($this->input->post('name', true)),
            'phone' => $phone,
            'nid' => trim($this->input->post('nid', true)),
            'address' => trim($this->input->post('address', true)),
            'purpose' => trim($this->input->post('purpose', true)),
            'visit_to' => trim($this->input->post('visit_to', true)),
            'department_id' => (int) $this->input->post('department_id', true),
            'visit_date' => $this->input->post('visit_date', true),
            'photo' => $photo,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        );

        $id = $this->vm->create($payload);
        redirect('visitor/success/' . $id);
    }

    private function upload_photo()
    {
        if (empty($_FILES['photo']['name'])) {
            return null;
        }

        $upload_path = FCPATH . 'uploads/visitors/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $config = array(
            'upload_path' => $upload_path,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size' => 2048,
            'encrypt_name' => true
        );

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('photo')) {
            return null;
        }

        $file = $this->upload->data();
        return 'uploads/visitors/' . $file['file_name'];
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
        $phone = trim($this->input->get('phone', true));
        $data['application'] = $this->vm->find_for_visitor((int) $id, $phone);

        if (!$data['application'] || $data['application']->status !== 'approved') {
            show_404();
        }

        $data['title'] = "Visitor Card";
        $data['content'] = $this->load->view('visitor/card', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
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
