<?php
/**
 * User: Pavel Osetrov
 * Date: 20.07.14
 * Time: 15:33
 */

class Poster_List {

    protected $mysql;
    protected $memcache;

    protected $owner;
    protected $owner_id;
    protected $post_id;

    /**
     * @param User $owner Владелец постера
     */
    public function __construct(User $owner) {
        $this->owner = $owner;
        $this->owner_id = $owner->getUserId();

        $this->mysql = DB::instance();
        $this->memcache = Cache::instance();
    }

    /**
     * TODO добавить в мемкеш
     * Добавить сообщение
     * @param $user_id int Id пользователя, отправляющего сообщения
     * @param $message string Текст сообщения
     * @param null|int $time  Время сообщения в unixtime, необязательный параметр
     * @param null|int $post_id Id сообщения, необязательный параметр
     * @return bool|int ID сообщения в случае удачи, false если ошибка
     */
    public function add($user_id, $message, $time = null, $post_id = null) {

        $post_id = ($post_id == null) ? time() : (int)$post_id;
        $time    = ($time == null) ? time() : $time;

        $sth = $this->mysql->prepare("
			INSERT INTO `poster_list` (
				`owner_id`, `id`, `create`, `message`, `post_owner_id`
			) VALUES (
				:owner_id, :post_id, :create, :message, :post_owner_id
			);
		");
        $sth->execute(array(
            'owner_id'			 => $this->owner_id,
            'post_id'			 => $post_id,
            'create'		     => (int)$time,
            'message'		     => $message,
            'post_owner_id'	     => (int)$user_id
        ));
        if ($sth->rowCount()) {
            $this->post_id = $post_id;
        } else {
            $this->post_id = false;
        }
        return $this->post_id;
    }


    /**
     * TODO добавить мемкеш
     * Получить список всех твиттов
     * @return array Массив твиттов
     */
    public function getList() {
        $sth = DB::instance()->prepare("SELECT * FROM `poster_list` WHERE `owner_id` = :owner_id ORDER BY `create` DESC ");
        $sth->execute(array(
            'owner_id' => (int)$this->owner_id
        ));

        return $sth->fetchAll();
    }

    /**
     * TODO обновить мемкеш
     * Удаление твитта
     * @param $message_id int ID твитта
     * @return bool
     */
    public function deleteMessage($message_id) {
        $sth = $this->mysql->prepare("DELETE FROM `poster_list`
			WHERE `owner_id` = :owner_id AND `id`  = :post_id
			LIMIT 1;");
        $sth->execute(array(
            'owner_id'		 => (int)$this->owner_id,
            'post_id'	     => (int)$message_id
        ));
        if ($sth->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

}