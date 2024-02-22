<?php

namespace App\Core\Services\Interfaces;

use App\Models\User;
use App\Utils\Dtos\BaseResponse;
use Illuminate\Http\JsonResponse;

 interface IFlutterWaveService {
    public function VerifyTransaction(string $transactionId) : BaseResponse;
    public function InitializeFLutterWavePayment(User $user, float $amount, string $paymentReference) : JsonResponse;
 }
