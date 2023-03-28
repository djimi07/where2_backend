<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Preselectcomment;
use Validator;
class PreSelectCommentController extends Controller
{
    function add_edit_comment(request $request)
    {
        $rules['comment'] = 'required';
        $msg['comment.required'] = trans('msg.req_pre_comment');

        $validator = Validator::make($request->all(), $rules,$msg);
        if ($validator->fails())
            return json_response(['status' => 400, 'message' => $validator->messages()->first()], 400);

        $Model = new Preselectcomment();
        $response = $Model->add_update_comment($request);
        if(@$request->precommentId)
        return json_response(['status' =>true,'data'=>$response,'message' =>trans('msg.succ_update_comment')], 200);
        else
        return json_response(['status' =>true,'data'=>$response,'message' =>trans('msg.succ_insert_comment')], 200);
    }
    function search_comment(Request $request)
    {
        $Model = new Preselectcomment();
        $response = $Model->search($request);
        $html_response = view('admin.search.pre_select_comment', ['data' => $response['data']])->render();
        return response()->json(['status' => 200, 'response' => $response['data'],'total' => $response['total'], 'html_response' => $html_response, 'msg' => 'success'], 200);
    }
    function get_single_edit($id)
    {
        $res = Preselectcomment::where('precommentId',$id)->first();
        $html = view('admin.form_modal.add_comment',['data'=>$res])->render();
        return response()->json(['status' => 200, 'html' => $html, 'msg' => 'success'], 200);
    }
    function delete(request $request)
    {
        $res = Preselectcomment::where('precommentId',$request->id)->delete();
        return response()->json(['status' => 200,'msg' =>trans('msg.pre_comment_delete')], 200);
    }
}
