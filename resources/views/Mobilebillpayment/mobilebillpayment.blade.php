@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
        <div class="page-header-content py-3">
            <h1 class="page-header-title">
                <div class="page-header-icon"><i class="fas fa-mobile-alt"></i></div>
                <span>Mobile Bill Payment</span>
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
                        @if(in_array('Mobilebillpayment-create',$userPermissions))
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record"
                            id="create_record"><i class="fas fa-plus mr-2"></i>Add Bill Payment</button>
                            @endif
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
                                    <th>Month</th>
                                    <th>Cost</th>
                                    <th>Remark</th>

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
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Mobile Bill Payment</h5>
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
                                        <label class="small font-weight-bold text-dark">Employees*</label>
                                        <select name="employee" id="employee" class="form-control form-control-sm" required>
                                            <option value="">Select Employees</option>
                                        </select>          
                                    </div>
                                </div>
                                
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Month*</label>
                                            <input type="month" id="month" name="month" class="form-control form-control-sm"
                                                required>
                                        </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Cost</label>
                                        <input type="number" name="cost" id="cost"
                                            class="form-control form-control-sm" required />
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Remark</label>
                                        <input type="price" name="remark" id="remark" class="form-control form-control-sm"
                                            required />
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
                    <h5 class="modal-title" id="staticBackdropLabel">Approve Mobile Bill Payment Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-row mb-1">
                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">Employee*</label>
                                <select name="app_employee" id="app_employee" class="form-control form-control-sm" required readonly>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->emp_first_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                            <div class="form-row mb-1">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Month*</label>
                                    <input type="month" id="app_month" name="app_month" class="form-control form-control-sm"
                                        required readonly>
                                </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">Cost</label>
                                <input type="number" name="app_cost" id="app_cost"
                                    class="form-control form-control-sm" required readonly>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">Remark</label>
                                <input type="price" name="app_remark" id="app_remark" class="form-control form-control-sm"
                                    required readonly>
                            </div>
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
        $('#collapseCorporation').addClass('show');
        $('#collapsimman').addClass('show');
        $('#mobilebillpayment_link').addClass('active');
        

        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{!! route('mobilebillpaymentlist') !!}",

            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'emp_first_name',
                    name: 'emp_first_name'
                },
                {
                    data: 'month',
                    name: 'month'
                },
                {
                    data: 'cost',
                    name: 'cost'
                },
                {
                    data: 'remark',
                    name: 'remark'
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
            $('.modal-title').text('Add New Mobile Bill Payment');
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
                action_url = "{{ route('mobilebillpaymentinsert') }}";
            }
            if ($('#action').val() == 'Edit') {
                action_url = "{{ route('mobilebillpaymentupdate') }}";
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
                url: '{!! route("mobilebillpaymentedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    // $('#cusname').val(data.result.name);
                    // $('#subregion_id').val(data.result.subregion_id);
                    $('#employee').val(data.result.emp_id);
                    $('#month').val(data.result.month);
                    $('#cost').val(data.result.cost);
                    $('#remark').val(data.result.remark);


                    // var valueToCheck = data.result.pay_by;

                    // if (valueToCheck == 1 ) {
                    //     $('#company').prop('checked', true);
                    // } else {
                    //      $('#branch').prop('checked', true);
                    // }

                    $('#hidden_id').val(id);
                    $('.modal-title').text('Edit Mobile Bill Payment');
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
                url: '{!! route("mobilebillpaymentdelete") !!}',
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
                url: '{!! route("mobilebillpaymentedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    // $('#app_cusname').val(data.result.name);
                    // $('#app_subregion_id').val(data.result.subregion_id);
                    $('#app_employee').val(data.result.emp_id);
                    $('#app_month').val(data.result.month);
                    $('#app_cost').val(data.result.cost);
                    $('#app_remark').val(data.result.remark);
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
                url: '{!! route("mobilebillpaymentedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_employee').val(data.result.emp_id);
                    $('#app_month').val(data.result.month);
                    $('#app_cost').val(data.result.cost);
                    $('#app_remark').val(data.result.remark);
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
                url: '{!! route("mobilebillpaymentedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_employee').val(data.result.emp_id);
                    $('#app_month').val(data.result.month);
                    $('#app_cost').val(data.result.cost);
                    $('#app_remark').val(data.result.remark);
                    $('#hidden_id').val(id_approve);
                    $('#app_level').val('3');
                    $('#approveconfirmModal').modal('show');

                }
            })


        });



        $('#approve_button').click(function () {
            var id_hidden = $('#hidden_id').val();
            var applevel = $('#app_level').val();

            var empid = $('#app_employee').val();
            var cost = $('#app_cost').val();
            var month = $('#app_month').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("mobilebillpaymentapprove") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_hidden,
                    applevel: applevel,
                    empid: empid,
                    cost: cost,
                    month: month,
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
    });

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }
</script>

<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#employee').select2({
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
</script>

@endsection