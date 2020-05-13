<?php

use PrestaShop\PrestaShop\Adapter\ServiceLocator;

class Module extends ModuleCore
{
    protected static function coreLoadModule($module_name)
    {
        // Store time and memory before and after hook call and save the result in the database
        if (Configuration::get('DEV_TOOLS_PROFILER') && !defined('_PS_ADMIN_DIR_')) {
            $time_start = microtime(true);
            $memory_start = memory_get_usage(true);
        }

        include_once _PS_MODULE_DIR_ . $module_name . '/' . $module_name . '.php';

        $r = false;
        if (Tools::file_exists_no_cache(_PS_OVERRIDE_DIR_ . 'modules/' . $module_name . '/' . $module_name . '.php')) {
            include_once _PS_OVERRIDE_DIR_ . 'modules/' . $module_name . '/' . $module_name . '.php';
            $override = $module_name . 'Override';

            if (class_exists($override, false)) {
                $r = self::$_INSTANCE[$module_name] = ServiceLocator::get($override);
            }
        }

        if (!$r && class_exists($module_name, false)) {
            $r = self::$_INSTANCE[$module_name] = ServiceLocator::get($module_name);
        }

        if (Configuration::get('DEV_TOOLS_PROFILER') && !defined('_PS_ADMIN_DIR_')) {
            $time_end = microtime(true);
            $memory_end = memory_get_usage(true);

            if (!isset($GLOBALS['perfs'])) {
                $GLOBALS['perfs'] = [];
            }

            $GLOBALS['perfs'][] = [
                'module' => $module_name,
                'method' => '__construct',
                'start' => $time_start,
                'end' => $time_end,
                'memory_start' => $memory_start,
                'memory_end' => $memory_end,
            ];
        }

        return $r;
    }
}