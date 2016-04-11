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

    /* Базовая функция. */
    public function basefun() {
        /*
            Сначала проверяем залогинен ли пользователь.
            Если залогинен, загружаем данные о пользователе из сессии, формируем
            $data для корректного отображения форм и вызываем формы.
        */
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
            /* Сформируем штатный массив data для общих представлений. */
            $data = $this->makedataarray();

            $this->CI->load->view('header', $data);
            $this->CI->load->view('mainmenu', $data);
            $this->CI->load->view('footer', $data);
        /*
            Если не залогинен, формируем данныые для формы сообщения о
            необходимости авторизации и вызываем соответствующие формы.
        */
        } else {
            $data['title'] = 'basefun';

            $data['rightmsg'] = "";
            $data['righthref'] = $this->CI->config->item('base_url')."index.php/Authoz/authz/";
            $data['righthreftext'] = "Войти";

            $this->CI->load->view('header', $data);
            $this->CI->load->view('authatata', $data);
            $this->CI->load->view('footer', $data);
        }
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
            'Справочники' =>
                array (
                    'Дома/гостиницы' => $this->CI->config->item('base_url')."index.php/base/hotelsmaintain",
                    'Пользователи' => $this->CI->config->item('base_url')."index.php/base/usersmaintain",
                    'Роли' => $this->CI->config->item('base_url')."index.php/base/rolesmaintain"
                ),
            'Справка' => $this->CI->config->item('base_url')."index.php/base/helpium.php"
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

    /*
        Функция отображает все дома/гостиницы [отели]
        и меню функционала по их добавлению/удалению/редактированию.
    */
    public function hotelsmaintain() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
            $data = $this->makedataarray();

            $data['innermenu'] = array (
                'Добавить' => $this->CI->config->item('base_url')."index.php/base/hotelsadd/",
                'Изменить' => $this->CI->config->item('base_url')."index.php/base/hotelsedit/",
                'Удалить' => $this->CI->config->item('base_url')."index.php/base/hotelsdel/",
                'Вернуть' => $this->CI->config->item('base_url')."index.php/base/hotelsrev/"
            );

            /* Загрузим отели для отображения. */
            $data['hotelsarray'] = $this->CI->hotels_model->get_hotels(FALSE);
            /* Зададим заголовок для страницы. */
            $data['title'] = 'Дома/Гостиницы';

            $this->CI->load->view('header', $data);
            $this->CI->load->view('mainmenu', $data);
            $this->CI->load->view('showhotels', $data);
            $this->CI->load->view('footer', $data);
        }
  }
}
