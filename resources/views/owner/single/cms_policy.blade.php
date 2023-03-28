@extends('owner.layouts.master')
@section('page_title')
    @if(isset($page_title) && !empty($page_title))
        {{ $page_title }}
    @else
        {{ config('constants.default_admin_page_title') }}
    @endif
@endsection
@section('content')
    <section class="content-header">
        <h1>
           Privacy Policy
        </h1>
    </section>
    <section class="content">
        <form  id="form_id">
            @csrf
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <textarea class="form-control summernote" id="description" name="description">@if(isset($data->content)){{ $data->content }}@endif</textarea>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <button type="button" id="form_id_btn" onclick="add_policy_details('form_id_btn')" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        $('.summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'hr']],
                ['view', ['fullscreen', 'codeview']],
                ['help', ['help']]
            ]
        });
    </script>
    <script>
        function add_policy_details(form_id)
        {
            var formdata={};
            var url = api_url+'update-cms-policy-api';
            formdata.description=$('#description').val();
            add_update_details(url,form_id,formdata);
        }

    </script>
@endsection
