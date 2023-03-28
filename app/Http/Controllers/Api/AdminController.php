<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Validator;
use Auth;
use DB;
use Session;

class AdminController extends Controller
{
    /*ADMIN LOGIN API*/
    function login(Request $request)
    {
        $rules['userMobile'] = 'required|numeric';
        $rules['password'] = 'required';
        $msg['userMobile.required'] = trans('msg.req_phone');
        $msg['userMobile.numeric'] = trans('msg.req_phone_numeric');
        $msg['password.required'] = trans('msg.req_password');

        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => 400, 'msg' => $validator->messages()->first()], 400);

        $userdata = ['userMobile' => $request->userMobile, 'password' => $request->password, 'user_type' => config('constants.ADMIN')];
        if (Auth::attempt($userdata)) // CHECK LOGIN
        {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken; // CREATE TOKEN
            $web_token_token = md5('admin_housefly' . time() . rand() . '_user_check');
            $userModel = new User();
            $user = $userModel->update_login_date($user->userId, $web_token_token);
            //    Session::set('AuthAdminData', $user);

            return json_response(['status' => 200, 'token' => $token, 'user_type' => $user->user_type, 'web_token' => $user->web_token, 'is_owner' => $user->is_owner, 'msg' => trans('msg.succ_login')], 200);

        }
        else
        {
            return json_response(['status' => 400, 'msg' => trans('msg.wrong_credential')], 400);
        }
    }

    // SEND MAIL FOR RESET FORGET PASSWORD API
    function forget_password(Request $request)
    {
        $rules['userMobile'] = 'required|numeric';
        $rules['code'] = 'required';
        $msg['userMobile.required'] = trans('msg.req_phone');
        $msg['userMobile.numeric'] = trans('msg.req_phone_numeric');
        $msg['code.required'] = 'Country Code is required';
        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => 400, 'msg' => $validator->messages()->first()], 400);

        $user = User::where('userMobile', '=', $request->userMobile)->where('user_type', '=', config('constants.ADMIN'))->first();
        if (empty($user))
        {
            return json_response(['status' => 400, 'msg' => trans('msg.user_not_register')], 400);
        }

        $token = md5(time() . uniqid()); // GENERATE TOKEN
        DB::table('password_resets')->insert(['email' => $request->userMobile, 'token' => $token, 'created_at' => Carbon::now()]);
        $config = get_fire_base();
        return json_response(['status' => 200, 'msg' => trans('msg.succ_otp_send_mail'), 'config' => $config, 'token' => $token], 200);


    }

    // UPDATE FORGET RESET PASSWORD
    public function update_reset_password(Request $request)
    {
        $valida_pass = config('custom_validation.password'); // PASSWORD VALIDATION
        array_push($valida_pass, 'confirmed');

        $rules['token'] = 'required|exists:password_resets,token';
        // $rules['userEmail'] = 'required|email|exists:users,userEmail';
        $rules['password'] = $valida_pass;
        $msg['token.required'] = trans('msg.req_token');
        $msg['token.exists'] = trans('msg.invalid_token');
        $msg['userEmail.required'] = trans('msg.req_email');
        $msg['userEmail.email'] = trans('msg.wrong_email_format');
        $msg['userEmail.exists'] = trans('msg.user_not_register');
        $msg['password.required'] = trans('msg.new_password');
        $msg['password.min'] = trans('msg.req_new_password_min');
        $msg['password.confirmed'] = trans('msg.new_password_confirmed_not_match');
        $msg['password.regex'] = trans('msg.invalid_password_format');

        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => 400, 'msg' => $validator->messages()->first()], 400);

        $tokenData = DB::table('password_resets')->where('token', $request->token)->first();
        if (empty($tokenData))
            return json_response(['status' => 400, 'msg' => trans('msg.invalid_token')], 400);

        $userModel = new User();
        $userData = $userModel->get_user_by_mobile($tokenData->email);
        $userModel->update_user_password($userData->userId, $request->password);

        DB::table('password_resets')->where('email', $userData->userEmail)->delete();
        return json_response(['status' => 200, 'msg' => trans('msg.succ_update_password')], 200);
    }

    // UPDATE PROFILE IMAGE
    function update_admin_profile_image(Request $request)
    {
        $rules['userProfilePicture'] = config('custom_validation.profile_image');
        $msg['userProfilePicture.required'] = trans('msg.req_user_image');
        $msg['userProfilePicture.mimes'] = trans('msg.req_user_image_mimes');
        $msg['userProfilePicture.max'] = trans('msg.req_user_image_max');

        /* $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
        return json_response(['status' => 400, 'msg' => $validator->messages()->first()], 400); */

        $auth_user = get_auth(); // CALL HELPER
        $imageHelper = new \ImageHelper(); // CALL IMAGE HELPER
        $imageResponse = $imageHelper->upload_user_image($request); // UPDATE IMAGE IN FOLDER

        $userModel = new User(); // CALL  MODEL
        $response = $userModel->update_user_image($auth_user->userId, $imageResponse); // UPDATE IMAGE
        return json_response(['status' => 200, 'response' => $response, 'msg' => trans('msg.succ_update_user_image')], 200);
    }

    // GET ADMIN PROFILE DETAILS
    function get_profile()
    {
        $auth_user = get_auth(); // CALL HELPER
        $userModel = new User();
        $response = $userModel->get_user_by_id($auth_user->userId);
        return json_response(['status' => 200, 'response' => $response, 'msg' => trans('msg.success')], 200);
    }

    // UPDATE ADMIN PROFILE
    function update_profile(Request $request)
    {
        $auth_user = get_auth();
        $rules['firstName'] = config('custom_validation.firstName');
        $rules['lastName'] = config('custom_validation.lastName');
        $rules['userEmail'] = ['required', 'email', 'regex:/^([a-zA-Z])+([a-zA-Z0-9_.+-])+\@(([a-zA-Z])+\.+?(com|co|in|org|net|edu|info|gov|vekomy))\.?(com|co|in|org|net|edu|info|gov)?$/', Rule::unique('users')->ignore(@$auth_user->userId, 'userId')];
        // $phone_validation = config('custom_validation.phone');

        $rules['userMobile'] = ['required', 'numeric', 'not_in:0', 'digits:10', Rule::unique('users')->ignore(@$auth_user->userId, 'userId')];

        $msg['firstName.required'] = trans('msg.req_first_name');
        $msg['firstName.regex'] = trans('msg.invalid_first_name_format');
        $msg['firstName.max'] = trans('msg.max_first_name');
        $msg['lastName.required'] = trans('msg.req_last_name');
        $msg['lastName.regex'] = trans('msg.invalid_last_name_format');
        $msg['lastName.max'] = trans('msg.max_last_name');
        $msg['userMobile.required'] = trans('msg.req_phone');

        $msg['userMobile.numeric'] = trans('msg.req_phone_numeric');
        $msg['userMobile.digits'] = trans('msg.max_phone');
        // $msg['userMobile.digits_between'] = trans('msg.req_phone_range');
        $msg['userMobile.not_in'] = trans('msg.invalid_phone');
        $msg['userMobile.unique'] = trans('msg.unique_phone');
        $msg['userEmail.required'] = trans('msg.req_email');
        $msg['userEmail.unique'] = trans('msg.unique_email');
        $msg['userEmail.email'] = trans('msg.wrong_email');
        $msg['userEmail.regex'] = trans('msg.wrong_email');


        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);

        $request->userId = $auth_user->userId;
        $userModel = new User();
        $response = $userModel->update_profile($request); // CALL MODEL FUNCTION
        // $response = $userModel->get_user_by_id($auth_user->userId);
        return json_response(['status' => true, 'response' => $response, 'message' => trans('msg.succ_update_profile')], 200);
    }

    // CHANGE PASSWORD
    function change_password_api(Request $request)
    {
        $valida_pass = config('custom_validation.password'); // PASSWORD VALIDATION
        // array_push($valida_pass,'confirmed');

        $rules['old_password'] = 'required';
        $rules['password'] = $valida_pass;
        $rules['password_confirmation'] = 'required|same:password';

        $msg['old_password.required'] = trans('msg.req_old_password');
        $msg['password.required'] = trans('msg.new_password');
        $msg['password.min'] = trans('msg.req_password_min');
        $msg['password.regex'] = trans('msg.invalid_password_format');
        $msg['password_confirmation.required'] = trans('msg.req_confirmPassword');
        $msg['password_confirmation.same'] = trans('msg.password_confirmed_not_match');

        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => 400, 'message' => $validator->messages()->first()], 400);

        $auth_user = get_auth();
        if (!Hash::check($request->old_password, $auth_user->userPassword)) // CHECK OLD PASSWORD
            return json_response(['status' => 400, 'message' => trans('msg.invalid_old_password')], 400);

        if ($request->old_password == $request->password)
            return json_response(['status' => 400, 'message' => 'Old Password and New Password will not be same'], 400);

        $userModel = new User();
        $userModel->update_user_password($auth_user->userId, $request->password); // CALL MODEL
        return json_response(['status' => true, 'message' => trans('msg.succ_update_password')], 200);
    }

    function logout()
    {
        $auth_user = get_auth();
        $auth_user->token()->revoke();
        return json_response(['status' => 200, 'msg' => trans('msg.succ_logout')], 200);
    }

    function update_sidebar_toggle()
    {
        $auth_user = get_auth();
        $userModel = new User();
        $response = $userModel->update_sidebar_toggle($auth_user->userId);
        return json_response(['status' => 200, 'msg' => trans('msg.success')], 200);
    }

    public function privacy_policy_api()
    {
        $data['data'] = Cms::where("type", 2)->first();
        return view("admin.single.cms_web_page", $data);
    }


}