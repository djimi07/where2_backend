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
                    <!-- {{$value->userStatus}} -->
                   {{-- <div class="toggle-status" data-status="{{ $value->userStatus }}" id="update_status_box_{{ $value->userId }}">
                        <div class="if-activated">
                            <span class="on">Unblock</span>
                            <a onclick="update_user_status('{{ $value->userId }}',0,'update_status_box_{{ $value->userId }}')" class="deact deact-user" data-user="">Block</a>
                        </div>
                        <div class="if-deactivated">
                            <span class="off">Block</span>
                            <a onclick="update_user_status('{{ $value->userId }}',1,'update_status_box_{{ $value->userId }}')" class="act act-user" data-user="">Unblock</a>
                        </div>
                    </div>
                   --}}
                   
                </div>
            </div>
            <div class="user-actions">
            <a onclick="view('','{{ $value->userId }}')" >
                    <i class="fa  fa-eye "></i>
                    <u></u>
                    <span>Location</span>
                </a>
            {{--<a onclick="edit('{{ $value->userId }}')" >
                    <i class="fa fa-edit"></i>
                    <u></u>
                    <span>Edit</span>
                </a>--}}
                <a style="display:none"></a>
                <a onclick="delete_box('{{ $value->userId }}')"  >
                    <i class="fa fa-trash"></i>
                    <u></u>
                    <span>Remove</span>
                </a>
            </div>
        </div>
    @endforeach
@endif
