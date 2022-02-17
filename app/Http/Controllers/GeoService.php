<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\ZipCode;

class GeoService extends Controller
{
    function getCoordinatesForZips(string $zipCodes) : JsonResponse
    {
        $zipCodes = explode(',', $zipCodes);

        $zipCodes = ZipCode::whereIn('zip_code', $zipCodes)->get(['zip_code', 'latitude', 'longitude']);

        $arrayKeys = [];

        // There are multiple coordinates for single zip codes. We only need one, filter them here.
        $zipCodes = array_filter($zipCodes->toArray(), function($zip) use (&$arrayKeys) {
            if (in_array($zip['zip_code'], $arrayKeys)) {
                return false;
            }

            $arrayKeys[] = $zip['zip_code'];

            return  true;
        });

        // Reset the indexes from 0
        $zipCodes = array_values($zipCodes);

        return response()->json($zipCodes);
    }
}
