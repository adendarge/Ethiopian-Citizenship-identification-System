<?php

namespace App\Http\Controllers\kebele;

use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CinNumberController;
// use App\Model\citizen\Cin;
// use App\Model\city\City;
// use App\Model\citizen\Profile;
// use App\Model\citizen\Family;
// use App\Model\citizen\Education;
// use App\Model\citizen\Insurance;
// use App\Model\citizen\BiologicalInformation;
use App\Model\kebele\RecordBirth;
use App\Model\kebele\RecordMarriage;
use App\Model\kebele\RecordDivorce;
use App\Model\kebele\RecordDeath;
use App\Model\citizen\Cin;
use App\Model\citizen\Profile;
use App\Model\kebele\Kebele;
use App\Model\region\Region;
use Session;


class CertificateController extends Controller{

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('user:Kebele');
  }


  public function birth_post(Request $request){

    $this->validate($request,[
      'child_name' => 'required',
      'gender' => 'required',
      'nationality' => 'required',
      'birth_date' => 'required|date|before:'.date('Y-m-d'),
      'insitution' => 'required',
      'mother_cin' => 'required|size:12',
      'father_cin' => 'required|size:12',
      'asmezgabi_cin' => 'required|size:12',
      'asmezgabi_relationship' => 'required',
      'zone' => 'required',
      'wereda' => 'required',
      'city' => 'required',
      'kebele' => 'required',
      'evidence' => 'required',
    ]);

    $birth = new RecordBirth;
    $birth->child_name = $request->child_name;
    $birth->child_gender = $request->gender;
    $birth->nationality = $request->nationality;
    $birth->date_of_birth = $request->birth_date;
    $birth->mother = $request->mother_cin;
    $birth->father = $request->father_cin;
    $birth->asmezgabi_cin = $request->asmezgabi_cin;
    $birth->asmezgabi_relation_with_child = $request->asmezgabi_relationship;
    $birth->region = $this->get_region();
    $birth->zone = $request->zone;
    $birth->city = $request->city;
    $birth->wereda = $request->wereda;
    $birth->kebele = $request->kebele;
    $birth->kebele_id_fk = auth()->user()->kebele_id;
    $birth->institute = $request->insitution;
    $birth->registration_date = date('Y-m-d H:i:s');
    $birth->status = 1;
    $birth->remark = $request->remark;


    $evidence = $request->evidence;
    $evidence_name = date('Y-m-d') . '_' . date('H-i-s') . '_birth.' . $evidence->getClientOriginalExtension();
    $path = '/birth/';
    $birth->evidence = $path.$evidence_name;
    $img_evidence_make = Image::make($evidence)->resize(300,200);
    Storage::disk('public')->put($path . $evidence_name , $img_evidence_make->stream());





    if($birth->save()){
      Session::flash('birth_flash' , 'The Data Has Been Successfully Pended.<br>This Data is Temporaty, Needs Further Operations');
      return redirect()->route('Kebele_User_Birth_Detail', array($birth->id));
    }else{
      Session::flash('error' , 'The Data Has Not Been Stored. Some Error Occured ');
      return redirect()->route('Kebele_Birth_Reg_View');
    }


  }


  public function mariage_post(Request $request){

    $this->validate($request,[
      'male_cin' => 'required|size:12',
      'female_cin' => 'required|size:12',
      'male_witness' => 'required',
      'female_witness' => 'required',
      'marriage_type' => 'required',
      'institution' => 'required',
      'date_of_marriage' => 'required|date|before:'.date('Y-m-d'),
      'zone' => 'required',
      'wereda' => 'required',
      'city' => 'required',
      'kebele' => 'required',
      'evidence' => 'required',
    ]);
 $male_cin= Profile::where('cin' , $request->male_cin)->first();
 $female_cin= Profile::where('cin' , $request->female_cin)->first(); 
    $m=$male_cin->marital_status;
    $f=$female_cin->marital_status;
    if(($m=="Married")||($f=="Married")){
     Session::flash('error' , 'You can not married. you got married already');
      return redirect()->route('Kebele_Marriage_Reg_View');
    }
    else{
    $marriage = new RecordMarriage;
    $marriage->male_cin_fk = $request->male_cin;
    $marriage->female_cin_fk = $request->female_cin;
    $marriage->region = $this->get_region();
    $marriage->zone = $request->zone;
    $marriage->wereda = $request->wereda;
    $marriage->city = $request->city;
    $marriage->kebele = $request->kebele;
    $marriage->kebele_id_fk = auth()->user()->kebele_id;
    $marriage->institution = $request->institution;
    $marriage->date_of_marriage = $request->date_of_marriage;

    $marriage->date_of_marriage_registration = date('Y-m-d H:i:s');

    $marriage->type_of_marriage = $request->marriage_type;
    $marriage->female_witness_one = $request->female_witness[0];
    $marriage->female_witness_two = $request->female_witness[1];
    $marriage->female_witness_three = $request->female_witness[2];
    $marriage->male_witness_one = $request->male_witness[0];
    $marriage->male_witness_two = $request->male_witness[1];
    $marriage->male_witness_three = $request->male_witness[2];
    $marriage->status = 1;
    $marriage->remark = $request->remark;

//need to get the image file and name
    $evidence = $request->evidence;
    $evidence_name = date('Y-m-d') . '_' . date('H-i-s') . '_marriage.' . $evidence->getClientOriginalExtension();
    $path = '/marriage/';
    $marriage->evidence = $path.$evidence_name;
    $img_evidence_make = Image::make($evidence)->resize(300,200);
    Storage::disk('public')->put($path . $evidence_name , $img_evidence_make->stream());





    if($marriage->save()){
      Session::flash('marriage_flash' , 'The Data Has Been Successfully Pended.<br>This Data is Temporaty, Needs Further Operations');
      return redirect()->route('Kebele_User_Marriage_Detail', array($marriage->id));
    }else{
      Session::flash('error' , 'The Data Has Not Been Stored. Some Error Occured ');
      return redirect()->route('Kebele_Marriage_Reg_View');
    }
  }
  }




  public function divorce_post(Request $request){

    $this->validate($request,[
      'male_cin' => 'required|size:12',
      'female_cin' => 'required|size:12',
      'institution' => 'required',
      'date_of_divorce' => 'required|date|before:'.date('Y-m-d'),
      'zone' => 'required',
      'wereda' => 'required',
      'city' => 'required',
      'kebele' => 'required',
      'reason' => 'required|min:20',
      'evidence' => 'required',
    ]);

    $divorce = new RecordDivorce;
    $divorce->male_cin_fk = $request->male_cin;
    $divorce->female_cin_fk = $request->female_cin;
    $divorce->region = $this->get_region();
    $divorce->zone = $request->zone;
    $divorce->wereda = $request->wereda;
    $divorce->city = $request->city;
    $divorce->kebele = $request->kebele;
    $divorce->kebele_id_fk = auth()->user()->kebele_id;
    $divorce->institution = $request->institution;
    $divorce->date_of_divorce = $request->date_of_divorce;
    $divorce->date_of_divorce_registration = date('Y-m-d H:i:s');
    $divorce->reason = $request->reason;
    $divorce->remark = $request->remark;
    $divorce->status = 1;

    $evidence = $request->evidence;
    $evidence_name = date('Y-m-d') . '_' . date('H-i-s') . '_divorce.' . $evidence->getClientOriginalExtension();
    $path = '/divorce/';
    $divorce->evidence = $path.$evidence_name;
    $img_evidence_make = Image::make($evidence)->resize(300,200);
    Storage::disk('public')->put($path . $evidence_name , $img_evidence_make->stream());


    if($divorce->save()){
      Session::flash('divorce_flash' , 'The Data Has Been Successfully Pended.This Data is Temporaty, Needs Further Operations');
      return redirect()->route('Kebele_User_Divorce_Detail', array($divorce->id));
    }else{
      Session::flash('error' , 'The Data Has Not Been Stored. Some Error Occured ');
      return redirect()->route('Kebele_Divorce_Reg_View');
    }
    //flash some message
  }




  public function death_post(Request $request){
    //validation
    $this->validate($request,[
      // 'dead_cin' => 'required|size:12',
      'asmezgabi_cin' => 'required|size:12',
      'asmezgabi_relationship' => 'required',
      'fueneral_place' => 'required',
      'insitution' => 'required',
      'date_of_death' => 'required|date|before:'.date('Y-m-d'),
      'kebele' =>'required',
      'city' =>'required',
      'zone' => 'required',
      'wereda' => 'required',
      'reason' => 'required|min:20',
      'evidence' => 'required',
    ]);

    $death = new RecordDeath;
    $cin=$request->dead_cin;
    $death->cin_fk = $request->dead_cin;
    $death->date_of_death = $request->date_of_death;
    $death->region = $this->get_region();
    $death->wereda = $request->wereda;
    $death->zone = $request->zone;
    $death->city = $request->city;
    $death->kebele = $request->kebele;
    $death->kebele_id_fk = auth()->user()->kebele_id;
    $death->institution = $request->insitution;
    $death->reason = $request->reason;
    $death->fueneral_place = $request->fueneral_place;
    $death->asmezgabi_cin = $request->asmezgabi_cin;
    $death->asmezgabi_relation_with_person = $request->asmezgabi_relationship;
    $death->date_of_death_registration = date('Y-m-d H:i:s');
    $death->status = 1;
    $death->remark = $request->remark;
    $death->evidence = $request->remark;//got to be handled
    

    $evidence = $request->evidence;
    $evidence_name = date('Y-m-d') . '_' . date('H-i-s') . '_death.' . $evidence->getClientOriginalExtension();
    $path = '/death/';
    $death->evidence = $path.$evidence_name;
    $img_evidence_make = Image::make($evidence)->resize(300,200);
    Storage::disk('public')->put($path . $evidence_name , $img_evidence_make->stream());
     

    if($death->save()){
    if($cin!=""){
    $profile = Profile::where('cin', $cin)->first();
    $profile->status = 5;
    $profile->save();
    $cin_info = Cin::where('cin_fk', $cin)->first();
    $cin_info->status = 5;
    $cin_info->save(); 
    }
      Session::flash('divorce_flash' , 'The Data Has Been Successfully Pended.This Data is Temporaty, Needs Further Operations');
      return redirect()->route('Kebele_User_Death_Detail', array($death->id));
    }else{
      Session::flash('error' , 'The Data Has Not Been Stored. Some Error Occured ');
      return redirect()->route('Kebele_Death_Reg_View');
    }
  }

  public function get_name($cin){
    $citizen = Profile::where('cin' , $cin)->first();
    $name = $citizen->first_name . ' ' . $citizen->middle_name . ' ' . $citizen->last_name;
    return $name;
  }

  public function get_region(){
    $region_id = Kebele::where('id' , auth()->user()->kebele_id)->first()->region_id;
    $region = Region::where('id' , $region_id)->first()->region_name;
    return $region;
  }

}
