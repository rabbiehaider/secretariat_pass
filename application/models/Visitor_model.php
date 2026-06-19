<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Visitor_model extends CI_Model
{
    public function active_departments()
    {
        return $this->db->where('status', 1)->order_by('name', 'ASC')->get('departments')->result();
    }

    public function create($data)
    {
        $this->db->insert('visitor_applications', $data);
        return $this->db->insert_id();
    }

    public function find($id)
    {
        return $this->db
            ->select('visitor_applications.*, departments.name AS department_name')
            ->join('departments', 'departments.id = visitor_applications.department_id', 'left')
            ->where('visitor_applications.id', (int) $id)
            ->get('visitor_applications')
            ->row();
    }

    public function find_for_visitor($id, $phone)
    {
        return $this->db
            ->select('visitor_applications.*, departments.name AS department_name')
            ->join('departments', 'departments.id = visitor_applications.department_id', 'left')
            ->where('visitor_applications.id', (int) $id)
            ->where('visitor_applications.phone', $phone)
            ->get('visitor_applications')
            ->row();
    }

    public function find_by_token($token)
    {
        return $this->db
            ->select('visitor_applications.*, departments.name AS department_name')
            ->join('departments', 'departments.id = visitor_applications.department_id', 'left')
            ->where('visitor_applications.qr_token', $token)
            ->get('visitor_applications')
            ->row();
    }

    public function by_status($status)
    {
        return $this->db
            ->select('visitor_applications.*, departments.name AS department_name')
            ->join('departments', 'departments.id = visitor_applications.department_id', 'left')
            ->where('visitor_applications.status', $status)
            ->order_by('visitor_applications.id', 'DESC')
            ->get('visitor_applications')
            ->result();
    }

    public function recent($limit)
    {
        return $this->db
            ->select('visitor_applications.*, departments.name AS department_name')
            ->join('departments', 'departments.id = visitor_applications.department_id', 'left')
            ->order_by('visitor_applications.id', 'DESC')
            ->limit((int) $limit)
            ->get('visitor_applications')
            ->result();
    }

    public function approve($id, $data)
    {
        return $this->db->where('id', (int) $id)->update('visitor_applications', $data);
    }

    public function reject($id, $data)
    {
        return $this->db->where('id', (int) $id)->where('status', 'pending')->update('visitor_applications', $data);
    }

    public function next_pass_no()
    {
        $prefix = 'VP-' . date('Ymd') . '-';
        $row = $this->db
            ->like('pass_no', $prefix, 'after')
            ->order_by('id', 'DESC')
            ->get('visitor_applications')
            ->row();

        $next = 1;
        if ($row && $row->pass_no) {
            $next = ((int) substr($row->pass_no, -4)) + 1;
        }

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
