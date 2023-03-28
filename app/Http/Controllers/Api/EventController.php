<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Deal;
use App\Models\Favourite;
use App\Models\User;
use App\Models\Invite_friend;

use App\Models\Bar_restaurant;
use Carbon\Carbon;
use DB;
class EventController extends Controller
{
  public function add_update_event(request $request)
  {
    $rules['eventType'] = 'required'; 
    // $rules['title'] = 'required|max:20'; 
    $rules['firstdate'] = 'required_if:eventType,1'; 
    $rules['secdate'] = 'required_if:eventType,1'; 
    // $rules['category'] = 'required_if:eventType,1'; 
    if($request->eventType == 1)
    // $rules['offer'] = 'required_if:eventType,1'; 
    $rules['description'] = 'required'; 
    $rules['eventname'] = 'required_if:eventType,2'; 
    $rules['image_url'] = 'required'; 

    $msg['eventType.required']   = trans('msg.req_eventType');
    // $msg['title.required']   = trans('msg.req_title');
    $msg['description.max']   = trans('msg.max_description');
    $msg['image_url.required']   = trans('msg.req_image');
    $msg['firstdate.required_if']   = trans('msg.req_firstdate');
    $msg['secdate.required_if']   = trans('msg.req_secdate');
    // $msg['category.required_if']   = trans('msg.req_category');
    $msg['offer.required_if']   = trans('msg.req_offer');
    // $msg['offer.digits']   = trans('msg.digits_offer');
    // $msg['offer.numeric']   = trans('msg.num_offer');
    $msg['eventname.required_if']   = trans('msg.req_eventname');
    $msg['description.required_if']   = trans('msg.req_description');
    
    $validator = Validator::make($request->all(),$rules,$msg);
    if ($validator->fails())
        return json_response(['status' => false, 'message' => $validator->messages()->first()], 400);

        $model = new Deal;
        $response=$model->Add_and_Update($request);
        if($response->count())
        {
          Bar_restaurant::where('restaurantId',$request->restaurantId)->update(['updated_at'=>Carbon::now()]);
          $userfavBar=Favourite::select('userId')->where(['restaurantId'=>$request->restaurantId,'status'=>1])->get();
          if($userfavBar)
          {
              $userDetails = User::whereIn('userId',$userfavBar)->get();
              $barDetails=DB::table('bar_restaurants')->where('restaurantId',$request->restaurantId)->first();
              foreach($userDetails as $val)
              {
                $notification =array(
                  'device_token'=>$val->fcm_token,
                  'body'=>'Your favorite marked '.$barDetails->name.' has some updated details for you to review.',
                );
                push_notification($notification);
                Invite_friend::where(['receiverId'=>$val->userId,'restaurantId'=>$barDetails->restaurantId,'type'=>3])->delete();
               
                $obj =new Invite_friend();
                $obj->date = date('Y-m-d');
                $obj->senderId = 0;
                $obj->receiverId= $val->userId;
                $obj->restaurantId = $barDetails->restaurantId;
                $obj->type = 3;
                $obj->save();
              }
          }
          if(@$request->dealId)
            return json_response(['status' =>true,'message' =>trans('msg.succ_update')], 200);
            else
            return json_response(['status' =>true,'message' =>trans('msg.succ_insert')], 200);
    
        }

  }

  function get_event(request $request){
        $model = new Deal;
        $response =$model->search_event($request);
        $html_response = view('owner.search.event', ['data' =>$response['data']])->render();
        return response()->json(['status' => 200, 'response' => $response['data'],'total' => $response['total'], 'html_response' => $html_response, 'msg' => 'success'], 200);
  }
  function Edit($id)
    {
        $event = Deal::where("dealId",$id)->first();
        $html= view('owner.form_model.add_create_event',['data'=>$event])->render();
        return response()->json(['status' => 200, 'response' =>$html,'data'=>$event], 200);
    }
    function delete(request $request)
    {
      $event = Deal::where("dealId",$request->id)->delete();
      if($event)
      {
        return response()->json(['status' => 200, 'response' => 'success','msg' => trans('msg.req_delete')], 200);
      }
    }
    function update_status(request $request)
    {
        $Model = new Deal();
        $response = $Model->update_event_status($request);
       
        if ($response->status == 1)
            $msg = trans('msg.publish');
        else
            $msg = trans('msg.unpublish');
        return response()->json(['status' => 200, 'msg' => $msg], 200);
    }

    function upload_image(request $request)
     {
        $request->destinationPath ="storage/temp_image/";
        $request->image_pre_name="deal_";
        $imageHelper = new \ImageHelper(); // CALL IMAGE HELPER
        $imageResponse = $imageHelper->upload_image($request);
        return response()->json(['status' => 200, 'response' => 'success','temp_image_url'=>$imageResponse->temp_image_url ,'image'=>$imageResponse->image], 200);
     }
}