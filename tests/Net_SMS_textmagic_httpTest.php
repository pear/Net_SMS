<?php
require_once 'Net/SMS/textmagic_http.php';

class Net_SMS_textmagic_httpTest extends PHPUnit_Framework_TestCase {


    public function test() {
        $request = new HTTP_Request2();

        // For tests only, avoid having to install various certificates
        $request->setConfig(array('ssl_verify_peer' => false));
        
        $driver = new Net_SMS_textmagic_http(null, $request);

        $driver->send(array(
            'id' => 0,
            'to' => array("hi"), 
            'text' => 'hi'
        ));
    }
}
