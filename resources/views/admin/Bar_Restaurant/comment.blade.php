@if(isset($data) && $data->count())
@foreach($data as $value)
<!-- @php $image_url = config('constants.default_user_image') @endphp
@if(!empty($value->imageUrl))
@php $image_url =$value->imageUrl @endphp
@endif -->
<div class="user-card-row" id="search_result_section_{{ $value->commentId }}">
<input type="hidden" id="commentrestaurantid" value="{{$value->restaurantId}}">
    <div class="user-details">
        <div class="user-pic">
            <!-- <img src="{{ $image_url }}"> -->
        </div>
        <div class="user-info">
            <h3>{{@$value->firstName .' '. @$value->lastName }} </h3>
            <p>{{@$value->comment}}</p>
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