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
                'Добавить' => $this->config->item('base_url')."index.php/booking/booking_add/",
                'Отменить' => $this->config->item('base_url')."index.php/booking/booking_cancel/"
            );
          /*  Сформируем заголовок для пользователя. */
            $data['title'] = "Все брони";
          /*  Получим период всех активных броней. */
            $period = $this->hotels_model->get_active_bookings_period();
          /*  Загрузим хелпер дат. */
            $this->load->helper('date');
          /*  Получим массив дат периода. */
            $datesarray = date_range($period[0], $period[1]);
          /*  Получим все активные отели. */
            $allhotels = $this->hotels_model->get_hotels();
          /*
              Далее для каждого отеля выберем все брони. Всё затолкаем в массив.
              Затем будем формировать итоговый массив для построения таблицы.
              Массив будет многомерным, каждый элемент будет являться массивом
              содержащим дату, номер брони, признак брони владельца.
              На основе этих данных затем постоится таблица в которой на каждую
              дату будет заполняться ячейка: при наличии брони заливается зеленым
              и вставляется номер брони, при наличии признака брони владельца
              заливается темно серым и проставляется символы "БР". Если брони нет -
              пустая, белая ячейка.
          */
          /*  Создадим пустой массив для всех броней, всех отелей. */
            $allbookings = array();
          /*  Для каждого отеля из полученного массива отелей ... */
            foreach($allhotels as $hotelitem) {
              /*  Получим все записи о бронировании. */
                $hotelbookings = $this->hotels_model->get_bookings($hotelitem['uid']);
              /*  Создадим первый элемент результирующей строки, в который запишем данные
                  о том отеле, которому принадлежит эта строка. */
                $hotelresult = array( 'huid' => $hotelitem['uid'],
                                      'hname' => $hotelitem['hname'],
                                      'bookings' => array()
                               );
              /*  Далее, для каждой брони текущего отеля ... */
                foreach($hotelbookings as $currentbooking) {
                  /*  Получим диапазон дат текущей брони. */
                    $bookingdaterange = date_range($currentbooking['datein'], $currentbooking['dateout']);
                  /*  Для каждой даты полученного диапазона ... */
                    foreach($bookingdaterange as $currentdate) {
                      /*  Сформируем массив c данными брони для текущей даты и добавим его
                          в конец массива результирующей строки. */
                        $hotelresult['bookings'][] = array(
                                            'date' => $currentdate,
                                            'buid' => $currentbooking['uid'],
                                            'byowner' => $currentbooking['byowner']
                                          );
                    }
                }
              /*  Добавим в массив всех броней строку с разобранными бронями текущего отеля. */
                $allbookings[] = $hotelresult;
            }
          /*  Теперь у нас есть большой массив с данными, где все брони разобраны по датам,
              с данными того какая бронь в какой дате в разрезе отелей. Далее нужно составить
              результирующий массив-таблицу для всех дат глобального диапазона броней.
              Важно учесть, что брони могут пересекаться по первой/последней дате. */
            $finish = array();
            foreach ($allbookings as $currenthotel) {
                $hotelresult = array( 'huid' => $currenthotel['huid'],
                                      'hname' => $currenthotel['hname'],
                                      'dateinfo' => array()
                                );
                foreach($datesarray as $currentdate) {
                    $curdateinfo = array('date' => $currentdate);

                    foreach($currenthotel['bookings'] as $booking) {

                    }
                }
            }


//            $data['str'] = "Начало периода: ".$period[0]." Конец периода: ".$period[1];

          /*  Отобразим необходимые представления. */
            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
            $this->load->view('bookings_show', $data);
//            $this->load->view('testv', $data);
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
