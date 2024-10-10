<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;

class AuthController extends Controller
{
    public function loginform(){
        try{
            return view('todos.login');
        }catch(\Exception $e){
            return redirect(route("login"))->with('error', 'Something went wrong');
           
        }
    }

    public function login(LoginRequest $request){
        try{
           
            if(Auth::attempt($request->only('email', 'password'))){
                $request->session()->regenerate();
                return redirect(route("todos.index"))->with('success', 'Logged in successfully');
            }else{
                return redirect(route("login"))->with('error', 'Invaild Credentials');
               
            }
        }catch(\Exception $e){
            return redirect(route("login"))->with('error', 'Something went wrong');
        }
    }

    public function registerform(){
        try{
            return view('todos.register');
        }catch(\Exception $e){
            dd($e->getMessage());
        }
    }

    public function register(RegisterRequest $request){
        try{
         
            $request->password=Hash::make($request->password);
            dd($request->all());
            $user=User::createUser($request->all());
            if($request->file('profile_image')->isvalid()){
                $name=$request->file('profile_image')->getClientOriginalName();
                $filepath=$request->file('profile_image')->store("images/profile/{{$user->id}}/$name");
                dd($name);
            }
            Auth::login($user);
            return redirect(route("todos.index"))->with('success', 'Registered successfully');
        }catch(\Exception $e){
            return redirect(route("todos.register"))->with('error', 'Could not register, something went wrong please try again later');
        }
    }

    public function logout(){
        try{
            Auth::logout();
            return redirect(route("login"));
               
        }catch(\Exception $e){
            return redirect(route("todos.index"))->with('error', 'Could not logout, something went wrong please try again later');
        }
      
    }
}
