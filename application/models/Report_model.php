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

    public function entry_report($from, $to, $filters = array())
    {
        $this->db
            ->select('gate_logs.*, visitor_applications.name, visitor_applications.phone, visitor_applications.nid, visitor_applications.visit_to, departments.name AS department_name')
            ->join('visitor_applications', 'visitor_applications.id = gate_logs.application_id', 'left')
            ->join('departments', 'departments.id = visitor_applications.department_id', 'left');

        if (!empty($from)) {
            $this->db->where('DATE(gate_logs.entry_time) >=', $from);
        }
        if (!empty($to)) {
            $this->db->where('DATE(gate_logs.entry_time) <=', $to);
        }

        if (!empty($filters['name'])) {
            $this->db->like('visitor_applications.name', trim($filters['name']));
        }
        if (!empty($filters['phone'])) {
            $this->db->like('visitor_applications.phone', trim($filters['phone']));
        }
        if (!empty($filters['nid'])) {
            $this->db->like('visitor_applications.nid', trim($filters['nid']));
        }
        if (!empty($filters['pass_no'])) {
            $this->db->like('gate_logs.pass_no', trim($filters['pass_no']));
        }
        if (!empty($filters['department_id'])) {
            $this->db->where('visitor_applications.department_id', (int) $filters['department_id']);
        }
        if (!empty($filters['scan_status'])) {
            $this->db->where('gate_logs.scan_status', $filters['scan_status']);
        }

        return $this->db->order_by('gate_logs.entry_time', 'DESC')
            ->get('gate_logs')
            ->result();
    }

    public function department_report($from = null, $to = null)
    {
        $this->db
            ->select('departments.id, departments.name')
            ->select("SUM(CASE WHEN visitor_applications.status = 'pending' THEN 1 ELSE 0 END) AS pending_count")
            ->select("SUM(CASE WHEN visitor_applications.status = 'approved' THEN 1 ELSE 0 END) AS approved_count")
            ->select("SUM(CASE WHEN visitor_applications.status = 'rejected' THEN 1 ELSE 0 END) AS rejected_count")
            ->select("COUNT(visitor_applications.id) AS total_count")
            ->from('departments')
            ->join('visitor_applications', 'visitor_applications.department_id = departments.id', 'left');

        if (!empty($from)) {
            $this->db->where('DATE(visitor_applications.created_at) >=', $from);
        }
        if (!empty($to)) {
            $this->db->where('DATE(visitor_applications.created_at) <=', $to);
        }

        return $this->db
            ->group_by('departments.id, departments.name')
            ->order_by('departments.name', 'ASC')
            ->get()
            ->result();
    }
}

