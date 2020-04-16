<?php

namespace App\Http\Controllers\city;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\region\Region;
use App\Model\zone\Zone;
use App\Model\wereda\Wereda;
use App\Model\city\City;
use App\Model\kebele\Kebele;
use App\Model\citizen\Profile;
use App\Model\organizations\Organization;
use App\User;
use Session;
use DB;

class CityAdminController extends Controller{

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('admin:City');
  }

  public function index(){
    return view('city.admin.home');
  }

  public function new_user_view(){
    $kebeles = DB::table("kebele")->where('city_id' , '=' , auth()->user()->city_id)->get();
    $kebeles=$kebeles->toArray();
    return view('city.admin.user_management' , compact('kebeles'));
  }

  public function register_kebele(Request $request){
    echo 'test case region';
    $this->validate($request , [

    ]);

    $kebels = Kebele::all();
    $kebele = new User;
    $kebele->email = $request->email;
    $kebele->phone = $request->phone;
    $kebele->password = bcrypt('pass');
    $kebele->cin_fk = $request->cin;
    $kebele->work_area = 'Kebele';
    $kebele->type = 'Admin';
    $kebele->status = 1;
    $kebele->region_id = auth()->user()->region_id;
    $kebele->zone_id = auth()->user()->zone_id;
    $kebele->wereda_id = auth()->user()->wereda_id;
    $kebele->city_id = auth()->user()->city_id;
    $kebele->kebele_id = $request->kebele;
    $kebele->organization_id = 0;
     foreach ($kebels as $keb) {
      if(($keb->kebele_id == $request->kebele && $keb->work_area='Kebele') && ($keb->status == 1 || $keb->status == 2)){
        Session::flash('exist' , 'Admin With The Selected Kebele Already Exist! Please Delete It To Create New!!');
        return redirect()->route('City_Admin_Kebele_All');
      }
    }
    if($kebele->save()){
      Session::flash('store' , 'The Data Was Successfully Stored!!');
      return redirect()->route('City_Admin_Kebele_All');
    }
    
  }

  public function enable($id){
    DB::table('users')->where('id', $id)->update(['status' => 1]);
    return redirect()->route('City_Admin_Kebele_All');
  }
public function disable($id){
    DB::table('users')->where('id', $id)->update(['status' => 2]);
    return redirect()->route('City_Admin_Kebele_All');
  }
public function delete($id){
    DB::table('users')->where('id', $id)->update(['status' => 5]);
    return redirect()->route('City_Admin_Kebele_All');
  }

  public function get_admins(){
    $users = User::where('work_area' , 'Kebele')
                  ->where('type' , 'Admin')
                  ->where('city_id' , auth()->user()->city_id)
                  ->whereNotIn('status', [5])->get();
    return view('city.admin.kebele_list' , compact('users'));
  }

  public function new_org_view(){
    $kebeles = DB::table("kebele")->where('city_id' , '=' , auth()->user()->city_id)->get();
    $kebeles=$kebeles->toArray();
    return view('city.admin.new_org' , compact('kebeles'));
  }

  public function org_post(Request $request){
    $this->validate($request , [

      ]);

    $org = new Organization;
    $org->org_name = $request->org_name;
    $org->ceo_cin_fk = $request->cin;
    $org->type = $request->type;
    $org->phone = $request->phone;
    $org->email = $request->email;
    $org->website = $request->web;
    $org->kebele_id = $request->kebele;
    $org->city_id = auth()->user()->city_id;
    $org->wereda_id = auth()->user()->wereda_id;
    $org->zone_id = auth()->user()->zone_id;
    $org->region_id = auth()->user()->region_id;
    $res = $org->save();
    $org_usr = new User;
    $org_usr->email = $request->email;
    $org_usr->phone = $request->phone;
    $org_usr->password = bcrypt('pass');
    $org_usr->cin_fk = $request->cin;
    $org_usr->work_area = $request->type;
    $org_usr->type = 'Admin';
    $org_usr->status = 1;
    $org_usr->region_id = auth()->user()->region_id;
    $org_usr->zone_id = auth()->user()->zone_id;
    $org_usr->wereda_id = auth()->user()->wereda_id;
    $org_usr->city_id = auth()->user()->city_id;
    $org_usr->kebele_id = $request->kebele;
    $org_usr->organization_id = $org->id;
    if($org->save() && $org_usr->save()){
      Session::flash('success' , 'The Data Has Been Successsfully Stored');
      return redirect()->route('City_Org_List');
    }
  }

  public function org_list(){
    $orgs = User::orwhere('work_area' , 'Police')
                  ->orWhere('work_area' , 'Court')
                  ->orWhere('work_area' , 'Medical')                  
                  ->where('type' , 'Admin')
                  ->where('city_id' , auth()->user()->city_id)->get();
    return view('city.admin.org_list' , compact('orgs'));
  }

  public static function get_name($cin){
    $pro = Profile::where('cin' , $cin)->first();
    $name = $pro->first_name . ' ' . $pro->middle_name ;
    return $name;
  }

  public static function get_org_name($id){
    return Organization::where('id' , $id)->first()->org_name;
  }

  public static function get_org_type($id){
    return Organization::where('id' , $id)->first()->type;
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
 
}
