@if(isset($data) && count($data) != 0)
@foreach($data as $key=> $value)
<!-- @php $image_url = config('constants.default_user_image') @endphp
@if(!empty($value->imageUrl))
@php $image_url =$value->imageUrl @endphp
@endif -->
<div class="user-card-row" id="search_result_section_ ">

    <div class="user-details">
        <div class="user-pic">
            <!-- <img src="{{ $image_url }}"> -->
        </div>
        <div class="user-info">
        <?php $restinfo =get_restaurant_name($value->restaurantId);?>
            <h3>{{@$restinfo}} </h3>
           <span class="users-list-date">Check-In: {{ set_date_format($value->created_at) }}</span>
           @if($value->status == 0)
           <span class="users-list-date">Check-Out: {{ set_date_format($value->updated_at) }}</span>
           @endif
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