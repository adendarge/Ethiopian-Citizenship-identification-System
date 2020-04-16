<?php

namespace App\Http\Controllers\wereda;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\region\Region;
use App\Model\zone\Zone;
use App\Model\wereda\Wereda;
use App\Model\city\City;
use App\Model\citizen\Profile;
use App\User;
use Session;
use DB;



class WeredaAdminController extends Controller{

   public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('admin:Wereda');
  }

  public function index(){
    return view('wereda.admin.home');
  }

  public function new_user_view(){
    $users = User::where('work_area','City')
                  ->where('type','User')->get();
    $cities = DB::table("city")->where('wereda_id' , '=' , auth()->user()->wereda_id)->get();
    $cities=$cities->toArray();
    return view('wereda.admin.user_management' , compact('users','cities'));
  }

  public function register_city(Request $request){
    echo 'test case region';
    $this->validate($request , [

    ]);

    $cities = User::all();
    $city = new User;
    $city->email = $request->email;
    $city->phone = $request->phone;
    $city->password = bcrypt('pass');
    $city->cin_fk = $request->cin;
    $city->work_area = 'City';
    $city->type = 'Admin';
    $city->status = 1;
    $city->region_id = auth()->user()->region_id;
    $city->zone_id = auth()->user()->zone_id;
    $city->wereda_id = auth()->user()->wereda_id;
    $city->city_id = $request->city;
    $city->kebele_id = 0;
    $city->organization_id = 0;
     foreach ($cities as $ct) {
      if(($ct->city_id == $request->city && $ct->work_area == 'City') && ($ct->status == 1 || $ct->status == 2)){
        Session::flash('exist' , 'Admin With The Selected City Already Exist! Please Delete It To Create New!!');
        return redirect()->route('Wereda_Admin_City_All');
      }
    }
    if($city->save()){
      Session::flash('store' , 'The Data Was Successfully Stored!!');
      return redirect()->route('Wereda_Admin_City_All');
    }
    
  }

  public function enable($id){
    DB::table('users')->where('id', $id)->update(['status' => 1]);
    return redirect()->route('Wereda_Admin_City_All');
  }
public function disable($id){
    DB::table('users')->where('id', $id)->update(['status' => 2]);
    return redirect()->route('Wereda_Admin_City_All');
  }
public function delete($id){
    DB::table('users')->where('id', $id)->update(['status' => 5]);
    return redirect()->route('Wereda_Admin_City_All');
  }



  public function get_admins(){
    $users = User::where('work_area' , 'City')
                  ->where('type' , 'Admin')
                  ->where('wereda_id' , auth()->user()->wereda_id)
                  ->whereNotIn('status', [5])->get();
    return view('wereda.admin.city_list' , compact('users'));
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
    return Zone::where('id' , $id)->first()->zone_name;
  }

  public static function get_wereda($id){
    return Wereda::where('id' , $id)->first()->wereda_name;
  }

  public static function get_city($id){
    return City::where('id' , $id)->first()->city_name;
  }

  public static function get_kebele($id){
    return Kebele::where('id' , $id)->first()->kebele_name;
  }
 public static function mapsearch(){
    return view('front');
  }

}
