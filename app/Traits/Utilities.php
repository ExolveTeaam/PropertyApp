<?php
namespace App\Traits;

use App\Models\User;
use App\Models\Country;

trait Utilities
{
    public function getcountrybycode(string $countrycode) : Country
    {
        $country = Country::where('country_code', $countrycode)->first();
        return $country;
    }

    public function generateUserHandles($firstName, $lastName): array
    {
        $suggestions = array();
        $suggestions[] = strtolower($firstName . substr($lastName, 0, 1));
        $suggestions[] = strtolower(substr($firstName, 0, 1) . $lastName);
        $suggestions[] = strtolower($firstName . $lastName);
        $suggestions[] = strtolower($firstName . '.' . $lastName);
        $suggestions[] = strtolower($firstName . rand(100, 999));
        $suggestions[] = strtolower($firstName . rand(1000, 9999));

        foreach ($suggestions as $key => $suggestion) {
            $existing = User::where('handle_name', $suggestion)->first();
            if ($existing) {
                $suggestions[$key] = strtolower($firstName . rand(100, 999));
            }
        }

        return $suggestions;
    }

    public function getCountrybyId(int $id) : string
    {
        $country = Country::find($id);
        if(!empty($country)){
        return $country->country_code;
        }else{
            return null;
        }
    }

    public function numberIsInvlaid($countrycode, $number): bool
    {
        $response = true;
        if($countrycode == "+234"){
            if(strlen($number) == 14){
                if($number[4] == "8" || $number[4] == "9" || $number[4] == "7" ){
                    if($number[5] == "1" ||$number[5] == "0" ){
                        $response = false;
                    }
                }
            }
        }

        if($countrycode == "+44"){
            if(strlen($number) == 13){
                $response = false;
            }
        }

        if($countrycode == "+1"){
            if(strlen($number) == 12){
                $response = false;
            }
        }

        return $response;
    }
}
