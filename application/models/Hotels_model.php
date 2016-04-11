<?php
/*
    Модель данных таблицы пользователей.
*/
class Hotels_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    /*
        Функция возвращает все отели из таблицы, если не задан никакой uid, или
        только строку по заданному uid.
    */
    public function get_hotels($hotelid = FALSE)
    {
        if ($hotelid === FALSE) {
            $query = $this->db->get_where('hotels', array('isactive' => 1));
            return $query->result_array();
        }

        $query = $this->db->get_where('hotels', array('uid' => $hotelid, 'isactive' => 0));
        return $query->row_array();
    }

    public function insert_hotel($data) {
        return $this->db->insert('hotels', $data);
    }
}
