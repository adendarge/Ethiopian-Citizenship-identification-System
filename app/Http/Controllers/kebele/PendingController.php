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
use Response;
use Session;
use Nexmo;
use Qrcode;
use DB;



class PendingController extends Controller{

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('user:Kebele');
  }

  public function approve_profile($cin){
    $error_message = "";
    $profile = Profile::where('cin' , $cin)
                      ->where('status' , 1)->first();
    if($profile){
        $profile->status = 2;
        $profile->update();
      
    }else{
      Session::flash('exist','The Record Was Already Approved');
      return redirect()->route('Kebele_Pending_Profile');
    }


    $educations = Education::where('cin_fk' , $cin)->where('status' , 1)->get();
    if(!$educations->isEmpty()){
        foreach ($educations as $education) {
          $education->status = 2;
          $education->update();
        }
    }


    $familys = Family::where('cin_fk' , $cin)->where('status' , 1)->get();
    if(!$familys->isEmpty()){
        foreach ($familys as $family) {
          $family->status = 2;
          $family->update();
        }
    }


    $insurances = Insurance::where('cin_fk' , $cin)->where('status' , 1)->get();
    if(!$insurances->isEmpty()){
        foreach ($insurances as $insurance ) {
          $insurance->status = 2;
          $insurance->update();
        }
    }

    // $cin = Cin::where('cin_fk' , $cin)->where('status' , 1)->first();
    // $cin->status = 2;
    // $cin->update();
    try {
      Nexmo::message()->send([
          'to' => $profile->mobile_number,
          'from' => 'CIS',
          'text' => 'This is Ethiopian CIS To Inform You That You Can Collect Your ID Card'
      ]);
    } catch (Exception $e) {
      $error_message .= "Error Happens While Sending SMS\n" . $e->getMessage()."\n";
    }


    Session::flash('profile_approve','The Record Was Successfully Approved');
    return $this->printid($profile->cin , $error_message);
  }


  public function printid($cin , $errors){
    $profile = Profile::where('cin' , $cin)->first();
    $path_front = $profile->image_front;
    if(file_exists(storage_path() .'/app/public/' . $path_front))
    $img_front = (Storage::url($path_front));
    return view('kebele.user.print_id' , compact('profile','img_front' , 'errors'));
  }

  public function printid2($cin){
    $profile = Profile::where('cin' , $cin)->first();
    $path_front = $profile->image_front;
    if(file_exists(storage_path() .'/app/public/' . $path_front))
    $img_front = (Storage::url($path_front));
    return view('kebele.user.print_id' , compact('profile','img_front' , 'errors'));
  }

  public function discard_profile($cin){

    $profile = Profile::where('cin' , $cin)->first();
    $profile->status = 5;
    $profile->update();

    $educations = Education::where('cin_fk' , $cin)->where('status' , 1)->get();
    if(!$educations->isEmpty()){
      foreach ($educations as $education) {
        $education->status = 5;
        $education->update();
      }
    }

    $familys = Family::where('cin_fk' , $cin)->where('status' , 1)->get();
    if(!$familys->isEmpty()){
      foreach ($familys as $family) {
        $family->status = 5;
        $family->update();
      }
    }

    $insurances = Insurance::where('cin_fk' , $cin)->where('status' , 1)->get();
    if(!$insurances->isEmpty()){
      foreach ($insurances as $insurance ) {
        $insurance->status = 5;
        $insurance->update();
      }
    }

    $cin = Cin::where('cin_fk' , $cin)->where('status' , 1)->first();
    $cin->status = 5;
    $cin->update();

    Nexmo::message()->send([
        'to' => $profile->mobile_number,
        'from' => 'CIS',
        'text' => 'This is Ethiopian CIS To Inform You To Contact The Local Kebele'
    ]);

    Session::flash('profile_discard','The Record Was Successfully Discarded');
    return redirect()->route('Kebele_Pending_Profile');
  }


  public function approve_birth($id){

    $birth = RecordBirth::where('id' , $id)->first();
    $birth->status = 2;
    $birth->update();

    $father_family = new Family;
    $father_family->cin_fk = $birth->father;
    $father_family->relation = ($birth->gender == 'male' ? "Son" : "Daughter");
    $father_family->full_name = $birth->child_name;
    $father_family->status = 2;

    $mother_family = new Family;
    $mother_family->cin_fk = $birth->mother;
    $mother_family->relation = ($birth->gender == 'male' ? "Son" : "Daughter");
    $mother_family->full_name = $birth->child_name;
    $mother_family->status = 2;
    $father_family->save();
    $mother_family->save();
    Session::flash('birth_approve','The Record Was Successfully Approved');
    return redirect()->route('Kebele_Pending_Birth');
  }

  public function discard_birth($id){
    $birth = RecordBirth::where('id' , $id)->first();
    $birth->status = 5;
    $birth->update();
    Session::flash('birth_discard','The Record Was Successfully Discarded');
    return redirect()->route('Kebele_Pending_Birth');
  }

  public function approve_marriage($id){
    $marriage = RecordMarriage::where('id' , $id)->first();
    $marriage->status = 2;
    $marriage->update();

    $husband = new Family;
    $husband->cin_fk = $marriage->male_cin_fk;
    $husband->relation = 'Wife';
    $husband->family_cin_fk = $marriage->female_cin_fk;
    $husband->full_name = Profile::where('cin', $marriage->female_cin_fk)->first()->first_name . ' ' . Profile::where('cin', $marriage->female_cin_fk)->first()->last_name;
    $husband->status = 2;
    $husband->update();

    $wife = new Family;
    $wife->cin_fk = $marriage->female_cin_fk;
    $wife->relation = 'Husband';
    $wife->family_cin_fk = $marriage->male_cin_fk;
    $wife->full_name = Profile::where('cin', $marriage->male_cin_fk)->first()->first_name . ' ' . Profile::where('cin', $marriage->male_cin_fk)->first()->last_name;
    $wife->status = 2;
    $wife->update();

    Session::flash('marriage_approve','The Record Was Successfully Approved');
    return redirect()->route('Kebele_Pending_Marriage');

  }

  public function discard_marriage($id){
    $marriage = RecordMarriage::where('id' , $id)->first();
    $marriage->status = 5;
    $marriage->update();
    Session::flash('marriage_discard','The Record Was Successfully Approved');
    return redirect()->route('Kebele_Pending_Marriage');
  }

  public function approve_divorce($id){
    $divorce = RecordDivorce::where('id' , $id)->first();
    $divorce->status = 2;
    $divorce->update();

    $husband = Family::where('cin_fk' , $divorce->male_cin_fk)->first();
    $husband->relation = "Divorced";
    $husband->remark = 'Divorced with ' . $husband->full_name . ' on Date ' . $divorce->date_of_divorce;
    $husband->status = 3;

    $wife = Family::where('cin_fk' , $divorce->female_cin_fk)->first;
    $wife->relation = "Divorced";
    $wife->remark = 'Divorced with ' . $wife->full_name . ' on Date ' . $divorce->date_of_divorce;
    $wife->status = 3;

    $husband->update();
    $wife->update();

    Session::flash('divorce_approve','The Record Was Successfully Approved');
    return redirect()->route('Kebele_Pending_Divorce');
  }

  public function discard_divorce($id){
    $divorce = RecordDivorce::where('id' , $id)->first();
    $divorce->status = 5;
    $divorce->update();
    Session::flash('divorce_discard','The Record Was Successfully Approved');
    return redirect()->route('Kebele_Pending_Divorce');
  }

  public function approve_death($id){
    $death = RecordDeath::where('id' , $id)->first();
    $death->status = 2;
    $death->update();

    $families = Family::where('family_cin_fk' , $death->cin_fk)->get();
    if(count($families) > 0){
      foreach ($families as $family) {
        $family->relation = "Dead";
        $family->status = 3;
        $family->update();
      }
    }

    Session::flash('death_approve','The Record Was Successfully Approved');
    return redirect()->route('Kebele_Pending_Death');
  }

  public function discard_death($id){
    $death = RecordDeath::where('id' , $id)->first();
    $death->status = 5;
    $death->update();


    Session::flash('death_discard','The Record Was Successfully Approved');
    return redirect()->route('Kebele_Pending_Death');
  }

