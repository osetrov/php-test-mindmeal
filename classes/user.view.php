<?php
/**
 * User: Pavel Osetrov
 * Date: 19.07.14
 * Time: 23:23
 */

class ViewUser extends User {
    public $info = NULL;

    /**
     * @param $userId int ID пользователя
     * @param $activeUser ActiveUser
     */
    public function __construct($userId, $activeUser) {
        parent::__construct($userId, $activeUser->getUserId());
        $this->info = User::getInfo($userId);
    }

    /**
     * Проверка на существование пользователя
     * @return bool
     */
    public function isExist() {
        $buffer = User::getInfo($this->_user_id);
        if ( !empty($buffer) ) {
            return true;
        } else {
            return false;
        }
    }
}