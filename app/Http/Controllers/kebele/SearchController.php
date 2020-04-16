<?php

namespace App\Http\Controllers\kebele;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\citizen\Profile;


class SearchController extends Controller{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('user:Kebele');
  }

  public function find(Request $request){
    $input = $request->search_input;
    $profiles = Profile::where(function($query){
      $query->where('kebele_id',auth()->user()->kebele_id);
              })->where(function($query) use($input){
                $query->where('first_name' , 'LIKE' , '%' . $input . '%')
                ->orwhere('middle_name' , 'LIKE' , '%' . $input . '%')
                ->orwhere('last_name' , 'LIKE' , '%' . $input . '%')
                ->orwhere('cin' , 'LIKE' , '%' . $input . '%');
              })->get();
    return view('kebele.user.master_search' , compact('profiles'));
  }

}
