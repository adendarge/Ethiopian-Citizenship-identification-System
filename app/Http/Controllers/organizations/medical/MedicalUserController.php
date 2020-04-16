<?php

namespace App\Http\Controllers\organizations\medical;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
use App\Http\Controllers\Controller;
// use App\Model\organizations\File;
use App\Model\organizations\MedicalRecord;
use App\Model\citizen\Profile;
use App\Model\organizations\Organization;
use App\Model\citizen\BiologicalInformation;
use Session;
use Image;
use View;


class MedicalUserController extends Controller{

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('user:Medical');
  }

  public function index(){
    $records = MedicalRecord::where('org_id_fk', auth()->user()->organization_id)->get();
    return view('organizations.medical.user.record_list' , compact('records'));
  }

  public function find_patient(Request $request){
    $profiles = Profile::where('cin' , 'LIKE' , '%' . $request->cin . '%')->get();
    return View::make('organizations.medical.user.home' , compact('profiles'));
  }

  public function medic_history($cin){
    $profile = Profile::where('cin' , $cin)->first();
    $medics = MedicalRecord::where('cin_fk' , $cin)->get();
    $bio = BiologicalInformation::where('cin_fk' , $cin)->first();
    return view('organizations.medical.user.medical_history' , compact('medics' , 'profile' , 'bio'));
  }

  public function medical_reg_form(){
    return view('organizations.medical.user.medical_record');
  }

  public function medical_reg_post(Request $request){
    $this->validate($request,[
      'cin' => 'required|max:12|min:12',
      'doctor_name' => 'required',
      'card_number' => 'required',
      'case' => 'required',
      'detail' => 'required',
      'result' => 'required',
      'status' => 'required',
    ]);
    $medical = new MedicalRecord;
    $medical->cin_fk = $request->cin;
    $medical->org_id_fk = auth()->user()->organization_id;
    $medical->doctor_name = $request->doctor_name;
    $medical->card_number = $request->card_number;
    $medical->catagory = $request->case;
    $medical->date = date('Y-m-d H:i:s');
    $medical->detail = $request->detail;
    $medical->result = $request->result;
    $medical->status = $request->status;
    $medical->remark = $request->remark;
    if($medical->save()){
      Session::flash('success' , 'The Data Is Successfull Inserted');
      return redirect()->route('Medical_User_List');
    }

  }

  public function medical_record_list(){
    $records = MedicalRecord::where('org_id_fk', auth()->user()->organization_id)->get();
    return view('organizations.medical.user.record_list' , compact('records'));
  }

  public static function get_org($org_id){
    return Organization::where('id' , $org_id)->first();
  }


  public function file_get(){
    return view('organizations.medical.user.record_list');
  }

  public function file_post(Request $request){
    //get the file from the input-group
    $image = $request->f;
    //get file name and extension;
    $filename = time() . '.' . $image->getClientOriginalExtension();
    $path = 'photos/';

    $img = Image::make($image);
    $img->resize(150 , 200);
    Storage::put($path . $filename, $img->stream());
    echo 'done';
  }

}
