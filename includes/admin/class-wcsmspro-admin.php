<?php

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

class WCSMSPRO_ADMIN
{
    public function load_settings()
    {
        $objects = array();
        $objects['settings'] = new WCSMSPRO_ADMIN_SETTINGS();
        $objects['main_menu'] = new WCSMSPRO_ADMIN_MENU();
        return $objects;
    }
}