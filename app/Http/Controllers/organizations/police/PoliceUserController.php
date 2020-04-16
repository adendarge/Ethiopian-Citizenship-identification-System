<?php

namespace App\Http\Controllers\organizations\police;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\organizations\MedicalRecord;
use App\Model\organizations\CrimeRecord;
use App\Model\citizen\Profile;
use App\Model\organizations\Organization;
use App\Model\citizen\BiologicalInformation;
use View;

class PoliceUserController extends Controller{

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('user:Police');
  }

  public function index(){
    $crimes = CrimeRecord::all();
    return view('organizations.police.user.home',compact('crimes'));
  }

  public function find(Request $request){
    $input = $request->search_input;
    $profiles = Profile::where(function($query){
      $query->where('kebele_id',auth()->user()->kebele_id);
              })->where(function($query) use($input){
                $query->where('first_name' , 'LIKE' , '%' . $input . '%')
                ->orwhere('middle_name' , 'LIKE' , '%' . $input . '%')
                ->orwhere('last_name' , 'LIKE' , '%' . $input . '%')
                ->orwhere('cin' , 'LIKE' , '%' . $input . '%');
              })->get();
    return View::make('organizations.police.user.search' , compact('profiles'));
  }

  public function details($cin){
    $profile = Profile::where('cin' , $cin)->first();
    $crimes = CrimeRecord::where('cin_fk' , $cin)->get();
    $bio = BiologicalInformation::where('cin_fk' , $cin)->first();
    return view('organizations.police.user.police_history' , compact('crimes' , 'profile' , 'bio'));
  }

  public static function get_org($org_id){
    return Organization::where('id' , $org_id)->first();
  }

  public static function mapsearch(){
    return view('organizations.police.user.front');
  }

  
  public function searchGirls(Request $request)
    {
      $lat=$request->lat;
      $lng=$request->lng;
        $girls=DB::table('criminal_record')->leftJoin('city', 'city.id', '=', 'criminal_record.city_id_fk')->leftJoin('profile', 'profile.cin', '=', 'criminal_record.cin_fk')->whereBetween('city.lat',[$lat-0.1,$lat+0.1])->whereBetween('city.lng',[$lng-0.1,$lng+0.1])->get();
      // $girls=DB::table('city')->whereBetween('lat',[$lat-0.1,$lat+0.1])->whereBetween('lng',[$lng-0.1,$lng+0.1])->get();
      return $girls;
    }

    public function searchCity(Request $request)
    {
        // $distval=$request->distval;
        // $matchedCities=DB::table('city')->where('city_name',$distval)->pluck('city_name','city_name');
        // return view('ajxresult',compact('matchedCities'));
        
         //by json automatically
        $locationVal=$request->locationVal;
        $matchedCities=DB::table('city')->where('city_name','like',"%$locationVal%")->pluck('city_name','city_name');
        return response()->json(['items'=>$matchedCities]);
        
    }

    public function locationCoords(Request $request)
    {
        // $cityval=$request->cityval;//
        // $distval=$request->distval;//
        // $col=Location::where('district',$distval)->where('city',$cityval)->first();//
        // $lat=$col->lat;
        // $lng=$col->lng;
        // return [$lat,$lng];
        
        //by json automatically
        $val=$request->val;
        $col=DB::table('city')->where('city_name',$val)->first();
        $lat=$col->lat;
        $lng=$col->lng;
        return [$lat,$lng];
        
    }

}
