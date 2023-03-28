<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class User extends Authenticatable
{
    use SoftDeletes;
    use HasApiTokens, Notifiable;

    protected $primaryKey = 'userId';
    protected $fillable = ['userEmail', 'userPassword'];
    protected $hidden = ['userPassword', 'remember_token', 'deleted_at', 'email_verified_at', 'companyId', 'companyName', 'userSecret',
        'userResetToken', 'lastModified'];
    protected $casts = ['email_verified_at' => 'datetime', 'id' => 'integer'];


    public function getAuthPassword()
    {
        return $this->userPassword;
    }

    // GET USER DETAILS BY ID
    function get_user_by_id($id)
    {
        $data = User::find($id);
        /*  $data->image_full_url = "";
        if(isset($data->image) && !empty($data->image))
        $data->image_full_url = url(Storage::url('image/'.$data->image));*/
        return $data;
    }

    function update_login_date($id, $web_token = "")
    {
        $obj = User::find($id);
        //        $obj->ip = $ip;
        $obj->login_date = Carbon::now();
        if (empty($obj->web_token))
            $obj->web_token = $web_token;

        $obj->save();
        return $obj;
    }
    function update_fire_base_token($id, $device_token = "", $fcm_token = "")
    {
        $obj = User::find($id);
        $obj->device_token = $device_token;
        $obj->fcm_token = $fcm_token;
        $obj->save();
        return $obj;
    }
    function erase_fire_base_token($id)
    {
        $obj = User::find($id);
        $obj->device_token = "";
        $obj->fcm_token = "";
        $obj->save();
        return $obj;
    }
    // GET USER DETAILS BY EMAIL
    function get_user_by_mobile($mobile)
    {
        $data = User::where('userMobile', '=', $mobile)->first();
        return $data;
    }

    // UPDATE USER PASSWORD
    function update_user_password($user_id, $userPassword)
    {
        $obj = User::find($user_id);
        $obj->userPassword = bcrypt($userPassword);
        $obj->save();
        return true;
    }

    // UPDATE IMAGE
    function update_user_image($id, $userProfilePicture)
    {
        $imageHelper = new \ImageHelper(); //CALL IMAGE HELPER
        $obj = User::find($id);
        if ($obj->userProfilePicture != $userProfilePicture)
        {
            $imageHelper->unlink_user_image($obj->userProfilePicture); // REMOVE OLD IMAGE FROM FOLDR
            $obj->userProfilePicture = $userProfilePicture;
        }
        $obj->save();
        return $obj;
    }


    function update_profile($request)
    {
        $obj = User::find($request->userId);
        $obj->firstName = $request->firstName;
        $obj->lastName = $request->lastName;
        $obj->userEmail = $request->userEmail;
        $obj->userMobile = $request->userMobile;
        $obj->save();
        return $obj;
    }

    function add_update_user($request)
    {
        $userId = 0;
        if (isset($request->userId) && !empty($request->userId))
            $userId = $request->userId;

        $obj = User::firstOrNew(['userId' => $userId]);

        if (isset($request->firstName))
            $obj->firstName = $request->firstName;

        if (isset($request->lastName))
            $obj->lastName = $request->lastName;

        if (isset($request->userName))
            $obj->userName = $request->userName;

        if (isset($request->userMobile))
            $obj->userMobile = $request->userMobile;

        if (isset($request->dateofBirth))
            $obj->userDateOfBirth = $request->dateofBirth;

        $obj->user_type = $request->user_type;

        if (isset($request->gender))
            $obj->userGender = $request->gender;

        $obj->userType = $request->userType;

        if (isset($request->userEmail))
            $obj->userEmail = $request->userEmail;
        if (isset($request->password) && !empty($request->password))
            $obj->userPassword = bcrypt($request->password);
        $obj->save();
        return $obj;
    }

    function search_user($request)
    {
        $limit = 10;
        $offset = 0;
        if (isset($request->limit) && !empty($request->limit))
            $limit = $request->limit;

        if (isset($request->offset) && !empty($request->offset))
            $offset = $request->offset;

        $query = User::query();

        if (isset($request->invite_friend_select))
        {
            $query->select('userId', 'firstName', 'lastName');
            $query->where('userId', '!=', $request->userId);
        }


        $query->where('userType', '=', $request->userType);

        if (isset($request->search_text) && !empty($request->search_text))
        {
            $query->where(function ($q) use ($request)
            {
                $q->where(DB::raw('CONCAT(firstName," ",lastName)'), 'like', '%' . $request->search_text . '%')
                    ->orWhere('userName', 'like', '%' . $request->search_text . '%')
                    ->orWhere('userMobile', 'like', '%' . $request->search_text . '%');
            });
        }


        if (isset($request->filter_status) && $request->filter_status != "")
            $query->where('userStatus', '=', $request->filter_status);

        if (isset($request->is_owner) && !empty($request->is_owner))
            $query->where('is_owner', '=', $request->is_owner);

        if (isset($request->user_type) && !empty($request->user_type))
            $query->where('user_type', '=', $request->user_type);
        $total_query = $query;
        $total_result = $total_query->get();
        $total = count($total_result);

        if (isset($request->order_by) && !empty($request->order_by))
        {
            if ($request->order_by == 'created_at-asc')
                $query->orderBy('created_at', 'ASC');
            elseif ($request->order_by == 'created_at-desc')
                $query->orderBy('created_at', 'DESC');
            elseif ($request->order_by == 'name-asc')
                $query->orderBy('firstName', 'ASC');
            elseif ($request->order_by == 'name-desc')
                $query->orderBy('firstName', 'DESC');
            elseif ($request->order_by == 'email-asc')
                $query->orderBy('userEmail', 'ASC');
            elseif ($request->order_by == 'email-desc')
                $query->orderBy('userEmail', 'DESC');
        }
        else
        {
            $query->orderBy('created_at', 'DESC');
        }
        $query->limit($limit)->offset($offset);
        $data = $query->get();
        $length = count($data);
        if ($total <= $limit)
            $next = false;
        else
            $next = true;
        if ($length < $limit)
            $next = false;
        else
            $next = true;
        $total_offset_value = $offset + $limit;
        if ($total == $total_offset_value)
            $next = false;

        return ['data' => $data, 'total' => $total, 'next' => $next];
    }


    function update_user_status($request)
    {

        $obj = User::find($request->id);
        $obj->userStatus = $request->status;
        $obj->save();
        return $obj;

    }

    function create_user($request)
    {
        $obj = new User();
        $obj->userMobile = $request->userMobile;
        $obj->userPassword = bcrypt($request->password);
        $obj->user_type = $request->user_type;
        $obj->userType = $request->userType;
        $obj->userVerification = 1;
        $obj->save();
        return $obj;
    }

    function update_user_signup_detail($request)
    {
        $obj = User::find($request->userId);
        $obj->firstName = $request->firstName;
        $obj->lastName = $request->lastName;
        if (isset($request->middleName) && !empty($request->middleName))
            $obj->middleName = $request->middleName;
        $obj->userMobile = $request->userMobile;
        $obj->zip_code = $request->zip_code;

        //  $obj->otp = $request->otp;
        $obj->save();
        return $obj;
    }

    function usr_email_verify($userId)
    {
        $obj = User::find($userId);
        $obj->userVerification = 1;
        $obj->otp = "";
        $obj->save();
        return $obj;
    }

    function update_password($request)
    {
        $data = ['userPassword' => bcrypt($request->password)];
        $obj = User::where('userMobile', $request->userMobile)->update($data);
        return $obj;
    }

    function update_sidebar_toggle($userId)
    {
        $obj = User::find($userId);
        if ($obj->sidebar_toggle == 1)
            $obj->sidebar_toggle = 0;
        else
            $obj->sidebar_toggle = 1;
        $obj->save();
        return true;
    }

    function update_user_setting($request, $userId)
    {
        $obj = User::find($userId);
        $obj->allow_push_notification = $request->allow_push_notification;
        $obj->allow_notification = $request->allow_notification;
        $obj->save();
        return $obj;
    }

    function update_user_picture($id, $newuserProfilePicture, $old_image)
    {
        $imageHelper = new \ImageHelper(); //CALL IMAGE HELPER
        $obj = User::find($id);
        $obj->userProfilePicture = $newuserProfilePicture;
        $obj->save();
        if (!empty($old_image))
            $imageHelper->unlink_user_image($old_image); // REMOVE OLD IMAGE FROM
        return $obj;
    }


}