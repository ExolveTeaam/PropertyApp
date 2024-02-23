<?php


namespace App\Core\Services\Interfaces;

interface IUtilityService {
    public function VerifyPhoneNumber(string $phoneNumber): bool;
}
