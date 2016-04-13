<?php
/* Контроллер бронирования. */
class Booking extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
      /* Загрузим библиотеку сессий. */
        $this->load->library('session');
      /* Загрузим собственную базовую библиотеку. */
        $this->load->library('baselib');
      /* Загрузим модели. */
        $this->load->model('users_model');
        $this->load->model('hotels_model');
    }

    /*
         Функция добавления брони.
    */
    public function booking_add() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
          /*  Загрузим универсальные данные. */
            $data = $this->baselib->makedataarray();
          /*  Сформируем массив с внутренним меню представления. */
            $data['innermenu'] = array (
                /*'Добавить' => $this->config->item('base_url')."index.php/base/hotelsadd/",
                'Изменить' => $this->config->item('base_url')."index.php/base/hotelsedit/",
                'Удалить' => $this->config->item('base_url')."index.php/base/hotelsdel/",
                'Вернуть' => $this->config->item('base_url')."index.php/base/hotelsrev/"*/
            );
          /*  Загрузим отели для отображения. */
            $data['hotelsarray'] = $this->hotels_model->get_hotels(FALSE);
          /*  Сформируем заголовок для пользователя. */
            $data['title'] = "Выберете отель";
          /*  Отобразим необходимые представления. */
            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
            $this->load->view('booking_hselect', $data);
            $this->load->view('footer', $data);
        }
    }

    /*
        Функия формы добавления брони (уже после выбора отеля).
    */
    public function booking_add_form($huid) {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
          /*  Загрузим универсальные данные. */
            $data = $this->baselib->makedataarray();
          /*  Получим данные об отеле из БД. Для формирования заголовка.
              Отображаем для пользователя название отеля, чтобы видел для какого он
              оформляет бронь. */
            $hotel = $this->hotels_model->get_hotels($huid);
            $data['title'] = "Добавить бронь для \"".$hotel['hname']."\"";
          /*  В этой переменной передадим uid отеля. */
            $data['huid'] = $huid;
          /*  Загрузим хелпер форм. */
            $this->load->helper('form');
          /*  Отобразим необходимые представления. */
            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
            $this->load->view('booking_add_form', $data);
            $this->load->view('footer', $data);
        }
    }

    /*
        Функия собственно добавления брони в БД.
    */
    public function booking_add_job() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
          /*  Сформируем массив для загрузки из принятых данных. */
            $bookinginfo = array (
                'huid' => intval($this->input->post('huid')),
                'datein' => $this->dateconvert($this->input->post('datein')),
                'dateout' => $this->dateconvert($this->input->post('dateout')),
                'person' => $this->input->post('person'),
                'personphone' => $this->input->post('personphone'),
                'totalsum' => floatval($this->input->post('totalsum')),
                'beforepaysum' => floatval($this->input->post('beforepaysum')),
                'beforepaydate' => $this->dateconvert($this->input->post('beforepaydate')),
                'comments' => $this->input->post('comments'),
                'userlogin' => $_SESSION['login'],
                'isactive' => 1
            );
          /*  Сейчас будем проверять не перекрывает ли новая бронь уже существующие. */

          /*  Добавим данные в БД. */
            $this->hotels_model->insert_booking($bookinginfo);
          /*  Редирект на страницу бронирования. */
            redirect('booking/bookings');
        }
    }

    /*
        Функция конвертирует дату в формате 'DD-MM-YYYY' в формат для MySQL 'YYYY-MM-DD'
    */
    private function dateconvert($instr) {
        return substr($instr, -4)."-".substr($instr, 3, 2)."-".substr($instr, 0, 2);
    }
}
