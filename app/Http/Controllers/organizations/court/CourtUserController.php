<?php

namespace App\Http\Controllers\organizations\court;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\organizations\CrimeRecord;
use App\Model\organizations\MedicalRecord;
use App\Model\citizen\Profile;
use App\Model\organizations\Organization;
use App\Model\citizen\BiologicalInformation;
use Session;
use Image;
use View;

class CourtUserController extends Controller{

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('user:Court');
  }

  public function index(){
    $records = CrimeRecord::where('org_fk', auth()->user()->organization_id)->get();
    return view('organizations.court.user.record_list' , compact('records'));
  }

  public function find_criminal(Request $request){
    $profiles = Profile::where('cin' , 'LIKE' , '%' . $request->cin . '%')->get();
    return View::make('organizations.court.user.home' , compact('profiles'));
  }

  public function crime_history($cin){
    $profile = Profile::where('cin' , $cin)->first();
    $crimes = CrimeRecord::where('cin_fk' , $cin)->get();
    $bio = BiologicalInformation::where('cin_fk' , $cin)->first();
    return View::make('organizations.court.user.criminal_history' , compact('crimes' , 'profile' , 'bio'));
  }

  public static function get_org($org_id){
    return Organization::where('id' , $org_id)->first();
  }

  public function crime_reg_form(){
    return view('organizations.court.user.crime_register');
  }

  public function crime_reg_post(Request $request){
    $crime = new CrimeRecord;
    $crime->cin_fk = $request->cin;
    $crime->accusation = $request->accusation;
    $crime->accusatory_description = $request->detail;
    $crime->org_fk = auth()->user()->organization_id;
    $crime->city_id_fk = auth()->user()->city_id;
    $crime->date = date('Y-m-d H:i:s');
    $crime->status = $request->status;
    $crime->remark = $request->remark;
    if($crime->save()){
      Session::flash('success' , 'The Data Is Inserted Successfully');
      return redirect()->route('Court_User_List');
    }
  }

  public function court_record_list(){
    $records = CrimeRecord::where('org_fk', auth()->user()->organization_id)->get();
    return view('organizations.court.user.record_list' , compact('records'));
  }

}
