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
    */
    public function basefun() {
        /*
            Сначала проверяем залогинен ли пользователь.
            Если залогинен, загружаем данные о пользователе из сессии, формируем
            $data для корректного отображения форм и вызываем формы.
        */
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
            /* Сформируем штатный массив data для общих представлений. */
            $data = $this->baselib->makedataarray();

            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
            $this->load->view('footer', $data);
        /*
            Если не залогинен, формируем данныые для формы сообщения о
            необходимости авторизации и вызываем соответствующие формы.
        */
        } else {
            $data['title'] = 'Система RentaGRAPH';

            $data['rightmsg'] = "";
            $data['righthref'] = $this->config->item('base_url')."index.php/Authoz/authz/";
            $data['righthreftext'] = "Войти";

            $this->load->view('header', $data);
            $this->load->view('authatata', $data);
            $this->load->view('footer', $data);
        }
    }

    /*
        Контроллер отображает все дома/гостиницы [отели]
        и меню функционала по их добавлению/удалению/редактированию.
    */
    public function hotelsmaintain() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
            $data = $this->baselib->makedataarray();

            $data['innermenu'] = array (
                'Добавить' => $this->config->item('base_url')."index.php/base/hotelsadd/",
                'Изменить' => $this->config->item('base_url')."index.php/base/hotelsedit/",
                'Удалить' => $this->config->item('base_url')."index.php/base/hotelsdel/",
                'Вернуть' => $this->config->item('base_url')."index.php/base/hotelsrev/"
            );

            /* Загрузим отели для отображения. */
            $data['hotelsarray'] = $this->hotels_model->get_hotels(FALSE);

            /* Зададим заголовок для страницы. */
            $data['title'] = 'Дома/Гостиницы';

            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
            $this->load->view('showhotels', $data);
            $this->load->view('footer', $data);
        }
    }

    /*
        Функция выводит форму для добавления отеля.
    */
    public function hotelsadd() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
            $data = $this->baselib->makedataarray();

            $data['title'] = "Добавить отель";

            /* Загрузим типы отелей. */
            $htypes = $this->hotels_model->get_htypes();
            $data['htypes'] = array();
            /* Сформируем массив с типами отелей для представления. */
            foreach ($htypes as $value) {
                $data['htypes'][] = $value['htype'];
            }

            $this->load->helper('form');

            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
            $this->load->view('hotelsadd', $data);
            $this->load->view('footer', $data);
        }
    }

    /*
        Функия обрабатывает данные от формы добавления отеля и добавляет отель в БД.
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
                'percentfee' => floatval(str_replace(",",".",$this->input->post('percentfee'))),
                'fixedfee' => floatval(str_replace(",",".",$this->input->post('fixedfee'))),
                'price' => floatval(str_replace(",",".",$this->input->post('price'))),
                'isactive' => 1
            );

            $this->hotels_model->insert_hotel($data);

            /* Загрузим библиотеку помощника по url-ам и вызовем функцию редиректа. */
            $this->load->helper('url');
            redirect('base/hotelsmaintain');
        }
    }

    /*
        Функция отображения/добавления/удаления типов отелей.
    */
    public function htypes() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
            $data = $this->makedataarray();

            $data['innermenu'] = array (
                'Добавить' => $this->config->item('base_url')."index.php/base/htypeadd/",
                'Удалить' => $this->config->item('base_url')."index.php/base/htypedel/",
            );

            $data['hotelsarray'] = $this->hotels_model->get_htypes();
            /* Зададим заголовок для страницы. */
            $data['title'] = 'Типы отелей';

            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
            /*$this->load->view('showhtypes', $data);*/
            $this->load->view('footer', $data);
        }
    }

    /*
        Функция отображения сообщений об ошибках.
    */
    public function showerror() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
            /*$data = $this->makedataarray();
            switch ($_SESSION['errorinfo']['etype']) {
                case 1:   $data['etitle'] = 'Текущая бронь перекрывается с уже существующей!';
                          $data['nextstephref'] = 'booking/booking_add_form/'.$_SESSION['errorinfo']['forminfo']['huid'];
                          'nextsteptext' => 'Попробуйте выбрать другой период.',
                          break;
            }

            $data['etitle'] = $_SESSION['errorinfo']['etitle'];
            $data['nextstephref'] = $_SESSION['errorinfo']['nextstephref'];
            $data['nextsteptext'] = $_SESSION['errorinfo']['nextstephref'];



            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
            $this->load->view('show_error', $data);
            $this->load->view('footer', $data);*/
        }
    }
}
