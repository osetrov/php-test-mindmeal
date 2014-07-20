<?php
/**
 * User: Pavel Osetrov
 * Date: 19.07.14
 * Time: 18:56
 */

/**
 * Генерация случайной строки
 * @param int $length длина строки
 * @return string случайная строка
 */
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
        $code .= $chars[mt_rand(0,$clen)];
    }

    return $code;
}

function moduloShot($num, $mod = 20000, $glue = '_') {
    $result = (int)($num/$mod);
    if ($result < $mod) {
        return (string)$result;
    } else {
        return ((int)($result/$mod)).$glue.($result%$mod);
    }
}