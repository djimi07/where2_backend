<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bar_Restaurant;
use App\Models\User;
use App\Models\Friend;
use App\Models\Cms;
use App\Models\ReportModel;
use App\Models\Images;
use App\Models\Checkedin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Validation\Rule;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // USER LOGIN AND SIGNUP
    function user_signup(request $request)
    {
        $rules['firstName'] = config('custom_validation.firstName');
        // $rules['lastName'] = 'required';
        $rules['userName'] = 'required';
        $userName = User::where('userName', $request->userName)->exists();
        if ($userName)
        {
            return json_response(['status' => false, 'message' => trans('msg.unique_username')], 400);
        }
        $rules['userEmail'] = 'required';

        $rules['userMobile'] = "required|numeric|not_in:0|digits:10";
        $userMobile = User::where('userMobile', $request->userMobile)->exists();
        if ($userMobile)
        {
            return json_response(['status' => false, 'message' => trans('msg.unique_phone')], 400);
        }
        $rules['gender'] = 'required';
        $rules['dateOfBirth'] = 'required';
        $valida_pass = config('custom_validation.password'); // PASSWORD VALIDATION
        $rules['password'] = $valida_pass;
        $rules['confirmPassword'] = 'required|same:password';

        $msg['firstName.required'] = trans('msg.req_first_name');
        $msg['firstName.regex'] = trans('msg.invalid_first_name_format');
        $msg['firstName.max'] = trans('msg.max_first_name');
        // $msg['lastName.required'] = trans('msg.req_last_name');
        // $msg['lastName.regex'] = trans('msg.invalid_last_name_format');
        // $msg['lastName.max'] = trans('msg.max_last_name');
        $msg['userMobile.required'] = trans('msg.req_phone');
        $msg['userMobile.numeric'] = trans('msg.req_invalid');
        $msg['userMobile.digits_between'] = trans('msg.req_phone_range');
        $msg['userMobile.not_in'] = trans('msg.invalid_phone');
        $msg['userMobile.unique'] = trans('msg.unique_phone');
        $msg['password.required'] = trans('msg.new_password');
        $msg['password.min'] = trans('msg.req_password_min');
        $msg['password.regex'] = trans('msg.invalid_password_format');


        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);

        $request->user_type = config('constants.USER');
        $request->userType = 1;
        $userModel = new User();
        $response = $userModel->add_update_user($request);

        $token = $response->createToken('MyApp')->accessToken; // CREATE TOKEN
        $web_token_token = md5('admin_where_2' . time() . rand() . '_user_check');
        $userModel = new User();
        $users = $userModel->update_login_date($response->userId, $web_token_token);
        if ($response->count())
        {
            $data = $this->user_array_data($response, $token);
            return json_response(['status' => true, 'data' => @$data, 'message' => trans('msg.succ_signup')], 200);
        }
    }
    function user_profile_update(request $request)
    {
        $rules['userId'] = 'required';
        $rules['firstName'] = config('custom_validation.firstName');
        // $rules['lastName'] = 'required';
        $rules['userName'] = ['required', Rule::unique('users')->ignore($request->userId, 'userId')];
        $rules['userEmail'] = 'required';
        $rules['userMobile'] = ['required', 'numeric', 'not_in:0', 'digits:10', Rule::unique('users')->ignore($request->userId, 'userId')];
        // $rules['gender'] = 'required';
        // $rules['dateOfBirth'] = 'required';
        // $valida_pass = config('custom_validation.password'); // PASSWORD VALIDATION
        if (@$request->password)
            $rules['password'] = ['min:6', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/'];
        // $rules['confirmPassword'] = 'required|same:password';

        $msg['firstName.required'] = trans('msg.req_first_name');
        $msg['firstName.regex'] = trans('msg.invalid_first_name_format');
        $msg['firstName.max'] = trans('msg.max_first_name');
        // $msg['lastName.required'] = trans('msg.req_last_name');
        // $msg['lastName.regex'] = trans('msg.invalid_last_name_format');
        // $msg['lastName.max'] = trans('msg.max_last_name');
        $msg['userMobile.required'] = trans('msg.req_phone');
        $msg['userMobile.numeric'] = trans('msg.req_invalid');
        $msg['userMobile.digits_between'] = trans('msg.req_phone_range');
        $msg['userMobile.not_in'] = trans('msg.invalid_phone');
        $msg['userMobile.unique'] = trans('msg.unique_phone');
        // $msg['password.min'] = trans('msg.req_password_min');
        // $msg['password.regex'] = trans('msg.invalid_password_format');


        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);

        $request->user_type = config('constants.USER');
        $request->userType = 1;
        $userModel = new User();
        $response = $userModel->add_update_user($request);
        if ($response->count())
        {
            $data = array(
                'userId' => $response->userId,
                'firstName' => $response->firstName,
                'lastName' => $response->lastName,
                'userName' => $response->userName,
                'dateOfBirth' => $response->userDateOfBirth,
                'userProfilePicture' => $response->userProfilePicture,
                'gender' => $response->userGender,
                'userType' => $response->userType,
                'userEmail' => $response->userEmail,
                'userMobile' => $response->userMobile,
                'userStatus' => $response->userStatus,
                'active' => $response->userStatus,

            );
            if (@isset($request->adminside))
                return json_response(['status' => true, 'data' => $data, 'message' => trans('msg.succ_update_user')], 200);
            else
                return json_response(['status' => true, 'data' => $data, 'message' => trans('msg.succ_update_profile')], 200);
        }
    }
    public function user_login(request $request)
    {
        $rules['userMobile'] = 'required';
        $rules['password'] = 'required';
        // $rules['deviceToken']='required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);

        $userdata = ['userMobile' => $request->userMobile, 'password' => $request->password, 'user_type' => config('constants.USER')];
        if (Auth::attempt($userdata)) // CHECK LOGIN
        {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->accessToken; // CREATE TOKEN
            $web_token_token = md5('admin_where_2' . time() . rand() . '_user_check');
            $userModel = new User();
            $users = $userModel->update_login_date($user->userId, $web_token_token);
            // $userModel->update_fire_base_token($user->userId , $request->deviceToken);

            //    Session::set('AuthAdminData', $user);
            $data = array(
                'token' => $token,
                'userId' => $user->userId,
                'firstName' => $user->firstName,
                'lastName' => $user->lastName,
                'userName' => $user->userName,
                'dateOfBirth' => $user->userDateOfBirth,
                'gender' => $user->userGender,
                'userType' => $user->userType,
                'userEmail' => $user->userEmail,
                'userProfilePicture' => Image_url($user->userProfilePicture),
                'userMobile' => $user->userMobile,
                'userStatus' => $user->userStatus,
                'active' => $user->userStatus,
                'is_owner' => $user->is_owner,
                'user_type' => $user->user_type,

            );
            return json_response(['status' => true, 'data' => @$data, 'message' => trans('msg.succ_login')], 200);

        }
        else
        {
            return json_response(['status' => false, 'message' => trans('msg.wrong_credential')], 400);
        }

    }
    /*...... END API ........*/
    // UPLOAD USER IMAGE INTO TEMP FOLDER
    function upload_user_image(Request $request)
    {
        $user = Auth::user();
        $rules['userProfilePicture'] = config('custom_validation.profile_image');
        $msg['userProfilePicture.required'] = trans('msg.req_user_image');
        $msg['userProfilePicture.mimes'] = trans('msg.req_user_image_mimes');
        $msg['userProfilePicture.max'] = trans('msg.req_user_image_max');

        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);
        $imageHelper = new \ImageHelper(); // CALL IMAGE HELPER
        $imageResponse = $imageHelper->upload_user_image($request);
        // $imageHelper-> move_image_from_temp( $imageResponse);
        $userModel = new User();
        $response = $userModel->update_user_picture($user->userId, $imageResponse, $user->userProfilePicture);
        $data = array(
            'userProfilePicture' => url('storage/app/public/image/') . '/' . $imageResponse,
        );

        return json_response(['status' => true, 'data' => @$data, 'message' => 'Success'], 200);
    }

    public function upload_user_image_with_id(request $request)
    {
        $user = Auth::user();
        $rules['imageId'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);
        $image = Images::where('imageId', $request->imageId)->first();
        $res = User::where('userId', $user->userId)->update(['userProfilePicture' => $image->imageName]);
        if ($res)
        {
            //$data = ['image' => Image_url($image->imageName)];
            $data = ['image' => $image->imageName];
        }
        return json_response(['status' => true, 'data' => @$data, 'message' => 'Success'], 200);

    }

    // LOGOUT USER
    function logout()
    {
        $auth_user = get_auth();
        $Model = new User();
        $Model->erase_fire_base_token($auth_user->userId);
        $auth_user->token()->revoke();
        return json_response(['status' => true, 'message' => trans('msg.succ_logout')], 200);
    }
    function search_user_api(Request $request)
    {
        $userModel = new User();
        $request->userType = 1;
        $response = $userModel->search_user($request);
        $html_response = "";
        if (isset($request->web_data_status) && $request->web_data_status == 1)
        {
            $html_response = view('admin.search.user', ['data' => $response['data']])->render();
        }
        return response()->json(['status' => 200, 'response' => $response['data'], 'total' => $response['total'], 'html_response' => $html_response, 'msg' => 'success'], 200);
    }

    function user_friend_list(request $request)
    {
        $model = new Friend();
        $request->userId = $request->id;
        $response = $model->get_friend_list($request);
        $html_response = "";
        if (isset($request->web_data_status) && $request->web_data_status == 1)
        {
            $html_response = view('admin.search.user_friend_list', ['Data' => @$response->data])->render();
        }
        return response()->json(['status' => 200, 'response' => @$response->data, 'total' => @$response->total, 'html_response' => @$html_response, 'msg' => 'success'], 200);
    }

    public function delete_single_user(request $request)
    {
        $dd = User::where("userId", $request->id)->delete();
        if ($dd)
        {
            return response()->json(['status' => 200, 'response' => 'success', 'msg' => trans('msg.del_user')], 200);

        }
    }
    public function get_users_details($id)
    {
        $data = User::where("userId", $id)->first();
        $html = view('admin.form_modal.user_details', ['data' => $data])->render();
        return response()->json(['status' => 200, 'response' => $html, 'data' => $data], 200);
    }
    public function get_users_info_api()
    {
        $user = Auth::user();
        $response = User::where("userId", $user->userId)->first();

        if ($response)
        {
            $restaurants = Bar_Restaurant::where('ownerId', $user->userId)->get();

            //$data = $this->user_array_data($response);

            $data = array(
                'token' => '',
                'userId' => $response->userId,
                'firstName' => $response->firstName,
                'lastName' => $response->lastName,
                'userName' => $response->userName,
                'dateOfBirth' => $response->userDateOfBirth,
                'userProfilePicture' => Image_url($response->userProfilePicture),
                'gender' => $response->userGender,
                'userType' => $response->userType,
                'userEmail' => $response->userEmail,
                'userMobile' => $response->userMobile,
                'userStatus' => $response->userStatus,
                'active' => $response->userStatus,
                'restaurants' => $restaurants,

            );

            return response()->json(['status' => true, 'data' => @$data, 'message' => 'Success'], 200);
        }
    }

    function forgot_user_password(request $request)
    {
        $rules['userMobile'] = 'required';
        $msg['userMobile.required'] = trans('msg.req_phone');
        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);

        $user = User::where('userMobile', '=', $request->userMobile)->where('user_type', '=', config('constants.USER'))->first();
        if (empty($user))
        {
            return json_response(['status' => false, 'message' => trans('msg.user_not_register')], 400);
        }
        return json_response(['status' => true, 'message' => trans('msg.succ_otp_send_mail')], 200);
    }
    function Reset_Password(request $request)
    {
        $rules['userMobile'] = 'required';
        $valida_pass = config('custom_validation.password');
        $rules['password'] = $valida_pass;
        $rules['confirmPassword'] = 'required|same:password';
        $msg['password.min'] = trans('msg.req_password_min');
        $msg['password.regex'] = trans('msg.invalid_password_format');
        $msg['password.required'] = trans('msg.new_password');

        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);

        $userModel = new User();
        $respose = $userModel->update_password($request);
        return json_response(['status' => true, 'message' => trans('msg.succ_update_password')], 200);

    }


    // function user_verify_otp(Request $request)
    // {
    //     $rules['otp'] = 'required';
    //     $msg['otp.required'] = trans('msg.req_otp');

    //     $validator = Validator::make($request->all(), $rules,$msg);
    //     if ($validator->fails())
    //         return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);

    //     $user = User::where('userMobile','=',$request->userMobile)->where('user_type','=',config('constants.USER'))
    //             ->where('otp',$request->otp)->first();
    //     if (empty($user)) {
    //         return json_response(['status' => false, 'message' => trans('msg.invalid_otp')], 400);
    //     }
    //     return json_response(['status' => 200,'response' => $response,'msg' => trans('msg.succ_verify_otp')], 200);
    // }

    // public function privacyPolicy()
    // {
    // 	$info =  Cms::where("type", 2)->first();
    // 	$data = $info->content;
    // 	return response()->json(['status' =>200 , 'response' => $data, 'page' => url('privacy-policy'),'msg' => 'Success'], 200);
    // }
    // public function send_message(request $request)
    // {
    //     sendMessage($request->message,$request->mobile);
    //     return response()->json(['status' =>200 , 'msg' => 'Success'], 200);
    // }

    function user_array_data($response, $token = "")
    {
        $data = array(
            'token' => @$token,
            'userId' => $response->userId,
            'firstName' => $response->firstName,
            'lastName' => $response->lastName,
            'userName' => $response->userName,
            'dateOfBirth' => $response->userDateOfBirth,
            'userProfilePicture' => Image_url($response->userProfilePicture),
            'gender' => $response->userGender,
            'userType' => $response->userType,
            'userEmail' => $response->userEmail,
            'userMobile' => $response->userMobile,
            'userStatus' => $response->userStatus,
            'active' => $response->userStatus,

        );
        return @$data;
    }

    public function user_history(request $request)
    {
        // print_r($request->all());die;
        $Model = new Checkedin();
        $request->restaurnatId = "";
        $request->userId = $request->id;
        $response = $Model->get_checked_user_list($request);

        if (isset($request->web_data_status) && $request->web_data_status == 1)
        {
            $html_response = view('admin.search.user_history', ['data' => $response['data']])->render();
        }
        return response()->json(['status' => 200, 'response' => $response['data'], 'total' => $response['total'], 'html_response' => $html_response, 'msg' => 'success'], 200);
    }

    public function notification_token(request $request)
    {
        $user = Auth::user();
        $rules['fcmToken'] = 'required';
        $rules['deviceToken'] = 'required';
        $msg['fcmToken.required'] = trans('msg.req_fcm_token');
        $msg['deviceToken.required'] = trans('msg.req_deviceToken');
        $validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);
        $userModel = new User();
        $res = $userModel->update_fire_base_token($user->userId, $request->deviceToken, $request->fcmToken);
        if ($res)
            return json_response(['status' => true, 'message' => 'success'], 200);
    }

}