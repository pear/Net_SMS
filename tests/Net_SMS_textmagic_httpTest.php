<?php
require_once 'Net/SMS/textmagic_http.php';
require_once 'PHPUnit/Framework/TestCase.php';

class Net_SMS_textmagic_httpTest extends PHPUnit_Framework_TestCase {


    public function test() {
        $driver = new Net_SMS_textmagic_http();

        $driver->send(null);
    }
}
