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
        <li class="breadcrumb-item active" aria-current="page">Serials</li>
    </ol>
</nav>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card position-relative">
            <div class="card-body">
                <h3 class="font-weight-bold mb-4">Serial <span style="color: #dc3545">List</span></h3>

                @if(Auth::user()->user_type == 'admin')
                    <div class="d-flex justify-content-end mb-4">
                        <a class="btn btn-primary btn-md" href="{{ route('serials.create') }}" >
                            <i class="fas fa-plus mr-1"></i>
                            {{ _lang('Add New') }}
                        </a>
                    </div>
                @endif

                <table id="data-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>{{ _lang('Serial Image') }}</th>
        					<th>{{ _lang('Serial') }}</th>
                            <th>{{ _lang('Status') }}</th>
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
            ajax: _url + "/serials",
            "columns" : [
                
                { data : "serial_image", name : "serial_image" },
                { data : "_serial", name : "_serial" },
                { data : "status", name : "status", className : "text-center status" },
                { data : "action", name : "action", orderable : false, searchable : false, className : "text-center" }
                
            ],
            responsive: true,
            "bStateSave": true,
            "bAutoWidth":false,	
            "ordering": false
	    });

    </script>

    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

    <script type="text/javascript">

        $(function() {
            $("#data-table tbody").sortable({
                delay: 150,
                stop: function() {
                    var streaming_sources = [];
                    var streamingSourceOrder = 1;
                    $("#data-table tbody > tr").each(function(){
                        var id = $(this).attr('id').replace('row_', '');
                        streaming_sources.push( { id: id, position: streamingSourceOrder });
                        streamingSourceOrder++;
                    });
                    streaming_sources = JSON.stringify( streaming_sources );
                    updateOrder(streaming_sources);
                }
            });
    
        });
    
        function updateOrder(streaming_sources) {
            $.ajax({
                url:"{{ route('serials.reordering') }}",
                type:'POST',
                data:{
                    streaming_sources,
                    _token: "{{ csrf_token() }}"
                },
                success:function(data){
                    Toast.fire({
                        icon: 'success',
                        title: data.message,
                    })
                }
            })
        }

    </script>
@endsection