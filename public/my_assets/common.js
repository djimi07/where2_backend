function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
}
function error_message(msg,url= "")
{
    iziToast.error({
        title: 'Error',
        message: msg,
        position: 'topRight',
        onOpening: function () {},
        onOpened: function ()
        {
            if (url != "")
            {
                window.location.href = url;
            }
        },
        onClosing: function () {},
        onClosed: function () {}
    });


}

function success_without_refresh(msg)
{
    if(msg) {
        iziToast.success({
            title: 'Success',
            message: msg,
            position: 'topRight', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
            zindex: 99999999999999999999,
            onOpening: function () {
            },
            onOpened: function () {

            },
            onClosing: function () {
            },
            onClosed: function () {
            }
        });
    }
    else {
        if(url)
        {
            window.location.href = url;
        }
    }

}

function success_message(msg="",url = "",refresh_status = 1)
{
    if(msg) {
        iziToast.success({
            title: 'Success',
            message: msg,
            position: 'topRight', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
            zindex: 99999999999999999999,
            onOpening: function () {
            },
            onOpened: function () {
                if (refresh_status == 1) {
                    if (url) {
                        window.location.href = url;

                    } else {
                        window.location.reload();
                    }
                }
                if (refresh_status == 3) {
                    window.location.reload();
                }
            },
            onClosing: function () {
            },
            onClosed: function () {
            }
        });
    }
    else {
        if(url)
        {
            window.location.href = url;
        }
    }
}
function success_message_without_reload(msg)
{
    if(msg) {
        iziToast.success({
            title: 'Success',
            message: msg,
            position: 'topRight', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter, center
            zindex: 99999999999999999999,
            onOpening: function () {
            },
            onClosing: function () {
            },
            onClosed: function () {
            }
        });
    }
}


function add_update_details(url,form_id,postData)
{
    $(document).find('#'+form_id).buttonLoader('start');
    $(document).find('#'+form_id).prop('disabled', true);

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'JSON',
        data: postData,
        beforeSend: function (xhr) {
            xhr.setRequestHeader ("Authorization", __token);
        },
        success: function (response)
        {
            $('#'+form_id).prop('disabled', false);
            $('#'+form_id).buttonLoader('stop');
            var responseJSON = response;

            if(responseJSON.status == true)
            {
                if(responseJSON.url)
                {
                    success_message(responseJSON.message,responseJSON.url,1);
                }
                else
                {
                    success_message(responseJSON.message);
                }
            }
            else
            {
                error_message(responseJSON.message);
            }

        },
        error:function (response) {
            $('#'+form_id).prop('disabled', false);
            $('#'+form_id).buttonLoader('stop');
            var responseJSON = response.responseJSON;
            if(responseJSON.status == true)
            {
                success_message(responseJSON.message);
            }
            else
            {
                error_message(responseJSON.message);
            }
            //  hide_loader();
        }
    });
}

function logout_admin()
{
    iziToast.question({
        timeout: 20000,
        close: false,
        overlay: true,
        displayMode: 'once',
        id: 'Logout',
        zindex: 999,
        title: 'Hey',
        message: 'Are you sure you want to sign out?',
        position: 'center',
        buttons: [
            ['<button><b>YES</b></button>', function (instance, toast)
            {
    
    
    $.ajax({
        url: api_url+"logout",
        type: 'GET',
        timeout: 0,
        beforeSend: function (xhr) {
            xhr.setRequestHeader ("Authorization", __token);
        },
        success: function (response)
        {
            var responseJSON = response;
            if(responseJSON.status == 200)
                $.ajax({
                    url: base_url+"admin/unset-auth-session",
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        web_token:responseJSON.web_token
                    },
                    success: function (response)
                    {
                        
                        window.localStorage.setItem('auth_api_token', "");
                        window.location.reload()
                        
                    }

                });
            },
        error:function (response) {
            var responseJSON = response.responseJSON;
                    error_message(responseJSON.msg);
                }
            });
        }, true],
        ['<button>NO</button>', function (instance, toast)
        {

            instance.hide({transitionOut: 'fadeOut'}, toast, 'button');

        }],
        ],

        });
}

