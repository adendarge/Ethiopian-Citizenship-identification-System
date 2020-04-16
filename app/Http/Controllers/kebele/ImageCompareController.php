<?php

namespace App\Http\Controllers\kebele;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\citizen\Profile;
use Illuminate\Support\Facades\Storage;
use Image;
use File;
use GuzzleHttp;
use Response;


class ImageCompareController extends Controller{

  	private function mimeType($i){
  		/*returns array with mime type and if its jpg or png. Returns false if it isn't jpg or png*/
  		$mime = getimagesize($i);
  		$return = array($mime[0],$mime[1]);

  		switch ($mime['mime']){
  			case 'image/jpeg':
  				$return[] = 'jpg';
  				return $return;
  			case 'image/png':
  				$return[] = 'png';
  				return $return;
  			default:
  				return false;
  		}
  	}

  	private function createImage($i){
  		/*retuns image resource or false if its not jpg or png*/
  		$mime = $this->mimeType($i);

  		if($mime[2] == 'jpg'){
  			return imagecreatefromjpeg ($i);
  		}else if ($mime[2] == 'png'){
  			return imagecreatefrompng ($i);
  		}else {
  			return false;
  		}
  	}

  	private function resizeImage($i,$source){
  		/*resizes the image to a 8x8 squere and returns as image resource*/
  		$mime = $this->mimeType($source);

  		$t = imagecreatetruecolor(8, 8);

  		$source = $this->createImage($source);

  		imagecopyresized($t, $source, 0, 0, 0, 0, 8, 8, $mime[0], $mime[1]);

  		return $t;
  	}

    private function colorMeanValue($i){
  		/*returns the mean value of the colors and the list of all pixel's colors*/
  		$colorList = array();
  		$colorSum = 0;
  		for($a = 0;$a<8;$a++){
  			for($b = 0;$b<8;$b++){
  				$rgb = imagecolorat($i, $a, $b);
  				$colorList[] = $rgb & 0xFF;
  				$colorSum += $rgb & 0xFF;
  			}
  		}
  		return array($colorSum/64,$colorList);
  	}

    private function bits($colorMean){
  		/*returns an array with 1 and zeros. If a color is bigger than the mean value of colors it is 1*/
  		$bits = array();
  		foreach($colorMean[1] as $color){
        $bits[]= ($color>=$colorMean[0])?1:0;
      }
  		return $bits;
  	}

    public function compare($a,$b){
  		/*main function. returns the hammering distance of two images' bit value*/
  		$i1 = $this->createImage($a);
  		$i2 = $this->createImage($b);
      // $i2 = $b;

  		if(!$i1 || !$i2){
        return false;
      }

  		$i1 = $this->resizeImage($i1,$a);
  		$i2 = $this->resizeImage($i2,$b);

  		imagefilter($i1, IMG_FILTER_GRAYSCALE);
  		imagefilter($i2, IMG_FILTER_GRAYSCALE);

  		$colorMean1 = $this->colorMeanValue($i1);
  		$colorMean2 = $this->colorMeanValue($i2);

  		$bits1 = $this->bits($colorMean1);
  		$bits2 = $this->bits($colorMean2);

  		$hammeringDistance = 0;

  		for($a = 0;$a<64;$a++){
  			if($bits1[$a] != $bits2[$a]){
  				$hammeringDistance++;
  			}
  		}

  		return $hammeringDistance;
  	}

    public function submit(Request $request1 , Request $request2){
      echo $this->compare($request1->first_image , $request2->second_image);
    }



    public function init(Request $request){

      $img1 = $request->first_image;
      $img2 = $request->second_image;
      $path= Profile::where('cin' , '581117247473')->first();
      $path_front = $path->image_front;


echo typeof($img1) . ' is type';
$img_front = (Storage::url($path_front));

  if(file_exists(storage_path() .'/app/public/' . $path_front)){
    echo 'found one ' . storage_path() . ' ';
    echo Storage::size('public/' . $path_front);
  }
$img_front = (Storage::url($path_front));

      echo '<img src="'.asset($img_front).'">';
      echo asset(storage_path().'/app/public/');
      if(Storage::has('public/' . $path_front)){
        echo ' found two ' . 'http://localhost/storage/app/public' . $path_front;
      }
      $content = Storage::get('public/' . $path_front);
// echo typeof($content);

      // $img2 = Image::make(asset($img_front));
      // $img2 = imagecreatefrompng(asset($img_front));
      // echo $content . ' image2';
      // echo $this->compare($img1 , $img2 );
    }

  }

  // $type = File::mimeType(storage_path() .'/app/public/' . $path_front);
  // echo 'type ' . $type;
  //   $response = Response::make($content, 200);
  //   $response->header("Content-Type", $type);
