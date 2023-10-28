@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
        <div class="page-header-content py-3">
            <h1 class="page-header-title">
                <div class="page-header-icon"><i class="fas fa-users"></i></div>
                <span>Dead Donation</span>
            </h1>
        </div>
    </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0 p-2">
                <div class="row">
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record"
                            id="create_record"><i class="fas fa-plus mr-2"></i>Add Donation</button>
                    </div>
                    <div class="col-12">
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">
                        <div class="center-block fix-width scroll-inner">
                        <table class="table table-striped table-bordered table-sm small nowrap display" style="width: 100%" id="dataTable">
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
    </div>
    <!-- Modal Area Start -->
    <div class="modal fade" id="formModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Donation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <span id="form_result"></span>
                            <form method="post" id="formTitle" class="form-horizontal">
                                {{ csrf_field() }}
                                {{-- <div class="input-group mb-4" style="width: 35%">
                                    <input type="search" class="form-control form-control-sm" placeholder="Search By NIC Or Service NO" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                      <button class="btn btn-primary btn-sm" type="button" id="searchbtn" name="searchbtn"><i class="fas fa-search"></i></button>
                                    </div>
                                  </div> --}}

                                <div class="form-row mb-1">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Search Employee*</label>
                                        <select name="serviceno" id="serviceno" class="form-control form-control-sm"
                                            onchange="getEmpName();idgetinserch()">
                                            <option value="">Select Service No</option>

                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Emp Name*</label>
                                        <input type="text" class="form-control form-control-sm" placeholder=""
                                            name="empname" id="empname" readonly required>
                                    </div>
                                    <input type="hidden" class="form-control form-control-sm" placeholder=""
                                        name="editempid" id="editempid" readonly>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Select Relatives*</label>
                                        <select name="relatives" id="relatives" class="form-control form-control-sm"
                                            required>
                                            {{-- <option value="">Select Type</option>
                                            <option value="1">GrandFather</option>
                                            <option style="color: red" disabled value="2">GrandMather <div>&#10003;
                                                </div> --}}
                                            </option>
                                        </select>
                                        <input type="hidden" class="form-control form-control-sm" placeholder=""
                                            name="old_relative" id="old_relative" readonly>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="date" id="date" value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                </div>

                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Causes of Death*</label>
                                        <textarea id="reason" name="reason" class="form-control form-control-sm"
                                            required></textarea>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" name="action_button" id="action_button"
                                        class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                            class="fas fa-plus"></i>&nbsp;Add</button>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Approve Donation Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <span id="form_result"></span>
                            <form class="form-horizontal">
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Service NO*</label>
                                        <input type="text" class="form-control form-control-sm" placeholder=""
                                            name="app_serviceno" id="app_serviceno" readonly>
                                        <label class="small font-weight-bold text-dark">Emp Name*</label>
                                        <input type="text" class="form-control form-control-sm" placeholder=""
                                            name="app_empname" id="app_empname" readonly>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Relatives*</label>
                                        <select name="app_relatives" id="app_relatives"
                                            class="form-control form-control-sm" readonly>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="app_date" id="app_date" value="<?php echo date('Y-m-d') ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Causes of Death*</label>
                                        <textarea id="app_reason" name="app_reason" class="form-control form-control-sm"
                                            readonly></textarea>
                                    </div>
                                </div>

                                <input type="hidden" name="hidden_id" id="hidden_id" />
                                <input type="hidden" name="app_level" id="app_level" value="1" />

                            </form>
                        </div>
                    </div>
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
        $('#collapseemployee').addClass('show');
        $('#employee_request_collapse').addClass('show');
        $('#deaddonationlistdrop').addClass('show');
        $('#deaddonation_link').addClass('active');

        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{!! route('deaddonationlist') !!}",

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

        $('#create_record').click(function () {
            $('.modal-title').text('Add New Dead Donation');
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
                action_url = "{{ route('deaddonationinsert') }}";
            }
            if ($('#action').val() == 'Edit') {
                action_url = "{{ route('deaddonationupdate') }}";
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
                url: '{!! route("deaddonationedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    relativeEdit(data.result.employee_id, data.result.relative_id);
                    $('#editempid').val(data.result.employee_id);
                    getEmpNameforEdit(data.result.employee_id)
                    $('#relatives').val(data.result.relative_id);
                    $('#old_relative').val(data.result.relative_id);
                    $('#date').val(data.result.dateofdead);
                    $('#reason').val(data.result.causesofdead);
                    getEmpName()

                    // var valueToCheck = data.result.pay_by;

                    // if (valueToCheck == 1 ) {
                    //     $('#company').prop('checked', true);
                    // } else {
                    //      $('#branch').prop('checked', true);
                    // }

                    $('#hidden_id').val(id);
                    $('.modal-title').text('Edit Dead Donation Request');
                    $('#action_button').html('Edit');
                    $('#action').val('Edit');
                    $('#formModal').modal('show');
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
                url: '{!! route("deaddonationdelete") !!}',
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
                url: '{!! route("deaddonationedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_relatives').val(data.result.relative_id);
                    $('#app_date').val(data.result.dateofdead);
                    $('#app_reason').val(data.result.causesofdead);
                    getEmpNameforApprove(data.result.employee_id);
                    relativeApprovel(data.result.employee_id, data.result.relative_id);
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
                url: '{!! route("deaddonationedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_relatives').val(data.result.relative_id);
                    $('#app_date').val(data.result.dateofdead);
                    $('#app_reason').val(data.result.causesofdead);
                    getEmpNameforApprove(data.result.employee_id);
                    relativeApprovel(data.result.employee_id, data.result.relative_id);
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
                url: '{!! route("deaddonationedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_relatives').val(data.result.relative_id);
                    $('#app_date').val(data.result.dateofdead);
                    $('#app_reason').val(data.result.causesofdead);
                    getEmpNameforApprove(data.result.employee_id);
                    relativeApprovel(data.result.employee_id, data.result.relative_id);
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
                url: '{!! route("deaddonationapprove") !!}',
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

    function getEmpName() {
        var empid = $('#serviceno').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.ajax({
            url: '{!! route("deaddonationgetempname") !!}',
            type: 'POST',
            dataType: "json",
            data: {
                id: empid
            },
            success: function (data) {
                $('#empname').val(data.result.emp_name_with_initial);
                $('#app_empname').val(data.result.emp_name_with_initial);
            }
        })
    }

    function getEmpNameforApprove(empid) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.ajax({
            url: '{!! route("deaddonationgetempname") !!}',
            type: 'POST',
            dataType: "json",
            data: {
                id: empid
            },
            success: function (data) {
                $('#app_empname').val(data.result.emp_name_with_initial);
                $('#app_serviceno').val(data.result.service_no);

            }
        })
    }

    function getEmpNameforEdit(empid) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.ajax({
            url: '{!! route("deaddonationgetempname") !!}',
            type: 'POST',
            dataType: "json",
            data: {
                id: empid
            },
            success: function (data) {
                $('#empname').val(data.result.emp_name_with_initial);
                // $('#app_empname').val(data.result.emp_name_with_initial);
            }
        })
    }

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }
</script>
<script>
    // Initialize Select2 on the select element
    $(document).ready(function () {
        // Set up default headers for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#serviceno').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("getemployeeinselect2") !!}',
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.service_no + ' - ' + item.emp_name_with_initial,
                                id: item.id
                            };
                        })
                    };
                }
            }
        });
    });

    function idgetinserch() {
        var editempid = $('#serviceno').val();
        $('#editempid').val(editempid);
    };
