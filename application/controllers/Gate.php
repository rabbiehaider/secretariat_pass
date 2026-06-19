<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Gate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Visitor_model", "vm", TRUE);
        $this->load->model("Gate_model", "gm", TRUE);
    }

    public function scanner()
    {
        $data['title'] = "Gate Scanner";
        $data['content'] = $this->load->view('gate/scanner', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function verify()
    {
        $token = trim($this->input->post('token', true));
        $result = $this->verify_token($token);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    private function verify_token($token)
    {
        if (!$token) {
            return array('ok' => false, 'status' => 'invalid', 'message' => 'Empty QR token');
        }

        $application = $this->vm->find_by_token($token);
        if (!$application) {
            $this->gm->log(null, null, $token, 'invalid', 'Token not found');
            return array('ok' => false, 'status' => 'invalid', 'message' => 'Invalid pass');
        }

        if ($application->status === 'pending') {
            $this->gm->log($application->id, $application->pass_no, $token, 'pending', 'Not approved yet');
            return array('ok' => false, 'status' => 'pending', 'message' => 'Pass is still pending');
        }

        if ($application->status === 'rejected') {
            $this->gm->log($application->id, $application->pass_no, $token, 'rejected', 'Rejected application');
            return array('ok' => false, 'status' => 'rejected', 'message' => 'Pass was rejected');
        }

        if ($application->visit_date !== date('Y-m-d')) {
            $this->gm->log($application->id, $application->pass_no, $token, 'expired', 'Visit date mismatch');
            return array('ok' => false, 'status' => 'expired', 'message' => 'Pass is not valid today');
        }

        if ($this->gm->has_valid_entry($application->id)) {
            $this->gm->log($application->id, $application->pass_no, $token, 'already_used', 'Duplicate entry attempt');
            return array('ok' => false, 'status' => 'already_used', 'message' => 'Pass already used');
        }

        $this->gm->log($application->id, $application->pass_no, $token, 'valid', 'Entry allowed');

        return array(
            'ok' => true,
            'status' => 'valid',
            'message' => 'Valid pass. Entry allowed.',
            'visitor' => array(
                'name' => $application->name,
                'phone' => $application->phone,
                'pass_no' => $application->pass_no,
                'visit_to' => $application->visit_to,
                'purpose' => $application->purpose
            )
        );
    }
}
