<?php
require_once 'Net/SMS/generic_smpp.php';
require_once 'PHPUnit/Framework/TestCase.php';

class Net_SMS_generic_smppTest extends PHPUnit_Framework_TestCase {


    public function test() {
        $driver = new Net_SMS_generic_smpp();

        $driver->send(array(
            'id' => 0,
            'to' => array(), 
            'text' => 'hi'
        ));
    }
}
