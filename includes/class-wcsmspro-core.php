<?php

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

class WCSMSPRO_CORE
{
    public
        $template_variables = null,
        $default_order_statuss = null,
        $default_sms_templates = null;

    private
        $_errors = null;

    public function __construct()
    {
        $this->template_variables = array(
            '{first_name}',
            '{last_name}',
            '{phone_number}',
            '{email}',
            '{order_id}',
            '{order_status}',
            '{payment_method}',
            '{total_shipping}',
            '{total_discount}',
            '{total_amount}',
            '{address_1}',
            '{address_2}',
            '{postcode}',
            '{city}',
            '{state}',
            '{country}',
        );
        $this->default_order_statuss = array(
            'wc-pending' => 'Pending payment',
            'wc-processing' => 'Processing',
            'wc-on-hold' => 'On hold',
            'wc-completed' => 'Completed',
            'wc-cancelled' => 'Cancelled',
            'wc-refunded' => 'Refunded',
            'wc-failed' => 'Failed',
        );
        $this->default_sms_templates = array(
            'wc-pending' => 'Hi {first_name}, your payment is pending. Total amount due is: {total_amount}',
            'wc-processing' => 'Hi {first_name}, we are processing your order. Thanks',
            'wc-on-hold' => 'Hi {first_name}, your order is on-hold. Please contact us for more information.',
            'wc-completed' => 'Hi {first_name}, your order has been completed successfully. Your Order ID: {order_id}',
            'wc-cancelled' => 'Hi {first_name}. Your order has been cancelled.',
            'wc-refunded' => 'Hi {first_name}, we have refunded your payment {total_amount}. Thanks',
            'wc-failed' => 'Hi {first_name}, your order was failed. Please try again.',
            'new-order-placed' => 'Hi {first_name}, thank you for placing this order. Your Order ID: {order_id}',
            'admin-new-order-placed' => 'Hi admin, you have received a new order from {first_name} totalling amount {total_amount}.',
        );
        add_action('admin_enqueue_scripts', array($this, 'load_scripts'));
        add_action('woocommerce_payment_complete', array($this, 'woo_send_order_placed'));
        add_action('woocommerce_order_status_changed', array($this, 'woo_send_order_status_changed'));
        add_action('admin_notices', array($this, 'woo_errors'));
    }

    public function load_scripts($hook)
    {
        if ($hook != 'toplevel_page_woo-sms-pro') {
            return;
        }
        wp_enqueue_script(
            'wcsmspro_custom_admin_js',
            plugin_dir_url(dirname(__FILE__)) . 'admin/js/main.js',
            array(),
            null,
            true
        );
        wp_enqueue_script(
            'wcsmspro_custom_admin_css',
            plugin_dir_url(dirname(__FILE__)) . 'admin/css/main.css',
            array(),
            null
        );
    }

