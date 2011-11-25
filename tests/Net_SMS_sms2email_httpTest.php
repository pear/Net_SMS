<?php
require_once 'Net/SMS/sms2email_http.php';
require_once 'PHPUnit/Framework/TestCase.php';

class Net_SMS_sms2email_httpTest extends PHPUnit_Framework_TestCase {


    public function test() {
        $driver = new Net_SMS_sms2email_http();

        $driver->send(array(
            'id' => 0,
            'to' => array(), 
            'text' => 'hi'
        ));
    }
}
