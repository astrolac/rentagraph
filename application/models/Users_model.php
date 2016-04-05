<?php
class Users_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }
    
    public function get_users($login = FALSE)
    {
        if ($login === FALSE)
            {
                $query = $this->db->get('users');
                return $query->result_array();
            }

        $query = $this->db->get_where('users', array('login' => $login));
        return $query->row_array();
    }
}