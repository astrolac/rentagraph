<?php
/*
    Контроллер авторизации пользователей.
*/
class Authoz extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        /* Загрузим модели */
        $this->load->model('users_model');
        $this->load->model('hotels_model');
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
            unset($_SESSION['role']);
            unset($_SESSION['mainmenuarray']);
            unset($_SESSION['scope']);
            $_SESSION['logon'] = FALSE;
            $this->session->sess_destroy();
            $this->load->helper('url');
            redirect('authoz/authz');
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
        $userdb = $userdb[0];
      /*  Проверим логин, пароль, то что пользователь не блокирован
          и если все хорошо запомним нужные данные в сессии. */
        if($userdb['login'] == $data['login']
                 && password_verify($data['passfraze'], $userdb['passfraze'])
                 && $userdb['isactive'] == 1) {
            $_SESSION['login'] = $userdb['login'];
            $_SESSION['logon'] = TRUE;
            $_SESSION['username'] = $userdb['username'];
          /*  Получим данные о разрешениях согласно id роли. */
            $rolefill = $this->users_model->get_roles($userdb['roleid']);
            /*  Механизм ролевого доступа следующий:
                Каждая функция будет отнесена к какому либо функциональному блоку.
                Доступность этих блоков соответственно прописывается в роли пользователя.
                При запуске каждая функция будет проверять не только авторизован пользователь или нет,
                но и есть ли в его записи разрешение на использование блока к которому сама функция
                относится. Если разрешение на блок есть все ОК, на нет и функции нет.
                Соотношения блоков и фукнций жесткое и прописано в таблице функций. */
              $_SESSION['role'] = array (
                  'roleid' => $userdb['roleid'],
                  'rcontrol' => $rolefill[0]['rcontrol'],
                  'hcontrol' => $rolefill[0]['hcontrol'],
                  'ucontrol' => $rolefill[0]['ucontrol'],
                  'bcontrol' => $rolefill[0]['bcontrol'],
                  'ownbonly' => $rolefill[0]['ownbonly']
              );
            $_SESSION['scope'] = $userdb['scope'];
            $_SESSION['mainmenuarray'] = $this->baselib->makeMenuArray($userdb['roleid']);
            redirect('base/basefun');
        } else {
            redirect('authoz/authz');
        }
      }
    }

  /*
      Функция отображает список пользователей и меню для управления ими.
  */
    public function usersmaintain($login = FALSE, $delor = FALSE) {
      if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['ucontrol']) {
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

          $data['innermenu'] = array (
              'Добавить пользователя' => $this->config->item('base_url')."index.php/authoz/user_add/",
          );

          $this->load->helper('form');
        /*  Получим перечень пользователей. */
          $data['users'] = $this->users_model->get_users();
        /*  Сформируем урл для удаления пользователя. */
          $data['udelref'] = $this->config->item('base_url')."index.php/authoz/usersmaintain/";
        /*  Сформируем урл для редактирования. */
          $data['ueditref'] = $this->config->item('base_url')."index.php/authoz/user_add/";
        /*  Зададим заголовок для страницы. */
          $data['title'] = 'Управление пользователями';
        /*  Отображаем представления. */
          $this->load->view('header', $data);
          $this->load->view('mainmenu', $data);
          $this->load->view('users_maintain', $data);
          $this->load->view('footer', $data);
      }
    }

    public function user_add($login = FALSE) {
      if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['ucontrol']) {
          $data = $this->baselib->makedataarray();
          $this->load->helper('form');

        /*  Получим данные о ролях. */
          $data['uroles'] = $this->users_model->get_roles();
        /*  Получим данные об областях видимости. */
          $data['scopes'] = $this->users_model->get_scopes();

          if($login) {
              $data['userdata'] = $this->users_model->get_users($login);
              $data['userdata'] = $data['userdata'][0];
          }
              /*  Необходимо получить данные о пользователе, затолкать их в массив и передать в форму.
                  Как признак что форма для редактирования будет наличие данных о пользователе.
                  Форма, получив данные о пользователе:
                    1. заполняет поля формы данными
                    2. делает недоступным для редактирования поле login
                    3. формирует скрытое поле isedit YES
                    4. данные для поля password удаляет, чтобы поле было пустым
                  useraction получив данные:
                    1. проверяет что делаем по скрытому полю isedit
                    2. если редактирование, формирует набор данных и вызывает UPDATE по login
                    при этом, если данные о пароле не передавались, то password в UPDATE не включаем.
                    Если же данные о пароле переданы, считаем что идет смена пароля и:
                      1. шифруем пароль
                      2. добавляем пароль в UPDATE. */
          $this->load->view('header', $data);
          $this->load->view('mainmenu', $data);
          $this->load->view('user_add_form', $data);
          $this->load->view('footer', $data);
      }
    }

    public function user_add_job() {
      if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['ucontrol']) {
          $this->load->helper('url');
          if($this->input->post('login') && $this->input->post('isedit') == 'NO') {
              $userdata = array(
                  'login' => $this->input->post('login'),
                  'isactive' => 1,
                  'username' => $this->input->post('username'),
                  'passfraze' => password_hash($this->input->post('passfraze'), PASSWORD_DEFAULT),
                  'roleid' => $this->input->post('roleid'),
                  'scope' => $this->input->post('scopeid')
              );
              $this->users_model->user_add($userdata);
          } else {
              $userdata = array(
                  'username' => $this->input->post('username'),
                  'roleid' => $this->input->post('roleid'),
                  'scope' => $this->input->post('scopeid')
              );
            /*  Проверим, если в пароле что-то передавалось, то считаем это новым паролем
                и добавляем параметр в апдейт. */
              if($this->input->post('passfraze') != "") {
                  $userdata['passfraze'] = password_hash($this->input->post('passfraze'), PASSWORD_DEFAULT);
              }
            /*  Апдейтим пользователя. */
              $this->users_model->user_update($this->input->post('login'), $userdata);
          }
          redirect('authoz/usersmaintain');
      }
    }

  /*
      2016-05-24
        Как будем ограничивать видимости?
        Самый простой способ делаем связку login пользователя <-> доступный отель. Далее при постоении списка
        uid-ов и имен отелей для отображения выбираем только те, которые IN доступные отели. Все просто.
        Вопрос, как сделать функционирование регионов отелей если будет доступ по регионам? Хотя ... а зачем?
        Мы можем просто сделать области видимости в разрезе регионов, в разрезе одного отеля и хоть как ...
        Едиинственное при отображении списка отелей в форме наполенения области видимости будет не лишним
        сделать две таблицы отелей - первая, отели которые уже внутри области; вторая, те что не внутри. Будет
        сразу видно какие входят и не входят.
        Также важно!
        Дочерние отели (комнаты в составе гостиниц, квартир, домов) не показываем - только родительские!
        Но при добавлении отеля в область видимости и исключении из него нужно будет соответственно
        добавлять/исключать и дочерние.
        Нужно ли в форме отеля для информации отображать принадлежит ли он к какой-либо
        области видимости/нескольким областям видимости? Думаю можно сделать для информативности,
        с параметрами только для чтения.
  */
    public function scopesmaintain($scopeid = FALSE) {
      if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['rcontrol']) {
          $data = $this->baselib->makedataarray();
          $data['innermenu'] = array ();

          if($scopeid) {
              $this->users_model->scope_del($scopeid);
          }

          if($this->input->post('scopename')) {
              $this->users_model->scope_add($this->input->post('scopename'));
          }

          $this->load->helper('form');
        /*  Получим перечень областей видимости. */
          $data['scopes'] = $this->users_model->get_scopes();
        /*  Сформируем урл для удаления области видимости. */
          $data['delref'] = $this->config->item('base_url')."index.php/authoz/scopesmaintain/";
        /*  Сформируем урл для редактирования. */
          $data['editref'] = $this->config->item('base_url')."index.php/authoz/scope_edit/";
        /*  Зададим заголовок для страницы. */
          $data['title'] = 'Области видимости';
        /*  Отображаем представления. */
          $this->load->view('header', $data);
          $this->load->view('mainmenu', $data);
          $this->load->view('scopes_maintain', $data);
          $this->load->view('footer', $data);
      }
    }

    public function scope_edit($scopeid) {
      if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['rcontrol']) {
          $data = $this->baselib->makedataarray();
          $data['innermenu'] = array ();

          $this->load->helper('form');
        /*  Сделаем две выборки:
              1. отели которые входят в область видимости
              2. и отели которые не входят в область видимости.
            Обе выборки делаем с uid родителя равным 0 [и isactive 1 - под вопросом].
            Выбираем из отелей. Применяем IN. ORDER BY hname. */
          $data['inscope'] = $this->users_model->get_hotels_in_scope($scopeid);
          $data['notinscope'] = $this->users_model->get_hotels_not_in_scopes($scopeid);
          $data['scopeid'] = $scopeid;
        /*  Отображаем представления. */
          $this->load->view('header', $data);
          $this->load->view('mainmenu', $data);
          $this->load->view('scope_edit_form', $data);
          $this->load->view('footer', $data);
      }
    }

    public function scope_edit_job() {
      if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['rcontrol']) {
          unset($_POST['submit']);
          $scopeid = $this->input->post('scopeid');
          unset($_POST['scopeid']);

          $this->users_model->scopefill_del($scopeid);

          foreach($_POST as $huid => $value) {
              $this->users_model->scopefill_add($scopeid, $huid);
            /*  Т.к. в нашем случае мы получаем отели только родительские, то нужно проверить
                нет ли у них детей и если есть пройтись по ним также в цикле. */
              $chotels = $this->hotels_model->get_chotels($huid, FALSE);
              foreach($chotels as $chotel) {
                  $this->users_model->scopefill_add($scopeid, $chotel['uid']);
              }
          }
          /*echo '<pre>';
          print_r( $_POST );
          echo '<pre>';*/

          $this->load->helper('url');
          redirect('authoz/scopesmaintain');
      }
    }

  /*
      Ведение ролей.
  */
    public function rolesmaintain($roleid = FALSE) {
      if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['rcontrol']) {
          $data = $this->baselib->makedataarray();
          $data['innermenu'] = array (
              'Добавить роль' => $this->config->item('base_url')."index.php/authoz/role_add/"
          );

          if($roleid) {
              $this->users_model->role_del($roleid);
            /*  Удалим все данные о пунктах меню доступных для этой роли. */
              $this->users_model->rolemenu_del($roleid);
          }

          /*if($this->input->post('rolename')) {
              $this->users_model->role_add($this->input->post('rolename'));
          }*/

          $this->load->helper('form');
        /*  Получим перечень ролей. */
          $data['roles'] = $this->users_model->get_roles();
        /*  Зададим заголовок для страницы. */
          $data['title'] = 'Управление ролями';
        /*  Сформируем урлы для построчного меню. */
          $data['urefedit'] = $this->config->item('base_url')."index.php/authoz/role_add/";
          $data['urefdel'] = $this->config->item('base_url')."index.php/authoz/rolesmaintain/";
        /*  Отображаем представления. */
          $this->load->view('header', $data);
          $this->load->view('mainmenu', $data);
          $this->load->view('roles_maintain', $data);
          $this->load->view('footer', $data);
      }
    }

  /*
      Редактирование параметров роли. Отображает форму для добавления/редактирования роли.
  */
    public function role_add($roleid = FALSE) {
      if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['rcontrol']) {
          $data = $this->baselib->makedataarray();
          $this->load->helper('form');
          if($roleid) {
              $data['roledata'] = $this->users_model->get_roles($roleid);
              $data['roledata'] = $data['roledata'][0];
          }
          $this->load->view('header', $data);
          $this->load->view('mainmenu', $data);
          $this->load->view('role_add_form', $data);
          $this->load->view('footer', $data);
      }
    }

    public function role_add_job() {
      if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE && $_SESSION['role']['rcontrol']) {
          $this->load->helper('url');
          if($this->input->post('isedit') == 'NO') {
            /*  2016-05-23
                  Обычно в качестве uid используется поле с автоинкрементом, чтобы при добавлении строки
                  не париться (ID формируется сам, средствами СУБД). Но в данном случае нам необходимо получить
                  ID до того как запись будет добавлена в БД, т.к. далее его нужно будет использовать для
                  создания записей доступных пуктов меню.
                  Потому была реализована функция в моделе, получения максимального uid-а и для таблицы ролей
                  с этого поля убран автоинкремент.
                  Получаем текущий максимальный uid, инкрементируем его и уже далее заталкиваем запись с
                  этим uid-ом. Ну и далее его используем для формирования записей доступных пунктов меню.
            */
              $maxid = $this->users_model->get_max_id("roles");
              $maxid++;
              $roledata = array (
                  'uid' => $maxid,
                  'title' => $this->input->post('title'),
                  'rcontrol' => (($this->input->post('rсontrol') == 'on') ? 1 : 0),
                  'hcontrol' => (($this->input->post('hсontrol') == 'on') ? 1 : 0),
                  'ucontrol' => (($this->input->post('uсontrol') == 'on') ? 1 : 0),
                  'bcontrol' => (($this->input->post('bсontrol') == 'on') ? 1 : 0),
                  'ownbonly' => (($this->input->post('ownbonly') == 'on') ? 1 : 0)
              );
            /*  Добавим новую роль в таблицу с ролями. */
              $this->users_model->role_add($roledata);
            /*  Добавим записи в таблицу для пунктов меню. */
            /*  2016-05-23
                  Получается следующее!
                  Если необходимо создать новый пункт меню, то:
                    1. нужно добавить этот пункт в таблицу menu
                    2. добавить его ниже, чтобы при добавлении/редактировании роли он прописывался в таблицу
                       соответствия пунктов меню ролям
                    3. если нужно чтобы он фигурироватл в какой-то уже существующей роли, то руками добавить
                       его в таблицу соответствия пунктов меню ролям.
                  Либо нужно реализовывать все механизмы ведения пунктов меню. Но пока мне кажется это излишне.
            */
              if($roledata['rcontrol'] || $roledata['hcontrol'] || $roledata['ucontrol']) {
                /*  Поскольку этот пункт меню используется для разных функциональных блоков, то надо проверить
                    их наличие отдельно и добавить его один раз отдельно, иначе возникнут лишние записи. */
                  $this->users_model->rolemenu_add($maxid, 2); /* Справочники */
              }
              if($roledata['rcontrol']) {
                  $this->users_model->rolemenu_add($maxid, 9); /* Роли */
                  $this->users_model->rolemenu_add($maxid, 10); /* Области видимости */
              }
              if($roledata['hcontrol']) {
                  $this->users_model->rolemenu_add($maxid, 6); /* Отели */
                  $this->users_model->rolemenu_add($maxid, 7); /* Типы отелей */
              }
              if($roledata['ucontrol']) {
                  $this->users_model->rolemenu_add($maxid, 8); /* Пользователи */
              }
              if($roledata['bcontrol']) {
                  $this->users_model->rolemenu_add($maxid, 1); /* Бронирование */
                  $this->users_model->rolemenu_add($maxid, 4); /* Добавить бронь */
                  $this->users_model->rolemenu_add($maxid, 5); /* Снять бронь */
              }
              $this->users_model->rolemenu_add($maxid, 3); /* Справка */
          } else {

              /*echo '<pre>';
              print_r( $_POST );
              echo '<pre>';*/
              $roleid = $this->input->post('roleid');
              $roledata = array (
                  'title' => $this->input->post('title'),
                  'rcontrol' => (($this->input->post('rсontrol') == 'on') ? 1 : 0),
                  'hcontrol' => (($this->input->post('hсontrol') == 'on') ? 1 : 0),
                  'ucontrol' => (($this->input->post('uсontrol') == 'on') ? 1 : 0),
                  'bcontrol' => (($this->input->post('bсontrol') == 'on') ? 1 : 0),
                  'ownbonly' => (($this->input->post('ownbonly') == 'on') ? 1 : 0)
              );
              $this->users_model->role_update($roleid, $roledata);
            /*  Удалим все данные о пунктах меню доступных для этой роли. */
              $this->users_model->rolemenu_del($roleid);
            /*  И добавим новые записи. */
              if($roledata['rcontrol'] || $roledata['hcontrol'] || $roledata['ucontrol']) {
                /*  Поскольку этот пункт меню используется для разных функциональных блоков, то надо проверить
                    их наличие отдельно и добавить его один раз отдельно, иначе возникнут лишние записи. */
                  $this->users_model->rolemenu_add($roleid, 2); /* Справочники */
              }
              if($roledata['rcontrol']) {
                  $this->users_model->rolemenu_add($roleid, 9); /* Роли */
                  $this->users_model->rolemenu_add($roleid, 10); /* Области видимости */
              }
              if($roledata['hcontrol']) {
                  $this->users_model->rolemenu_add($roleid, 6); /* Отели */
                  $this->users_model->rolemenu_add($roleid, 7); /* Типы отелей */
              }
              if($roledata['ucontrol']) {
                  $this->users_model->rolemenu_add($roleid, 8); /* Пользователи */
              }
              if($roledata['bcontrol']) {
                  $this->users_model->rolemenu_add($roleid, 1); /* Бронирование */
                  $this->users_model->rolemenu_add($roleid, 4); /* Добавить бронь */
                  $this->users_model->rolemenu_add($roleid, 5); /* Снять бронь */
              }
              $this->users_model->rolemenu_add($roleid, 3); /* Справка */
          }
          redirect('authoz/rolesmaintain');
      }
    }
}
