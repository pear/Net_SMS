<?php
class Net_SMS_Factory {

    /**
     * Attempts to return a concrete Gateway instance based on $driver.
     *
     * @param string $driver  The type of concrete Gateway subclass to return.
     *                        This is based on the gateway driver ($driver).
     *                        The code is dynamically included.
     * @param array $params   A hash containing any additional configuration or
     *                        connection parameters a subclass might need.
     *
     * @return Net_SMS  The newly created concrete Gateway instance or false on
     *                  an error.
     */
    public static function build($driver, $params = array())
    {
        include_once 'Net/SMS/' . $driver . '.php';
        $class = 'Net_SMS_' . $driver;
        if (class_exists($class)) {
            $sms = new $class($params);
        } else {
            $sms = PEAR::raiseError(sprintf(_("Class definition of %s not found."), $driver));
        }

        return $sms;
    }
}