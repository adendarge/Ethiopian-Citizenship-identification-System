<?php

namespace App\Http\Controllers\kebele;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\citizen\Profile;
use App\Model\citizen\Family;
use App\Model\citizen\Education;
use App\Model\citizen\Insurance;
use App\Model\citizen\BiologicalInformation;
use Session;
use QrCode;
use Image;

class UpdateController extends Controller{

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('user:Kebele');
  }

  public function profile(Request $request , $cin){
    $this->validate($request,[
      'first_name' => 'required|string',
      'middle_name' => 'required|string',
      'last_name' => 'required|string',
    ]);

    $profile = Profile::where('cin', $cin)->first();
    $profile->first_name = $request->first_name;
    $profile->middle_name = $request->middle_name;
    $profile->last_name = $request->last_name;
    $profile->save();
    Session::flash('profile_update' , 'This Profile Has Been Updated Successfully With New Name');
    return redirect()->route('Kebele_User_Registered_Profile' , array('cin'=>$cin));
  }

  public function family(Request $request , $cin){
    $len = sizeof($request->relation);
    for ($i=0; $i < $len; $i++) {
      $family = new Family;
      $family->cin_fk = $cin;
      if($request->relation[$i] == "" || $request->relation[$i] == null)
        break;
      $family->relation = $request->relation[$i];
      $family->full_name = $request->full_name[$i];
      $family->family_cin_fk = $request->cin[$i];
      $family->status = 2;
      $family->save();
    }
      Session::flash('family_update' , 'This Profile Has Been Updated Successfully With New Family Member');
      return redirect()->route('Kebele_User_Registered_Profile' , array('cin'=>$cin));
  }

  public function education(Request $request , $cin){
    $education = new Education;
    $education->cin_fk = $cin;
    $education->institution = $request->institution[$i];
    $education->qualification = $request->qualification[$i];
    $education->level = $request->level[$i];
    $education->entry_date =  $request->entry_date[$i];
    $education->exit_date =  $request->exit_date[$i];
    $education->status = 2;
    Session::flash('education_update' , 'This Profile Has Been Updated Successfully With New Education Profile');
    return redirect()->route('Kebele_User_Registered_Profile' , array('cin'=>$cin));
  }

  public function insurance(Request $request , $cin){
    $len = sizeof($request->insurance_company);
    for ($i=0; $i < $len; $i++) {
      $insurance = new Insurance;
      $insurance->cin_fk = $cin;
      if($request->insurance_company[$i] == "" || $request->insurance_company[$i] == null)
        break;
      $insurance->insurance_companey = $request->insurance_company[$i];
      $insurance->insurance_number = $request->insurance_number[$i];
      $insurance->insurance_policy = $request->insurance_policy[$i];
      $insurance->status = 2;
      $insurance->save();
    }
      Session::flash('insurance_update' , 'This Profile Has Been Updated Successfully With New Insurance Profile');
      return redirect()->route('Kebele_User_Registered_Profile' , array('cin'=>$cin));
  }

  public function qr(){
    echo public_path();
    $qr = QrCode::format('png')->size(200)->color(255,12,45)->backgroundColor(255,255,0)->margin(1)->generate('CIN:'.'000'.'Name:'.
                                                                                                               'fname'.' '.'lname'
                                                                                                               .'Link:'.'http://localhost/kebel/home'
                                                                                                             ,public_path());
      $img = Image::make($qr);
    echo '<img src="data:image/png;base64,'. base64_encode($qr).'">';
  }

}
