<?php

// exit if file is called directly
if (!defined('ABSPATH')) {
    exit;
}

class WCSMSPRO_Autoloader
{
    private $include_path = '';

    public function __construct()
    {
        if (function_exists("__autoload")) {
            spl_autoload_register("__autoload");
        }

        spl_autoload_register(array($this, 'autoload'));

        $this->include_path = untrailingslashit(WCSMSPRO_BASEPATH) . '/includes/';
    }

    private function get_file_name_from_class($class)
    {
        return 'class-' . str_replace('_', '-', $class) . '.php';
    }

    private function load_file($path)
    {
        if ($path && is_readable($path)) {
            include_once($path);
            return true;
        }
        return false;
    }

    public function autoload($class)
    {
        $class = strtolower($class);

        if (0 !== strpos($class, 'wcsmspro_')) {
            return;
        }

        $file = $this->get_file_name_from_class($class);
        $path = '';

        if (0 === strpos($class, 'wcsmspro_admin')) {
            $path = $this->include_path . 'admin/';
        } elseif (0 === strpos($class, 'wcsmspro_provider')) {
            $path = $this->include_path . 'provider/';
        }

        if (empty($path) || !$this->load_file($path . $file)) {
            $this->load_file($this->include_path . $file);
        }
    }

}

new WCSMSPRO_Autoloader();