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
        if ($login === FALSE) {
            $querystr = "SELECT users.login,users.username,users.passfraze,users.roleid,users.isactive,users.scope,roles.title rolename,scopes.title scopename FROM users LEFT JOIN roles ON users.roleid = roles.uid LEFT JOIN scopes ON users.scope = scopes.uid";
/*                $query = $this->db->get('users');
                $query = $query->result_array();
                $newquery = array ();
                foreach($query as $qitem) {
                    $rolerow = $this->get_roles($qitem['roleid']);
                    $qitem['rolename'] = $rolerow[0]['title'];
                    $newquery[] = $qitem;
                }
                return $newquery;*/
        } else {
        /*  Если логин задан то соответственно делаем выборку по совпадению
            с логином. */
            $querystr = "SELECT users.login,users.username,users.passfraze,users.roleid,users.isactive,users.scope,roles.title rolename,scopes.title scopename FROM users LEFT JOIN roles ON users.roleid = roles.uid LEFT JOIN scopes ON users.scope = scopes.uid WHERE users.login = '";
            $querystr .= $login."';";
        }
        $query = $this->db->query($querystr);
        return $query->result_array();
    }
    /*
        Функции добавления/блокировки/активации пользователя.
    */
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
        Апдейтит пользователя.
    */
    public function user_update($login, $data) {
        $querystr = "UPDATE users SET";
        foreach($data as $field => $value) {
            $querystr .= " ".$field."=";
            if(is_string($value)) {
                $querystr .= "'".$value."',";
            } else {
                $querystr .= $value.",";
            }
        }

        $querystr = substr($querystr,0,strlen($querystr)-1);

        $querystr .= " WHERE login='".$login."';";

        $query = $this->db->query($querystr);
    }

  /*
      Возвращает пункты меню по заданному родительскому uid, либо просто все записи таблицы.
      2016-05-18 Думаю эту функцию в дальнейшем мы не будем использовать,
          вместо нее будет get_user_menu.
  */
    public function get_menu($puid = FALSE) {
        if($puid !== FALSE) {
            $querystr = "SELECT * FROM menu WHERE puid=".$puid." AND ispm=1 ORDER BY pmorder;";
            $query = $this->db->query($querystr);
        } else {
            $querystr = "SELECT * FROM menu ORDER BY pmorder;";
            $query = $this->db->query($querystr);
        }
        return $query->result_array();
    }
  /*
      Возвращает меню для пользователя заданное его ролью ограниченное родительским uid.
  */
    public function get_user_menu($roleid, $puid) {
        $querystr = "SELECT * FROM menu WHERE puid=".$puid." AND ispm=1 AND uid IN (SELECT pm FROM rolesfill WHERE ruid=".$roleid.") ORDER BY pmorder;";
        $query = $this->db->query($querystr);
        return $query->result_array();
    }

  /*
      Возвращает все роли, или если задан id роли, то конкретную роль.
  */
    public function get_roles($roleid = FALSE) {
        if($roleid == FALSE) {
            $querystr = "SELECT * FROM roles ORDER BY uid;";
        } else {
            $querystr = "SELECT * FROM roles WHERE uid=".$roleid.";";
        }
        $query = $this->db->query($querystr);
        return $query->result_array();
    }
  /*  Добавляет новую роль. */
    public function role_add($roledata) {
        $this->db->insert('roles', $roledata);
    }
  /*  Удаляем роль. При удалении роли удаляем не только саму роль,
      но и привязки пунктов меню для этой роли. */
    public function role_del($roleid) {
        /*$querystr = "DELETE FROM rolesfill WHERE ruid=".$roleid.";";
        $query = $this->db->query($querystr);*/

        $this->rolemenu_del($roleid);

        $querystr = "DELETE FROM roles WHERE uid=".$roleid.";";
        $this->db->query($querystr);
    }
  /*  Апдейтим роль. */
    public function role_update($roleid, $data) {
        $querystr = "UPDATE roles SET";
        foreach($data as $field => $value) {
            $querystr .= " ".$field."=";
            if(is_string($value)) {
                $querystr .= "'".$value."',";
            } else {
                $querystr .= $value.",";
            }
        }

        $querystr = substr($querystr,0,strlen($querystr)-1);

        $querystr .= " WHERE uid='".$roleid."';";

        $query = $this->db->query($querystr);
    }
  /*  Получаем максимальный id-роли. Нужно для того, чтобы при создании новой роли
      сформировать id и после создания собственно роли сделать свзяку с пунктами меню. */
    public function get_max_id($table) {
        $querystr = "SELECT MAX(uid) maxuid FROM ".$table.";";
        $query = $this->db->query($querystr);
        $query = $query->result_array();
        return $query[0]['maxuid'];
    }
  /*  Связывает пункт меню с ролью. */
    public function rolemenu_add($ruid, $pm) {
        $querystr = "INSERT INTO rolesfill (ruid, pm) VALUES (".$ruid.",".$pm.");";
        $query = $this->db->query($querystr);
    }
  /*  Удаляет связку пунктов меню с ролью. */
    public function rolemenu_del($roleid) {
        $querystr = "DELETE FROM rolesfill WHERE ruid=".$roleid.";";
        $this->db->query($querystr);
    }
  /*  Выдает все области видимости. */
    public function get_scopes() {
        $querystr = "SELECT * FROM scopes;";
        $query = $this->db->query($querystr);
        return $query->result_array();
    }
  /*  Добавляет область видимости. */
    public function scope_add($scopename) {
        $querystr = "INSERT INTO scopes (title) VALUES ('".$scopename."');";
        $this->db->query($querystr);
    }
  /*  Удаляет область видимости. */
    public function scope_del($scopeid) {
        $querystr = "DELETE FROM scopes WHERE uid=".$scopeid.";";
        $this->db->query($querystr);
    }
  /*  Удаляет данные о доступных отелях из области видимости. */
    public function scopefill_del($scopeid) {
        $querystr = "DELETE FROM scopesfill WHERE scope=".$scopeid.";";
        $this->db->query($querystr);
    }
  /*  Добавляет связку области видимости и отеля. */
    public function scopefill_add($scopeid, $huid) {
        $querystr = "INSERT INTO scopesfill (scope,hotel) VALUES (".$scopeid.",".$huid.")";
        $this->db->query($querystr);
    }
  /*  Выбирает все отели входящие в заданную область видимости. */
    public function get_hotels_in_scope($scopeid) {
        $querystr = "SELECT * FROM hotels WHERE puid=0 AND uid IN (SELECT hotel FROM scopesfill WHERE scope=".$scopeid.") ORDER BY hname;";
        $query = $this->db->query($querystr);
        return $query->result_array();
    }
  /*  Выбирает все отели НЕ входящие в заданную область видимости. */
    public function get_hotels_not_in_scopes($scopeid) {
        $querystr = "SELECT * FROM hotels WHERE puid=0 AND uid NOT IN (SELECT hotel FROM scopesfill WHERE scope=".$scopeid.") ORDER BY hname;";
        $query = $this->db->query($querystr);
        return $query->result_array();
    }

}
