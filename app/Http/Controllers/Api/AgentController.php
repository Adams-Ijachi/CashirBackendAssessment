<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function create(UserRegisterRequest $request)
    {
        try{
         
            $user = User::create($request->validated());
            $user->assignRole(User::AGENT_ROLE);
           
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

    public function index()
    {
        try {
            $users = User::role(User::AGENT_ROLE)->paginate();
            return  UserResource::collection($users);

        }  catch(\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
        
    }
}
