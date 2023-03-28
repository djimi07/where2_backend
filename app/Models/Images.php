<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $fillable = ['imageId'];
    function update_picture($request)
    {

        $imageId = 0;
        if (isset($request->imageId) && !empty($request->imageId))
        {
            $imageId = $request->imageId;
        }

        $obj = Images::firstOrNew(['imageId' => $imageId]);
        $obj->imageName = $request->new_image;
        $obj->type = 1;
        $obj->save();


        return true;
    }
    function upload_images($request)
    {
        $obj = new Images();
        $obj->restaurantId = $request->restaurantId;
        $obj->imageName = $request->new_image;
        if (isset($request->type))
            $obj->type = $request->type;
        $obj->save();
    }
}
