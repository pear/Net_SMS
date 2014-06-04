<?php
require_once 'Net/SMS/Exception.php';

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
            switch ($class) {
                case 'Net_SMS_textmagic_http':
                    if (!extension_loaded('json')) {
                        throw new InvalidArgumentException("JSON extenstion isn't loaded!");
                    }

                    $request = new HTTP_Request2();
                    $request->setMethod('POST');
                    $request->setConfig('timeout', 5);
                    $request->setConfig('follow_redirects', true);
                    return new Net_SMS_textmagic_http($params, $request);

                case 'Net_SMS_clickatell_http':
                    $request = new HTTP_Request2();
                    $request->setMethod('POST');
                    $request->setConfig('timeout', 5);
                    $request->setConfig('follow_redirects', true);
                    return new Net_SMS_clickatell_http($params, $request);

                case 'Net_SMS_textmagic_http':
                    $request = new HTTP_Request2();
                    $request->setMethod('POST');
                    $request->setConfig('timeout', 5);
                    $request->setConfig('follow_redirects', true);
                    return new Net_SMS_textmagic_http($params, $request);

                case 'Net_SMS_sms2email_http':
                    $request = new HTTP_Request2();
                    $request->setMethod('POST');
                    $request->setConfig('timeout', 5);
                    $request->setConfig('follow_redirects', true);
                    return new Net_SMS_textmagic_http($params, $request);

                case 'Net_SMS_generic_smtp':
                   if (!isset($params['mailBackend']) || !isset($params['mailParams'])) {
                        throw new InvalidArgumentException("You must specify a mailBackend, mailHeaders and mailParams as sperate parameters. mailHeaders may be an empty array.");
                    }

                    require_once 'Mail.php';
                    
                    $m = new Mail();

                    return new Net_SMS_generic_smtp($params, $m->factory(
                        $params['mailBackend'],
                        $params['mailParams']
                    ));

                case 'Net_SMS_vodafoneitaly_smtp':
                   if (!isset($params['mailBackend']) || !isset($params['mailParams'])) {
                        throw new InvalidArgumentException("You must specify a mailBackend and mailParams as sperate parameters");
                    }
                    require_once 'Mail.php';
                    
                    $m = new Mail();

                    return new Net_SMS_vodafoneitaly_smtp($params, $m->factory(
                        $params['mailBackend'],
                        $params['mailParams']
                    ));

                case 'Net_SMS_generic_smpp':
                    if (!isset($params['host']) || !isset($params['port'])) {
                        throw new InvalidArgumentException("You must specify a host and port as sperate parameters");
                    }
                    $client = new Net_SMPP_Client($params['host'], $params['port']);

                    if (isset($params['vendor'])) {
                        /** @todo Assess if this is actually beneficial */
                        Net_SMPP::setVendor($params['vendor']);
                    }

                    return new Net_SMS_generic_smpp($params, $client);

                default:
                    return new $class($params);
            }
        }

        throw new Net_SMS_Exception(sprintf(_("Class definition of %s not found."), $driver));
    }

    /**
     * Returns information on a gateway, such as name and a brief description,
     * from the driver subclass getInfo() function.
     *
     * @return array  An array of extra information.
     */
    public static function getGatewayInfo($gateway)
    {
        static $info = array();
        if (isset($info[$gateway])) {
            return $info[$gateway];
        }

        require_once 'Net/SMS/' . $gateway . '.php';
        $class = 'Net_SMS_' . $gateway;
        $info[$gateway] = call_user_func(array($class, 'getInfo'));

        return $info[$gateway];
    }

    /**
     * Returns parameters for a gateway from the driver subclass getParams()
     * function.
     *
     * @param string  The name of the gateway driver for which to return the
     *                parameters.
     *
     * @return array  An array of extra information.
     */
    public static function getGatewayParams($gateway)
    {
        static $params = array();
        if (isset($params[$gateway])) {
            return $params[$gateway];
        }

        require_once 'Net/SMS/' . $gateway . '.php';
        $class = 'Net_SMS_' . $gateway;
        $params[$gateway] = call_user_func(array($class, 'getParams'));

        return $params[$gateway];
    }

    /**
     * Returns a list of available gateway drivers.
     *
     * @return array  An array of available drivers.
     */
    public static function getDrivers()
    {
        static $drivers = array();
        if (!empty($drivers)) {
            return $drivers;
        }

        $drivers = array();

        if ($driver_dir = opendir(dirname(__FILE__) . '/SMS/')) {
            while (false !== ($file = readdir($driver_dir))) {
                /* Hide dot files and non .php files. */
                if (substr($file, 0, 1) != '.' && substr($file, -4) == '.php') {
                    $driver = substr($file, 0, -4);
                    $driver_info = Net_SMS_Factory::getGatewayInfo($driver);
                    $drivers[$driver] = $driver_info['name'];
                }
            }
            closedir($driver_dir);
        }

        return $drivers;
    }

    /**
     * Returns send parameters for a gateway from the driver subclass
     * getDefaultSendParams()function. These are parameters which are available
     * to the user during sending, such as setting a time for delivery, or type
     * of SMS (normal text or flash), or source address, etc.
     *
     * @param string  The name of the gateway driver for which to return the
     *                send parameters.
     *
     * @return array  An array of available send parameters.
     */
    public static function getDefaultSendParams($gateway)
    {
        static $params = array();
        if (isset($params[$gateway])) {
            return $params[$gateway];
        }

        require_once 'Net/SMS/' . $gateway . '.php';
        $class = 'Net_SMS_' . $gateway;
        $params[$gateway] = call_user_func(array($class, 'getDefaultSendParams'));

        return $params[$gateway];
    }

}
