<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Baselib {

    protected $CI;

    public function __construct()
    {
        /*parent::__construct();*/

        $this->CI =& get_instance();

        /* Загрузим модель таблицы с пользователями */
        $this->CI->load->model('Users_model');

        /* Загрузим библиотеку сессий */
        $this->CI->load->library('session');

        /*$this->load->helper('url_helper');*/
    }

    public function basefun() {
        if(!isset($_SESSION['login'])) {
            $data['title'] = 'basefun';
            $this->CI->load->view('header', $data);
            $this->CI->load->view('authatata', $data);
            $this->CI->load->view('footer', $data);
        } else {
            $data['login'] = $_SESSION['login'];
            $data['username'] = $_SESSION['username'];
            $data['roleid'] = $_SESSION['roleid'];

            $this->CI->load->view('header', $data);

            $this->CI->load->view('footer', $data);
        }
    }
}
