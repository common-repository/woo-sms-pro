<?php

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

class WCSMSPRO_PROVIDER_TWILIO extends WCSMSPRO_PROVIDER
{
    private
        $_account_sid,
        $_auth_token,
        $_from_number,
        $_url;

    public function __construct($options)
    {
        parent::__construct();
        $this->_account_sid = empty($options['twilio_account_sid']) ? null : trim($options['twilio_account_sid']);
        $this->_auth_token = empty($options['twilio_auth_token']) ? null : trim($options['twilio_auth_token']);
        $this->_from_number = empty($options['twilio_from_number']) ? null : trim($options['twilio_from_number']);
        $this->_url = "https://api.twilio.com/2010-04-01/Accounts/{$this->_account_sid}/Messages.json";
        $this->data['provider_status'] = !empty($this->_account_sid) && !empty($this->_auth_token) && !empty($this->_from_number) ? 1 : 0;
    }

    public function send_sms()
    {
        if (empty($this->data['to']) || empty($this->data['message'])) {
            $this->data['errors'][] = 'Please fill all fields.';
            return false;
        }

        if (empty($this->_from_number)) {
            $this->data['errors'][] = 'Please set From number in SMS Gateway settings.';
            return false;
        }

        $data = http_build_query(array(
            'Body' => $this->data['message'],
            'To' => $this->data['to'],
            'From' => $this->_from_number,
        ));
        $curl_options = array(
            'CURLOPT_USERPWD' => $this->_account_sid . ':' . $this->_auth_token
        );
        $response = $this->_curl($this->_url, $data, array(), true, $curl_options);
        if (!empty($response)) {
            $response = json_decode($response);
            if (!in_array($response->status, array('accepted', 'queued'))) {
                $this->data['errors'][] = 'Your message was failed to be sent.';
                $this->data['errors'][] = "Error: {$response->message} - Code: {$response->code}";
                return false;
            } else {
                $this->data['success'][] = 'Your message was sent successfully.';
                return true;
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