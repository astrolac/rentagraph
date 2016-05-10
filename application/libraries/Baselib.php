<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* Основная библиотека. */
class Baselib {

    /*
        Объявим переменную, по требованию codeigniter,
        для возможносто доступа к классам фреймворка.
    */
    protected $CI;

    public function __construct()
    {
        /* Получим ссылку для доступа к классам фреймворка. */
        $this->CI =& get_instance();
        /* Загрузим модель таблицы с пользователями. */
        $this->CI->load->model('Users_model');
        /* Загрузим модель таблицы с отелями. */
        $this->CI->load->model('Hotels_model');
    }

    /*
        Функция формирования массива для построения меню пользователя (согласно
        роли).
        Возвращаемый массив содержит пункты меню. Каждый пункт меню - массив.
        Массивы могут быть вложенными, если являются пунктами подменю.
        Вложенность не ограничена.
    */
    public function makeMenuArray($roleid) {
        $menuArray = array (
            array(
                'title' =>  'Бронирование',
                'href'  =>  $this->CI->config->item('base_url')."index.php/booking/bookings",
                'subm'  =>  array (
                              array (
                                'title' =>  'Забронировать',
                                'href'  =>  $this->CI->config->item('base_url')."index.php/booking/booking_add",
                                'subm'  =>  ''
                              ),
                              array (
                                'title' =>  'Снять бронь',
                                'href'  =>  $this->CI->config->item('base_url')."index.php/booking/booking_cancel",
                                'subm'  =>  ''
                              )
                            )
            ),
            array(
                'title' =>  'Справочники',
                'href'  =>  '#',
                'subm'  =>  array (
                              array (
                                'title' =>  'Отели',
                                'href'  =>  $this->CI->config->item('base_url')."index.php/base/hotelsmaintain",
                                'subm'  =>  ''
                              ),
                              array (
                                'title' =>  'Типы отелей',
                                'href'  =>  $this->CI->config->item('base_url')."index.php/base/htypes",
                                'subm'  =>  ''
                              ),
                              array (
                                'title' =>  'Пользователи',
                                'href'  =>  $this->CI->config->item('base_url')."index.php/authoz/usersmaintain",
                                'subm'  =>  ''
                              ),
                              array (
                                'title' =>  'Роли',
                                'href'  =>  $this->CI->config->item('base_url')."index.php/authoz/rolesmaintain",
                                'subm'  =>  ''
                              )
                            )
            ),
            array(
                'title' =>  'Справка',
                'href'  =>  $this->CI->config->item('base_url')."index.php/base/helpium",
                'subm'  =>  ''
            )
        );

        return $menuArray;
    }

    /*
        Функция формирует массив data для общих представлений и возвращает его.
    */
    public function makedataarray() {
        $data['login'] = $_SESSION['login'];
        $data['logon'] = TRUE;
        $data['username'] = $_SESSION['username'];
        /*$data['roleid'] = $_SESSION['roleid']; */

        $data['rightmsg'] = $_SESSION['username']." [".$_SESSION['login']."] ";
        $data['righthref'] = $this->CI->config->item('base_url')."index.php/Authoz/auth_end/";
        $data['righthreftext'] = 'Выход';

        $data['mainmenuarray'] = $this->makeMenuArray($_SESSION['roleid']);

        return $data;
    }

    public function get_hnames($allall = FALSE) {
        if($allall) {
          /* Загрузим отели для отображения. */
            $allhotels = $this->CI->hotels_model->get_allall_hotels();
        } else {
          /* Загрузим отели для отображения. */
            $allhotels = $this->CI->hotels_model->get_hotels(FALSE);
        }
      /*  Создадим массив наименований отелей для передачи в представление. */
        $hotelsname = array ();
        foreach($allhotels as $hotelitem) {
            //$childhotels = $this->hotels_model->get_hotels(FALSE, 1, $hotelitem['uid']);
            if($hotelitem['puid'] == 0) {
                $hotelsname[$hotelitem['uid']] = array (
                    'hname' => $hotelitem['hname'],
                    'chotels' => array ()
                );
            }
        }
        foreach($allhotels as $hotelitem) {
            if($hotelitem['puid'] != 0) {
                $hotelsname[$hotelitem['puid']]['chotels'][$hotelitem['uid']] = $hotelitem['hname'];
            }
        }
        /*
            В результате получается следующая структура имен отелей ...
              'uid отеля' => массив родителей
                              'hname' => имя отеля-родителя
                              'chotels' => массив детей
                                            'uid' => имя ребенка 1
                                            'uid' => имя ребенка 2
                                            ...
        */
        return $hotelsname;
    }

    public function get_hotels_data($allall = FALSE) {
        /*  Получим данные отелей и модифицируем структуру для отображения.
            Фактически мы к каждому массиву с записью из БД добавим ключ в виде значения
            поля uid этой записи. Делаем мы это для того, чтобы потом формировать
            отображение отталкиваясь от массива с именами отелей, где по uid будем
            вынимать информацию для отображения уже из этого массива. */
        /*  2016-05-10 Добавил на вход параметр allall это для того чтобы можно было выбрать все
            данные обо всех отелях. Независимо от активности, либо чего-го еще. По этому признаку
            вызывается специальная функция модели которая тупо выбирает все поля из таблицы hotels
            без каких либо условий.
            Аналогичную лажу добавил в get_hnames.
        */
            if($allall) {
              /* Загрузим отели для отображения. */
                $allhotels = $this->CI->hotels_model->get_allall_hotels();
            } else {
              /* Загрузим отели для отображения. */
                $allhotels = $this->CI->hotels_model->get_hotels(FALSE);
            }
        $hotelsarray = array ();
        foreach($allhotels as $hotelitem) {
            foreach($hotelitem as $field => $value) {
                $hotelsarray[$hotelitem['uid']][$field] = $value;
            }
        }
        return $hotelsarray;
    }
}
