<?php

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    function upload_user_image($request)
    {
        $file = $request->file('userProfilePicture');
        $filetype = $file->getClientOriginalExtension();
        $destinationPath = 'storage/app/public/image';

        $image_name = "admin_image_";
        if (isset($request->img_name) && !empty($request->img_name))
            $image_name = $request->img_name;

        $new_file_name = $image_name . time() . '.' . $filetype;

        //$file->move($destinationPath, $new_file_name);

        $stored = Storage::disk('s3')->putFileAs($destinationPath, $file, $new_file_name);

        return $stored;
    }

    function upload_owner_image($request)
    {
        $file = $request->file('ownerProfilePicture');
        $filetype = $file->getClientOriginalExtension();
        $destinationPath = 'storage/app/public/image/';

        $image_name = "owner_image_";
        if (isset($request->img_name) && !empty($request->img_name))
            $image_name = $request->img_name;

        $new_file_name = $image_name . time() . '.' . $filetype;
        // $request->file('userProfilePicture')->storeAs('public/image',$new_file_name);
        //$file->move($destinationPath, $new_file_name);
        Storage::disk('s3')->putFileAs($destinationPath, $file, $new_file_name);

        return $new_file_name;
    }

    function upload_image_into_temp($request)
    {
        $file = $request->file('image');
        $filetype = $file->getClientOriginalExtension();

        $image_name = "user_image_";
        if (isset($request->img_name) && !empty($request->img_name))
            $image_name = $request->img_name;

        $new_file_name = $image_name . time() . '.' . $filetype;
        $request->file('image')->storeAs('public/temp_image', $new_file_name);

        $full_image = url('public/storage/temp_image') . '/' . $new_file_name;
        $response = ['status' => 200, 'temp_image_url' => $full_image, 'image' => $new_file_name, 'msg' => 'Success'];
        return $response;
    }

    function move_image_from_temp($new_image, $old_image = "")
    {
        $image_name = $new_image->getClientOriginalName();

        //$full_path_source = 'storage/temp_image/';
        $full_path_dest = 'storage/app/public/image';
        //$full_path = 'storage/temp_image/';
        //File::move($full_path_source, $full_path_dest);
        //Storage::disk('s3')->move($full_path_source, $full_path_dest);
        $stored = Storage::disk('s3')->putFileAs($full_path_dest, $new_image, time() . $image_name);

        //if (!empty($old_image) && Storage::disk('s3')->exists($old_image))
        //unlink($full_path);
        //$stored = Storage::disk('s3')->delete($full_path);

        //return url('storage/app/public/image') . '/' . $image_name;
        return 'https://where2.s3.amazonaws.com/' . $stored;
    }


    function unlink_user_image($image)
    {
        /*
        $old_image_path = 'storage/app/public/image/' . $image;
        if (!empty($image) && file_exists($old_image_path))
        unlink($old_image_path);
        */

        if (!empty($image) && Storage::disk('s3')->exists($image))
        {
            //unlink($old_image_path);
            $stored = Storage::disk('s3')->delete($image);
        }

        return true;
    }

    function upload_image($request)
    {
        $file = $request->file('image');
        $filetype = $file->getClientOriginalExtension();
        $destinationPath = $request->destinationPath;

        $new_file_name = "$request->image_pre_name" . time() . '.' . $filetype;
        //$file->move($destinationPath, $new_file_name);
        $stored = Storage::disk('s3')->putFileAs($destinationPath, $file, $new_file_name);
        if (!empty(@$request->old_image))
        {
            //$upload_extension = explode("/", $request->old_image);
            //$image_name = end($upload_extension);
            $old_image_path = $request->destinationPath . '/' . $request->old_image;
            /*if (file_exists($old_image_path))
            {
            unlink($old_image_path);
            }*/
            if (Storage::disk('s3')->exists($old_image_path))
            {
                $deleted = Storage::disk('s3')->delete($old_image_path);
            }
        }
        //$data = array('temp_image_url' => url('/') . '/' . $request->destinationPath . '/' . $new_file_name, 'image' => $new_file_name);
        $data = array('temp_image_url' => 'https://where2.s3.amazonaws.com' . '/' . $stored, 'image' => $new_file_name);
        return (object) $data;
    }


}
