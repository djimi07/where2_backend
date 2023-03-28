@if(isset($data) && $data->count())
@foreach($data as $value)
<!-- @php $image_url = config('constants.default_user_image') @endphp
@if(!empty($value->imageUrl))
@php $image_url =$value->imageUrl @endphp
@endif -->
<div class="user-card-row" id="search_result_section_{{ $value->commentId }}">
    <input type="hidden" id="inviterestaurantid" value="{{$value->restaurantId}}">
    <div class="user-details">
        <div class="user-pic">
            <!-- <img src="{{ $image_url }}"> -->
        </div>
        <div class="user-info">
            <?php  $sinfo =get_user_name($value->senderId); 
            $rinfo =get_user_name($value->receiverId); ?>
            <h3> {{@$sinfo->firstName.' '.@$sinfo->lastName}} invited to {{@$rinfo->firstName.' '.@$rinfo->lastName}} </h3>
            <div class="date">{{ set_date_format($value->created_at) }}</div>
        </div>
      
    </div>
      
    <div class="user-actions">
    </div>
    
</div>
@endforeach
@else
<div class="user-card-row">
    <div class="text-center">
        <div class="col-xs-12">
            <h4>Record not found...!</h4>
        </div>
    </div>
</div>
@endif