<?php
/* Основной контроллер. */
class Base extends CI_Controller {

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
        Базовая функция контроллера.
        Фактически сама функция ничего не делает, а просто вызывает одноименную
        функцию из базовой библиотеки.
    */
    public function basefun() {
        $this->baselib->basefun();
    }

    /*
        Контроллер отображает все дома/гостиницы [отели]
        и меню функционала по их добавлению/удалению/редактированию.
        Собственно, вызывает одноименную функцию базовой библиотеки.
    */
    public function hotelsmaintain() {
        $this->baselib->hotelsmaintain();
    }

    /*
        Функция выводит форму для добавления отеля.
    */
    public function hotelsadd() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
            $data = $this->baselib->makedataarray();

            $data['title'] = "Добавить отель";

            $this->load->helper('form');

            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
            $this->load->view('hotelsadd', $data);
            $this->load->view('footer', $data);
        }
    }

    /*
        Функия обрабатывает данные от формы добавления отеля.
    */
    public function hotelsadd_job() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
            /* Сформируме массив с переданными данными. */
            $data = array(
                'hname' => $this->input->post('hname'),
                'htype' => $this->input->post('htype'),
                'person' => $this->input->post('person'),
                'personphone' => $this->input->post('personphone'),
                'haddress' => $this->input->post('address'),
                'hcomments' => $this->input->post('comments'),
                'percentfee' => floatval($this->input->post('percentfee')),
                'fixedfee' => floatval($this->input->post('fixedfee')),
                'price' => floatval($this->input->post('price')),
                'isactive' => 1
            );

            $this->hotels_model->insert_hotel($data);

            $this->baselib->hotelsmaintain();
        }
    }
}
