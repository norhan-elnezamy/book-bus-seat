<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Auth;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->only(['email', 'password']))) { 
            $user = new UserResource(Auth::user());
            return response()->json(['status'=>true , 'message' => 'logged in successfully .', 'user' => $user], 200);
        } else { 
            return response()->json(['error'=>'Unauthorized'], 401); 
        }
    }
}
