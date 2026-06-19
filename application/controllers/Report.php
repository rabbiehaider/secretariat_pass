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
        $from = $this->input->get('from', true) ?: date('Y-m-d');
        $to = $this->input->get('to', true) ?: date('Y-m-d');

        $data['title'] = "Reports";
        $data['from'] = $from;
        $data['to'] = $to;
        $data['summary'] = $this->rm->range_summary($from, $to);
        $data['entries'] = $this->rm->entry_report($from, $to);
        
        $data['content'] = $this->load->view('reports/index', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }
}

