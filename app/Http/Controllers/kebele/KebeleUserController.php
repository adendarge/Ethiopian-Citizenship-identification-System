<?php

namespace App\Http\Controllers\kebele;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\citizen\Cin;
use App\Model\city\City;
use App\Model\kebele\Kebele;
use App\Model\wereda\Wereda;
use App\Model\zone\Zone;
use App\Model\region\Region;
use App\Model\citizen\Profile;
use App\Model\citizen\Family;
use App\Model\citizen\Education;
use App\Model\citizen\Insurance;
use App\Model\citizen\BiologicalInformation;
use App\Http\Controllers\CinNumberController;
use App\Model\kebele\RecordBirth;
use App\Model\kebele\Transfer;
use App\Model\kebele\RecordMarriage;
use App\Model\kebele\RecordDivorce;
use App\Model\kebele\RecordDeath;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
use DB;
use Image;
use Response;
use Session;




class KebeleUserController extends Controller
{
  public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('user:Kebele');
  }

  public function index(){
    return view('kebele.user.dashboard');
  }

  public function citizen_registration_form(){
    return view('kebele.user.citizen_reg');
  }

  public function citizen_post_view($cin){
    $pro = Profile::where('cin' , $cin)->first();
    $fams = Family::where('cin_fk' , $cin)->get();
    $edus = Education::where('cin_fk' , $cin)->get();
    $insus = Insurance::where('cin_fk' , $cin)->get();
    $bio = BiologicalInformation::where('cin_fk' , $cin)->first();

    //this is for the Image
    $path= Profile::where('cin' , $cin)->first();
    $path_front = $path->image_front;
    $path_side = $path->image_side;
    if(file_exists(storage_path() .'/app/public/' . $path_front) && file_exists(storage_path() .'/app/public/' . $path_side) ){
    $img_front = (Storage::url($path_front));
    $img_side = (Storage::url($path_side));
    }
    return view('kebele.user.detail.citizen_detail',compact('pro','fams','edus','insus','bio' , 'img_front', 'img_side'));

  }

 
/*adnan yo darge*/
public function getZoneList(Request $request)
    {
        $zone = DB::table("zone")
                    ->where("region_id",$request->region_id)
                    ->pluck("zone_name","id")->toArray();
        return response()->json($zone);
    }

public function getWeredaList(Request $request)
    {
        $wereda = DB::table("wereda")
                    ->where("zone_id",$request->zone_id)
                    ->pluck("wereda_name","id")->toArray();
        return response()->json($wereda);
    }
    public function getCityList(Request $request)
    {
        $city = DB::table("city")
                    ->where("wereda_id",$request->wereda_id)
                    ->pluck("city_name","id")->toArray();
        return response()->json($city);
    }
public function getKebeleList(Request $request)
    {
        $kebele = DB::table("kebele")
                    ->where("city_id",$request->city_id)
                    ->pluck("kebele_name","id")->toArray();
        return response()->json($kebele);
    }

