<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $primaryKey = 'favId';
    protected $fillable = ['favId'];

    //add favouite bar
    function addFavoutireBar($request)
    {
        $obj = Favourite::firstOrNew(['userId' => $request->userId, 'restaurantId' => $request->restaurantId]);
        $obj->userId = $request->userId;
        $obj->restaurantId = $request->restaurantId;
        if ($obj->status == 1)
            $obj->status = 0;
        else
            $obj->status = 1;
        $obj->save();
        return $obj;
    }
}
