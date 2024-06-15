<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;

class TranslationController extends Controller
{
    /**
     * @throws \JsonException
     */
    public function index(): string
    {
        $lang = config('app.locale');

        $strings = Cache::rememberForever('lang_' . $lang . '.js', static function () use ($lang) {
            $files = [
                resource_path('lang/' . $lang . '/crud.php'),
                resource_path('lang/' . $lang . '/global.php'),
            ];
            $strings = [];

            foreach ($files as $file) {
                $name = basename($file, '.php');
                $strings[$name] = require $file;
            }

            return $strings;
        });

        header('Content-Type: text/javascript');
        echo('i18n = ' .json_encode($strings, JSON_THROW_ON_ERROR). ';');
        exit();
    }
}
