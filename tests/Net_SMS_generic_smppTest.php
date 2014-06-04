<?php
require_once 'Net/SMS/generic_smpp.php';
require_once 'PHPUnit/Autoload.php';

class Net_SMS_generic_smppTest extends PHPUnit_Framework_TestCase {


    public function test() {
        $driver = new Net_SMS_generic_smpp(null, new Net_SMPP_Client(null, null));

        $driver->send(array(
            'id' => 0,
            'to' => array("hi"), 
            'text' => 'hi'
        ));
    }
}
