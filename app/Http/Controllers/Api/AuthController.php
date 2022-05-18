<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\{
    UserRegisterRequest,
    UserLoginRequest,
};

use Auth;


class AuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    {

        try{

            $user = User::create($request->validated());
            $user->assignRole(User::USER_ROLE);
           
            return (new UserResource($user))->additional(
                [
                    'message' => 'User created successfully',
                ],
                )->response()->setStatusCode(201);
            
        }
        catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

       
    }


    public function login(UserLoginRequest $request)
    {
        try{

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $token = $user->createToken('authToken')->plainTextToken;
    
    
            return (new UserResource($user))->additional([
                'message' => 'User logged in successfully',
                'token' => $token
            ]);
    

        }catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
      
    }


    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
     
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
