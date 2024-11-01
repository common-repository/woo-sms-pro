<?php

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

class WCSMSPRO_ADMIN_SETTINGS
{
    private
        $_sections,
        $_options,
        $_success,
        $_errors;

    private static $_sms_providers = null;

    public function __construct()
    {
        $this->_sections = array(
            'general' => 'General Settings',
            'sms_gateway' => 'SMS Gateway Settings',
            'sms_gateway_twilio' => 'Twilio Settings',
            'sms_gateway_plivo' => 'Plivo Settings',
            'sms_gateway_burstsms' => 'Burst SMS Settings',
            'sms_gateway_nexmo' => 'Nexmo SMS Settings',
            'sms_gateway_voodoosms' => 'VoodooSMS Settings',
            'sms_sender' => 'SMS Sender',
            'sms_templates' => 'SMS Templates',
        );
        $this->_options = array(
            'general',
            'sms_gateway',
            'sms_templates',
        );
        self::$_sms_providers = array(
            'twilio' => 'Twilio',
            'plivo' => 'Plivo',
            'burstsms' => 'Burst SMS',
            'nexmo' => 'Nexmo SMS',
            'voodoosms' => 'VoodooSMS',
        );

        add_action('admin_init', array($this, 'register_settings'));
        add_action('wcsmspro_send_sms', array($this, 'send_sms'));
    }

    public function send_sms()
    {
        if (!current_user_can('manage_options')) return;
        $is_error = false;
        $notice_id = 'wcsmspro_sms_sender';
        if (empty($_POST['wcsmspro_options_sms_sender']['to']) || empty($_POST['wcsmspro_options_sms_sender']['message'])) {
            add_settings_error($notice_id, $notice_id, 'Please complete all fields.');
            $is_error = true;
        }
        $to = isset($_POST['wcsmspro_options_sms_sender']['to']) ? $_POST['wcsmspro_options_sms_sender']['to'] : '';
        $message = isset($_POST['wcsmspro_options_sms_sender']['message']) ? $_POST['wcsmspro_options_sms_sender']['message'] : '';

        if (!$is_error) {
            $provider = cb_wcsmspro()->provider;
            if ($provider->is_provider_active()) {
                $provider->to = $to;
                $provider->message = $message;
                $sent = $provider->send_sms();
                if ($sent) {
                    $this->_success = $provider->success;
                    $messages = implode('<br>', $this->_success);
                    add_settings_error($notice_id, $notice_id, $messages, 'updated');
                } else {
                    $this->_errors = $provider->errors;
                    $messages = implode('<br>', $this->_errors);
                    add_settings_error($notice_id, $notice_id, $messages);
                }
            } else {
                add_settings_error($notice_id, $notice_id, 'Please set sms gateway settings first.');
            }
        }
    }

