<?php
require_once 'Net/SMS/sms2email_http.php';

class Net_SMS_sms2email_httpTest extends PHPUnit_Framework_TestCase {


    public function test() {
        $driver = new Net_SMS_sms2email_http(null, new HTTP_Request2());

        $driver->send(array(
            'id' => 0,
            'to' => array("hi"), 
            'text' => 'hi'
        ));
    }
}
