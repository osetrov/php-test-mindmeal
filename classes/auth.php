<?php
/**
 * User: Pavel Osetrov
 * Date: 19.07.14
 * Time: 20:14
 */

class Auth {
    static protected $_instance = NULL;

    protected $is_login = NULL;

    static public function instance() {

        if (self::$_instance === NULL) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct($update = false) {
        session_start();
        if (isset($_SESSION['id']) and isset($_SESSION['hash'])) {

            $sth = DB::instance()->prepare("SELECT *
					FROM `users`
					WHERE `user_id` = :user_id
					LIMIT 1;"
            );
            $sth->execute(array(
                'user_id' => (int)$_SESSION['id']
            ));
            $userData = $sth->fetch(PDO::FETCH_ASSOC);

            if  (($userData['user_hash'] !== $_SESSION['hash']) or ($userData['user_id'] !== $_SESSION['id'])) {

                unset($_SESSION['id']);
                unset($_SESSION['hash']);

            } else {
                $this->is_login = true;
            }
        }
    }

    /**
     * Выход пользователя
     */
    static public function logout() {
        unset($_SESSION['id']);
        unset($_SESSION['hash']);
    }

    /**
     * Вход пользователя
     * @param $login string Логин
     * @param $password string Пароль
     * @return Array с ошибками или ID нового пользователя
     */
    static public function login($login, $password) {
        $sth = DB::instance()->prepare("SELECT `user_id`, `user_password` FROM `users` WHERE `user_login` = :login LIMIT 1");
        $sth->execute(array(
            'login' => $login
        ));

        $user = $sth->fetch();

        if ($user['user_password'] === md5(md5($password))) {
            $hash = md5(generateCode(10));

            $sth = DB::instance()->prepare("UPDATE `users` SET `user_hash` = :hash WHERE `user_id` = :user_id");

            DB::instance()->beginTransaction();

            $sth->execute(array (
                    'hash'		 => $hash,
                    'user_id'	 => $user['user_id']
                )
            );

            $_SESSION['id'] = $user['user_id'];
            $_SESSION['hash'] = $hash;


            return DB::instance()->commit();
        }
        return false;
    }

    /**
     * Регистрация
     * @param $login string Логин
     * @param $password string Пароль
     * @return bool
     */
    static public function register($login, $password) {
        $err = array();
        $user_id = null;

        if(!preg_match("/^[a-zA-Z0-9]+$/", $login)) {
            $err[] = "Логин может состоять только из букв английского алфавита и цифр";
        }

        if(strlen($login) < 3 or strlen($login) > 30) {
            $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
        }

        if(strlen($password) < 3 or strlen($password) > 30) {
            $err[] = "Пароль должен быть не меньше 3-х символов и не больше 30";
        }

        $sth = DB::instance()->prepare("SELECT `user_id` FROM `users` WHERE `user_login` = :login");
        $sth->execute(array(
            'login' => $login
        ));
        $count = $sth->rowCount();

        if ($count > 0) {
            $err[] = "Пользователь с таким логином уже существует в базе данных";
        } else if (count($err) === 0) {
            $password = md5(md5(trim($password)));

            $sth = DB::instance()->prepare("
                INSERT INTO `users`
                (`user_login`, `user_password`)
                VALUE (:user_login, :user_password);
                ");
            $complete = $sth->execute(array(
                'user_login'    => $login,
                'user_password' => $password
            ));
            if ($complete) {
                $user_id = DB::instance()->lastInsertId();
            } else {
                $err[] = "Ошибка БД";
            }
        }

        return array(
            'errors'  => $err,
            'user_id' => $user_id
        );
    }

    public function isLogin() {
        return $this->is_login;
    }

    public function getUserId() {
        return ((isset($_SESSION['id']) && $_SESSION['id'] > 0) ? $_SESSION['id'] : null);
    }
}