    public function section_callback($section)
    {
        switch ($section['id']) {
            // general
            case 'wcsmspro_section_general':
                echo '<p>These settings enable you to customize the General settings.</p>';
                $section_options = 'wcsmspro_options_general';
                $fields = array(
                    'enable_plugin' => array(
                        'title' => 'Enable Plugin',
                        'label' => 'Enable plugin for use'
                    ),
                    'order_placed_sms' => array(
                        'title' => 'Send New Order SMS',
                        'label' => 'Sends SMS when customer places a new order.'
                    ),
                    'allowed_order_status_sms' => array(
                        'title' => 'Allowed Order Status SMS',
                        'label' => 'Allowed Order Status SMS'
                    ),
                    'admin_notifications' => array(
                        'title' => 'Enable Admin Notifications',
                        'label' => 'Receive SMS when a customer places a new order.'
                    ),
                    'admin_phone_numbers' => array(
                        'title' => 'Admin Phone Number',
                        'label' => 'e.g. +447441231231'
                    ),
                );

                break;

            // sms gateway
            case 'wcsmspro_section_sms_gateway':
                echo "<p>These settings enable you to customize the SMS Gateway settings.<br>It is recommended to test your API by sending a test SMS to your number using our SMS Sender tab to verify your settings.</p>";
                $section_options = 'wcsmspro_options_sms_gateway';
                $fields = array(
                    'sms_provider' => array(
                        'title' => 'SMS Gateway Provider',
                        'label' => 'Choose your SMS Gateway Provider'
                    )
                );

                break;

            // sms gateway: twilio
            case 'wcsmspro_section_sms_gateway_twilio':
                echo "<p>Please enter credentials from Twilio. <a href='https://www.twilio.com/try-twilio' target='_blank'>Click here</a> to sign up for a trial account.</p>";
                $section_options = 'wcsmspro_options_sms_gateway';
                $fields = array(
                    'twilio_account_sid' => array(
                        'title' => 'Account SID',
                        'label' => 'Account SID',
                        'type' => 'text',
                    ),
                    'twilio_auth_token' => array(
                        'title' => 'AUTH Token',
                        'label' => 'AUTH Token',
                        'type' => 'text',
                    ),
                    'twilio_from_number' => array(
                        'title' => 'From Number',
                        'label' => 'Enter your registered From phone number',
                        'type' => 'text',
                    ),
                );

                break;

            // sms gateway: plivo
            case 'wcsmspro_section_sms_gateway_plivo':
                echo "<p>Please enter credentials from Plivo. <a href='https://manage.plivo.com/accounts/register/' target='_blank'>Click here</a> to sign up for a trial account.</p>";
                $section_options = 'wcsmspro_options_sms_gateway';
                $fields = array(
                    'plivo_auth_id' => array(
                        'title' => 'Auth ID',
                        'label' => 'Auth ID',
                        'type' => 'text',
                    ),
                    'plivo_auth_token' => array(
                        'title' => 'AUTH Token',
                        'label' => 'AUTH Token',
                        'type' => 'text',
                    ),
                    'plivo_from_number' => array(
                        'title' => 'From Number',
                        'label' => 'Enter your registered From phone number',
                        'type' => 'text',
                    ),
                );

                break;

            // sms gateway: burstsms
            case 'wcsmspro_section_sms_gateway_burstsms':
                echo "<p>Please enter credentials from Burst SMS. <a href='http://go.burstsms.com/register' target='_blank'>Click here</a> to sign up for a trial account.</p>";
                $section_options = 'wcsmspro_options_sms_gateway';
                $fields = array(
                    'burstsms_api_key' => array(
                        'title' => 'API Key',
                        'label' => 'API Key',
                        'type' => 'text',
                    ),
                    'burstsms_api_secret' => array(
                        'title' => 'API Secret',
                        'label' => 'API Secret',
                        'type' => 'text',
                    ),
                    'burstsms_from_number' => array(
                        'title' => 'From Number',
                        'label' => 'Enter your registered From phone number',
                        'type' => 'text',
                    ),
                );

                break;

            // sms gateway: nexmo
            case 'wcsmspro_section_sms_gateway_nexmo':
                echo "<p>Please enter credentials from Nexmo SMS. <a href='https://dashboard.nexmo.com/sign-up' target='_blank'>Click here</a> to sign up for a trial account.</p>";
                $section_options = 'wcsmspro_options_sms_gateway';
                $fields = array(
                    'nexmo_api_key' => array(
                        'title' => 'API Key',
                        'label' => 'API Key',
                        'type' => 'text',
                    ),
                    'nexmo_api_secret' => array(
                        'title' => 'API Secret',
                        'label' => 'API Secret',
                        'type' => 'text',
                    ),
                    'nexmo_from' => array(
                        'title' => 'Sender ID',
                        'label' => '15 characters max with a-z, A-Z or 0-9',
                        'type' => 'text',
                    ),
                );

                break;

            // sms gateway: voodoosms
            case 'wcsmspro_section_sms_gateway_voodoosms':
                echo "<p>Please enter credentials from VoodooSMS. <a href='https://www.voodoosms.com/free-account.html' target='_blank'>Click here</a> to sign up for a trial account.</p>";
                $section_options = 'wcsmspro_options_sms_gateway';
                $fields = array(
                    'voodoosms_api_username' => array(
                        'title' => 'API Username',
                        'label' => 'API Username',
                        'type' => 'text',
                    ),
                    'voodoosms_api_password' => array(
                        'title' => 'API Password',
                        'label' => 'API Password',
                        'type' => 'text',
                    ),
                    'voodoosms_from' => array(
                        'title' => 'Sender ID',
                        'label' => '15 Numeric Digits or 11 alphanumeric A-Z, 0-9',
                        'type' => 'text',
                    ),
                );

                break;

            // sms sender
            case 'wcsmspro_section_sms_sender':
                echo "<p>You can use this page to quickly send an SMS to your specified receiver. Please note, this module will still work if you have disabled plugin from General settings.</p>";
                $section_options = 'wcsmspro_options_sms_sender';
                $fields = array(
                    'to' => array(
                        'title' => 'To',
                        'label' => 'Enter receiver mobile number'
                    ),
                    'message' => array(
                        'title' => 'Message',
                        'label' => 'Enter your message here..'
                    ),
                );
                break;

            // sms templates
            case 'wcsmspro_section_sms_templates':
                ?>
                <p>You can set SMS templates for order status's. You can also use following variables to
                    replace with dynamic information:</p>
                <table class="form-table">
                    <tr>
                        <td>
                            <ul>
                                <?php foreach (cb_wcsmspro()->core->template_variables as $key => $variable): ?>
                                    <?php if ($key != 0 && $key % 4 == 0) echo '</ul></td><td><ul>' ?>
                                    <li>
                                        <input class="wcsmspro_copy_variable"
                                               type="text"
                                               readonly="readonly"
                                               value="<?php echo $variable; ?>">
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                </table>
                <?php
                $section_options = 'wcsmspro_options_sms_templates';
                if (function_exists('wc_get_order_statuses')) {
                    $statuss = wc_get_order_statuses();
                } else {
                    $statuss = cb_wcsmspro()->core->default_order_statuss;
                }
                $fields = array();
                $fields['new-order-placed'] = array(
                    'title' => 'New Order',
                    'label' => 'Enter message for the new order placed.'
                );
                foreach ($statuss as $status_id => $status) {
                    $fields[$status_id] = array(
                        'title' => $status,
                        'label' => 'Enter message for order status: ' . $status
                    );
                }
                $fields['admin-new-order-placed'] = array(
                    'title' => 'Admin New Order Notification',
                    'label' => 'Enter message to receive on new order placed by customer.'
                );
                break;
            default:
                $fields = array();
                break;
        }


        foreach ($fields as $field_id => $field) {
            add_settings_field(
                $field_id,
                $field['title'],
                array($this, 'field_callback'),
                $section['id'],
                $section['id'],
                array(
                    'id' => $field_id,
                    'label' => $field['label'],
                    'type' => isset($field['type']) ? $field['type'] : '',
                    'options_id' => $section_options,
                )
            );
        }
    }

