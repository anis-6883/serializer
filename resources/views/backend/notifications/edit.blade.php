@extends('layouts.app')

@section('css-stylesheet')
<link rel="stylesheet" href="{{ asset('public/backend/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/backend/plugins/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/backend/plugins/dropify/dropify.min.css') }}">

<style>
    select.form-control {
    	color: #000;
	}
    .form-check .form-check-label{
        margin-left: 8px;
    }
    .form-check .form-check-label > span{
        margin-right: 15px;
        font-weight: 600;
        font-size: 16px;
    }
    .select2-container--default .select2-selection--single{
        border: 1px solid #ced4da;
    }
    .select2-container .select2-selection--single{
        height: 38px;
    }
    span.select2.select2-container.select2-container--default {
        width: 100% !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        line-height: 10px;
        color: #000;
        padding-bottom: 12px;
    }
    .size .card .card-body {
        padding: 5px 1.25rem;
    }
</style>
@endsection

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard.index') }}">
                <i style="color: #000" class='fas fa-home'></i>
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('notifications.index') }}">
                Notifications
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Resend</li>
    </ol>
</nav>

<form method="POST" autocomplete="off" action="{{ route('notifications.update', $notification->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card position-relative">
                <div class="card-body">
                    <h3 class="font-weight-bold mb-4">{{ _lang('Sent New') }} <span style="color: #dc3545">{{ _lang('Notification') }}</span></h3>
                    <div class="row">
                        
                        <div class="col-md-12">
                            <div class="form-group" style="margin-bottom: 0px;">
                                <label class="control-label">{{ _lang('Select App') }}</label>
                                <span class="required text-danger"> *</span>
                            </div>

                            @php
                                $notification_apps = json_decode($notification->apps);
                                $checked = '';

                                if(count($notification_apps) == counter('apps', ['status' => 1])){
                                    $checked = 'checked';
                                }
                            @endphp

                            <div class="d-flex flex-wrap">
                                <div class="form-check flex-shrink-0" style="width: 150px">
                                    <label class="form-check-label">
                                        <span class="form-check-sign">Select All</span>
                                        <input class="form-check-input" type="checkbox" value="" id="checkAll" {{ $checked }}>
                                    </label>
                                </div>

                                @foreach ($apps as $app)
                                    @php
                                        $checked = '';

                                        if(in_array($app->id, $notification_apps)){
                                            $checked = 'checked';
                                        }
                                    @endphp
                                    <div class="form-check flex-shrink-0" style="width: 150px">
                                        <label class="form-check-label">
                                            <span class="form-check-sign">
                                                <img src="{{ asset($app->app_logo) }}" width="20px" height="20px" style="margin-right: 5px; border-radius: 10px;margin-bottom: 5px;">
                                                {{ $app->app_name }}
                                            </span>
                                            <input class="form-check-input appbox" type="checkbox" name="apps[]" value="{{ $app->id }}" {{ $checked }}>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Title') }}</label>
                                <input type="text" class="form-control" name="title" value="{{ $notification->title }}" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Body') }}</label>
                                <textarea rows="4"  class="form-control" name="body" required>{{ $notification->message }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Image Type') }}</label>
                                <select class="form-control select2" name="image_type" data-selected="{{ $notification->image_type }}">
                                    <option value="none">{{ _lang('None') }}</option>
                                    <option value="url">{{ _lang('Url') }}</option>
                                    <option value="image">{{ _lang('Image') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 @if ($errors->has('cover_url')) '' @else d-none @endif">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Image Url') }}</label>
                                <input type="text" class="form-control" name="image_url" value="{{ $notification->image_url }}" >
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group image">
                                
                            </div>
                        </div>
                        <div class="col-md-12 d-none">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Image') }}</label>
                                <input type="file" class="form-control dropify" name="image" data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG" data-default-file="{{ $notification->image ? asset($notification->image) : '' }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Notification Type') }}</label>
                                <select class="form-control select2" name="notification_type" data-selected="{{ $notification->notification_type }}">
                                    <option value="in_app">{{ _lang('inApp') }}</option>
                                    <option value="url">{{ _lang('Url') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 {{ $notification->notification_type != 'url' ? 'd-none' : '' }}">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Action Url') }}</label>
                                <input type="text" class="form-control" name="action_url" value="{{ $notification->action_url }}" >
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="form-group">
                                <button type="reset" class="btn btn-danger btn-md">{{ _lang('Reset') }}</button>
                                <button type="submit" class="btn btn-primary btn-md">{{ _lang('Save') }}</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</form>

@endsection

@section('js-script')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="{{ asset('public/backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('public/backend/plugins/dropify/dropify.min.js') }}"></script>

<script>
    // Load Dropify
	$(".dropify").dropify();

    // Select 2
    if ($("#select2").length) {
        $("#select2").select2();
    }
    
    // Red * for Required Fields
    $("input:required, select:required, textarea:required")
            .prev()
            .append('<span class="required text-danger"> *</span>');
    $(".dropify:required")
        .parent()
        .prev()
        .append('<span class="required text-danger"> *</span>');

    // Load Multi-selected Data
	if ($("[data-selected]").length) {
        $("[data-selected]").each(function (i, obj) {
            $(this).val($(this).data("selected")).trigger("change");
        });
    }

    // Check All Apps
    $("#checkAll").click(function(){
        if(this.checked){
            $(this).parent().find('span').text('Unselect All');
        }else{
            $(this).parent().find('span').text('Select All');
        }
        $('.appbox').not(this).prop('checked', this.checked);
    });

    $(".appbox").change(function(){
        if ($('.appbox:checked').length == $('.appbox').length) {
            $("#checkAll").prop('checked', true).parent().find('span').text('Unselect All');
        }else{
            $("#checkAll").prop('checked',false).parent().find('span').text('Select All');
        }
    });

    @if ($notification->image_type == 'image')
        $('[name=image]').closest('.col-md-12').removeClass('d-none');
    @elseif ($notification->image_type == 'url')
        $('[name=image_url]').parent().parent().removeClass('d-none');
        $('.image').html('<img src="{{ $notification->image_url }}" style="width: 150px; border-radius: 10px;">');
    @else
        $('[name=image]').closest('.col-md-12').addClass('d-none');
        $('[name=image_url]').parent().parent().addClass('d-none');
    @endif

    // Handle Cover Image/URL
    $('[name=image_type]').on('change', function() {
        $('[name=image]').closest('.col-md-12').addClass('d-none');
        $('[name=image_url]').parent().parent().addClass('d-none');
        
        if($(this).val() == 'url'){
            $('[name=image_url]').attr("required", true);
            $('[name=image]').removeAttr('required');
            $('[name=image_url]').parent().parent().removeClass('d-none');
            
        }else if($(this).val() == 'image'){
            $('[name=image]').attr("required", true);
            $('[name=image_url]').removeAttr('required').val('');
            $('[name=image]').closest('.col-md-12').removeClass('d-none');
            $('.image').parent().addClass('d-none');
        }else{
            $('[name=image]').removeAttr('required');
            $('[name=image_url]').removeAttr('required').val('');
            $('[name=image]').closest('.col-md-12').addClass('d-none');
            $('[name=image_url]').parent().parent().addClass('d-none');
            $('.image').parent().addClass('d-none');
        }
    });

    $('[name=image_url]').on('keyup', function() {
        $('.image').parent().removeClass('d-none');
        $('.image').html('<img src="' + $(this).val() + '" style="width: 150px; border-radius: 10px;">');
    });

    $('[name=notification_type]').on('change', function() {
        $('[name=action_url]').closest('.col-md-12').addClass('d-none');
        if($(this).val() == 'url'){
            $('[name=action_url]').attr("required", true);
            $('[name=action_url]').closest('.col-md-12').removeClass('d-none');
        }else{
            $('[name=action_url]').removeAttr('required');
            $('[name=action_url]').closest('.col-md-12').addClass('d-none');
        }
    });

</script>
@endsection