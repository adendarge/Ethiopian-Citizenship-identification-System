<?php

namespace App\Http\Controllers\kebele;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\citizen\Cin;
use App\Model\city\City;
use App\Model\citizen\Profile;
use App\Model\citizen\Family;
use App\Model\citizen\Education;
use App\Model\citizen\Insurance;
use App\Model\citizen\BiologicalInformation;
use App\Http\Controllers\CinNumberController;
use App\Model\kebele\RecordBirth;
use App\Model\kebele\RecordMarriage;
use App\Model\kebele\RecordDivorce;
use App\Model\kebele\RecordDeath;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Image;
use Session;


class ProfileController extends Controller{

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('user:Kebele');
  }


  public function citizen_registration_post(Request $request){

    $this->validate($request,[
      'first_name' => 'required|string',
      'middle_name' => 'required|string',
      'last_name' => 'required|string',
      'gender' => 'required',
      'nationality' => 'required',
      'occupation' => 'required',
      'ethinicity' => 'required',
      'birth_date' => 'required',
      'birth_place' => 'required',
      'current_address' => 'required',
      'house_number' => 'required',
      'marital_status' => 'required',
      'religion' => 'required',
      'front_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
      'side_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
      'eye_color' => 'required',
      'skin_color' => 'required',
      'hair' => 'required',
      'height' => 'required',
    ]);


// if($request->hasFile('front_image')){
//   echo '<script>alert("if if");</script>';
//   return view('kebele.user.certificate.marriage');
// }else{
//   echo '<script>alert("else else");</script>';
//   return view('kebele.user.certificate.divorce');
// }
      $city_id = auth()->user()->city_id;
      $city = City::where('id' , $city_id)->first();

      $cin_obj = new CinNumberController($city->city_name);
      $cin = $cin_obj->cin_getter();

      $profile = new Profile;
      $profile->cin = $cin;
      $profile->first_name = $request->first_name;
      $profile->middle_name = $request->middle_name;
      $profile->last_name = $request->last_name;
      $profile->gender = $request->gender;
      $profile->nationality = $request->nationality;
      $profile->occupation = $request->occupation;
      $profile->ethinicity = $request->ethinicity;
      $profile->birth_date = $request->birth_date;
      $profile->birth_place = $request->birth_place;
      $profile->current_address = $request->current_address;
      $profile->house_number = $request->house_number;
      $profile->mobile_number = $request->mobile_number;
      $profile->marital_status = $request->marital_status;
      $profile->religion = $request->religion;
      $profile->kebele_id = auth()->user()->kebele_id;
      $profile->emergency_contact_name = $request->emergency_contact_name;
      $profile->emergency_contact_phone = $request->emergency_contact_phone;
      $profile->emergency_house_number = $request->emergency_house_number;
      $profile->remark = $request->remark;
      $profile->status = 1;
      $profile->crime_status = 0;//wanted or clean
      $profile->due_date_of_issue = date('Y-m-d H:i:s');
      $profile->digital_signature = "signature";

      $img_front = $request->front_image;
      $img_side = $request->side_image;
      $img_front_name = $cin . '_front.' . $img_front->getClientOriginalExtension();
      $img_side_name = $cin . '_side.' . $img_side->getClientOriginalExtension();
      $path = '/photos/';
      $profile->image_front = $path.$img_front_name;
      $profile->image_side = $path.$img_side_name;
      $img_front_make = Image::make($img_front)->resize(300,200);
      $img_side_make = Image::make($img_side)->resize(300,200);
      Storage::disk('public')->put($path . $img_front_name , $img_front_make->stream());
      Storage::disk('public')->put($path . $img_side_name , $img_side_make->stream());


      $log_name = $cin . '.log';
      $log_path = 'logs/';
      $profile->activity_log = $log_path.$log_name;
      $log = auth()->user()->name . ' has registered ' . $request->first_name . ' ' . $request->middle_name . ' for the first time on ' . date('m-d-y');
      Storage::put($log_path.$log_name, $log);


      $profile->save();


// Biological Info Table
      $biological = new BiologicalInformation;
      $biological->cin_fk = $cin;
      $biological->eye_color = $request->eye_color;
      $biological->skin_color = $request->skin_color;
      $biological->hair = $request->hair;
      $biological->height = $request->height;
      $biological->blood_type = $request->blood_type . $request->rh;
      $biological->finger_print = $request->finger_print;
      $biological->disability = $request->disability;
      $biological->save();



//Family Table
      $len = sizeof($request->relation);

      for ($i=0; $i < $len; $i++) {
        $family = new Family;
        $family->cin_fk = $cin;
        if($request->relation[$i] == "" || $request->relation[$i] == null)
          break;
        $family->relation = $request->relation[$i];
        $family->full_name = $request->full_name[$i];
        $family->family_cin_fk = $request->cin[$i];
        $family->status = 1;
        $family->save();
      }





//Education Table
      $len = sizeof($request->institution);

      for ($i=0; $i < $len; $i++) {
        $education = new Education;
        $education->cin_fk = $cin;
        if($request->institution[$i] == "" || $request->institution[$i] == null)
          break;
        $education->institution = $request->institution[$i];
        $education->qualification = $request->qualification[$i];
        $education->level = $request->level[$i];
        $education->entry_date =  $request->entry_date[$i];
        $education->exit_date =  $request->exit_date[$i];
        $education->status = 1;

//handle this part
        $education->evidence = "path to file";
        $education->save();
     }





      $len = sizeof($request->insurance_companey);

      for ($i=0; $i < $len; $i++) {
        $insurance = new Insurance;
        $insurance->cin_fk = $cin;
        if($request->insurance_companey[$i] == "" || $request->insurance_companey[$i] == null)
          break;
        $insurance->insurance_companey = $request->insurance_companey[$i];
        $insurance->insurance_number = $request->insurance_number[$i];
        $insurance->insurance_policy = $request->insurance_policy[$i];
        $insurance->status = 1;
        $insurance->save();
      }

      $cin_info = new Cin;
      $cin_info->cin_fk = $cin;
      $cin_info->due_date_of_issue = date('Y-m-d H:i:s');
      $cin_info->kebele_id_fk = auth()->user()->kebele_id;
      $cin_info->status = 1;
      $cin_info->save();
    Session::flash('profile_message' , 'The Following Citizen Has Been Successfully Stored. This Request Is Temporary. It Needs Further Operations');
    return redirect()->route('Kebele_User_Registered_Profile',array($cin));
     // return redirect()->route('Kebele_User_Registered_Profile',compact($cin));
  }


}
