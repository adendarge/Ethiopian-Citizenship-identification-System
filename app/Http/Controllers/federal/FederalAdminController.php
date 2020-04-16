<?php

namespace App\Http\Controllers\federal;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminMiddleware;
use App\Model\region\Region;
use App\Model\citizen\Profile;
use App\User;
use Session;
use DB;



class FederalAdminController extends Controller{

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('admin:Federal');
  }

  public function index(){
    return view('federal.admin.home');
  }

  public function new_user_view(){
    $users = User::where('work_area','Federal')
                  ->where('type','User')->get();
    $regions = DB::table("region")->get();
    $regions=$regions->toArray();
    return view('federal.admin.user_management' , compact('users','regions'));
  }

  public function register_region(Request $request){
    echo 'test case region';
    $this->validate($request , [

    ]);

    $regions = User::all();
    $region = new User;
    $region->email = $request->email;
    $region->phone = $request->phone;
    $region->password = bcrypt('pass');
    $region->cin_fk = $request->cin;
    $region->work_area = 'Region';
    $region->type = 'Admin';
    $region->status = 1;
    $region->region_id = $request->region;
    $region->zone_id = 0;
    $region->wereda_id = 0;
    $region->city_id = 0;
    $region->kebele_id = 0;
    $region->organization_id = 0;
    foreach ($regions as $reg) {
      if(($reg->region_id == $request->region && $reg->work_area == 'Region') && ($reg->status == 1 || $reg->status == 2)){
        Session::flash('exist' , 'Admin With The Selected Region Already Exist! Please Delete It To Create New!!');
        return redirect()->route('Federal_Admin_Region_All');
      }
    }
    if($region->save()){
      Session::flash('store' , 'The Data Was Successfully Stored!!');
      return redirect()->route('Federal_Admin_Region_All');
    }
    
  }

  public function enable($id){
    DB::table('users')->where('id', $id)->update(['status' => 1]);
    return redirect()->route('Federal_Admin_Region_All');
  }
public function disable($id){
    DB::table('users')->where('id', $id)->update(['status' => 2]);
    return redirect()->route('Federal_Admin_Region_All');
  }
public function delete($id){
    DB::table('users')->where('id', $id)->update(['status' => 5]);
    return redirect()->route('Federal_Admin_Region_All');
  }

  public function get_admins(){
    $users = User::where('work_area' , 'Region')
                  ->where('type' , 'Admin')
                  ->whereNotIn('status', [5])->get();
    return view('federal.admin.region_list' , compact('users'));
  }

  public static function get_name($cin){
    $pro = Profile::where('cin' , $cin)->first();
    $name = $pro->first_name . ' ' . $pro->middle_name ;
    return $name;
  }

  public static function get_region($id){
    return Region::where('id' , $id)->first()->region_name;
  }

  public static function get_zone($id){
    return Zone::where('id' , auth()->user()->zone_id)->first()->zone_name;
  }

  public static function get_wereda($id){
    return Wereda::where('id' , auth()->user()->wereda_id)->first()->wereda_name;
  }

  public static function get_city($id){
    return City::where('id' , auth()->user()->city_id)->first()->city_name;
  }

  public static function get_kebele($id){
    return Kebele::where('id' , auth()->user()->kebele_id)->first()->kebele_name;
  }
 public static function mapsearch(){
    return view('front');
  }




































  public function ajax_search(Request $request){
    if($request->ajax()){
      echo  'test case ' . $request->user . ' <br>' . $request->filter . '<br>' . $request->admin;
    }else{
      echo 'not ajax';
    }

  }

 

}
