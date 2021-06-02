<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

/* Define routes from config */
foreach (Config::get('sqms-servers.routes.def') as $definition) {
    /* Create the definitor as an anonymous function */
    $define = function() use ($definition) {
        $type = Arr::get($definition, 'type', 'get');

        Route::$type(Arr::get($definition, 'path', '/'), [Arr::get($definition, 'controller'), Arr::get($definition, 'executor', 'show')])->middleware(Arr::get($definition, 'middlewares'))->name(Arr::get($definition, 'name'));
    };

    if (Arr::get($definition, 'localized', false)) {
        Route::localized(function() use ($define) {
            $define();
        });
    } else {
        $define();
    }
}