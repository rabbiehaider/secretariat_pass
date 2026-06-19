<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Visitor_model", "vm", TRUE);
        $this->load->model("Report_model", "rm", TRUE);
        $this->require_login();
    }

    private function require_login()
    {
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    public function dashboard()
    {
        $data['title'] = "Dashboard";
        $data['stats'] = $this->rm->dashboard_stats();
        $data['recent'] = $this->vm->recent(8);
        $data['content'] = $this->load->view('admin/dashboard', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function applications($status = 'pending')
    {
        $data['title'] = ucfirst($status) . ' Applications';
        $data['status'] = $status;
        $data['applications'] = $this->vm->by_status($status);
        $data['content'] = $this->load->view('admin/applications', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function approve($id)
    {
        $application = $this->vm->find($id);
        if (!$application || $application->status !== 'pending') {
            redirect('admin/applications/pending');
        }

        $pass_no = $this->vm->next_pass_no();
        $token = bin2hex(openssl_random_pseudo_bytes(24));

        $this->vm->approve($id, array(
            'pass_no' => $pass_no,
            'qr_token' => $token,
            'status' => 'approved',
            'approved_by' => $this->session->userdata('user_id'),
            'approved_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));

        redirect('visitor/card/' . $token);
    }

    public function reject($id)
    {
        $reason = trim($this->input->post('rejected_reason', true));
        $this->vm->reject($id, array(
            'status' => 'rejected',
            'rejected_reason' => $reason ? $reason : 'Rejected by admin',
            'updated_at' => date('Y-m-d H:i:s')
        ));

        redirect('admin/applications/pending');
    }
}
