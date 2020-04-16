<?php

namespace App\Http\Controllers\region;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\region\Region;
use App\Model\zone\Zone;
use App\Model\citizen\Profile;
use App\User;
use Session;
use DB;



class RegionAdminController extends Controller
{
   public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('admin:Region');
  }

  public function index(){
    return view('region.admin.home');
  }

  public function new_user_view(){
    $users = User::where('work_area','Federal')
                  ->where('type','User')->get();
     $zones = DB::table("zone")->where('region_id' , '=' , auth()->user()->region_id)->get();
    $zones=$zones->toArray();
    return view('region.admin.user_management' , compact('users','zones'));
  }

  public function register_zone(Request $request){
    $this->validate($request , [

    ]);

    $zones = Zone::all();
    $zone = new User;
    $zone->email = $request->email;
    $zone->phone = $request->phone;
    $zone->password = bcrypt('pass');
    $zone->cin_fk = $request->cin;
    $zone->work_area = 'Zone';
    $zone->type = 'Admin';
    $zone->status = 1;
    $zone->region_id = auth()->user()->region_id; 
    $zone->zone_id = $request->zone;
    $zone->wereda_id = 0;
    $zone->city_id = 0;
    $zone->kebele_id = 0;
    $zone->organization_id = 0;
     foreach ($zones as $z) {
      if(($z->zone_id == $request->zone && $z->work_area == 'Zone') && ($z->status == 1 || $z->status == 2)){
        Session::flash('exist' , 'Admin With The Selected Zone Already Exist! Please Delete It To Create New!!');
        return redirect()->route('Region_Admin_Zone_All');
      }
    }
    if($zone->save()){
      Session::flash('store' , 'The Data Was Successfully Stored!!');
      return redirect()->route('Region_Admin_Zone_All');
    }
    
  }

  public function enable($id){
    DB::table('users')->where('id', $id)->update(['status' => 1]);
    return redirect()->route('Region_Admin_Zone_All');
  }


public function disable($id){
    DB::table('users')->where('id', $id)->update(['status' => 2]);
    return redirect()->route('Region_Admin_Zone_All');
  }
  
public function delete($id){
    $zone = zone::where('id' , $id)->first();
    // $zone->delete();
    echo $zone;
    DB::table('users')->where('id', $id)->update(['status' => 5]);
    return redirect()->route('Region_Admin_Zone_All');
  }


  public function get_admins(){
    $users = User::where('work_area' , 'Zone')
                  ->where('type' , 'Admin')
                  ->where('region_id' , auth()->user()->region_id)
                  ->whereNotIn('status', [5])->get();
    return view('region.admin.zone_list' , compact('users'));
  }

  public static function get_name($cin){
    $pro = Profile::where('cin' , $cin)->first();
    $name = $pro->first_name . ' ' . $pro->middle_name ;
    return $name;
  }

  public static function get_zone($id){
    return Zone::where('id' , $id)->first()->zone_name;
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
}
