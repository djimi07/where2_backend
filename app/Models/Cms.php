<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cms extends Model
{
    protected $fillable = ['type'];
    function add_update_cms_details($request)
    {
        $obj = Cms::firstOrNew(['type' => $request->type]);
        $obj->type = $request->type;
        $obj->content = $request->description;
        $obj->save();
        return true;
    }
}
