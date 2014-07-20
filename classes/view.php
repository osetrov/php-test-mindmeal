<?php
/**
 * User: Pavel Osetrov
 * Date: 19.07.14
 * Time: 21:20
 */

class View extends Blitz{

    static $path = '';
    static $use_lang = '';


    public function __construct($t) {
        parent::__construct($t);
    }


    static public function init($lang) {
        self::$path = PATH_VIEWS.$lang.DIRECTORY_SEPARATOR;
        self::$use_lang = $lang;
    }

    /**
     *
     * @param string $tpl
     * @return Blitz
     */
    static public function factory($tpl, $usepath = TRUE) {
        if ($usepath) {
            // return new self(self::$path.$tpl);
            return new self(self::$path.$tpl);
        } else {
            return new self($tpl);
        }
    }


    public function to_str_val($data, $separator) {
        if (is_array($data) && !empty($data)) {
            return implode($separator, $data);
        }
        return '';
    }


    public function to_str_key($data, $separator) {
        if (is_array($data) && !empty($data)) {
            return implode($separator, array_keys($data));
        }
        return '';
    }


    public function nl2br($string) {
        return nl2br($string);
    }


    public function to_str_date($time, $format = 'd.m.Y H:i') {
        if ($time) {
            return date($format, $time);
        } else {
            return '';
        }
    }

    public function post_to_str_date($time, $format = 'd.m.Y H:i') {
        $time /= 1000;
        if ($time) {
            return date($format, $time);
        } else {
            return '';
        }
    }

    public function get_str_time($time) {
        $time = (int)$time;
        $seconds = $time%60;
        $minutes = (int)($time/60)%60;
        $hours = (int)($time/3600);
        $response = ($hours ? $hours.':' : '');
        if (!(bool)(int)($minutes/10)) {
            $response .= '0'.$minutes.':';
        } else {
            $response .= $minutes.':';
        }
        if (!(bool)(int)($seconds/10)) {
            $response .= '0'.$seconds;
        } else {
            $response .= $seconds;
        }
        return $response;
    }





    public function tag_to_bb($text) {
        $search = array(
            '/<strong>(.*?)<\/strong>/i',
            '/<em>(.*?)<\/em>/i',
            '/<span class="underline">(.*?)<\/span>/i',
            '/<span class="line-throught">(.*?)<\/span>/i',
            '/<a href="\/redirect\?to=(.*?)">(.*?)<\/a>/i',
        );
        $replace = array(
            '<b>$1</b>',
            '<i>$1</i>',
            '<u>$1</u>',
            '<s>$1</s>',
            '<a href="$1">$2</a>',
        );
        $text = preg_replace($search, $replace, $text);
        return ($text);
    }

    public function tag_to_bb_quotes($text) {
// var_dump($text, $this->tag_to_bb($text), urlencode($this->tag_to_bb($text)));
        return htmlspecialchars($this->tag_to_bb($text));
    }




    public function escaping_quotes($str, $ssn = false) {
        if ($ssn) {
            // return str_replace(array("\\", "\r\n", "\r", "\n", "\r", "\"", "'"), array('', "", "", "", '"', ''), $str);
            // return str_replace(array("\\", "\r\n", "\r", "\n", "\r", "\"", "'"), array('', "", "", "", '"', ''), addslashes(htmlspecialchars($str)));
            // return preg_replace('![^\w\d\s]*!','',$str);
            return str_replace(array("\\", "\r\n", "\r", "\n", "\r", "\"", "'"), array('', "", "", "", '"', ''), iconv("UTF-8", "UTF-8//IGNORE", $str));
        } else {
            return str_replace(array("\"", "'"), array('\\"', '\\\''), $str);
        }
    }




    public function tag_to_links($str, $link = '/search/all?tag=', $escaping_quotes = false) {
        $temp = explode(',', $str);
        if (!empty($temp)) {
            foreach ($temp as &$current) {
                $current = trim($current);
                $current = '<a href="'.$link.urlencode($current).'">'.$current.'</a>';
            }
            $str = implode(', ',$temp);
        }
        if ($escaping_quotes) {
            $str = str_replace(array("\\", "\r\n", "\r", "\n", "\r", "\"", "'"), array('', "", "", "", '"', ''), $str);
        }
        return $str;
    }


