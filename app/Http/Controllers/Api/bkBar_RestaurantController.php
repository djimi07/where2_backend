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
use Carbon\Carbon;
use DB;
use Auth;
use Validator;

class bkBar_RestaurantController extends Controller
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
        $res = $model->search_bar_restaurant_api($request);
        User::where('userId', $user->userId)->update(['latitude' => @$request->latitude, 'longitude' => @$request->longitude]);
        $result_arr = array();
        foreach ($res['list'] as $key => $val)
        {
            $res[$key] = $val;
            $res[$key]->imageUrl = 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=' . $val->imageUrl . '&key=AIzaSyDRTvKgRxnqrA8FJt_-n4EvgtK-N64GoFg';
            $deal = Deal::select('offer', 'description')->where(['restaurantId' => $val->restaurantId, 'hot_deal' => 1])->first();
            if (@$deal)
                $res[$key]->hotDeal = @$deal->offer;
            else
                $res[$key]->hotDeal = null;
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
        $dd = Bar_Restaurant::where("restaurantId", $request->id)->delete();
        $images = Images::where("restaurantId", $request->id)->get();

        foreach ($images as $val)
        {
            $upload_extension = explode("/", $val->imageName);
            $image_name = end($upload_extension);
            $old_image_path = 'storage/bar_restaurant_image/' . $image_name;
            if (file_exists($old_image_path))
                unlink($old_image_path);
        }

        $dd = Images::where("restaurantId", $request->id)->delete();
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

    public function add_or_update(request $request)
    {
        //  print_r($request->all());die;
        $rules['name'] = 'required';
        $rules['phone'] = "required|numeric|digits:10";
        $rules['address'] = 'required';
        $rules['zipCode'] = 'required|numeric|digits_between:4,10';
        $rules['country'] = 'required';
        $rules['state'] = 'required';
        $rules['city'] = 'required';
        $rules['latitude'] = 'required|numeric';
        $rules['longitude'] = 'required|numeric';
        // $rules['distance'] = 'required|numeric';
        $rules['rating'] = 'required';
        if (empty($request->restaurantId))
            $rules['imageUrl.0'] = 'required';

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
        $msg['imageUrl.0.required'] = trans('msg.req_image');
        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);

        $model = new Bar_Restaurant;
        $request->type = 2;
        $response = $model->Add_and_Update($request);
        if ($response->count())
        {
            if (@$request->restaurantId)
                return json_response(['status' => true, 'message' => trans('msg.succ_update')], 200);
            else
                return json_response(['status' => true, 'message' => trans('msg.succ_insert_bar_restaurant')], 200);
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
        $data->type = 1;
        $response = $model->single_add($data->result);
        if ($response)
            return json_response(['status' => true, 'data' => $response, 'message' => trans('msg.succ_insert')], 200);

    }

    function upload_images(request $request)
    {
        $request->destinationPath = "storage/temp_image/";
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

        $response = Bar_Restaurant::select('restaurantId', 'ownerId', 'name', 'imageUrl', 'address', 'city', 'state', 'country', 'zipCode', 'phone', 'reviewCount', 'rating', 'latitude', 'longitude', 'description')->where('restaurantId', $request->restaurantId)->first();
        $deal = Deal::select('dealId', 'eventType', 'title', 'eventName', 'offer', 'bogo', 'description', 'imageUrl')->where(['restaurantId' => $request->restaurantId, 'status' => 1])->get();
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
        //    print_r($people);die;
        $people_count = count($people);
        $friend = count($checkin_friend);
        $data = array();
        $res = @$response;
        $res['isCheckedin'] = @$is_checking;
        $res['friendList'] = @$people;
        $res['checkedInList'] = @$friend . ' friends And ' . @$people_count . ' ' . 'People Here';
        $res['deals'] = @$deal;
        $res['comments'] = @$com;
        $data = $res;
        return json_response(['status' => true, 'restaurantDetail' => @$data, 'message' => 'Success'], 200);
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
        $request->type = 2;
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
        if (file_exists($old_image_path))
            unlink($old_image_path);
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
            'query' => 'bars+in+Cincinnati+' . $zipcode,
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




}
