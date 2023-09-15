<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bar_Restaurant;
use App\Models\User;
use App\Models\Deal;
use App\Models\Comment;
use App\Models\Friend;
use App\Models\Images;
use App\Models\Checkedin;
use App\Models\Preselectcomment;
use App\Models\Favourite;
use App\Models\Invite_friend;
use Carbon\Carbon;
use DB;
use Auth;
use Validator;
use Illuminate\Support\Facades\Storage;

class Bar_RestaurantController extends Controller
{
    public function search_api_from_yelp(request $request)
    {
        $rules['location'] = 'required';
        if ($request->radius)
            $rules['radius'] = 'numeric';
        $msg['location.required'] = trans('msg.req_location');
        $msg['search_text.required'] = trans('msg.req_search_text');
        $msg['latitude.numeric'] = trans('msg.num_latitude');
        $msg['longitude.numeric'] = trans('msg.num_longitude');
        $msg['radius.numeric'] = trans('msg.num_radius');
        // $msg['radius.digits_between']   =   trans('msg.d_b_radius');
        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => 400, 'msg' => $validator->messages()->first()], 400);
        if ($request->radius)
            $radius = $request->radius * 1609;
        $param = array(
            'query' => 'bars ' . $request->search_text . '+in+' . $request->location,
            'key' => config('constants.google_api_key'),

        );
        if (!empty($request->radius))
            $param['radius'] = @$request->radius;

        if (!empty($request->next_page_token))
            $param['pagetoken'] = @$request->next_page_token;

        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json";
        $data = get_bar_restaurant($url, $param);
        if (@$data->results)
        {
            foreach ($data->results as $key => $val)
            {
                $arr[$key] = $val;
                $is_exists = Bar_Restaurant::where('yelpId', $val->place_id)->first();
                if ($is_exists)
                {
                    $exist = 1;
                    $restaurantId = $is_exists->restaurantId;
                }
                else
                {
                    $exist = 0;
                }
                $arr[$key]->exist = @$exist;
                $arr[$key]->restaurantId = @$restaurantId;
            }
            $html_response = view('admin.Bar_Restaurant.search', ['data' => $data->results])->render();
            return response()->json(['status' => true, 'response' => $data->results, 'next' => @$data->next_page_token, 'html' => $html_response, 'msg' => 'success'], 200);
        }
        return response()->json(['status' => false, 'total' => 0], 200);
    }

    public function get_api_bar_restaurant(request $request)
    {
        $user = Auth::user();
        $model = new Bar_Restaurant;
        $request->userId = $user->userId;
        $res = $model->search_bar_restaurant_api($request);
        User::where('userId', $user->userId)->update(['latitude' => @$request->latitude, 'longitude' => @$request->longitude]);
        $result_arr = array();
        foreach ($res['list'] as $key => $val)
        {
            $res[$key] = $val;
            if ($val->type == 1 && $val->imageType == 1)
            {
                $res[$key]->imageUrl = 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=' . $val->imageUrl . '&key=AIzaSyDRTvKgRxnqrA8FJt_-n4EvgtK-N64GoFg';
            }
            else
            {
                $res[$key]->imageUrl = $val->imageUrl;
            }
            $deal = Deal::select('offer', 'description')->where(['restaurantId' => $val->restaurantId, 'hot_deal' => 1])->first();
            if (@$deal)
                $res[$key]->hotDeal = @$deal->offer;
            else
                $res[$key]->hotDeal = null;

            $is_fav = Favourite::where(['userId' => $user->userId, 'restaurantId' => $val->restaurantId, 'status' => 1])->first();
            if ($is_fav)
                $res[$key]->isFav = true;
            else
                $res[$key]->isFav = false;
            $result_arr[] = $res;
        }
        return json_response(['list' => @$res['list'], 'is_next' => @$res['next'], 'message' => 'Success'], 200);

    }
    /// Admin Panel
    public function search_api(request $request)
    {
        $model = new Bar_Restaurant;
        $response = $model->search_bar_restaurant($request);
        $html_response = view('admin.Bar_Restaurant.search2', ['data' => $response['list']])->render();
        return response()->json(['status' => 200, 'rating' => $response['list'], 'response' => $response['list'], 'total' => $response['total'], 'html_response' => $html_response, 'msg' => 'success'], 200);
    }
    /// Owner Panel
    public function owner_search_api(request $request)
    {
        $model = new Bar_Restaurant;
        $request->filter_status = 1;
        $response = $model->search_bar_restaurant($request);
        $html_response = view('owner.search.search_bar_restaurant', ['data' => $response['list']])->render();
        return response()->json(['status' => 200, 'rating' => $response['list'], 'response' => $response['list'], 'total' => $response['total'], 'html_response' => $html_response, 'msg' => 'success'], 200);
    }
    public function Delete(request $request)
    {
        $dd = DB::table('bar_restaurants')->where("restaurantId", $request->id)->delete();
        // $images =Images::where("restaurantId",$request->id)->get();

        // foreach($images as $val)
        // {
        //     $upload_extension =  explode("/", $val->imageName);
        //     $image_name = end($upload_extension);
        //     $old_image_path = 'storage/bar_restaurant_image/'.$image_name;
        //     if(file_exists($old_image_path))
        //         unlink($old_image_path);
        // }

        // $dd =Images::where("restaurantId",$request->id)->delete();
        if ($dd)
        {
            return response()->json(['status' => 200, 'response' => 'success', 'msg' => trans('msg.req_delete')], 200);
        }
        if ($dd)
        {
            return response()->json(['status' => 200, 'response' => 'success', 'msg' => trans('msg.req_delete')], 200);

        }
    }
    function Edit($id)
    {
        $data = Bar_Restaurant::where("restaurantId", $id)->first();
        $image = Images::where("restaurantId", $id)->get();
        $html = view('admin.Bar_Restaurant.add_form', ['data' => $data, 'image' => $image])->render();
        return response()->json(['status' => 200, 'response' => $html, 'data' => $data], 200);
    }
    //Owner Panel
    function edit_basic_details($id)
    {
        $data = Bar_Restaurant::where("restaurantId", $id)->first();
        $image = Images::where("restaurantId", $id)->get();
        $html = view('owner.search.edit_bar_restaurant', ['data' => $data, 'image' => $image])->render();
        return response()->json(['status' => 200, 'response' => $html, 'data' => $data], 200);
    }
    function getLatLong($request)
    {
        if (!empty($request))
        {
            //Formatted address
            $format = str_replace(' ', ',', $request->address);
            $country = str_replace(' ', ',', $request->country);
            $city = str_replace(' ', ',', $request->city);
            $state = str_replace(' ', ',', $request->state);
            //Send request and receive json data by address
            $formattedAddr = $format . '+' . $city . '+' . $state . '+' . $country . '+' . $request->zipCode;
            $geocodeFromAddr = file_get_contents("https://maps.googleapis.com/maps/api/place/textsearch/json?key=AIzaSyDRTvKgRxnqrA8FJt_-n4EvgtK-N64GoFg&query=" . $formattedAddr . "&sensor=false");
            $output = json_decode($geocodeFromAddr);
            //Get latitude and longitute from json data
            @$data['latitude'] = $output->results[0]->geometry->location->lat;
            @$data['longitude'] = $output->results[0]->geometry->location->lng;
            //Return latitude and longitude of the given address
            if (!empty($data))
            {
                return $data;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    public function add_or_update(request $request)
    {
        $rules['name'] = 'required';
        $rules['phone'] = "required|numeric|digits:10";
        $rules['address'] = 'required';
        $rules['zipCode'] = 'required|numeric|digits_between:4,10';
        $rules['country'] = 'required';
        $rules['state'] = 'required';
        $rules['city'] = 'required';
        if (!empty($request->restaurantId))
        {
            $rules['latitude'] = 'required|numeric';
            $rules['longitude'] = 'required|numeric';
        }
        // $rules['distance'] = 'required|numeric';
        $rules['rating'] = 'required';


        if (empty($request->restaurantId))
        {
            $rules['imageUrl'] = 'required';
        }
        if (empty($request->restaurantId))
        {
            $lat_long = $this->getLatLong($request);
            $request->latitude = $lat_long['latitude'];
            $request->longitude = $lat_long['longitude'];

        }

        $msg['name.required'] = trans('msg.req_bar_name');
        $msg['city.required'] = trans('msg.req_city');
        $msg['phone.required'] = trans('msg.req_phone');
        $msg['address.required'] = trans('msg.req_address');
        $msg['zipCode.required'] = trans('msg.req_zipCode');
        $msg['zipCode.digits_between'] = trans('msg.between_zipCode');
        $msg['state.required'] = trans('msg.req_zipCode');
        $msg['country.required'] = trans('msg.req_zipCode');
        $msg['latitude.required'] = trans('msg.req_latitude');
        $msg['longitude.required'] = trans('msg.req_longitude');
        $msg['country.required'] = trans('msg.req_country');
        $msg['rating.required'] = trans('msg.req_rating');
        $msg['phone.numeric'] = trans('msg.req_phone_numeric');
        $msg['phone.max_phone'] = trans('msg.req_phone_range');
        $msg['imageUrl.required'] = trans('msg.req_image');
        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);

        $model = new Bar_Restaurant;
        if (empty($request->restaurantId))
            $request->type = 2;
        $response = $model->Add_and_Update($request);
        if ($response->count())
        {
            $userfavBar = Favourite::select('userId')->where(['restaurantId' => $request->restaurantId, 'status' => 1])->get();
            if ($userfavBar)
            {
                $userDetails = User::whereIn('userId', $userfavBar)->get();
                $barDetails = Bar_Restaurant::where('restaurantId', $request->restaurantId)->first();
                foreach ($userDetails as $val)
                {
                    $notification = array(
                        'device_token' => $val->fcm_token,
                        'body' => 'Your favorite marked ' . $barDetails->name . ' has some updated details for you to review.',
                    );
                    Invite_friend::where(['receiverId' => $val->userId, 'restaurantId' => $barDetails->restaurantId, 'type' => 3])->delete();
                    push_notification($notification);
                    $obj = new Invite_friend();
                    $obj->date = date('Y-m-d');
                    $obj->senderId = 0;
                    $obj->receiverId = $val->userId;
                    $obj->restaurantId = $barDetails->restaurantId;
                    $obj->type = 3;
                    $obj->save();
                }
            }
            if (@$request->restaurantId)
                return json_response(['status' => true, 'message' => trans('msg.succ_update'), 'data' => @$response], 200);
            else
                return json_response(['status' => true, 'message' => trans('msg.succ_insert_bar_restaurant'), 'data' => @$response,], 200);
        }


    }

    function update_status(request $request)
    {
        $Model = new Bar_Restaurant();
        $response = $Model->update_owner_status($request);

        if ($response->status == 1)
            $msg = trans('msg.publish');
        else
            $msg = trans('msg.unpublish');
        return response()->json(['status' => 200, 'msg' => $msg], 200);
    }

    public function add_single_bar_restaurant(request $request)
    {
        $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . $request->id . "&key=AIzaSyDRTvKgRxnqrA8FJt_-n4EvgtK-N64GoFg";
        $data = get_bar_restaurant($url);
        // print_r($data);die;
        $is_exists = Bar_Restaurant::where('yelpId', $request->id)->exists();
        if ($is_exists)
        {
            return json_response(['status' => false, 'message' => trans('msg.unique_bar_res')], 200);
        }
        $model = new Bar_Restaurant();
        $data->result->type = 1;
        $response = $model->single_add($data->result);
        if ($response)
            return json_response(['status' => true, 'data' => $response, 'message' => trans('msg.succ_insert')], 200);
    }

    function upload_images(request $request)
    {
        $request->destinationPath = "storage/temp_image";
        $request->image_pre_name = "bar_restaurant_";
        $imageHelper = new \ImageHelper(); // CALL IMAGE HELPER
        $imageResponse = $imageHelper->upload_image($request);
        return response()->json(['status' => 200, 'response' => 'success', 'temp_image_url' => $imageResponse->temp_image_url, 'image' => $imageResponse->image], 200);
    }

    function get_details_api(request $request)
    {
        $user = Auth::user();
        $rules['restaurantId'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);

        $response = Bar_Restaurant::select('restaurantId', 'ownerId', 'name', 'imageUrl', 'address', 'city', 'state', 'country', 'zipCode', 'phone', 'reviewCount', 'rating', 'latitude', 'longitude', 'description', 'is_bold', 'color', 'fontSize')->where('restaurantId', $request->restaurantId)->first();
        $images = Images::where('restaurantId', $request->restaurantId)->get();
        if (!empty($response))
        {
            $deal = Deal::select('dealId', 'eventType', 'title', 'eventName', 'offer', 'bogo', 'description', 'imageUrl', 'enddate')->where(['restaurantId' => $request->restaurantId, 'status' => 1])->get();
            $x_deal = array();
            foreach ($deal as $key => $val1)
            {
                if (@$val1->eventType == 1)
                {
                    if (@$val1->enddate >= date('Y-m-d'))
                    {
                        $x_deal[] = $val1;
                    }
                }
                else
                {
                    $x_deal[] = $val1;
                }
            }
            $s1 = Carbon::now();
            $s2 = Carbon::parse('today')->format('Y-m-d') . ' 06:00:00';
            $t1 = strtotime($s1);
            $t2 = strtotime($s2);
            $comment = new Comment();
            $comment = $comment->where(['restaurantId' => $request->restaurantId]);
            if ($t1 < $t2)
                $comment = $comment->where('comments.created_at', '>=', Carbon::parse('yesterday')->format('Y-m-d') . ' 6:00:00');
            else
                $comment = $comment->where('comments.created_at', '>=', Carbon::parse('today')->format('Y-m-d') . ' 6:00:00');
            $comment = $comment->join('users', 'comments.userId', '=', 'users.userId');

            $comment = $comment->select('comments.commentId', 'comments.userId', 'comments.comment', 'users.userName', 'users.firstName', 'users.lastName', 'users.userProfilePicture', 'comments.created_at as date');
            $comment = $comment->get();
            foreach ($comment as $key => $val)
            {
                $com[$key] = $val;
                $com[$key]->date = convertTimeToUTC(@$val->date, 'EST');
            }

            //dateformate "%d%b|%h.%i %p"
            $friend_list = Friend::select('userId', 'friendId')->where('userId', $user->userId)->orwhere('friendId', $user->userId)->get();


            $is_user_checkin = Checkedin::where(['userId' => $user->userId, 'restaurantId' => $request->restaurantId, 'status' => 1, 'date' => date('Y-m-d')])->exists();
            if ($is_user_checkin)
            {
                $is_checking = true;
            }
            else
            {
                $is_checking = false;
            }

            $is_already_checkin = Checkedin::where(['userId' => $user->userId, 'status' => 1, 'date' => date('Y-m-d')])->first();
            if ($is_already_checkin)
            {
                $is_Already_checking = true;
                $barname = DB::table('bar_restaurants')->where('restaurantId', $is_already_checkin->restaurantId)->first();
                $BarName = $barname->name;
            }
            else
            {
                $is_Already_checking = false;
            }
            $checkin_friend = array();
            foreach ($friend_list as $value)
            {
                if ($user->userId == $value->userId)
                {
                    $is_exist = Checkedin::join('users', 'checkedins.userId', '=', 'users.userId')
                        ->where(['checkedins.userId' => $value->friendId, 'checkedins.restaurantId' => $request->restaurantId, 'checkedins.status' => 1, 'checkedins.date' => date('Y-m-d')])
                        ->select('checkedins.userId', 'users.firstName', 'users.lastName')
                        ->first();
                    if ($is_exist)
                    {
                        $checkin_friend[] = $is_exist;
                    }
                }
                if ($user->userId == $value->friendId)
                {
                    $is_exist = Checkedin::join('users', 'checkedins.userId', '=', 'users.userId')
                        ->where(['checkedins.userId' => $value->userId, 'checkedins.restaurantId' => $request->restaurantId, 'checkedins.status' => 1, 'checkedins.date' => date('Y-m-d')])
                        ->select('checkedins.userId', 'users.firstName', 'users.lastName')
                        ->first();
                    if ($is_exist)
                    {
                        $checkin_friend[] = $is_exist;
                    }
                }
            }
            $people = Checkedin::join('users', 'checkedins.userId', '=', 'users.userId')
                ->where(['checkedins.restaurantId' => $request->restaurantId, 'checkedins.status' => 1, 'checkedins.date' => date('Y-m-d')])->where('checkedins.userId', '!=', $user->userId)
                ->select('checkedins.userId', 'users.firstName', 'users.lastName')
                ->get();
            $is_fav = Favourite::where(['userId' => $user->userId, 'restaurantId' => $request->restaurantId, 'status' => 1])->first();
            if ($is_fav)
                $IsFav = true;
            else
                $IsFav = false;

            $people_count = count($people);
            $friend = count($checkin_friend);
            $data = array();
            $res = @$response;
            $res['isCheckedin'] = @$is_checking;
            $res['isAlreadyCheckedIn'] = @$is_Already_checking;
            $res['checkedInBar'] = @$BarName;
            $res['friendList'] = @$people;
            $res['checkedInList'] = @$friend . ' friends And ' . @$people_count . ' ' . 'People Here';
            $res['deals'] = @$x_deal;
            $res['preSelectedComment'] = Preselectcomment::select('precommentId', 'comment')->get();
            $res['isFav'] = @$IsFav;
            $res['comments'] = @$com;
            $res['images'] = @$images;
            $data = $res;
            return json_response(['status' => true, 'restaurantDetail' => @$data, 'message' => 'Success'], 200);
        }
        return json_response(['status' => false, 'message' => 'This bar is deleted by the admin.'], 200);
    }

    public function get_comment(request $request)
    {
        $comment = new Comment();
        $res = $comment->get_comments_list($request);
        $html = view('admin.Bar_Restaurant.comment', ['data' => $res['data']])->render();
        return json_response(['status' => true, 'response' => $res['data'], 'total' => $res['total'], 'html_response' => $html, 'msg' => 'success'], 200);

    }

    public function assign_owner($id)
    {

        $rest = Bar_Restaurant::where('restaurantId', $id)->first();
        $owner = User::select('userId', 'firstName', 'lastName', 'userType')->where(['userType' => 1, 'is_owner' => 1])->get();
        $html = view('admin.Bar_Restaurant.add_form_assign_owner', ['restaurantId' => $id, 'owner' => $owner])->render();
        return response()->json(['status' => 200, 'response' => $html, 'data' => @$rest->ownerId], 200);
    }
    public function add_update_assign_owner(request $request)
    {
        $rules['ownerId'] = 'required';
        $msg['ownerId.required'] = trans('msg.req_ownerId');
        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);
        $model = new Bar_Restaurant;
        // $request->type =2;
        $response = $model->Add_and_Update($request);
        if ($response->count())
        {
            return json_response(['status' => true, 'message' => trans('msg.succ_assign_owner')], 200);
        }
    }

    function Comment_api(request $request)
    {
        $user = Auth::user();
        $rules['restaurantId'] = 'required';
        $rules['commentText'] = 'required';

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);
        $model = new Comment();
        $request->userId = $user->userId;
        $response = $model->add_comment($request);
        $data = array(
            "commentId" => $response->CommentId,
            "userId" => $response->userId,
            "comment" => $response->comment,
            "firstName" => $user->firstName,
            "lastName" => $user->lastName,
            "date" => convertTimeToUTC($response->created_at, 'EST')
        );
        return response()->json(['status' => true, 'comments' => $data, 'message' => trans('msg.add_comment')], 200);
    }

    public function delete_img(request $request)
    {
        $count = Images::where("restaurantId", $request->res)->count();
        if ($count == 1)
        {
            return response()->json(['status' => 400, 'response' => 'success', 'msg' => trans('msg.error_delete')], 200);
        }
        $image = Images::where("imageId", $request->id)->first();
        $upload_extension = explode("/", $image->imageName);
        $image_name = end($upload_extension);
        $old_image_path = 'storage/app/public/image/' . $image_name;
        /*
        if (file_exists($old_image_path))
        unlink($old_image_path);
        */

        if (Storage::disk('s3')->exists($old_image_path))
        {
            $deleted = Storage::disk('s3')->delete($old_image_path);
        }

        $dd = Images::where("imageId", $request->id)->delete();
        if ($dd)
        {
            return response()->json(['status' => 200, 'response' => 'success', 'msg' => trans('msg.req_delete')], 200);
        }

    }
    public function update_bar_res(request $request)
    {
        $rules['description'] = 'required';
        $msg['description.required'] = trans('msg.req_description');

        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);

        $model = new Bar_Restaurant;
        $response = $model->Add_and_Update($request);
        if ($response->count())
        {
            return json_response(['status' => true, 'message' => trans('msg.succ_update')], 200);

        }

    }


    public function getBars($zipcode = "")
    {

        // $arr = array(
        // '309'=>43085,
        // '317'=>43085,
        // '510'=>43110,
        // '562'=>43240,
        // '572'=>43240,
        // '586'=>43240,
        // '981'=>45255
        // );


        // foreach ($arr as $key => $value) {
        //     echo $key.'-'.$value;
        //     DB::table('bar_restaurants')->where('restaurantId',$key)->update(['zipCode'=>$value]);
        // }

        $param = array(
            'key' => config('constants.google_api_key'),
            // 'query'=>'bars+in+Cincinnati+'.$zipcode,
            'query' => 'bars+in+Cincinnati',
            'type' => 'bar',
            'radius' => 10000,
        );

        $token = DB::table('cron_token')->where('type', 1)->first();
        if (!empty($token->nextpage))
        {
            $param['pagetoken'] = $token->nextpage;
        }
        // get token for Cincinnati
        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json";
        $data = get_bar_restaurant($url, $param);
        dd($data);
        die();


        if (@$data->results)
        {

            if (!empty($data->next_page_token))
            {

                DB::table('cron_token')->where('type', 1)->update(['nextpage' => $data->next_page_token]);

            }
            else
            {
                DB::table('cron_token')->where('type', 1)->update(['nextpage' => ""]);
            }

            foreach ($data->results as $row)
            {
                $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . $row->place_id . "&key=AIzaSyDRTvKgRxnqrA8FJt_-n4EvgtK-N64GoFg";
                $data2 = get_bar_restaurant($url);
                $is_exists = Bar_Restaurant::where('yelpId', $row->place_id)->exists();
                if ($is_exists)
                {
                    echo "continue";
                    continue;
                }
                $model = new Bar_Restaurant();
                $data2->type = 1;
                $response = $model->single_add($data2->result);
            }
            return response()->json(['status' => true, 'response', 'is_next' => @$data->next_page_token, 'msg' => 'success'], 200);
        }
        return response()->json(['status' => false, 'msg' => 'No data found'], 200);
    }

    // nextpage
    public function getBarsColumbus()
    {
        $param = array(
            'key' => config('constants.google_api_key'),
            'query' => 'bars+in+Columbus',

        );
        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json";
        $data = get_bar_restaurant($url, $param);
        if (@$data->results)
        {
            foreach ($data->results as $row)
            {
                $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . $row->place_id . "&key=AIzaSyDRTvKgRxnqrA8FJt_-n4EvgtK-N64GoFg";
                $data2 = get_bar_restaurant($url);

                $is_exists = Bar_Restaurant::where('yelpId', $row->place_id)->exists();
                if ($is_exists)
                {
                    continue;
                }
                $model = new Bar_Restaurant();
                $data2->type = 1;
                $response = $model->single_add($data2->result);
            }
            return response()->json(['status' => true, 'response', 'is_next' => $data->next_page_token, 'msg' => 'success'], 200);
        }
        return response()->json(['status' => false, 'msg' => 'No data found'], 200);
    }


    public function insertzipcode()
    {
        die();
        // $codes = DB::table('zipcodes')->offset(50)->limit(50)->get();
        $codes = DB::table('zipcodes')->where('city', "")->get();
        foreach ($codes as $zip)
        {
            $zipcode = $zip->zipcode;
            echo $zipcode . '<br>';
            // continue;
            $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $zipcode . '&key=AIzaSyBnEdRdrnZIUrKcP7_jPmDUpoxDhwIUQ4g';
            $data = file_get_contents($url);
            $data = json_decode($data);
            // echo "<pre>";
            // print_r($data);
            // die();

            $city = "";

            if (empty($data->results))
            {
                echo "empty";
                die();
            }

            foreach ($data->results[0]->address_components as $key)
            {

                if ($key->types[0] == "locality")
                {
                    $city = $key->long_name;
                }
            }

            // echo "<pre>";
            // print_r($key);
            // die();

            // if(isset($data->results[0]->geometry->location->lat)){
            $lat = $data->results[0]->geometry->location->lat;
            $lng = $data->results[0]->geometry->location->lng;
            DB::table('zipcodes')->where('zipcode', $zipcode)->update(['lat' => $lat, 'lng' => $lng, 'city' => $city]);
            // }

            // die();

        }
        /*dd($codes);
        die();*/
        // $arr = array(43085,43201,43202,43203,43204,43205,43206,43207,43209,43210,43211,43212,43213,43214,43215,43216,43217,43218,43219,43220,43221,43222,43223,43224,43226,43227,43228,43229,43230,43231,43232,43234,43235,43236,43240,43251,43260,43265,43266,43268,43270,43271,43272,43279,43287,43291,43299);
        // $arr = array(45207,45217,45216,45202,45232,45242,45220,45244,45227,45238,45245,45203,45233,45240,45239,45255,45223,45252,45221,45209,45243,45251,45241,45225,45205,45211,45237,45231,45224,45230,45204,45247,45206,45214,45248,45208,45219,45229,45249,45236,45226,45215,45212,45218,45246,452130);
        // foreach ($arr as $row => $value) {
        //  // DB::table('zipcodes')->insert(['zipCode'=>$value]);
        // }

        // echo 'done';
    }


    public function cronGetPlaces()
    {
        $zip = DB::table('zipcodes')->where('isProccessed', 0)->first();

        if (!$zip)
        {
            exit();
        }


        $latlng = $zip->lat . ',' . $zip->lng;
        $zipcode = $zip->zipcode;

        $param = array(
            'key' => config('constants.google_api_key'),
            // 'query'=>'bars+in+Cincinnati+'.$zipcode,
            'type' => 'bar',
            'location' => $latlng,
            'radius' => 50000,
        );

        // $token=DB::table('cron_token')->where('type',1)->first();
        // if(!empty($token->nextpage))
        // {
        //     $param['pagetoken'] =$token->nextpage;
        // }


        // https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=39.1031182,-84.5120196&type=bar&radius=10000&key=AIzaSyCZuDdWc2zIWJlaDH-h8ylxXzAgdlZyM4w

        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json";
        $data = get_bar_restaurant($url, $param);
        // -

        // echo "<pre>";
        print_r($data->results);
        // die();
        if (@$data->results)
        {

            DB::table('zipcodes')->where('zipcode', $zipcode)->update(['isProccessed' => 1]);

            if (!empty($data->next_page_token))
            {
                $cronExist = DB::table('cron_token')->where('zipcode', $zipcode)->first();
                if ($cronExist)
                {
                    DB::table('cron_token')->where('zipcode', $zipcode)->update(['nextpage' => $data->next_page_token]);
                }
                else
                {
                    DB::table('cron_token')->insert(['nextpage' => $data->next_page_token, 'zipcode' => $zipcode]);
                }

            }

            foreach ($data->results as $row)
            {
                $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . $row->place_id . "&key=AIzaSyDRTvKgRxnqrA8FJt_-n4EvgtK-N64GoFg";
                $data2 = get_bar_restaurant($url);
                $is_exists = Bar_Restaurant::where('yelpId', $row->place_id)->exists();
                if ($is_exists)
                {
                    echo "continue";
                    continue;
                }
                $model = new Bar_Restaurant();
                $data2->type = 1;
                $response = $model->saveBarData($data2->result);
            }
            return response()->json(['status' => true, 'response', 'is_next' => @$data->next_page_token, 'msg' => 'success'], 200);
        }
        return response()->json(['status' => false, 'msg' => 'No data found'], 200);
    }


    public function cronNextPlaces()
    {
        $next = DB::table('cron_token')->where('nextpage', "!=", "")->first();



        if (!$next)
        {
            exit();
        }


        $zipcode = $next->zipcode;
        $nextpage = $next->nextpage;

        if (empty($zipcode))
        {
            return FALSE;
        }

        $zip = DB::table('zipcodes')->where('zipcode', $zipcode)->first();



        $latlng = $zip->lat . ',' . $zip->lng;
        $param = array(
            'key' => config('constants.google_api_key'),
            // 'query'=>'bars+in+Cincinnati+'.$zipcode,
            'type' => 'bar',
            'location' => $latlng,
            'radius' => 50000,
        );


        if (!empty($nextpage))
        {
            $param['pagetoken'] = $nextpage;
        }

        //   echo "<pre>";
        // print_r($param);
        // die();


        // https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=39.1031182,-84.5120196&type=bar&radius=10000&key=AIzaSyCZuDdWc2zIWJlaDH-h8ylxXzAgdlZyM4w

        $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json";
        $data = get_bar_restaurant($url, $param);
        // -

        // echo "<pre>";
        // print_r($data->results);
        // die();
        if (@$data->results)
        {

            // DB::table('zipcodes')->where('zipcode',$zipcode)->update(['isProccessed'=>1]);

            if (!empty($data->next_page_token))
            {
                $cronExist = DB::table('cron_token')->where('zipcode', $zipcode)->first();
                if ($cronExist)
                {
                    DB::table('cron_token')->where('zipcode', $zipcode)->update(['nextpage' => $data->next_page_token]);
                }
                else
                {
                    DB::table('cron_token')->insert(['nextpage' => $data->next_page_token, 'zipcode' => $zipcode]);
                }

            }
            else
            {
                DB::table('cron_token')->where('zipcode', $zipcode)->update(['nextpage' => ""]);
            }

            foreach ($data->results as $row)
            {
                $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . $row->place_id . "&key=AIzaSyDRTvKgRxnqrA8FJt_-n4EvgtK-N64GoFg";
                $data2 = get_bar_restaurant($url);
                $is_exists = Bar_Restaurant::where('yelpId', $row->place_id)->exists();
                if ($is_exists)
                {
                    echo "continue";
                    continue;
                }
                $model = new Bar_Restaurant();
                $data2->type = 1;
                $response = $model->saveBarData($data2->result);
            }
            return response()->json(['status' => true, 'response', 'is_next' => @$data->next_page_token, 'msg' => 'success'], 200);
        }
        return response()->json(['status' => false, 'msg' => 'No data found'], 200);
    }


    public function cronbyzip()
    {
        $zip = DB::table('zipcodes')->where('isProccessed', 0)->first();

        if (!$zip)
        {
            exit();
        }


        $latlng = $zip->lat . ',' . $zip->lng;
        $zipcode = $zip->zipcode;
        $city = $zip->city;
        echo $zipcode;
        $param = array(
            'key' => config('constants.google_api_key'),
            'query' => 'bars+in+' . $city . '+' . $zipcode,
            // 'type'=>'bar',
            // 'location'=>$latlng,
            'radius' => 10000,
        );

        // $token=DB::table('cron_token')->where('type',1)->first();
        // if(!empty($token->nextpage))
        // {
        //     $param['pagetoken'] =$token->nextpage;
        // }


        // https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=39.1031182,-84.5120196&type=bar&radius=10000&key=AIzaSyCZuDdWc2zIWJlaDH-h8ylxXzAgdlZyM4w

        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json";
        $data = get_bar_restaurant($url, $param);
        // -

        // echo "<pre>";
        // print_r($data);
        // die();
        if (@$data->results)
        {

            DB::table('zipcodes')->where('zipcode', $zipcode)->update(['isProccessed' => 1]);

            if (!empty($data->next_page_token))
            {
                $cronExist = DB::table('cron_token')->where('zipcode', $zipcode)->first();
                if ($cronExist)
                {
                    DB::table('cron_token')->where('zipcode', $zipcode)->update(['nextpage' => $data->next_page_token]);
                }
                else
                {
                    DB::table('cron_token')->insert(['nextpage' => $data->next_page_token, 'zipcode' => $zipcode]);
                }

            }

            foreach ($data->results as $row)
            {
                $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . $row->place_id . "&key=AIzaSyDRTvKgRxnqrA8FJt_-n4EvgtK-N64GoFg";
                $data2 = get_bar_restaurant($url);
                $is_exists = Bar_Restaurant::where('yelpId', $row->place_id)->exists();
                if ($is_exists)
                {
                    echo "continue";
                    continue;
                }
                $model = new Bar_Restaurant();
                $data2->type = 1;
                $response = $model->saveBarData($data2->result);
            }
            return response()->json(['status' => true, 'response', 'is_next' => @$data->next_page_token, 'msg' => 'success'], 200);
        }
        return response()->json(['status' => false, 'msg' => 'No data found'], 200);
    }


    public function cronNextByZip()
    {
        $next = DB::table('cron_token')->where('nextpage', "!=", "")->first();



        if (!$next)
        {
            exit();
        }


        $zipcode = $next->zipcode;
        $nextpage = $next->nextpage;

        if (empty($zipcode))
        {
            return FALSE;
        }

        $zip = DB::table('zipcodes')->where('zipcode', $zipcode)->first();



        $latlng = $zip->lat . ',' . $zip->lng;
        $city = $zip->city;
        $param = array(
            'key' => config('constants.google_api_key'),
            'query' => 'bars+in+' . $city . '+' . $zipcode,
            'radius' => 10000,
        );


        if (!empty($nextpage))
        {
            $param['pagetoken'] = $nextpage;
        }

        //   echo "<pre>";
        // print_r($param);
        // die();


        // https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=39.1031182,-84.5120196&type=bar&radius=10000&key=AIzaSyCZuDdWc2zIWJlaDH-h8ylxXzAgdlZyM4w

        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json";
        $data = get_bar_restaurant($url, $param);
        // -

        // echo "<pre>";
        // print_r($data);
        // die();
        if (@$data->results)
        {

            // DB::table('zipcodes')->where('zipcode',$zipcode)->update(['isProccessed'=>1]);

            if (!empty($data->next_page_token))
            {
                $cronExist = DB::table('cron_token')->where('zipcode', $zipcode)->first();
                if ($cronExist)
                {
                    DB::table('cron_token')->where('zipcode', $zipcode)->update(['nextpage' => $data->next_page_token]);
                }
                else
                {
                    DB::table('cron_token')->insert(['nextpage' => $data->next_page_token, 'zipcode' => $zipcode]);
                }

            }
            else
            {
                DB::table('cron_token')->where('zipcode', $zipcode)->update(['nextpage' => ""]);
            }

            foreach ($data->results as $row)
            {
                $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . $row->place_id . "&key=AIzaSyDRTvKgRxnqrA8FJt_-n4EvgtK-N64GoFg";
                $data2 = get_bar_restaurant($url);
                $is_exists = Bar_Restaurant::where('yelpId', $row->place_id)->exists();
                if ($is_exists)
                {
                    echo "continue";
                    continue;
                }
                $model = new Bar_Restaurant();
                $data2->type = 1;
                $response = $model->saveBarData($data2->result);
            }
            echo '<script type="text/javascript">setTimeout(function(){window.location.reload();},5000)</script>';
            // return response()->json(['status' =>true, 'response','is_next'=>@$data->next_page_token, 'msg' => 'success'], 200);
        }
        // return response()->json(['status' =>false, 'msg' => 'No data found'], 200);
    }



}
