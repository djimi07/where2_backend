<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Question_field;
use DB;
use Validator;
class QuestionController extends Controller
{
    public function Get_Question()
    {
        $res = Question::select('*')
                        ->where("status",1)
                        ->orderBy('id','DESC')
                        ->get();
        $arr= array();
        foreach($res as $key =>$value)
        {
            $arr[$key]=$value;
            $arr[$key]->option=Question_field::select('que_id','option')->where('que_id',$value->id)->get();
        }
       
        return json_response(['status' => 200,'response' =>$arr,'msg' => 'Success'], 200);
    }
   
    function search_user_api(Request $request)
    {
        $userModel = new Question();
        $response = $userModel->search_user($request);
        $html_response = "";
        if (isset($request->web_data_status) && $request->web_data_status == 1) {
            $html_response = view('admin.search.que', ['data' => $response['data']])->render();
        }
        return response()->json(['status' => 200, 'response' => $response['data'],'total' => $response['total'], 'html_response' => $html_response, 'msg' => 'success'], 200);
    }
    function get_single_question($id)
    {
        $response = Question::where("id",$id)->first();
        $res=Question_field::where("que_id",$id)->get();
        $data="";
        if($res->count())
        {
            $data=$res;
        }
        $response_html = view('admin.form_modal.add_question', ['data' => $response,'optiondata'=>$data])->render();
        return response()->json(['status' => 200,'html' => $response_html,'data'=> $response], 200);
    }
     public function AddorUpdate(request $request)
    {
        // print_r($request->all());die;
        $rules=[
            'question'=>'required|max:200',
            'hint'=>'required|max:200',
            'fieldtype'=>'required',
            'display_order'=>'required|numeric'
                ];
        foreach($request->option as $key => $val)
        {
            $rules['option.'.$key] = 'required_if:fieldtype,3,4,5';  
        }
        $count=count($request->option);
         for($i=1; $i<=$count; $i++)
        {
            $msg['option.'.$i.'.required_if'] = 1+$i.' Option is required'; 
        }
        $msg['option.0.required_if'] = trans('msg.req_option');
        $msg['question.required'] = trans('msg.req_question');
        $msg['fieldtype.required'] = trans('msg.req_field_type');
        $msg['hint.required'] = trans('msg.req_hint');
        $msg['question.max'] = trans('msg.que_max');
        $msg['hint.max'] = trans('msg.hint_max');
        $msg['display_order.required'] = 'Display Order is required.';
      
        $validator = Validator::make($request->all(), $rules,$msg);
        if ($validator->fails()){  
            return json_response(['status' => 400, 'msg' => $validator->messages()->first()], 400);
        }   
        $QuestionModel= new Question();
        $res=$QuestionModel->Question_add_update($request);
        if($request->id){
            return json_response(['status' => 200,'response' =>$res,'msg' => 'Updated Successfully'], 200);  
        }
        else{
        return json_response(['status' => 200,'response' =>$res,'msg' => 'Added Successfully'], 200); 
        } 
    }
    public function User_Answer(request $request)
    {
        $auth_user = get_auth();
        // print_r($request->all());die;
        
        foreach($request->data as $key => $value){
        $filledarr=[
                'user_id'=>$auth_user->userId,
                'question_id'=>$value['question_id'],
                'answer'=>$value['answer'],
                
        ];
        if(DB::table('user_ans')->where(['user_id'=>$auth_user->userId,"question_id"=>$value['question_id']])->exists()){
            $res = DB::table('user_ans')->where(['user_id'=>$auth_user->userId,"question_id"=>$value['question_id']])->update($filledarr);
        }
        else{
            $res = DB::table('user_ans')->insert($filledarr);
        }
    }

         return json_response(['status' => 200,'response' =>$res,'msg' => 'Success'], 200);  

    }
    public function single_question_delete(request $request)
    {
        $dd = Question::where("id",$request->id)->delete();
              Question_field::where("que_id",$request->id)->delete();
        if($dd){
            return response()->json(['status' => 200, 'response' => 'success','msg' => trans('msg.req_delete')], 200);
      
        }
    }
    function update_question_status(request $request)
    {
        $Q_Model = new Question();
        $response = $Q_Model->update_ques_status($request->id);
        if ($response->status == 1)
            $msg = trans('msg.succ_active');
        else
            $msg = trans('msg.de_active');
        return response()->json(['status' => 200, 'msg' => $msg], 200);
    }
   
    public function get_user_que_answer($id)
    {
        $userdata = DB::table('user_ans')->where('user_id',$id)
       ->join('questions', 'questions.id', '=', 'user_ans.question_id')
       ->select('questions.question', 'user_ans.answer')
       ->get();
       $html = view('admin.form_modal.view_user_que_answer', ['data' => $userdata])->render();
       return response()->json(['status' => 200, 'response' =>$html], 200);
    }

    public function single_option_delete($id)
    {
        $dd = Question_field::where("id",$id)->delete();
        if($dd){
            return response()->json(['status' => 200, 'response' => 'success','msg' => "Option Deleted successfully"], 200);
      
        } 
    }
    public function upload_question_icon(request $request)
    {
        $file = $request->image;
        $filetype = $file->getClientOriginalExtension();
        $destinationPath = 'storage/question_icon/';
        $new_file_name = "question_icon_". time() . '.' . $filetype;
        $file->move($destinationPath, $new_file_name);
        if(!empty(@$request->old_image))
        {
        $old_image_path = 'storage/question_icon/'.$image_name;
        if(file_exists($old_image_path))
            unlink($old_image_path);
        }
        return response()->json(['status' => 200, 'response' => 'success','temp_image_url'=>url('storage/question_icon/').'/'.$new_file_name, 'image'=>$new_file_name], 200);
        
    }
   

}