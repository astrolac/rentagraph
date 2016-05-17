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
                'Добавить' => $this->config->item('base_url')."index.php/base/hotelsadd/"
            );

            /*  Получим массив с данными отелей ассоциированный по uid-ам. */
            $data['hotelsarray'] = $this->baselib->get_hotels_data(TRUE);
            /*  Получим имена отелей для отображения. */
            $data['hnames'] = $this->baselib->get_hnames(TRUE);
            /* Зададим заголовок для страницы. */
            $data['title'] = 'Отели';
            /*  Дадим ссылки на действия изменения и удаления. */
            $data['href'] = array (
                'edit' => array (
                    'text' => "Изменить",
                    'href' => $this->config->item('base_url')."index.php/base/hotelsadd/"
                ),
                'block' => array (
                    'text' => "Блокировать",
                    'href' => $this->config->item('base_url')."index.php/base/hotelsdel/"
                ),
                'rev' => array (
                    'text' => "Восстановить",
                    'href' => $this->config->item('base_url')."index.php/base/hotelsrev/"
                )
            );
            /*  Отобразим необходимые представления. */
            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
            $this->load->view('showhotels', $data);
            $this->load->view('footer', $data);
        }
    }

    /*
        Функция выводит форму для добавления отеля.
    */
    public function hotelsadd($huid = FALSE) {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
            $data = $this->baselib->makedataarray();

            if($huid == FALSE) {
                $data['title'] = "Добавить отель";
            } else {
                $data['title'] = "Изменение данных об отеле";
                $data['hoteldata'] = $this->hotels_model->get_hotels($huid);
            }
          /*  Получим соответствие имен отелей их uid-ам.
              Для этого сначала получим данные обо всех отелях, а затем
              из этих данных сформируем массив для передачи форме. */
            /*$data['hnames'] = array ();
            $allhotels = $this->hotels_model->get_hotels();
            foreach ($allhotels as $hdata) {
                $data['hnames'][$hdata['uid']] = $hdata['hname'];
            }*/
            $data['hnames'] = $this->baselib->get_hnames();
          /*  Загрузим типы отелей. */
            $htypes = $this->hotels_model->get_htypes();
            $data['htypes'] = array();
          /*  Сформируем массив с типами отелей для представления. */
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
        Если передан признак редактирования, то данные об отеле обновляют существующие.
    */
    public function hotelsadd_job() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
          /*  Сформируме массив с переданными данными. */
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
                'isactive' => 1,
                'puid' => intval($this->input->post('puid'))
            );
          /*  Проверим, не передан ли нам признак того что данные
              для редактирования отеля, а не для добавления. */
            if($this->input->post('isedit') == "YES") {
              /*  Если признак есть, то дополнительно извлекаем uid отеля и запускаем
                  функцию апдейта отеля в модели. */
                $huid = intval($this->input->post('huid'));
                $this->hotels_model->update_hotel($huid, $data);
            } else {
              /*  Иначе запускаем функцию добавления записи об отеле. */
                $this->hotels_model->insert_hotel($data);
            }

          /*  Загрузим библиотеку помощника по url-ам и вызовем функцию редиректа. */
            $this->load->helper('url');
            redirect('base/hotelsmaintain');
        }
    }

    /*
        Функция редактирования отеля.
    */
    public function hotelsedit() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
            $data = $this->baselib->makedataarray();
            $data['innermenu'] = array();
          /*  Загрузим отели для отображения. */
            $data['hotelsarray'] = $this->hotels_model->get_hotels(FALSE);
          /*  Сформируем заголовок для пользователя. */
            $data['title'] = "Выберете отель";
          /*  Сформируем переменную с ссылкой которая будет на имени отеля.
            2016-04-27 Это добавлено для того, чтобы использовать это представление
              не только для этой функции, но и для тех где требуется аналогичный
              выбор отеля и передача его uid по ссылке. */
            $data['href'] = $this->config->item('base_url')."index.php/base/hotelsadd/";
          /*  Отобразим необходимые представления. */
            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
            $this->load->view('booking_hselect', $data);
            $this->load->view('footer', $data);
        }
    }

    /*
        Функция блокировки отеля.
    */
    public function hotelsdel($huid = FALSE) {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
          /*  Если uid отеля передан, значит мы уже после формы выбора отеля
              и нужно просто перевести выбранный отель в статус неактивного. */
            if($huid) {
                $this->hotels_model->isactive_hotel($huid, 0);
              /*  Загрузим хелпер урлов для будущих редиректов. */
                $this->load->helper('url');
              /*  Сделаем редирект на страницу отображения всех броней. */
                redirect('base/hotelsmaintain');
            } else {
              /*  Иначе выдаем штатно страницу выбора отеля. */
                $data = $this->baselib->makedataarray();
                $data['innermenu'] = array();
              /*  Загрузим отели для отображения. */
                $data['hotelsarray'] = $this->hotels_model->get_hotels(FALSE);
              /*  Сформируем заголовок для пользователя. */
                $data['title'] = "Выберете отель для блокировки";
              /*  Ссылка на страницу-обработчик. */
                $data['href'] = $this->config->item('base_url')."index.php/base/hotelsdel/";
              /*  Отобразим необходимые представления. */
                $this->load->view('header', $data);
                $this->load->view('mainmenu', $data);
                $this->load->view('booking_hselect', $data);
                $this->load->view('footer', $data);
            }
        }
    }

    /*
        Функция разблокировки отеля.
    */
    public function hotelsrev($huid = FALSE) {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
          /*  Если uid отеля передан, значит мы уже после формы выбора отеля
              и нужно просто перевести выбранный отель в статус активного. */
            if($huid) {
                $this->hotels_model->isactive_hotel($huid, 1);
              /*  Загрузим хелпер урлов для будущих редиректов. */
                $this->load->helper('url');
              /*  Сделаем редирект на страницу отображения всех броней. */
                redirect('base/hotelsmaintain');
            } else {
              /*  Сформируем общие данные. */
                $data = $this->baselib->makedataarray();
                $data['innermenu'] = array();
              /*  Сформируем заголовок для пользователя. */
                $data['title'] = "Отели для разблокировки";
              /*  Создадим массив со ссылками действия. */
                $data['href'] = array (
                    array (
                        'text' => "Вернуть",
                        'href' => $this->config->item('base_url')."index.php/base/hotelsrev/"
                    )
                );
              /*  Получим массив с именами отелей. */
                /*Нужно получить все отели (get_all_hotels).
                Затем из всех отелей сформировать имена по следующему принуципу:
                  1. попадает в массив, если isactive = 0
                  2. попадает в массив, если есть дочерние у которых isactive = 0
                остальное штатно.
                Есть НО! При отображении действий на родительском НЕБЛОКИРОВАННОМ отеле
                также отобразится действие восстановления и при его активации восстановятся
                все дочерние.
                Как вариант можно сделать блокировку родительского со всеми потомками,
                а восстановление только каждой единицы в отдельности.*/

              /*  Отобразим необходимые представления. */
                $this->load->view('header', $data);
                $this->load->view('mainmenu', $data);
                $this->load->view('showhotels', $data);
                $this->load->view('footer', $data);




              /*  Загрузим отели для отображения. Вторым парамтером передаем состочние
                  поля isactive в таблице с отелями. */
                $data['hotelsarray'] = $this->hotels_model->get_hotels(FALSE,0);
            }
        }
    }

    /*
        Функция отображения/добавления/удаления типов отелей.
    */
    public function htypes($htuid = FALSE) {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
            $data = $this->baselib->makedataarray();
          /*  Если нам передали htuid то значит нажата кнопка удаления типа отеля.
              Т.о. просто удаляем соответствующий тип отеля.
              Если не передавали, то это либо просто вход в функцию из меню,
              либо добавление нового типа из формы. В случае добавления нового типа
              у нас будут значения в переменных переданных в POST. */
            if($htuid) {
                $this->hotels_model->htype_del($htuid);
            }
            if($this->input->post('htype')) {
                $this->hotels_model->htype_add($this->input->post('htype'));
            }

            $data['innermenu'] = array ();

            $this->load->helper('form');
          /*  Получим типы отелей. */
            $data['htypes'] = $this->hotels_model->get_htypes();
          /*  Сформируем урл для удаления типа отеля. */
            $data['hrefdel'] = $this->config->item('base_url')."index.php/base/htypes/";
          /*  Зададим заголовок для страницы. */
            $data['title'] = 'Типы отелей';
          /*  Отображаем представления. */
            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
            $this->load->view('showhtypes', $data);
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

    public function tmp_hc_func() {
        $hotels = $this->hotels_model->get_allall_hotels();
        foreach($hotels as $hotel) {
            $sqlstr = "INSERT INTO hotels (";
            foreach($hotel as $field => $value) {
                $sqlstr .= $field.",";
            }
            $sqlstr .= ") VALUES (";
            foreach($hotel as $field => $value) {
                if(is_string($value)) {
                    $sqlstr .= "'".$value."',";
                } else {
                    $sqlstr .= $value.",";
                }
            }
            $sqlstr .= ");<br>";
            echo $sqlstr;
        }
    }

    public function tmp_bc_func() {
        $hotels = $this->hotels_model->get_allall_bookings();
        foreach($hotels as $hotel) {
            $sqlstr = "INSERT INTO bookings (";
            foreach($hotel as $field => $value) {
                $sqlstr .= $field.",";
            }
            $sqlstr .= ") VALUES (";
            foreach($hotel as $field => $value) {
                if(is_string($value)) {
                    $sqlstr .= "'".$value."',";
                } else {
                    $sqlstr .= $value.",";
                }
            }
            $sqlstr .= ");<br>";
            echo $sqlstr;
        }
    }
}
