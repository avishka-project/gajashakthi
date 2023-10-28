@extends('layouts.app')
@section('content')
    <main>
        <div class="page-header page-header-light bg-white shadow">

                <div class="page-header-content py-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon" style="padding-left: 12px"><i class="fas fa-user-plus" ></i></div>
                        <span>Employee Allocation</span>
                    </h1>
                </div>
        </div>
        <br>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0 p-2">
                    <div class="row">
                        <div class="col-4">
                            <label class="small font-weight-bold text-dark">Sub Client*</label>
                            <select name="subcustomerlist" id="subcustomerlist" class="form-control form-control-sm"
                                required>
                                <option value="">Select Sub Customer</option>
                                @foreach($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->sub_name}}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="col-4">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Branch*</label>
                                <select name="customer" id="customer" class="form-control form-control-sm">
                                    <option value="">Select Customer Branch</option>
                                    
                                </select>
                            </div>
                               
                         
                        </div>
                        <div class="col-12">
                            <hr class="border-dark">
                        </div>
                        <div class="col-12">
                            <table class="table table-striped table-bordered table-sm small" id="dataTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Branch</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Shift</th>
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
 
        <br>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0 p-2">
                    <div class="col-12">
                        <h3>Allocated List</h3>
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">
                        <table class="table table-striped table-bordered table-sm small" id="dataTable2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Branch</th>
                                    <th>Date</th>
                                    <th>Shift</th>
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
        
        <!-- Modal Area End -->
        <div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Approve Employee Allocation Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-5">
                   <form>
                    <div class="form-row mb-1">
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Customer*</label>
                            <input type="text" id="app_customername" name="app_customername" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Sub Customer*</label>
                            <input type="text" id="app_subcustomer" name="app_subcustomer" class="form-control form-control-sm" readonly>
                            
                        </div>
                        
                    </div>
                    <div class="form-row mb-1">
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Branch*</label>
                            <input type="text" id="app_branch" name="app_branch" class="form-control form-control-sm" readonly>
                           
                        </div>
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Shift</label>
                            <input type="text" id="app_shift" name="app_shift" class="form-control form-control-sm" readonly>
                            
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Date*</label>
                            <input type="date" class="form-control form-control-sm" placeholder="" name="app_date" id="app_date"  readonly>
                        </div>
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Holiday</label>
                            <input type="text" id="app_hollyday" name="app_hollyday" class="form-control form-control-sm" readonly>
                            
                        </div>
                    </div><hr>
                    <table class="table table-striped table-bordered table-sm small" id="tableorder">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody id="app_requestdetaillist"></tbody>
                    </table>
                </div>
                    <div class="col-7">
                        <table class="table table-striped table-bordered table-sm small" id="allocationtbl">
                            <thead>
                                <tr>
                                    <th>Empolyee Name</th>
                                    <th>Job Title</th>
                                </tr>
                            </thead>
                            <tbody id="app_Detilslist"></tbody>
                        </table>
                    </div>
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <input type="hidden" name="app_level" id="app_level" value="1" />

                    </form>
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
    </main>

@endsection


