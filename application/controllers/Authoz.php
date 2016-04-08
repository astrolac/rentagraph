<?php
/*
    Контроллер авторизации пользователей.
*/
class Authoz extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        /* Загрузим модель таблицы с пользователями */
        $this->load->model('Users_model');
        /* Загрузим библиотеку сессий */
        $this->load->library('session');
        /* Загрузим базовую библиотеку. */
        $this->load->library('baselib');
    }

    /*
        Функция авторизации пользователя.
    */
    public function authz()
    {
        /*
            Проверяем авторизован ли пользователь. Если нет, то готовим $data
            и отображаем представление с формой для авторизации.
            Если нет, то и суда нет.
        */
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

    /*
        Функция завершения сессии пользователя.
        Штатно, рубим данные сессии и саму сессию закрываем.
    */
    public function auth_end() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
            unset($_SESSION['login']);
            unset($_SESSION['username']);
            unset($_SESSION['roleid']);
            $_SESSION['logon'] = FALSE;
            $this->session->sess_destroy();
            $this->baselib->basefun();
        }
    }

    /*
        Функция проверки введенных пользовательских данных.
        Если логин и пользователь корректны, запоминаем данные о пользователе
        в сессии и вызываем базовую функцию.
        Если данные не корректны, запускаем функцию авторизации пользователя.
    */
    public function auth_test() {
      if(!isset($_SESSION['logon']) || $_SESSION['logon'] == FALSE) {
        /* Сформируме массив с переданными данными о имени пользователя и пароле. */
        $data = array(
            'login' => $this->input->post('login'),
            'passfraze' => $this->input->post('passfraze')
        );
        /* Получим данные о пользователе из БД. */
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
        }
      }
    }
}
