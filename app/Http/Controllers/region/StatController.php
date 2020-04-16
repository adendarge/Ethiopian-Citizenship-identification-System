<?php

namespace App\Http\Controllers\region;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Response;
use App\User;
use App\profile;
use App\Product;
use Carbon\Carbon;
//use Response;
//use Input;
use Session;
use DB;
use Illuminate\Support\Facades\Input;


class StatController extends Controller{

  public function __construct(){
    $this->middleware('auth');
    $this->middleware('backbutton');
    $this->middleware('admin:Region');
  }

  

public function stat_init(){

  $type = Input::get('type');
  switch ($type) {
    case 'gender':
      return $this->gender2();
      break;
    case 'ethinicity':
      return $this->ethinicity();
      break;
    case 'crime':
      return $this->crime();
      break;
    case 'health':
      return $this->health();
      break;
    case 'qualification':
      return $this->qualification();
      break;
    case 'institution':
      return $this->institution();
      break;
    case 'birth_date':
      return $this->age();
      break;
    case 'marital_status':
      return $this->marital();
      break;
    default:
      return 'shadow ';
      break;
  }
}

  public function gender(){
    $users = DB::table('profile')->orderBy('gender', 'asc')->get();
    $totalmale = DB::table('profile')->select('gender')->where('gender','Male')->get();
    $totalfemale = DB::table('profile')->select('gender')->where('gender','Female')->get();
      return view('region.admin.stat.gender_stat',compact('users','totalmale','totalfemale'));
  }
    public function gender2(){
    $users = DB::table('profile')->orderBy('gender', 'asc')->get();
    $totalmale = DB::table('profile')->select('gender')->where('gender','Male')->get();
    $totalfemale = DB::table('profile')->select('gender')->where('gender','Female')->get();
      return view('region.admin.stat.sorterstat',compact('users','totalmale','totalfemale'));
  }

  public function age(){
    $users = DB::table('profile')->get();
    $users=$users->toArray();
    return view('region.admin.stat.age_stat',compact('users'));
    }

    public function marital(){
    $users = DB::table('profile')->get();
    $totalsingle = DB::table('profile')->select('marital_status')->where('marital_status','single')->get();
    $totaldivorced = DB::table('profile')->select('marital_status')->where('marital_status','divorced')->get();
    $totalmarried = DB::table('profile')->select('marital_status')->where('marital_status','married')->get();
    $users=$users->toArray();
    $totalsingle=$totalsingle->toArray();$totaldivorced=$totaldivorced->toArray();
    $totalmarried=$totalmarried->toArray();
    return view('region.admin.stat.marital_stat',compact('users','totalsingle','totaldivorced','totalmarried'));

  }

  public function ethinicity(){
    $users = DB::table('profile')->get();
    $totalamhara = DB::table('profile')->select('ethinicity')->where('ethinicity','amhara')->get();
    $totaloromiya = DB::table('profile')->select('ethinicity')->where('ethinicity','oromiya')->get();
    $totaltigrai = DB::table('profile')->select('ethinicity')->where('ethinicity','tigrai')->get();
    $totalafar = DB::table('profile')->select('ethinicity')->where('ethinicity','afar')->get();
    $totalsomalia = DB::table('profile')->select('ethinicity')->where('ethinicity','somalia')->get();
    $totalsouthern = DB::table('profile')->select('ethinicity')->where('ethinicity','southern')->get();
    $totalbenishangul = DB::table('profile')->select('ethinicity')->where('ethinicity','benishangul')->get();
    $totalgambela = DB::table('profile')->select('ethinicity')->where('ethinicity','gambela')->get();
    $totalharrari = DB::table('profile')->select('ethinicity')->where('ethinicity','harrari')->get();
    $users=$users->toArray();
    $totalamhara=$totalamhara->toArray();$totaloromiya=$totaloromiya->toArray();
    $totaltigrai=$totaltigrai->toArray();$totalafar=$totalafar->toArray();
    $totalsomalia=$totalsomalia->toArray();$totalsouthern=$totalsouthern->toArray();
    $totalbenishangul=$totalbenishangul->toArray();$totalgambela=$totalgambela->toArray();
    $totalharrari=$totalharrari->toArray();
    return view('region.admin.stat.ethinicity_stat',compact('users','totalamhara','totaloromiya','totalharrari','totaltigrai','totalafar','totalsomalia','totalsouthern','totalbenishangul','totalgambela'));

  }

  public function qualification(){
    $users = DB::table('profile') ->leftJoin('education', 'education.cin_fk', '=', 'profile.cin')->get();
    $totalqualification = DB::table('profile')->leftJoin('education', 'education.id', '=', 'profile.id')->count('education.qualification');
    $users=$users->toArray();
    $totalqualification=$totalqualification;
    return view('region.admin.stat.qualification_stat',compact('users','totalqualification'));
  }

  public function institution(){
    $users = DB::table('profile') ->leftJoin('education', 'education.cin_fk', '=', 'profile.cin')->get();
    $totalinstitution = DB::table('profile')->leftJoin('education', 'education.id', '=', 'profile.id')->count('education.institution');
    $users=$users->toArray();
    $totalinstitution=$totalinstitution;
    return view('region.admin.stat.institution_stat',compact('users','totalinstitution'));
  }

  public function health(){
    $users = DB::table('profile') ->leftJoin('medical_record', 'medical_record.cin_fk', '=', 'profile.cin')->get();
    $totalhiv = DB::table('profile')->leftJoin('medical_record', 'medical_record.cin_fk', '=', 'profile.cin')->where('medical_record.catagory','HIV')->get();
    $totaltb = DB::table('profile')->leftJoin('medical_record', 'medical_record.cin_fk', '=', 'profile.cin')->where('medical_record.catagory','TB')->get();
    $totalother = DB::table('profile')->leftJoin('medical_record', 'medical_record.cin_fk', '=', 'profile.cin')->where('medical_record.catagory','OTHER')->get();
    $totalhiv=$totalhiv->toArray();$totaltb=$totaltb->toArray();
    $totalother=$totalother->toArray();
    $users=$users->toArray();
    return view('region.admin.stat.health_stat',compact('users','totalhiv','totaltb','totalother'));
  }

  public function crime(){
    $users = DB::table('profile') ->leftJoin('criminal_record', 'criminal_record.cin_fk', '=', 'profile.cin')->get();
    $totalmurder = DB::table('profile')->leftJoin('criminal_record', 'criminal_record.cin_fk', '=', 'profile.cin')->where('accusation','murder')->get();
    $totalmollify = DB::table('profile')->leftJoin('criminal_record', 'criminal_record.cin_fk', '=', 'profile.cin')->where('accusation','mollify')->get();
    $totalother = DB::table('profile')->leftJoin('criminal_record', 'criminal_record.cin_fk', '=', 'profile.cin')->where('accusation','OTHER')->get();
    $totalmurder=$totalmurder->toArray();$totalmollify=$totalmollify->toArray();
    $totalother=$totalother->toArray();
    $users=$users->toArray();
    return view('region.admin.stat.crime_stat',compact('users','totalmurder','totalmollify','totalother'));
  }







}
