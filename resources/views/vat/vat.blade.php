@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
            @include('layouts.corporate_nav_bar')
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0 p-2">
                <div class="row">
                    <div class="col-12">
                        @if(in_array('Vat-create',$userPermissions))
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record"
                            id="create_record"><i class="fas fa-plus mr-2"></i>Add New Vat</button>
                            @endif
                    </div>
                    <div class="col-12">
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">
                        <div class="center-block fix-width scroll-inner">
                            <table class="table table-striped table-bordered table-sm small nowrap display"
                                style="width: 100%" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>ID </th>
                                        <th>From Date</th>
                                        <th>Vat (%)</th>
                                        <th>Tax (%)</th>
                                        <th>NBT (%)</th>
                                        <th>SSCL (%)</th>
                                        <th>Approval Status</th>
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
    </div>
    <!-- Modal Area Start -->
   

    <div class="modal fade" id="formModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Vehicle</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <span id="form_result"></span>
                            <form method="post" id="formTitle" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">From Date*</label>
                                        <input type="date" name="fromdate" id="fromdate"
                                            class="form-control form-control-sm" required />
                                    </div>
                                </div>

                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Vat (%)*</label>
                                        <input type="text" name="vat" id="vat" value="0"
                                            class="form-control form-control-sm" required />
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Tax (%)*</label>
                                        <input type="text" name="tax" id="tax" value="0"
                                            class="form-control form-control-sm" required />
                                    </div>
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">NBT (%)*</label>
                                        <input type="text" name="nbt" id="nbt" value="0"
                                            class="form-control form-control-sm" required />
                                    </div>

                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">SSCL (%)*</label>
                                        <input type="text" name="sscl" id="sscl" value="0"
                                            class="form-control form-control-sm" required />
                                    </div>


                                    <div class="form-group mt-3">
                                        <button type="submit" name="action_button" id="action_button"
                                            class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                                class="fas fa-plus"></i>&nbsp;Add</button>
                                    </div>
                                </div>
                                    <input type="hidden" name="action" id="action" value="Add" />
                                    <input type="hidden" name="hidden_id" id="hidden_id" />
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
                <h5 class="app_modal-title" id="staticBackdropLabel">Approve Vat Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal">

                    <div class="form-row mb-1">
                        <div class="col-12">
                            <label class="small font-weight-bold text-dark">From Date*</label>
                            <input type="date" name="app_fromdate" id="app_fromdate"
                                class="form-control form-control-sm" required readonly/>
                        </div>
                    </div>

                    <div class="form-row mb-1">
                        <div class="col-12">
                            <label class="small font-weight-bold text-dark">Vat (%)*</label>
                            <input type="text" name="app_vat" id="app_vat" class="form-control form-control-sm"
                                required readonly/>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <div class="col-12">
                            <label class="small font-weight-bold text-dark">Tax (%)*</label>
                            <input type="text" name="app_tax" id="app_tax" class="form-control form-control-sm"
                                required readonly/>
                        </div>
                        <div class="col-12">
                            <label class="small font-weight-bold text-dark">NBT (%)*</label>
                            <input type="text" name="app_nbt" id="app_nbt" class="form-control form-control-sm"
                                required readonly/>
                        </div>
                        <div class="col-12">
                            <label class="small font-weight-bold text-dark">SSCL (%)*</label>
                            <input type="text" name="app_sscl" id="app_sscl" class="form-control form-control-sm"
                                required readonly/>
                        </div>
                    </div>
                        <input type="hidden" name="app_hidden_id" id="app_hidden_id" />
                        <input type="hidden" name="app_level" id="app_level" value="1" />

                </form>
            </div>
            <div class="modal-footer p-2">
                <button type="button" name="approve_button" id="approve_button"
                    class="btn btn-warning px-3 btn-sm">Approve</button>
                <button type="button" name="reject_button" id="reject_button" class="btn btn-dark px-3 btn-sm"
                    data-dismiss="modal">Reject</button>
            </div>
        </div>
    </div>
