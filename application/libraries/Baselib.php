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
            $data['login'] = $_SESSION['login'];
            $data['logon'] = TRUE;
            $data['username'] = $_SESSION['username'];
            $data['roleid'] = $_SESSION['roleid'];

            $data['rightmsg'] = $_SESSION['username']." [".$_SESSION['login']."] ";
            $data['righthref'] = $this->CI->config->item('base_url')."index.php/Authoz/auth_end/";
            $data['righthreftext'] = 'Выход';

            $this->CI->load->view('header', $data);
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
}