    private function section_default_options($section_id)
    {
        switch ($section_id) {
            case 'wcsmspro_options_general':
                $options = array(
                    'enable_plugin' => false,
                    'admin_notifications' => false,
                    'admin_phone_numbers' => '',
                    'order_placed_sms' => true,
                    'allowed_order_status_sms' => array_keys(cb_wcsmspro()->core->default_order_statuss),
                );
                break;

            case 'wcsmspro_options_sms_gateway':
                $options = array(
                    'sms_provider' => 'twilio',
                );
                break;

            case 'wcsmspro_options_sms_templates':
                $options = cb_wcsmspro()->core->default_sms_templates;
                break;

            default:
                $options = array();
                break;
        }
        return $options;
    }

    public function field_callback($args)
    {
        $options_id = $args['options_id'];
        $options = get_option($options_id, $this->section_default_options($options_id));
        $id = isset($args['id']) ? $args['id'] : '';
        $label = isset($args['label']) ? $args['label'] : '';
        $field_type = isset($args['type']) ? $args['type'] : '';
        $field_id = "{$options_id}_{$id}";
        $field_name = "{$options_id}[{$id}]";

        switch ($options_id) {
            // general
            case 'wcsmspro_options_general':
                if ($id == 'enable_plugin') {
                    $checked = isset($options[$id]) ? checked($options[$id], 1, false) : '';
                    echo "<input id='{$field_id}' 
                         name='{$field_name}'
                         type='checkbox' 
                         value='1' 
                         {$checked}> 
                  <label for='{$field_id}'>{$label}</label>
                  ";
                } elseif ($id == 'admin_notifications') {
                    $checked = isset($options[$id]) ? checked($options[$id], 1, false) : '';
                    echo "<input id='{$field_id}' 
                         name='{$field_name}'
                         type='checkbox' 
                         value='1' 
                         {$checked}> 
                  <label for='{$field_id}'>{$label}</label>
                  ";
                } elseif ($id == 'admin_phone_numbers') {
                    $value = isset($options[$id]) ? sanitize_textarea_field($options[$id]) : '';
                    echo "<input id='{$field_id}' 
                         name='{$field_name}'
                         type='text' 
                         size='40'
                         placeholder='{$label}'
                         value='{$value}' 
                         ><br>
                         <small>You can set notification template in SMS Templates section.</small>
                  ";
                } elseif ($id == 'order_placed_sms') {
                    $checked = isset($options[$id]) ? checked($options[$id], 1, false) : '';
                    echo "<input id='{$field_id}' 
                         name='{$field_name}'
                         type='checkbox' 
                         value='1' 
                         {$checked}> 
                  <label for='{$field_id}'>{$label}</label>
                  ";
                } elseif ($id == 'allowed_order_status_sms') {
                    if (function_exists('wc_get_order_statuses')) {
                        $statuss = wc_get_order_statuses();
                    } else {
                        $statuss = cb_wcsmspro()->core->default_order_statuss;
                    }
                    $status_counter = 0;
                    foreach ($statuss as $status_id => $status) {
                        $checked = in_array($status_id, $options[$id]) ? "checked='checked'" : '';
                        echo "<input id='{$field_id}_{$status_counter}' 
                         name='{$field_name}[]'
                         type='checkbox' 
                         value='{$status_id}' 
                         {$checked}> 
                        <label for='{$field_id}_{$status_counter}'>{$status}</label><br>
                        ";
                        $status_counter++;
                    }
                }
                break;

            // sms gateway
            case
            'wcsmspro_options_sms_gateway':
                if ($id == 'sms_provider') {
                    $selected_option = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';
                    echo "<select id='{$field_id}' name='{$field_name}'>";
                    foreach (self::$_sms_providers as $key => $value) {
                        $selected = selected($selected_option === $key, true, false);
                        echo "<option value='{$key}' {$selected}>{$value}</option>";
                    }
                    echo "</select>";
                } elseif ($field_type == 'text') {
                    $value = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';
                    echo "<input id='{$field_id}' 
                         name='{$field_name}'
                         type='text' 
                         size='40' 
                         placeholder='{$label}'
                         value='{$value}'>";
                }
                break;

            // sms sender
            case 'wcsmspro_options_sms_sender':
                $posted = !empty($this->_errors) && !empty($_POST[$options_id][$id]) ? $_POST[$options_id][$id] : '';
                if ($id == 'to') {
                    $value = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';
                    echo "<input id='{$field_id}' 
                         name='{$field_name}'
                         type='text' 
                         size='40' 
                         placeholder='{$label}'
                         value='" . (isset($posted) ? $posted : $value) . "'>";
                } elseif ($id == 'message') {
                    $value = isset($options[$id]) ? sanitize_textarea_field($options[$id]) : '';
                    echo "<textarea id='{$field_id}' 
                         cols='40'
                         rows='10'
                         placeholder='{$label}'
                         name='{$field_name}'>" . (isset($posted) ? $posted : $value) . "</textarea>";
                }
                break;

            // sms templates
            case 'wcsmspro_options_sms_templates':
                if (function_exists('wc_get_order_statuses')) {
                    $statuss = wc_get_order_statuses();
                } else {
                    $statuss = cb_wcsmspro()->core->default_order_statuss;
                }
                $value = isset($options[$id]) ? $options[$id] : '';
                $posted = !empty($this->_errors) && !empty($_POST[$options_id][$id]) ? $_POST[$options_id][$id] : '';

                // new order
                if ($id = 'new-order-placed') {
                    echo "<textarea id='{$field_id}' 
                         cols='40'
                         rows='10'
                         placeholder='{$label}'
                         name='{$field_name}'>" . (!empty($posted) ? $posted : $value) . "</textarea>";
                } elseif ($id = 'admin-new-order-placed') {
                    echo "<textarea id='{$field_id}' 
                         cols='40'
                         rows='10'
                         placeholder='{$label}'
                         name='{$field_name}'>" . (!empty($posted) ? $posted : $value) . "</textarea>";
                }
                // order status change
                if (array_key_exists($id, $statuss)) {
                    echo "<textarea id='{$field_id}' 
                         cols='40'
                         rows='10'
                         placeholder='{$label}'
                         name='{$field_name}'>" . (!empty($posted) ? $posted : $value) . "</textarea>";
                }

                break;
        }
    }

