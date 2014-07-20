<?php
/**
 * User: Pavel Osetrov
 * Date: 19.07.14
 * Time: 18:58
 */

class Cache extends Memcache {
    const TIME_USERWALL = 10800;
    const TIME_USER_INFO = 10800;
    const TIME_USER_FRIENDS = 10830;
    const TIME_USER_AUDIO = 10830;

    static protected $_instance = array();
    static protected $_configs = NULL;

    /**
     * @param string $cache
     * @return Memcache
     */
    static public function instance($cache = 'default') {
        if (!isset(self::$_instance[$cache]) || self::$_instance[$cache] === NULL) {
            if (self::$_configs === NULL) {
                require PATH_CONFIGS.'memcache.php';
                /** @var $memcache Array из memcache.php */
                self::$_configs = $memcache;
            }
            try {
                self::$_instance[$cache] = new Memcache();
                self::$_instance[$cache]->addServer('localhost', 11211);
                self::$_instance[$cache]->connect('localhost', 11211);
            } catch (Exception $e) {
                die("Server connect error!");
            }
        }
        return self::$_instance[$cache];
    }

    public function setJ($key, $var, $flag = 0, $expire = 0) {
        return @$this->set($key, json_encode($var), $flag, $expire);
    }

    public function getJ($key, $flag = 0) {
        return @json_decode($this->get($key, $flag), true);
    }

}
