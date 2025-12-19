<?php
// app/Core/App.php

require_once __DIR__ . '/../Config/config.php';
require_once CORE_PATH . '/Router.php';
require_once CORE_PATH . '/Controller.php';
require_once CORE_PATH . '/View.php';

class App
{
    public static function run()
    {
        $router = new Router();

        // Routes
        $router->get('/', 'CalculatorController@show');
        $router->post('/calculate', 'CalculatorController@calculate');

        $router->dispatch();
    }
}
