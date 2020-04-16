<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserRedirectionController extends Controller{


    public function __construct(User $user){
      $this->user = $user;
      $this->get_details();
    }

    public function get_details(){
      $this->work_area = $this->user['work_area'];
      $this->type = $this->user['type'];
    }

    public function get_route(){
      switch ($this->work_area) {
        case 'Federal':
          $this->route = ($this->type=='Admin'?'Federal_Admin_Home':'Federal_User_Home');
          break;
        case 'Region':
          $this->route = ($this->type=='Admin'?'Region_Admin_Home':'Region_User_Home');
          break;
          case 'Zone':
          $this->route = ($this->type=='Admin'?'Zone_Admin_Home':'ZoneUser_Home');
          break;
        case 'Wereda':
          $this->route = ($this->type=='Admin'?'Wereda_Admin_Home':'Wereda_User_Home');
          break;
        case 'City':
          $this->route = ($this->type=='Admin'?'City_Admin_Home':'City_User_Home');
          break;
        case 'Kebele':
          $this->route = ($this->type=='Admin'?'Kebele_Admin_Home':'Kebele_User_Home');
          break;
        case 'Medical':
          $this->route = ($this->type=='Admin'?'Medical_Admin_Home':'Medical_User_Home');
          break;
        case 'Court':
          $this->route = ($this->type=='Admin'?'Court_Admin_Home':'Court_User_Home');
          break;
        case 'Police':
          $this->route = ($this->type=='Admin'?'Police_Admin_Home':'Police_User_Home');
          break;
        default:
          $this->route = 'No_Route_Found_To_This_User';
          break;
      }
      return $this->route;
    }

}
