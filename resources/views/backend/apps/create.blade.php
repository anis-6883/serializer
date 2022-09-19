@extends('layouts.app')

@section('css-stylesheet')
<link rel="stylesheet" href="{{ asset('public/backend/plugins/dropify/dropify.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/backend/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/backend/plugins/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
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
            <a href="{{ route('apps.index') }}">
                Apps
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Create</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card position-relative">
            <div class="card-body">
                <h3 class="font-weight-bold mb-4">{{ _lang('Add New') }} <span style="color: #dc3545">{{ _lang('App') }}</span></h3>
                <form method="POST" autocomplete="off" action="{{ route('apps.store') }}" enctype="multipart/form-data">
					@csrf
					@method('POST')
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('App Name') }}</label>
								<input type="text" name="app_name" class="form-control" value="{{ old('app_name') }}" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('App Unique Id') }}</label>
								<input type="text" name="app_unique_id" class="form-control" value="{{ time() }}_{{ rand() }}" readonly required>
							</div>
						</div>
						<div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Logo') }}</label>
                                <input type="file" class="form-control dropify" name="app_logo" data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG">
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Notification Type') }}</label>
                                <select id="select2" class="form-control" name="notification_type" required>
                                    <option value="">{{ _lang('Select One') }}</option>
                                    <option value="onesignal">{{ _lang('One Signal') }}</option>
                                    <option value="fcm">{{ _lang('FCM') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 d-none">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('One Signal App ID') }}</label>
								<input type="text" name="onesignal_app_id" class="form-control" value="{{ old('onesignal_app_id') }}">
							</div>
						</div>
						<div class="col-md-6 d-none">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('One Signal Api Key') }}</label>
								<input type="text" name="onesignal_api_key" class="form-control" value="{{ old('onesignal_api_key') }}">
							</div>
						</div>

                        <div class="col-md-6 d-none">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Firebase Server Key') }}</label>
								<input type="text" name="firebase_server_key" class="form-control" value="{{ old('firebase_server_key') }}">
							</div>
						</div>
						<div class="col-md-6 d-none">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Firebase Topics') }}</label>
								<input type="text" name="firebase_topics" class="form-control" value="{{ old('firebase_topics') }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Android App Publishing Control') }}</label>
								
								<select class="form-control" name="app_publishing_control" required>
                                    <option value="on">{{ _lang('On') }}</option>
                                    <option value="off">{{ _lang('Off') }}</option>
                                </select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Android Ads Control') }}</label>
								
								<select class="form-control" name="ads_control" required>
                                    <option value="on">{{ _lang('On') }}</option>
                                    <option value="off">{{ _lang('Off') }}</option>
                                </select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('IOS App Publishing Control') }}</label>
								
								<select class="form-control" name="ios_app_publishing_control" required>
                                    <option value="on">{{ _lang('On') }}</option>
                                    <option value="off">{{ _lang('Off') }}</option>
                                </select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('IOS Ads Control') }}</label>
								
								<select class="form-control" name="ios_ads_control" required>
                                    <option value="on">{{ _lang('On') }}</option>
                                    <option value="off">{{ _lang('Off') }}</option>
                                </select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Google App ID') }}</label>
								<input type="text" name="google_app_id" class="form-control" value="{{ old('google_app_id') }}" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Google AppOpen Ads ID') }}</label>
								<input type="text" name="google_appOpenAd_id" class="form-control" value="{{ old('google_appOpenAd_id') }}" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Google Banner Ads') }}</label>
								<input type="text" name="google_banner_ads" class="form-control" value="{{ old('google_banner_ads') }}" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Google Interstitial Ads') }}</label>
								<input type="text" name="google_interstitial_ads" class="form-control" value="{{ old('google_interstitial_ads') }}" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label" id="block__country__label">{{ _lang('Enable Countries') }}</label>
								<select id="select3" class="form-control" data-selected="{{ json_encode(old('enable_countries')) }}" name="enable_countries[]" multiple required>
									{{ get_country_list() }}
								</select>
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
							<h3 class="font-weight-bold mb-3 mt-3">{{ _lang('Required') }} <span style="color: #dc3545">{{ _lang('App') }}</span></h3>
						</div>
						<div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Required Enable App') }}</label>
                                <select class="form-control" name="required_app_enable" required>
                                    <option value="Yes">{{ _lang('Yes') }}</option>
                                    <option value="No">{{ _lang('No') }}</option>
                                    <option value="Update">{{ _lang('Update') }}</option>
                                </select>
                            </div>
                        </div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Application Id') }}</label>
								<input type="text" name="required_app_id" class="form-control" value="{{ old('required_app_id') }}" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('App Url') }}</label>
								<input type="url" name="required_app_url" class="form-control" value="{{ old('required_app_url') }}" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('App Name') }}</label>
								<input type="text" name="required_app_name" class="form-control" value="{{ old('required_app_name') }}" required>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Description') }}</label>
								<textarea name="required_app_desc" class="form-control" rows="5">{{ old('required_app_desc') }}</textarea>
							</div>
						</div>
						<div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Logo') }}</label>
                                <input type="file" class="form-control dropify" name="required_app_logo" data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG">
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
<script src="{{ asset('public/backend/plugins/dropify/dropify.min.js') }}"></script>
<script src="{{ asset('public/backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('public/backend/js/select2.js') }}"></script>
<script>
	// Load Dropify
	$(".dropify").dropify();

	// Load Select 2
    if ($("#select2").length) {
        $("#select2").select2();
    }
    if ($("#select3").length) {
        $("#select3").select2();
    }

    // Red * for Required Fields
    $("input:required, select:required, textarea:required")
            .prev()
            .append('<span class="required text-danger"> *</span>');
    $(".dropify:required")
        .parent()
        .prev()
        .append('<span class="required text-danger"> *</span>');

    // Load Selected Data
	if ($("[data-selected]").length) {
        $("[data-selected]").each(function (i, obj) {
            $(this).val($(this).data("selected")).trigger("change");
        });
    }

	// Handle One-Signal/Firebase
    $('[name=notification_type]').on('change', function() {
        $('[name=onesignal_app_id]').closest('.col-md-6').addClass('d-none');
        $('[name=onesignal_api_key]').parent().parent().addClass('d-none');
        $('[name=firebase_server_key]').parent().parent().addClass('d-none');
        $('[name=firebase_topics]').parent().parent().addClass('d-none');
        
        if($(this).val() == 'onesignal'){
			$('[name=onesignal_app_id]').parent().parent().removeClass('d-none');
        	$('[name=onesignal_api_key]').parent().parent().removeClass('d-none');
			$('[name=onesignal_app_id]').attr("required", true);
			$('[name=onesignal_api_key]').attr("required", true);
            $('[name=firebase_server_key]').removeAttr('required').val('');
            $('[name=firebase_topics]').removeAttr('required').val('');
        }else if($(this).val() == 'fcm'){
			$('[name=firebase_server_key]').closest('.col-md-6').removeClass('d-none');
        	$('[name=firebase_topics]').closest('.col-md-6').removeClass('d-none');
			$('[name=firebase_server_key]').attr("required", true);
			$('[name=firebase_topics]').attr("required", true);
            $('[name=onesignal_app_id]').removeAttr('required').val('');
            $('[name=onesignal_api_key]').removeAttr('required').val('');
        }else{
            $('[name=onesignal_app_id]').closest('.col-md-6').addClass('d-none');
			$('[name=onesignal_api_key]').parent().parent().addClass('d-none');
			$('[name=firebase_server_key]').parent().parent().addClass('d-none');
			$('[name=firebase_topics]').parent().parent().addClass('d-none');
			$('[name=onesignal_app_id]').removeAttr('required').val('');
            $('[name=onesignal_api_key]').removeAttr('required').val('');
			$('[name=firebase_server_key]').removeAttr('required').val('');
            $('[name=firebase_topics]').removeAttr('required').val('');
        }
    });
</script>
@endsection