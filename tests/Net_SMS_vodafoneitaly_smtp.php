<?php
require_once 'Net/SMS/vodafoneitaly_smtp.php';
require_once 'PHPUnit/Framework/TestCase.php';

class Net_SMS_vodafoneitaly_smtpTest extends PHPUnit_Framework_TestCase {


    public function test() {
        $driver = new Net_SMS_vodafoneitaly_smtp();

        $driver->send(array(
            'id' => 0,
            'to' => array(), 
            'text' => 'hi'
        ));
    }
}
