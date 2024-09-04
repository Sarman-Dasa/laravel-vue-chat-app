<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function list(Request $request) {
        $user  = User::whereNot('id', auth()->id())->get();

        return view('dashboard',[
            'users' => $user
        ]);
    }

    public function saveToken(Request $request)
    {
        auth()->user()->update(['device_token'=>$request->token]);
        return response()->json(['token saved successfully.']);
    }
}
