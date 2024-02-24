<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Core\Services\Interfaces\IUtilityService;

class RegistrationController extends Controller
{
use HttpResponses;
    private IUtilityService $utilityService;

    public function __construct(IUtilityService $utilityService)
    {
        $this->utilityService = $utilityService;
    }
    public function RegisterUser(Request $request){

         $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'home_address' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:255',
            'role' => 'required|integer|in:1,2,3',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8|same:password',
        ]);
        $phonenumber = $request->phone_number;
        if(!$this->utilityService->VerifyPhoneNumber($phonenumber)){
            return $this->error("Invalid Phone Number");
        }
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->home_address = $request->home_address;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = Hash::make($request->password);
        $user->phone_number = $request->phone_number;
        $user->name = $request->first_name." ".$request->last_name;
        $user->save();

        return $this->success("User Created Successfully");
    }
    //
}
