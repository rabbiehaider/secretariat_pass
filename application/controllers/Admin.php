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
        $data['content'] = $this->load->view('admin/dashboard', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function applications($status = 'pending')
    {
        $data['title'] = ucfirst($status) . ' Applications';
        $data['status'] = $status;
        $data['content'] = $this->load->view('admin/applications', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function getDashboard()
    {
        $res = array('success' => false, 'message' => '');
        try {
            $recent = $this->vm->recent(8);
            foreach ($recent as $row) {
                $row->tracking_id = visitor_tracking_id($row);
                $row->card_url = $row->status === 'approved' ? site_url('visitor/card/' . $row->qr_token) : '';
            }

            $res = array(
                'success' => true,
                'message' => 'Dashboard loaded',
                'stats' => $this->rm->dashboard_stats(),
                'recent' => $recent
            );
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage());
        }

        echo json_encode($res);
    }

    public function getApplications()
    {
        $res = array('success' => false, 'message' => '', 'applications' => array());
        try {
            $status = $this->input->get('status', true) ?: 'pending';
            $status = $status == 'all' ? '' : $status;
            $applications = $this->vm->by_status($status);

            foreach ($applications as $row) {
                $row->tracking_id = visitor_tracking_id($row);
                $row->card_url = $row->status === 'approved' ? site_url('visitor/card/' . $row->qr_token) : '';
            }

            $res = array(
                'success' => true,
                'message' => 'Applications loaded',
                'applications' => $applications
            );
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage(), 'applications' => array());
        }

        echo json_encode($res);
    }

    public function approveApplication()
    {
        $res = array('success' => false, 'message' => '');
        try {
            $request = json_decode($this->input->post('data'));
            if (!$request || empty($request->id)) {
                throw new Exception('Invalid application request.');
            }

            $application = $this->vm->find($request->id);
            if (!$application || $application->status !== 'pending') {
                throw new Exception('Application is not pending.');
            }

            $pass_no = $this->vm->next_pass_no();
            $token = $this->vm->next_qr_token();

            $this->vm->approve($application->id, array(
                'pass_no' => $pass_no,
                'qr_token' => $token,
                'status' => 'approved',
                'approved_by' => $this->session->userdata('user_id'),
                'approved_at' => date('Y-m-d H:i:s'),
                'approved_by_ip' => $this->input->ip_address(),
                'updated_at' => date('Y-m-d H:i:s')
            ));

            $res = array(
                'success' => true,
                'message' => 'Application approved',
                'card_url' => site_url('visitor/card/' . $token)
            );
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage());
        }

        echo json_encode($res);
    }

    public function rejectApplication()
    {
        $res = array('success' => false, 'message' => '');
        try {
            $request = json_decode($this->input->post('data'));
            if (!$request || empty($request->id)) {
                throw new Exception('Invalid application request.');
            }

            $reason = isset($request->reason) ? trim($request->reason) : '';
            if (empty($reason)) {
                throw new Exception('Cancel Note is required for rejection.');
            }

            $this->vm->reject($request->id, array(
                'status' => 'rejected',
                'rejected_reason' => $reason,
                'approved_by_ip' => $this->input->ip_address(),
                'updated_at' => date('Y-m-d H:i:s')
            ));

            $res = array('success' => true, 'message' => 'Application rejected');
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage());
        }

        echo json_encode($res);
    }

    public function approve($id)
    {
        $application = $this->vm->find($id);
        if (!$application || $application->status !== 'pending') {
            redirect('admin/applications/pending');
        }

        $pass_no = $this->vm->next_pass_no();
        $token = $this->vm->next_qr_token();

        $this->vm->approve($id, array(
            'pass_no' => $pass_no,
            'qr_token' => $token,
            'status' => 'approved',
            'approved_by' => $this->session->userdata('user_id'),
            'approved_at' => date('Y-m-d H:i:s'),
            'approved_by_ip' => $this->input->ip_address(),
            'updated_at' => date('Y-m-d H:i:s')
        ));

        redirect('visitor/card/' . $token);
    }

    public function reject($id)
    {
        $reason = trim($this->input->post('rejected_reason', true));
        if (empty($reason)) {
            $this->session->set_flashdata('error', 'Cancel Note is required for rejection.');
            redirect('admin/applications/pending');
            return;
        }

        $this->vm->reject($id, array(
            'status' => 'rejected',
            'rejected_reason' => $reason,
            'approved_by_ip' => $this->input->ip_address(),
            'updated_at' => date('Y-m-d H:i:s')
        ));

        redirect('admin/applications/pending');
    }

    public function users()
    {
        $data['title'] = "Visitor User Accounts";
        $data['content'] = $this->load->view('admin/users', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function getUsers()
    {
        $res = array('success' => false, 'message' => '', 'users' => array());
        try {
            $this->load->model('Visitor_user_model', 'vum', TRUE);
            $users = $this->vum->get_all_users();
            foreach ($users as $row) {
                $row->photo_url = $row->photo ? base_url($row->photo) : '';
            }
            $res = array('success' => true, 'message' => 'Users loaded', 'users' => $users);
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage());
        }
        echo json_encode($res);
    }

    public function approveUser()
    {
        $res = array('success' => false, 'message' => '');
        try {
            $request = json_decode($this->input->post('data'));
            if (!$request || empty($request->id)) {
                throw new Exception('Invalid user request.');
            }
            $this->load->model('Visitor_user_model', 'vum', TRUE);
            $this->vum->update($request->id, array('status' => 1));
            $res = array('success' => true, 'message' => 'User account approved successfully.');
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage());
        }
        echo json_encode($res);
    }

    public function rejectUser()
    {
        $res = array('success' => false, 'message' => '');
        try {
            $request = json_decode($this->input->post('data'));
            if (!$request || empty($request->id)) {
                throw new Exception('Invalid user request.');
            }
            $this->load->model('Visitor_user_model', 'vum', TRUE);
            $this->vum->update($request->id, array('status' => 2));
            $res = array('success' => true, 'message' => 'User account suspended successfully.');
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage());
        }
        echo json_encode($res);
    }

    public function scanner()
    {
        $data['title'] = "Admin QR Code Scanner";
        $data['content'] = $this->load->view('admin/scanner', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function scanner_details()
    {
        $res = array('success' => false, 'message' => '');
        try {
            $token = trim($this->input->get('token', true));
            if (filter_var($token, FILTER_VALIDATE_URL) || preg_match('/https?:\/\//i', $token)) {
                $parts = explode('/', rtrim($token, '/'));
                $token = end($parts);
            }

            if (empty($token)) {
                throw new Exception('QR Token is required.');
            }

            $application = $this->vm->find_by_token($token);
            if (!$application) {
                throw new Exception('Visitor pass not found in database.');
            }

            $gate_logs = $this->db
                ->where('application_id', $application->id)
                ->order_by('entry_time', 'DESC')
                ->get('gate_logs')
                ->result();

            $application->tracking_id = visitor_tracking_id($application);
            $application->photo_url = $application->photo ? base_url($application->photo) : '';

            $res = array(
                'success' => true,
                'message' => 'Details loaded successfully.',
                'application' => $application,
                'gate_logs' => $gate_logs
            );
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage());
        }

        echo json_encode($res);
    }
}
