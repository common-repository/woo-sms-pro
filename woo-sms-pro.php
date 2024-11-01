<?php
/**
 * Plugin Name: Woo SMS Pro
 * Description: Sends personalized SMS to customers on each new order as well as order status change. You can also receive SMS notifications on new orders.
 * Plugin URI: https://www.codebite.pk/
 * Author: CodeBite
 * Version: 1.0.6
 * Text Domain: woo-sms-pro
 * Tags: woo,woocommerce,sms,notifications,alerts,twilio,plivio,burst-sms,nexmo,gateway,provider,shopping,voodoosms
 * WC requires at least: 2.2
 * WC tested up to: 3.2.3
 *
 * Copyright: © 2009-2015 WooCommerce.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

// basepath
if (!defined('WCSMSPRO_BASEPATH')) {
    define('WCSMSPRO_BASEPATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
}

// define slug
if (!defined('WCSMSPRO_SLUG')) {
    define('WCSMSPRO_SLUG', plugin_basename(__FILE__));
}


// include the main class
if (!class_exists('WCSMSPRO')) {
    include_once dirname(__FILE__) . '/includes/class-wcsmspro.php';
}

// main instance
function cb_wcsmspro()
{
    return WCSMSPRO::getInstance();
}

cb_wcsmspro();