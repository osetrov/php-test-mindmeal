<?php
/**
 * User: Pavel Osetrov
 * Date: 20.07.14
 * Time: 1:32
 */

class User_Controller extends Controller {
    public $is_login = FALSE;
    public $register = 'user/register.tpl';
    public $login    = 'user/login.tpl';
    public $header = "header.tpl";

    public function before() {
        parent::before();
        $this->is_login = Auth::instance()->isLogin();

        require_once PATH_CLASSES.'base.user.php';
        require_once PATH_CLASSES.'user.active.php';
        require_once PATH_CLASSES.'user.view.php';
    }

    public function after() {
        parent::after();
    }

    public function show($content) {
        echo $this->mtpl->parse(array(
            'content'	=> $content,
        ));
    }

    public function init($viewUser, $activeUser) {
        $this->mtpl->setGlobal(array(
            'is_login'					=> $this->is_login,
            'active_user_id'			=> $activeUser->info['user_id'],
            'active_user_login'	        => $activeUser->info['user_login']
        ));
        if (!$this->is_login) {
            $this->panel->block('Not_Login');
        } else {
            $user = new ActiveUser(Auth::instance()->getUserId());
            $user_info = $user->getInfo($user->getUserId());
            $this->panel->context('/MyPanel');
            $this->panel->set($user_info);
        }

        $this->mtpl->set(array(
            'header' => $this->header->parse(),
            'panel'  => $this->panel->parse()
        ));
    }

    public function action_register() {
        $this->register = View::factory($this->register);
        if (!empty($_POST['userLogin']) && !empty($_POST['userPassword'])) {
            $login = $_POST['userLogin'];
            $password  = $_POST['userPassword'];

            $register_info = Auth::register($login, $password);

            if (count ($register_info['errors'])) {
                $errors = $register_info['errors'];
                foreach ($errors as &$item) {
                    $item = array('title' => $item);
                }
                $this->register->context('/Errors/Item');
                $this->register->set($errors);
                $this->show($this->register->parse());
            } else {
                Auth::login($login, $password);
                header('Location: /u'.$register_info['user_id']);
            }
        } else {
            $this->show($this->register->parse());
        }
    }

    public function action_login() {
        $this->login = View::factory($this->login);
        if (!empty($_POST['userLogin']) && !empty($_POST['userPassword'])) {
            $login = $_POST['userLogin'];
            $password  = $_POST['userPassword'];
            $login_info = Auth::login($login, $password);

            if (!$login_info) {
                $this->login->context('/Errors/Item');
                $this->login->set(array(0 => array('title' => 'Не правильный логин или пароль')));
                $this->show($this->login->parse());
            } else {
                header('Location: /');
            }
        } else {
            $this->show($this->login->parse());
        }
    }

    public function action_logout() {
        Auth::logout();
        header('Location: /');
    }
}