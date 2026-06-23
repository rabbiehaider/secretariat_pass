<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Visitor_panel extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Visitor_user_model", "vum", TRUE);
        $this->load->model("Visitor_model", "vm", TRUE);
        $this->require_visitor();
    }

    private function require_visitor()
    {
        if (!$this->session->userdata('visitor_id')) {
            redirect('visitor_auth/login');
        }
    }

    private function current_visitor()
    {
        $visitor = $this->vum->find_active($this->session->userdata('visitor_id'));
        if (!$visitor) {
            $this->session->unset_userdata(array('visitor_id', 'visitor_name', 'visitor_email', 'visitor_phone'));
            redirect('visitor_auth/login');
        }

        return $visitor;
    }

    public function dashboard()
    {
        $visitor = $this->current_visitor();

        $data['title'] = "Visitor Dashboard";
        $data['visitor'] = $visitor;
        $data['applications'] = $this->vm->by_visitor($visitor->id);
        $data['stats'] = $this->vm->visitor_stats($visitor->id);
        $data['content'] = $this->load->view('visitor_panel/dashboard', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function profile()
    {
        $visitor = $this->current_visitor();

        $data['title'] = "Visitor Profile";
        $data['visitor'] = $visitor;
        $data['content'] = $this->load->view('visitor_panel/profile', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function getProfile()
    {
        $res = array('success' => false, 'message' => '', 'profile' => null);
        try {
            $visitor = $this->current_visitor();
            $res = array('success' => true, 'message' => 'Profile loaded', 'profile' => $visitor);
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage(), 'profile' => null);
        }

        echo json_encode($res);
    }

    public function getApplications()
    {
        $res = array('success' => false, 'message' => '', 'applications' => array());
        try {
            $visitor = $this->current_visitor();
            $applications = $this->vm->by_visitor($visitor->id);

            foreach ($applications as $application) {
                $application->tracking_id = visitor_tracking_id($application);
                $application->card_url = $application->status === 'approved' ? site_url('visitor_panel/card/' . $application->id) : '';
            }

            $res = array('success' => true, 'message' => 'Applications loaded', 'applications' => $applications);
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage(), 'applications' => array());
        }

        echo json_encode($res);
    }

    public function card($id)
    {
        $visitor = $this->current_visitor();
        $data['application'] = $this->vm->find_for_logged_visitor((int) $id, $visitor->id);

        if (!$data['application'] || $data['application']->status !== 'approved') {
            show_404();
        }

        $data['title'] = "Visitor Card";
        $data['content'] = $this->load->view('visitor/card', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }
}