@section('script')

    <script>
        $(document).ready(function(){
            $('#empallocationlist').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#empallocationlistdrop').addClass('show');
            $('#empallocation_link').addClass('active');

            $(document).on("change", "#customer", function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                $('#dataTable').DataTable({
                    "destroy": true,
                    "processing": true,
                    "serverSide": true,
                    ajax: {
                        url: "{!! route('requestlist') !!}",
                        type: "POST",
                        data: function (d) {
                            return $.extend({}, d, {
                                "cusid": $("#customer").val()
                            });
                        },
                    },
                    "columns": [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'namecustomer',
                            name: 'namecustomer'
                        },
                        {
                            data: 'namebranch',
                            name: 'namebranch'
                        },
                        {
                            data: 'fromdate',
                            name: 'fromdate'
                        },
                        {
                            data: 'todate',
                            name: 'todate'
                        },
                        {
                            data: 'nameshift',
                            name: 'nameshift'
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
                $('#dataTable2').DataTable({
                    "destroy": true,
                    "processing": true,
                    "serverSide": true,
                    ajax: {
                        url: "{!! route('alocationlist') !!}",
                        type: "POST",
                        data: function (d) {
                            return $.extend({}, d, {
                                "cusid": $("#customer").val()
                            });
                        },
                    },
                    "columns": [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'customername',
                            name: 'customername'
                        },
                        {
                            data: 'branchname',
                            name: 'branchname'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'shiftname',
                            name: 'shiftname'
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
            });
            $(document).on('click', '.allocate', function () {
                var id = $(this).attr('id');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                $('#form_result').html('');
                $.ajax({
                    url: '{!! route("requestdetails") !!}',
                    dataType: "json",
                     type: "POST",
                       data: {id: id  },
                    success: function (data) {


                        $('#customername').val(data.result.name);
                        $('#cusid').val(data.result.customer_id);
                         $('#branch').val(data.result.branch_name);
                        $('#branchid').val(data.result.	customerbranch_id);
                        $('#subcustomer').val(data.result.sub_name);
                        $('#subcustomerid').val(data.result.subcustomer_id);
                         $('#hollyday_id').val(data.result.holiday_id);
                          $('#shift').val(data.result.shift_name);
                        $('#shift_id').val(data.result.shift_id);
                        $('#date').val(data.result.date);
                         $('#year').val(data.result.year);
                        $('#requestid').val(id);
                        $('.modal-title').text('Allocate Employees');
                        $('#action_button').html('Add');
                        $('#action').val('Add');
                        $('#form_result').html('');
                         $('#formModal').modal('show');
                    }
                })
              
            });

           

            $("#formsubmit").click(function () {
                if (!$("#emplist")[0].checkValidity()) {
                    // If the form is invalid, submit it. The form won't actually submit;
                    // this will just cause the browser to display the native HTML5 error messages.
                    $("#submitBtn").click();
                } else {
                    var employeeID = $('#employee').val();
                    var titleID = $('#title').val();
                    var employee = $("#employee option:selected").text();
                    var title = $("#title option:selected").text();

                    $('#allocationtbl > tbody:last').append('<tr class="pointer"><td>' + employee + '</td><td>' + title + '</td><td class="d-none">'
                        + employeeID + '</td><td class="d-none">' + titleID + '</td><td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td></tr>');
                    $('#title').val('');
                    $('#employee').val('');
                }
            });
   

    

            $('#btncreateorder').click(function() {
                var action_url = '';

                if ($('#action').val() == 'Add') {
                    action_url = "{{ route('alloctioninsert') }}";
                }
                if ($('#action').val() == 'Edit') {
                    action_url = "{{ route('alloctionupdate') }}";
                }
                $('#btncreateorder').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Allocation');

                var tbody = $("#allocationtbl tbody");

                if (tbody.children().length > 0) {
                    var jsonObj = [];
                    $("#allocationtbl tbody tr").each(function() {
                        var item = {};
                        $(this).find('td').each(function(col_idx) {
                            item["col_" + (col_idx + 1)] = $(this).text();
                        });
                        jsonObj.push(item);
                    });

                    var cusid = $('#cusid').val();
                    var requestid = $('#requestid').val();
                    var subcusidid = $('#subcustomerid').val();
                    var branchid = $('#branchid').val();
                    var hollyday_id = $('#hollyday_id').val();
                    var shift_id = $('#shift_id').val();
                    var date = $('#date').val();
                    var year = $('#year').val();
                    var hidden_id = $('#hidden_id').val();

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })

                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: {
                            tableData: jsonObj,
                            recordID: requestid,
                            customer: cusid,
                            subcustomer: subcusidid,
                            branch: branchid,
                            shift: shift_id,
                            hollyday: hollyday_id,
                            date: date,
                            year: year,
                            hidden_id: hidden_id
                        },
                        url: action_url,
                        success: function(result) {
                            if (result.status == 1) {
                                $('#formModal').modal('hide');
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                            action(result.action);
                        }
                    });
                }
            });

// allocation edit
            $(document).on('click', '.edit', function () {
                var id = $(this).attr('id');
                $('#form_result').html('');
                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })

                $.ajax({
                    url: '{!! route("alloctionedit") !!}',
                    type: 'POST',
                    dataType: "json",
                    data: {id: id },
                    success: function (data) {

                        $('#customername').val(data.result.mainData.name);
                        $('#cusid').val(data.result.mainData.customer_id);
                        $('#subcustomer').val(data.result.mainData.sub_name);
                        $('#subcustomerid').val(data.result.mainData.subcustomer_id);
                        $('#branch').val(data.result.mainData.branch_name);
                        $('#branchid').val(data.result.mainData.customerbranch_id);
                        $('#hollyday_id').val(data.result.mainData.holiday);
                        $('#shift').val(data.result.mainData.shift_name);
                        $('#shift_id').val(data.result.mainData.shift_id);
                        $('#date').val(data.result.mainData.date);
                        $('#requestdetaillist').html(data.result.requestdata);
                        $('#Detilslist').html(data.result.detaildata);
                  
                        $('#previouslist').prop('disabled', true);
                         $('.modal-title').text('Edit Employees Allocation');
                         $('#btncreateorder').text('Update Allocation');
                        $('#hidden_id').val(id);
                        $('#action_button').html('Edit');
                        $('#action').val('Edit');
                        $('#formModal').modal('show');
                    }
                })
            });

            // allocation detail edit
          $(document).on('click', '.btnEditlist', function () {
                var id = $(this).attr('id');
                $('#form_result').html('');
                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })

                $.ajax({
                    url: '{!! route("alloctiondetailedit") !!}',
                    type: 'POST',
                    dataType: "json",
                    data: {id: id },
                    success: function (data) {                     
                        $('#employee').val(data.result.emp_id);
                        $('#title').val(data.result.assigndesignation_id);
                        $('#allocationid').val(data.result.allocation_id);
                        $('#allocationdeiailsid').val(data.result.id);  
                        $('#Btnupdatelist').show();
                        $('#formsubmit').hide();
                    }
                })
            });

             // allocation detail update list
          
              
              $(document).on("click", "#Btnupdatelist", function () {
                  var employeeID = $('#employee').val();
                  var titleID = $('#title').val();
                  var employee = $("#employee option:selected").text();
                  var title = $("#title option:selected").text();
                  var detailid = $('#allocationdeiailsid').val();
                  var allocatioid = $('#allocationid').val();

                  $("#allocationtbl> tbody").find('input[name="hiddenid"]').each(function () {
                      var hiddenid = $('#hiddenid').val();
                      if (hiddenid == detailid) {
                          $(this).parents("tr").remove();
                      }
                  });

                  $('#allocationtbl> tbody:last').append('<tr class="pointer"><td>' + employee + '</td><td>' + title + '</td><td class="d-none">' +
                      employeeID + '</td><td class="d-none">' + titleID + '</td><td class="d-none">' +
                      detailid + '</td><td class="d-none">' + allocatioid + '</td><td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td></tr>');


                  $('#title').val('');
                  $('#employee').val('');
                  $('#Btnupdatelist').hide();
                  $('#formsubmit').show();
              });

      



            // allocation detail item delete
            $(document).on("click", ".btnDeletelist", function () {

                var hidden =  $('#hidden_id').val(id);
            var r = confirm("Are you sure you want to remove this? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                                
                $.ajax({
                    type: 'POST',
                    dataType: "json",
                    data: {id: id },
                    url: '{!! route("alloctiondetailedelete") !!}',
                    success: function (result) { 
                        location.reload()
                    }
                });
            }
            });



                // allocation Delete 
            var customerrequest_id;
            $(document).on('click', '.delete', function () {
                customerrequest_id = $(this).attr('id');
                $('#confirmModal').modal('show');
                
            });

            $('#ok_button').click(function () {

                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                    $.ajax({
                        url: '{!! route("alloctiondelete") !!}',
                        type: 'POST',
                        data: {id: customerrequest_id  },
                        success: function(res) {
                            setTimeout(function () {
                            $('#confirmModal').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();
                            alert('Data Deleted');
                        }, 2000);
                        location.reload()
                        },
                        error: function(res) {
                            // alert(data);
                        }
                    });
                
                
             
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
                    url: '{!! route("alloctionedit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_approve },
                    success: function (data) {
                        $('#app_customername').val(data.result.mainData.name);
                        $('#app_subcustomer').val(data.result.mainData.sub_name);
                        $('#app_branch').val(data.result.mainData.branch_name);
                        $('#app_hollyday').val(data.result.mainData.holiday);
                        $('#app_shift').val(data.result.mainData.shift_name);
                        $('#app_date').val(data.result.mainData.date);
                        $('#app_requestdetaillist').html(data.result.requestdata);
                        $('#app_Detilslist').html(data.result.detaildata);
                        $('.btnEditlist').hide();
                        $('.btnDeletelist').hide();
                      
                        

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
                    url: '{!! route("alloctionedit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_approve },
                    success: function (data) {
                        $('#app_customername').val(data.result.mainData.name);
                        $('#app_subcustomer').val(data.result.mainData.sub_name);
                        $('#app_branch').val(data.result.mainData.branch_name);
                        $('#app_hollyday').val(data.result.mainData.holiday);
                        $('#app_shift').val(data.result.mainData.shift_name);
                        $('#app_date').val(data.result.mainData.date);
                        $('#app_requestdetaillist').html(data.result.requestdata);
                        $('#app_Detilslist').html(data.result.detaildata);
                        $('.btnEditlist').hide();
                        $('.btnDeletelist').hide();

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
                    url: '{!! route("alloctionedit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_approve },
                    success: function (data) {
                        $('#app_customername').val(data.result.mainData.name);
                        $('#app_subcustomer').val(data.result.mainData.sub_name);
                        $('#app_branch').val(data.result.mainData.branch_name);
                        $('#app_hollyday').val(data.result.mainData.holiday);
                        $('#app_shift').val(data.result.mainData.shift_name);
                        $('#app_date').val(data.result.mainData.date);
                        $('#app_requestdetaillist').html(data.result.requestdata);
                        $('#app_Detilslist').html(data.result.detaildata);
                        $('.btnEditlist').hide();
                        $('.btnDeletelist').hide();

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
                    url: '{!! route("alloctionapprove") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_hidden,
                            applevel: applevel},
                    success: function (data) {//alert(data);
                        setTimeout(function () {
                            $('#approveconfirmModal').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();
                            
                        }, 2000);
                        location.reload()
                    }
                })
            });

            $('#previouslist').click(function () {
                    var cusid = $('#cusid').val();
                    var subcusidid = $('#subcustomerid').val();
                    var branchid = $('#branchid').val();
                    var shift_id = $('#shift_id').val();
                    var date = $('#date').val();

                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                $.ajax({
                    url: '{!! route("pervoiuslist") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: { 
                            customer: cusid,
                            subcustomer: subcusidid,
                            branch: branchid,
                            shift: shift_id,
                            date: date },
                    success: function (data) {
                 
                        $('#Detilslist').html(data.result);
                    }
                })
            });

            $('#subcustomerlist').change(function () {
            var subcustomerId = $(this).val();
            if (subcustomerId !== '') {
                $.ajax({
                    url: '{!! route("getbranchallocatoion", ["subcustomerId" => "id_subcustomer"]) !!}'.replace('id_subcustomer', subcustomerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#customer').empty().append(
                        '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#customer').append('<option value="' + branch.id + '">' +
                                branch.branch_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#customer').empty().append('<option value="">Select Branch</option>');
            }
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

@endsection