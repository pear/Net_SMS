<?php
require_once 'Net/SMS.php';
require_once 'PHPUnit/Autoload.php';

class Net_SMSTest extends PHPUnit_Framework_TestCase {


    public function test() {
        $driver = new Net_SMS(array());

        try {
            $driver->send(null);
            $this->fail("null is not a valid array()");
        } catch (InvalidArgumentException $iae) {
        }

        try {
            $driver->send(array());
            $this->fail("An array is not a valid hash containing id, text, to");
        } catch (InvalidArgumentException $iae) {
        }

        try {
            $driver->send(array('id' => 0));
            $this->fail("An array is not a valid hash containing id, text, to");
        } catch (InvalidArgumentException $iae) {
        }

        try {
            $driver->send(array('id' => 0, 'to' => array()));
            $this->fail("An array is not a valid hash containing id, text, to");
        } catch (InvalidArgumentException $iae) {
        }


        $driver->send(array('id' => 0, 'to' => array(), 'text' => ''));
    }
}
