<?php

$tempCompiled = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'laporpras_views';
if (!is_dir($tempCompiled)) {
    @mkdir($tempCompiled, 0777, true);
}

return [

    /*
    |--------------------------------------------------------------------------
    | View Storage Paths
    |--------------------------------------------------------------------------
    |
    | Most templating systems load templates from disk. Here you may specify
    | an array of paths that should be checked for your views.
    |
    */

    'paths' => [
        resource_path('views'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled Blade templates will be
    | stored for your application. Using the system temp folder guarantees
    | full read/write permission without Windows filesystem lock issues.
    |
    */

    'compiled' => env('VIEW_COMPILED_PATH', $tempCompiled),

];
