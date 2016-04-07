<?php
/* Основной контроллер. */
class Base extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        /* Загрузим библиотеку сессий. */
        $this->load->library('session');
        /* Загрузим собственную базовую библиотеку. */
        $this->load->library('baselib');
    }

    /*
        Базовая функция контроллера.
        Фактически сама функция ничего не делает, а просто вызывает одноименную
        функцию из базовой библиотеки.
    */
    public function basefun() {
        $this->baselib->basefun();
    }
}
