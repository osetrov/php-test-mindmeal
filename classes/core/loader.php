<?php
/**
 * User: Pavel Osetrov
 * Date: 19.07.14
 * Time: 19:10
 */

class Loader {

    public static function load($className) {
        if (class_exists($className, false)) {
            return true;
        }
        $file = PATH_CLASSES.str_replace(array( '_', '\\' ), DIRECTORY_SEPARATOR, strtolower($className)).'.php';
        if (file_exists($file)) {
            include $file;
            if (class_exists($className, false)) {
                return true;
            }
        }
        return false;
    }

}