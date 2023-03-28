<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bar_Restaurant;
use App\Models\Checkedin;
use Session;
use DB;

class OwnerViewController extends Controller
{
    function login_view()
    {
        $page_title = trans('page_title.owner_login');
        return view('owner.login', ['page_title' => $page_title]);
    }

    function forget_password_view()
    {
        $page_title = trans('page_title.owner_forget_password');
        $data = DB::table('country_code')->get();
        $config = get_fire_base();
        return view('owner.forget_password', ['page_title' => $page_title, 'data' => $data, 'config' => $config]);
    }
    function password_reset_view(Request $request)
    {
        if (!isset($request->token) || empty($request->token))
            return view('error', ['status' => 404, 'msg' => 'Token not found']);

        $page_title = trans('page_title.owner_forget_password');
        return view('owner.password_reset', ['page_title' => $page_title, 'token' => $request->token]);
    }

    function unset_auth_session(Request $request)
    {
        Session::put('AuthOwnerWebToken', " ");
        Session::forget('AuthOwnerWebToken');
        return json_response(['status' => 200, 'token' => "", 'msg' => 'logout successfully'], 200);
    }

    function dashboard_view()
    {
        $auth_user = get_owner();
        $page_title = trans('page_title.owner_dashboard');
        $data['allbarrestaurnat'] = Bar_Restaurant::where('ownerId', $auth_user->userId)->count('restaurantId');
        $restId = Bar_Restaurant::select('restaurantId')->where('ownerId', $auth_user->userId)->get();

        $user = 0;
        foreach ($restId as $val)
        {
            $user += Checkedin::where(['restaurantId' => $val->restaurantId, 'date' => date('Y-m-d'), 'status' => 1])->count('checkinId');
        }
        $data['checkedinuser'] = $user;
        $checkinuser = array();
        foreach ($restId as $val)
        {
            $checkinuser[] = Checkedin::where(['restaurantId' => $val->restaurantId, 'date' => date('Y-m-d'), 'status' => 1])->orderBy('checkinId', 'DESC')->limit(9)->get();
        }

        $data['allnewusers'] = $checkinuser;
        // print_r($data['allnewusers']);die;

        // print_r($data['allusers']);die;
        // $data['deactiveusers'] = User::where(function($q){
        // 	$q->where('status',2)->orwhere('status',3);
        // })->count('id');

        // $data['allquestioncount'] = Question::count('id');
        // $data['allnewuserscount'] =  User::orderBy('userId','DESC')->count();
        // $data['publish'] = Question::where('status',1)->count('id');
        // $data['Unpublish'] = Question::where('status',0)->count('id');
        return view('owner.single.dashboard', ['page_title' => $page_title, 'auth_user' => $auth_user], $data);
    }

    function profile_view()
    {
        $auth_user = get_owner();
        $page_title = trans('page_title.owner_profile');
        return view('owner.single.profile', ['page_title' => $page_title, 'auth_user' => $auth_user]);
    }

    function change_password_view()
    {
        $auth_user = get_owner();
        $page_title = trans('page_title.owner_change_password');
        return view('owner.single.change_password', ['page_title' => $page_title, 'auth_user' => $auth_user]);
    }

    function list_bar_restaurant()
    {
        $auth_user = get_owner();
        $page_title = trans('page_title.owner_view_bar_restaurant');
        $html = view('owner.form_model.add_create_event')->render();
        return view('owner.list.bar_restaurant', ['page_title' => $page_title, 'auth_user' => $auth_user, 'create_event_add_form' => @$html]);
    }
    function event_page($id)
    {
        $auth_user = get_owner();
        $page_title = trans('page_title.event_page');
        $html = view('owner.form_model.add_create_event')->render();
        return view('owner.list.event', ['page_title' => $page_title, 'auth_user' => $auth_user, 'restaruantId' => $id, 'create_event_add_form' => @$html]);
    }

}
