<?php
require_once 'Net/SMS/generic_smtp.php';
require_once 'PHPUnit/Framework/TestCase.php';

class Net_SMS_generic_smtpTest extends PHPUnit_Framework_TestCase {


    public function test() {
        $driver = new Net_SMS_generic_smtp();

        $driver->send(null);
    }
}
