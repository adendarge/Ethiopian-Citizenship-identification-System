<?php

namespace App\Http\Controllers\kebele;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\citizen\Profile;
use App\User;
use DB;

class KebeleAdminController extends Controller{

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('admin:Kebele');
  }

  public function index(){
    return view('kebele.admin.home');
  }

  public function user_management_view(){
    return view('kebele.admin.user_management', compact('kebeles'));
  }

  public function new_user_post(Request $request){
    //validation
    $this->validate($request,[
      'email' => 'required|email',
      'cin' => 'required',
    ]);

    //insert into table
    $user = new User;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->password = bcrypt("pass");
    $user->cin_fk = $request->cin;
    $user->work_area = "Kebele";
    $user->type = "User";
    $user->status = 1;
    $user->region_id = auth()->user()->region_id;
    $user->zone_id = auth()->user()->zone_id;
    $user->wereda_id = auth()->user()->wereda_id;
    $user->city_id = auth()->user()->city_id;
    $user->kebele_id = auth()->user()->kebele_id;
    $user->organization_id = 0;
    $user->save();
    return redirect()->route('Kebele_Admin_User_List');
  }

  public function user_list(){
    $users = User::where('work_area' , 'Kebele')
                  ->where('type' , 'User')
                  ->where('kebele_id' , auth()->user()->kebele_id)->get();
    return view('kebele.admin.user_list', compact('users'));
  }

  public function enable($id){
    DB::table('users')->where('id', $id)->update(['status' => 1]);
    return redirect()->route('Kebele_Admin_User_List');
  }
public function disable($id){
    DB::table('users')->where('id', $id)->update(['status' => 2]);
    return redirect()->route('Kebele_Admin_User_List');
  }
public function delete($id){
    DB::table('users')->where('id', $id)->update(['status' => 5]);
    return redirect()->route('Kebele_Admin_User_List');
  }

  public function get_admins(){
    $users = User::where('work_area' , 'Kebele')
                  ->where('type' , 'Admin')
                  ->where('city_id' , auth()->user()->city_id)
                  ->whereNotIn('status', [5])->get();
    return view('city.admin.kebele_list' , compact('users'));
  }

  public static function get_name($cin){
    $pro = Profile::where('cin' , $cin)->first();
    $name = $pro->first_name . ' ' . $pro->middle_name ;
    return $name;
  }

}
