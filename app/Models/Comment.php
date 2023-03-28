<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $primaryKey = 'CommentId';

    //protected $fillable = ['CommentId']; // Error you must write all the fillable fields here
    protected $fillable = ['CommentId', 'userId', 'restaurantId', 'status', 'comment', 'date'];

    function add_comment($request)
    {
        $obj = new Comment();
        $obj->userId = $request->userId;
        $obj->restaurantId = $request->restaurantId;
        $obj->status = 1;
        $obj->comment = $request->commentText;

        $obj->date = date('Y-m-d');

        $obj->save();
        return $obj;
    }
    function get_comments_list($request)
    {

        $limit = 10;
        $offset = 0;
        if (isset($request->limit) && !empty($request->limit))
            $limit = $request->limit;

        if (isset($request->offset) && !empty($request->offset))
            $offset = $request->offset;

        $res = Comment::query();
        $res->where(['restaurantId' => $request->restaurantId]);
        $res->join('users', 'comments.userId', '=', 'users.userId');
        $res->select('comments.commentId', 'comments.restaurantId', 'comments.userId', 'comments.comment', 'users.firstName', 'users.lastName', 'users.userProfilePicture', 'comments.created_at');
        $res->orderBy('commentId', 'DESC');

        $total_query = $res;
        $total_result = $total_query->get();
        $total = count($total_result);

        $res->limit($limit)->offset($offset);
        $result = $res->get();


        $data['data'] = $result;
        $data['total'] = $total;

        return $data;
    }
}