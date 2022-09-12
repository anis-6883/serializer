@extends('layouts.app')

@section('css-stylesheet')
<link rel="stylesheet" href="{{ asset('public/backend/plugins/dropify/dropify.min.css') }}">
<style>
    .tab-content {
        border: none;
        padding: 0rem 1rem;
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
        <li class="breadcrumb-item active" aria-current="page">General Settings</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card position-relative">
            <div class="card-body">
                <h3 class="font-weight-bold mb-4">General <span style="color: #dc3545">Settings</span></h3>

                <div class="row">
                    <div class="col-md-3">
                        <div class="nav flex-column nav-pills nav-primary nav-pills-no-bd" id="v-pills-tab-without-border" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active show btn-primary" id="v-pills-general-settings-tab" data-toggle="pill" href="#v-pills-general-settings" role="tab" aria-controls="v-pills-general-settings" aria-selected="true">{{ _lang('General Settings') }}</a>
                            <a class="nav-link btn-primary" id="v-pills-links-tab" data-toggle="pill" href="#v-pills-links" role="tab" aria-controls="v-pills-email" aria-selected="false">{{ _lang('App & Social Links') }}</a>
                            <a class="nav-link btn-primary" id="v-pills-logo-tab" data-toggle="pill" href="#v-pills-logo" role="tab" aria-controls="v-pills-logo" aria-selected="false">{{ _lang('Logo & Icon') }}</a>
                        </div>
                    </div>
        
                    <div class="col-md-9">
                        <div class="tab-content" id="v-pills-without-border-tabContent">
        
                            <div class="tab-pane fade active show" id="v-pills-general-settings" role="tabpanel" aria-labelledby="v-pills-general-settings-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="mb-3 header-title card-title">{{ _lang('General Settings') }}</h3>
                                        <form method="post" class="ajax-submit2 params-card" autocomplete="off" action="{{ route('store_settings') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('Company Name') }}</label>
                                                        <input type="text" class="form-control" name="company_name" value="{{ get_option('company_name') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('Site Title') }}</label>
                                                        <input type="text" class="form-control" name="site_title" value="{{ get_option('site_title') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('Timezone') }}</label>
                                                        <select class="form-control select2" name="timezone" required>
                                                            <option value="">{{ _lang('Select One') }}</option>
                                                            {{ create_timezone_option(get_option('timezone')) }}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('Language') }}</label>
                                                        <select class="form-control select2" name="language" required>
                                                            {{ load_language( get_option('language') ) }}
                                                        </select>
                                                    </div>
                                                </div>
                
                                                <div class="col-md-12">
                                                    <div class="form-group text-right">
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            {{ _lang('Update') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
        
                            <div class="tab-pane fade" id="v-pills-links" role="tabpanel" aria-labelledby="v-pills-live-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="mb-3 header-title card-title">{{ _lang('App & Social Links') }}</h3>
                                        <form method="post" class="ajax-submit2 params-card" autocomplete="off" action="{{ route('store_settings') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('Facebook') }}</label>
                                                        <input type="text" class="form-control" name="facebook" value="{{ get_option('facebook') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('Instagram') }}</label>
                                                        <input type="text" class="form-control" name="instagram" value="{{ get_option('instagram') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('Youtube') }}</label>
                                                        <input type="text" class="form-control" name="youtube" value="{{ get_option('youtube') }}" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-12">
                                                    <div class="form-group text-right">
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            {{ _lang('Update') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
        
                            <div class="tab-pane fade" id="v-pills-logo" role="tabpanel" aria-labelledby="v-pills-logo-tab">
                                <div class="card">
                                    <div class="card-body">
                                        <h3 class="mb-3 header-title card-title">{{ _lang('Logo & Icon') }}</h3>
                                        <form method="post" class="ajax-submit2 params-card" autocomplete="off" action="{{ route('store_settings') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('Logo') }}</label>
                                                        <input type="file" class="form-control dropify" name="logo" data-allowed-file-extensions="png jpg jpeg PNG JPG JPEG" data-default-file="{{ get_logo() }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">{{ _lang('Site Icon') }}</label>
                                                        <input type="file" class="form-control dropify" name="icon" data-allowed-file-extensions="png PNG" data-default-file="{{ get_icon() }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group text-right">
                                                        <button type="submit" class="btn btn-primary btn-sm">
                                                            {{ _lang('Update') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
    </script>
@endsection