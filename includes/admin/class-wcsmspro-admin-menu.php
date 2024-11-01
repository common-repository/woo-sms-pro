<?php

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

class WCSMSPRO_ADMIN_MENU
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'main_menu'));
        add_filter('plugin_action_links_' . WCSMSPRO_SLUG, array($this, 'action_links'));
    }

    public function main_menu()
    {
        add_menu_page(
            'Woo SMS Pro',
            'Woo SMS Pro',
            'manage_options',
            'woo-sms-pro',
            array($this, 'display_settings_page'),
            'dashicons-testimonial',
            null
        );
    }

    public function action_links($links)
    {
        if (defined('WCSMSPRO_BASEPATH')) {
            $settings_link = '<a href="admin.php?page=woo-sms-pro">' . __('Settings') . '</a>';
            array_push($links, $settings_link);
        }
        return $links;
    }

    public function display_settings_page()
    {
        WCSMSPRO_ADMIN_SETTINGS::display_settings_page();
    }
}