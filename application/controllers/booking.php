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
         Функция отображения броней всех отелей в табличном виде
         с цветовой дифференциацией штанов.
    */
    public function bookings($datestart = FALSE, $dateend = FALSE) {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
          /*  Загрузим универсальные данные. */
            $data = $this->baselib->makedataarray();
          /*  Сформируем массив с внутренним меню представления. */
            $data['innermenu'] = array (
                'Отобразить период'  => $this->config->item('base_url')."index.php/booking/booking_show_period/",
                'Показать по отелю' => $this->config->item('base_url')."index.php/booking/booking_show_detail/",
                'Добавить бронь' => $this->config->item('base_url')."index.php/booking/booking_add/",
                'Снять бронь' => $this->config->item('base_url')."index.php/booking/booking_cancel/",

            );
          /*  Сформируем заголовок для пользователя. */
            $data['title'] = "Все брони";
          /*  Получим все активные отели. */
            $allhotels = $this->hotels_model->get_hotels();
          /*  Создадим массив наименований отелей для передачи в представление,
              чтобы потом не мучать БД, для поиска имен, т.к. массив с бронями в качестве
              ключей будет использовать uid отеля. */
            $hotelsname = array();
            foreach($allhotels as $hotelitem) {
                $hotelsname[$hotelitem['uid']] = $hotelitem['hname'];
            }
          /*  Получим период всех активных броней. */
            $period = $this->hotels_model->get_active_bookings_period();
          /*  2016-04-22 По просьбе заказчика ограничиваем начало периода вчерашним днем. */
          /*  Получим текущую Unix метку времени. */
            $unixtime = time();
          /*  Вычтем из неё 24 часа по 60 мин и 60 секунд, т.е. сутки выраженные в секундах. */
            $unixtime -= 24 * 60 * 60;
          /*  В начальную дату периода затолкаем полученное значение, приведенное в строку. */
            $period[0] = date('Y-m-d', $unixtime);
          /*  Если входными параметрами заданы даты налача/конца периода, то подменим ими
              значения полученные перед этим. */
            if($datestart) {
                $period[0] = $datestart;
            }
            if($dateend) {
                $period[1] = $dateend;
            }
          /*  Загрузим хелпер дат. */
            $this->load->helper('date');
          /*  Получим массив дат периода.
            2016-04-21 Вот тут есть важное дополнение.
              Чтобы эта функция корректно работала нужно обязательно в php.ini задать
              значение для параметра date.timezone. Либо задавать его через функцию php
              date_default_timezone_set(). Возможно, когда будем реализовывать функционал
              для разных регионов сможем использовать эту функцию для задания значения timezone
              в рамказ локальных сессий. Чтобы была возможность заводить пользователей из
              разных часовых поясов. Также в такой ситуации сможет помочь функция timezone_menu()
              из хелпера Date самого CI. */
            $datesarray = date_range($period[0], $period[1]);
          /*  Сформируем пустой массив для конечного итога. */
            $finish = array();
          /*  Для каждого отеля ... */
            foreach($allhotels as $hotelitem) {
              /*  Сформируем элемент массива с ключем uid отеля и пустым массивом в качестве значения. */
                $finish[$hotelitem['uid']]=array();
              /*  Для каждой даты из диапазона дат всех броней ... */
                foreach($datesarray as $currentdate) {
                  /*  Для текущего uid отеля сформируем элемент массива с ключем текущей даты
                      и пустым массивом в качестве значения. */
                    $finish[$hotelitem['uid']][$currentdate] = array();
                }
            }
          /*  Для каждого отеля из полученного массива отелей ... */
            foreach($allhotels as $hotelitem) {
              /*  Получим все записи о бронировании. */
                $hotelbookings = $this->hotels_model->get_bookings($hotelitem['uid']);
              /*  Далее, для каждой брони текущего отеля ... */
                foreach($hotelbookings as $currentbooking) {
                  /*  Получим диапазон дат текущей брони. */
                    $bookingdaterange = date_range($currentbooking['datein'], $currentbooking['dateout']);
                  /*  Для каждой даты полученного диапазона ... */
                    foreach($bookingdaterange as $currentdate) {
                      /*  В результирующий массив по ключу uid отеля и соответствующей даты
                          добавим данные о брони. */
                        $finish[$hotelitem['uid']][$currentdate][] = array(
                                    'buid' => $currentbooking['uid'],
                                    'byowner' => $currentbooking['byowner']
                                    );
                    }
                }
            }
          /*
              Получается следующая структура ...
                'uid отеля' => массив
                                'дата' => массив
                                            массив бронь 1
                                            массив бронь 2
                                'дата' => пустой массив
                                'дата' => массив
                                            массив бронь 1
          */
          /*  Загоним в массив data данные о периоде и всех бронях для передачи в представление. */
            $data['datesarray'] = $datesarray;
            $data['finish'] = $finish;
            $data['hotelsname'] = $hotelsname;
          /*  Отобразим необходимые представления. */
            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
            $this->load->view('bookings_show', $data);
            $this->load->view('footer', $data);
        }
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
                'Отмена' => $this->config->item('base_url')."index.php/booking/bookings/",
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

            if(isset($_SESSION['errorinfo'])) {
                $data['errortext'] = $_SESSION['errorinfo']['etext'];
                $data['forminfo'] = $_SESSION['errorinfo']['forminfo'];
                $data['addinfo'] = $_SESSION['errorinfo']['blob'];
                unset($_SESSION['errorinfo']);
            }
          /*  Загрузим хелпер форм. */
            $this->load->helper('form');
          /*  Отобразим необходимые представления. */
            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
          /*  Если есть текст ошибки - отобразим его. */
            if(isset($data['errortext'])) {
                $this->load->view('show_error', $data);
            }
          /*  Далее отобразим саму форму. */
            $this->load->view('booking_add_form', $data);
          /*  И если нужно еще какую-нибудь лажу отобразить ее также выдаем. */
            if(isset($data['addinfo'])) {
              /*  В случае если лажа передана, сформируем для нее заголовок
                  и добавим его в массив с лажей.
                  Важно отметить что лажа это всегда какая-либо таблица. */
                array_unshift($data['addinfo'],
                                array(
                                  'Номер брони',
                                  'Дата заезда',
                                  'Дата выезда',
                                  'Гость',
                                  'Номер телефона',
                                  'Стоимость проживания',
                                  'Сумма предоплаты',
                                  'Дата внесения предоплаты',
                                  'Комментарии',
                                  'Бронь владельца'
                                )
                              );
              /*  Теперь отобразим лажу. */
                $this->load->view('show_addinfo', $data);
            }
          /*  Ну и футер как обычно. */
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
                'totalsum' => floatval(str_replace(",",".",$this->input->post('totalsum'))),
                'beforepaysum' => floatval(str_replace(",",".",$this->input->post('beforepaysum'))),
                'beforepaydate' => $this->dateconvert($this->input->post('beforepaydate')),
                'comments' => $this->input->post('comments'),
                'userlogin' => $_SESSION['login'],
                'byowner' => $this->input->post('byowner'),
                'isactive' => 1
            );
          /*  Загрузим хелпер урлов для будущих редиректов. */
            $this->load->helper('url');
          /*  Сейчас будем проверять не перекрывает ли новая бронь уже существующие.
              Сначала получим все брони которые пересекаются с введенной. */
            $res = $this->hotels_model->get_bookings( $bookinginfo['huid'],
                                                      $bookinginfo['datein'],
                                                      $bookinginfo['dateout']);
            if (count($res) == 0) {
              /*  Если никаких броней пересекающейся с нашей нет, то добавим данные в БД. */
                $this->hotels_model->insert_booking($bookinginfo);
              /*  Редирект на общую страницу бронирования. */
                redirect('booking/bookings');
            } else {
              /*  Если что-то все же обнаружим, то сделаем редирект на страницу
                  формы добавления брони, но с уведомлением об ошибке.
                  Для этого сформируем данные для сообщения об ошибке. */

              /*  Сначала подготовим массив для формы. Чтобы при повторном
                  отображении формы введенные данные были сохранены. */
                unset($bookinginfo['userlogin']);
                unset($bookinginfo['isactive']);
                $bookinginfo['datein'] = $this->input->post('datein');
                $bookinginfo['dateout'] = $this->input->post('dateout');
                $bookinginfo['beforepaydate'] = $this->input->post('beforepaydate');
              /*  Теперь сформируем сам массив для отображения ошибки. */
                $errorinfo = array (
                    'etext' => 'Новая бронь пересекается с одной из существующих!',
                    'forminfo' => $bookinginfo,
                    'blob' => $res,
                );
              /*  Запомним эти данные в сессии. */
                $_SESSION['errorinfo'] = $errorinfo;
              /*  И сделаем редирект. */
                redirect('booking/booking_add_form/'.$bookinginfo['huid']);
            }
        }
    }

    /*
        Функция запроса номера брони для отмены.
    */
    public function booking_cancel($buid) {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
          /*  Загрузим универсальные данные. */
            $data = $this->baselib->makedataarray();
            $data['title'] = "Снять бронь";

        }
    }

    /*
        Функция конвертирует дату в формате 'DD-MM-YYYY' в формат для MySQL 'YYYY-MM-DD'
    */
    private function dateconvert($instr) {
        return substr($instr, -4)."-".substr($instr, 3, 2)."-".substr($instr, 0, 2);
    }

    public function testv() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
            $data = $this->baselib->makedataarray();
              $data['str'] = $this->hotels_model->test_get_bookings(4,'2016-03-30','2016-04-25');
            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
              $this->load->view('testv', $data);
            $this->load->view('footer', $data);
        }
    }
}
