<?php
namespace App;
class Response
{

    /*
     * Some fields may declared dynamically
     */

    public $error_code = 0;
    public $error_msg = null;
    public $status;

    /**
     * Display response
     */
    public function display()
    {
        if (!$this->error_msg) {
            unset($this->error_code);
            unset($this->error_msg);
            unset($this->status);
        }
        return $this;
    }
}