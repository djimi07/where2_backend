@if(isset($data))
@foreach($data as $value)
@php $image_url = config('constants.default_image') @endphp
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
            <h5 >{{@$value->address}} {{@$value->city.',  '. @$value->zipCode}}</h5>
            @if(@$value->phone)
            <div class="mobile">
                <h5><i class="fa fa-phone"></i> {{ $value->phone}} </h5>
            </div>
            @endif
            <div id="rateYo_{{ $value->restaurantId }}"></div>
            {{--<div class="toggle-status" data-status="{{ $value->status }}" id="update_status_box_{{ $value->restaurantId }}">
                        <div class="if-activated">
                            <span class="on">Publish</span>
                            <a onclick="update_status('{{ $value->restaurantId }}',0,'update_status_box_{{ $value->restaurantId }}')" class="deact deact-user" data-user="">UnPublish</a>
                        </div>
                        <div class="if-deactivated">
                            <span class="off">UnPublish</span>
                            <a onclick="update_status('{{ $value->restaurantId }}',1,'update_status_box_{{ $value->restaurantId }}')" class="act act-user" data-user="">Publish</a>
                        </div>
                    </div>
            --}}
            <!-- <span id="distance_$value->restaurantId">{{@$value->distance}}</span> -->
        </div>
    </div>
   
    <div class="user-actions">
        <a href="{{url('/owner/event_page').'/'.$value->restaurantId}}">
                        <i class="fa  fa-plus "></i>
                        <u></u>
                        <span>Create live</br>
                         event/special</span>
                    </a>
            <a onclick="edit('{{ $value->restaurantId }}')">
                    <i class="fa fa-edit"></i>
                    <u></u>
                    <span>Edit</span>
                </a>
                <a onclick="comment('','{{ $value->restaurantId }}')">
                    <i class="fa  fa-comments-o"></i>
                    <u></u>
                    <span>Comment</span>
                </a>
                <a onclick="Invited_user('','{{$value->restaurantId }}')">
                    <i class="fa  fa-user-plus "></i>
                    <u></u>
                    <span>Invited user</span>
                </a>
               
                <a onclick="Checkedlist('','{{ $value->restaurantId }}')">
                    <i class="fa fa-check-circle  "></i>
                    <u></u>
                    <span>Checked In list</span>
                </a>
    </div>
</div>
@endforeach
@endif