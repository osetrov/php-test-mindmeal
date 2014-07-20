<?php
/**
 * User: Pavel Osetrov
 * Date: 19.07.14
 * Time: 20:10
 */

class DB {

    static protected $_instance = array();
    static protected $_configs = NULL;

    /**
     *
     * @param string $db
     * @return PDO
     */
    static public function instance($db = 'default') {
        if (!isset(self::$_instance[$db]) || self::$_instance[$db] === NULL) {
            if (self::$_configs === NULL) {
                require PATH_CONFIGS.'db.php';
                /** @var $database Array из db.php */
                self::$_configs = $database;
            }
            $dsn = 'mysql:host='.self::$_configs[$db]['host'].';dbname='.self::$_configs[$db]['database'];
            try {
                self::$_instance[$db] = new PDO($dsn, self::$_configs[$db]['user'], self::$_configs[$db]['password']);
            } catch (Exception $e) {
                die("BD connect error!: " . $e->getMessage() . "<br/>");
            }
            self::$_instance[$db]->query("set names ".self::$_configs[$db]['charset']);
        }
        return self::$_instance[$db];
    }


    static public function tableExist($table_name, $db='default') {
        $sth = self::instance($db)->query("SHOW TABLES LIKE  '".mysql_escape_string($table_name)."'");
        $info = $sth->fetch(PDO::FETCH_NUM);
        if ($info) {
            if ($info[0] == $table_name) {
                return true;
            }
        }
        return false;
    }

    static public function table_exist($table_name, $db = "default") {
        $mysql = self::instance();
        $sth = $mysql->query("
					SELECT `TABLE_NAME`
					FROM `information_schema`.`TABLES`
					WHERE `TABLE_SCHEMA` LIKE '".mysql_escape_string(self::$_configs[$db]['database'])."'
						AND `TABLE_NAME` LIKE '".mysql_escape_string($table_name)."'
					LIMIT 1;");
        $sth->execute();
        if ($sth->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

}
