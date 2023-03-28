@if(isset($Data) && count($Data) != 0)
@foreach($Data as $key=> $value)
<!-- @php $image_url = config('constants.default_user_image') @endphp
@if(!empty($value->imageUrl))
@php $image_url =$value->imageUrl @endphp
@endif -->
<div class="user-card-row" id="search_result_section_{{ $value['fId'] }}">

    <div class="user-details">
        <div class="user-pic">
            <!-- <img src="{{ $image_url }}"> -->
        </div>
        <div class="user-info">
            <h3>{{@$value['firstName'] .' '. @$value['lastName'] }} </h3>
            <!-- <div class="contact">
                        @if(!empty($value['userEmail']))
                            <div class="email"><i class="fa fa-envelope-o"></i>

                                <a href="mailto:{{ $value['userEmail'] }}">{{$value['userEmail'] }}</a>
                            </div>
                        @endif
                        @if(!empty($value['userMobile']))
                            <div class="mobile"><i class="fa fa-phone"></i>
                            <a href="tel:{{ $value['userMobile'] }}">{{ $value['userMobile'] }}</a>
                            </div>
                        @endif
                    </div> -->
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