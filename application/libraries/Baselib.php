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
}