/**/

  public function birth_detail($id){
    $child = RecordBirth::where('id' , $id)->first();
    $mom = Profile::where('cin' , $child->mother)->first();
    $dad = Profile::where('cin' , $child->father)->first();
    $asm = Profile::where('cin' , $child->asmezgabi_cin)->first();
    return view('kebele.user.detail.birth_detail' , compact('child' , 'dad' , 'mom' , 'asm'));
  }

   public function birth_view(){
    $region = DB::table("region")->get();
    $region=$region->toArray();
    return view('kebele.user.certificate.birth',compact('region'));
  }

  public function marriage_view(){
    $region = DB::table("region")->get();
  $region=$region->toArray();
  return view('kebele.user.certificate.marriage',compact('region'));
  }

  public function marriage_detail($id){
    $marriage = RecordMarriage::where('id' , $id)->first();
    $groum = Profile::where('cin' , $marriage->male_cin_fk)->first();
    $bride = Profile::where('cin' , $marriage->female_cin_fk)->first();
    return view('kebele.user.detail.marriage_detail' , compact('marriage' , 'groum' , 'bride'));
  }

  public function divorce_view(){
        $region = DB::table("region")->get();
  $region=$region->toArray();
  return view('kebele.user.certificate.divorce',compact('region'));
  }

  public function divorce_detail($id){
    $divorce = RecordDivorce::where('id' , $id)->first();
    $husband = Profile::where('cin' , $divorce->male_cin_fk)->first();
    $wife = Profile::where('cin' , $divorce->female_cin_fk)->first();
    return view('kebele.user.detail.divorce_detail' , compact('divorce' , 'husband' , 'wife'));
  }

  public function death_view(){
       $region = DB::table("region")->get();
  $region=$region->toArray();
  return view('kebele.user.certificate.death',compact('region'));
  }

  public function death_detail($id){
    $death = RecordDeath::where('id' , $id)->first();
    $decead = Profile::where('cin' , $death->cin_fk)->first();
    $asm = Profile::where('cin' , $death->asmezgabi_cin)->first();
    return view('kebele.user.detail.death_detail' , compact('death' , 'decead' , 'asm'));

  }

  public function basic_update_view(){
    return view('kebele.user.basic_update');
  }

  public function education_update_view(){
    return view('kebele.user.education_update');
  }

  public function insurance_update_view(){
    return view('kebele.user.insurance_update');
  }

  public function pending_profile(){
    $profiles = Profile::where('status' , 1)
                        ->where('kebele_id',auth()->user()->kebele_id)->get();
    return view('kebele.user.pending.pending_profile')->with('profiles' , $profiles);
  }

  public function pending_birth(){
    $births = RecordBirth::where('status' , 1)
                          ->where('kebele_id_fk' , auth()->user()->kebele_id)->get();
    return view('kebele.user.pending.pending_birth')->with('births' , $births);
  }

  public function pending_marriage(){
    $marriages = RecordMarriage::where('status' , 1)
                                ->where('kebele_id_fk' , auth()->user()->kebele_id)->get();
    return view('kebele.user.pending.pending_marriage')->with('marriages' , $marriages);
  }

  public function pending_divorce(){
    $divorces = RecordDivorce::where('status' , 1)
                              ->where('kebele_id_fk' , auth()->user()->kebele_id)->get();
    return view('kebele.user.pending.pending_divorce')->with('divorces' , $divorces);
  }

  public function pending_death(){
    $deaths = RecordDeath::where('status' , 1)
                           ->where('kebele_id_fk' , auth()->user()->kebele_id)->get(); 
    return view('kebele.user.pending.pending_death')->with('deaths' , $deaths);
  }

  public function kebele_master(){
    $profiles = Profile::where('kebele_id' , auth()->user()->kebele_id)->get();
    return view('kebele.user.master.kebele_master')->with('profiles' , $profiles);
  }

  public function birth_master(){
    $births = RecordBirth::where('kebele_id_fk' , auth()->user()->kebele_id)->get();
    return view('kebele.user.master.birth_master')->with('births' , $births);
  }

  public function marriage_master(){
    $marriages = RecordMarriage::where('kebele_id_fk' , auth()->user()->kebele_id)->get();
    return view('kebele.user.master.marriage_master')->with('marriages' , $marriages);
  }

  public function divorce_master(){
    $divorces = RecordDivorce::where('kebele_id_fk' , auth()->user()->kebele_id)->get();
    return view('kebele.user.master.divorce_master')->with('divorces' , $divorces);
  }

  public function death_master(){
    $deaths = RecordDeath::where('kebele_id_fk' , auth()->user()->kebele_id)->get();
    return view('kebele.user.master.death_master')->with('deaths' , $deaths);
  }

  public function cin_search(Request $request){
    $path= Profile::where('cin' , $request->cin)
                    ->where('status' , 2)->first();
    if($path->count()){
    $path_front = $path->image_front;
    $img_front = (Storage::url($path_front));
    }
    return $img_front;
  
  }
 public function cin_search_father(Request $request){
    $path= Profile::where('cin' , $request->cin)
                    ->where('status' , 2)->first();
     $gender= Profile::where('cin' , $request->cin)->first();
   
    $g=$gender->gender;
    if(($g=="Male")&&($path->count())){
    $path_front = $path->image_front;
    $img_front = (Storage::url($path_front));
    }
    return $img_front;
  
  }
   public function cin_search_mother(Request $request){
    $path= Profile::where('cin' , $request->cin)
                    ->where('status' , 2)->first();
     $gender= Profile::where('cin' , $request->cin)->first();
    $g=$gender->gender;
    if(($g=="Female")&&($path->count())){
    $path_front = $path->image_front;
    $img_front = (Storage::url($path_front));
    }
    return $img_front;
  
  }
  public function image_search(){
    $taker = 0;
    return view('kebele.user.image_search' , compact('taker'));
  }

  public function in_view(){
    $region = Region::where('id' , auth()->user()->region_id)->first()->id;
    $zone = Zone::where('id' , auth()->user()->zone_id)->first()->id;
    $wereda = Wereda::where('id' , auth()->user()->wereda_id)->first()->id;
    $city = City::where('id' , auth()->user()->city_id)->first()->id;
    $kebele = Kebele::where('id' , auth()->user()->kebele_id)->first()->id;
    $transfers = Transfer::where('status' , 1)
                          ->where('region' , $region)
                          ->where('zone' , $zone)
                          ->where('wereda' , $wereda)
                          ->where('city' , $city)
                          ->where('kebele' , $kebele)->get();
    return view('kebele.user.transfer.transfer_in' , compact('transfers'));
  }

  public function out_view(){
       $region = DB::table("region")->get();
  $region=$region->toArray();
  return view('kebele.user.transfer.transfer_out',compact('region'));
  }

  public function out_post(Request $request){
    $transfer = new Transfer;
    $transfer->from_kebele = auth()->user()->kebele_id;
    $transfer->cin_fk = $request->cin;
    $transfer->region = $request->region;
    $transfer->zone = $request->zone;
    $transfer->wereda = $request->wereda;
    $transfer->city = $request->city;
    $transfer->kebele = $request->kebele;
    $transfer->reason = $request->reason;
    $transfer->remark = $request->remark;
    $transfer->date_of__registration = date('Y-m-d H:i:s');
    $transfer->status = 1;
    
     if($transfer->save()){
    
      Session::flash('transfer_flash' , 'The Data Has Been Successfully tranfered');
      return redirect()->route('Kebele_Out_View');
    }else{
      Session::flash('error' , 'you can transfer. Some Error Occured ');
      return redirect()->route('Kebele_Out_View');
    }
  }

  public function confirm_incoming($cin){
    $transfer = Transfer::where('cin_fk' , $cin)->first();
    $path= Profile::where('cin' , $cin)->first();
    $path_front = $path->image_front;
    $path_side = $path->image_side;
    if(file_exists(storage_path() .'/app/public/' . $path_front) && file_exists(storage_path() .'/app/public/' . $path_side) ){
    $img_front = (Storage::url($path_front));
    $img_side = (Storage::url($path_side));
    }
    return view('kebele.user.transfer.transfer_in_confirm' , compact('transfer' , 'img_front' , 'img_side'));
  }

  public function accept($cin){
    $transfer = Transfer::where('cin_fk' , $cin)->first();
    $transfer->status = 2;
    $profile = Profile::where('cin' , $cin)->first();
    $profile->kebele_id = auth()->user()->kebele_id;
    $transfer->save();
    $profile->save();

    Session::flash('incoming_approve','The Citizen Has Been Welcomed To The Community');
    return redirect()->route('Kebele_In_View');
  }

  public function discard($cin){
    $transfer = Transfer::where('cin_fk' , $cin)->first();
    $transfer->status = 5;

    $transfer->save();
    Session::flash('incoming_discard' , 'The Citizen Has Been Rejected To Join The Community');
    return redirect()->route('Kebele_In_View');
  }

  public static function get_name($cin){
    $pro = Profile::where('cin' , $cin)->first();
    $name = $pro->first_name . ' ' . $pro->middle_name ;
    return $name;
  }

  public static function get_trans_data($value , $place){
    switch($place){
      case 'region':
        $reg = Kebele::where('id' , $value)->first()->region_id;
        return Region::where('id' , $reg)->first()->region_name;
        break; 
      case 'zone':
        $zon = Kebele::where('id' , $value)->first()->zone_id;
        return Zone::where('id' , $zon)->first()->zone_name;
        break; 
      case 'wereda':
        $wer = Kebele::where('id' , $value)->first()->wereda_id;
        return Wereda::where('id' , $wer)->first()->wereda_name;
        break; 
      case 'city':
        $ct = Kebele::where('id' , $value)->first()->city_id;
        return City::where('id' , $ct)->first()->city_name;
        break; 
      case 'kebele':
        return Kebele::where('id' , $value)->first()->kebele_name;
        break; 
      default:
        return 'unknown';
        break;
    }
  }

  public static function get_region($id){
    return Region::where('id' , auth()->user()->region_id)->first()->region_name;
  }

  public static function get_zone($id){
    return Zone::where('id' , auth()->user()->zone_id)->first()->zone_name;
  }

  public static function get_wereda($id){
    return Wereda::where('id' , auth()->user()->wereda_id)->first()->wereda_name;
  }

  public static function get_city($id){
    return City::where('id' , auth()->user()->city_id)->first()->city_name;
  }

  public static function get_kebele($id){
    return Kebele::where('id' , auth()->user()->kebele_id)->first()->kebele_name;
  }
 
 public function update_enabled_user($id){
    DB::table('users')->where('cin_fk', $id)->update(['status' => 1]);
 $userdata=DB::table('users')->select('users.email','users.status','users.cin_fk','profile.first_name','profile.last_name','profile.mobile_number')->leftjoin('profile','profile.cin','=','users.cin_fk')->whereNotIn('users.status', [5])->get();
    return view('useren',compact('userdata'));
  }
public function update_disabled_user($id){
    DB::table('users')->where('cin_fk', $id)->update(['status' => 2]);
  $userdata=DB::table('users')->select('users.email','users.status','users.cin_fk','profile.first_name','profile.last_name','profile.mobile_number')->leftjoin('profile','profile.cin','=','users.cin_fk')->whereNotIn('users.status', [5])->get();
    return view('useren',compact('userdata'));
  }
public function delete_user($id){
    DB::table('users')->where('cin_fk', $id)->update(['status' => 5]);
$userdata=DB::table('users')->select('users.email','users.status','users.cin_fk','profile.first_name','profile.last_name','profile.mobile_number')->leftjoin('profile','profile.cin','=','users.cin_fk')->whereNotIn('users.status', [5])->get();
    return view('useren',compact('userdata'));
  }

}
