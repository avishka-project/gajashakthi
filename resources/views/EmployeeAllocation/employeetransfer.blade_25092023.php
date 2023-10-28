@extends('layouts.app')
@section('content')
    <main>
        <div class="page-header page-header-light bg-white shadow">
            <div class="container-fluid">
                <div class="page-header-content py-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-user-friends"></i></div>
                        <span>Security Guards Transfer</span>
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
                                id="create_record"><i class="fas fa-plus mr-2"></i>New Transfer</button>
                        </div>
                        <div class="col-12">
                            <hr class="border-dark">
                        </div>
                        <div class="col-12">
    
                            <table class="table table-striped table-bordered table-sm small" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Transfer Sub Region</th>
                                        <th>Employee Count</th>
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
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">New Security Guards Transfer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <span id="form_result"></span>
                    <form method="post" id="formTitle" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-4">
                                <label class="small font-weight-bold text-dark">Transfer To *</label>
                                <select name="subregion_to" id="subregion_to" class="form-control form-control-sm"
                                    required>
                                    <option value="">Select Sub Region</option>
                                    @foreach($subregion as $subregions)
                                    <option value="{{$subregions->id}}">{{$subregions->subregion}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4" id="fromdrop">
                                <label class="small font-weight-bold text-dark">Transfer From *</label>
                                <select name="subregion_from" id="subregion_from" class="form-control form-control-sm"
                                    required>
                                    <option value="">Select Sub Region</option>
                                    @foreach($subregion as $subregions)
                                    <option value="{{$subregions->id}}">{{$subregions->subregion}}</option>
                                    @endforeach
                                </select>

                            </div>

                        </div>
                        <button id="resetButton" class="d-none">Reset Form</button>
                        <br>
                    </form>

                    <div class="col-12">
                        <table class="table table-striped table-bordered table-sm small" id="tableorder" style="width:100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th>Service No</th>
                                    <th>Employee Name</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                        <br>
                        <table class="table table-striped table-bordered table-sm small" id="tblcopylist" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Service No</th>
                                    <th>Employee Name</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="transferlist"></tbody>
                        </table>
                        <div class="form-group mt-2">
                            <button type="button" name="btncreateorder" id="btncreateorder"
                                class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                    class="fas fa-plus"></i>&nbsp;Create Transfer</button>
                            <input type="hidden" name="hidden_id" id="hidden_id">
                            <input type="hidden" name="action" id="action" value="Add">

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

        <div class="modal fade" id="confirmModal2" data-backdrop="static" data-keyboard="false" tabindex="-1"
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
                        <button type="button" name="ok_button2" id="ok_button2" class="btn btn-danger px-3 btn-sm">OK</button>
                        <button type="button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-xl">
                <div class="modal-content">
                    <div class="modal-header p-2">
                        <h5 class="modal-title" id="staticBackdropLabel">Approve Security Guards Transfer Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4">
                                <label class="small font-weight-bold text-dark">Transfer To *</label>
                                <select name="app_subregion_to" id="app_subregion_to" class="form-control form-control-sm"
                                    readonly>
                                    <option value="">Select Sub Region</option>
                                    @foreach($subregion as $subregions)
                                    <option value="{{$subregions->id}}">{{$subregions->subregion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <br>
                            <table class="table table-striped table-bordered table-sm small" id="app_tblcopylist" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Service No</th>
                                        <th>Employee Name</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                    </tr>
                                </thead>
                                <tbody id="app_transferlist"></tbody>
                            </table>
                        </div>
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <input type="hidden" name="app_level" id="app_level" value="1" />
                    </div>
                
                    <div class="modal-footer p-2">
                        <button type="button" name="approve_button" id="approve_button"
                            class="btn btn-warning px-3 btn-sm">Approve</button>
                        <button type="button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

    </main>

@endsection


@section('script')

    <script>
        $(document).ready(function(){
            $('#empmanagementlist').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#collapseemployee').addClass('show');
            $('#security_staff_main_nav_link').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#security_staff_collapse').addClass('show');
            $('#empallocationlist').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#empallocationlistdrop').addClass('show');
            $('#empallocation_link').addClass('active');

          
            $('#create_record').click(function () {
                $('#formModal').modal('show');
                $('#subregion_to').val('');
                $('#hidden_id').val('');
                $('#tableorder tbody').empty();
                $('#tblcopylist tbody').empty();
                $('#tableorder ').show();
                $('#tblcopylist ').show();
                $('#fromdrop ').show();

            });


        $("#stafflist").select2();

        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{!! route('emptransferlist') !!}",
            },
            columns: [{
                    data: 'id',
                    name: 'transid'
                }, 
                {
                    data: 'transferregion',
                    name: 'transferregion'
                }, 
                {
                    data: 'details_count',
                    name: 'details_count'
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
                [0, "desc"]
            ]
        });


             var dtdtable;   // staffFilter in subregionId
        $('#subregion_from').change(function () {
            var subregion_from = $(this).val();
            var tosubregion = $("#subregion_to option:selected").text();
            var fromsubregion = $("#subregion_from option:selected").text();
            var subregion_to = $('#subregion_to').val();

            if (tosubregion !== '' && fromsubregion !== '') {

                dtdtable =  $('#tableorder').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{!! route('transferemployeedetails') !!}",
                        type: "POST", 
                        dataType: "json",
                        data: {
                            _token: '{{ csrf_token() }}',
                            subregion_from: subregion_from,
                            subregion_to: subregion_to,
                            tosubregion: tosubregion,
                            fromsubregion: fromsubregion
                        },
                    },
                    columns: [
                        {
                        data: null,
                        orderable: false, 
                        render: function (data, type, row) {
                            return '<input type="checkbox" class="row-select-checkbox selectCheck removeIt">';
                        }
                        },
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'service_no',
                            name: 'service_no'
                        },
                        {
                            data: 'emp_name_with_initial',
                            name: 'emp_name_with_initial'
                        },
                        {
                            data: 'fromsubregion',
                            name: 'fromsubregion'
                        },
                        {
                            data: 'tosubregion', 
                            name: 'tosubregion'
                        },
                        { 
                            data: 'from_date', 
                            name: 'from_date',
                            render: function (data, type, row) {
                                return '<input type="date" class="form-control form-control-sm" name="fromdate" id="fromdate">';
                            }
                        },
                        {
                            data: 'to_date', 
                            name: 'to_date',
                            render: function (data, type, row) {
                                return '<input type="date" class="form-control form-control-sm" name="todate" id="todate">';
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            render: function (data, type, row) {
                                return '<div style="text-align: right;">' + data + '</div>';
                            }
                        }
                    ],
                    "bDestroy": true,
                    "order": [
                        [1, "asc"]
                    ],
                    lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
                });
            }
        });

        var selectedemployeeArray = [];

        $('#tableorder').on('click', '.add-row', function () {

        var data = dtdtable.row($(this).closest('tr')).data(); 
        var empid = data.id; 
        var empname = data.emp_name_with_initial; 
        var serviceNo = data.service_no; 
        var subreg_from = data.fromsubregion; 
        var subreg_to = data.tosubregion; 
        
        var fromid =  $("#subregion_from").val();

        var fromDateValue = $(this).closest('tr').find('input[name="fromdate"]').val();
        var toDateValue = $(this).closest('tr').find('input[name="todate"]').val();


        $('#tblcopylist > tbody:last').append('<tr class="pointer"><td>' + empid +'</td>><td>' + serviceNo +
        '</td><td>' + empname +'</td><td>' +subreg_from +'</td><td>' + subreg_to + 
        '</td><td>' + fromDateValue + '</td><td>' + toDateValue + '</td><td class="d-none">' + fromid + '</td><td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td></tr>');


    
        // var selectedData = {
        //     serviceNo: serviceNo,
        //     empid: empid,
        //     fromid: fromid,
        //     fromDate: fromDateValue,
        //     toDate: toDateValue
        // };

        // selectedemployeeArray.push(selectedData);
        // console.log(selectedemployeeArray);

        });


        $('#btncreateorder').click(function () {
                $('#btncreateorder').prop('disabled', true).html(
                    '<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Transfer');

                var tbody = $("#tblcopylist tbody");

                if (tbody.children().length > 0) {
                    var jsonObj = [];
                    $("#tblcopylist tbody tr").each(function () {
                        var item = {};
                        $(this).find('td').each(function (col_idx) {
                            item["col_" + (col_idx + 1)] = $(this).text();
                        });
                        jsonObj.push(item);
                    });

                  
                    var subregionto = $('#subregion_to').val();
                    var hidden_id = $('#hidden_id').val();

                    var action_url = '';

                if ($('#action').val() == 'Add') {
                    action_url = "{{ route('emptransferinsert') }}";
                }
                if ($('#action').val() == 'Edit') {
                    action_url = "{{ route('emptransferupdate') }}";
                }

                    
                    $.ajax({
                        method: "POST",
                        dataType: "json",
                        data: {
                            _token: '{{ csrf_token() }}',
                            tableData: jsonObj,
                            subregionto: subregionto,
                            hidden_id: hidden_id
                        },
                        url: action_url,
                        success: function (result) {
                            if (result.status == 1) {
                                location.reload();
                                $('#formModal').modal('hide');
                            }
                            action(result.action);
                        }
                    });
                }
        });

        $(document).on('click', '.edit', function () {
            var id = $(this).attr('id');
            $('#hidden_id').val(id);

            $('#form_result').html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })


            $.ajax({
                url: '{!! route("emptransferedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {

                    $('#subregion_to').val(data.result.mainData.subregion_id_to);
    
                    $('#transferlist').html(data.result.detaildata);

                    $('#hidden_id').val(id);
                    $('#tableorder').hide();
                    $('#fromdrop').hide();
                    $('#action').val('Edit');
                    $('.modal-title').text('Edit Security Guards Transfer');
                    $('#btncreateorder').html('Update');
                    $('#formModal').modal('show');


                }
            })
        });

        $(document).on('click', '.btnDeletelist', function () {
            rowToDelete = $(this).closest('tr');
            rowid = $(this).attr('id');
            $('#confirmModal').modal('show');

        });

        $('#ok_button').click(function () {
            rowToDelete.remove();
            $('#form_result').html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("transferdetailedelete") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: rowid,
                },
                beforeSend: function () {
                    $('#ok_button').text('Deleting...');
                },
                success: function (data) {
                    setTimeout(function () {
                        $('#confirmModal').modal('hide');
                    }, 2000);
                }
            })
        });


        
        $(document).on('click', '.delete', function () {
                user_id = $(this).attr('id');
                $('#confirmModal2').modal('show');
            });
          
            $('#ok_button2').click(function () {
                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                $.ajax({
                    url: '{!! route("emptransferdelete") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: user_id },
                    beforeSend: function () {
                        $('#ok_button2').text('Deleting...');
                    },
                    success: function (data) {//alert(data);
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
                url: '{!! route("transferdetailapprove") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_fromdate').val(data.result.mainData.from_date);
                    $('#app_todate').val(data.result.mainData.to_date);
                    $('#app_subregion_to').val(data.result.mainData.subregion_id_to);
    
                    $('#app_transferlist').html(data.result.detaildata);

                    $('#approve_button').show();
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
                url: '{!! route("transferdetailapprove") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_fromdate').val(data.result.mainData.from_date);
                    $('#app_todate').val(data.result.mainData.to_date);
                    $('#app_subregion_to').val(data.result.mainData.subregion_id_to);
    
                    $('#app_transferlist').html(data.result.detaildata);
                    $('#approve_button').show();
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
                url: '{!! route("transferdetailapprove") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_fromdate').val(data.result.mainData.from_date);
                    $('#app_todate').val(data.result.mainData.to_date);
                    $('#app_subregion_to').val(data.result.mainData.subregion_id_to);
    
                    $('#app_transferlist').html(data.result.detaildata);
                    $('#approve_button').show();
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
                url: '{!! route("transferapprove") !!}',
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
                        //alert('Data Approved');
                    }, 2000);
                    location.reload()
                }
            })
        });

         // approve level 02 
         $(document).on('click', '.view', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("transferdetailapprove") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_fromdate').val(data.result.mainData.from_date);
                    $('#app_todate').val(data.result.mainData.to_date);
                    $('#app_subregion_to').val(data.result.mainData.subregion_id_to);
    
                    $('#app_transferlist').html(data.result.detaildata);
                    $('#approveconfirmModal').modal('show');

                    $('#approve_button').hide();
                    $('#approveconfirmModal').modal('show');
                    $('.modal-title').text('View Employee Transfer');


                }
            })


        });

      

        });
       
    function productDelete(ctl) {
    	$(ctl).parents("tr").remove();
    }

        function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }
    </script>

    {{-- <script>
        $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var subregion_id = $('#subregion_from').val();
        $('#stafflist').select2({
            
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("transfergetsearchempinfo") !!}',
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term,
                        subregion_id: subregion_id
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.service_no + ' - ' + item.emp_name_with_initial +
                                    ' - ' + item.emp_national_id,
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
    </script> --}}

@endsection