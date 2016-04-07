<?php
class Authoz extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        /* Загрузим модель таблицы с пользователями */
        $this->load->model('Users_model');

        /* Загрузим библиотеку сессий */
        $this->load->library('session');

        $this->load->library('baselib');
        /*$this->load->helper('url_helper');*/
    }

    /* Функция авторизации пользователя */
    public function authz()
    {
        if(!isset($_SESSION['logon']) || $_SESSION['logon'] == FALSE) {

            $data['title'] = 'Авторизация';

            $data['rightmsg'] = "";
            $data['righthref'] = $this->config->item('base_url')."index.php/Authoz/authz/";
            $data['righthreftext'] = "Войти";

            $this->load->helper('form');

            $this->load->view('header', $data);
            $this->load->view('auth_view', $data);
            $this->load->view('footer', $data);
        }
    }

    public function auth_end() {
        unset($_SESSION['login']);
        unset($_SESSION['username']);
        unset($_SESSION['roleid']);
        $_SESSION['logon'] = FALSE;
        $this->session->sess_destroy();
        $this->baselib->basefun();
    }

    public function auth_test() {

        $data = array(
            'login' => $this->input->post('login'),
            'passfraze' => $this->input->post('passfraze')
        );

        /* Получим данные о пользователе из БД */
        $userdb = $this->Users_model->get_users($data['login']);

        if($userdb['login'] == $data['login']
                 && password_verify($data['passfraze'], $userdb['passfraze'])) {
            $_SESSION['login'] = $userdb['login'];
            $_SESSION['logon'] = TRUE;
            $_SESSION['username'] = $userdb['username'];
            $_SESSION['roleid'] = $userdb['roleid'];
            $this->baselib->basefun();
        } else {
            $this->authz();
/*            $data['title'] = 'Ошибка авторизации! Повторите ввод данных!';
            $this->load->helper('form');
            $this->load->view('header', $data);
            $this->load->view('auth_view', $data);
            $this->load->view('footer', $data);*/
        }
    }
}
