<?php

namespace JaimeCores\WeatherPackage\Tests\Feature;

use JaimeCores\WeatherPackage\Tests\TestCase;

class ForecastTest extends TestCase
{
    /** Test a list of IP addresses and get the forecast */
    function testIPAddressesForecast()
    {
        // Current date and time
        $datetime = date('Y-m-d H:i:s');

        // IP addresses to check against
        $ips = [
            "123.211.61.50",
            "122.62.248.72",
            "23.19.62.102",
            "105.225.185.20",
            "80.62.117.27",
            "68.96.102.16",
            "103.242.39.92",
            "156.0.201.255"
        ];

        // Test all IP addresses
        foreach($ips as $ip){
            // Set request
            $request = [
                'ip' => $ip, 
                'datetime' => $datetime, 
                'api' => true
            ];

            // Request to get the forecast for the given ip
            $response =$this->json('POST', route('forecast.store'), $request, ['Accept' => 'application/json']);

            // 200 HTTP status code
            $response->assertStatus(200);

            // Response contains the following structure
            $response->assertJsonStructure([
                [
                    'dt_txt',
                    'description',
                    'icon'
                ]
            ]);
        }
    }

    /** Test a wrong ipv4 */
    function testIncorrectIPForecast()
    {
        // Set request
        $request = [
            'ip' => "test.211.61.50", 
            'datetime' => date('Y-m-d H:i:s'), 
            'api' => true
        ];

        // Request to get the forecast for the given ip
        $response =$this->json('POST', route('forecast.store'), $request, ['Accept' => 'application/json']);

        // 400 HTTP status code
        $response->assertStatus(400);        
    }

    /** Test a wrong date */
    function testIncorrectDateForecast()
    {
        // Set request
        $request = [
            'ip' => "123.211.61.50", 
            'datetime' => "test-9-10 02:25:13", 
            'api' => true
        ];

        // Request to get the forecast for the given ip
        $response =$this->json('POST', route('forecast.store'), $request, ['Accept' => 'application/json']);

        // 400 HTTP status code
        $response->assertStatus(400);        
    }

}