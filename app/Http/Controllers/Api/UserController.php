<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
                $success['token'] = $user->createToken('MyApp')->accessToken;
                $success['user_id'] = $user->id;
                return response()->json(['success' => $success], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;

        $success['user_id'] = $user->id;
        return response()->json(['success' => $success], 200);
    }

    public function userDetail()
    {
        $user = Auth::user();
        return response()->json(new UserResource($user));
    }
}
