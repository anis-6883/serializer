@extends('layouts.app')

@section('css-stylesheet')
<link rel="stylesheet" href="{{ asset('public/backend/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/backend/plugins/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/backend/plugins/dropify/dropify.min.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
            <a href="{{ route('episodes.index') }}">
                Episodes
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Create</li>
    </ol>
</nav>

<form method="POST" autocomplete="off" action="{{ route('episodes.store') }}" enctype="multipart/form-data">
    @csrf
    @method('POST')

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card position-relative">
                <div class="card-body">
                    <h3 class="font-weight-bold mb-4">{{ _lang('Add New') }} <span style="color: #dc3545">{{ _lang('Episode') }}</span></h3>
                    <div class="row">
                        
                        <div class="col-md-12">
                            <div class="form-group" style="margin-bottom: 0px;">
                                <label class="control-label">{{ _lang('Select App') }}</label>
                                <span class="required text-danger"> *</span>
                            </div>
                            <div class="d-flex flex-wrap">
                                <div class="form-check flex-shrink-0" style="width: 150px">
                                    <label class="form-check-label">
                                        <span class="form-check-sign">Select All</span>
                                        <input class="form-check-input" type="checkbox" value="" id="checkAll">
                                    </label>
                                </div>

                                @foreach ($apps as $app)
                                    <div class="form-check flex-shrink-0" style="width: 150px">
                                        <label class="form-check-label">
                                            <span class="form-check-sign">
                                                <img src="{{ asset($app->app_logo) }}" width="20px" height="20px" style="margin-right: 5px; border-radius: 10px;margin-bottom: 5px;">
                                                {{ $app->app_name }}
                                            </span>
                                            <input class="form-check-input appbox" type="checkbox" name="apps[]" value="{{ $app->id }}">
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Serial') }}</label>
                                <select id="select2" class="form-control" name="serial_id" required>
                                    <option value="">{{ _lang('Select One') }}</option>
                                    @foreach ($serials as $serial)
                                        <option value="{{ $serial->id }}">{{ _lang($serial->serial_name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label">{{ _lang('Episode Title') }}</label>
                                <input type="text" name="episode_title" class="form-control" value="{{ old('episode_title') }}" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Image Type') }}</label>
                                <select class="form-control" name="cover_image_type" data-selected="{{ old('cover_image_type', 'none') }}" required>
                                    <option value="none">{{ _lang('None') }}</option>
                                    <option value="url">{{ _lang('Url') }}</option>
                                    <option value="image">{{ _lang('Image') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 d-none">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Image Url') }}</label>
                                <input type="text" class="form-control" name="cover_url" value="{{ old('cover_url') }}" >
                            </div>
                        </div>
                        <div class="col-md-12 d-none">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Image') }}</label>
                                <input type="file" class="form-control dropify" name="cover_image" data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group cover_image">

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">{{ _lang('Episode Date') }}</label>
                                <input id="datepicker" type="text" name="episode_date" class="form-control" value="{{ old('episode_date') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Status') }}</label>
                                <select class="form-control" name="status" required>
                                    <option value="1">{{ _lang('Active') }}</option>
                                    <option value="0">{{ _lang('In-Active') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Episode Link -->
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card position-relative">
                <div class="card-body size">
                    <h3 class="font-weight-bold mb-4">{{ _lang('Episode') }} <span style="color: #dc3545">{{ _lang('Links') }}</span></h3>
                
                    <div class="row mb-4 field-group" style="width: 100%; margin:auto;">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">

                                    <div class="text-right">
                                        <button style="padding: 1px 8px;" type="button" class="btn btn-danger btn-sm">-</button>
                                    </div>
    
                                    <div class="row">
    
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">{{ _lang('Stream Title') }}</label>
                                                <input type="text" class="form-control" name="stream_title[0]" required>
                                            </div>
                                        </div>
    
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">{{ _lang('Resulation') }}</label>
                                                <select class="form-control" name="resulation[0]" required>
                                                    <option value="">{{ _lang('Select One') }}</option>
                                                    <option value="1080p">{{ _lang('1080p') }}</option>
                                                    <option value="720p">{{ _lang('720p') }}</option>
                                                    <option value="480p">{{ _lang('480p') }}</option>
                                                    <option value="360p">{{ _lang('360p') }}</option>
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">{{ _lang('Stream Type') }}</label>
                                                <select class="form-control stream_type" name="stream_type[0]" required>
                                                    <option value="">{{ _lang('Select One') }}</option>
                                                    <option value="m3u8">{{ _lang('M3u8') }}</option>
                                                    <option value="restricted">{{ _lang('Restricted') }}</option>
                                                </select>
                                            </div>
                                        </div>
    
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">{{ _lang('Stream Url') }}</label>
                                                <input type="url" class="form-control" name="stream_url[0]" required>
                                            </div>
                                        </div>
    
                                        <!-- Nested Form Fields (Start) -->
                                        <div class="row field-group2 restricted d-none mx-4 mb-3" style="width: 100%">

                                            <div class="col-md-12">
                                                <div class="card" style="background: #F5F7FF">
                                                    <div class="card-body">
                                                        <div class="text-right">
                                                            <button style="padding: 2px 8px;" type="button" class="btn btn-danger btn-xs">-</button>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 restricted d-none">
                                                                <div class="form-group">
                                                                    <label class="control-label">{{ _lang('Name') }}</label>
                                                                    <input type="text" class="form-control" name="name[0][]" value="Content-Type">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 restricted d-none">
                                                                <div class="form-group">
                                                                    <label class="control-label">{{ _lang('Value') }}</label>
                                                                    <input type="text" class="form-control" name="value[0][]" value="application/json; charset=UTF-8">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 

                                        </div>
                                        <!-- Nested Form Fields (End) -->

                                        <div class="col-md-11 text-right restricted d-none m-auto">
                                            <div class="form-group">
                                                <button type="button" class="btn btn-primary btn-sm add-more2">{{ _lang('Add More') }}</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                 
                    <div class="col-md-12 text-right mt-4">
                        <div class="form-group">
                            <button type="button" class="btn btn-primary btn-sm add-more">{{ _lang('Add More') }}</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group" style="text-align: end">
                <button type="reset" class="btn btn-danger btn-md">{{ _lang('Reset') }}</button>
                <button type="submit" class="btn btn-primary btn-md">{{ _lang('Save') }}</button>
            </div>
        </div>

    </div>

</form>

<div class="d-none">

    <div class="row mb-4 field-group repeat" style="width: 100%; margin:auto;">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-right">
                        <button style="padding: 1px 8px;" type="button" class="btn btn-danger btn-sm remove">-</button>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Stream Title') }}</label>
                                <input type="text" class="form-control" name="stream_title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Resulation') }}</label>
                                <select class="form-control" name="resulation" required>
                                    <option value="">{{ _lang('Select One') }}</option>
                                    <option value="1080p">{{ _lang('1080p') }}</option>
                                    <option value="720p">{{ _lang('720p') }}</option>
                                    <option value="480p">{{ _lang('480p') }}</option>
                                    <option value="360p">{{ _lang('360p') }}</option>
                                </select>
                            </div>
                        </div>       
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Stream Type') }}</label>
                                <select class="form-control stream_type" name="stream_type" required>
                                    <option value="">{{ _lang('Select One') }}</option>
                                    <option value="m3u8">{{ _lang('M3u8') }}</option>
                                    <option value="restricted">{{ _lang('Restricted') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Stream Url') }}</label>
                                <input type="url" class="form-control" name="stream_url" required>
                            </div>
                        </div>

                        <!-- Nested Form Fields (Start) -->
                        <div class="row field-group2 restricted d-none mx-4 mb-3" style="width: 100%">

                            <div class="col-md-12">
                                <div class="card" style="background: #F5F7FF">
                                    <div class="card-body">
                                        <div class="text-right">
                                            <button style="padding: 2px 8px;" type="button" class="btn btn-danger btn-xs">-</button>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 restricted d-none">
                                                <div class="form-group">
                                                    <label class="control-label">{{ _lang('Name') }}</label>
                                                    <input type="text" class="form-control" name="name" value="Content-Type">
                                                </div>
                                            </div>
                                            <div class="col-md-6 restricted d-none">
                                                <div class="form-group">
                                                    <label class="control-label">{{ _lang('Value') }}</label>
                                                    <input type="text" class="form-control" name="value" value="application/json; charset=UTF-8">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 

                        </div>
                        <!-- Nested Form Fields (End) -->

                        <div class="col-md-11 text-right restricted d-none m-auto">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-sm add-more2">{{ _lang('Add More') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row field-group2 restricted mx-4 repeat2 mb-3" style="width: 100%;">
        <div class="col-md-12">
            <div class="card" style="background: #F5F7FF">
                <div class="card-body">
                    <div class="text-right">
                        <button style="padding: 2px 8px;" type="button" class="btn btn-danger btn-xs remove-row2">-</button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 restricted">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Name') }}</label>
                                <input type="text" class="form-control" name="name">
                            </div>
                        </div>
                        <div class="col-md-6 restricted">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Value') }}</label>
                                <input type="text" class="form-control" name="value">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js-script')
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="{{ asset('public/backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('public/backend/plugins/dropify/dropify.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    // Load Dropify
	$(".dropify").dropify();

    // Select 2
    if ($("#select2").length) {
        $("#select2").select2();
    }

    // Date Time Picker
    $("#datepicker").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        allowInput: true,
    });
    
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

    // Handle Cover Image/URL
    $('[name=cover_image_type]').on('change', function() {
        $('[name=cover_image]').closest('.col-md-12').addClass('d-none');
        $('[name=cover_url]').parent().parent().addClass('d-none');
        
        if($(this).val() == 'url'){
            $('[name=cover_url]').parent().parent().removeClass('d-none');
            $('.cover_image').removeClass('d-none');
            
        }else if($(this).val() == 'image'){
            $('[name=cover_image]').closest('.col-md-12').removeClass('d-none');
            $('.cover_image').addClass('d-none');
        }else{
            $('[name=cover_image]').closest('.col-md-12').addClass('d-none');
            $('[name=cover_url]').parent().parent().addClass('d-none');
            $('.cover_image').addClass('d-none');
        }
    });

    $('[name=cover_url]').on('keyup', function() {
        $('.cover_image').html('<img src="' + $(this).val() + '" style="width: 150px; border-radius: 10px;">');
    });

    // Add More Stream
    var i = 1;
    $('.add-more').on('click',function(){
        var form = $('.repeat').clone().removeClass('repeat');
        form.find('.remove').addClass('remove-row');

        form.find('[name=stream_title]').attr('name', 'stream_title[' + i + ']');
        form.find('[name=resulation]').attr('name', 'resulation[' + i + ']');
        form.find('[name=stream_type]').attr('name', 'stream_type[' + i + ']');
        form.find('[name=stream_url]').attr('name', 'stream_url[' + i + ']');
        form.find('[name=name]').attr('name', 'name[' + i + '][]');
        form.find('[name=value]').attr('name', 'value[' + i + '][]');

        i++;
        $(this).closest('.col-md-12').before(form);
    });

    // Delete Stream Card
    $(document).on('click','.remove-row',function(){
        $(this).closest('.field-group').remove();
    });

    // Handle Restricted Stream and Headers
    $(document).on('change', '.stream_type', function() {

        $dis = $(this).closest('.field-group');

        if($(this).val() == 'restricted'){
            $dis.find('.restricted').removeClass('d-none').find('input, select').attr('required', true);
        }else{
            $dis.find('.restricted').addClass('d-none').find('input, select').attr('required', false);
        }
    });

    // Add More Restricted Stream Headers
    $(document).on('click', '.add-more2', function(){
        var form = $('.repeat2').clone().removeClass('repeat2');
        var name = $(this).closest('.field-group').find('[name^="name"]:first').attr('name');
        var value = $(this).closest('.field-group').find('[name^="value"]:first').attr('name');

        form.find('[name=name]').attr('name', name);
        form.find('[name=value]').attr('name', value);

        $(this).closest('.col-md-11').before(form);
    });

    // Delete Restricted Stream Headers
    $(document).on('click','.remove-row2',function(){
        $(this).closest('.field-group2').remove();
    });

</script>
@endsection