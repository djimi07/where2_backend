<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user;
use App\Models\Friend;
use App\Models\Bar_Restaurant;
use App\Models\Checkedin;
use App\Models\Invite_friend;
use Validator;
use Auth;
use DB;

class InviteControllerV2 extends Controller
{
    public function invite_friend(request $request)
    {
        $rules['restaurantId'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);
        $user = Auth::user();
        // print_r($user);die;
        // $userModel = new User();
        // $request->userType=1;
        // if(@$request->searchText)
        // $request->search_text =$request->searchText;

        // $request->invite_friend_select =1;
        // $response = $userModel->search_user($request);
        $model = new Friend();
        $request->userId = $user->userId;
        $ress = $model->get_friend_list($request);
        // print_r($ress->data);die;
        $data = array();
        foreach (@$ress->data as $key => $val)
        {
            $is_exists = Invite_friend::where(['senderId' => $user->userId, 'receiverId' => $val['userId'], 'restaurantId' => $request->restaurantId, 'type' => 1, 'date' => date('Y-m-d')])->exists();
            if (@$is_exists)
            {
                $isInvite = true;
            }
            else
            {
                $isInvite = false;
            }
            $is_exist = Invite_friend::where(['senderId' => $user->userId, 'receiverId' => $val['userId'], 'restaurantId' => $request->restaurantId, 'type' => 1, 'isAccept' => 1, 'date' => date('Y-m-d')])->exists();
            if (@$is_exist)
            {
                $isAccept = true;
            }
            else
            {
                $isAccept = false;
            }
            $res['userId'] = $val['userId'];
            $res['firstName'] = $val['firstName'];
            $res['lastName'] = $val['lastName'];
            $res['isInvite'] = @$isInvite;
            $res['isAccept'] = @$isAccept;
            $data[] = $res;

        }

        //return response()->json(['status' => true, 'data' => @$data, 'total' => @$response['total'], 'is_next' => @$response['next'], 'message' => 'success'], 200);
        return response()->json(['status' => true, 'data' => @$data, 'message' => 'success'], 200);
    }
    public function Search_friend(request $request)
    {
        $user = Auth::user();
        $userModel = new User();
        $request->userType = 1;
        if (@$request->searchText)
        {
            $request->search_text = $request->searchText;
            $request->invite_friend_select = 1;
            $response = $userModel->search_user($request);
            $data = array();
            foreach (@$response['data'] as $key => $val)
            {
                $is_exists = Invite_friend::where(['senderId' => $user->userId, 'receiverId' => $val->userId])->exists();
                if (@$is_exists)
                {
                    $request_sent = true;
                }
                else
                {
                    $request_sent = false;
                }

                $is_exist = Invite_friend::where(['senderId' => $user->userId, 'receiverId' => $val->userId, 'isAccept' => 1])->exists();
                if (@$is_exist)
                {
                    $isAccept = true;
                }
                else
                {
                    $isAccept = false;
                }
                $res[$key] = $val;
                $res[$key]->requestSent = @$request_sent;
                $res[$key]->isAccept = @$isAccept;
                $data = $res;

            }
            if ($data)
            {
                return response()->json(['status' => true, 'friends' => @$data, 'message' => 'success'], 200);
            }
        }
        return response()->json(['status' => false, 'friends' => null, 'message' => trans('msg.record_not_found')], 200);

    }
    public function send_friends_request(request $request)
    {
        $rules['receiverId'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);
        $user = Auth::user();
        $model = new Invite_friend();
        $request->userId = $user->userId;
        $request->type = 2;
        $response = $model->send_request($request);
        $receiver_info = get_user_name($request->receiverId);
        $notification = array(
            'device_token' => $receiver_info->fcm_token,
            'body' => @$user->firstName . ' ' . @$user->lastName . ' ' . config('constants.request'),
            'badge' => Invite_friend::where(['receiverId' => $user->userId, 'isAccept' => 0, 'type' => 2])->count('id')
        );
        push_notification($notification);
        return response()->json(['status' => true, 'data' => null, 'message' => trans('msg.send_friend_req')], 200);

    }

    public function Send_request(request $request)
    {
        $rules['receiverId'] = 'required';
        $rules['restaurantId'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);
        $user = Auth::user();
        $model = new Invite_friend();
        $request->userId = $user->userId;
        $request->type = 1;
        $response = $model->send_request($request);
        $receiver_info = get_user_name($request->receiverId);
        $res_name = get_restaurant_name($request->restaurantId);
        $notification = array(
            'device_token' => $receiver_info->fcm_token,
            'body' => @$user->firstName . ' ' . @$user->lastName . ' ' . config('constants.invitation') . ' ' . $res_name,
            'badge' => Invite_friend::where(['receiverId' => $request->receiverId, 'isAccept' => 0, 'type' => 1])->count('id')
        );
        push_notification($notification);
        return response()->json(['status' => true, 'data' => null, 'message' => trans('msg.send_invitation_req')], 200);

    }


