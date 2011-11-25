<?php
require_once 'Net/SMS/Factory.php';
require_once 'PHPUnit/Framework/TestCase.php';

class Net_SMSFactoryTest extends PHPUnit_Framework_TestCase {


    public function test() {
        Net_SMS_Factory::build('clickatell_http');
        Net_SMS_Factory::build('win_http');
    }

    public function testBuildGenericSMPP() {
        $driver = Net_SMS_Factory::build(
            'generic_smpp',
            array('host' => 'example.com', 'port' => 1234)
        );
    }

    public function testBuildGenericSMTP() {
        $driver = Net_SMS_Factory::build(
            'generic_smtp',
            array('mailBackend' => 'mail', 'mailParams' => array())
        );
    }


    public function testBuildVodafoneItalySMTP() {
        $driver = Net_SMS_Factory::build(
            'vodafoneitaly_smtp',
            array('mailBackend' => 'mail', 'mailParams' => array())
        );
    }
}
