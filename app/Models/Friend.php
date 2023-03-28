<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Friend extends Model
{
    // protected $primaryKey = 'fId';
    // protected $fillable ='fId';
    function add_friend($request)
    {
       $obj = new Friend();
       $obj->userId = $request->senderId;
       $obj->friendId = $request->receiverId;
       $obj->save();
       return;
    }
    function get_friend_list($request)
    {
        $limit = 10;
        $offset = 0;
        if (isset($request->limit) && !empty($request->limit))
            $limit = $request->limit;

        if (isset($request->offset) && !empty($request->offset))
            $offset = $request->offset;

            $query = Friend::query();
            $query->join('users as t1','friends.friendId','=','t1.userId');
            $query->join('users as t2','friends.userId','=','t2.userId');
            // $query->where('friends.userId',$request->userId)->orwhere('friends.friendId',$request->userId);
            if (isset($request->search_text) && !empty($request->search_text)) {
                $query->where(function ($q) use ($request) {
                    $q->where(DB::raw('CONCAT(t1.firstName," ",t1.lastName)'), 'like', '%' . $request->search_text . '%');
                    $q->orwhere(DB::raw('CONCAT(t2.firstName," ",t2.lastName)'), 'like', '%' . $request->search_text . '%');
                });
            }
             $query->where(function ($q) use ($request) {
                    $q->where('friends.userId', '=',$request->userId );
                    $q->orWhere('friends.friendId', '=',$request->userId);
                });
        
            $query->select('friends.fId','friends.userId','friends.friendId','t1.firstName','t1.lastName','t2.firstName as firstName2','t2.lastName as lastName2');
            $total_query = $query;
            $total_result = $total_query->get();
            $total = count($total_result);
            $query->orderBy('friends.created_at', 'DESC');
     
                // $query->limit($limit)->offset($offset);
                $data = $query->get();
                $length =count($data);
                if($total <=$limit)
                $next = false;
                else
                $next = true;
                if($length < $limit)
                $next = false;  
                else
                $next = true;
                $total_offset_value = $offset + $limit;
                if($total == $total_offset_value)
                $next = false;
                $dd=array();
                foreach($data as $val)
                {
                    if($val->userId == $request->userId)
                    {
                        $xx['fId']=$val->fId;
                        $xx['userId']=$val->friendId;
                        $xx['firstName']=$val->firstName;
                        $xx['lastName']=$val->lastName;
                    }
                    if($val->friendId == $request->userId)
                    {
                        $xx['fId']=$val->fId;
                        $xx['userId']=$val->userId;
                        $xx['firstName']=$val->firstName2;
                        $xx['lastName']=$val->lastName2;
                    }
                    $dd[]=$xx;
                
                }
                return (object)['data' =>@$dd, 'total' =>@$total,'next'=>@$next];
    }
    
}
