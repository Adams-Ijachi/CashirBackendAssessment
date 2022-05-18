<?php

namespace App\Http\Service;
use Illuminate\Support\Facades\Http;
class LocationService
{
    protected $api_key;
    protected $places_url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/';

    public function __construct()
    {
        $this->api_key = config('app.google_map_api_key');
    }
 

    public function getUserLocation($location)
    {
        $long =6.553013072976993;
        $lat = 3.3870773855973972;

      

        // get locations 1km radius from user location logitude and latitude
        $response = Http::get($this->places_url.'json?location='.$long."&".$lat.'&radius=1000&type=restaurant&key='.$this->api_key);

        dd($response->json());
       
        
    }
}