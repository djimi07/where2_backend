<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkedin;
use Validator;
use Auth;

class CheckedInController extends Controller
{
    public function user_check_in(request $request)
    {
        $user = Auth::user();
        Checkedin::where(['userId' => $user->userId, 'status' => 1, 'date' => date('Y-m-d')])->update(['status' => 0]);
        $rules['restaurantId'] = 'required';
        $rules['status'] = 'required';
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
            return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);

        $model = new Checkedin();
        $request->userId = $user->userId;

        $response = $model->checked_In_Out($request);
        return response()->json(['status' => true, 'checkId' => $response, 'message' => trans('msg.checkedIn')], 200);
    }
    //Admin side
    function checked_in_user_list(request $request)
    {
        $Model = new Checkedin();
        $request->userType = 1;
        // $request->status = 1;
        $request->restaurantId = $request->id;
        $response = $Model->get_checked_user_list($request);
        $html_response = view('admin.Bar_Restaurant.checked_user_list', ['row' => $response['data']])->render();
        return response()->json(['status' => 200, 'response' => $response['data'], 'total' => $response['total'], 'html_response' => $html_response, 'msg' => 'success'], 200);
    }
}