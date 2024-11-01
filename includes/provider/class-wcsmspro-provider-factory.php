<?php

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

class WCSMSPRO_PROVIDER_FACTORY
{
    public static function getInstance()
    {
        $options = get_option('wcsmspro_options_sms_gateway');
        $provider = empty($options['sms_provider']) ? '' : $options['sms_provider'];
        switch ($provider) {
            case 'twilio':
                $class_name = 'WCSMSPRO_PROVIDER_TWILIO';
                break;
            case 'plivo':
                $class_name = 'WCSMSPRO_PROVIDER_PLIVO';
                break;
            case 'burstsms':
                $class_name = 'WCSMSPRO_PROVIDER_BURSTSMS';
                break;
            case 'nexmo':
                $class_name = 'WCSMSPRO_PROVIDER_NEXMO';
                break;
            case 'voodoosms':
                $class_name = 'WCSMSPRO_PROVIDER_VOODOOSMS';
                break;
            default:
                $class_name = 'WCSMSPRO_PROVIDER_TWILIO';
                break;
        }

        if (empty($class_name)) {
            throw new Exception('Wrong provider selected');
        }

        return new $class_name($options);
    }
}