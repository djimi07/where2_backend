<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cms;
use App\Models\Bar_Restaurant;
use Illuminate\Http\Request;
use Session;
use Validator;
use DB;

class AdminViewController extends Controller
{
    function login_view()
    {
        $page_title = trans('page_title.admin_login');
        return view('admin.login', ['page_title' => $page_title]);
    }

    function forget_password_view()
    {
        $page_title = trans('page_title.admin_forget_password');
        $data = DB::table('country_code')->get();
        return view('admin.forget_password', ['page_title' => $page_title, 'data' => $data]);
    }
    function password_reset_view(Request $request)
    {
        if (!isset($request->token) || empty($request->token))
            return view('error', ['status' => 404, 'msg' => 'Token not found']);

        $page_title = trans('page_title.admin_forget_password');
        return view('admin.password_reset', ['page_title' => $page_title, 'token' => $request->token]);
    }

    function set_auth_session(Request $request)
    {
        Session::put('AuthAdminWebToken', $request->web_token);
        return json_response(['status' => 200, 'token' => "", 'msg' => trans('msg.succ_login')], 200);
    }

    function unset_auth_session(Request $request)
    {
        Session::put('AuthAdminWebToken', " ");
        Session::forget('AuthAdminWebToken');
        return json_response(['status' => 200, 'token' => "", 'msg' => 'success'], 200);
    }

    function dashboard_view()
    {
        $auth_user = get_admin();
        $page_title = trans('page_title.admin_dashboard');
        $data['total_users'] = User::where('userType', 1)->count('userId');
        $data['allnewusers'] = User::where('userStatus', 1)->where('userType', 1)->orderBy('userId', 'DESC')->limit(9)->get();

        $data['Owneractive'] = User::where('userStatus', 1)->where(['userType' => 1, 'is_owner' => 1])->count('userId');
        $data['Ownerdeactive'] = User::where('userStatus', 0)->where(['userType' => 1, 'is_owner' => 1])->count('userId');

        $data['Useractive'] = User::where('userStatus', 1)->where('userType', 1)->count('userId');
        $data['Userdeactive'] = User::where('userStatus', 0)->where('userType', 1)->count('userId');

        $data['allnewowner'] = User::where('userStatus', 1)->where(['userType' => 1, 'is_owner' => 1])->orderBy('userId', 'DESC')->limit(9)->get();

        $data['total_owner'] = User::where(['userType' => 1, 'is_owner' => 1])->count('userId');
        $data['total_bar_restaurant'] = Bar_Restaurant::count('restaurantId');
        $data['Publish'] = Bar_Restaurant::where('status', 1)->count('restaurantId');
        $data['UnPublish'] = Bar_Restaurant::where('status', 0)->count('restaurantId');
        // $data['allnewuserscount'] =  User::orderBy('userId','DESC')->count();

        return view('admin.single.dashboard', ['page_title' => $page_title, 'auth_user' => $auth_user], $data);
    }

    function profile_view()
    {
        $auth_user = get_admin();
        $page_title = trans('page_title.admin_profile');
        return view('admin.single.profile', ['page_title' => $page_title, 'auth_user' => $auth_user]);
    }

    function change_password_view()
    {
        $auth_user = get_admin();
        $page_title = trans('page_title.admin_change_password');
        return view('admin.single.change_password', ['page_title' => $page_title, 'auth_user' => $auth_user]);
    }


    function users_list_view()
    {
        $auth_user = get_admin();
        $page_title = trans('page_title.admin_users');
        return view('admin.list.users', ['page_title' => $page_title, 'auth_user' => $auth_user]);
    }

    public function privacy_policy()
    {
        $auth_user = get_admin();
        $data['auth_user'] = $auth_user;
        $data['page_title'] = trans('page_title.admin_policy');
        $data['data'] = Cms::where("type", 2)->first();
        // $data['page_title'] = trans('page_title.admin_cms_policy');
        return view("admin.single.cms_policy", $data);
    }

    public function getDownload($filename)
    {
        $file = public_path() . "/storage/image/" . $filename;
        return response()->download($file);
    }

    function owner_management()
    {
        $auth_user = get_admin();
        $page_title = trans('page_title.admin_owner');
        $html = view('admin.form_modal.search_user_and_make_owner')->render();
        return view('admin.list.owner_management', ['page_title' => $page_title, 'auth_user' => $auth_user, 'owner_add_form' => $html]);
    }

    function add_bar_restaurant()
    {
        $auth_user = get_admin();
        $page_title = trans('page_title.admin_bar_restaurant');
        $html = view('admin.Bar_Restaurant.add_form')->render();
        return view('admin.Bar_Restaurant.list_bar_restaurant', ['page_title' => $page_title, 'auth_user' => $auth_user, 'add_form' => @$html]);
    }
    function view_bar_restaurant()
    {
        $auth_user = get_admin();
        $page_title = trans('page_title.admin_view_bar_restaurant');
        $owner = User::select('userId', 'firstName', 'lastName', 'userType')->where(['userType' => 1, 'is_owner' => 1])->get();
        return view('admin.Bar_Restaurant.view', ['page_title' => $page_title, 'auth_user' => $auth_user, 'owner' => $owner]);
    }
    function comment_view()
    {
        $auth_user = get_admin();
        $page_title = trans('page_title.comment_view');
        $html = view('admin.form_modal.add_comment')->render();
        return view('admin.list.comment', ['page_title' => $page_title, 'auth_user' => $auth_user, 'add_form' => @$html]);
    }

}
