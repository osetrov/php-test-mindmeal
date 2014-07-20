<?php
/**
 * User: Pavel Osetrov
 * Date: 19.07.14
 * Time: 23:05
 */

class ActiveUser extends User {
    public $info = NULL;

    public function __construct($userId) {
        parent::__construct($userId, $userId);

        $this->info = User::getInfo($userId);
    }
}