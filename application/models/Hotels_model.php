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
    public function get_hotels($hotelid = FALSE, $isactive = 1/*, $puid = 0*/)
    {
        if ($hotelid === FALSE) {
            //$querystr = "SELECT * FROM hotels WHERE isactive=".$isactive." AND puid=".$puid." ORDER BY hname";
            $querystr = "SELECT * FROM hotels WHERE isactive=".$isactive." ORDER BY hname";
            $query = $this->db->query($querystr);
            return $query->result_array();
        }

        $query = $this->db->get_where('hotels', array('uid' => $hotelid, 'isactive' => $isactive));
        return $query->row_array();
    }

    public function get_allall_hotels() {
        $querystr = "SELECT * FROM hotels ORDER BY hname";
        $query = $this->db->query($querystr);
        return $query->result_array();
    }

    public function get_chotels($hotelid, $puid, $isactive = 1)
    {
        $query = $this->db->get_where('hotels', array('uid' => $hotelid, 'puid' => $puid, 'isactive' => $isactive));
        return $query->row_array();
    }

    public function get_all_hotels() {
        $querystr = "SELECT * FROM hotels;";
        $query = $this->db->query($querystr);
        return $query->result_array();
    }

    /*
        Функция добавляет отель в БД.
    */
    public function insert_hotel($data) {
        return $this->db->insert('hotels', $data);
    }

    /*
        Функция обновляет данные об отеле.
    */
    public function update_hotel($huid, $data) {
        $querystr = "UPDATE hotels SET";
        foreach($data as $field => $value) {
            $querystr .= " ".$field."=";
            if(is_string($value)) {
                $querystr .= "'".$value."',";
            } else {
                $querystr .= $value.",";
            }
        }

        $querystr = substr($querystr,0,strlen($querystr)-1);

        $querystr .= " WHERE uid=".$huid.";";

        $query = $this->db->query($querystr);
    }

    public function isactive_hotel($huid, $isactive) {
        $stages = array ('uid', 'puid');
        foreach ($stages as $field) {
            $querystr = "UPDATE hotels SET isactive=".$isactive." WHERE ".$field."=".$huid;
            $query = $this->db->query($querystr);
        }
    }

    /*
        Функия возвращает типы отелей.
        Добавляет тип отеля.
        Удаляет тип отеля.
    */
    public function get_htypes()
    {
        $query = $this->db->get('htypes');
        return $query->result_array();
    }
    public function htype_add($htype) {
        $data = array ('htype' => $htype);
        $this->db->insert('htypes', $data);
    }
    public function htype_del($htuid) {
        $data = array ('uid' => $htuid);
        $this->db->delete('htypes', $data);
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
    public function get_bookings($huid, $datein = FALSE, $dateout = FALSE, $buid = FALSE) {
        $querystr = "SELECT * from bookings WHERE huid=".$huid." AND isactive=1" ;
        /* uid,datein,dateout,person,personphone,totalsum,beforepaysum,beforepaydate,comments,byowner,userlogin,bookingtimestamp */
        if ($datein) {
            $querystr .=" AND dateout>'".$datein."'";
        }
        if ($dateout) {
            $querystr .=" AND datein<'".$dateout."'";
        }
        if ($buid) {
            $querystr .=" AND uid<>".$buid;
        }
        $querystr .=" ORDER BY datein;";
        $query = $this->db->query($querystr);
        return $query->result_array();
    }

    public function get_allall_bookings() {
        $querystr = "SELECT * from bookings;";
        $query = $this->db->query($querystr);
        return $query->result_array();
    }

    /*
        Функция добавляет в БД бронь для отеля.
    */
    public function insert_booking($data) {
        return $this->db->insert('bookings', $data);
    }

    /*
        Функция апдейтит бронь.
    */
    public function update_booking($buid, $bookinginfo) {
      $querystr = "UPDATE bookings SET";
      foreach($bookinginfo as $field => $value) {
          $querystr .= " ".$field."=";
          if(is_string($value)) {
              $querystr .= "'".$value."',";
          } else {
              $querystr .= $value.",";
          }
      }

      $querystr = substr($querystr,0,strlen($querystr)-1);

      $querystr .= " WHERE uid=".$buid.";";

      $query = $this->db->query($querystr);
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

    /*
        Функция исполнения запроса отмены брони. Фактически апдейтит
        бронь переводя ее в неактивное состояние (isactive = 0).
    */
    public function booking_cancel($buid) {
        if($buid) {
            $data = array('isactive' => 0);
            $this->db->where('uid', $buid);
            $this->db->update('bookings', $data);
        }
    }

    /*
        Функция получения данных по заданной брони.
    */
    public function get_booking_data($buid) {
        $query = $this->db->get_where('bookings', array('uid' => $buid));
        return $query->row_array();
    }

    /*
        Функция возвращает максимальный период всех активных броней.
    */
    public function get_active_bookings_period() {
        $querystr = "SELECT MIN(datein) AS periodstart FROM bookings WHERE isactive=1;";
        $query = $this->db->query($querystr);
        $res = $query->result_array();
        $periodstart = $res[0]['periodstart'];

        $querystr = "SELECT MAX(dateout) AS periodend FROM bookings WHERE isactive=1;";
        $query = $this->db->query($querystr);
        $res = $query->result_array();
        $periodend = $res[0]['periodend'];

        return array($periodstart, $periodend);
    }

}
