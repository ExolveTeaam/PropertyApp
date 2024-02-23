<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    use HttpResponses;
    //
    public function GetcontactDetials(Request $request){
        $contact = Settings::first();

        return $this->success("Contact Details Retrieved Successfully", [
            'twitter' => $contact->twitter,
            'facebook' => $contact->facebook,
            'instagram' => $contact->instagram,
            'phone' => $contact->phone,
            'email' => $contact->email,
            'linkedin' => $contact->linkedin

        ]);
}

}
