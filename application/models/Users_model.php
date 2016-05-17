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
        $query = $this->db->get_where('users', array('login' => $login, 'isactive' => 1));
        return $query->row_array();
    }

    public function user_add($userdata) {
        $this->db->insert('users', $userdata);
    }
    public function user_del($login) {
        $querystr = "UPDATE users SET isactive=0 WHERE login LIKE '".$login."';";
        $query = $this->db->query($querystr);
    }
    public function user_act($login) {
        $querystr = "UPDATE users SET isactive=1 WHERE login LIKE '".$login."';";
        $query = $this->db->query($querystr);
    }

  /*
      Возвращает пунты меню по заданному родительскому uid, либо просто все записи таблицы.
  */
    public function get_menu($puid = FALSE) {
        if($puid !== FALSE) {
            $querystr = "SELECT * FROM menu WHERE puid=".$puid.";";
            $query = $this->db->query($querystr);
        } else {
            $querystr = "SELECT * FROM menu;";
            $query = $this->db->query($querystr);
        }
        return $query->result_array();
    }
}
