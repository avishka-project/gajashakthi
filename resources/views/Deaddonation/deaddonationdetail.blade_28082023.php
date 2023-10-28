@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="page-header-content py-3">
            <h1 class="page-header-title">
                <div class="page-header-icon"><i class="fas fa-users"></i></div>
                <span>Dead Donation Detail</span>

            </h1>
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0 p-2">
                <div class="row">
                    <form style="width: 100%;" id="formFilter">
                        {{ csrf_field() }}
                        <div class="form-row" style="width: 100%;margin-left: 5px;">
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">Service NO*</label>
                                <select name="serviceno" id="serviceno" class="form-control form-control-sm" required
                                    onchange="getEmpName()">
                                    <option value="">Select Service No</option>
                                    @foreach($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->service_no}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-5">
                                <label class="small font-weight-bold text-dark">Emp Name*</label>
                                <input type="text" class="form-control form-control-sm" placeholder="" name="empname"
                                    id="empname" readonly>
                            </div>
                            <div class="col-1 d-flex flex-column justify-content-center">
                                <br>
                                <button type="submit" class="btn btn-outline-primary btn-sm" name="btn-filter"
                                    id="btn-filter">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

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


    <div class="modal fade" id="detailsModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="detailsmodal-title" id="staticBackdropLabel">Dead Donation Details</h5>
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
                                        <label class="small font-weight-bold text-dark">Relative*</label>
                                        <input type="text" class="form-control form-control-sm" placeholder=""
                                            name="relative" id="relative" readonly>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Date of Dead*</label>
                                        <input type="text" class="form-control form-control-sm" placeholder=""
                                            name="dateofdead" id="dateofdead" readonly>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Causes of Death*</label>
                                        <input type="text" class="form-control form-control-sm" placeholder=""
                                            name="causesofdeath" id="causesofdeath" readonly>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">First Allocation*</label>
                                        <input type="text" class="form-control form-control-sm" placeholder=""
                                            name="firstallocation" id="firstallocation" readonly>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Document Upload*</label>
                                        <input type="text" class="form-control form-control-sm" placeholder=""
                                            name="documentupload" id="documentupload" readonly>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Second Allocation*</label>
                                        <input type="text" class="form-control form-control-sm" placeholder=""
                                            name="secondallocation" id="secondallocation" readonly>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
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
        $('#deaddonationdetail_link').addClass('active');

        function load_dt(empid) {

            $('#dataTable').DataTable({
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000],
                    [10, 25, 50, 100, 500, 1000]
                ],
                dom: 'Blfrtip',
                buttons: [
                    'excelHtml5',
                    'pdfHtml5'
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{!! route('deaddonationdetaillist') !!}",
                    "data": {
                        emp_id: empid,
                        _token: "{{ csrf_token() }}"
                    },

                },
                columns: [{
                        data: 'id'
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

        }

        // $('#create_record').click(function () {
        //     $('.modal-title').text('Add Payment Details');
        //     $('#action_button').html('Add');
        //     $('#action').val('Add');
        //     $('#form_result').html('');
        //     $('#formTitle')[0].reset();

        //     $('#formModal').modal('show');
        // });



        // allocate function
        $(document).on('click', '.details', function () {
            var id = $(this).attr('id');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("getdeaddonationdetails") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#app_serviceno').val(data.result.service_no || 'NOT Assign');
                    $('#relative').val(data.result.emp_dep_relation || 'NOT Assign');
                    $('#dateofdead').val(data.result.dateofdead || 'NOT Assign');
                    $('#causesofdeath').val(data.result.causesofdead || 'NOT Assign');
                    $('#firstallocation').val(data.result.firstallocation || 'NOT Assign');
                    $('#documentupload').val(data.result.filename ? 'Document Upload' :
                        'Document not Uploaded yet');
                    $('#secondallocation').val(data.result.lastallocation || 'NOT Assign');

                    fieldcolor()
                }
            })

            $('#hidden_id').val(id);
            $('.detailsmodal-title').text('Dead Donation Details');

            $('#detailsModal').modal('show');

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
                url: '{!! route("deaddonationdetaildelete") !!}',
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


        $('#formFilter').on('submit', function (e) {
            e.preventDefault();
            let empid = $('#serviceno').val();

            load_dt(empid);
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

    function fieldcolor() {
        if ($('#firstallocation').val() === 'NOT Assign') {
            $('#firstallocation').css('border-color', 'red');
        } else {
            $('#firstallocation').css('border-color', ''); 
        }


        if ($('#secondallocation').val() === 'NOT Assign') {
            $('#secondallocation').css('border-color', 'red');
        } else {
            $('#secondallocation').css('border-color', '');
        }


        if ($('#documentupload').val() ===
            'Document not Uploaded yet') {
            $('#documentupload').css('border-color', 'red');
        } else {
            $('#documentupload').css('border-color', '');
        }
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
        $('#serviceno').select2({
            width: '100%' // Set the width to 100% of its parent container
        });
    });
</script>


</body>

@endsection