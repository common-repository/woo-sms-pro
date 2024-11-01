<?php

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

class WCSMSPRO_PROVIDER_NEXMO extends WCSMSPRO_PROVIDER
{
    private
        $_api_key,
        $_api_secret,
        $_from,
        $_url;

    public function __construct($options)
    {
        parent::__construct();
        $this->_api_key = empty($options['nexmo_api_key']) ? null : trim($options['nexmo_api_key']);
        $this->_api_secret = empty($options['nexmo_api_secret']) ? null : trim($options['nexmo_api_secret']);
        $this->_from = empty($options['nexmo_from']) ? null : trim($options['nexmo_from']);
        $this->_url = "https://rest.nexmo.com/sms/json";
        $this->data['provider_status'] = !empty($this->_api_key) && !empty($this->_api_secret) && !empty($this->_from) ? 1 : 0;
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
            'text' => $this->data['message'],
            'to' => $this->data['to'],
            'from' => $this->_from,
            'api_key' => $this->_api_key,
            'api_secret' => $this->_api_secret,
        ));
        $response = $this->_curl($this->_url, $data, array(), true);
        if (!empty($response)) {
            $response = json_decode($response);
            if ($response->messages[0]->status == 0) {
                $this->data['success'][] = 'Your message was sent successfully.';
                return true;
            } else {
                $this->data['errors'][] = 'Your message was failed to be sent.';
                $this->data['errors'][] = "Error: {$response->messages[0]->{'error-text'}} - Code: {$response->messages[0]->status}";
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