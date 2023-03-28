@if(isset($data))
@foreach($data as $value)
@php $image_url = config('constants.default_image') @endphp
@if(!empty($value->imageUrl))
@php $image_url =$value->imageUrl @endphp
@endif
<div class="user-card-row" id="search_result_section_{{ $value->dealId }}">

    <div class="user-details">
        <div class="user-pic">
            <img src="{{ $image_url }}">
        </div>
        <div class="user-info">
            <?php $sinfo =get_restaurant_name(@$value->restaurantId);?>
            <h3>{{@$sinfo}}</h3>
            @if($value->eventType == 1)
            <h5>{{@$value->offer}}</h5>
            <h5>Description: {{strlenghtlimit(@$value->description)}}</h5>
            <div class="date"> Start/EndDate : {{Date('m/d/Y',strtotime($value->startdate)).' - '.Date('m/d/Y',strtotime($value->enddate))}}</div>
            @endif
            @if($value->eventType == 2)
            <h5>Event: {{@$value->eventName}}</h5>
            <h5>Description: {{strlenghtlimit(@$value->description)}}</h5>
            @endif
            @if(@$value->eventType == 1 && @$value->enddate < date('Y-m-d'))
            <h5 style="color:red">Event Expired</h5>
            @endif   
            <div class="toggle-status" data-status="{{ $value->status }}" id="update_status_box_{{ $value->dealId }}">
                <div class="if-activated">
                    <span class="on">Publish</span>
                    <a onclick="update_status('{{ $value->dealId }}',0,'update_status_box_{{ $value->dealId }}')"
                        class="deact deact-user" data-user="">Unpublish</a>
                </div>
                <div class="if-deactivated">
                    <span class="off">Unpublish</span>
                    <a onclick="update_status('{{ $value->dealId }}',1,'update_status_box_{{ $value->dealId }}')"
                        class="act act-user" data-user="">Publish</a>
                </div>
            </div>
            <!-- <span id="distance_$value->dealId">{{@$value->distance}}</span> -->
        </div>
    </div>

    <div class="user-actions">

        <a onclick="edit('{{ $value->dealId }}')">
            <i class="fa fa-edit"></i>
            <u></u>
            <span>Edit</span>
        </a>
        <a onclick="delete_event('{{ $value->dealId }}')">
            <i class="fa fa-trash    "></i>
            <u></u>
            <span>Delete</span>
        </a>

    </div>
</div>
@endforeach
@endif