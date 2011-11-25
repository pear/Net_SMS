<?php
require_once 'Net/SMS/generic_smtp.php';
require_once 'Mail.php';
require_once 'PHPUnit/Framework/TestCase.php';

class Net_SMS_generic_smtpTest extends PHPUnit_Framework_TestCase {


    public function test() {
        $driver = new Net_SMS_generic_smtp(array(), new Mail());

        $driver->send(array(
            'id' => 0,
            'to' => array("hi"), 
            'text' => 'hi'
        ));
    }
}
