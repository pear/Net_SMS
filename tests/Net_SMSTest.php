<?php
require_once 'Net/SMS.php';
require_once 'PHPUnit/Framework/TestCase.php';

class Net_SMSTest extends PHPUnit_Framework_TestCase {


    public function test() {
        $driver = new Net_SMS();

        $driver->send(null);
    }
}