function logout_owner()
{
    iziToast.question({
        timeout: 20000,
        close: false,
        overlay: true,
        displayMode: 'once',
        id: 'Logout',
        zindex: 999,
        title: 'Hey',
        message: 'Are you sure you want to sign out?',
        position: 'center',
        buttons: [
            ['<button><b>YES</b></button>', function (instance, toast)
            {
    
    
    $.ajax({
        url: api_url+"logout",
        type: 'GET',
        timeout: 0,
        beforeSend: function (xhr) {
            xhr.setRequestHeader ("Authorization", __token);
        },
        success: function (response)
        {
            var responseJSON = response;
            if(responseJSON.status == 200)   
            window.localStorage.setItem('auth_api_token', "");
            window.location.reload()
                        
            },
        error:function (response) {
            var responseJSON = response.responseJSON;
                    error_message(responseJSON.msg);
                }
            });
        }, true],
        ['<button>NO</button>', function (instance, toast)
        {

            instance.hide({transitionOut: 'fadeOut'}, toast, 'button');

        }],
        ],

        });
}
function sidebar_toggle()
{

    $.ajax({
        url: api_url+"update-sidebar-toggle",
        type: 'GET',
        timeout: 0,
        beforeSend: function (xhr) {
            xhr.setRequestHeader ("Authorization", __token);
        },
        success: function (response)
        {},
        error:function (response) {

        }
    });
}


$(".toggle-password").click(function() {

    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $($(this).attr("toggle"));
    if (input.attr("type") == "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});


var next_page = 1;
function search_and_load_more_data(url,searchData,page,search_box_id,no_box_id,load_btn_id,more_icon,form_id="")
{
    
    if(form_id!= ""){
    $(document).find("#"+form_id).buttonLoader('start');
    $(document).find("#"+form_id).prop('disabled', true);
    }
    if(page == 1){
    page = next_page;
    }    
    next_page++;
    var limit = 10;
    page = page - 1;
    var offset = "";
    if (page == 0){
        offset = 0;
    }
    else{
        offset = page * limit;
        }
    searchData.limit = limit;
    searchData.offset = offset;
    searchData.web_data_status = 1; // CALL FOR WEBSITE
    searchData._token = $("input[name=_token]").val();
    if(form_id== "")
    $('#'+more_icon).show();
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'JSON',
        data:searchData,
        beforeSend: function (xhr) {
            xhr.setRequestHeader ("Authorization", __token);
        },
        success: function (response)
        {
            
            if(form_id != ""){
            $('#'+form_id).prop('disabled', false);
            $('#'+form_id).buttonLoader('stop');
            }
            $(document).find('#'+no_box_id).hide();

            if(response.total == 0){
                $('#'+no_box_id).show();
            }
            if(response.total <= limit)
                $('#'+load_btn_id).hide();
            else
                $('#'+load_btn_id).show();

            if(response.response.length < limit)
               $('#'+load_btn_id).hide();
            else
                $('#'+load_btn_id).show();

            var total_offset_value = (offset + limit);

            if(response.total == total_offset_value)
                $('#'+load_btn_id).hide();

            if(offset == 0){
                $('#'+search_box_id).empty();
            }
            $('#'+search_box_id).append(response.html_response);
            $('#'+more_icon).hide();

            if(response.rating){
            $.each(response.rating, function(obj, item) {
                var rating = item.rating;
                $(document).find('#rateYo_'+item.restaurantId+'').rateYo({
                 rating: rating,
                 starWidth: "16px",
                 readOnly: true
                     });
             }, {});
            }
        },
        error:function (response) {
            if(response.status == 401)
            {
                // console.log(base_url);
                window.location.href = base_url+'clearcache';
            }
        }

    });
}
// var next_page2 = 1;
function search_and_load_more_data_for_inner(url,searchData,page,search_box_id,no_box_id,load_btn_id,more_icon,form_id="")
{
    
    if(form_id!= ""){
    $(document).find("#"+form_id).buttonLoader('start');
    $(document).find("#"+form_id).prop('disabled', true);
    }
    // if(page == 1){
    // page = next_page2;
    // }    
    // next_page2++;
    var limit = 10;
    page = page - 1;
    var offset = "";
    if (page == 0){
        offset = 0;
    }
    else{
        offset = page * limit;
        }
    searchData.limit = limit;
    searchData.offset = offset;
    searchData.web_data_status = 1; // CALL FOR WEBSITE
    searchData._token = $("input[name=_token]").val();
    if(form_id== "")
    $('#'+more_icon).show();
    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'JSON',
        data:searchData,
        beforeSend: function (xhr) {
            xhr.setRequestHeader ("Authorization", __token);
        },
        success: function (response)
        {
            
            if(form_id != ""){
            $('#'+form_id).prop('disabled', false);
            $('#'+form_id).buttonLoader('stop');
            }
            $(document).find('#'+no_box_id).hide();

            if(response.total == 0){
                $('#'+no_box_id).show();
            }

            if(response.total <= limit)
            {
                $('#'+load_btn_id).hide();
            }
            else
            {
               $('#'+load_btn_id).show();
            }
            
            if(response.response.length < limit)
            {  
                $('#'+load_btn_id).hide();
            } 
            // else{
            //     $('#'+load_btn_id).show();
            // }
                

            var total_offset_value = (offset + limit);

            if(response.total == total_offset_value)
                $('#'+load_btn_id).hide();

            if(offset == 0){
                $('#'+search_box_id).empty();
            }
            $('#'+search_box_id).append(response.html_response);
            $('#'+more_icon).hide();
        },
        error:function (response) {
            if(response.status == 401)
            {
                window.location.href = base_url+'clearcache';
            }
        }

    });
}

