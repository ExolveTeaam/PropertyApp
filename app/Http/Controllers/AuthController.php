<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;
    //
    public function Login(Request $request){
        $request->validate([
            'phone_number' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
           return $this->error("Invalid Login Credentials");
        }

        $tokenResult = $user->createToken('Personal Access Token');
        return $this->success("Login Successfull",[
            'token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => $tokenResult->token->expires_at
        ]);
    }

    public function Logout(Request $request){
        $request->user()->token()->revoke();
        return $this->success("Logout Successfull");
    }
}
