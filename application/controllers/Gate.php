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
        $res = array('success' => false, 'message' => '');
        try {
            $request = json_decode($this->input->post('data'));
            $token = $request && !empty($request->token) ? trim($request->token) : trim($this->input->post('token', true));
            $res = $this->verify_token($token);
        } catch (Exception $ex) {
            $res = array('success' => false, 'status' => 'invalid', 'message' => $ex->getMessage());
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($res));
    }

    private function verify_token($token)
    {
        $token = trim($token);
        if (filter_var($token, FILTER_VALIDATE_URL) || preg_match('/https?:\/\//i', $token)) {
            $parts = explode('/', rtrim($token, '/'));
            $token = end($parts);
        }

        if (!$token) {
            return array('success' => false, 'status' => 'invalid', 'message' => 'Empty QR token');
        }

        $application = $this->vm->find_by_token($token);
        if (!$application) {
            $this->gm->log(null, null, $token, 'invalid', 'Token not found');
            return array('success' => false, 'status' => 'invalid', 'message' => 'Invalid pass');
        }

        if ($application->status === 'pending') {
            $this->gm->log($application->id, $application->pass_no, $token, 'pending', 'Not approved yet');
            return array('success' => false, 'status' => 'pending', 'message' => 'Pass is still pending');
        }

        if ($application->status === 'rejected') {
            $this->gm->log($application->id, $application->pass_no, $token, 'rejected', 'Rejected application');
            return array('success' => false, 'status' => 'rejected', 'message' => 'Pass was rejected');
        }

        if ($application->visit_date !== date('Y-m-d')) {
            $this->gm->log($application->id, $application->pass_no, $token, 'expired', 'Visit date mismatch');
            return array('success' => false, 'status' => 'expired', 'message' => 'Pass is not valid today');
        }

        if ($this->gm->has_valid_entry($application->id)) {
            $this->gm->log($application->id, $application->pass_no, $token, 'already_used', 'Duplicate entry attempt');
            return array('success' => false, 'status' => 'already_used', 'message' => 'Pass already used');
        }

        $this->gm->log($application->id, $application->pass_no, $token, 'valid', 'Entry allowed');

        $photo = '';
        if (!empty($application->photo)) {
            $photo = base_url($application->photo);
        } else if (!empty($application->visitor_id)) {
            $this->load->model('Visitor_user_model', 'vum', TRUE);
            $v_user = $this->vum->find($application->visitor_id);
            if ($v_user && !empty($v_user->photo)) {
                $photo = base_url($v_user->photo);
            }
        }

        return array(
            'success' => true,
            'status' => 'valid',
            'message' => 'Valid pass. Entry allowed.',
            'visitor' => array(
                'name' => $application->name,
                'phone' => $application->phone,
                'pass_no' => $application->pass_no,
                'visit_to' => $application->visit_to,
                'purpose' => $application->purpose,
                'photo' => $photo
            )
        );
    }
}
