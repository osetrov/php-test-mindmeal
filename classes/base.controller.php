<?php
/**
 * User: Pavel Osetrov
 * Date: 19.07.14
 * Time: 20:07
 */

class Controller {

    const HTML	= 0;
    const JSON	= 1;
    const XML	= 2;

    protected $_params	= array();
    protected $_ajax = FALSE;
    protected $_response = 0;
    protected $_request = NULL;
    protected $_lang = 'ru_RU';

    public $mtpl = 'main.tpl';
    public $template = NULL;
    public $header = '';
    public $panel = '';

    public function __construct($param = array(), $request = '') {
        $this->_params = $param;
        $this->_request = $request;
    }

    public function before() {
        $this->_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' : false;
        if (isset($_REQUEST['format'])) {
            if (strtolower($_REQUEST['format']) == 'json') {
                $this->_response = Controller::JSON;
            } else if (strtolower($_REQUEST['format']) == 'xml') {
                $this->_response = Controller::XML;
            } else {
                $this->_response = Controller::HTML;
            }
        } else if (isset($_REQUEST['callback'])) {
            $this->_response = Controller::JSON;
        } else {
            $this->_response = Controller::HTML;
        }

        require_once PATH_CLASSES.'view.php';
        View::init($this->_lang);

        if ($this->mtpl) {
            $this->mtpl = View::factory($this->mtpl);
        }
        if ($this->template) {
            $this->template = View::factory($this->template);
        }
        if ($this->header) {
            $this->header = View::factory($this->header);
        }
        if ($this->panel) {
            $this->panel = View::factory($this->panel);
        }
    }

    public function after() {

    }
}