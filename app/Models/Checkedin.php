<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkedin extends Model
{
   protected $fillable=['checkinId','userId'];
   protected $primaryKey='checkinId';

   function checked_In_Out($request)
   {

      $obj = Checkedin::firstOrNew(['userId' => $request->userId,'restaurantId'=>$request->restaurantId,'date'=>date('Y-m-d')]);
      if(@$request->userId)
      $obj->userId = $request->userId;
      if(@$request->restaurantId)
      $obj->restaurantId = $request->restaurantId;
      $obj->status = $request->status;
      $obj->date = date('Y-m-d');
      $obj->save();
      return $obj;
   }

   function get_checked_user_list($request)
   {
    $limit = 10;
    $offset = 0;
    if (isset($request->limit) && !empty($request->limit))
        $limit = $request->limit;

    if (isset($request->offset) && !empty($request->offset))
        $offset = $request->offset;

        $res =Checkedin::query();

         if(isset($request->status))
         $res->where(['status'=>1]);

         if(isset($request->userId)&& !empty($request->userId))
         $res->where(['userId'=>$request->userId]);

        if(isset($request->restaurantId) && !empty($request->restaurantId))
        $res->where(['restaurantId'=>$request->restaurantId]);

        $res->orderBy('checkinId','DESC');

        $total_query = $res;
        $total_result = $total_query->get();
        $total = count($total_result);

        $res->limit($limit)->offset($offset);
        $result = $res->get();                
      

        $data['data']=$result;
        $data['total']=$total;
        
        return $data;
   }
}
