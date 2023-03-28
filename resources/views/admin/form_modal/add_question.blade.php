<style type="text/css">
.info_msg {
  text-transform: uppercase;
  background: transparent;
  color: #000;
  cursor: help;
  font-size: 14px;
  position: relative;
  text-align: center;
  -webkit-transform: translateZ(0); /* webkit flicker fix */
  -webkit-font-smoothing: antialiased; /* webkit text rendering fix */
}

.info_msg .tooltip {
  background: rgba(0,0,0,.8);
  bottom: 100%;
  color: #fff;
  display: block;
  left: 0px;
  margin-bottom: 15px;
  opacity: 0;
  padding: 10px;
  pointer-events: none;
  position: absolute;
  width: 250px;
  -webkit-transform: translateY(10px);
     -moz-transform: translateY(10px);
      -ms-transform: translateY(10px);
       -o-transform: translateY(10px);
          transform: translateY(10px);
  -webkit-transition: all .25s ease-out;
     -moz-transition: all .25s ease-out;
      -ms-transition: all .25s ease-out;
       -o-transition: all .25s ease-out;
          transition: all .25s ease-out;
  -webkit-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
     -moz-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
      -ms-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
       -o-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
          box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
}

/* This bridges the gap so you can mouse into the tooltip without it disappearing */
.info_msg .tooltip:before {
  bottom: -20px;
  content: " ";
  display: block;
  height: 20px;
  left: 0;
  position: absolute;
  width: 100%;
}  

/* CSS Triangles - see Trevor's post */
.info_msg .tooltip:after {
  border-left: solid transparent 10px;
  border-right: solid transparent 10px;
  border-top: solid rgba(0,0,0,.8) 10px;
  bottom: -10px;
  content: " ";
  height: 0;
  left: 0;
  margin-left: 0;
  position: absolute;
  width: 0;
}
  
.info_msg:hover .tooltip {
  opacity: 1;
  pointer-events: auto;
  -webkit-transform: translateY(0px);
     -moz-transform: translateY(0px);
      -ms-transform: translateY(0px);
       -o-transform: translateY(0px);
          transform: translateY(0px);
}

/* IE can just show/hide with no transition */
.info_msg .tooltip {
  display: none;
}

.info_msg:hover .tooltip {
  display: block;
}
	</style>
