<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Report_model", "rm", TRUE);
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $this->load->model('Visitor_model', 'vm', TRUE);
        $data['title'] = "Reports";
        $data['departments'] = $this->vm->active_departments();
        $data['content'] = $this->load->view('reports/index', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function getReport()
    {
        $res = array('success' => false, 'message' => '');
        try {
            $from = $this->input->get('from', true) ?: '';
            $to = $this->input->get('to', true) ?: '';
            $departmentId = $this->input->get('department_id', true) ?: '';

            $filters = array(
                'name' => $this->input->get('name', true),
                'phone' => $this->input->get('phone', true),
                'nid' => $this->input->get('nid', true),
                'pass_no' => $this->input->get('pass_no', true),
                'department_id' => $this->input->get('department_id', true),
                'scan_status' => $this->input->get('scan_status', true),
            );

            $res = array(
                'success' => true,
                'message' => 'Report loaded',
                'summary' => $this->rm->range_summary($from ?: date('Y-m-d'), $to ?: date('Y-m-d')),
                'entries' => $this->rm->entry_report($from, $to, $filters),
                'department_report' => $this->rm->department_report($from, $to, $departmentId)
            );
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage());
        }

        echo json_encode($res);
    }

    public function applications()
    {
        $this->load->model('Visitor_model', 'vm', TRUE);
        $data['title'] = "Visitor Application Reports";
        $data['departments'] = $this->vm->active_departments();
        $data['content'] = $this->load->view('reports/applications', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function getApplicationReport()
    {
        $res = array('success' => false, 'message' => '');
        try {
            $from = $this->input->get('from', true) ?: '';
            $to = $this->input->get('to', true) ?: '';

            $filters = array(
                'name' => $this->input->get('name', true),
                'phone' => $this->input->get('phone', true),
                'nid' => $this->input->get('nid', true),
                'department_id' => $this->input->get('department_id', true),
                'status' => $this->input->get('status', true),
            );

            $res = array(
                'success' => true,
                'message' => 'Application report loaded',
                'visitor_report' => $this->rm->application_report($from, $to, $filters),
                'department_report' => $this->rm->department_application_report($from, $to, $filters)
            );
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage());
        }

        echo json_encode($res);
    }
}
