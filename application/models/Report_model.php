<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report_model extends CI_Model
{
    public function dashboard_stats()
    {
        return array(
            'today_apply' => $this->count_applications(date('Y-m-d'), date('Y-m-d')),
            'pending' => $this->db->where('status', 'pending')->count_all_results('visitor_applications'),
            'approved' => $this->db->where('status', 'approved')->count_all_results('visitor_applications'),
            'used_today' => $this->db->where('scan_status', 'valid')->like('entry_time', date('Y-m-d'), 'after')->count_all_results('gate_logs')
        );
    }

    public function count_applications($from, $to)
    {
        return $this->db
            ->where('DATE(created_at) >=', $from)
            ->where('DATE(created_at) <=', $to)
            ->count_all_results('visitor_applications');
    }

    public function range_summary($from, $to)
    {
        return array(
            'applied' => $this->count_applications($from, $to),
            'approved' => $this->count_by_status($from, $to, 'approved'),
            'rejected' => $this->count_by_status($from, $to, 'rejected'),
            'used' => $this->db->where('scan_status', 'valid')->where('DATE(entry_time) >=', $from)->where('DATE(entry_time) <=', $to)->count_all_results('gate_logs')
        );
    }

    public function count_by_status($from, $to, $status)
    {
        return $this->db
            ->where('status', $status)
            ->where('DATE(created_at) >=', $from)
            ->where('DATE(created_at) <=', $to)
            ->count_all_results('visitor_applications');
    }

    public function entry_report($from, $to)
    {
        return $this->db
            ->select('gate_logs.*, visitor_applications.name, visitor_applications.phone, visitor_applications.visit_to')
            ->join('visitor_applications', 'visitor_applications.id = gate_logs.application_id', 'left')
            ->where('DATE(gate_logs.entry_time) >=', $from)
            ->where('DATE(gate_logs.entry_time) <=', $to)
            ->order_by('gate_logs.entry_time', 'DESC')
            ->get('gate_logs')
            ->result();
    }
}

