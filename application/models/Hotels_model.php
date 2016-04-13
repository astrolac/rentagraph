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

        $query = $this->db->get_where('hotels', array('uid' => $hotelid, 'isactive' => 1));
        return $query->row_array();
    }

    /*
        Функция добавляет отель с БД.
    */
    public function insert_hotel($data) {
        return $this->db->insert('hotels', $data);
    }

    /*
        Функия возвращает типы отелей.
    */
    public function get_htypes()
    {
        $query = $this->db->get('htypes');
        return $query->result_array();
    }

    /*
        Функция получения всех броней отеля за заданный период.
        Если период не задан, то выдает все брони отеля.
        Если задано только начало или конец периода, выдает с
        соответствующими ограничениями.
        Попадание в заданный период определяется на основе пересечения
        периодов.
    */
    public function get_bookings($huid, $datestart == FALSE, $dateend == FALSE) {
        ;
    }

    /*
        Функция добавляет в БД бронь для отеля.
    */
    public function insert_booking($data) {
        return $this->db->insert('bookings', $data);
    }

}
