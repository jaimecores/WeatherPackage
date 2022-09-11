<?php

namespace JaimeCores\WeatherPackage\Http\Controllers;

use Illuminate\Http\Request;
use JaimeCores\WeatherPackage\Models\Guest;
use JaimeCores\WeatherPackage\Models\Forecast;
use Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ForecastController extends Controller
{
    public function index(Request $request)
    {
        // Get the IP
        $ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));

        // Set the current date and time
        $datetime = date('Y-m-d H:i:s');

        // Display the view
        return view('weatherpackage::forecast.index', [
            'ip' => $ip,
            'datetime' => $datetime
        ]);
    }

    public function store(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'ip' => 'required|ipv4',
            'datetime'  => 'required|date',
        ]);

        // Display an error if validation fails
        if($validator->fails()){
            abort (400, 'Validation Error.');
        }

        // Convert the datetime to a date
        $date = date('Y-m-d', strtotime($request->input('datetime')));

        // Get the IP
        $ip = $request->input('ip');

        // Get the location if it has not been cached previously
        $location = Cache::remember('location-'.$ip.'-'.$date, 86400, function () use ($ip) {

            // Get the location using Curl
            $loc = null;
            $url = 'http://ip-api.com/json/'.$ip;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec ($ch);

            $obj = json_decode($response);
            if ($obj->status == "success") {
                $loc = $obj->city;
            }
            curl_close ($ch);
            return $loc;
        });

        // Return a 500 error if the location has not been found
        if(!isset($location)){
            abort (500, 'We have not found the location for your IP.');
        }
        
        // Get and store the forecast if it has not been cached previously
        $forecast = Cache::remember('forecast-'.$location.'-'.$date, 86400, function () use ($location, $date) {

            // Get the forecast using HTTP Client (Guzzle HTTP client)+
            $url = "https://api.openweathermap.org/data/2.5/forecast?q=${location}&appid=".env('OPENWEATHERMAP_APP_KEY');
            $res = Http::get($url);
            if ($res->getStatusCode() == 200) {
                $resBody = $res->getBody();
                $obj = json_decode($resBody);

                $data = [];
                foreach($obj->list as $day){
                    $dayForecast = [
                        'dt_txt' => $day->dt_txt,
                        'description' => $day->weather[0]->description,
                        'icon' => $day->weather[0]->icon,
                    ];
                    array_push($data, $dayForecast);
                }

            }else{

                // Return a 500 error if the forecast has not been found
                abort ($res->getStatusCode(), 'We have not found the forecast for your location.');
            }

            // Store the forecast
            return Forecast::firstOrCreate([
                'location'     => $location,
                'date'         => $date,
                'forecast'     => json_encode($data),
            ]);
        });

        // Store the guest request
        $guest = $forecast->guests()->create([
            'ip' => $ip,
            'datetime' => $request->input('datetime'),
        ]);

        // Return a JSON file or display a view depending on the api parameter from the request
        $api = $request->input('api');
        if(isset($api) and ($api == true))
        {
            return response()->json(json_decode($forecast->forecast), 200);
        }
        else{
            return view('weatherpackage::forecast.show', ['location' => $location, 'forescastArray' => json_decode($forecast->forecast)]);
        }
    }
}