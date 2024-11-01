<?php

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

class WCSMSPRO_PROVIDER_BURSTSMS extends WCSMSPRO_PROVIDER
{
    private
        $_api_key,
        $_api_secret,
        $_from_number,
        $_url;

    public function __construct($options)
    {
        parent::__construct();
        $this->_api_key = empty($options['burstsms_api_key']) ? null : trim($options['burstsms_api_key']);
        $this->_api_secret = empty($options['burstsms_api_secret']) ? null : trim($options['burstsms_api_secret']);
        $this->_from_number = empty($options['burstsms_from_number']) ? null : trim($options['burstsms_from_number']);
        $this->_url = "https://api.transmitsms.com/send-sms.json";
        $this->data['provider_status'] = !empty($this->_api_key) && !empty($this->_api_secret) && !empty($this->_from_number) ? 1 : 0;
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
            'message' => $this->data['message'],
            'to' => $this->data['to'],
            'from' => $this->_from_number,
        ));
        $curl_options = array(
            'CURLOPT_USERPWD' => $this->_api_key . ':' . $this->_api_secret
        );
        $response = $this->_curl($this->_url, $data, array(), true, $curl_options);
        if (!empty($response)) {
            $response = json_decode($response);
            if ($response->error->code == 'SUCCESS') {
                $this->data['success'][] = 'Your message was sent successfully.';
                return true;
            } else {
                $this->data['errors'][] = 'Your message was failed to be sent.';
                $this->data['errors'][] = "Error: {$response->error->description} - Code: {$response->error->code}";
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