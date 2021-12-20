<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['register', 'login']);
    } 

     public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'     => 'required|string|max:255',
            'password'     => 'required|string|max:255',
            'first_name'     => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'telephone'     => 'required|string|max:255',
            'profile_image'     => 'required|string|max:255',
            'address'     => 'required|string|max:255',
            'city'     => 'required|string|max:255',
            'province' => 'required',
            'country' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $user = User::create([
            'username'      => $request->username,
            'password'  => Hash::make($request->password),
            'first_name'      => $request->first_name,
            'last_name'      => $request->last_name,
            'telephone'      => $request->telephone,
            'profile_image'      => $request->profile_image,
            'address'      => $request->address,
            'city'      => $request->city,
            'province'      => $request->province,
            'country'      => $request->country,
            
        ]);

        $token = JWTAuth::fromUser($user);

        if($user) {
            return response()->json([
                'success' => true,
                'user'    => $user,  
                'token'   => $token  
            ], 201);
        }

        return response()->json([
            'success' => false,
        ], 409);
    }

     public function login(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'username'    => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $credentials = $request->only('username', 'password');

        if(!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Username or Password is incorrect'
            ], 401);
        }
        return response()->json([
            'success' => true,
            'user'    => auth()->guard('api')->user(),  
            'token'   => $token   
        ], 201);
    }
    
    /**
     * getUser
     *
     * @return void
     */
    public function getUser()
    {
        return response()->json([
            'success' => true,
            'user'    => auth()->user()
        ], 200);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    
}
