<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Visitor_user_model extends CI_Model
{
    public function create($data)
    {
        $this->db->insert('visitor_users', $data);
        return $this->db->insert_id();
    }

    public function find($id)
    {
        return $this->db->where('id', (int) $id)->get('visitor_users')->row();
    }

    public function find_active($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->where('status', 1)
            ->get('visitor_users')
            ->row();
    }

    public function find_by_email($email)
    {
        return $this->db->where('email', $email)->get('visitor_users')->row();
    }

    public function find_by_phone($phone)
    {
        return $this->db->where('phone', $phone)->get('visitor_users')->row();
    }

    public function find_by_login($login)
    {
        return $this->db
            ->where("(email = " . $this->db->escape($login) . " OR phone = " . $this->db->escape($login) . ")", null, false)
            ->where('status', 1)
            ->get('visitor_users')
            ->row();
    }

    public function email_exists($email)
    {
        return $this->db->where('email', $email)->count_all_results('visitor_users') > 0;
    }

    public function phone_exists($phone)
    {
        return $this->db->where('phone', $phone)->count_all_results('visitor_users') > 0;
    }

    public function update_last_login($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update('visitor_users', array('last_login_at' => date('Y-m-d H:i:s')));
    }
}
