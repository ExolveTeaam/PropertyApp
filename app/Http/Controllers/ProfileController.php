<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    //

    public function ChangePassword(Request $request){
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();
        if (!Hash::check($request->old_password, $user->password)) {
            return $this->error("Invalid Old Password");
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->success("Password Changed Successfully");
    }

    public function UpdateProfile(Request $request){
        $request->validate([
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'home_address' => 'string|max:255',
            'email' => 'email|unique:users,email,'.$request->user()->id,
            'phone_number' => 'string|max:255',
        ]);

        $user = $request->user();
        $user->first_name = $request->first_name ?? $user->first_name;
        $user->last_name = $request->last_name ?? $user->last_name;
        $user->home_address = $request->home_address ?? $user->home_address;
        $user->email = $request->email ?? $user->email;
        $user->phone_number = $request->phone_number ?? $user->phone_number;
        $user->save();

        return $this->success("Profile Updated Successfully");
    }
}
