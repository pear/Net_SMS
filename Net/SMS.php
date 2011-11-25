<?php

require_once 'PEAR.php';
require_once 'Net/SMS/Exception.php';

/**
 * Net_SMS Class
 *
 * Copyright 2003-2009 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * $Horde: framework/Net_SMS/SMS.php,v 1.25 2009/01/06 17:49:34 jan Exp $
 *
 * @author  Marko Djukic <marko@oblo.com>
 * @package Net_SMS
 */
class Net_SMS {

    /**
     * A hash containing any parameters for the current gateway driver.
     *
     * @var array
     */
    var $_params = array();

    var $_auth = null;

    /**
     * Constructor
     *
     * @param array $params  Any parameters needed for this gateway driver.
     */
    public function __construct($params = null)
    {
        $this->_params = $params;
    }

    /**
     * Query the current Gateway object to find out if it supports the given
     * capability.
     *
     * @param string $capability  The capability to test for.
     *
     * @return mixed  Whether or not the capability is supported or any other
     *                value that the capability wishes to report.
     */
    function hasCapability($capability)
    {
        if (!empty($this->capabilities[$capability])) {
            return $this->capabilities[$capability];
        }
        return false;
    }

    /**
     * Authenticates against the gateway if required.
     *
     * @return mixed  True on success or PEAR Error on failure.
     */
    function authenticate()
    {
        /* Do authentication for this gateway if driver requires it. */
        if ($this->hasCapability('auth')) {
            $this->_auth = $this->_authenticate();
            return $this->_auth;
        }
        return true;
    }

    /**
     * Sends a message to one or more recipients. Hands off the actual sending
     * to the gateway driver.
     *
     * @param array $message  The message to be sent, which is composed of:
     *                        <pre>
     *                          id   - A unique ID for the message;
     *                          to   - An array of recipients;
     *                          text - The text of the message;
     *                        </pre>
     *
     *
     * @return mixed  True on success or PEAR Error on failure.
     */
    public function send($message)
    {
        if (!is_array($message)) {
            throw new InvalidArgumentException("Parameter message is expected to be an array/hash");
        }

        if (!isset($message['id'])) {
            throw new InvalidArgumentException("Please specify message id, ie: array('id' => 1)");
        }

        if (!isset($message['to'])) {
            throw new InvalidArgumentException("Please specify an array of recipients, ie: array('to' => array('phone1', 'phone2'))");
        }

        if (!isset($message['text'])) {
            throw new InvalidArgumentException("Please specify message text, ie: array('text' => 'Hi!')");
        }

        /* Authenticate. */
        if (is_a($this->authenticate(), 'PEAR_Error')) {
            return $this->_auth;
        }

        /* Make sure the recipients are in an array. */
        if (!is_array($message['to'])) {
            $message['to'] = array($message['to']);
        }

        /* Array to store each send. */
        $sends = array();

        /* If gateway supports batch sending, preference is given to this
         * method. */
        if ($max_per_batch = $this->hasCapability('batch')) {
            /* Split up the recipients in the max recipients per batch as
             * supported by gateway. */
            $iMax = count($message['to']);
            $batches = ceil($iMax / $max_per_batch);

            /* Loop through the batches and compose messages to be sent. */
            for ($b = 0; $b < $batches; $b++) {
                $recipients = array_slice($message['to'], ($b * $max_per_batch), $max_per_batch);
                $response = $this->_send($message, $recipients);
                foreach ($recipients as $recipient) {
                    if ($response[$recipient][0] == 1) {
                        /* Message was sent, store remote id. */
                        $remote_id = $response[$recipient][1];
                        $error = null;
                    } else {
                        /* Message failed, store error code. */
                        $remote_id = null;
                        $error = $response[$recipient][1];
                    }

                    /* Store the sends. */
                    $sends[] = array('message_id' => $message['id'],
                                     'remote_id'  => $remote_id,
                                     'recipient'  => $recipient,
                                     'error'      => $error);
                }
            }

            return $sends;
        }

        /* No batch sending available, just loop through all recipients
         * and send a message for each one. */
        foreach ($message['to'] as $recipient) {
            $response = $this->_send($message, $recipient);
            if ($response[0] == 1) {
                /* Message was sent, store remote id if any. */
                $remote_id = (isset($response[1]) ? $response[1] : null);
                $error = null;
            } else {
                /* Message failed, store error code. */
                $remote_id = null;
                $error = $response[1];
            }

            /* Store the sends. */
            $sends[] = array('message_id' => $message['id'],
                             'remote_id'  => $remote_id,
                             'recipient'  => $recipient,
                             'error'      => $error);
        }

        return $sends;
    }

    /**
     * If the current driver has a credit capability, queries the gateway for
     * a credit balance and returns the value.
     *
     * @return integer  Value indicating available credit or null if not
     *                  supported.
     */
    function getBalance()
    {
        /* Authenticate. */
        if (is_a($this->authenticate(), 'PEAR_Error')) {
            return $this->_auth;
        }

        /* Check balance. */
        if ($this->hasCapability('credit')) {
            return $this->_getBalance();
        } else {
            return null;
        }
    }

    /**
     * This function does the actual sending of the message.
     *
     * @param array $message  The array containing the message and its send
     *                        parameters.
     * @param array $to       The recipients.
     *
     * @return array  An array with the success status and additional
     *                information.
     */
    protected function _send($message, $to) {
        return array();
    }
}
