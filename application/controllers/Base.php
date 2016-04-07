<?php
class Base extends CI_Controller {

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

    public function basefun() {
        $this->baselib->basefun();
        /*if(!isset($_SESSION['login'])) {
            $data['title'] = 'basefun';
            $this->load->view('header', $data);
            $this->load->view('authatata', $data);
            $this->load->view('footer', $data);
        } else {
            $data['login'] = $_SESSION['login'];
            $data['username'] = $_SESSION['username'];
            $data['roleid'] = $_SESSION['roleid'];

            $this->load->view('header', $data);

            $this->load->view('footer', $data);
        }*/
    }
}
