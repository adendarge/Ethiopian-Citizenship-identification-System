<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	public function run() {
		//$this -> call('profile_seeder');
		// $this -> call('region_seed');
		// $this -> call('zone_seed');
		// $this -> call('wereda_seed');
		// $this -> call('city_seed');
		// $this -> call('kebele_seed');
		// $this -> call('organization_seed');
		$this -> call('users_seed');

	}
}

class region_seed extends Seeder {
	public function run(){
		DB::table('region')->insert(array(
			'id' => 1,
			'region_name' => "Oromia",
			'ceo_cin_fk' => "000000000000",
			'phone' => "0915153555",
			'email' =>  "email@region.com",
			'website' => "website.com",
			'summery_file' => "1_summery_file.txt"
			));
	}
}


class zone_seed extends Seeder {
	public function run(){
		DB::table('zone')->insert(array(
			'id' => 1,
			'zone_name' => "Adama",
			'region_id' => 1 ,
			'ceo_cin_fk' => "000000000000",
			'phone' => "0915153555",
			'email' =>  "email@wereda.com",
			'website' => "website.com",
			'summery_file' => "1_1_summery_file.txt"
			));
	}
}


class wereda_seed extends Seeder {
	public function run(){
		DB::table('wereda')->insert(array(
			'id' => 1,
			'wereda_name' => "Adama",
			'region_id' => 1 ,
			'zone_id' => 1,
			'ceo_cin_fk' => "000000000000",
			'phone' => "0915153555",
			'email' =>  "email@wereda.com",
			'website' => "website.com",
			'summery_file' => "1_1_summery_file.txt"
			));
	}
}


class city_seed extends Seeder {
	public function run(){
		DB::table('city')->insert(array(
			'id' => 1,
			'city_name' => "Adama",
			'wereda_id' => 1,
			'zone_id' => 1,
			'region_id' => 1 ,
			'ceo_cin_fk' => "000000000000",
			'phone' => "0915153555",
			'email' =>  "email@city.com",
			'website' => "website.com",
			'summery_file' => "1_1_1_summery_file.txt"
			));
	}
}

class kebele_seed extends Seeder {
	public function run(){
		DB::table('kebele')->insert(array(
			'id' => 1,
			'kebele_name' => "Adama",
			'city_id' => 1,
			'wereda_id' => 1,
			'zone_id' => 1,
			'region_id' => 1 ,
			'ceo_cin_fk' => "000000000000",
			'phone' => "0915153555",
			'email' =>  "email@kebele.com",
			'website' => "website.com",
			'summery_file' => "1_1_1_1_summery_file.txt"
			));
	}
}


class users_seed extends Seeder {
	public function run(){
		DB::table('users')->insert(array(
	 		'email' => "kebele2@user.com" ,
	 		'password' =>  bcrypt("pass"),
	 	    'cin_fk' => "000000000000",
	 	    'work_area' => "Kebele",
	 	    'type' => "User" ,
	 	    'status' => 1,
	 	    'region_id' => 1 ,
			'zone_id' => 1,
	 	    'wereda_id' => 1 ,
	 	    'city_id' => 1 ,
	 	    'kebele_id' => 0 ,
	 	    'organization_id' => 0
			));
	}
}


class profile_seeder extends Seeder{

	public function run(){

		DB::table('profile')->insert(array(

			'id' => 1,
			'cin'=> "000000000000",
			'first_name' => "first_name",
			'middle_name' => "middle_name",
			'last_name' => "last_name",
			'gender' => "Male",
			'nationality' => "Ethiopian",
			'occupation' => "CIS Employee",
			'ethinicity' => "Amhara",
			'birth_date' => \Carbon\Carbon::now()->toDateTimeString(),
			'birth_place' => "Hometown",
			'current_address' => "Current Address",
			'house_number' => 000,
			'mobile_number' => "0915153555",
			'emergency_contact_name' => "Emergency Contact Name",
			'emergency_contact_phone' => "E-phone",
			'due_date_of_issue' => \Carbon\Carbon::now()->toDateTimeString(),
			'digital_signature' => "DigitalSignature.png",
			'marital_status' => "Single",
			'profile_status' => 1,
			'status' => 1,
			'religion' => "Religion",
			'image_front' => "images\\000000000000_front.jpg",
			'image_side' => "images\\000000000000_side.jpg",
			'pending_status' => 1,
			'remark' => "Default Profile",
			'activity_log' => "000000000000.log"

			));

	}
}



class organization_seed extends Seeder{

	public function run(){

		DB::table('organization')->insert(array(
 			'id' => 1,
 			'org_name' => "Adama General Hospital",
 			'type' => "Medical" ,
 			'kebele_id' => 1 ,
 			'city_id' => 1 ,
 			'wereda_id' => 1 ,
			'zone_id' => 1,
 			'region_id' => 1 ,
 			'ceo_cin_fk' => "000000000000" ,
 			'phone' => "0915151515",
 			'email' => "email@org.com",
 			'website' => "organization.com",
 			"summery_file" => "org_summery_file.txt"


			));

	}
}
