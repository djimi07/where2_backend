@if(isset($data) && count($data) != 0)
    @foreach($data AS $key => $value)
        <div class="user-card-row" id="search_result_section_{{ $value->precommentId }}">
            <div class="user-details">
                <div class="user-pic">
                </div>
                <div class="user-info">
                    <h3>{{ $value->comment }} </h3>
                    <div class="date">{{ set_date_format($value->created_at) }}</div>
                   
                </div>
            </div>
            <div class="user-actions">
            <a onclick="edit('{{ $value->precommentId }}')" >
                    <i class="fa fa-edit"></i>
                    <u></u>
                    <span>Edit</span>
                </a>
                <!-- <a style="display:none"></a> -->
                <a onclick="delete_box('{{ $value->precommentId }}')"  >
                    <i class="fa fa-trash"></i>
                    <u></u>
                    <span>Delete</span>
                </a>
            </div>
        </div>
    @endforeach
@endif
