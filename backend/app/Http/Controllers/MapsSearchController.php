<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapsSearchController extends Controller
{
    public function searchByLocation(Request $request) {
        try {
            header('Access-Control-Allow-Origin', '*');
            header('Content-Type', 'application/json');
            header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, DELETE, OPTIONS');
            $curl = curl_init();
            curl_setopt_array($curl, array(
//                CURLOPT_URL => 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input='. $request['location'].'&inputtype=textquery&fields=formatted_address%2Cname%2Crating%2Copening_hours%2Cgeometry&key=' . env('GOOGLE_MAPS_KEY'),
//                CURLOPT_URL => 'https://maps.googleapis.com/maps/api/place/textsearch/json?query='. $request['location'].'&key=' . env('GOOGLE_MAPS_KEY'),
                CURLOPT_URL => 'https://maps.googleapis.com/maps/api/place/queryautocomplete/json?input='. urlencode($request['location']).'&key=' . env('GOOGLE_MAPS_KEY') . '&location=' . $request['lat'] . ',' . $request['lng'] .'&radius=10000',
//                https://maps.googleapis.com/maps/api/place/queryautocomplete/json?input=cairo&key=AIzaSyBtwuPEM2cVeJ6U5AIrVYhE-uTVGR7S0oo&location=30.9302491,29.8942701
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            return response()->json([
                'data' => json_decode($response)
            ]);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function searchByPlaceId(Request $request) {
        try {
            header('Access-Control-Allow-Origin', '*');
            header('Content-Type', 'application/json');
            header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, DELETE, OPTIONS');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://maps.googleapis.com/maps/api/place/details/json?input='. urlencode($request['location']) .'&placeid=' . $request['place_id'] . '&key=' . env('GOOGLE_MAPS_KEY'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            return response()->json([
                'data' => json_decode($response),
                'url' => urlencode($request['location'])
            ]);
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
}
