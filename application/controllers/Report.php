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
        $data['title'] = "Reports";
        $data['content'] = $this->load->view('reports/index', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function getReport()
    {
        $res = array('success' => false, 'message' => '');
        try {
            $from = $this->input->get('from', true) ?: date('Y-m-d');
            $to = $this->input->get('to', true) ?: date('Y-m-d');

            $res = array(
                'success' => true,
                'message' => 'Report loaded',
                'summary' => $this->rm->range_summary($from, $to),
                'entries' => $this->rm->entry_report($from, $to)
            );
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage());
        }

        echo json_encode($res);
    }
}
