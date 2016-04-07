<?php
/*
    Модель данных таблицы пользователей.
*/
class Users_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_users($login = FALSE)
    {
        /* Если логин пользователя не задан, то идет выборка всех
          пользователей. */
        if ($login === FALSE)
            {
                $query = $this->db->get('users');
                return $query->result_array();
            }
        /*  Если логин задан то соответственно делаем выборку по совпадению
            с логином. */
        $query = $this->db->get_where('users', array('login' => $login));
        return $query->row_array();
    }
}
