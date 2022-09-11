<?php

namespace JaimeCores\WeatherPackage\Tests\Unit;

use JaimeCores\WeatherPackage\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JaimeCores\WeatherPackage\Models\Forecast;
use JaimeCores\WeatherPackage\Models\Guest;

class GuestTest extends TestCase
{
    use RefreshDatabase;

    /* Test a guest who has requested a forecast */
    function testAGuestHasAForecast()
    {
        // Create forecast
        $forecast = Forecast::create([
            'location' => 'London', 
            'date' => '2022-09-10' , 
            'forecast' => '[{"icon": "01n", "dt_txt": "2022-09-11 18:00:00", "description": "clear sky"}]'
        ]);

        // Create a guest who has requested a forecast
        $guest = Guest::create([
            'ip' => '123.211.61.50',
            'datetime'  => date('Y-m-d H:i:s'),
            'forecast_id'  => $forecast->id,
        ]);

        
        // This guest has requested a forecast
        $this->assertCount(1, $guest->forecast()->get());

        tap($guest->forecast()->first(), function ($forecast) use ($guest) {
            $this->assertEquals('London', $forecast->location);
            $this->assertEquals('2022-09-10', $forecast->date);
            $this->assertEquals('[{"icon": "01n", "dt_txt": "2022-09-11 18:00:00", "description": "clear sky"}]', $forecast->forecast);
           # $this->assertTrue($forecast->guests->is($guest));
        });
    }


}