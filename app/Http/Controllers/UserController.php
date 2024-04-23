<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;



class UserController extends Controller
{
    public function register( RegisterRequest $request)
    {
       
       $request -> validated(); 

       $userData = [
        'nom' => $request->nom,
        'prenom' => $request->prenom,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'numero_tel' => $request->numero_tel,
        'type' => $request->type,
    ];
    print($userData);


    $user = User::create($userData);
    $token = $user->createToken('forumapp')->plainTextToken;

    return response(
        [
            'user' => $user,
            'token' => $token,
        ],201
    );
    }

    public function login(LoginRequest $request)
    {
        
        $request->validated();

        $user = User::whereEmail($request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
          
            return response([
                'message' => 'Invalid credentials'
            ], 422);
        }
    
        $token = $user->createToken('forumapp')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 200);
    }
}
