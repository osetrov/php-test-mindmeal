<?php
/**
 * User: Pavel Osetrov
 * Date: 19.07.14
 * Time: 20:21
 */
class Index_Controller extends Controller {

    public $is_login = FALSE;
    public $mtpl = 'main.tpl';
    public $template = 'page/user-list.tpl';
    public $header = "header.tpl";
    public $panel = "panel.tpl";

    public function before() {

        parent::before();

        require_once PATH_CLASSES.'base.user.php';
        require_once PATH_CLASSES.'user.active.php';
        require_once PATH_CLASSES.'user.view.php';

        $this->is_login = Auth::instance()->isLogin();
    }

    public function init($viewUser, $activeUser) {
        $this->mtpl->setGlobal(array(
            'is_login'					=> $this->is_login,
            'active_user_id'			=> $activeUser->info['user_id'],
            'active_user_login'	        => $activeUser->info['user_login'],
            'view_user_id'			    => $viewUser->info['user_id'],
            'view_user_login'	        => $viewUser->info['user_login']
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

    public function show($content) {
        echo $this->mtpl->parse(array(
            'content'		=> $content
        ));
    }

    public function action_index() {
        $activeUser = new ActiveUser(Auth::instance()->getUserId());
        $viewUser = $activeUser;
        $this->init($viewUser, $activeUser);

        $userList = User::getUserList();

        if (count($userList) == 0) {
            $this->template->block('NotUsers');
        } else {
            $this->template->block('/Users');
            $this->template->context('/Users/Item');
            $this->template->set($userList);
        }

        $this->show($this->template->parse());

    }

    public function after() {
        parent::after();
    }
}
