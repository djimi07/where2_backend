@if(isset($data) && count($data) != 0)
    @foreach($data AS $key => $value)
        <!-- @php $image_url = config('constants.default_user_image') @endphp
         @if(!empty($value->userProfilePicture))
            @php $image_url =Image_url($value->userProfilePicture) @endphp
        @endif -->
        <div class="user-card-row" id="search_result_section_{{ $value->userId }}">
            <div class="user-details">
                <div class="user-pic">
                    <!-- <img src="{{ $image_url }}"> -->
                </div>
                <div class="user-info">
                    <h3>{{ $value->firstName.' '.$value->lastName }} </h3>
                
                    <div class="contact">
                        @if(!empty($value->userEmail))
                            <div class="email"><i class="fa fa-envelope-o"></i>

                                <a href="mailto:{{ $value->userEmail }}">{{ $value->userEmail }}</a>
                            </div>
                        @endif
                        @if(!empty($value->userMobile))
                            <div class="mobile"><i class="fa fa-phone"></i>
                            <a href="tel:{{ $value->userMobile }}">{{ $value->userMobile }}</a>
                            </div>
                        @endif
                    </div>
                    <div class="date">{{ set_date_format($value->created_at) }}</div>
                </div>
            </div>
            <div class="user-actions">
            @if($value->is_owner == 0)
            <a onclick="make_owner('{{ $value->userId }}')" >
                    <i class="fa  fa-plus "></i>
                    <u></u>
                    <span>Make Owner</span>
                </a>
                @else
                <a style="color:green">
                    <!-- <i class="fa  fa-plus "></i> -->
                    <u></u>
                    <span style="color:green">Owner Role</span>
                </a>
                @endif
            </div>
        </div>
    @endforeach
@endif
