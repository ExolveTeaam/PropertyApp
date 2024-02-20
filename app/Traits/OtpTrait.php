<?php 
namespace App\Traits;

use Carbon\Carbon;
use App\Models\Otp;
use App\Core\Enums\OtpStatusEnum;
use App\Core\Enums\OtpPurposeEnum;

trait OtpTrait
{
    public function generateOtp(int $user_id, OtpPurposeEnum $purpose) : Otp
    {       
        Otp::where([
            "user_id" => $user_id,
            "status" => OtpStatusEnum::Pending,
            "purpose" => $purpose
        ])->update(["status" => OtpStatusEnum::Cancelled]);

        $code = mt_rand(1000, 9999);
        if($purpose == OtpPurposeEnum::Email){
          $code =  mt_rand(100000, 999999);
        }

        $otp = new Otp();
        $otp->code = $code;
        $otp->user_id = $user_id;
        $otp->status = OtpStatusEnum::Pending;
        $otp->purpose = $purpose;
        $otp->save();
        
        return $otp;
    }

    public function isOtpExpired(Otp $otp): bool
    {
        $is_expired = true;

        $now = Carbon::now();
        $timeDifference = $now->diffInMinutes($otp->created_at);

        if($timeDifference <= 15){
            $is_expired = false;
        }

        return $is_expired;
    }
}