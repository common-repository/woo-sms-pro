<?php

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

class WCSMSPRO_PROVIDER_PLIVO extends WCSMSPRO_PROVIDER
{
    private
        $_auth_id,
        $_auth_token,
        $_from_number,
        $_url;

    public function __construct($options)
    {
        parent::__construct();
        $this->_auth_id = empty($options['plivo_auth_id']) ? null : trim($options['plivo_auth_id']);
        $this->_auth_token = empty($options['plivo_auth_token']) ? null : trim($options['plivo_auth_token']);
        $this->_from_number = empty($options['plivo_from_number']) ? null : trim($options['plivo_from_number']);
        $this->_url = "https://api.plivo.com/v1/Account/{$this->_auth_id}/Message/";
        $this->data['provider_status'] = !empty($this->_auth_id) && !empty($this->_auth_token) && !empty($this->_from_number) ? 1 : 0;
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

        $data = json_encode(array(
            'text' => $this->data['message'],
            'dst' => $this->data['to'],
            'src' => $this->_from_number,
        ));
        $headers = array(
            'Content-Type: application/json'
        );
        $curl_options = array(
            'CURLOPT_USERPWD' => $this->_auth_id . ':' . $this->_auth_token
        );
        $response = $this->_curl($this->_url, $data, $headers, true, $curl_options);
        if (!empty($response)) {
            $response = json_decode($response);
            if (!empty($response->error)) {
                $this->data['errors'][] = 'Your message was failed to be sent.';
                $this->data['errors'][] = "Error: {$response->error}";
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