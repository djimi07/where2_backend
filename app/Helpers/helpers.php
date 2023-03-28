<?php
use Twilio\Rest\Client;
//GET USER AUTH
function get_auth()
{
    $auth_user = Auth::user();
    return $auth_user;
}

function get_fire_base()
{
    $config = array(
        "apiKey"=> "AIzaSyBBX7gGYJupEPoKC1Mtxd3u0v2eZ_o0PEM",
        "authDomain"=> "where2-17539.firebaseapp.com",
        "databaseURL"=> "https://where2-17539.firebaseio.com",
        "projectId"=> "where2-17539",
        "storageBucket"=> "where2-17539.appspot.com",
        "messagingSenderId"=> "526255485034"
    );
      return $config;
}

//SEND MAIL
function send_mail($request)
{
    try
    {
            $check = Mail::send($request->template,$request->data,function($message) use($request){
            $message->subject($request->subject);
            $message->to($request->receiver_email);
          
        });

        if (Mail::failures()) {
            return false;
        }

        return true;
    }
    catch(Exception $ex){
        dd($ex);
        return false;
    }
}

// JSON RESPONSE
function json_response($array,$status)
{
    return response()->json($array,$status,[],JSON_NUMERIC_CHECK);
}

function generate_key($string)
{
    $string = trim(strtoupper($string));
    $text = str_replace(' ','_',$string);
    return $text;
}

//GET ADMIN
function get_admin()
{
    $web_token =  Session::get('AuthAdminWebToken');
    $auth_user = \App\Models\User::where('web_token','=',$web_token)->first();
    return $auth_user;
}
function get_owner()
{
    $web_token =  Session::get('AuthOwnerWebToken');
    $auth_user = \App\Models\User::where('web_token','=',$web_token)->first();
    return $auth_user;
}
//GET USER WEB AUTH
function get_user_web_auth()
{
    $web_token =  Session::get('AuthUserWebToken');
    if(empty($web_token))
        return false;
    $auth_user = \App\Models\User::where('web_token','=',$web_token)->first();
    return $auth_user;
}

function set_date_format($created_at)
{
    // $date = Date('d M Y H:i',strtotime($created_at));
    $date = Date('m/d/Y g:i A',strtotime($created_at));
    $new_date = str_replace('00:00','',$date);
    return $new_date;
}

function strlenghtlimit($char)
{
   $value = \Illuminate\Support\Str::limit($char ?? '',90,'....');
   return $value;
}

function sendMessage($message, $recipients)
{
    $account_sid =env('TWILIO_SID');
    $auth_token = env('TWILIO_AUTH_TOKEN');
    $twilio_number =env('TWILIO_NUMBER');
    $client = new Client($account_sid, $auth_token);
    $client->messages->create($recipients, 
            ['from' => $twilio_number, 'body' => $message] );
}
function N_format($num)
{
   $formate_num= number_format($num);
   return  $formate_num;
}
function export(){
    $headers = array(
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=file.csv",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    );

    $reviews = User::select('*')->where('userType',1)->get();
    $columns = array('Mobile Number','login Date','firebase token','web token','Created at');

    $callback = function() use ($reviews, $columns)
    {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach($reviews as $review) {
            fputcsv($file, array($review->userMobile, $review->login_date, $review->firebase_token, $review->web_token, $review->created_at));
        }
        fclose($file);
    };
    return response()->stream($callback, 200, $headers);
}
function push_notification($data=array())
{
    
      if(!isset($data['title']))
      {
         $data['title']='Where2 App';
      }
      if(!isset($data['body']))
      {
         $data['body']='Where2 App App test notification';
      }
      $device_token=$data['device_token'];
      if(!isset($data['device_token']))
      {
         $device_token='xxsx';
      }

      $data['priority'] = "high";
      $data['content_available'] = true;
      $data['icon'] = 'logo';
      
        $fields=array(
        "to" => $device_token,
        "data"=>$data,
        "notification"=>$data,
        );

        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = array(
        'Authorization: key='.config('constants.FIREBASE_API_KEY'),
        'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
        return $result;


}

function Image_url($image="")
{
    if(isset($image) && !empty($image))
    $path =  url('storage/app/public/image/').'/'.$image;
    return @$path;
}

function get_user_name($id)
{
    $user =  DB::table('users')->where('userId',$id)->first();
   
    return (object)$user;
}
function get_restaurant_name($id)
{
    $res = DB::table('bar_restaurants')->where('restaurantId',$id)->first();
    return $res->name;
}
function get_restaurant_name2($id)
{
    $res = DB::table('bar_restaurants')->where('bar_restaurants.restaurantId',$id)
                                    ->join('images','bar_restaurants.restaurantId','=','images.restaurantId')
                                    ->groupBy('images.restaurantId')
                                    ->select(["bar_restaurants.*","images.imageName as imageUrl","images.type as imageType"])     
                                    ->first();
    return $res;
}
function get_restaurant_info($id)
{
    $res = DB::table('bar_restaurants')->where('restaurantId',$id)->first();
   
    return $res;
}
function find_distance($lat1, $lon1, $lat2, $lon2, $unit) {
    if (($lat1 == $lat2) && ($lon1 == $lon2)) {
      return 0;
    }
    else {
      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit = strtoupper($unit);
  
      if ($unit == "K") {
        return ($miles * 1.609344);
      } else if ($unit == "N") {
        return ($miles * 0.8684);
      } else {
        return $miles;
      }
    }
  }
function convertTimeToUTC($str, $userTimezone, $format = 'Y-m-d H:i:s'){
        
    $new_str = new DateTime($str, new DateTimeZone($userTimezone) );
    $new_str->setTimeZone(new DateTimeZone('UTC'));
    return $new_str->format( $format);
}
function get_bar_restaurant($url,$params="")
{
    $curl = curl_init();
    $_url =$url;
    if(!empty($params))    
    $_url = $url . '?' . http_build_query($params);
    curl_setopt_array($curl, array(
    CURLOPT_URL => $_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    // CURLOPT_HTTPHEADER => array(
    //     "Authorization: Bearer 1QFBUK8bZz6nDBQZRo-SOHBkML7c63YaBnDyqaTzhBj_LZ4JKA7gsAyzL1Bw6igf6QCnUfOBDYPDkrm0HXAwz8dVUp6tW75SjkwIYXoIIyaXlPgn9O5d1_hcf-HQXnYx"
    // ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response);

}
 
?>