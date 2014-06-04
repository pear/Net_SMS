<?php
require_once 'Net/SMS/win_http.php';

class Net_SMS_win_httpTest extends PHPUnit_Framework_TestCase {


    public function test() {
        $driver = new Net_SMS_win_http();

        $driver->send(array(
            'id' => 0,
            'to' => array("hi"), 
            'text' => 'hi'
        ));
    }
}
