<?php

namespace App\Core\Services\Implementations;
use App\Core\Services\Interfaces\IUtilityService;

 class UtilityService implements IUtilityService{

    public function VerifyPhoneNumber(string $phoneNumber): bool{
        //verify phone number

        if(strlen($phoneNumber) != 14 || substr($phoneNumber, 0, 4) != '+234' || !is_numeric(substr($phoneNumber, 4))){
            return false;
        }
        return true;
    }

}
