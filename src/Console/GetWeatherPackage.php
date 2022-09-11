<?php

namespace JaimeCores\WeatherPackage\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use JaimeCores\WeatherPackage\Http\Controllers\ForecastController;
use Illuminate\Http\Request;

class GetWeatherPackage extends Command
{
    protected $signature = 'weatherpackage:getweather {ip}';

    protected $description = '5-days weather forecast based on your IP';

    public function handle()
    {
        // Display a message
        $this->info('Getting the forecast based on your IP...');

        //Get the IP
        $ip = $this->argument('ip');

        // Current date and time
        $datetime = date('Y-m-d H:i:s');

        // Create the request
        $request = new Request();
        $request->setMethod('POST');
        $request->request->add(['ip' => $ip, 'datetime' => $datetime, 'api' => true]);

        // Resolve a class instance from the container
        $controller = app()->make('JaimeCores\WeatherPackage\Http\Controllers\ForecastController');

        // Call the controller and the method
        $response = app()->call([$controller, 'store'], ['request' => $request]);

        // Display the response
        $this->info($response);
    }

}