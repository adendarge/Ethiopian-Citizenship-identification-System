<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserRedirectionController;
use App\User;

class LoginController extends Controller{

  public function __construct(){
    $this->middleware('guest')->except('logout');
  }

//this method will return the login
    public function index(){
      return view('login');
    }

//this method will make sure if the user is in the database and logs the user in
  public function post_login(Request $request){
//validation rules are listed here
    $this->validate($request,[
      'email' => 'required|email',
      'password' => 'required',
    ]);
//the core authentication is carried out here
    $email = $request->email;
    $password = $request->password;
    if(auth()->attempt(['email'=>$email, 'password'=>$password])){
      $user = User::where('email',$request->email)->first();
//this will get the correct route corresponding to the loged in user
      $route = new UserRedirectionController($user);
      auth()->login($user);
      return redirect()->route($route->get_route());
    }else
      return back()->with('error','your username and password are wrong.');;
  }

  public function logout(){
    auth()->logout();
    return redirect(\URL::previous());
  }

}
