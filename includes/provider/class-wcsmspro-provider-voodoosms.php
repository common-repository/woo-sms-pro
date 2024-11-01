<?php

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

class WCSMSPRO_PROVIDER_VOODOOSMS extends WCSMSPRO_PROVIDER
{
    private
        $_api_username,
        $_api_password,
        $_from,
        $_url;

    public function __construct($options)
    {
        parent::__construct();
        $this->_api_username = empty($options['voodoosms_api_username']) ? null : trim($options['voodoosms_api_username']);
        $this->_api_password = empty($options['voodoosms_api_password']) ? null : trim($options['voodoosms_api_password']);
        $this->_from = empty($options['voodoosms_from']) ? null : trim($options['voodoosms_from']);
        $this->_url = "https://www.voodoosms.com/vapi/server/sendSMS";
        $this->data['provider_status'] = !empty($this->_api_username) && !empty($this->_api_password) && !empty($this->_from) ? 1 : 0;
    }

    public function send_sms()
    {
        if (empty($this->data['to']) || empty($this->data['message'])) {
            $this->data['errors'][] = 'Please fill all fields.';
            return false;
        }

        if (empty($this->_from)) {
            $this->data['errors'][] = 'Please set From number in SMS Gateway settings.';
            return false;
        }

        $data = http_build_query(array(
            'uid' => $this->_api_username,
            'pass' => $this->_api_password,
            'dest' => $this->data['to'],
            'orig' => $this->_from,
            'msg' => $this->data['message'],
            'format' => 'XML',
            'validity' => 1,
        ));

        $response = $this->_curl($this->_url . '?' . $data);
        if (!empty($response)) {
            $response = simplexml_load_string($response);
            if ($response->{'result'} == '200') {
                $this->data['success'][] = 'Your message was sent successfully.';
                return true;
            } else {
                $this->data['errors'][] = 'Your message was failed to be sent.';
                $this->data['errors'][] = "Error: " . $response->{'resultText'} . " - Code: " . $response->{'result'};
                return false;
            }
        } else {
            $this->data['errors'][] = 'Your message could not be sent. Please try again later.';
            return false;
        }
    }

    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->data)) {
            $this->data[$name] = $value;
        }
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return null;
    }
}