<?php

namespace App\Http\Controllers\Users;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CredentialController extends Controller
{
    public function sendResetMail(string $mail = null){

        if(is_null($mail)){
            $user = User::where('id', Auth::user()->id)->first();
        }else{
            $user = User::where('email',$mail);
            if($user->count() != 1){
                return response()->json([],500);
            }
            $user = $user->first();
        }

        $token = Str::uuid();
        Mail::to($user)->send(new ResetPasswordMail($token));
        $user->token = $token;
        $user->save();

        if(Auth::check()){
            event(new Notify('Un mail a été envoyé',1));
        }

        return 'oK';

    }

    public function tokenVerify(string $uuid){
        $req = User::where('token', $uuid);
        if($req->count() == 1){
            $user = $req->first();
            $user->token = null;
            Auth::login($user);
            Session::push('user_grade', $user->GetGrade);
            return redirect('/reset');
        }
        return 'error';
    }

    public function changepass(request $request){
        $user = User::where('id',Auth::user()->id)->first();
        if($request->psw != $request->pswrep){
            event(new Notify('Les deux mots de passes ne sont pas les mêmes',3));
        }
        $user->token = null;
        $user->password = Hash::make($request->psw);
        $user->save();
        event(new Notify('Mot de passe modifié',1));
        $redirect = '';
        if(!$user->GetGrade->perm_1){
            Auth::logout();
            $redirect = '/login';
        }else{
            $redirect = '/';
        }
        return response()->json(['redirect'=>$redirect],201);

    }
}
