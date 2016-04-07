<?php
class Base extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        /* Загрузим модель таблицы с пользователями */
        /*$this->load->model('Users_model');*/

        /* Загрузим библиотеку сессий */
        $this->load->library('session');

        $this->load->library('baselib');

        /*$this->load->helper('url_helper');*/
    }

    public function basefun() {
        $this->baselib->basefun();
    }
}
