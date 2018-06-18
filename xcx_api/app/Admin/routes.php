<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('quotes', QuoteController::class);
    $router->resource('per_images', PerImageController::class);
    $router->resource('wxusers', WxUserController::class);
    $router->resource('power_records', PowerRecordController::class);
    $router->resource('product_images', ProductImageController::class);
    $router->post('add_powers', 'PowerController@add');
});
