<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>

<div class="error-body">
    <div class="icon-div">
        <img src="{{ asset('/default_image/error.png') }}">
    </div>
    <div class="text-div">
        <div class="error-code">
            @if(isset($status))
                {{ $status }}
            @endif
        </div>
        <div class="error-message">
            @if(isset($msg))
                {{ $msg }}
            @endif
        </div>
    </div>
    <div>
        <a href="{{ url('/') }}" class="btn btn-block my-3">Click</a>
    </div>

</div>
</body>

<style type="text/css">
    .btn {
        font-weight: 400;
        color: #fff;
        background: #F8790A;
        border: none;
        box-shadow: inset 0 0 0 0 #3d3d3d;
        -webkit-transition: ease-out 0.4s;
        -moz-transition: ease-out 0.4s;
        transition: ease-out 0.4s;
        background-image: linear-gradient(to right, #3d3d3d 50%, #f8790a 50%);
        background-size: 205% 100%;
        background-position: right bottom;
        text-align: center;
        vertical-align: middle;
        user-select: none;
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: .25rem;
    }
    .mb-3, .my-3 {
        margin-bottom: 1rem!important;
    }
    body{
        font-family: 'Open Sans', sans-serif;
        background: #EEEEEE;
        color: #3d3d3d;
    }
    .icon-div {
        padding-right: 27px;
    }
    .icon-div img {
        width: 50px;
    }
    .text-div {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .error-code {
        border-right: 2px solid;
        font-size: 26px;
        padding: 0 15px 0 15px;
        text-align: center;
    }
    .error-message {
        font-size: 18px;
        text-align: center;
        padding: 10px;
    }
    .error-body {
        position: relative;
        height: 70vh;
        align-items: center;
        display: flex;
        justify-content: center;
        flex-direction: column;
        position: relative;
    }
</style>
</html>