    public function woo_send_order_placed($order_id)
    {
        $provider = cb_wcsmspro()->provider;
        if (cb_wcsmspro()->is_plugin_active()) {
            $options = get_option('wcsmspro_options_general');
            $is_new_order = empty($options['order_placed_sms']) ? false : true;
            if ($is_new_order) {
                $order = new WC_Order($order_id);
                $data = $order->get_data();
                $options = get_option('wcsmspro_options_sms_templates', $this->default_sms_templates);
                $current_template = isset($options['new-order-placed']) ? trim($options['new-order-placed']) : '';
                $admin_template = isset($options['admin-new-order-placed']) ? trim($options['admin-new-order-placed']) : '';
                $phone_number = isset($data['billing']['phone']) ? trim($data['billing']['phone']) : '';
                $phone_number = !empty($phone_number) ? WCSMSPRO_HELPER::get_valid_number($phone_number, $data['billing']['country']) : '';
                if ($phone_number && $current_template) {
                    $replace_vars = array(
                        $data['shipping']['first_name'],
                        $data['shipping']['last_name'],
                        $data['billing']['phone'],
                        $data['billing']['email'],
                        $order_id,
                        $order->get_status(),
                        $data['payment_method_title'],
                        $data['shipping_total'],
                        $data['discount_total'],
                        $data['total'],
                        $data['shipping']['address_1'],
                        $data['shipping']['address_2'],
                        $data['shipping']['postcode'],
                        $data['shipping']['city'],
                        $data['shipping']['state'],
                        $data['shipping']['country'],
                    );
                    $provider->to = $phone_number;
                    $provider->message = str_replace($this->template_variables, $replace_vars, $current_template);
                    $sent = $provider->send_sms();
                    if ($sent) {
                        $order->add_order_note('SMS sent: ' . $provider->message);
                        $order->save();
                        // send admin notifs
                        $options = get_option('wcsmspro_options_general');
                        $is_admin_sms = !empty($options['admin_notifications']) ? true : false;
                        $admin_number = isset($options['admin_phone_numbers']) ? trim($options['admin_phone_numbers']) : '';
                        if ($is_admin_sms && $admin_number) {
                            $provider->to = $admin_number;
                            $provider->message = str_replace($this->template_variables, $replace_vars, $admin_template);
                            $sent = $provider->send_sms();
                        }
                    } else {
                        set_transient('wcsmspro_errors', $provider->errors);
                    }
                } else {
                    $errors = array(
                        "Sorry, either customer's phone number is invalid or you have not set any SMS template."
                    );
                    set_transient('wcsmspro_errors', $errors);
                }
            }
        }
    }

    public function woo_send_order_status_changed($order_id)
    {
        $provider = cb_wcsmspro()->provider;
        if (cb_wcsmspro()->is_plugin_active()) {
            $options = get_option('wcsmspro_options_general');
            $allowed_statuss = isset($options['allowed_order_status_sms']) ? $options['allowed_order_status_sms'] : array();
            $order = new WC_Order($order_id);
            $data = $order->get_data();
            $current_status = 'wc-' . $order->get_status();

            if (in_array($current_status, $allowed_statuss)) {
                $options = get_option('wcsmspro_options_sms_templates', $this->default_sms_templates);
                $status_titles = wc_get_order_statuses();
                $current_template = isset($options[$current_status]) ? trim($options[$current_status]) : '';
                $phone_number = isset($data['billing']['phone']) ? trim($data['billing']['phone']) : '';
                $phone_number = !empty($phone_number) ? WCSMSPRO_HELPER::get_valid_number($phone_number, $data['billing']['country']) : '';
                if ($phone_number && $current_template) {
                    $replace_vars = array(
                        $data['shipping']['first_name'],
                        $data['shipping']['last_name'],
                        $data['billing']['phone'],
                        $data['billing']['email'],
                        $order_id,
                        $status_titles[$current_status],
                        $data['payment_method_title'],
                        $data['shipping_total'],
                        $data['discount_total'],
                        $data['total'],
                        $data['shipping']['address_1'],
                        $data['shipping']['address_2'],
                        $data['shipping']['postcode'],
                        $data['shipping']['city'],
                        $data['shipping']['state'],
                        $data['shipping']['country'],
                    );
                    $provider->to = $phone_number;
                    $provider->message = str_replace($this->template_variables, $replace_vars, $current_template);
                    $sent = $provider->send_sms();
                    if ($sent) {
                        $order->add_order_note('SMS sent: ' . $provider->message);
                        $order->save();
                    } else {
                        set_transient('wcsmspro_errors', $provider->errors);
                    }
                } else {
                    $errors = array(
                        "Sorry, either customer's phone number is invalid or you have not set any SMS template."
                    );
                    set_transient('wcsmspro_errors', $errors);
                }
            }
        }
    }

    public function woo_errors()
    {
        $this->_errors = get_transient('wcsmspro_errors');
        delete_transient('wcsmspro_errors');
        if (!empty($this->_errors)) {
            ?>
            <div class="notice notice-error is-dismissible">
                <?php foreach ($this->_errors as $error): ?>
                    <p><?php _e($error, 'wcsmspro'); ?></p>
                <?php endforeach; ?>
            </div>
            <?php

        }

    }
}