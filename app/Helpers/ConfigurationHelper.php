<?php

namespace App\Helpers;

use App\Models\GlobalConfiguration;
use App\Models\Task;

class ConfigurationHelper {
    public static function get(string $key, $default = null)
    {
        $config = GlobalConfiguration::where('key', $key)->first();

        if($config) {
            return $config->value;
        }

        return $default;
    }
}
