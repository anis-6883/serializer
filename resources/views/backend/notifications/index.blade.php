@extends('layouts.app')

@section('css-stylesheet')
    <link rel="stylesheet" href="{{ asset('public/backend/css/dataTables/dataTables.bootstrap4.min.css') }}">
    <style>
        .dataTables_wrapper .dataTables_length select {
            height: calc(1.5em + 0.5rem + 2px);
        }
        .table td img, .jsgrid .jsgrid-table td img {
            width: 43px;
            height: 43px;
        }
        .swal2-modal .swal2-icon, .swal2-modal .swal2-success-ring {
            margin-top: 0;
            margin-bottom: 0;
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
        <li class="breadcrumb-item active" aria-current="page">Notifications</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card position-relative">
            <div class="card-body">
                <h3 class="font-weight-bold mb-4">Notification <span style="color: #dc3545">List</span></h3>

                @if(Auth::user()->user_type == 'admin')
                    <div class="d-flex justify-content-end mb-4">
                        <a class="btn btn-danger btn-md btn-remove mr-2" href="{{ route('notifications.deleteall') }}" >
                            <i class="fas fa-trash-alt mr-1"></i>
                            {{ _lang('Delete All') }}
                        </a>
                        <a class="btn btn-primary btn-md" href="{{ route('notifications.create') }}" >
                            <i class="fas fa-plus mr-1"></i>
                            {{ _lang('Add New') }}
                        </a>
                    </div>
                @endif

                <table id="data-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th style=" white-space: nowrap; ">{{ _lang('Title') }}</th>
                            <th style=" white-space: nowrap; ">{{ _lang('Body') }}</th>
                            <th style=" white-space: nowrap; ">{{ _lang('Created Date') }}</th>

                            <th class="text-center">{{ _lang('Action') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-script')
    <script src="{{ asset('public/backend/plugins/dataTables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/backend/plugins/dataTables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: _url + "/notifications",
            "columns" : [

            { data : "title", name : "title", className: 'details-control', responsivePriority: 1 },
            { data : "message", name : "message", className : "message" },
            { data : "created_at", name : "created_at", className : "created_at" },
            { data : "action", name : "action", orderable : false, searchable : false, className : "text-center" }

            ],
            responsive: true,
            "bStateSave": true,
            "bAutoWidth":false, 
            "ordering": false
        });
    </script>
@endsection