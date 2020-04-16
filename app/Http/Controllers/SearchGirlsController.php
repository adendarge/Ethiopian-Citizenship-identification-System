<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;

class SearchGirlsController extends Controller
{
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