public function print_bith_id($id){
$father=DB::table('record_birth')->select('father')->where('id',$id)->get();
foreach ($father as $fa) {
$father_cin=$fa->father;
}
$mother=DB::table('record_birth')->select('mother')->where('id',$id)->get();
foreach ($mother as $ma) {
$mother_cin=$ma->mother;
}
  
    $profile = Profile::where('cin' , $father_cin)->first();
    $path_front = $profile->image_front;
    if(file_exists(storage_path() .'/app/public/' . $path_front))
    $img_father = (Storage::url($path_front));

    $profile2 = Profile::where('cin' , $mother_cin)->first();
    $path_front2 = $profile2->image_front;
    if(file_exists(storage_path() .'/app/public/' . $path_front2))
    $img_mother = (Storage::url($path_front2));
$child=DB::table('record_birth')->where('id',$id)->get();
    return view('kebele.user.print_certificate.print_birth' , compact('child','img_father','img_mother'));
  }
public function print_death_id($id){
$father=DB::table('record_death')->select('cin_fk')->where('id',$id)->get();
foreach ($father as $fa) {
$father_cin=$fa->cin_fk;
}
$mother=DB::table('record_death')->select('asmezgabi_cin')->where('id',$id)->get();
foreach ($mother as $ma) {
$mother_cin=$ma->asmezgabi_cin;
}
  
    $profile = Profile::where('cin' , $father_cin)->first();
    $path_front = $profile->image_front;
    if(file_exists(storage_path() .'/app/public/' . $path_front))
    $img_father = (Storage::url($path_front));

    $profile2 = Profile::where('cin' , $mother_cin)->first();
    $path_front2 = $profile2->image_front;
    if(file_exists(storage_path() .'/app/public/' . $path_front2))
    $img_mother = (Storage::url($path_front2));
$child=DB::table('record_death')->where('id',$id)->get();

 $recordm = RecordDeath::where('id' , $id)->first();
    $path_front3 = $recordm->evidence;
    if(file_exists(storage_path() .'/app/public/' . $path_front3))
    $img_evidence = (Storage::url($path_front3));
    return view('kebele.user.print_certificate.print_death' , compact('child','img_father','img_mother','img_evidence'));
  }

  public function print_divorce_id($id){
$father=DB::table('record_divorce')->select('male_cin_fk')->where('id',$id)->get();
foreach ($father as $fa) {
$father_cin=$fa->male_cin_fk;
}
$mother=DB::table('record_divorce')->select('female_cin_fk')->where('id',$id)->get();
foreach ($mother as $ma) {
$mother_cin=$ma->female_cin_fk;
}
  
    $profile = Profile::where('cin' , $father_cin)->first();
    $path_front = $profile->image_front;
    if(file_exists(storage_path() .'/app/public/' . $path_front))
    $img_father = (Storage::url($path_front));

    $profile2 = Profile::where('cin' , $mother_cin)->first();
    $path_front2 = $profile2->image_front;
    if(file_exists(storage_path() .'/app/public/' . $path_front2))
    $img_mother = (Storage::url($path_front2));

    $recordm = RecordDivorce::where('id' , $id)->first();
    $path_front3 = $recordm->evidence;
    if(file_exists(storage_path() .'/app/public/' . $path_front3))
    $img_evidence = (Storage::url($path_front3));

    $child=DB::table('record_divorce')->where('id',$id)->get();
    return view('kebele.user.print_certificate.print_divorce' , compact('child','img_father','img_mother','img_evidence'));
  }

  public function print_marriage_id($id){
$father=DB::table('record_marriage')->select('male_cin_fk')->where('id',$id)->get();
foreach ($father as $fa) {
$father_cin=$fa->male_cin_fk;
}
$mother=DB::table('record_marriage')->select('female_cin_fk')->where('id',$id)->get();
foreach ($mother as $ma) {
$mother_cin=$ma->female_cin_fk;
}
  
    $profile = Profile::where('cin' , $father_cin)->first();
    $path_front = $profile->image_front;
    if(file_exists(storage_path() .'/app/public/' . $path_front))
    $img_father = (Storage::url($path_front));

    $profile2 = Profile::where('cin' , $mother_cin)->first();
    $path_front2 = $profile2->image_front;
    if(file_exists(storage_path() .'/app/public/' . $path_front2))
    $img_mother = (Storage::url($path_front2));

    $recordm = RecordMarriage::where('id' , $id)->first();
    $path_front3 = $recordm->evidence;
    if(file_exists(storage_path() .'/app/public/' . $path_front3))
    $img_evidence = (Storage::url($path_front3));

    $child=DB::table('record_marriage')->where('id',$id)->get();
    return view('kebele.user.print_certificate.print_marriage' , compact('child','img_father','img_mother','img_evidence'));
  }

}
