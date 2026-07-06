<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function find_by_email($email)
    {
        return $this->db->where('email', $email)->get('users')->row();
    }

    public function update_last_login_ip($id, $ip)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update('users', array('last_login_ip' => $ip));
    }
}

