<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $primaryKey = 'dealId';
    protected $fillable = ['dealId'];

    function Add_and_Update($request)
    {
        $dealId = 0;
        if(isset($request->dealId) && !empty($request->dealId))
        {
            $dealId = $request->dealId;   
        }

        $obj = Deal::firstOrNew(['dealId' => $dealId]);
        
        $obj->eventType=$request->eventType;

        if(@$request->restaurantId)
        $obj->restaurantId= $request->restaurantId;

        if(@$request->firstdate)
        $obj->startdate=$request->firstdate;

        if(@$request->secdate)
        $obj->enddate=$request->secdate;

        if(@$request->eventname)
        $obj->eventName=$request->eventname;

        if(@$request->offer)
        $obj->offer=$request->offer;

        $obj->hot_deal =$request->hot_deal;

        if(@$request->description)
        $obj->description =$request->description;

        if(@$request->image_url){
        if ($request->image_url != $obj->imageUrl){
        $upload_extension =  explode("/", $request->image_url);
        $image_name = end($upload_extension);
        $imageHelper = new \ImageHelper();
        $new_image =$imageHelper->move_image_from_temp($image_name);
        $obj->imageUrl =$new_image;
        }
        }

        $obj->save();

        
        return $obj;
    }

    function search_event($request){
        // print_r($request->all());die;
        $limit = 10;
         $offset = 0;
    if (isset($request->limit) && !empty($request->limit))
        $limit = $request->limit;

    if (isset($request->offset) && !empty($request->offset))
        $offset = $request->offset;

        $res =deal::query();
         if(isset($request->status))
         $res->where(['status'=>1]);

        if(isset($request->restaurantId) && !empty($request->restaurantId))
        $res->where(['restaurantId'=>$request->restaurantId]);
        $res->orderBy('dealId','DESC');
        $total_query = $res;
        $total_result = $total_query->get();
        $total = count($total_result);

        $res->limit($limit)->offset($offset);
        $result = $res->get();                
      

        $data['data']=$result;
        $data['total']=$total;

        return $data;
    }

    function update_event_status($request)
    {
        $obj = Deal::find($request->id);
        $obj->status = $request->status;
        $obj->save();
        return $obj;
      
    }
}