</div>

    {{-- reject confirm and comment msg --}}
    <div class="modal fade" id="rejectModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col text-center">
                            <h4 class="font-weight-normal">Are you sure you want to Reject this Record?</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-center">
                            <textarea class="form-control form-control-sm" id="rejectcomment"
                                name="rejectcomment"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" name="reject_ok_button" id="reject_ok_button"
                        class="btn btn-danger px-3 btn-sm">OK</button>
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
        var approvel01 = {{$approvel01permission}};
        var approvel02 = {{$approvel02permission}};
        var approvel03 = {{$approvel03permission}};

        var listcheck = {{$listpermission}};
        var editcheck = {{$editpermission}};
        var statuscheck = {{$statuspermission}};
        var deletecheck = {{$deletepermission}};


        $("#companylink").addClass('navbtnactive');
   $('#corporate_link').addClass('active');

        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: scripturl + '/vatlist.php',

                type: "POST", // you can use GET
                data: {},

            },
            dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            "buttons": [{
                    extend: 'csv',
                    className: 'btn btn-success btn-sm',
                    title: 'Vat Details',
                    text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                },
                {
                    extend: 'print',
                    title: 'Vat Details',
                    className: 'btn btn-primary btn-sm',
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function (win) {
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    },
                },
            ],
            "order": [
                [0, "desc"]
            ],
            "columns": [{
                    "data": "id",
                    "className": 'text-dark'
                },
                {
                    "data": "fromdate",
                    "className": 'text-dark'
                },
                {
                    "data": "vat",
                    "className": 'text-dark'
                },
                {
                    "data": "tax",
                    "className": 'text-dark'
                },
                {
                    "data": "nbt",
                    "className": 'text-dark'
                },
                {
                    "data": "sscl",
                    "className": 'text-dark'
                },
                {
                    data: 'approve_status',
                    name: 'approve_status',
                    render: function (data, type, row) {
                        if (data == 0) {
                            return '<i style="color:red" class="fas fa-times"></i>&nbsp;&nbsp Pending';
                        } else if (data == 1) {
                            return '<i style="color:green" class="fas fa-check"></i>&nbsp;&nbsp Approved';
                        } else {
                            return '<i style="color:red" class="fas fa-ban"></i>&nbsp;&nbsp Reject';
                        }
                    }
                },

                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {

                        var approvelevel = '';
                        var requesttype = '';
                        var button = '';

                        if (approvel01) {
                            if (full['approve_01'] == 0) {
                                button += ' <button name="appL1" id="' + full['id'] +
                                    '" class="appL1 btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                        if (approvel02) {
                            if (full['approve_01'] == 1 && full['approve_02'] == 0) {
                                button += ' <button name="appL2" id="' + full['id'] +
                                    '" class="appL2 btn btn-outline-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                        if (approvel03) {
                            if (full['approve_02'] == 1 && full['approve_03'] == 0) {
                                button += ' <button name="appL3" id="' + full['id'] +
                                    '" class="appL3 btn btn-outline-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }

                        if (editcheck) {
                            if (full['approve_status'] == 0) {
                                button += ' <button name="edit" id="' + full['id'] +
                                    '" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                            }
                        }
                        if (statuscheck) {
                            if (full['status'] == 1) {
                                button += ' <a href="vatstatus/' + full['id'] +
                                    '/2 " onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            } else {
                                button += '&nbsp;<a href="vatstatus/' + full['id'] +
                                    '/1 "  onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        if (deletecheck) {
                            if (full['approve_status'] == 0) {
                                button += ' <button name="delete" id="' + full['id'] +
                                    '" issue_id="' + full['issue_id'] +
                                    '" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                            }
                        }

                        return button;
                    }
                }
            ],
            drawCallback: function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('#create_record').click(function () {
            $('.modal-title').text('Add New Vat');
            $('#action_button').html('Add');
            $('#action').val('Add');
            $('#form_result').html('');
            $('#formTitle')[0].reset();

            $('#formModal').modal('show');
        });

        $('#formTitle').on('submit', function (event) {
            event.preventDefault();
            var action_url = '';

            if ($('#action').val() == 'Add') {
                action_url = "{{ route('vatinsert') }}";
            }
            if ($('#action').val() == 'Edit') {
                action_url = "{{ route('vatupdate') }}";
            }

            $.ajax({
                url: action_url,
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function (data) { //alert(data);
                    var html = '';
                    if (data.errors) {
                        html = '<div class="alert alert-danger">';
                        for (var count = 0; count < data.errors.length; count++) {
                            html += '<p>' + data.errors[count] + '</p>';
                        }
                        html += '</div>';
                    }
                    if (data.success) {
                        html = '<div class="alert alert-success">' + data.success +
                            '</div>';
                        $('#formTitle')[0].reset();
                        //$('#titletable').DataTable().ajax.reload();
                        location.reload()
                    }
                    $('#form_result').html(html);
                }
            });
        });

        // edit function
        $(document).on('click', '.edit', function () {
            var id = $(this).attr('id');

            $('#form_result').html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("vatedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#fromdate').val(data.result.fromdate);
                    $('#vat').val(data.result.vat);
                    $('#tax').val(data.result.tax);
                    $('#nbt').val(data.result.nbt);
                    $('#sscl').val(data.result.sscl);

                    $('#hidden_id').val(id);
                    $('.modal-title').text('Edit Vat');
                    $('#action_button').html('Edit');
                    $('#action').val('Edit');
                    $('#formModal').modal('show');
                }
            })
        });

        var user_id;

        $(document).on('click', '.delete', function () {
            user_id = $(this).attr('id');
            $('#confirmModal').modal('show');
        });

        $('#ok_button').click(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("vatdelete") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: user_id
                },
                beforeSend: function () {
                    $('#ok_button').text('Deleting...');
                },
                success: function (data) { //alert(data);
                    setTimeout(function () {
                        $('#confirmModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        // alert('Data Deleted');
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
                url: '{!! route("vatedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_fromdate').val(data.result.fromdate);
                    $('#app_vat').val(data.result.vat);
                    $('#app_tax').val(data.result.tax);
                    $('#app_nbt').val(data.result.nbt);
                    $('#app_sscl').val(data.result.sscl);

                    $('#app_hidden_id').val(id_approve);
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
                url: '{!! route("vatedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_fromdate').val(data.result.fromdate);
                    $('#app_vat').val(data.result.vat);
                    $('#app_tax').val(data.result.tax);
                    $('#app_nbt').val(data.result.nbt);
                    $('#app_sscl').val(data.result.sscl);

                    $('#app_hidden_id').val(id_approve);
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
                url: '{!! route("vatedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_fromdate').val(data.result.fromdate);
                    $('#app_vat').val(data.result.vat);
                    $('#app_tax').val(data.result.tax);
                    $('#app_nbt').val(data.result.nbt);
                    $('#app_sscl').val(data.result.sscl);

                    $('#app_hidden_id').val(id_approve);
                    $('#app_level').val('3');
                    $('#approveconfirmModal').modal('show');

                }
            })


        });



        $('#approve_button').click(function () {
            var id_hidden = $('#app_hidden_id').val();
            var applevel = $('#app_level').val();
            var fromdate = $('#app_fromdate').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("vatapprove") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_hidden,
                    applevel: applevel,
                    fromdate:fromdate
                },
                success: function (data) { //alert(data);
                    setTimeout(function () {
                        $('#approveconfirmModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        // alert('Data Approved');
                    }, 2000);
                    location.reload()
                }
            })
        });

        $(document).on('click', '#reject_button', function () {
            $('#rejectModal').modal('show');
        });

        $('#reject_ok_button').click(function () {
            var reject_id = $('#app_hidden_id').val();
            var reject_comment = $('#rejectcomment').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("vatreject") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: reject_id,
                    comment: reject_comment,
                },
                success: function (data) { //alert(data);
                    setTimeout(function () {
                        $('#approveconfirmModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        // alert('Data Rejected');
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

@endsection