<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function register(Request  $request): \Illuminate\Http\JsonResponse
    {
        $pseudo = $request->pseudo;
        $mail = $request->email;
        $psw = $request->psw;
        if($user = \App\Models\User::where('email', $mail)->count() != 0){
            return response()->json('Email alrady taken');
        }else{
            $uuid = \Illuminate\Support\Str::uuid();
            $createuser = new \App\Models\User();
            $createuser->name = $pseudo;
            $createuser->email = $mail;
            $createuser->password = Hash::make($psw);
            $createuser->uuid = $uuid;
            $createuser->save();
            $newuser = \App\Models\User::where('email', $mail)->first();
            Auth::login($newuser);
            if(Auth::check()){
                return response()->json([
                    'status' => 'User created',
                    'user' => $newuser,
                    'authed' => true,
                ], 201);
            }else{
                return response()->json([
                   'status' => 'auth error',
                   'user' => null,
                   'authed' => false,
                    'check' => Auth::check(),
                ], 200);
            }


        }
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $email = $request->email;
        $psw = $request->psw;




        if($user = \App\Models\User::where('email', $email)->count() == 0){
            return response()->json([
                'status'=> 'adresse mail non existante',
                'user' => null,
                'authed' => false,
            ], 202);
        }else{
            $user= User::where('email', $email)->first();
             if(Hash::check($psw, $user->password)){
                Auth::login($user);
                return response()->json([
                    'status'=>'OK',
                    'user'=>$user,
                    'authed'=>Auth::check(),
                ]);
            }else{
                return response()->json([
                    'status'=>'Mot de passe invalide',
                    'user' => null,
                    'authed'=>false
                ], 202);
            }
        }
    }

    public function getUser(Request $request): \Illuminate\Http\JsonResponse
    {
        $users = User::all();
        return response()->json([
            'status'=>'OK',
            'users'=>$users,
        ]);
    }

    public function setusergrade(Request $request, $id, $userid): \Illuminate\Http\JsonResponse
    {
        $user= User::where('id', $userid)->first();
        $user->grade = $id;
        $user->save();
        return \response()->json(['status'=>'OK']);
    }

    public function checkConnexion(): \Illuminate\Http\JsonResponse
    {
        if(Auth::user()){
            return response()->json(['session'=>true]);
        }
        return response()->json(['session'=>false]);
    }

}
