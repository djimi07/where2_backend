@if(isset($data))
@foreach($data as $value)
@php $image_url = config('constants.default_user_image') @endphp
@if(!empty($value->photos[0]->photo_reference))
@php $image_url = 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference='.$value->photos[0]->photo_reference.'&key=AIzaSyDRTvKgRxnqrA8FJt_-n4EvgtK-N64GoFg' @endphp
@endif
<style>
.hoverblue:hover{
    background:#7453ec !important;
    color:white !important; 

}
.hoverblue{
    color: #7453ec !important;
    background: rgba(116,83,236,0.1) !important;
}
</style>
@if(@$value->exist == 1)
<div class="user-card-row" id="search_result_section_{{  $value->restaurantId }}">
@else
<div class="user-card-row" id="search_result_section_{{  $value->place_id }}">
@endif

    <div class="user-details">
        <div class="user-pic">
            <img src="{{ $image_url }}">
        </div>
        <div class="user-info">
            <h3>{{ $value->name }} </h3>
            <h5>{{@$value->formatted_address}}</h5>
            <!-- @if(@$value->international_phone_number)
            <div class="mobile">
                <h5><i class="fa fa-phone"></i>{{ $value->international_phone_number}} </h5>
            </div>
            @endif -->
            
            <div id="rateYo_{{ $value->place_id }}"></div>
            <input type="hidden" id="rest_{{$value->place_id}}" value="@if($value->restaurantId){{$value->restaurantId}}@endif">
            <input type="hidden" id="place_{{$value->place_id}}" value="@if($value->place_id){{$value->place_id}}@endif">
        </div>
    </div>

    <div class="user-actions">
        <div id="after-action_{{ $value->place_id}}" class="user-actions">
        @if(@$value->exist == 1)
        <a onclick="delete_box('{{ $value->place_id}}')">
    
        <i class="fa fa-trash"></i>
        <span>Remove</span>

        </a>
        @else
        <a onclick="add_single_bar_restaurant('{{ $value->place_id}}')">

        <i class="fa fa-plus"></i>
        <span>ADD</span>

        </a>
        @endif
        </div>
        <a onclick="delete_box('{{ $value->place_id}}')" id="remove_box_{{ $value->place_id}}" style="display:none;">
        <i class="fa fa-trash"></i>

        <span>Remove</span>

        </a>
        <a  class="hoverblue" onclick="add_single_bar_restaurant('{{ $value->place_id}}')" id="add_box_{{ $value->place_id}}" style="display:none;">
        <i class="fa fa-plus"></i>

        <span>ADD</span>
        </a>
    </div>
</div>
@endforeach
@endif