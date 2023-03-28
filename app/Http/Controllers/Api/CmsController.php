<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cms;
use Validator;

class CmsController extends Controller
{
    // function update_term_condition_api(Request $request)
    // {
    //     $rules['description'] = 'required';
    //     $msg = ['description.required' => trans('msg.req_term_condition')];
    //     $validator = Validator::make($request->all(), $rules,$msg);
    //     if ($validator->fails())
    //         return response()->json(['status' => 400, 'msg' => $validator->messages()->first()], 400);

    //     $request->type = 1;
    //     $cmsModel = new Cms();
    //     $response = $cmsModel->add_update_cms_details($request);

    //     return response()->json(['status' => 200,'msg' => trans('msg.succ_update_term_condition')]);
    // }

    function update_policy_api(Request $request)
    {
        $rules['description'] = 'required';
        $msg = ['description.required' => trans('msg.req_policy')];
        $validator = Validator::make($request->all(), $rules,$msg);
        if ($validator->fails())
            return response()->json(['status' => 400, 'message' => $validator->messages()->first()], 400);

        $request->type = 2;
        $cmsModel = new Cms();
        $response = $cmsModel->add_update_cms_details($request);

        return response()->json(['status' =>true,'message' => trans('msg.succ_update_policy')]);
    }
}