<form id="add_edit_form_data" method="post" role="form" autocomplete="off" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="id" , id="question_id" value="@if(isset($data->id)){{ $data->id }}@endif">
    <div class="form-group">
        <div class="form-group ">
            <label for="question">Question</label>
            <input type="text" name="question" class="form-control disable" id="question" placeholder="Enter Question"
                value="@if(isset($data->question)){{ $data->question }}@endif">

        </div>
        <div class="form-group ">
            <label for="Hint">Hint</label>
            <input type="text" name="hint" class="form-control disable" id="hint" placeholder="Enter Hint"
                value="@if(isset($data->hint)){{ $data->hint }}@endif">

        </div>
        <div class="row">
            @if(isset($data->is_editable) && $data->is_editable==0)
            <div class="form-group col-lg-6">
                <label for="fieldtype">Select Field Type</label>
                <select id="fieldtype2" class="form-control" name="fieldtype" disabled>
                    <option value="">Select</option>
                    <option value="1">Input</option>
                    <option value="2">Textarea</option>
                    <option value="3">Radio</option>
                    <option value="4">Checkbox</option>
                    <option value="5">Dropdown</option>
                </select>
            </div>
            @else
            <div class="form-group col-lg-6">
                <label for="fieldtype">Select Field Type</label>
                <select id="fieldtype" class="form-control" name="fieldtype">
                    <option value="">Select</option>
                    <option value="1">Input</option>
                    <option value="2">Textarea</option>
                    <option value="3">Radio</option>
                    <option value="4">Checkbox</option>
                    <option value="5">Dropdown</option>
                </select>
            </div>
            @endif
            <div class="form-group col-lg-6 ">
                <label for="Hint">Display Order</label>
                <input type="text" name="display_order" class="form-control" id="display_order"
                    placeholder="Display Order"
                    value="@if(isset($data->display_order)){{ $data->display_order }}@endif">

            </div>
        </div>


        <?php 
        $is_yes="";
        $no="";
        $publish="";
        $unp="";
        $is_field_yes="";
        $field_no="";
        if(isset($data->is_mandatory)){
        if(!empty($data->is_mandatory) && @$data->is_mandatory =="1" || @$data->is_mandatory ==1){
            $is_yes="checked";
        }
        else{
            $no="checked";      
        }
        if(!empty($data->status) && @$data->status =="1" || @$data->status ==1){
            $publish="checked";
        }
        else{
            $unp="checked";      
        }
        if(@$data->field_input_type ==1 ||@$data->field_input_type =="1"){
            $is_field_yes="checked";
        }
        else{
            $field_no="checked"; 
        }
        }
        else{
            $publish="checked"; 
            $is_yes="checked";
            $is_field_yes="checked";
        }
        ?>
        <div class="form-group row">
            <div class="col-lg-6">
                <div class="form-group status" id="mandatory">    
                    <label for="">Mandatory </label>&nbsp;
                    <span class="info_msg pl-1" >
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltip">If clicked Yes, Question at App end will be Mandatory to answer. If clicked No, Question at App end will have Skip Option.</span>
                        
                    </span>
                    <br>
                    <label>
                        <input type="radio" name="is_mandatory" id="is_mandatory" value="1" class="minimal"
                            {{@$is_yes}}> Yes
                    </label>
                    &nbsp;
                    <label>
                        <input type="radio" name="is_mandatory" id="is_man" value="0" class="minimal" {{@$no}}> No
                    </label>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group status" id="status">
                    <label for="">Status</label><br>
                    <label>
                        <input type="radio" name="status" id="Publish" value="1" class="minimal" {{@$publish}}> Publish
                    </label>
                    &nbsp;
                    <label>
                        <input type="radio" name="status" id="UnPublish" value="0" class="minimal" {{@$unp}}> UnPublish
                    </label>
                </div>
            </div>
            <?php
             $Yedit="";
             $Nedit="";
            if(@$data){    
             if(@$data->is_editable == 1 || @$data->is_editable =="1"){
                $Yedit="checked";
            }
            else{
                $Nedit="checked"; 
            }}
            else{
                $Yedit="checked";  
            }
            ?>
            <div class="col-lg-12">
                <div class="form-group status" id="editable">
                    <label for="">Editable</label> &nbsp;
                    <span class="info_msg pl-1" >
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                    <span class="tooltip">If clicked Yes, Question's Details can be Editable from Admin's End.If clicked No, only Display Order will be Editable.</span>
                       
                    </span>
                    <br>
                    <label>
                        <input type="radio" name="is_editable" id="" value="1" class="minimal" {{$Yedit}}> Yes
                    </label>
                    &nbsp;
                    <label>
                        <input type="radio" name="is_editable" id="" value="0" class="minimal" {{$Nedit}}> No
                    </label>
                </div>
            </div>
        </div>
        <div id="onlyinput" style="display:none">
            <div class="form-group ">
                <label for="">Input Type</label><br>
                <label>
                    <input type="radio" name="inputtype" id="inputtype" value="1" class="minimal" {{$is_field_yes}}>
                    Numeric
                </label>
                &nbsp;
                <label>
                    <input type="radio" name="inputtype" id="_inputtype" value="0" class="minimal" {{$field_no}}> String
                </label>
            </div>
        </div>

        <div id="choiceoption" style="display:none">
            <div class="form-group row items">
                <div class="">
                    <input type="hidden" name="per_q_count[]" class="per_q_count" value="1">
                    <div class="col-sm-8">
                        <div class=" form-group">
                            <input type="text" class="vol form-control" placeholder="Enter Option" name="option[]">

                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-primary add_field_button">Add Option</button>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($optiondata)&&!empty($optiondata))
        <?php $i = 0;?>
        <div class="form-group row append_row">
            @foreach($optiondata as $val)
            <div id="search_result_section_{{$val->id}}">
                <div class="col-sm-8">
                    <div class="form-group option">
                        <input type="hidden" name="option_id[]" value="{{$val->id}}">
                        <input type="text" id="option_exist" class="vol form-control disable" placeholder="Enter Option"
                            name="option[<?php echo $i ?>]" value="{{$val->option}}">
                    </div>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-danger delete_button"
                        onclick="delete_option({{$val->id}})">DELETE</button>
                </div>
            </div>
            <?php $i++;?>
            @endforeach
            <div class=" form-group col-sm-1">
                <button type="button" id="add_field_button2" class="btn btn-success">Add Option</button>
            </div>
        </div>
        @endif
        <div class="form-group row">
            <div class="col-md-4">
                <label for="image_file">Icon</label>
                <div class="">
                    <img id="icon_preview"
                        src="@if(isset($data->question_icon) && !empty($data->question_icon)){{$data->question_icon }}@else{{ config('constants.default_image') }}@endif"
                        style="width: 100px;">

                    <div class="mt-10">
                        <label class=" btn btn-secondary ">
                            <input type="file" name="icon_image" class="disable" id="icon_image"
                                onchange="upload_user_image('icon_image','question_icon','icon_preview')">
                            <!-- Select File -->
                        </label>
                    </div>
                </div>
            </div>

        </div>
        <input type="hidden" name="question_icon" id="question_icon"
            value="@if(isset($data->question_icon)){{ $data->question_icon }}@endif">
        <div class="mb-10">
        <input type="hidden"  id="editable_or_not"
            value="@if(isset($data->is_editable)){{ $data->is_editable }}@else 1 @endif">
        <div class="mb-10">
            <button type="submit" id="subbutton" submit="submit" class="btn btn-primary form-custom-btn">Submit
            </button>
        </div>
</form>