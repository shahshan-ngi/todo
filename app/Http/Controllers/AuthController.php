<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\RegisterationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
                return redirect(route("login"))->with('error', 'Invaild Credentials')->withInput();
               
            }
        }catch(\Exception $e){
            return redirect(route("login"))->with('error', $e->getMessage())->withInput();
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
           
            $user=User::createUser($request);

 
            Auth::login($user);
            // Mail::to('shahshan@nextgeni.com')
            // ->cc(['shahshan871@gmail.com'])
            // ->send(new RegisterationMail($user));
            return redirect(route("todos.index"))->with('success', 'Registered successfully');
        }catch(\Exception $e){
          
            return redirect(route("todos.register"))->with('error',$e->getMessage())->withInput();
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