function upload_image(id, url, preview_id,  input_field = "")
{
    var image = document.getElementById(id);
    var fd = new FormData();
    fd.append('image', image.files[0]);
    fd.append('_token', $("input[name=_token]").val());
    fd.append('old_image',$("#"+input_field).val());
    $.ajax({
        url: url,
        type: 'POST',
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'JSON',
        data: fd,
        beforeSend: function (xhr) {
            xhr.setRequestHeader ("Authorization", __token);
        },
        success: function (response)
        {

            if (response.status == 200)
            {
                if (input_field != "")
                {
                    if($(document).find("#temp_image_Url"))
                    {
                        $(document).find("#temp_image_Url").remove();  
                    }
                    imagename='<input type="hidden" name="imageUrl[]" id="'+response.image+'" value="'+response.image+'">';
                    $("." + input_field).append(imagename);
                
                }
                if(preview_id != "") {
                image = '<div class="img-wrap col-md-4 ">\
                <span class="close" ><i class="fa fa-remove remove_image" data="'+response.image+'" style="font-size:24px;color:red"></i></span>\
                <img id="'+response.temp_image_url+'" src="'+response.temp_image_url+'" width="220" height="150">\
                </div>';
                    $("." + preview_id).append(image);
                }
            }
        },
        error:function (response) {
            if(response.status == 413)
            {
                error_message('Allow jpeg,png,jpg,gif,svg Image type');
            }
            else{
                var responseJSON = response.responseJSON;
                error_message(responseJSON.msg);
            }
        }
    });
}
function upload_single_image(id, url, preview_id,  input_field = "")
{
    var image = document.getElementById(id);
    var fd = new FormData();
    fd.append('image', image.files[0]);
    fd.append('_token', $("input[name=_token]").val());
    fd.append('old_image',$("#"+input_field).val());
    $.ajax({
        url: url,
        type: 'POST',
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'JSON',
        data: fd,
        beforeSend: function (xhr) {
            xhr.setRequestHeader ("Authorization", __token);
        },
        success: function (response)
        {

            if (response.status == 200)
            {
                if (input_field != "")
                {
                    $('#' + input_field).val(response.temp_image_url);
                }
                if(preview_id != "") {
                    $("#" + preview_id).attr("src", response.temp_image_url);
                }
            }
        },
        error:function (response) {
            if(response.status == 413)
            {
                error_message('Allow jpeg,png,jpg,gif,svg Image type');
            }
            else{
                var responseJSON = response.responseJSON;
                error_message(responseJSON.msg);
            }
        }
    });
}



function update_single_status(url,postData,box_id,status,confirm_message="")
{
    if(confirm_message == "")
    {
    confirm_message ='Are you sure you want to delete this item?';
    }
    iziToast.question({
        timeout: 20000,
        close: false,
        overlay: true,
        displayMode: 'once',
        id: 'question',
        zindex: 999,
        title: 'Hey',
        message: confirm_message,
        position: 'center',
        buttons: [
            ['<button><b>YES</b></button>', function (instance, toast)
            {

                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'JSON',
                    data:postData,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader ("Authorization", __token);
                    },
                    success: function (data)
                    {
                        instance.hide({transitionOut: 'fadeOut'}, toast, 'button');
                        if (data.status == 400)
                        {
                            error_message(data.msg);
                        }
                        if (data.status == 200)
                        {
                            success_message(data.msg, "",2);
                        }
                        $('#'+box_id).attr('data-status',status);

                    }
                });

            }, true],
            ['<button>NO</button>', function (instance, toast)
            {

                instance.hide({transitionOut: 'fadeOut'}, toast, 'button');

            }],
        ],

    });
}


