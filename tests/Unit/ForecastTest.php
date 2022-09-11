<?php

namespace JaimeCores\WeatherPackage\Tests\Unit;

use JaimeCores\WeatherPackage\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JaimeCores\WeatherPackage\Models\Forecast;
use JaimeCores\WeatherPackage\Models\Guest;

class ForecastTest extends TestCase
{
    use RefreshDatabase;

    /* Test to create a Forecast */
    function testCreateForecast()
    {
        // Create forecast
        $forecast = Forecast::create([
            'location' => 'London', 
            'date' => '2022-09-10' , 
            'forecast' => '[{"icon": "01n", "dt_txt": "2022-09-11 18:00:00", "description": "clear sky"}]'
        ]);
        $this->assertEquals('London', $forecast->location);
        $this->assertEquals('2022-09-10', $forecast->date);
        $this->assertEquals('[{"icon": "01n", "dt_txt": "2022-09-11 18:00:00", "description": "clear sky"}]', $forecast->forecast);
    }

    /* Test a forecast which has been requested from multiple guests */
    function testAForecastHasManyGuests()
    {
        // Create forecast
        $forecast = Forecast::create([
            'location' => 'London', 
            'date' => '2022-09-10' , 
            'forecast' => '[{"icon": "01n", "dt_txt": "2022-09-11 18:00:00", "description": "clear sky"}]'
        ]);

        // Current date and time
        $datetime = date('Y-m-d H:i:s');

        // This forecast has been requested from a guest
        $forecast->guests()->create([
            'ip' => '123.211.61.50',
            'datetime'  => $datetime,
        ]);

         // This forecast has been requested from other guest
        $forecast->guests()->create([
            'ip' => '122.62.248.72',
            'datetime'  => $datetime,
        ]);

        // This forecast has been requested from 2 guests
        $this->assertCount(2, $forecast->guests()->get());

        tap($forecast->guests()->first(), function ($guest) use ($forecast, $datetime) {
            $this->assertEquals('123.211.61.50', $guest->ip);
            $this->assertEquals($datetime, $guest->datetime);
            $this->assertTrue($guest->forecast->is($forecast));
        });
    }


}