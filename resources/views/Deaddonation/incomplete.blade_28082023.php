@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="page-header-content py-3">
            <h1 class="page-header-title">
                <div class="page-header-icon"><i class="fas fa-users"></i></div>
                <span>Incomplete</span>
            </h1>
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0 p-2">
                <div class="row">
                    {{-- <div class="col-12">
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record"
                            id="create_record"><i class="fas fa-plus mr-2"></i>Add Details</button>
                    </div> --}}
                    <style>
                        .alert {
                            transition: opacity 0.5s ease-out;
                        }
                    </style>
                    @if(session('success'))
                    <div style="margin-left: 10px" class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="col-12">
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">
                        <table class="table table-striped table-bordered table-sm small" id="dataTable">
                            <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>Employee</th>
                                    <th>Relative</th>
                                    <th>Date Of Dead</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Area Start -->
    <div class="modal fade" id="formModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <span id="form_result"></span>

                            <form id="formTitle" method="post" action="{{ url('/upload') }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Upload Death
                                            Certificates*</label>
                                        <input type="file" id="certificates" name="certificates"
                                            class="form-control form-control-sm" required>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" name="action_button" id="action_button"
                                        class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                            class="fas fa-plus"></i>&nbsp;Add</button>
                                </div>
                                <input type="hidden" name="action" id="action" />
                                <input type="hidden" name="addhidden_id" id="addhidden_id" />
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col text-center">
                            <h4 class="font-weight-normal">Are you sure you want to remove this data?</h4>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" name="ok_button" id="ok_button" class="btn btn-danger px-3 btn-sm">OK</button>
                    <button type="button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Approve Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-row mb-1">

                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">Documnt Upload*</label>
                                <input type="text" id="app_documentupload" name="app_documentupload"
                                    class="form-control form-control-sm" readonly>
                            </div>

                            <input type="hidden" name="hidden_id" id="hidden_id" />
                            <input type="hidden" name="app_level" id="app_level" value="1" />

                    </form>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" name="approve_button" id="approve_button"
                        class="btn btn-warning px-3 btn-sm">Approve</button>
                    <button type="button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal Area End -->
</main>

@endsection


@section('script')

<script>
    $(document).ready(function () {

        $('#deaddonationlist').prop('aria-expanded', 'true').removeClass('collapsed');
        $('#deaddonationlistdrop').addClass('show');
        $('#incomplete_link').addClass('active');

        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{!! route('incompletelist') !!}",

            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'emp_name_with_initial',
                    name: 'emp_name_with_initial'
                },
                {
                    data: 'emp_dep_relation',
                    name: 'emp_dep_relation'
                },
                {
                    data: 'dateofdead',
                    name: 'dateofdead'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return '<div style="text-align: right;">' + data + '</div>';
                    }
                },
            ],
            "bDestroy": true,
            "order": [
                [2, "desc"]
            ]
        });

        // allocate function
        $(document).on('click', '.allocate', function () {
            var id = $(this).attr('id');

            $('#addhidden_id').val(id);
            $('.modal-title').text('Add Documents');
            $('#action_button').html('Add');
            $('#action').val('Add');
            $('#form_result').html('');
            $('#formTitle')[0].reset();
            $('#addhidden_id').val(id);
            $('#formModal').modal('show');

        });
        // allocate function
        $(document).on('click', '.edit', function () {
            var id = $(this).attr('id');

            $('#addhidden_id').val(id);
            $('.modal-title').text('Edit Documents');
            $('#action_button').html('Edit');
            $('#action').val('Edit');
            $('#form_result').html('');
            $('#formTitle')[0].reset();
            $('#addhidden_id').val(id);
            $('#formModal').modal('show');

        });

        //   download document
        $(document).on('click', '.viewpdf', function () {
            var id = $(this).attr('id');
            var filename = $(this).attr('filename');
            console.log(filename);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("downloadpdf") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id,
                    filename: filename
                },
                success: function (data) {
                    // The download link has been clicked, and the download should start automatically.
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            })

        });


        var user_id;
        var relative_id;

        $(document).on('click', '.delete', function () {
            user_id = $(this).attr('id');
            relative_id = $(this).attr('relative_id');
            $('#confirmModal').modal('show');
        });

        $('#ok_button').click(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("incompletedelete") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: user_id,
                    relative_id: relative_id
                },
                beforeSend: function () {
                    $('#ok_button').text('Deleting...');
                },
                success: function (data) { //alert(data);
                    setTimeout(function () {
                        $('#confirmModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        alert('Data Deleted');
                    }, 2000);
                    location.reload()
                }
            })
        });

        // approve model
        var id_approve;
        // approve level 01 
        $(document).on('click', '.appL1', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("incompleteedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    if (data.count === 0) {
                        $('#app_documentupload').val('document not uploaded');
                        $('#approve_button').prop('disabled', true);
                    } else {
                        $('#app_documentupload').val('document upload success');
                        $('#approve_button').prop('disabled', false);
                    }

                    $('#hidden_id').val(id_approve);
                    $('#app_level').val('1');
                    $('#approveconfirmModal').modal('show');

                }
            })


        });

        // approve level 02 
        $(document).on('click', '.appL2', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("incompleteedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    if (data.count === 0) {
                        $('#app_documentupload').val('document not uploaded');
                        $('#approve_button').prop('disabled', true);
                    } else {
                        $('#app_documentupload').val('document upload success');
                        $('#approve_button').prop('disabled', false);
                    }

                    $('#hidden_id').val(id_approve);
                    $('#app_level').val('2');
                    $('#approveconfirmModal').modal('show');

                }
            })


        });

        // approve level 03 
        $(document).on('click', '.appL3', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("incompleteedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                      if (data.count === 0) {
                        $('#app_documentupload').val('document not uploaded');
                        $('#approve_button').prop('disabled', true);
                    } else {
                        $('#app_documentupload').val('document upload success');
                        $('#approve_button').prop('disabled', false);
                    }


                    $('#hidden_id').val(id_approve);
                    $('#app_level').val('3');
                    $('#approveconfirmModal').modal('show');

                }
            })


        });



        $('#approve_button').click(function () {
            var id_hidden = $('#hidden_id').val();
            var applevel = $('#app_level').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("incompleteapprove") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_hidden,
                    applevel: applevel
                },
                success: function (data) { //alert(data);
                    setTimeout(function () {
                        $('#approveconfirmModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        alert('Data Approved');
                    }, 2000);
                    location.reload()
                }
            })
        });
    });

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }
</script>
<script>
    window.addEventListener('DOMContentLoaded', function () {
        var alert = document.querySelector('.alert');
        if (alert) {
            setTimeout(function () {
                alert.style.opacity = 0;
                setTimeout(function () {
                    alert.style.display = 'none';
                }, 500); // Wait for the fade-out animation to finish
            }, 2000); // 5000 milliseconds = 5 seconds
        }
    });
</script>


</body>

@endsection