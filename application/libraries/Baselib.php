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

        /*$this->load->helper('url_helper');*/
    }

    public function basefun() {
        if(isset($_SESSION['logon']) && $_SESSION['logon'] == TRUE) {
            $data['login'] = $_SESSION['login'];
            $data['logon'] = TRUE;
            $data['username'] = $_SESSION['username'];
            $data['roleid'] = $_SESSION['roleid'];

            $data['rightmsg'] = $_SESSION['username']." [".$_SESSION['login']."] ";
            $data['righthref'] = $this->CI->config->item('base_url')."index.php/Authoz/auth_end/";
            $data['righthreftext'] = 'Выход';

            $this->CI->load->view('header', $data);
            $this->CI->load->view('footer', $data);
        } else {
            $data['title'] = 'basefun';

            $data['rightmsg'] = "";
            $data['righthref'] = $this->CI->config->item('base_url')."index.php/Authoz/authz/";
            $data['righthreftext'] = "Войти";

            $this->CI->load->view('header', $data);
            $this->CI->load->view('authatata', $data);
            $this->CI->load->view('footer', $data);
        }
    }
}