    public function mif($if, $then, $then_func = false, $else = false, $else_func = false) {
        if ($if) {
            return is_callable(array($this, $then_func)) ? $this->$then_func($then) : '';
        } else {
            return is_callable(array($this, $else_func)) ? $this->$else_func($else) : '';
        }
        /* switch ($lang) {
            case 'ru_RU':
            default: */
        if ($num == 11 || $num == 12 || $num == 13 || $num == 14) {
            $response = isset($word_ends[2]) ? $word_ends[2] : '';
        } else {
            $rest = $num % 10;
            if ($rest == 1) {
                $response = isset($word_ends[0]) ? $word_ends[0] : '';
            } else if ($rest == 2 || $rest == 3 || $rest == 4) {
                $response = isset($word_ends[1]) ? $word_ends[1] : '';
            } else {
                $response = isset($word_ends[2]) ? $word_ends[2] : '';
            }
        }
        /* } */
        return $response;
    }

    public function if_eq($operand_1, $operand_2, $then, $else = false) {
        if ($operand_1 == $operand_2) {
            return $then;
        } else {
            return $else;
        }
    }


    public function getEndWord($num, $lang = 'ru_RU') {
        $response = '';
        if (func_num_args() > 2) {
            foreach (func_get_args() as $current) {
                $word_ends[] = $current;
            }
            array_shift($word_ends);
            array_shift($word_ends);
        }
        /* switch ($lang) {
            case 'ru_RU':
            default: */
        if ($num == 11 || $num == 12 || $num == 13 || $num == 14) {
            $response = isset($word_ends[2]) ? $word_ends[2] : '';
        } else {
            $rest = $num % 10;
            if ($rest == 1) {
                $response = isset($word_ends[0]) ? $word_ends[0] : '';
            } else if ($rest == 2 || $rest == 3 || $rest == 4) {
                $response = isset($word_ends[1]) ? $word_ends[1] : '';
            } else {
                $response = isset($word_ends[2]) ? $word_ends[2] : '';
            }
        }
        /* } */
        return $response;
    }


    public function ssn($text) {
        $text = explode("\n", $text);
        $text = array_map('trim', $text);
        foreach ($text as &$current) {
            $current = str_replace(array("\\", "\"", "'"), array("\\\\", "\\\"", "\\'"), $current);
        }
        $text = implode("\\n\\\n", $text);
        return $text;
    }


    function pruning_preview($text, $max_lenght = 95) {
        $clear_text = strip_tags($text);
        if ($max_lenght && mb_strlen($clear_text, 'UTF-8') > $max_lenght) {
            $length = mb_strripos(mb_substr($clear_text, 0, $max_lenght, 'UTF-8'), ' ', 0, 'UTF-8');
            if ($length == 0) {
                $flag = false;
                $length = $max_lenght;
            } else {
                $flag = true;
            }
            $clear_text = mb_substr($clear_text, 0, $length, 'UTF-8').($flag ? ' ' : '').'...';
        }
        return $clear_text;
    }


    public function prepare_description($text, $max_lenght = 450) {
        if ($max_lenght && mb_strlen($text, 'UTF-8') > $max_lenght) {
            $length = mb_strripos(mb_substr($text, 0, $max_lenght, 'UTF-8'), ' ', 0, 'UTF-8');
            $short_text = mb_substr($text, 0, $length, 'UTF-8');
            return '
<div class="shot-text-container break-word" style="float: left">'.nl2br($short_text).' ...</div>
<div class="clear"></div>
<span class="show-full-text as-link small" onclick="(function(event){var t=$(event.target != undefined ? event.target : event.srcElement);var n=t.next();t.add(t.prev().prev()).remove();n.show();})(event)">Показать полностью</span>
<div class="full-text-container break-word" style="display:none;">'.nl2br($text).'</div>
				';
        } else {
            return '<div class="full-text-container break-word">'.nl2br($text).'</div>';
        }
    }

    public function js_escape($str) {
        return str_replace(array("\r", "\n", "'", "\""), array("", "\\n", "", ""), $str);
    }

    public function truncate_str($str, $len = 22) {
        if (mb_strlen($str) > $len) {
            return mb_substr($str, 0, $len, "UTF-8")."...";
        } else {
            return $str;
        }
    }

}