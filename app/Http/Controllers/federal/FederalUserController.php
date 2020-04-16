<?php

namespace App\Http\Controllers\federal;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FederalUserController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('user:Federal');
  }

  public function index(){
    return view('federal.user.home');
  }

}
