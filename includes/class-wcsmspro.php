<?php

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

class WCSMSPRO
{
    public
        $provider = null,
        $admin_settings = null,
        $core = null;

    protected static $_instance = null;

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Private constructor
     *
     */
    private function __construct()
    {
        $this->includes();
        $this->init_objects();
    }

    private function includes()
    {
        include_once(WCSMSPRO_BASEPATH . 'includes/class-wcsmspro-autoloader.php');
    }

    private function init_objects()
    {
        // active provider
        $this->provider = WCSMSPRO_PROVIDER_FACTORY::getInstance();

        // admin settings
        if (is_admin()) {
            $admin = new WCSMSPRO_ADMIN();
            $this->admin_settings = $admin->load_settings();
        }

        // core libraries
        $this->core = new WCSMSPRO_CORE();
    }

    public function __clone()
    {
        // do nothing
    }

    public function __wakeup()
    {
        // do nothing
    }

    /**
     * Check if WooCommerce is active
     **/
    public function is_woocommerce_active()
    {
        $is_active = false;
        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            $is_active = true;
        }
        return $is_active;
    }

    public function is_plugin_active()
    {
        $is_active = false;
        $general_options = get_option('wcsmspro_options_general');
        $gateway_options = get_option('wcsmspro_options_sms_gateway');
        $is_active = empty($general_options['enable_plugin']) ? false : true;
        if ($is_active)
            $is_active = !$this->provider->is_provider_active() ? false : true;
        if ($is_active)
            $is_active = !$this->is_woocommerce_active() ? false : true;

        return $is_active;
    }

}
