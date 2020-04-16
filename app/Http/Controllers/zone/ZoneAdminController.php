<?php

namespace App\Http\Controllers\zone;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\region\Region;
use App\Model\zone\Zone;
use App\Model\wereda\Wereda;
use App\Model\citizen\Profile;
use App\User;
use Session;
use DB;

class ZoneAdminController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('admin:Zone');
  }

  public function index(){
    return view('zone.admin.home');
  }

  public function new_user_view(){
    $users = User::where('work_area','Were')
                  ->where('type','User')->get();
    $weredas = DB::table("wereda")->where('zone_id' , '=' , auth()->user()->zone_id)->get();
    $weredas=$weredas->toArray();
    return view('zone.admin.user_management' , compact('users','weredas'));
  }

  public function register_wereda(Request $request){
    echo 'test case region';
    $this->validate($request , [

    ]);

    $weredas = Wereda::all();
    $wereda = new User;
    $wereda->email = $request->email;
    $wereda->phone = $request->phone;
    $wereda->password = bcrypt('pass');
    $wereda->cin_fk = $request->cin;
    $wereda->work_area = 'Wereda';
    $wereda->type = 'Admin';
    $wereda->status = 1;
    $wereda->region_id = auth()->user()->region_id;
    $wereda->zone_id = auth()->user()->zone_id;
    $wereda->wereda_id = $request->wereda;
    $wereda->city_id = 0;
    $wereda->kebele_id = 0;
    $wereda->organization_id = 0;
     foreach ($weredas as $wer) {
      if(($wer->wereda_id == $request->wereda && $wer->work_area == 'Wereda') && ($wer->status == 1 || $wer->status == 2)){
        Session::flash('exist' , 'Admin With The Selected Wereda Already Exist! Please Delete It To Create New!!');
        return redirect()->route('Zone_Admin_Wereda_All');
      }
    }
    if($wereda->save()){
      Session::flash('store' , 'The Data Was Successfully Stored!!');
      return redirect()->route('Zone_Admin_Wereda_All');
    }
    
  }

  public function enable($id){
    DB::table('users')->where('id', $id)->update(['status' => 1]);
    return redirect()->route('Zone_Admin_Wereda_All');
  }
public function disable($id){
    DB::table('users')->where('id', $id)->update(['status' => 2]);
    return redirect()->route('Zone_Admin_Wereda_All');
  }
public function delete($id){
    DB::table('users')->where('id', $id)->update(['status' => 5]);
    return redirect()->route('Zone_Admin_Wereda_All');
  }



  public function get_admins(){
    $users = User::where('work_area' , 'Wereda')
                  ->where('type' , 'Admin')
                  ->where('zone_id' , auth()->user()->zone_id)
                  ->whereNotIn('status', [5])->get();
    return view('zone.admin.wereda_list' , compact('users'));
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
    return City::where('id', $id)->first()->city_name;
  }

  public static function get_kebele($id){
    return Kebele::where('id', $id)->first()->kebele_name;
  }
 public static function mapsearch(){
    return view('front');
  }
}