    public function notification(request $request)
    {
        $user = Auth::user();
        $model = new Invite_friend();
        $response = $model->get_notification($request);

        if ($response->result->count())
        {
            $arr = array();
            foreach ($response->result as $val)
            {
                if ($val->type == 1 || $val->type == 2)
                {
                    $sinfo = get_user_name($val->senderId);
                    $data['id'] = $val->id;
                    $data['firstName'] = @$sinfo->firstName;
                    $data['lastName'] = @$sinfo->lastName;
                }
                $data['date'] = convertTimeToUTC(@$val->date, 'EST');
                if ($val->type == 1)
                {

                    $res_name = get_restaurant_name($val->restaurantId);

                    $res_info = get_restaurant_info($val->restaurantId);
                    $data['message'] = config('constants.invitation');
                    $data['mergeMsg'] = @$sinfo->firstName . ' ' . @$sinfo->lastName . ' ' . config('constants.invitation') . ' ' . $res_name;
                    $data['notificationType'] = 1; ////Invitation
                    $data['restaurantName'] = $res_name;
                    $data['notificationName'] = "Invitation";
                    $data['restaurantId'] = $val->restaurantId;
                    $data['distance'] = find_distance($user->latitude, $user->longitude, $res_info->latitude, $res_info->longitude, '');
                    $data['imageUrl'] = "";
                }
                if ($val->type == 2)
                {
                    $data['message'] = config('constants.request');
                    $data['mergeMsg'] = @$sinfo->firstName . ' ' . @$sinfo->lastName . ' ' . config('constants.request');
                    $data['notificationType'] = 2; /////Request
                    $data['notificationName'] = "Request";
                    $data['imageUrl'] = "";
                    $data['restaurantName'] = "";

                }
                if ($val->type == 3)
                {
                    $res_info2 = get_restaurant_name2($val->restaurantId);
                    $data['message'] = 'Your favorite marked ' . $res_info2->name . ' has some updated details for you to review.';
                    $data['mergeMsg'] = 'Your favorite marked ' . $res_info2->name . ' has some updated details for you to review.';
                    $data['notificationType'] = 3; /////Favourite Bars
                    $data['restaurantId'] = $val->restaurantId;
                    $data['restaurantName'] = $res_info2->name;
                    if ($res_info2->type == 1 && $res_info2->imageType == 1)
                    {
                        $data['imageUrl'] = 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=' . $res_info2->imageUrl . '&key=AIzaSyDRTvKgRxnqrA8FJt_-n4EvgtK-N64GoFg';
                    }
                    else
                    {
                        $data['imageUrl'] = $res_info2->imageUrl;
                    }

                    $data['notificationName'] = "FavouriteBarChanges";
                    $data['distance'] = find_distance($user->latitude, $user->longitude, $res_info2->latitude, $res_info2->longitude, '');
                }
                $arr[] = $data;
            }
            return response()->json(['status' => true, 'notification' => $arr, 'is_next' => @$response->next, 'total' => @$response->total, 'message' => 'success'], 200);
        }
        return response()->json(['status' => false, 'notification' => null, 'message' => trans('msg.record_not_found')], 200);

    }

    public function Accept_invitation(request $request)
    {
        $rules['id'] = 'required|not_in:0';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);


