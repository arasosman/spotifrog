<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{

    public function __construct()
    {

    }


    public function getProfile(Request $request){
        $user = Auth::user();
        return view('pages.admin.profile',['the_user' => $user]);
    }

    public function setProfile(Request $request){
        if(!($request->has("new_user_name") && $request->has("new_user_email")))
            return;
        $user = User::find(Auth::user()->id);
        $user->name = $request->input('new_user_name');
        $user->email = $request->input('new_user_email');
        if($request->has("new_user_password"))
            $user->password = $request->input("new_user_password");
        $user->save();
        session(['account_update_success' => true]);
        return redirect()->back();
    }

}
