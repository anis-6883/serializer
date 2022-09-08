@extends('layouts.app')

@section('css-stylesheet')
<link rel="stylesheet" href="{{ asset('public/backend/plugins/dropify/dropify.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/backend/plugins/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
<style>
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
            <a href="{{ route('serials.index') }}">
                Serials
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card position-relative">
            <div class="card-body">
                <h3 class="font-weight-bold mb-4">{{ _lang('Edit') }} <span style="color: #dc3545">{{ _lang('Serial') }}</span></h3>
                <form method="POST" autocomplete="off" action="{{ route('serials.update', $serial->id) }}" enctype="multipart/form-data">
					@csrf
                    @method('PUT')
					<div class="row">
						
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Serial Name') }}</label>
								<input type="text" name="serial_name" class="form-control" value="{{ $serial->serial_name }}" required>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-control-label">{{ _lang('Serial Unique Id') }}</label>
								<input type="text" name="serial_unique_id" class="form-control" value="{{ $serial->serial_unique_id  }}" readonly required>
							</div>
						</div>
						<div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Image') }}</label>
                                <input type="file" class="form-control dropify" name="serial_image" data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG" data-default-file="{{ asset($serial->serial_image) }}">
                            </div>
                        </div>

						<div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">{{ _lang('Status') }}</label>
                                <select class="form-control" name="status" data-selected="{{ $serial->status }}" required>
                                    <option value="1">{{ _lang('Active') }}</option>
                                    <option value="0">{{ _lang('In-Active') }}</option>
                                </select>
                            </div>
                        </div>

						<div class="col-md-12">
							<div class="form-group">
								{{-- <button type="reset" class="btn btn-danger btn-md">{{ _lang('Reset') }}</button> --}}
								<button type="submit" class="btn btn-primary btn-md">{{ _lang('Update') }}</button>
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
<script>
    // Load Dropify
	$(".dropify").dropify();

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