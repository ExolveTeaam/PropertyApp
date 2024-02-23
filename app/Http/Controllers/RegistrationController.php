<?php

namespace App\Http\Controllers;

use App\Core\Services\Interfaces\IUtilityService;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        ]);

        if(!$this->utilityService->VerifyPhoneNumber($request->input()->phone_number)){
            return $this->error("Invalid Phone Number");
        }
        $user = new User();
        $user->first_name = $request->input()->first_name;
        $user->last_name = $request->input()->last_name;
        $user->home_address = $request->input()->home_address;
        $user->email = $request->input()->email;
        $user->role = $request->input()->role;
        $user->password = Hash::make($request->input()->password);
        $user->phone_number = $request->input()->phone_number;
        $user->save();

        return $this->success("User Created Successfully");
    }
    //
}
