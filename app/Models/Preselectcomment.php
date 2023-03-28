<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preselectcomment extends Model
{
    protected $primaryKey = 'precommentId';
    protected $fillable = ['precommentId'];

    function add_update_comment($request)
    {
        $precommentId = 0;
        if (isset($request->precommentId) && !empty($request->precommentId))
            $precommentId = $request->precommentId;

        $obj = Preselectcomment::firstOrNew(['precommentId' => $precommentId]);
        $obj->comment = $request->comment;
        $obj->save();
        return $obj;

    }
    function search($request)
    {
        $limit = 10;
        $offset = 0;
        if (isset($request->limit) && !empty($request->limit))
            $limit = $request->limit;

        if (isset($request->offset) && !empty($request->offset))
            $offset = $request->offset;

        $query = Preselectcomment::query();


        if (isset($request->search_text) && !empty($request->search_text))
        {
            $query->Where('comment', 'like', '%' . $request->search_text . '%');
        }

        $total_query = $query;
        $total_result = $total_query->get();
        $total = count($total_result);

        if (isset($request->order_by) && !empty($request->order_by))
        {
            if ($request->order_by == 'created_at-asc')
                $query->orderBy('created_at', 'ASC');
            elseif ($request->order_by == 'created_at-desc')
                $query->orderBy('created_at', 'DESC');
        }
        else
        {
            $query->orderBy('created_at', 'DESC');
        }
        $query->limit($limit)->offset($offset);
        $data = $query->get();

        return ['data' => $data, 'total' => $total];
    }

}