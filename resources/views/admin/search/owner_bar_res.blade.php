@if(isset($data) && count($data) != 0)
@foreach($data as $value)
@php $image_url = config('constants.default_user_image') @endphp

@if(!empty($value->imageUrl)&& @$value->type == 1 && @$value->imageType == 1)
@php $image_url ="https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=".$value->imageUrl."&key=AIzaSyDRTvKgRxnqrA8FJt_-n4EvgtK-N64GoFg" @endphp
@else
@php $image_url =$value->imageUrl @endphp
@endif
<div class="user-card-row" id="search_result_section_{{ $value->restaurantId }}">

    <div class="user-details">
        <div class="user-pic">
            <img src="{{ $image_url }}">
        </div>
        <div class="user-info">
            <h3>{{ $value->name }} </h3>
            <h5>{{@$value->address .' '. @$value->city.',  '.@$value->zipCode}}</h5>
            @if(@$value->phone)
            <div class="mobile">
                <h5><i class="fa fa-phone"></i>{{ $value->phone}} </h5>
            </div>
            @endif
            <div id="rateYo_{{ $value->restaurantId }}"></div>
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