    // register settings
    public
    function register_settings()
    {
        // register options
        foreach ($this->_options as $option) {
            register_setting(
                "wcsmspro_options_{$option}",
                "wcsmspro_options_{$option}",
                array($this, "validate_options_{$option}")
            );
        }

        // register sections
        foreach ($this->_sections as $section_id => $section_name) {
            add_settings_section(
                "wcsmspro_section_{$section_id}",
                $section_name,
                array($this, 'section_callback'),
                "wcsmspro_section_{$section_id}"
            );
        }
    }

    public
    function validate_options_general($input)
    {
        $input['enable_plugin'] = isset($input['enable_plugin']) && $input['enable_plugin'] == 1 ? 1 : 0;
        /**
         * Check if WooCommerce is active
         **/
        if ($input['enable_plugin'] && !cb_wcsmspro()->is_woocommerce_active()) {
            $input['enable_plugin'] = 0;
        }

        if (function_exists('wc_get_order_statuses')) {
            $statuss = wc_get_order_statuses();
        } else {
            $statuss = cb_wcsmspro()->core->default_order_statuss;
        }

        if (is_array($input['allowed_order_status_sms'])) {
            foreach ($input['allowed_order_status_sms'] as $key => $status_id) {
                if (!array_key_exists($status_id, $statuss)) {
                    unset($input['allowed_order_status_sms'][$key]);
                }
            }
        } else {
            $input['allowed_order_status_sms'] = array();
        }

        $input['admin_notifications'] = isset($input['admin_notifications']) && $input['admin_notifications'] == 1 ? 1 : 0;
        $input['admin_phone_numbers'] = isset($input['admin_phone_numbers']) ? sanitize_text_field($input['admin_phone_numbers']) : '';
        $input['order_placed_sms'] = isset($input['order_placed_sms']) && $input['order_placed_sms'] == 1 ? 1 : 0;

        return $input;
    }

