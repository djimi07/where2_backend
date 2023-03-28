@if(isset($row) && count(@$row)!=0)
@foreach(@$row as $value)
<!-- @php $image_url = config('constants.default_user_image') @endphp
@if(!empty($value->imageUrl))
@php $image_url =$value->imageUrl @endphp
@endif -->
<div class="user-card-row" id="search_result_section_{{ @$value->checkedinId }}">

    <div class="user-details">
        <div class="user-pic">
            <!-- <img src="{{ $image_url }}"> -->
        </div>
        <div class="user-info">
            <?php $sinfo =get_user_name(@$value->userId);?>
            <h3> {{@$sinfo->firstName.' '.@$sinfo->lastName}}</h3>
            <div class="contact">
                        @if(!empty($sinfo->userEmail))
                            <div class="email"><i class="fa fa-envelope-o"></i>

                                <a href="mailto:{{ $sinfo->userEmail }}">{{ $sinfo->userEmail }}</a>
                            </div>
                        @endif
                        @if(!empty($sinfo->userMobile))
                            <div class="mobile"><i class="fa fa-phone"></i>
                            <a href="tel:{{ $sinfo->userMobile }}">{{ $sinfo->userMobile }}</a>
                            </div>
                        @endif
                    </div>
            <div class="date">Checked-In:{{ set_date_format(@$value->created_at) }}</div>
            @if($value->status == 0)
            <div class="date">Checked-Out:{{ set_date_format(@$value->updated_at) }}</div>
            @endif
            <input type="hidden" id="checkedrestaurantid" value="{{$value->restaurantId}}">
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