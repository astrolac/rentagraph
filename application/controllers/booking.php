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
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['bcontrol']) {
          /*  Загрузим универсальные данные. */
            $data = $this->baselib->makedataarray();
          /*  Сформируем массив с внутренним меню представления. */
            $data['innermenu'] = array (
                'Добавить бронь' => $this->config->item('base_url')."index.php/booking/booking_add/",
                'Снять бронь' => $this->config->item('base_url')."index.php/booking/booking_cancel/",
            );
          /*  Сформируем заголовок для пользователя. */
            $data['title'] = "Все брони";
          /*  Получим все активные отели. */
            $allhotels = $this->hotels_model->get_hotels(FALSE, 1);
          /*  Создадим массив наименований отелей для передачи в представление. */
            $hotelsname = $this->baselib->get_hnames();
          /*  Получим период всех активных броней. */
            $period = $this->hotels_model->get_active_bookings_period();

            $this->load->helper('date');

          /*  2016-04-22 По просьбе заказчика ограничиваем начало периода вчерашним днем. */
          /*  Получим текущую Unix метку времени. */
            $unixtime = time();
          /*  Вычтем из неё 24 часа по 60 мин и 60 секунд, т.е. сутки выраженные в секундах. */
            $unixtime -= 24 * 60 * 60;
          /*  2016-05-04 Вот тут меня ждал подвох. Может сложиться такая ситуация, при которой
                дата "вчера" должная стать началом периода может оказаться позже окончания периода.
                Т.о. получится что период обратный, что конечно приведет к ошибке формирования
                массива с датами в дальнейшем. Поэтому появилось такое условие, что если дата "вчера"
                больше даты окончания периода, то просто выводятся все брони - т.е. фактически
                исторические данные. */
            if($unixtime < mysql_to_unix($period[1])) {
              /*  В начальную дату периода затолкаем полученное значение, приведенное в строку. */
                $period[0] = date('Y-m-d', $unixtime);
            } else {
              /*  Либо опционально можно обе даты периода сделать равными "вчера". */
                /*$period[0] = date('Y-m-d', $unixtime);
                $period[1] = date('Y-m-d', $unixtime);*/
            }
          /*  Если входными параметрами заданы даты налача/конца периода, то подменим ими
              значения полученные перед этим. */
            if($datestart) {
                $period[0] = $datestart;
            }
            if($dateend) {
                $period[1] = $dateend;
            }
            $data['period']['datestart'] = substr($period[0], -2)."-".substr($period[0], 5, 2)."-".substr($period[0], 0, 4);
            $data['period']['dateend'] = substr($period[1], -2)."-".substr($period[1], 5, 2)."-".substr($period[1], 0, 4);
          /*  Загрузим хелпер дат. */
            $this->load->helper('date');
          /*  Получим массив дат периода.
            2016-04-21 Вот тут есть важное дополнение.
              Чтобы эта функция корректно работала нужно обязательно в php.ini задать
              значение для параметра date.timezone. Либо задавать его через функцию php
              date_default_timezone_set(). Возможно, когда будем реализовывать функционал
              для разных регионов сможем использовать эту функцию для задания значения timezone
              в рамках локальных сессий. Чтобы была возможность заводить пользователей из
              разных часовых поясов. Также в такой ситуации сможет помочь функция timezone_menu()
              из хелпера Date самого CI. */
            $datesarray = date_range($period[0], $period[1]);
            if(!is_array($datesarray)) {
                $datesarray = array ();
            }
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

    /*'Отобразить период'  => $this->config->item('base_url')."index.php/booking/booking_by_period/",*/
    public function bookings_by_period() {
      if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['bcontrol']) {
          $this->bookings($this->dateconvert($this->input->post('datestart')), $this->dateconvert($this->input->post('dateend')));
      }
    }

    /*
         Функция добавления брони.
    */
    public function booking_add() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['bcontrol']) {
          /*  Загрузим универсальные данные. */
            $data = $this->baselib->makedataarray();
          /*  Сформируем массив с внутренним меню представления. */
            $data['innermenu'] = array (
                'Отмена' => $this->config->item('base_url')."index.php/booking/bookings/",
            );
          /*  Загрузим отели для отображения. */
            $data['hotelsarray'] = $this->hotels_model->get_hotels(FALSE, 1);
          /*  Сформируем заголовок для пользователя. */
            $data['title'] = "Выберете отель";
          /*  Получим имена отелей для отображения. */
            $data['hnames'] = $this->baselib->get_hnames();
/*
            $hdata = $this->baselib->get_hotels_data();
            $data['hdata'] = array ();
            foreach($hdata as $hrow) {
                $bookingsdata = $this->hotels_model->get_bookings($hrow['uid']);
                $bstr = "";
                foreach($bookingsdata as $brow) {
                    echo "[c ".$brow['datein']." по ".$brow['dateout']."]";
                    $bstr += "[c ".$brow['datein']." по ".$brow['dateout']."]";
                }
                  echo $bstr;
                $data['hdata'][$hrow['uid']] = $bstr;
            }*/

          /*  Сформируем переменную с ссылкой которая будет на имени отеля.
            2016-04-27 Это добавлено для того, чтобы использовать это представление
              не только для этой функции, но и для тех где требуется аналогичный
              выбор отеля и передача его uid по ссылке. */
            $data['href'] = $this->config->item('base_url')."index.php/booking/booking_add_form/";
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
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['bcontrol'] &&
                                                            $this->baselib->is_hotel_in_my_scope($huid)) {
          /*  Загрузим универсальные данные. */
            $data = $this->baselib->makedataarray();
          /*  Получим данные об отеле из БД. Для формирования заголовка.
              Отображаем для пользователя название отеля, чтобы видел для какого он
              оформляет бронь. */
            $hotel = $this->hotels_model->get_hotels($huid, 1);
            $data['title'] = "Добавить бронь для \"".$hotel['hname']."\"";
          /*  В этой переменной передадим uid отеля. */
            $data['huid'] = $huid;
          /*  Проверим, если есть информация об ошибке, то запомним ее для
              передачи в форму. */
            if(isset($_SESSION['errorinfo'])) {
              /*  Здесь передаем текст ошибки. */
                $data['errortext'] = $_SESSION['errorinfo']['etext'];
              /*  Здесь информация для формы, чтобы не заполнять заново. */
                $data['forminfo'] = $_SESSION['errorinfo']['forminfo'];
              /*  Здесь некая лажа, которую будет обрабатывать представление. */
                $data['addinfo'] = $_SESSION['errorinfo']['blob'];
              /*  Проверим не редактирование ли формы происходит и если да,
                  то сообщим форме об этом. */
                if($_SESSION['errorinfo']['isedit'] == "YES") {
                    $data['isedit'] = "YES";
                }
              /*  Разрегестрируем переменную, чтобы она больше не болталась,
                  т.к. она больше не нужна. */
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
                                  'Бронь владельца',
                                  'Поставил бронь',
                                  'Дата/время бронирования'
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
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['bcontrol']) {
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
            if($_SESSION['role']['ownbonly']) {
               $bookinginfo['byowner'] = "on";
            }
            /*  Проверим что та бронь которую мы пытаемся добавить принадлежит отелю,
                который есть в нашей области видимости. */
            if($this->baselib->is_hotel_in_my_scope($bookinginfo['huid'])) {
              /*  Получим значение переменной-индикатора редактирования. */
                $isedit = $this->input->post('isedit');
              /*  Примем uid брони если редактирование. */
                $buid = $this->input->post('buid');
              /*  Это также для режима редактирования. */
                if($bookinginfo['beforepaydate'] == '0000-00-00') {
                    $bookinginfo['beforepaydate'] = '';
                }
                if(!is_string($bookinginfo['byowner'])) {
                    $bookinginfo['byowner'] = '';
                }
              /*  Загрузим хелпер урлов для будущих редиректов. */
                $this->load->helper('url');
              /*  Сейчас будем проверять не перекрывает ли новая бронь уже существующие.
                  Сначала получим все брони которые пересекаются с введенной. */
              /*  2016-05-01
                  Тут есть один момент, если это редактирование брони, то она будет пересекаться
                  сама с собой. Чтобы этого не происходило, то нужно сделать передачу uid брони
                  в запрос, а в самом запросе, в случае передачи номера брони исключать ее из
                  поиска. */
                $res = $this->hotels_model->get_bookings( $bookinginfo['huid'],
                                                          $bookinginfo['datein'],
                                                          $bookinginfo['dateout'],
                                                          $buid);
                $this->load->helper('date');
                if(mysql_to_unix($bookinginfo['dateout']) < mysql_to_unix($bookinginfo['datein'])) {
                  /*  Если дата выезда раньше чем дата заезда. */
                  /*  Сначала подготовим массив для формы. Чтобы при повторном
                      отображении формы введенные данные были сохранены. */
                    unset($bookinginfo['userlogin']);
                    unset($bookinginfo['isactive']);
                    $bookinginfo['datein'] = $this->input->post('datein');
                    $bookinginfo['dateout'] = $this->input->post('dateout');
                    $bookinginfo['beforepaydate'] = $this->input->post('beforepaydate');
                  /*  Теперь сформируем сам массив для отображения ошибки. */
                    $errorinfo = array (
                        'etext' => 'Дата заезда раньше даты выезда!',
                        'forminfo' => $bookinginfo,
                        'blob' => NULL
                    );
                  /*  Запомним эти данные в сессии. */
                    $_SESSION['errorinfo'] = $errorinfo;
                  /*  Если это редактирование то надо сообщить об этом или об обратном. */
                    if($isedit == "YES") {
                        $_SESSION['errorinfo']['isedit'] = "YES";
                      /*  Добавим информацию о номере брони. */
                        $_SESSION['errorinfo']['forminfo']['uid'] = $buid;
                    } else {
                        $_SESSION['errorinfo']['isedit'] = "NO";
                    }
                  /*  И сделаем редирект. */
                    redirect('booking/booking_add_form/'.$bookinginfo['huid']);
                }
                if (count($res) == 0) {
                  /*  Если никаких броней пересекающейся с нашей нет, то
                      добавим данные в БД, или проапдейтим текущую запись. */
                    if($isedit == "YES") {
                      /*  Если да, то проапдейтим запись. */
                        /*  Предварительно изымеме userlogin из набора данных, чтобы при апдейте
                            не менялся логин пользователя, который завел бронь. */
                            unset($bookinginfo['userlogin']);
                        $this->hotels_model->update_booking($buid, $bookinginfo);
                    } else {
                      /*  Если нет, то значит запись новая и добавляем новую запись. */
                        $this->hotels_model->insert_booking($bookinginfo);
                    }
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
                        'etext' => 'Бронь пересекается с одной из существующих!',
                        'forminfo' => $bookinginfo,
                        'blob' => $res
                    );
                  /*  Запомним эти данные в сессии. */
                    $_SESSION['errorinfo'] = $errorinfo;
                  /*  Если это редактирование то надо сообщить об этом или об обратном. */
                    if($isedit == "YES") {
                        $_SESSION['errorinfo']['isedit'] = "YES";
                      /*  Добавим информацию о номере брони. */
                        $_SESSION['errorinfo']['forminfo']['uid'] = $buid;
                    } else {
                        $_SESSION['errorinfo']['isedit'] = "NO";
                    }
                  /*  И сделаем редирект. */
                    redirect('booking/booking_add_form/'.$bookinginfo['huid']);
                }
            }
        }
    }

    /*
        Функция запроса номера брони для отмены.
    */
    public function booking_cancel($buid = FALSE) {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['bcontrol']) {
          /*  Загрузим универсальные данные. */
            $data = $this->baselib->makedataarray();
            $data['title'] = "Снять бронь";
          /*  Загрузим хелпер форм. */
            $this->load->helper('form');
          /*  Отобразим заголовок страницы и главное меню. */
            $this->load->view('header', $data);
            $this->load->view('mainmenu', $data);
          /*  Если нам передан номер брони ... */
            if($buid) {
              /*  Получим данные об отменяемой брони. */
                $bookingdata = $this->hotels_model->get_booking_data($buid);
                if($this->baselib->is_hotel_in_my_scope($bookingdata['huid'])) {
                    if($_SESSION['role']['ownbonly'] && $_SESSION['login'] != $bookingdata['userlogin']) {
                      /*  Изменим заголовок. */
                        $data['title'] = "Данная бронь не доступна для редактирования/снятия!";
                    } else {
                      /*  Передадим в форму номер брони. */
                        $data['buid'] = $buid;
                      /*  Изменим заголовок. */
                        $data['title'] = "Вы отменяете бронь";
                      /*  Получим имя отеля. */
                        $hotelname = $this->hotels_model->get_hotels($bookingdata['huid'], 1);
                        $hotelname = $hotelname['hname'];
                      /*  Сформируем массив для отображения дополнительной информации. */
                        $data['addinfo'] = array(
                            array(
                                'Номер брони',
                                'Отель',
                                'Дата заезда',
                                'Дата выезда',
                                'Гость',
                                'Комментарии',
                                'Бронь владельца'
                            ), array (
                                $bookingdata['uid'],
                                $hotelname,
                                substr($bookingdata['datein'], -2)."-".substr($bookingdata['datein'], 5, 2)."-".substr($bookingdata['datein'], 0, 4),
                                substr($bookingdata['dateout'], -2)."-".substr($bookingdata['dateout'], 5, 2)."-".substr($bookingdata['dateout'], 0, 4),
                                /*$bookingdata['datein'],
                                $bookingdata['dateout'],*/
                                $bookingdata['person'],
                                $bookingdata['comments'],
                                $bookingdata['byowner']
                            )
                        );
                      /*  Отобразим дополнительную информацию. */
                        $this->load->view('show_addinfo', $data);
                    }
                }
            }
          /*  Теперь отобразим саму форму и футер. */
            $this->load->view('booking_cancel_form', $data);
            $this->load->view('footer', $data);
        }
    }

    /*
        Функция непосредственно отмены брони.
    */
    public function booking_cancel_job() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['bcontrol']) {
            $buid = intval($this->input->post('buid'));
          /*  Получим данные об отменяемой брони. */
            $bookingdata = $this->hotels_model->get_booking_data($buid);
            if($this->baselib->is_hotel_in_my_scope($bookingdata['huid'])) {
              /*  Если форма запроса номера уже отображалась, значит информация
                  об отменяемой брони уже отображалась и можно отменять бронь. */
                if($this->input->post('isshown') == 'YES') {
                  /*  Загрузим хелпер урлов для будущих редиректов. */
                    $this->load->helper('url');
                  /*  Выполним функцию отмены брони модели, передав uid брони. */
                    $this->hotels_model->booking_cancel($buid);
                  /*  Сделаем редирект на страницу отображения всех броней. */
                    redirect('booking/bookings');
                } else {
                  /*  Иначе снова выполним вызов функции отмены брони, но
                      на вход подадим номер брони. */
                    $this->booking_cancel($buid);
                }
            }
        }
    }

    /*
        Функция редактирования брони.
    */
    public function booking_edit($buid) {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['bcontrol']) {
          /*  Загрузим универсальные данные. */
            $data = $this->baselib->makedataarray();
          /*  Получим данные об изменяемой брони. */
            $data['forminfo'] = $this->hotels_model->get_booking_data($buid);
            if($this->baselib->is_hotel_in_my_scope($data['forminfo']['huid'])) {
                $datein = $data['forminfo']['datein'];
                $data['forminfo']['datein'] = substr($datein, -2)."-".substr($datein, 5, 2)."-".substr($datein, 0, 4);
                $dateout = $data['forminfo']['dateout'];
                $data['forminfo']['dateout'] = substr($dateout, -2)."-".substr($dateout, 5, 2)."-".substr($dateout, 0, 4);
                $beforepaydate = $data['forminfo']['beforepaydate'];
                $data['forminfo']['beforepaydate'] = substr($beforepaydate, -2)."-".substr($beforepaydate, 5, 2)."-".substr($beforepaydate, 0, 4);
              /*  Скажем форме, что это редактирование существующей брони. */
                $data['isedit'] = "YES";
              /*  Получим имя отеля для заголовка. */
                $hotelname = $this->hotels_model->get_hotels($data['forminfo']['huid'], 1);
                $hotelname = $hotelname['hname'];
              /*  Сформируем заголовок. */
                $data['title'] = "Вы изменяете данные брони №".$buid." отеля \"".$hotelname."\"";
              /*  Загрузим хелпер форм. */
                $this->load->helper('form');
              /*  Отобразим заголовок страницы и главное меню. */
                $this->load->view('header', $data);
                $this->load->view('mainmenu', $data);
              /*  Далее отобразим саму форму. */
                $this->load->view('booking_add_form', $data);
                $this->load->view('footer', $data);
            }
        }
    }

    /*
        Функция отображения броней отеля.
    */
    public function bookings_by_hotel($huid) {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['bcontrol']) {
            if($this->baselib->is_hotel_in_my_scope($huid)) {
              /*  Загрузим универсальные данные. */
                $data = $this->baselib->makedataarray();
              /**/
                $data['innermenu'] = array (
                    'Добавить бронь' => $this->config->item('base_url')."index.php/booking/booking_add_form/".$huid
                );
              /*  Получим имя отеля. */
                $hotelname = $this->hotels_model->get_hotels($huid, 1);
                $hotelname = $hotelname['hname'];
              /*  Сформируем заголовок. */
                $data['title'] = "Брони отеля ".$hotelname;
              /*  Получим все активные брони отеля. */
                $data['bookings'] = $this->hotels_model->get_bookings($huid);

                $data['hrefedit'] = $this->config->item('base_url')."index.php/booking/booking_edit/";
                $data['hrefcancel'] = $this->config->item('base_url')."index.php/booking/booking_cancel/";
              /*  Отобразим необходимые представления. */
                $this->load->view('header', $data);
                $this->load->view('mainmenu', $data);
                $this->load->view('bookings_by_hotel', $data);
                $this->load->view('footer', $data);
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
