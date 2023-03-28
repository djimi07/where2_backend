@if(isset($data) && count($data) != 0)
    @foreach($data AS $key => $value)
        
        @if($value->userGender == 1)
        @php $image_url = config('constants.default_user_image') @endphp
        @else
        @php $image_url = config('constants.default_female_image') @endphp
        @endif
         @if(!empty($value->userProfilePicture))
            @php $image_url =Image_url($value->userProfilePicture) @endphp
        @endif
        <div class="user-card-row" id="search_result_section_{{ $value->userId }}">
            <div class="user-details">
                <div class="user-pic">
                    <img src="{{ $image_url }}">
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
                <a onclick="history('{{ $value->userId }}','')">
                        <i class="fa fa-history "></i>
                        <u></u>
                        <span>History</span>
                    </a>
            <a onclick="show_user_detalis('{{ $value->userId }}')">
                    <i class="fa fa-edit"></i>
                    <u></u>
                    <span>Edit</span>
                </a>
                <a onclick="friendlist('{{ $value->userId }}','')">
                    <i class="fa  fa-eercast "></i>
                    <u></u>
                    <span>Friend List</span>
                </a>
                <a onclick="delete_user_box('{{ $value->userId }}')" >
                    <i class="fa fa-trash"></i>
                    <u></u>
                    <span>DELETE</span>
                </a>


            </div>
        </div>
    @endforeach
@endif
