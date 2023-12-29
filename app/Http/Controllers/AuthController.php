<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin;
use App\Models\agent;
use App\Models\superstockez;
use App\Models\stockez;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use validator;

class AuthController extends Controller
{
    public function login(request $request)
    {

        return view('login');
    }


    public function UserLogin(Request $request)
    {
        $username = $request->userId;
        $password = $request->password;


        $admin = Admin::where('username', $username)->first();
        if ($admin) {
            $storedPassword = $admin->password;
            $decryptedPassword = $storedPassword;
            if ($password === $decryptedPassword) {
                session(['user_type' => 'admin', 'user_id' => $admin->id]);
                return redirect('index')->with('success', 'logged in successfully');
            } else {
                return redirect('/')->with('error', 'wrong password');
            }
        }

        $superstockez = Superstockez::where('username', $username)->first();
        if ($superstockez) {
            $storedPassword = $superstockez->password;
            $decryptedPassword = $storedPassword;
            if ($password === $decryptedPassword) {
                session(['user_type' => 'superstockez', 'user_id' => $superstockez->id]);
                return redirect('index')->with('success', 'logged in successfully');
            } else {
                return redirect('/')->with('error', 'wrong password');
            }
        }


        $stockez = Stockez::where('username', $username)->first();
        if ($stockez) {
            $storedPassword = $stockez->password;
            $decryptedPassword = $storedPassword;
            if ($password === $decryptedPassword) {
                session(['user_type' => 'stockez', 'user_id' => $stockez->id]);
                return redirect('index')->with('success', 'logged in successfully');
            } else {
                return redirect('/')->with('error', 'wrong password');
            }
        }


        return redirect('/')->with('error', 'user not found');
    }

    public function UserLogOut(Request $request)
    {
        session()->flush();
        return redirect('/')->with('success', 'log out Successfully');
    }


public function apilogout(Request $request){
            
}
    



    public function api(Request $request)
    {

        $username = $request->input('username');
        $password = $request->input('password');
        $device = $request->input('device');
        $os = $request->input('os');
        $ip = $request->input('ip');

        $user = agent::where('username', $username)->first();
        if ($user) {

            if ($password == $user->password) {
                if ($user->device != $device) { 

                    $user->loginstatus = 0;
                    $user->device = $device;
                    $user->os = $os;
                    $user->machine_ip = $ip; 
                    $user->save();
                    return response()->json(['status' => false, 'message' => 'You Need approval first', 'loginstatus' => $user->loginstatus]);

                } else {
                    if ($user->loginstatus == 1) {
                        if ($user->status == 1) {
                            $token = $user->createToken('user-auth')->plainTextToken;
                            $user->onstatus = 1;
                            $user->os = $os;
                            $user->machine_ip = $ip; 
                            $user->save();
                            return response()->json(['status' => true, 'userId' => $user->username, 'balance' => $user->credit, 'token' => $token, 'message' => 'Login Successfull', 'activestatus' => $user->status]);
                        } else {
                            return response()->json(['status' => false, 'message' => 'You are Banned', 'loginstatus' => $user->loginstatus, 'activestatus' => $user->status]);
                        }
                    }else if ($user->loginstatus == 2){
                        $user->os = $os;
                        $user->machine_ip = $ip;
                        $user->save();
                        return response()->json(['status' => false, 'message' => 'You can\'t login, You are blocked', 'loginstatus' => $user->loginstatus]);
                    } else {
                        $user->loginstatus = 0;
                        $user->device = $device;
                        $user->os = $os;
                        $user->machine_ip = $ip; 
                        $user->save();
                        return response()->json(['status' => false, 'message' => 'You Need approval first', 'loginstatus' => $user->loginstatus]);
                    }
                }

            } else {

                return response()->json(['message' => 'Invalid password'], 401);
            }
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }


    public function sanity(Request $request)
    {


        return response('Sanity Check', 200)
            ->header('Content-Type', 'text/plain');

    }


}

