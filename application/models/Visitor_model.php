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

    public function uploadImage($imgFile, $image, $dirName, $fileName = null)
    {
        if (empty($imgFile[$image]["name"])) {
            return null;
        }

        if (!file_exists($dirName)) {
            mkdir($dirName, 0777, true);
        }

        $name = basename($imgFile[$image]["name"]);
        $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($file_ext, array('jpg', 'jpeg', 'png'))) {
            return null;
        }

        $prefix = $fileName ? str_replace(" ", "_", $fileName) : 'visitor';
        $fileNewName = $prefix . '_' . uniqid() . '.' . $file_ext;
        $target_file = rtrim($dirName, '/') . '/' . $fileNewName;

        if (move_uploaded_file($imgFile[$image]["tmp_name"], $target_file)) {
            return $target_file;
        }

        return null;
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

    public function find_for_visitor($tracking_id, $phone)
    {
        $tracking_id = trim($tracking_id);

        if (ctype_digit($tracking_id)) {
            return $this->db
                ->select('visitor_applications.*, departments.name AS department_name')
                ->join('departments', 'departments.id = visitor_applications.department_id', 'left')
                ->where('visitor_applications.id', (int) $tracking_id)
                ->where('visitor_applications.phone', $phone)
                ->get('visitor_applications')
                ->row();
        }

        $applications = $this->db
            ->select('visitor_applications.*, departments.name AS department_name')
            ->join('departments', 'departments.id = visitor_applications.department_id', 'left')
            ->where('visitor_applications.phone', $phone)
            ->order_by('visitor_applications.id', 'DESC')
            ->get('visitor_applications')
            ->result();

        foreach ($applications as $application) {
            if ($this->tracking_id($application) === strtoupper($tracking_id)) {
                return $application;
            }
        }

        return null;
    }

    public function find_by_token($token)
    {
        $token = trim($token);
        if (filter_var($token, FILTER_VALIDATE_URL) || preg_match('/https?:\/\//i', $token)) {
            $parts = explode('/', rtrim($token, '/'));
            $token = end($parts);
        }

        return $this->db
            ->select('visitor_applications.*, departments.name AS department_name')
            ->join('departments', 'departments.id = visitor_applications.department_id', 'left')
            ->where('visitor_applications.qr_token', $token)
            ->get('visitor_applications')
            ->row();
    }

    public function find_for_logged_visitor($id, $visitor_id)
    {
        return $this->db
            ->select('visitor_applications.*, departments.name AS department_name')
            ->join('departments', 'departments.id = visitor_applications.department_id', 'left')
            ->where('visitor_applications.id', (int) $id)
            ->where('visitor_applications.visitor_id', (int) $visitor_id)
            ->get('visitor_applications')
            ->row();
    }

    public function by_visitor($visitor_id)
    {
        return $this->db
            ->select('visitor_applications.*, departments.name AS department_name')
            ->join('departments', 'departments.id = visitor_applications.department_id', 'left')
            ->where('visitor_applications.visitor_id', (int) $visitor_id)
            ->order_by('visitor_applications.id', 'DESC')
            ->get('visitor_applications')
            ->result();
    }

    public function visitor_stats($visitor_id)
    {
        return array(
            'total' => $this->db->where('visitor_id', (int) $visitor_id)->count_all_results('visitor_applications'),
            'pending' => $this->db->where('visitor_id', (int) $visitor_id)->where('status', 'pending')->count_all_results('visitor_applications'),
            'approved' => $this->db->where('visitor_id', (int) $visitor_id)->where('status', 'approved')->count_all_results('visitor_applications'),
            'rejected' => $this->db->where('visitor_id', (int) $visitor_id)->where('status', 'rejected')->count_all_results('visitor_applications')
        );
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

    public function next_qr_token()
    {
        do {
            $token = strtoupper(bin2hex(openssl_random_pseudo_bytes(5)));
        } while ($this->db->where('qr_token', $token)->count_all_results('visitor_applications') > 0);

        return $token;
    }

    public function tracking_id($application)
    {
        if (function_exists('visitor_tracking_id')) {
            return visitor_tracking_id($application);
        }

        $date_source = !empty($application->created_at) ? $application->created_at : $application->visit_date;
        $date_part = date('ymd', strtotime($date_source));
        $date_part = substr($date_part, 0, 2) . (int) substr($date_part, 2, 2) . substr($date_part, 4, 2);
        $seed = $application->id . '|' . $application->phone . '|' . $date_source;

        return 'TRK-' . $date_part . '-' . strtoupper(substr(hash('sha256', $seed), 0, 6));
    }
}
