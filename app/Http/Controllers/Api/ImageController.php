<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Images;

class ImageController extends Controller
{
    public function Add_or_Update(request $request)
    {
        $rules['image'] = config('custom_validation.profile_image');
        $msg['image.required'] = trans('msg.req_user_image');
        $msg['image.mimes'] = trans('msg.req_user_image_mimes');
        $msg['image.max'] = trans('msg.req_user_image_max');

        /*$validator = Validator::make($request->all(), $rules, $msg);
        if ($validator->fails())
        {
        return json_response(['status' => 400, 'msg' => $validator->messages()->first()], 400);
        }
        */
        $imageHelper = new \ImageHelper(); // CALL IMAGE HELPER
        $imageResponse = $imageHelper->upload_user_image($request);
        $request->new_image = $imageResponse;
        $userModel = new Images();
        $response = $userModel->update_picture($request);

        return json_response(['status' => true, 'data' => @$response, 'message' => 'Success'], 200);
    }
    public function Get_images()
    {
        $response = Images::select('*')->get();
        foreach ($response as $key => $val)
        {
            $arr['imageId'] = $val->imageId;
            //$arr['image'] = Image_url($val->imageName);
            $arr['image'] = 'https://where2.s3.amazonaws.com/' . $val->imageName;

            $data[] = $arr;
        }
        return json_response(['status' => true, 'data' => @$data, 'message' => 'Success'], 200);
    }
}