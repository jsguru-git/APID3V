<?php

namespace App\Services;

use App\Models\Business;

class BusinessBioGeneratorService
{

    public static function generateBio(Business $business): string
    {
        $businessName = $business->name;
        $categoryName = $business->categories()->firstOrFail()->name;
        $cityName = self::getCityName($business);
        $countDayOfWeek = $business->businessHours()->count();

        return "$businessName is a $categoryName in $cityName open $countDayOfWeek days per week";
    }

    public static function getCityName(Business $business): string
    {
        $lat = $business->lat;
        $lng = $business->lng;

        $fileContent = file_get_contents( storage_path('all_cities.json'));

        $jsonCities = json_decode($fileContent, true);

        $minDistance = null;
        $cityName = null;
        foreach ($jsonCities as $jsonCity) {

            $distanceX = $jsonCity['coordinate']['lat'] - $lat;
            $distanceY = $jsonCity['coordinate']['lng'] - $lng;
            $distance = sqrt($distanceX * $distanceX + $distanceY * $distanceY);
            if ($minDistance === null || ($minDistance !== null && $distance < $minDistance)) {
                $minDistance = $distance;
                $cityName = $jsonCity['name'];
            }
        }
        return $cityName;
    }
}