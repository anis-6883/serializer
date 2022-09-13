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
                        <div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('One Signal App ID') }}</label>
								<input type="text" name="onesignal_app_id" class="form-control" value="{{ old('onesignal_app_id') }}" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('One Signal Api Key') }}</label>
								<input type="text" name="onesignal_api_key" class="form-control" value="{{ old('onesignal_api_key') }}" required>
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
								<label class="form-control-label">{{ _lang('IOS Share Link') }}</label>
								<input type="url" name="ios_share_link" class="form-control" value="{{ old('ios_share_link') }}" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Privacy Policy') }}</label>
								<input type="text" name="privacy_policy" class="form-control" value="{{ old('privacy_policy') }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Facebook') }}</label>
								<input type="url" name="facebook" class="form-control" value="{{ old('facebook') }}">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Telegram') }}</label>
								<input type="url" name="telegram" class="form-control" value="{{ old('telegram') }}">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Youtube') }}</label>
								<input type="url" name="youtube" class="form-control" value="{{ old('youtube') }}">
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label" id="block__country__label">{{ _lang('Enable Countries') }}</label>
								<select class="form-control select2" data-selected="{{ json_encode(old('enable_countries')) }}" name="enable_countries[]" multiple required>
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
	$("select.select2").select2();

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
</script>
@endsection