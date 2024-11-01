<?php

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

abstract class WCSMSPRO_PROVIDER
{
    protected
        $data;

    public function __construct()
    {
        $this->data = array(
            'to' => null,
            'from' => null,
            'message' => null,
            'provider_status' => 0,
            'errors' => array(),
            'success' => array(),
        );
    }

    public abstract function send_sms();

    public function is_provider_active()
    {
        return $this->data['provider_status'] ? true : false;
    }

    protected function _curl($url = '', $data = array(), $headers = array(), $is_post = false, $curl_options = array())
    {
        if (empty($url)) {
            return false;
        }

        $response = '';

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 300);
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            if (!empty($headers)) {
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
            curl_setopt($ch, CURLOPT_POST, $is_post);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            if (!empty($curl_options)) {
                foreach ($curl_options as $option => $value) {
                    $curl_option = constant($option);
                    if ($curl_option) {
                        curl_setopt($ch, $curl_option, $value);
                    }
                }
            }
            $response = curl_exec($ch);
        } catch (Exception $ex) {
            error_log($ex->getMessage());
        }

        return $response;
    }
}