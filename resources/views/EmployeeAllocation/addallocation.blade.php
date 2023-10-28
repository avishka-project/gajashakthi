@extends('layouts.app')
@section('content')
    <main>
        <div class="page-header page-header-light bg-white shadow">

            <div class="page-header-content py-3">
                <h1 class="page-header-title">
                    <div class="page-header-icon" style="padding-left: 12px"><i class="fas fa-user-plus"></i></div>
                    <span>Employee Allocation</span>
                </h1>
            </div>
        </div>
        <br>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0 p-2">
                    <div class="row">
                        <div class="col-5">
                            <span id="form_result"></span>
                            <form method="post" id="formTitle" class="form-horizontal">
                                <input type="hidden" id="req_id" name="req_id" value="{{$id}}">
                                <input type="hidden" id="subregionid" name="subregionid">
                                <input type="hidden" id="pageaction" name="pageaction" value="{{$pageaction}}">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Customer*</label>
                                        <input type="text" id="customername" name="customername"
                                            class="form-control form-control-sm" readonly>
                                        <input type="hidden" id="cusid" name="cusid"
                                            class="form-control form-control-sm">
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Sub Customer*</label>
                                        <input type="text" id="subcustomer" name="subcustomer"
                                            class="form-control form-control-sm" readonly>
                                        <input type="hidden" id="subcustomerid" name="subcustomerid"
                                            class="form-control form-control-sm">
                                    </div>

                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <input type="text" id="branch" name="branch"
                                            class="form-control form-control-sm" readonly>
                                        <input type="hidden" id="branchid" name="branchid"
                                            class="form-control form-control-sm">
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Shift</label>
                                        <input type="text" id="shift" name="shift" class="form-control form-control-sm"
                                            readonly>
                                        <input type="hidden" id="shift_id" name="shift_id"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="date" id="date" readonly>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Holiday</label>
                                        <input type="text" id="hollyday" name="hollyday"
                                            class="form-control form-control-sm" readonly>
                                        <input type="hidden" id="hollyday_id" name="hollyday_id"
                                            class="form-control form-control-sm">
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-striped table-bordered table-sm small" id="tableorder">
                                    <thead>
                                        <tr>
                                            <th>Job Title</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody id="requestdetaillist"></tbody>
                                </table>
                                <input type="hidden" id="requestid" name="requestid"
                                    class="form-control form-control-sm">
                            </form>
                        </div>
                        <div class="col-7">
                            <form method="post" id="emplist" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Employees*</label>
                                        <select name="employee" id="employee" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Employees</option>
                                            @foreach($employees as $employee)
                                            <option value="{{$employee->id}}">{{$employee->emp_fullname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Job Title*</label>
                                        <select name="title" id="title" class="form-control form-control-sm">
                                            <option value="">Select Job Title</option>
                                            @foreach($jobtitles as $title)
                                            <option value="{{$title->id}}">{{$title->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" id="empsubregion" name="empsubregion">
                                </div>
                                <div class="form-row mt-3">
                                    &nbsp;&nbsp;<button type="button" id="formsubmit"
                                        class="btn btn-primary btn-sm px-4 fa-pull-right"><i
                                            class="fas fa-plus"></i>&nbsp;Add to list</button>
                                    <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">


                                    <input type="hidden" name="allocationid" class="form-control form-control-sm"
                                        id="allocationid">
                                    <input type="hidden" name="allocationdeiailsid" class="form-control form-control-sm"
                                        id="allocationdeiailsid">
                                    &nbsp;<button type="button" name="Btnupdatelist" id="Btnupdatelist"
                                        class="btn btn-primary btn-sm px-4 fa-pull-right" style="display:none;"><i
                                            class="fas fa-plus"></i>&nbsp;Update List</button>

                                    &nbsp;&nbsp;<button type="button" id="previouslist"
                                        class="btn btn-success btn-sm px-4 "><i
                                            class="fas fa-calendar-check"></i>&nbsp;Same as Yesterday</button>
                                    &nbsp;&nbsp;<button type="button" id="specialrquest"
                                        class="btn btn-warning btn-sm px-4 "><i
                                            class="fas fa-user-plus"></i>&nbsp;Special Request</button>

                                </div>
                            </form>
                            <hr>

                            <table class="table table-striped table-bordered table-sm small" id="allocationtbl">
                                <thead>
                                    <tr>
                                        <th>Empolyee Name</th>
                                        <th>Job Title</th>
                                        <th>Action</th>
                                        <th class="d-none">empID</th>
                                    </tr>
                                </thead>
                                <tbody id="Detilslist"></tbody>
                            </table>
                            <div class="form-group mt-2">
                                <button type="button" name="btncreateorder" id="btncreateorder"
                                    class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                        class="fas fa-plus"></i>&nbsp;Create Allocation</button>
                                <input type="hidden" name="action" id="action" value="Add" />
                                <input type="hidden" name="hidden_id" id="hidden_id" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- special request model --}}

           <!-- Modal Area Start -->
           <div class="modal fade" id="specailrequest" data-backdrop="static" data-keyboard="false" tabindex="-1"
           aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
              <div class="modal-content">
                  <div class="modal-header p-2">
                    <h5 >Add Special Request</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <form method="post" id="formspecialrequest" class="form-horizontal">
                  <div class="modal-body">
                      <div class="row">
                          <div class="col">
                              <span id="form_result"></span>
                            
                                  {{ csrf_field() }}
                                  <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Employees*</label>
                                        <select name="specialemployee" id="specialemployee" class="form-control form-control-sm" required>
                                            <option value="">Select Employees</option>
                                        @foreach($employeesspecial as $employeespecial)
                                            <option value="{{$employeespecial->id}}">{{$employeespecial->emp_fullname}} -- {{$employeespecial->subregion}}</option>
                                         @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Job Title*</label>
                                        <select name="spciltitle" id="spciltitle" class="form-control form-control-sm" required>
                                            <option value="">Select Job Title</option>
                                @foreach($jobtitles as $title)
                                                <option value="{{$title->id}}">{{$title->title}}</option>
                                @endforeach
                                        </select>
                                    </div>

                                </div>

                               
                             
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer p-2">
                    &nbsp;&nbsp;<button type="button" id="formsubmitspecial" class="btn btn-primary btn-sm px-4 fa-pull-right"><i class="fas fa-plus"></i>&nbsp;Add to list</button>
                    <input name="submitBtnspecial" type="submit" value="Save" id="submitBtnspecial" class="d-none">
                </div>
            </form>
              </div>
          </div>
      </div>

@endsection

@section('script')

    <script>
        $(document).ready(function(){
            $('#empallocationlist').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#empallocationlistdrop').addClass('show');
            $('#empallocation_link').addClass('active');

                var id =$('#req_id').val();

                if ($('#pageaction').val() == 'Add') {
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
                    data: {
                        id: id
                    },
                    success: function (data) {

                        if (data.redirect) {
                            window.location.href = '{{ route("allocation") }}';
                        } else {
                            $('#subregionid').val(data.result.mainData.subregion_id);
                            $('#customername').val(data.result.mainData.name);
                            $('#cusid').val(data.result.mainData.customer_id);
                            $('#branch').val(data.result.mainData.branch_name);
                            $('#branchid').val(data.result.mainData.customerbranch_id);
                            $('#subcustomer').val(data.result.mainData.sub_name);
                            $('#subcustomerid').val(data.result.mainData.subcustomer_id);
                            $('#shift').val(data.result.mainData.shift_name);
                            $('#shift_id').val(data.result.mainData.shift_id);
                            $('#date').val(data.result.allocatedate);
                            $('#hollyday').val(data.result.holidatename);
                            $('#hollyday_id').val(data.result.holidateid);
                            $('#requestid').val(id);
                            $('#requestdetaillist').html(data.result.detaildata);
                            $('.modal-title').text('Allocate Employees');
                            $('#action_button').html('Add');
                            $('#action').val('Add');
                            $('#form_result').html('');
                            $('#formModal').modal('show');
                        }
                    }
                })
                }
                else{
                   
  
                    

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
                        $('#subregionid').val(data.result.mainData.subregion_id);
                        $('#customername').val(data.result.mainData.name);
                        $('#cusid').val(data.result.mainData.customer_id);
                        $('#subcustomer').val(data.result.mainData.sub_name);
                        $('#subcustomerid').val(data.result.mainData.subcustomer_id);
                        $('#branch').val(data.result.mainData.branch_name);
                        $('#branchid').val(data.result.mainData.customerbranch_id);
                        $('#hollyday_id').val(data.result.mainData.holiday_id);
                        $('#hollyday').val(data.result.mainData.holiday);
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
//         


                }
                


            $("#formsubmit").click(function () {
                if (!$("#emplist")[0].checkValidity()) {
                    // If the form is invalid, submit it. The form won't actually submit;
                    // this will just cause the browser to display the native HTML5 error messages.
                    $("#submitBtn").click();
                } else {
                    var employeeID = $('#employee').val();
                    var titleID = $('#title').val();
                    var empsubregionID = $('#empsubregion').val();
                    var employee = $("#employee option:selected").text();
                    var title = $("#title option:selected").text();

                    $('#allocationtbl > tbody:last').append('<tr class="pointer"><td>' + employee + '</td><td>' + title + '</td><td class="d-none">'
                        + employeeID + '</td><td class="d-none">' + titleID + '</td><td class="d-none">' + empsubregionID + '</td><td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td></tr>');
                    $('#title').val('');
                    $('#employee').val('');
                    $('#empsubregion').val('');
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

                    var subregionid = $('#subregionid').val();
                    var cusid = $('#cusid').val();
                    var requestid = $('#requestid').val();
                    var subcusidid = $('#subcustomerid').val();
                    var branchid = $('#branchid').val();
                    var hollyday_id = $('#hollyday_id').val();
                    var shift_id = $('#shift_id').val();
                    var date = $('#date').val();
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
                            hidden_id: hidden_id,
                            subregion: subregionid
                        },
                        url: action_url,
                        success: function(result) {

                            if($('#action').val() == 'Add'){
                                if (result.status == 1) {
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                            }else{
                               window.location.href = '{{ route("allocation") }}';
                            }
                           
                            action(result.action);
                        }
                    });
                }
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
                  var empsubregionID = $('#empsubregion').val();

                  $("#allocationtbl> tbody").find('input[name="hiddenid"]').each(function () {
                      var hiddenid = $('#hiddenid').val();
                      if (hiddenid == detailid) {
                          $(this).parents("tr").remove();
                      }
                  });

                  $('#allocationtbl> tbody:last').append('<tr class="pointer"><td>' + employee + '</td><td>' + title + '</td><td class="d-none">' +
                      employeeID + '</td><td class="d-none">' + titleID + '</td><td class="d-none">' +empsubregionID + '</td><td class="d-none">' +
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
        // special request function
        $('#specialrquest').click(function () {
            $('#specailrequest').modal('show');
        });


        $("#formsubmitspecial").click(function () {
                    var employeeID = $('#specialemployee').val();
                    var titleID = $('#spciltitle').val();
                    var empsubregionID = $('#empsubregion').val();
                    var employee = $("#specialemployee option:selected").text();
                    var title = $("#spciltitle option:selected").text();

                    $('#allocationtbl > tbody:last').append('<tr class="pointer"><td>' + employee + '</td><td>' + title + '</td><td class="d-none">'
                        + employeeID + '</td><td class="d-none">' + titleID + '</td><td class="d-none">' + empsubregionID + '</td><td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td></tr>');
                    $('#spciltitle').val('');
                    $('#specialemployee').val('');
                    $('#empsubregion').val('');
                    $('#specailrequest').modal('hide');
            });



// get sub customer branch list
            $('#subcustomerlist').change(function () {
            var subcustomerId = $(this).val();
            if (subcustomerId !== '') {
                $.ajax({
                    url: '{!! route("getbranchallocatoion", ["subcustomerId" => "id_subcustomer"]) !!}'.replace('id_subcustomer', subcustomerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#employee').empty().append(
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

        // get subregional id according to selected employee 
        $('#employee').change(function () {
            var subcustomerId = $(this).val();
                $.ajax({
                    url: '{!! route("getempregion", ["subcustomerId" => "id_subcustomer"]) !!}'.replace('id_subcustomer', subcustomerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#empsubregion').val(data.result.subregion_id); 
                   
                    }
                });
          
        });
        
        // get subregional id according to selected employee 
        $('#specialemployee').change(function () {
            var subcustomerId = $(this).val();
                $.ajax({
                    url: '{!! route("getempregion", ["subcustomerId" => "id_subcustomer"]) !!}'.replace('id_subcustomer', subcustomerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#empsubregion').val(data.result.subregion_id); 
                   
                    }
                });
          
        });

        });

        
       
    function productDelete(ctl) {
    	$(ctl).parents("tr").remove();
    }
    function action(data) { //alert(data);
        var obj = JSON.parse(data);
        $.notify({
            // options
            icon: obj.icon,
            title: obj.title,
            message: obj.message,
            url: obj.url,
            target: obj.target
        }, {
            // settings
            element: 'body',
            position: null,
            type: obj.type,
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "center"
            },
            offset: 100,
            spacing: 10,
            z_index: 1031,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'

			});
 }
       
    </script>

@endsection