function logout_user()
{
    $.ajax({
        url: api_url+"logout",
        type: 'GET',
        timeout: 0,
        beforeSend: function (xhr) {
            xhr.setRequestHeader ("Authorization", __token);
        },
        success: function (response)
        {
            var responseJSON = response;
            if(responseJSON.status == 200)
                $.ajax({
                    url: base_url+"/unset-auth-session",
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        web_token:responseJSON.web_token
                    },
                    success: function (response)
                    {
                        
                        window.localStorage.setItem('auth_api_token', "");
                        window.location.reload()
                        
                    }

                });
        },
        error:function (response) {
            var responseJSON = response.responseJSON;

            $.ajax({
                url: base_url+"/unset-auth-session",
                type: 'GET',
                dataType: 'JSON',
                data: {
                    web_token:responseJSON.web_token
                },
                success: function (response)
                {
                    window.localStorage.setItem('auth_api_token', "");
                    window.location.reload()
                }

            });

          //  error_message(responseJSON.msg);
        }
    });
}

function delete_single_details(url,id,refresh_status = 2,confirm_message="")
{
    if(confirm_message == "")
    {
    confirm_message ='Are you sure you want to delete this item?';
    }
    
    iziToast.question({
        timeout: 2000,
        close: false,
        overlay: true,
        displayMode: 'once',
        id: 'question',
        zindex: 999,
        title: 'Hey',
        message: confirm_message,
        position: 'center',
        buttons: [
            ['<button><b>YES</b></button>', function (instance, toast)
            {
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        _token: $("input[name=_token]").val(),
                        id:id
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader ("Authorization", __token);
                    },
                    success: function (response)
                    {
                        instance.hide({transitionOut: 'fadeOut'}, toast, 'button');
                        if (response.status == 400)
                        {
                            error_message(response.msg);
                        }
                        if (response.status == 200)
                        {
                            $('#search_result_section_'+id).hide();
                            $('#search_result_section_'+id).empty();
                            success_message(response.msg,"",refresh_status);
                        }

                    }
                });
                }, true],
            ['<button>NO</button>', function (instance, toast)
            {

                instance.hide({transitionOut: 'fadeOut'}, toast, 'button');

            }],
        ],

    });
}

function delete_single_details_without_zindex(url,id,refresh_status = 2,confirm_message="")
{
    if(confirm_message == "")
    {
    confirm_message ='Are you sure you want to delete this item?';
    }
    
    iziToast.question({
        timeout: 2000,
        close: false,
        overlay: true,
        displayMode: 'once',
        title: 'Hey',
        message: confirm_message,
        position: 'center',
        buttons: [
            ['<button><b>YES</b></button>', function (instance, toast)
            {
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        _token: $("input[name=_token]").val(),
                        id:id
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader ("Authorization", __token);
                    },
                    success: function (response)
                    {
                        instance.hide({transitionOut: 'fadeOut'}, toast, 'button');
                        if (response.status == 400)
                        {
                            error_message(response.msg);
                        }
                        if (response.status == 200)
                        {
                            $('#search_result_section_'+id).hide();
                            $('#search_result_section_'+id).empty();
                            success_message(response.msg,"",refresh_status);
                        }

                    }
                });
                }, true],
            ['<button>NO</button>', function (instance, toast)
            {

                instance.hide({transitionOut: 'fadeOut'}, toast, 'button');

            }],
        ],

    });
}

function loadmorehtml()
{
    var html='<div class="row">\
    <div class="col-xs-12">\
    </div>\
    <div class="col-xs-12 append-users-invite" id="search_result_box_invite">\
    </div>\
    <div class="text-center" id="load_more_btn_invite" style="display:none">\
        <div><div id="appendloardmorebutton">\
        </div></div>\
    </div>\
    <div class="text-center">\
        <div class="sk-circle" id="loadmoreIcon_invite" style="display:none">\
            <div class="sk-circle1 sk-child"></div>\
            <div class="sk-circle2 sk-child"></div>\
            <div class="sk-circle3 sk-child"></div>\
            <div class="sk-circle4 sk-child"></div>\
            <div class="sk-circle5 sk-child"></div>\
            <div class="sk-circle6 sk-child"></div>\
            <div class="sk-circle7 sk-child"></div>\
            <div class="sk-circle8 sk-child"></div>\
            <div class="sk-circle9 sk-child"></div>\
            <div class="sk-circle10 sk-child"></div>\
            <div class="sk-circle11 sk-child"></div>\
            <div class="sk-circle12 sk-child"></div>\
        </div>\
    </div>\
</div>';

return html;
}




