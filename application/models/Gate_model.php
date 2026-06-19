<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Gate_model extends CI_Model
{
    public function has_valid_entry($application_id)
    {
        return $this->db
            ->where('application_id', (int) $application_id)
            ->where('scan_status', 'valid')
            ->count_all_results('gate_logs') > 0;
    }

    public function log($application_id, $pass_no, $token, $status, $remarks)
    {
        $this->db->insert('gate_logs', array(
            'application_id' => $application_id,
            'pass_no' => $pass_no,
            'qr_token' => $token,
            'scanned_by' => $this->session->userdata('user_id') ?: null,
            'scan_status' => $status,
            'entry_time' => date('Y-m-d H:i:s'),
            'remarks' => $remarks
        ));
    }
}

