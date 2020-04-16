<?php

namespace App\Http\Controllers\city;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Model\citizen\Profile;
use App\Model\organizations\Organization;

class CityUserController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('user:City');
  }

  public function index(){
    return view('city.user.home');
  }

  public function org_reg_view(){
    return view('city.user.org-reg');
  }

  public function org_reg_post(Request $request){
    //validation

    //store data
    $org = new Organization;
    $org->org_name = $request->org_name;
    $org->type = $request->type;
    $org->ceo_cin_fk = $request->ceo_fk;
    $org->phone = $request->phone;
    $org->email = $request->email;
    $org->website = $request->website;
    $org->kebele_id = auth()->user()->kebele_id;
    $org->city_id = auth()->user()->city_id;
    $org->wereda_id = auth()->user()->wereda_id;
    $org->zone_id = auth()->user()->zone_id;
    $org->region_id = auth()->user()->region_id;
    $org->summery_file = 'some summery file';
    //$org->save();
    echo ' organization saved ' . $org;

    $admin = new User;
    $user = Profile::where('cin',$request->ceo_fk)->first();
    $admin->name = $user->first_name . ' ' . $user->last_name;
    $admin->email = $request->email;
    $admin->password = bcrypt('localhost');
    $admin->cin_fk = $request->ceo_fk;
    $admin->work_area = $request->type;
    $admin->type = "Admin";
    $admin->localization = 'en_us';
    $admin->kebele_id = auth()->user()->kebele_id;
    $admin->city_id = auth()->user()->city_id;
    $admin->wereda_id = auth()->user()->wereda_id;
    $admin->zone_id = auth()->user()->zone_id;
    $admin->region_id = auth()->user()->region_id;
    $admin->organization_id = $org->id;
    $admin->save();
    echo ' admin is save ' . $admin;


  }





}
