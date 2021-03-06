<?php

namespace App\Http\Controllers\organizations\police;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\citizen\Profile;
use App\User;

class PoliceAdminController extends Controller
{
   public function __construct(){
    	$this->middleware('auth');
	    $this->middleware('backbutton');
	    $this->middleware('admin:Police');
    }

    public function index(){
    	return view('organizations.police.admin.home');
    }

    public function new_user(){
    	return view('organizations.police.admin.new_user');
    }

    public function user_post(Request $request){
    	$org_usr = new User;
    	$org_usr->email = $request->email;
	    $org_usr->phone = $request->phone;
	    $org_usr->password = bcrypt('pass');
	    $org_usr->cin_fk = $request->cin;
	    $org_usr->work_area = 'Police';
	    $org_usr->type = 'User';
	    $org_usr->status = 1;
	    $org_usr->region_id = auth()->user()->region_id;
	    $org_usr->zone_id = auth()->user()->zone_id;
	    $org_usr->wereda_id = auth()->user()->wereda_id;
	    $org_usr->city_id = auth()->user()->city_id;
	    $org_usr->kebele_id = auth()->user()->kebele_id;
	    $org_usr->organization_id = auth()->user()->org_id;
	    if($org_usr->save()){
	    	return redirect()->route('Police_Admin_User_List');
	    }
    }

    public function user_list(){
    	$users = User::where('work_area' , 'Police')
                  ->where('type' , 'User')
                  ->where('organization_id' , auth()->user()->organization_id)->get();
    	return view('organizations.police.admin.user_list' , compact('users'));
    }

    public static function get_name($cin){
    $pro = Profile::where('cin' , $cin)->first();
    $name = $pro->first_name . ' ' . $pro->middle_name ;
    return $name;
  }

  public static function get_police($id){
    
  }
}
