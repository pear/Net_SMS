<?php
require_once 'Net/SMS/generic_smtp.php';
require_once 'Mail.php';

class Net_SMS_generic_smtpTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $this->mock = $this->getMock('Mail');
    }

    public function testSend() {
        $driver = new Net_SMS_generic_smtp(array(), $this->mock);

        $this->mock->expects($this->never())
                    ->method('send');

        // With no to addresses, we should not send
        $driver->send(array(
            'id' => 0,
            'to' => array(), 
            'text' => 'hi'
        ));
    }

    public function testSendWithCarrier() {
        $driver = new Net_SMS_generic_smtp(array('mailHeaders' => array()), $this->mock);

        $this->mock->expects($this->once())
                    ->method('send')
                    ->with('0433311442@bob.com', array(), 'hi');

        $driver->addCarrier('bob.com', '%s@bob.com');

        $driver->send(array(
            'id' => 0,
            'to' => array("0433311442"), 
            'text' => 'hi',
            'carrier' => 'bob.com'
        ));
    }
}
