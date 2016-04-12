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
        Возвращаемый массив содержит пункты меню в штатном формате $key => $value
        где $key - имя пункта меню, $ value - ссылка на страницу-обработчик.
        Если пункт меню содержит подпункты, то $value - массив подпунктов и т.д.
    */
    public function makeMenuArray($roleid) {
        $menuArray = array (
            'Бронирование' =>
                array (
                    'Забронировать' => $this->CI->config->item('base_url')."index.php/booking/booking_add",
                    'Снять бронь' => $this->CI->config->item('base_url')."index.php/booking/booking_cancel"
                ),

            'Справочники' =>
                array (
                    'Дома/гостиницы' => $this->CI->config->item('base_url')."index.php/base/hotelsmaintain",
                    'Типы' => $this->CI->config->item('base_url')."index.php/base/htypes",
                    'Пользователи' => $this->CI->config->item('base_url')."index.php/base/usersmaintain",
                    'Роли' => $this->CI->config->item('base_url')."index.php/base/rolesmaintain"
                ),

            'Справка' => $this->CI->config->item('base_url')."index.php/base/helpium"
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
