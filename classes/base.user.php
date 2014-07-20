<?php
/**
 * User: Pavel Osetrov
 * Date: 19.07.14
 * Time: 23:02
 */

class User {

    protected $_user_id = NULL;
    protected $_active_user_id = NULL;

    public function __construct($userId, $activeUserId) {
        $this->_user_id = (int)$userId;
        $this->_active_user_id = (int)$activeUserId;
    }

    /**
     * Получить информацию о пользователе
     * @param $userId int|array ID пользователя или массив с ID
     * @param bool $update Обновить кеш
     * @return array
     */
    static public function getInfo($userId, $update = false) {
        $response = array();
        $multi = false;
        if (is_array($userId)) {
            $keys = array();
            $multi = true;
            foreach ($userId as &$current) {
                $keys[] = 'u'.$current;
            }
        } else {
            $keys = 'u'.$userId;
        }
        if ($update) {
            $buffer = FALSE;
        } else {
            $buffer = Cache::instance()->get($keys);
        }
        if ($multi) {
            $none = array();
            if ( ! empty($buffer)) {
                foreach ($buffer as &$current) {
                    $response[$current['id']] = $current;
                    $none[] = $current['id'];
                }
            }
            $userId = array_diff($userId, $none);
            if (!empty($userId)) {
                foreach ($userId as $current) {
                    $current = DB::instance()->prepare("SELECT `user_id`, `user_name`
						FROM `users`
						WHERE `user_id` IN(".implode(',',$current).")
						LIMIT ".count($current).";");
                    $current->execute();
                    $buffer = $current->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($buffer)) {
                        foreach ($buffer as &$info) {
                            $response[$info['id']] = $info;
                            Cache::instance()->set('u'.$info['id'], $info, false, Cache::TIME_USER_INFO);
                        }
                    }
                }
            }
        } else {
            if ( !empty($buffer)) {
                $response = $buffer;
            } else {
                $user_info = DB::instance()->prepare("SELECT *
					FROM `users`
					WHERE `user_id` = :user_id
					LIMIT 1"
                );

                $user_info->execute(array(
                    'user_id' => (int)$userId
                ));
                $response = $user_info->fetch();
                if (! empty($response)) {
                    Cache::instance()->set('u'.$userId, $response, false, Cache::TIME_USER_INFO);
                }
            }
        }
        return $response;
    }

    /**
     * Получить ID пользователя
     * @return int|null ID пользователя
     */
    public function getUserId() {
        return $this->_user_id;
    }

    /**
     * Получить список пользователей
     * @return array список пользователей
     */
    static public function getUserList() {
        $user_list = DB::instance()->prepare("SELECT * FROM `users`");
        $user_list->execute();
        return $user_list->fetchAll();
    }


}