@extends('layouts.app')

@section('css-stylesheet')
<link rel="stylesheet" href="{{ asset('public/backend/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/backend/plugins/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/backend/plugins/dropify/dropify.min.css') }}">
<style>
    select.form-control {
    	color: #000;
	}
	.select2-container--default .select2-selection--multiple .select2-selection__choice{
		border: 1px solid #aaa;
		border-radius: 4px;
		cursor: default;
		float: left;
		margin-right: 5px;
		margin-top: 5px;
		padding: 6px 5px;
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
            <a href="{{ route('serials.index') }}">
                Serials
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Create</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card position-relative">
            <div class="card-body">
                <h3 class="font-weight-bold mb-4">{{ _lang('Add New') }} <span style="color: #dc3545">{{ _lang('Serial') }}</span></h3>
                <form method="POST" autocomplete="off" action="{{ route('serials.store') }}" enctype="multipart/form-data">
					@csrf
                    @method('POST')
					<div class="row">
						
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Serial Name') }}</label>
								<input type="text" name="serial_name" class="form-control" value="{{ old('serial_name') }}" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Serial Unique Id') }}</label>
								<input type="text" name="serial_unique_id" class="form-control" value="{{ time() }}_{{ rand() }}" readonly required>
							</div>
						</div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Image Type') }}</label>
                                <select id="select2" class="form-control" name="cover_image_type" required>
                                    <option value="">{{ _lang('Select One') }}</option>
                                    <option value="url">{{ _lang('Url') }}</option>
                                    <option value="image">{{ _lang('Image') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 @if ($errors->has('cover_url')) '' @else d-none @endif">
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
                                <label class="control-label">{{ _lang('Status') }}</label>
                                <select class="form-control" name="status" required>
                                    <option value="1">{{ _lang('Active') }}</option>
                                    <option value="0">{{ _lang('In-Active') }}</option>
                                </select>
                            </div>
                        </div>
						<div class="col-md-12">
							<div class="form-group">
								<button type="reset" class="btn btn-danger btn-md">{{ _lang('Reset') }}</button>
								<button type="submit" class="btn btn-primary btn-md">{{ _lang('Save') }}</button>
							</div>
						</div>
					</div>
				</form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-script')
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

    // Handle Cover Image/URL
    $('[name=cover_image_type]').on('change', function() {
        $('[name=cover_image]').closest('.col-md-12').addClass('d-none');
        $('[name=cover_url]').parent().parent().addClass('d-none');
        
        if($(this).val() == 'url'){
            $('[name=cover_url]').parent().parent().removeClass('d-none');
            $('[name=cover_url]').attr("required", true);
            $('[name=cover_image]').removeAttr('required');
            $('.cover_image').removeClass('d-none');
            
        }else if($(this).val() == 'image'){
            $('[name=cover_image]').closest('.col-md-12').removeClass('d-none');
            $('[name=cover_image]').attr("required", true);
            $('[name=cover_url]').removeAttr('required');
            $('.cover_image').addClass('d-none');
        }else{
            $('[name=cover_image]').closest('.col-md-12').addClass('d-none');
            $('[name=cover_url]').parent().parent().addClass('d-none');
            $('[name=cover_image]').removeAttr('required');
            $('[name=cover_url]').removeAttr('required');
            $('.cover_image').addClass('d-none');
        }
    });

    $('[name=cover_url]').on('keyup', function() {
        $('.cover_image').html('<img src="' + $(this).val() + '" style="width: 150px; border-radius: 10px;">');
    });
</script>
@endsection