        $Model = new Invite_friend();
        $request->isAccept = 1;
        $res = $Model->request_accept($request);
        if ($res->type == 2)
        {
            $friend = new Friend;
            $res = $friend->add_friend($res);
        }
        return response()->json(['status' => true, 'message' => trans('msg.accept')], 200);
    }
    public function Decline(request $request)
    {
        $dd = Invite_friend::where("id", $request->id)->delete();
        if ($dd)
        {
            return response()->json(['status' => true, 'message' => trans('msg.decline')], 200);

        }
        return response()->json(['status' => false, 'message' => trans('msg.record_not_found')], 200);
    }
    public function My_friends_list(request $request)
    {
        $user = Auth::user();
        if (@$request->searchText)
        {
            $userModel = new User();
            $req = (object) array();
            $req->userType = 1;
            $req->invite_friend_select = 1;
            $req->userId = $user->userId;
            $req->search_text = $request->searchText;
            $response = $userModel->search_user($req);
            $data = array();

            foreach (@$response['data'] as $key => $val)
            {

                $is_exists = Invite_friend::where('senderId', '=', $user->userId)->where(['receiverId' => $val->userId, 'type' => 2, 'isAccept' => 0])->exists();
                if (@$is_exists)
                {
                    $request_sent = true;
                }
                else
                {
                    $request_sent = false;
                }
                $is_request = Invite_friend::where(['receiverId' => $user->userId, 'senderId' => $val->userId, 'isAccept' => 0])->where('type', '=', 2)->first();
                if (@$is_request)
                {
                    $id = $is_request->id;
                    $request = true;
                }
                else
                {
                    $request = false;
                }
                $is_friend = Friend::Where(['userId' => $user->userId, 'friendId' => $val->userId])->first();
                if (empty($is_friend))
                    $is_friend = Friend::Where(['friendId' => $user->userId, 'userId' => $val->userId])->first();

                if (!empty($is_friend))
                {
                    $_Friend = true;
                    $_fId = $is_friend->fId;
                }
                else
                {
                    $_Friend = false;
                }

                $res[$key] = $val;
                $res[$key]->isRequestSent = @$request_sent;
                $res[$key]->isFriend = @$_Friend;
                if ($_Friend == true)
                {
                    $res[$key]->fId = $_fId;
                    $checked_user = Checkedin::where(['userId' => $val->userId, 'date' => date('Y-m-d')])->get();
                    if ($checked_user->count())
                    {
                        $res[$key]->isCheckedin = true;
                        foreach ($checked_user as $val)
                        {

                            $bar_name = DB::table('bar_restaurants')->where(['restaurantId' => $val->restaurantId])->first();
                            $r['restaurantId'] = @$bar_name->restaurantId;
                            $r['barName'] = @$bar_name->name;
                            $r['distance'] = find_distance($user->latitude, $user->longitude, $bar_name->latitude, $bar_name->longitude, '');

                            $arrbar[] = $r;
                        }
                        $res[$key]->barCheckedInList = $arrbar;

                    }
                    else
                    {
                        $res[$key]->isCheckedin = false;
                    }

                }

                $res[$key]->isReceiveFriendRequest = @$request;
                if (@$is_request)
                    $res[$key]->id = @$id;

                @$friendlist = $res;

            }
        }
        else
        {
            $model = new Friend();
            $request->userId = $user->userId;
            $res = $model->get_friend_list($request);

            foreach ($res->data as $key => $xx)
            {
                $arrbar = array();
                $ress[$key] = $xx;
                $ress[$key]['isFriend'] = true;
                $checked_user = Checkedin::where(['userId' => $xx['userId'], 'date' => date('Y-m-d')])->get();

                if ($checked_user->count())
                {
                    $ress[$key]['isCheckedin'] = true;
                    foreach ($checked_user as $val)
                    {
                        $bar_name = DB::table('bar_restaurants')->where(['restaurantId' => $val->restaurantId])->first();
                        $r['restaurantId'] = @$bar_name->restaurantId;
                        $r['barName'] = @$bar_name->name;
                        $r['distance'] = find_distance($user->latitude, $user->longitude, $bar_name->latitude, $bar_name->longitude, '');

                        $arrbar[] = $r;
                    }
                    $ress[$key]['barCheckedInList'] = $arrbar;
                }
                else
                {
                    $ress[$key]['isCheckedin'] = false;
                }

                $friendlist = $ress;
            }

            $model = new Invite_friend();
            $friendResquest = $model->get_friend_request($request);
        }
        return response()->json(['status' => true, 'friendResquest' => @$friendResquest, 'friendList' => @$friendlist, 'total' => @$res->total, 'is_next' => @$res->next, 'message' => 'success'], 200);

    }

    //////////Admin side

    function get_invited_user_list(request $request)
    {

        $Model = new Invite_friend();
        $request->userType = 1;
        $response = $Model->search_invited_user($request);
        $html_response = "";
        if (isset($request->web_data_status) && $request->web_data_status == 1)
        {
            $html_response = view('admin.Bar_Restaurant.search_invited_user', ['data' => $response['data']])->render();
        }
        return response()->json(['status' => 200, 'response' => $response['data'], 'total' => $response['total'], 'html_response' => $html_response, 'msg' => 'success'], 200);
    }

    function remove_friend(request $request)
    {
        $rules['fId'] = 'required';
        $msg['fId.required'] = 'fId is required';

        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);
        $Model = Friend::where('fId', $request->fId)->delete();
        if ($Model)
        {
            return response()->json(['status' => true, 'message' => trans('msg.remove_friend')], 200);
        }
    }
}
