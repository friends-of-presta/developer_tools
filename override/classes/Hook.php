<?php

class Hook extends HookCore
{
    public static function coreCallHook($module, $method, $params)
    {
        if (!isset($GLOBALS['perfs'])) {
            $GLOBALS['perfs'] = [];
        }

        // Immediately return the result if we do not log performances
        if (!Configuration::get('DEV_TOOLS_PROFILER') && !defined('_PS_ADMIN_DIR_')) {
            return $module->{$method}($params);
        }

        // Store time and memory before and after hook call and save the result in the database
        $time_start = microtime(true);
        $memory_start = memory_get_usage(true);

        // Call hook
        $hookCallResult = $module->{$method}($params);

        $time_end = microtime(true);
        $memory_end = memory_get_usage(true);

        $GLOBALS['perfs'][] = [
            'module' => $module->name,
            'method' => $method,
            'start' => $time_start,
            'end' => $time_end,
            'memory_start' => $memory_start,
            'memory_end' => $memory_end,
        ];

        return $hookCallResult;
    }
}
