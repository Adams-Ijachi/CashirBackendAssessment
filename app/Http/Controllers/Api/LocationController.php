<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Service\LocationService;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class LocationController extends Controller
{
    // get user ip add adress

    public function getUserLocation(Request $request, LocationService $locationService)
    {

        $locationService->getUserLocation($request);
        // $ip = $request->ip();
        // $ip = '162.159.24.227'; 
        // $currentUserInfo = Location::get($ip);
        // dd($currentUserInfo->latitude);
        
    }
}
