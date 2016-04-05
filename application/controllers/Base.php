<?php
class Base extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        /* Загрузим модель таблицы с пользователями */
        $this->load->model('Users_model');

        /* Загрузим библиотеку сессий */
        $this->load->library('session');

        /*$this->load->helper('url_helper');*/
    }

    /* Функция авторизации пользователя */
    public function auth()
    {
        if(!isset($_SESSTION['login'])) {

            $data['title'] = 'Авторизация';
            $this->load->helper('form');

            /*$this->load->library('form_validation');

            $this->form_validation->set_rules('title', 'Title', 'required');
            $this->form_validation->set_rules('text', 'Text', 'required');*/

            $this->load->view('auth_view', $data);

        } else {
            $data['title'] = '';
            $login = $_SESSTION['login'];
            $data['user'] = $this->Users_model->get_users($login);
        }
    }

    public function auth_test()
    {

        $data = array(
            'login' => $this->input->post('login'),
            'passfraze' => $this->input->post('passfraze')
        );

    }
}
