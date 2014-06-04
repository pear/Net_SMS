<?php
require_once 'Net/SMS/clickatell_http.php';
require_once 'PHPUnit/Autoload.php';

class Net_SMS_clickatell_httpTest extends PHPUnit_Framework_TestCase {


    public function test() {
        $request = new HTTP_Request2();
        $driver = new Net_SMS_clickatell_http(null, $request);

        $driver->send(array(
            'id' => 0,
            'to' => array(), 
            'text' => 'hi'
        ));
    }
}