</script>
<script>
    $(document).ready(function () {
        //Get relative
        $('#serviceno').change(function () {
            var empId = $(this).val();
            if (empId !== '') {
                $.ajax({
                    url: '{!! route("getrelatives", ["empId" => "id_emp"]) !!}'.replace('id_emp',empId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#relatives').empty().append(
                            '<option value="">Select Relatives</option>');
                        $.each(data, function (index, relative) {
                            var optionText = relative.emp_dep_name + '  -  ' +
                                relative.emp_dep_relation;
                            var optionValue = relative.id;
                            var optionHtml = '<option value="' + optionValue +
                                '">' + optionText + '</option>';

                            if (relative.life_status === "dead") {
                                optionHtml = '<option value="' + optionValue +
                                    '" disabled style="color: red;">' + optionText +
                                    ' (Dead)</option>';
                            }

                            $('#relatives').append(optionHtml);
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#relatives').empty().append('<option value="">Select Relatives</option>');
            }
        });
    });


    //get relative in edit
    function relativeEdit(empId, relativeId) {
        if (empId !== '') {
            $.ajax({
                url: '{!! route("getrelatives", ["empId" => "id_emp"]) !!}'.replace('id_emp',empId),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#relatives').empty().append('<option value="">Select Relatives</option>');
                    $.each(data, function (index, relative) {
                        $('#relatives').append('<option value="' + relative.id + '">' + relative
                            .emp_dep_name + ' - ' + relative.emp_dep_relation + '</option>');
                    });

                    // Set the selected value after populating the dropdown
                    $('#relatives').val(relativeId);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $('#relatives').empty().append('<option value="">Select Relatives</option>');
        }
    };

    //get app_relative in edit
    function relativeApprovel(empId, relativeId) {
        if (empId !== '') {
            $.ajax({
                url: '{!! route("getrelatives", ["empId" => "id_emp"]) !!}'.replace('id_emp',empId),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#app_relatives').empty().append('<option value="">Select Relatives</option>');
                    $.each(data, function (index, relative) {
                        $('#app_relatives').append('<option value="' + relative.id + '">' + relative
                            .emp_dep_name + ' - ' + relative.emp_dep_relation + '</option>');
                    });

                    // Set the selected value after populating the dropdown
                    $('#app_relatives').val(relativeId);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $('#app_relatives').empty().append('<option value="">Select Relatives</option>');
        }
    };
</script>
</body>

@endsection