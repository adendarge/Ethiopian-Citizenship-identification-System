<?php

namespace App\Http\Controllers\organizations\court;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\citizen\Profile;
use App\User;

class CourtAdminController extends Controller
{
  public function __construct(){
    	$this->middleware('auth');
	    $this->middleware('backbutton');
	    $this->middleware('admin:Court');
    }

    public function index(){
    	return view('organizations.court.admin.home');
    }

    public function new_user(){
    	return view('organizations.court.admin.new_user');
    }

    public function user_post(Request $request){
    	$org_usr = new User;
    	$org_usr->email = $request->email;
	    $org_usr->phone = $request->phone;
	    $org_usr->password = bcrypt('pass');
	    $org_usr->cin_fk = $request->cin;
	    $org_usr->work_area = 'Court';
	    $org_usr->type = 'User';
	    $org_usr->status = 1;
	    $org_usr->region_id = auth()->user()->region_id;
	    $org_usr->zone_id = auth()->user()->zone_id;
	    $org_usr->wereda_id = auth()->user()->wereda_id;
	    $org_usr->city_id = auth()->user()->city_id;
	    $org_usr->kebele_id = auth()->user()->kebele_id;
	    $org_usr->organization_id = auth()->user()->org_id;
	    if($org_usr->save()){
	    	return redirect()->route('Court_Admin_User_List');
	    }
    }

    public function user_list(){
    	$users = User::where('work_area' , 'Court')
                  ->where('type' , 'User')
                  ->where('organization_id' , auth()->user()->organization_id)->get();
    	return view('organizations.court.admin.user_list' , compact('users'));
    }

    public static function get_name($cin){
    $pro = Profile::where('cin' , $cin)->first();
    $name = $pro->first_name . ' ' . $pro->middle_name ;
    return $name;
  }
}
}
