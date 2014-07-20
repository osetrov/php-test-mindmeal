<?php
/**
 * User: Pavel Osetrov
 * Date: 20.07.14
 * Time: 5:45
 */

class Poster_Controller extends Controller {
    public $is_login = FALSE;
    public $register = 'user/register.tpl';
    public $login    = 'user/login.tpl';
    public $header = "header.tpl";
    public $panel = "panel.tpl";
    public $template = "user/poster.tpl";

    public function before() {
        parent::before();
        $this->is_login = Auth::instance()->isLogin();

        require_once PATH_CLASSES.'base.user.php';
        require_once PATH_CLASSES.'user.active.php';
        require_once PATH_CLASSES.'user.view.php';
        require_once PATH_CLASSES.'poster/list.php';
    }

    public function after() {
        parent::after();
    }

    public function show($content) {
        echo $this->mtpl->parse(array(
            'content'	=> $content,
        ));
    }

    public function init(User $viewUser, User $activeUser) {
        $this->mtpl->setGlobal(array(
            'is_login'					=> $this->is_login
        ));
        if (!$this->is_login) {
            $this->panel->block('Not_Login');

            $user_info = $viewUser->getInfo($viewUser->getUserId());

            $this->panel->context('/NotMyPanel');
            $this->panel->set($user_info);

        } else if ($viewUser->getUserId() == $activeUser->getUserId()) {
            $user = new ActiveUser(Auth::instance()->getUserId());
            $user_info = $user->getInfo($user->getUserId());
            $this->panel->context('/MyPanel');
            $this->panel->set($user_info);
        } else {
            $user_info = $viewUser->getInfo($viewUser->getUserId());

            $this->panel->context('/NotMyPanel');
            $this->panel->set($user_info);
        }

        $this->mtpl->set(array(
            'header' => $this->header->parse(),
            'panel'  => $this->panel->parse(),
            'js'     => '<script type="text/javascript" src="/js/poster.js"></script>',
            'active_user_id'			=> $activeUser->info['user_id'],
            'active_user_login'	        => $activeUser->info['user_login'],
            'view_user_id'			    => $viewUser->info['user_id'],
            'view_user_login'	        => $viewUser->info['user_login']
        ));
    }

   public function action_index() {
       $activeUser = new ActiveUser(Auth::instance()->getUserId());
       $viewUser = new ViewUser($this->_params['id'], $activeUser);
       $this->init($viewUser, $activeUser);

       $poster = new Poster_list($viewUser);
       $posterList = $poster->getList();

       if (!Auth::instance()->isLogin()) {
           $this->template->block('/NotAuth');
       } else {
           $this->template->context('/SendMessage');
           $this->template->set(array(
               'view_user_id'			=> $viewUser->info['user_id']
           ));
       }

       $this->template->context('/Poster/Item');

       foreach ($posterList as &$post) {
           $post_owner_info = User::getInfo($post['post_owner_id']);
           $post['post_owner_login'] = $post_owner_info['user_login'];
           if ( $post['owner_id'] == $activeUser->getUserId() || $post['post_owner_id'] == $activeUser->getUserId() ) {
               $post['mypost'] = true;
           }
       }

       $this->template->set($posterList);

       $this->show($this->template->parse());
   }

   public function action_addpost() {
       if (isset($_POST['message']) && strlen($_POST['message']) > 0) {

           $activeUser = new ActiveUser(Auth::instance()->getUserId());
           $viewUser = new ViewUser($this->_params['id'], $activeUser);

           $posterList = new Poster_List($viewUser);
           $postID = $posterList->add($activeUser->getUserId(), $_POST['message']);
            if ($postID > 0) {
                echo $postID;
            } else {
                echo 'Ошибка отправки сообщения';
            }
       } else {
           echo 'Нельзя отправлять пустое сообщение';
       }
   }

    public function action_post_del() {
        if (isset($_POST['ID'])) {
            $activeUser = new ActiveUser(Auth::instance()->getUserId());
            $viewUser = new ViewUser($this->_params['id'], $activeUser);

            $posterList = new Poster_List($viewUser);

            if ($posterList->deleteMessage($_POST['ID'])) {
                echo 'Ok';
            } else {
                echo 'Ошибка удаления';
            }
        } else {
            echo 'Нет ID сообщения';
        }
    }
}