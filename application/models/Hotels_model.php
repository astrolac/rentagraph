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
        Функция получения всех ативных броней отеля за заданный период.
        Если период не задан, то выдает все активные брони отеля.
        Если задано только начало или конец периода, выдает с
        соответствующими ограничениями.
        Попадание в заданный период определяется на основе пересечения
        периодов.
        Диапазон дат ограничен 01-01-1900 и 31-12-2100, думаю хватит ... или нет ... ?
    */
    public function get_bookings($huid, $datein = FALSE, $dateout = FALSE) {
        $querystr = "SELECT uid,datein,dateout,person,personphone,totalsum,beforepaysum,beforepaydate,comments,byowner from bookings WHERE huid=".$huid." AND isactive=1";
        if ($datein) {
            $querystr .=" AND dateout>'".$datein."'";
        }
        if ($dateout) {
            $querystr .=" AND datein<'".$dateout."'";
        }
        $querystr .=" ORDER BY datein;";
        $query = $this->db->query($querystr);
        return $query->result_array();
    }

    /*
        Функция добавляет в БД бронь для отеля.
    */
    public function insert_booking($data) {
        return $this->db->insert('bookings', $data);
    }

    public function test_get_bookings($huid, $datein = FALSE, $dateout = FALSE) {
        $querystr = "SELECT uid,datein,dateout,person,personphone,totalsum,beforepaysum,beforepaydate,comments,byowner from bookings WHERE huid=".$huid." AND isactive=1";
        if ($datein) {
            $querystr .=" AND dateout>'".$datein."'";
        }
        if ($dateout) {
            $querystr .=" AND datein<'".$dateout."'";
        }
        $querystr .=" ORDER BY datein;";

        return $querystr;
    }

}
