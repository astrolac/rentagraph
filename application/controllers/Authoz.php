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
    public function authz($tryToAuth = FALSE)
    {
        if(!isset($_SESSION['login'])) {

            if($tryToAuth === FALSE) {
                $data['title'] = 'Авторизация';
            } else {
                $data['title'] = 'Ошибка авторизации! Повторите ввод логина и пароля.';
            }
            $this->load->helper('form');

            /*$this->load->library('form_validation');

            $this->form_validation->set_rules('title', 'Title', 'required');
            $this->form_validation->set_rules('text', 'Text', 'required');*/

            $this->load->view('header', $data);
            $this->load->view('auth_view', $data);
            $this->load->view('footer', $data);

        } else {
            unset($_SESSION['login']);
            $this->session->sess_destroy();
            $this->baselib->basefun();
            /*$data['title'] = '';
            $login = $_SESSION['login'];
            $data['user'] = $this->Users_model->get_users($login);*/
        }
    }

    public function auth_test()
    {
        $this->load->helper('form');

        /* Получим переданные данные о логине и пароле */
        $data = array(
            'login' => $this->input->post('login'),
            'passfraze' => $this->input->post('passfraze')
        );

        /* Проверим есть ли логин в базе пользователей */
        $userdb = $this->Users_model->get_users($data['login']);

        if(!isset($userdb['login'])) {
            $this->authz(TRUE);
        } elseif($userdb['login'] == $data['login']
                 && password_verify($data['passfraze'], $userdb['passfraze'])) {
            $_SESSION['login'] = $userdb['login'];
            $_SESSION['username'] = $userdb['username'];
            $_SESSION['roleid'] = $userdb['roleid'];
            $this->baselib->basefun();
        } else {
            $this->authz(TRUE);
        }
    }

}