    public
    function validate_options_sms_gateway($input)
    {
        if (!isset($input['sms_provider'])) {
            $input['sms_provider'] = null;
        }

        if (!array_key_exists($input['sms_provider'], self::$_sms_providers)) {
            $input['sms_provider'] = null;
        }

        switch ($input['sms_provider']) {
            case 'twilio':
                if (isset($input['twilio_account_sid'])) {
                    $input['twilio_account_sid'] = sanitize_text_field($input['twilio_account_sid']);
                }

                if (isset($input['twilio_auth_token'])) {
                    $input['twilio_auth_token'] = sanitize_text_field($input['twilio_auth_token']);
                }

                if (isset($input['twilio_from_number'])) {
                    $input['twilio_from_number'] = sanitize_text_field($input['twilio_from_number']);
                }

                break;

            case 'plivo':
                if (isset($input['plivo_auth_id'])) {
                    $input['plivo_auth_id'] = sanitize_text_field($input['plivo_auth_id']);
                }

                if (isset($input['plivo_auth_token'])) {
                    $input['plivo_auth_token'] = sanitize_text_field($input['plivo_auth_token']);
                }

                if (isset($input['plivo_from_number'])) {
                    $input['plivo_from_number'] = sanitize_text_field($input['plivo_from_number']);
                }

                break;

            case 'burstsms':
                if (isset($input['burstsms_api_key'])) {
                    $input['burstsms_api_key'] = sanitize_text_field($input['burstsms_api_key']);
                }

                if (isset($input['burstsms_api_secret'])) {
                    $input['burstsms_api_secret'] = sanitize_text_field($input['burstsms_api_secret']);
                }

                if (isset($input['burstsms_from_number'])) {
                    $input['burstsms_from_number'] = sanitize_text_field($input['burstsms_from_number']);
                }

                break;

            case 'nexmo':
                if (isset($input['nexmo_api_key'])) {
                    $input['nexmo_api_key'] = sanitize_text_field($input['nexmo_api_key']);
                }

                if (isset($input['nexmo_api_secret'])) {
                    $input['nexmo_api_secret'] = sanitize_text_field($input['nexmo_api_secret']);
                }

                if (isset($input['nexmo_from'])) {
                    $input['nexmo_from'] = sanitize_text_field($input['nexmo_from']);
                }

                break;

            case 'voodoosms':
                if (isset($input['voodoosms_api_username'])) {
                    $input['voodoosms_api_username'] = sanitize_text_field($input['voodoosms_api_username']);
                }

                if (isset($input['voodoosms_api_password'])) {
                    $input['voodoosms_api_password'] = sanitize_text_field($input['voodoosms_api_password']);
                }

                if (isset($input['voodoosms_from'])) {
                    $input['voodoosms_from'] = sanitize_text_field($input['voodoosms_from']);
                }

                break;
        }

        return $input;
    }

