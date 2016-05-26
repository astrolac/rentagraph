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
        Функция формирования массива для построения меню пользователя.
        Возвращаемый массив содержит пункты меню. Каждый пункт меню - массив.
        Массивы могут быть вложенными, если являются пунктами подменю.
        Вложенность не ограничена.
    */
    public function makePureMenuArray($roleid, $puid) {
      /*  Получаем из БД все пункты меню с заданным puid. */
        $menufromDB = $this->CI->Users_model->get_user_menu($roleid, $puid);
      /*  Создадим пустой массив. */
        $resmenu = array ();
      /*  Теперь пройдемся по нему. */
        foreach($menufromDB as $menuitem) {
          /*  Для каждого пункта меню создадим ассоциативный массив. */
            $resinmenu = array ();
          /*  Затолкаем в него данные из записи о пункте меню. */
            $resinmenu['uid'] = $menuitem['uid'];
            $resinmenu['title'] = $menuitem['title'];
            $resinmenu['href'] = $this->CI->config->item('base_url').$menuitem['href'];
          /*  Для подменю рекурсивно запустим эту же функцию передав в качестве родительского
              uid свой собственный. */
            $resinmenu['subm'] = $this->makePureMenuArray($roleid, $menuitem['uid']);
          /*  В результирующий массив затолкаем созданный элемент. */
            $resmenu[] = $resinmenu;
        }
        return $resmenu;
        /*  Стуртура массива меню:
          arrayitem:
              'uid' => uid пункта меню
              'title' => заголовок пункта меню
              'href' => URL пункта меню
              'sumb' => массив подменю этого пункта меню (по структуре повторяет родительский элемент)
                  и т.д. вложенность не ограничена
        */
    }

    public function makeMenuArray($roleid) {
        $menuArray = $this->makePureMenuArray($roleid, 0);
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

        $data['mainmenuarray'] = $_SESSION['mainmenuarray'];

        $data['innermenu'] = array ();

        return $data;
    }

    public function get_hnames($allall = FALSE) {
        if($allall) {
          /* Загрузим отели для отображения. */
            $allhotels = $this->CI->hotels_model->get_allall_hotels();
        } else {
          /* Загрузим отели для отображения. */
            $allhotels = $this->CI->hotels_model->get_hotels(FALSE, 1);
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
                $allhotels = $this->CI->hotels_model->get_hotels(FALSE, 1);
            }
        $hotelsarray = array ();
        foreach($allhotels as $hotelitem) {
            foreach($hotelitem as $field => $value) {
                $hotelsarray[$hotelitem['uid']][$field] = $value;
            }
        }
        return $hotelsarray;
    }

    public function is_hotel_in_my_scope($huid) {
        $hotels = $this->CI->hotels_model->get_hotels($huid, FALSE);
        if(count($hotels) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
