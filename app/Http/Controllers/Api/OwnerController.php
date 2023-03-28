<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user;
use App\Models\Bar_Restaurant;
use Illuminate\Validation\Rule;
use Validator;
use Auth;
use Session;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class OwnerController extends Controller
{
    function login(Request $request)
    {
        $rules['userMobile'] = 'required|numeric';
        $rules['password'] = 'required';
        $msg['userMobile.required'] = trans('msg.req_phone');
        $msg['userMobile.numeric'] = trans('msg.req_phone_numeric');
        $msg['password.required'] = trans('msg.req_password');

        $msg['password.required'] = trans('msg.req_password');

        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => 400, 'msg' => $validator->messages()->first()], 400);

        $userdata = ['userMobile' => $request->userMobile, 'password' => $request->password, 'user_type' => config('constants.USER'), 'is_owner' => 1];
        if (Auth::attempt($userdata)) // CHECK LOGIN
        {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken; // CREATE TOKEN
            $web_token_token = md5('owner' . time() . rand() . '_user_check');
            $userModel = new User();
            $user = $userModel->update_login_date($user->userId, $web_token_token);
            Session::put('AuthOwnerWebToken', $user->web_token);
            return json_response(['status' => 200, 'token' => $token, 'web_token' => $user->web_token, 'msg' => trans('msg.succ_login')], 200);

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
        $user = ['userMobile' => $request->userMobile, 'user_type' => config('constants.USER'), 'is_owner' => 1];
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
    ////////Admin side/////////////
    function owner_profile_update(request $request)
    {
        $rules['userName'] = ['required', 'max:16', Rule::unique('users')->ignore(@$request->userId, 'userId')];
        $rules['firstName'] = config('custom_validation.firstName');
        $rules['lastName'] = config('custom_validation.lastName');
        $rules['userEmail'] = ['required', 'email', 'regex:/^([a-zA-Z])+([a-zA-Z0-9_.+-])+\@(([a-zA-Z])+\.+?(com|co|in|org|net|edu|info|gov|vekomy))\.?(com|co|in|org|net|edu|info|gov)?$/', Rule::unique('users')->ignore(@$request->userId, 'userId')];
        $rules['userMobile'] = ['required', 'numeric', 'not_in:0', 'digits:10', Rule::unique('users')->ignore(@$request->userId, 'userId')];
        $valida_pass = config('custom_validation.password'); // PASSWORD VALIDATION
        if (@$request->userId)
        {
            if (!empty(@$request->password))
            {
                $rules['password'] = $valida_pass;
                $rules['confirmPassword'] = 'required|same:password';
            }
        }
        else
        {
            $rules['password'] = $valida_pass;
            $rules['confirmPassword'] = 'required|same:password';
        }

        $msg['userName.required'] = trans('msg.req_username');
        $msg['userName.unique'] = trans('msg.unique_username');
        $msg['firstName.required'] = trans('msg.req_first_name');
        $msg['firstName.regex'] = trans('msg.invalid_first_name_format');
        $msg['firstName.max'] = trans('msg.max_first_name');
        $msg['lastName.required'] = trans('msg.req_last_name');
        $msg['lastName.regex'] = trans('msg.invalid_last_name_format');
        $msg['lastName.max'] = trans('msg.max_last_name');

        $msg['userEmail.required'] = trans('msg.req_email');
        $msg['userEmail.email'] = trans('msg.wrong_email_format');
        $msg['userEmail.regex'] = trans('msg.wrong_email');
        $msg['userMobile.required'] = trans('msg.req_phone');
        $msg['userMobile.numeric'] = trans('msg.req_phone_numeric');
        $msg['userMobile.digits'] = trans('msg.max_phone');
        // $msg['userMobile.digits_between'] = trans('msg.req_phone_range');

        $msg['userMobile.not_in'] = trans('msg.invalid_phone');
        $msg['userMobile.unique'] = trans('msg.unique_phone');

        $msg['password.required'] = trans('msg.req_password');
        $msg['password.min'] = trans('msg.req_password_min');
        $msg['password.regex'] = trans('msg.invalid_password_format');
        $msg['confirmPassword.required'] = trans('msg.req_confirmPassword');
        $msg['confirmPassword.same'] = trans('msg.password_confirmed_not_match');


        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);

        // $request->user_type = config('constants.Owner');
        // $request->userType = 1;
        $request->user_type = config('constants.Owner');
        $request->userType = 1;

        $userModel = new user();
        $response = $userModel->add_update_user($request);
        if ($response->count())
        {
            if ($request->userId)
            {
                return json_response(['status' => true, 'data' => $response, 'message' => trans('msg.succ_update_owner')], 200);
            }
            else
            {
                $link = url('/owner');
                $obj = (object) [];
                $obj->receiver_email = $response->userEmail;
                $obj->template = 'email.create_owner';
                $obj->data = ['title' => trans('mail.owner_title'),
                    'text' => trans('mail.owner_text'),
                    'mobile' => $request->userMobile,
                    'password' => $request->password,
                    'user_name' => $request->userName,
                    'link' => $link];
                $obj->subject = trans('mail.owner_sub');

                //$check = send_mail($obj);
                return json_response(['status' => true, 'data' => $response, 'message' => trans('msg.succ_insert_owner')], 200);
            }

        }
    }

    function search_owner_api(Request $request)
    {
        $Model = new User();
        $request->userType = 1;
        $request->is_owner = 1;
        $response = $Model->search_user($request);
        $html_response = "";
        if (isset($request->web_data_status) && $request->web_data_status == 1)
        {
            $html_response = view('admin.search.owner', ['data' => $response['data']])->render();
        }
        return response()->json(['status' => 200, 'response' => $response['data'], 'total' => $response['total'], 'html_response' => $html_response, 'msg' => 'success'], 200);
    }
    function search_user_api(Request $request)
    {
        $Model = new User();
        $request->userType = 1;
        $request->is_owner = 0;
        $response = $Model->search_user($request);
        $html_response = "";
        if (isset($request->web_data_status) && $request->web_data_status == 1)
        {
            $html_response = view('admin.search.make_owner', ['data' => $response['data']])->render();
        }
        return response()->json(['status' => 200, 'response' => $response['data'], 'total' => $response['total'], 'html_response' => $html_response, 'msg' => 'success'], 200);
    }
    function get_single_owner($id)
    {
        $data = User::where("userId", $id)->first();
        $html = view('admin.form_modal.add_owner', ['data' => $data])->render();
        return response()->json(['status' => 200, 'response' => $html, 'data' => $data], 200);
    }
    function update_status(request $request)
    {
        $Model = new user();
        $response = $Model->update_user_status($request);

        if ($response->userStatus == 1)
            $msg = trans('msg.succ_active');
        else
            $msg = trans('msg.de_active');
        return response()->json(['status' => 200, 'msg' => $msg], 200);
    }
    public function delete(request $request)
    {
        $dd = User::where("userId", $request->id)->update(['is_owner' => 0]);
        Bar_Restaurant::where('ownerId', $request->id)->update(['ownerId' => ""]);
        if ($dd)
        {
            return response()->json(['status' => 200, 'response' => 'success', 'msg' => trans('msg.del_owner')], 200);

        }
    }
    public function make_owner(request $request)
    {
        $dd = User::where("userId", $request->id)->update(['is_owner' => 1]);
        return response()->json(['status' => 200, 'response' => 'success', 'msg' => trans('msg.succ_insert_owner')], 200);
    }

    //////////Owner side//////////////
    function update_profile(Request $request)
    {
        $auth_user = get_auth();
        $rules['firstName'] = config('custom_validation.firstName');
        $rules['lastName'] = config('custom_validation.lastName');
        $rules['userEmail'] = ['required', 'email', 'regex:/^([a-zA-Z])+([a-zA-Z0-9_.+-])+\@(([a-zA-Z])+\.+?(com|co|in|org|net|edu|info|gov|vekomy))\.?(com|co|in|org|net|edu|info|gov)?$/', Rule::unique('users')->ignore(@$auth_user->userId, 'userId')];
        $rules['userMobile'] = ['required', 'numeric', 'not_in:0', 'digits:10', Rule::unique('users')->ignore(@$auth_user->userId, 'userId')];

        $msg['firstName.required'] = trans('msg.req_first_name');
        $msg['firstName.regex'] = trans('msg.invalid_first_name_format');
        $msg['firstName.max'] = trans('msg.max_first_name');
        $msg['lastName.required'] = trans('msg.req_last_name');
        $msg['lastName.regex'] = trans('msg.invalid_last_name_format');
        $msg['lastName.max'] = trans('msg.max_last_name');
        $msg['userMobile.required'] = trans('msg.req_phone');
        $msg['userMobile.numeric'] = trans('msg.req_phone_numeric');
        $msg['userMobile.digits_between'] = trans('msg.req_phone_range');
        $msg['userMobile.not_in'] = trans('msg.invalid_phone');
        $msg['userMobile.unique'] = trans('msg.unique_phone');
        $msg['userMobile.not_in'] = trans('msg.invalid_phone');
        $msg['userMobile.unique'] = trans('msg.unique_phone');
        $msg['userEmail.required'] = trans('msg.req_email');
        $msg['userEmail.unique'] = trans('msg.unique_email');
        $msg['userEmail.email'] = trans('msg.wrong_email');
        $msg['userEmail.regex'] = trans('msg.wrong_email');
        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => 400, 'message' => $validator->messages()->first()], 400);

        $request->userId = $auth_user->userId;
        $userModel = new User();
        $response = $userModel->update_profile($request); // CALL MODEL FUNCTION
        // $response = $userModel->get_user_by_id($auth_user->userId);
        return json_response(['status' => true, 'response' => $response, 'message' => trans('msg.succ_update_profile')], 200);
    }
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
    function update_owner_profile_image(Request $request)
    {
        $rules['ownerProfilePicture'] = config('custom_validation.profile_image');
        $msg['ownerProfilePicture.required'] = trans('msg.req_user_image');
        $msg['ownerProfilePicture.mimes'] = trans('msg.req_user_image_mimes');
        $msg['ownerProfilePicture.max'] = trans('msg.req_user_image_max');

        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => true, 'message' => $validator->messages()->first()], 400);

        $auth_user = get_auth(); // CALL HELPER
        $imageHelper = new \ImageHelper(); // CALL IMAGE HELPER
        $imageResponse = $imageHelper->upload_owner_image($request); // UPDATE IMAGE IN FOLDER

        $userModel = new User(); // CALL  MODEL
        $response = $userModel->update_user_image($auth_user->userId, $imageResponse); // UPDATE IMAGE
        return json_response(['status' => 200, 'response' => $response, 'msg' => trans('msg.succ_update_user_image')], 200);
    }

    public function view_bar_res(request $request)
    {
        $model = new Bar_Restaurant;
        $request->filter_owner = $request->id;
        $request->filter_status = 1;
        $response = $model->search_bar_restaurant($request);
        $html_response = view('admin.search.owner_bar_res', ['data' => $response['list']])->render();
        return response()->json(['status' => 200, 'rating' => $response['list'], 'response' => $response['list'], 'total' => $response['total'], 'html_response' => $html_response, 'msg' => 'success'], 200);
    }

}