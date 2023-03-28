<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Invite_friend extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = ['id'];

    function send_request($request)
    {
        $obj = new Invite_friend();
        $obj->date = date('Y-m-d');
        $obj->senderId = $request->userId;
        $obj->receiverId = $request->receiverId;
        if (isset($request->restaurantId))
            $obj->restaurantId = $request->restaurantId;
        $obj->type = $request->type;
        $obj->save();
        return $obj;
    }
    function request_accept($request)
    {
        $obj = Invite_friend::find($request->id);
        $obj->isAccept = $request->isAccept;
        $obj->save();
        return $obj;

    }
    function get_notification($request)
    {
        $user = Auth::user();
        $limit = 10;
        $offset = 0;
        if (isset($request->limit) && !empty($request->limit))
            $limit = $request->limit;

        if (isset($request->offset) && !empty($request->offset))
            $offset = $request->offset;
        $res = Invite_friend::query();
        // $res->select('*',DB::raw('DATE_FORMAT(created_at, "%h.%i %p") as date'))
        $res->select('*', 'created_at as date')
            ->where(['receiverId' => $user->userId, 'isAccept' => 0]);
        $res->orderBy('id', 'DESC');
        $total_query = $res;
        $total_result = $total_query->get();
        $total = count($total_result);
        $res->limit($limit)->offset($offset);
        $result = $res->get();
        $length = count($result);
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
        $data['result'] = $result;
        $data['next'] = $next;
        $data['total'] = $total;
        return (object) $data;
    }

    function get_friend_request($request)
    {
        $res = Invite_friend::query();
        $res->select('invite_friends.*');
        $res->join('users', 'invite_friends.senderId', '=', 'users.userId');
        $res->where(['invite_friends.receiverId' => $request->userId, 'invite_friends.isAccept' => 0, 'invite_friends.type' => 2]);
        if (isset($request->search_text) && !empty($request->search_text))
        {
            $res->where(DB::raw('CONCAT(users.firstName," ",users.lastName)'), 'like', '%' . $request->search_text . '%');
        }
        $res->select('invite_friends.id', 'users.firstName', 'users.lastName');
        $res->orderBy('invite_friends.id', 'DESC');
        $result = $res->get();

        return $result;
    }

    function search_invited_user($request)
    {
        $limit = 10;
        $offset = 0;
        if (isset($request->limit) && !empty($request->limit))
            $limit = $request->limit;

        if (isset($request->offset) && !empty($request->offset))
            $offset = $request->offset;
        $res = Invite_friend::query();
        $res->where(['isAccept' => 1, 'type' => 1, 'restaurantId' => $request->id]);
        $res->orderBy('id', 'DESC');
        // $res->groupBy('senderId');
        $total_query = $res;
        $total_result = $total_query->get();
        $total = count($total_result);
        $res->limit($limit)->offset($offset);
        $result = $res->get();
        $length = count($result);
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
        $data['data'] = $result;
        $data['next'] = $next;
        $data['total'] = $total;
        return $data;
    }

}