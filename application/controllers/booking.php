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
            $data = $this->baselib->makedataarray();

            $data['innermenu'] = array (
                /*'Добавить' => $this->config->item('base_url')."index.php/base/hotelsadd/",
                'Изменить' => $this->config->item('base_url')."index.php/base/hotelsedit/",
                'Удалить' => $this->config->item('base_url')."index.php/base/hotelsdel/",
                'Вернуть' => $this->config->item('base_url')."index.php/base/hotelsrev/"*/
            );

            /* Загрузим отели для отображения. */
            $data['hotelsarray'] = $this->hotels_model->get_hotels(FALSE);

            $data['title'] = "Выберете отель";

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
            $data = $this->baselib->makedataarray();

            $hotel = $this->hotels_model->get_hotels($huid);
            $data['title'] = "Добавить бронь для \"".$hotel['hname']."\"";

            $this->load->helper('form');

            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
            $this->load->view('booking_add_form', $data);
            $this->load->view('footer', $data);
        }
    }
}
