<?php
/*
    Контроллер авторизации пользователей.
*/
class Authoz extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        /* Загрузим модель таблицы с пользователями */
        $this->load->model('users_model');
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
            $this->load->helper('url');
            redirect('base/basefun');
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
        $this->load->helper('url');
        /* Сформируме массив с переданными данными о имени пользователя и пароле. */
        $data = array(
            'login' => $this->input->post('login'),
            'passfraze' => $this->input->post('passfraze')
        );
        /* Получим данные о пользователе из БД. */
        $userdb = $this->users_model->get_users($data['login']);
      /*  Проверим логин и пароль и если все хорошо запомним нужные данные в сессии. */
        if($userdb['login'] == $data['login']
                 && password_verify($data['passfraze'], $userdb['passfraze'])) {
            $_SESSION['login'] = $userdb['login'];
            $_SESSION['logon'] = TRUE;
            $_SESSION['username'] = $userdb['username'];
            $_SESSION['roleid'] = $userdb['roleid'];
            redirect('base/basefun');
        } else {
            redirect('authoz/authz');
        }
      }
    }

  /*
      Функция добавления/удаления пользователя.
  */
    public function usersmaintain($login = FALSE, $delor = FALSE) {
      if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
          $data = $this->baselib->makedataarray();
        /*  Если нам передали login то значит нажата кнопка удаления пользователя.
            Т.о. просто удаляем соответствующего пользователя.
            Если не передавали, то это либо просто вход в функцию из меню,
            либо добавление нового пользователя. В случае добавления нового пользователя
            у нас будут значения в переменных переданных в POST. */
          if($login && $delor == 0) {
              $this->users_model->user_del($login);
          } elseif ($login && $delor == 1) {
              $this->users_model->user_act($login);
          }
          if($this->input->post('login')) {
              $userdata = array(
                  'login' => $this->input->post('login'),
                  'isactive' => 1,
                  'username' => $this->input->post('username'),
                  'passfraze' => password_hash($this->input->post('passfraze'), PASSWORD_DEFAULT),
                /*  До реализации ролевого доступа в качестве id роли загоняем ноль. */
                  'roleid' => 0
              );
              $this->users_model->user_add($userdata);
          }

          $data['innermenu'] = array ();

          $this->load->helper('form');
        /*  Получим перечень пользователей. */
          $data['users'] = $this->users_model->get_users();
        /*  Сформируем урл для удаления типа отеля. */
          $data['urefdel'] = $this->config->item('base_url')."index.php/authoz/usersmaintain/";
        /*  Зададим заголовок для страницы. */
          $data['title'] = 'Управление пользователями';
        /*  Отображаем представления. */
          $this->load->view('header', $data);
          $this->load->view('mainmenu', $data);
          $this->load->view('users_maintain', $data);
          $this->load->view('footer', $data);
      }
    }
}