    public function validate_options_sms_templates($input)
    {
        if (function_exists('wc_get_order_statuses')) {
            $statuss = wc_get_order_statuses();
        } else {
            $statuss = cb_wcsmspro()->core->default_order_statuss;
        }

        foreach ($statuss as $field_id => $field) {
            if (array_key_exists($field_id, $input)) {
                $input[$field_id] = sanitize_textarea_field($input[$field_id]);
            } else {
                $input[$field_id] = '';
            }
        }

        if (isset($input['new-order-placed'])) {
            $input['new-order-placed'] = sanitize_textarea_field($input['new-order-placed']);
        } else {
            $input['new-order-placed'] = '';
        }

        if (isset($input['admin-new-order-placed'])) {
            $input['admin-new-order-placed'] = sanitize_textarea_field($input['admin-new-order-placed']);
        } else {
            $input['admin-new-order-placed'] = '';
        }

        return $input;
    }

    public
    static function display_settings_page()
    {
        if (!current_user_can('manage_options')) return;
        // check if form is submitted
        if (isset($_POST['wcsmspro_options_sms_sender']['to'])) {
            do_action('wcsmspro_send_sms');
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <?php
            if (!cb_wcsmspro()->is_woocommerce_active()) {
                add_settings_error('wcsmspro_options_general', 'wcsmspro_options_general', 'Woo SMS Pro requires WooCommerce to be activated.');
            }
            settings_errors();
            $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
            ?>
            <h2 class="nav-tab-wrapper">
                <a href="<?php echo admin_url('admin.php?page=woo-sms-pro&tab=general'); ?>"
                   class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>">
                    General
                </a>
                <a href="<?php echo admin_url('admin.php?page=woo-sms-pro&tab=sms-gateway'); ?>"
                   class="nav-tab <?php echo $active_tab == 'sms-gateway' ? 'nav-tab-active' : ''; ?>">
                    SMS Gateway
                </a>
                <a href="<?php echo admin_url('admin.php?page=woo-sms-pro&tab=sms-sender'); ?>"
                   class="nav-tab <?php echo $active_tab == 'sms-sender' ? 'nav-tab-active' : ''; ?>">
                    SMS Sender
                </a>
                <a href="<?php echo admin_url('admin.php?page=woo-sms-pro&tab=sms-templates'); ?>"
                   class="nav-tab <?php echo $active_tab == 'sms-templates' ? 'nav-tab-active' : ''; ?>">
                    SMS Templates
                </a>
            </h2>
            <?php
            switch ($active_tab) {
                case 'general':
                    echo '<form method="post" action="options.php">';
                    settings_fields('wcsmspro_options_general');
                    do_settings_sections('wcsmspro_section_general');
                    submit_button();
                    echo '</form>';
                    break;


                case 'sms-gateway':
                    echo '<form method="post" action="options.php">';
                    settings_fields('wcsmspro_options_sms_gateway');
                    do_settings_sections('wcsmspro_section_sms_gateway');
                    ?>
                    <hr>
                    <?php
                    foreach (self::$_sms_providers as $provider_id => $provider_name) {
                        echo "<div id='wcsmspro_section_sms_gateway_{$provider_id}' class='hidden'>";
                        do_settings_sections("wcsmspro_section_sms_gateway_{$provider_id}");
                        echo "</div>";
                    }
                    submit_button();
                    echo '</form>';
                    break;


                case 'sms-sender':
                    ?>
                    <form method="post"
                          action="<?php echo admin_url('admin.php?page=woo-sms-pro&tab=sms-sender'); ?>">
                        <?php do_settings_sections('wcsmspro_section_sms_sender'); ?>
                        <table class="form-table">
                            <tr>
                                <th scope="row"></th>
                                <td><?php submit_button('Send Now'); ?></td>
                            </tr>
                        </table>
                    </form>
                    <?php
                    break;


                case 'sms-templates':
                    echo '<form method="post" action="options.php">';
                    settings_fields('wcsmspro_options_sms_templates');
                    do_settings_sections('wcsmspro_section_sms_templates');
                    ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row"></th>
                            <td><?php submit_button(); ?></td>
                        </tr>
                    </table>
                    <?php
                    echo '</form>';
                    break;
            }
            ?>
        </div>
        <?php
    }
}