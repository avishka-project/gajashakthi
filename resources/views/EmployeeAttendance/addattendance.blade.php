@extends('layouts.app')
@section('content')
    <main>
        <div class="page-header page-header-light bg-white shadow">
            <div class="container-fluid">
                <div class="page-header-content py-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon" style="padding-left: 12px"><i class="fas fa-user-plus"></i></div>
                        <span>Security Attendance</span>
                    </h1>
                </div>
            </div>
        </div>
        <br>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0 p-2">
                            <span id="form_result"></span>
                          
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col-9">
                                        <form method="post" id="formTitle" class="form-horizontal">
                                            <input type="hidden" id="req_id" name="req_id" value="">
                                            <input type="hidden" id="subregionid" name="subregionid">
                                            <input type="hidden" id="pageaction" name="pageaction" value="">
                                            <div class="form-row mb-1">
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark">Client*</label>
                                                    <select name="customer" id="customer"
                                                        class="form-control form-control-sm" required>
                                                        <option value="">Select Client</option>
                                                        @foreach($customers as $customer)
                                                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                {{-- <div class="col">
                                                <label class="small font-weight-bold text-dark">Sub Client*</label>
                                                <select name="subcustomer" id="subcustomer"
                                                    class="form-control form-control-sm">
                                                    <option value="">Select Sub Client</option>
                                                </select>
                                            </div> --}}

                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark">Branch*</label>
                                                    <select name="area" id="area" class="form-control form-control-sm"
                                                        required>
                                                        <option value="">Select Branch</option>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark">Shift</label>
                                                    <select name="shift" id="shift" class="form-control form-control-sm"
                                                        required>
                                                        <option value="">Select Shift</option>
                                                        @foreach($shifttypes as $shifttype)
                                                        <option value="{{$shifttype->id}}">{{$shifttype->shift_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label class="small font-weight-bold text-dark">Date*</label>
                                                    <input type="date" class="form-control form-control-sm"
                                                        placeholder="" name="date" id="date"
                                                        value="<?php echo date('Y-m-d') ?>">
                                                </div>
                                            </div>
                                            <div class="form-row mb-1">
                                                <div class="col">
													<label class="small font-weight-bold text-dark"></label>
													<div class="form-check">
														&nbsp;&nbsp;&nbsp;<input class="form-check-input" type="checkbox" value="1" id="lastshift"
															name="lastshift">
														<label class="form-check-label font-weight-bold text-dark" for="lastshift">Last Shift</label>
													</div>
												</div>
                                                <div class="col">
                                                    <div class="container-fluid" style="padding-top: 30px;">
                                                        <button type="button" id="vsoemployee"
                                                            class="btn btn-warning btn-sm px-4 fa-pull-right" disabled><i class="fas fa-user"></i>&nbsp;View VSO Employee List</button>
                                                    </div>

                                                </div>
                                                <div class="col">
                                                    <div class="container-fluid" style="padding-top: 30px;">
                                                        <button type="button" id="serachbtn"
                                                            class="btn btn-primary btn-sm px-4 fa-pull-right"><i
                                                                class="fas fa-search"></i>&nbsp;Search</button>
                                                    </div>

                                                </div>
                                            </div>
                                    </div>
                                    <input type="hidden" id="requestid" name="requestid">
                                    <input type="hidden" id="subregion" name="subregion">
                                    <input type="hidden" id="totalcount" name="totalcount">
                                    <input type="hidden" id="holidaytype" name="holidaytype">
                                    </form>
                                    <div class="col-3">
                                        <table class="table table-striped table-bordered table-sm small"
                                            id="tableorder">
                                            <thead>
                                                <tr>
                                                    <th>Job Title</th>
                                                    <th>Count</th>
                                                </tr>
                                            </thead>
                                            <tbody id="requestdetaillist"></tbody>
                                        </table>
                                      
                                    </div>
                                </div>
                        <hr>
                  
                        <div class="col-12">
                                    <div class="form-check">
                                        <input class="serchname form-check-input" type="checkbox" value="1" id="serchname"
                                        name="serchname">
                                    <label class="form-check-label font-weight-bold text-dark" for="serchname">Search by Name</label>
                                  </div><br>
                            <div class="scrollbar pb-3" id="style-2">
                                <table class="table table-striped table-bordered table-sm small nowrap display" id="allocationtbl" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Empolyee Name</th>
                                        <th>Service No</th>
                                        <th>Job Title</th>
                                        <th>Age</th>
                                        <th>On Time</th>
                                        <th>Off Time</th>
                                        <th style="white-space: nowrap;">Action</th>
                                        <th class="d-none">empID</th>
                                    </tr>
                                </thead>
                                <tbody id="emplistbody">
                                    <tr>
                                        <td style="white-space: nowrap;">
                                          <select name="employee" id="employee" class="employee form-control form-control-sm" style="width:100%">
                                            <option value="">Select Employees</option>
                                        </select>

                                        </td>
                                        <td style="white-space: nowrap;">

                                            <input type="text" id="serviceno" name="serviceno"
                                                class="form-control form-control-sm" readonly>

                                        </td>
                                        <td style="white-space: nowrap;">

                                            <select name="title" id="title" class="form-control form-control-sm"
                                                required>
                                                <option value="">Select Job Title</option>
                                                @foreach($titles as $title)
                                                <option value="{{$title->id}}">{{$title->title}}</option>
                                                @endforeach
                                            </select>

                                        </td>
                                        <td style="white-space: nowrap;">

                                            <input type="text" id="empage" name="empage"
                                                class="form-control form-control-sm" readonly>

                                        </td>
                                        <td style="white-space: nowrap;">
                                            <input type="datetime-local" id="empontime" name="empontime"
                                                class="form-control form-control-sm" required>

                                        </td>
                                        <td style="white-space: nowrap;">

                                            <input type="datetime-local" id="empofftime" name="empofftime"
                                                class="form-control form-control-sm" required>

                                        </td>
                                            <td style="white-space: nowrap;">
                                                <button type="button" onclick="productDelete(this);"
                                                class="deletebtn btn btn-danger btn-sm " disabled><i class="fas fa-trash-alt"></i></button>
                                            <button class="addRowBtn btn btn-success btn-sm "><i class="fas fa-plus"></i></button>
                                            
                                        </td>
                                        <td class="d-none">

                                            <input type="number" id="empID" name="empID"
                                                class="empID form-control form-control-sm" required>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                            
                            <div class="form-group mt-2 fa-pull-right">
                                <button type="button" name="btncreateorder" id="btncreateorder"
                                    class="btn btn-primary btn-sm  fa-pull-right"><i
                                        class="fas fa-plus"></i>&nbsp;Create Attendance</button>&nbsp;
                                <input type="hidden" name="action" id="action" value="Add" />
                                <input type="hidden" name="hidden_id" id="hidden_id" />
                            </div>
                        </div>
                       




                    
                </div>
            </div>
        </div>

        <div class="modal fade" id="formModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">View VSO Employee List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                        <span aria-hidden="true" >&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="center-block fix-width scroll-inner">
                        <table class="table table-striped table-bordered table-sm small nowrap display" id="vsoeplist"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Service No</th>
                                <th>Empolyee Name</th>
                                <th>Age</th>
                                <th>NIC</th>
                                <th>Mobile No</th>
                            </tr>
                        </thead>
                        <tbody id="emplistbody">
                        </tbody>
                    </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    </main>

@endsection

@section('script')

    <script>
        $(document).ready(function(){
            $('#collapse_employee_info').addClass('show');
            $('#collapsattendance').addClass('show');
            $('#security_staff_main_nav_link').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#security_staff_collapse').addClass('show');
            $('#empallocationlist').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#empallocationlistdrop').addClass('show');
            $('#empattendace_link').addClass('active');

            $(".employee").select2();


            $("#allocationtbl tbody").on("click", ".addRowBtn", function () {
                var totalcount = $('#totalcount').val();
                var rowCount = $('#allocationtbl tbody tr').length;

                if (rowCount >= totalcount) {
                    alert("You have added the maximum limit of " + totalcount + " employees.");
                    return;
                } else {
                    var empontimeValue = $(this).closest("tr").find("input[name='empontime']").val();
                    var empofftimeValue = $(this).closest("tr").find("input[name='empofftime']").val();

                    var newRow = $("#allocationtbl tbody tr:last").clone();

                    newRow.find(".employee").each(function(index)
                    {
                        $(this).select2('destroy');
                    }); 


                    newRow.find("input").val('');
                    newRow.find(".employee").val('');
                    newRow.find("input[name='empontime']").val(empontimeValue);
                    newRow.find("input[name='empofftime']").val(empofftimeValue);
        $("#allocationtbl tbody").find(".deletebtn").prop('disabled', false);
        $(this).closest("tr").find(".addRowBtn").remove();
                    
                    
                    $("#serchname").prop('checked', false);

                    $("#allocationtbl tbody tr:last").after(newRow);
                 
                    
                newRow.find(".employee").select2();
                $(".employee").last().next().next().remove();

                newRow.find(".employee").select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: "{{ route('empattendancegetsearchempinfo') }}",
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    var searchData = {
                        _token: '{{ csrf_token() }}',
                        search: params.term,
                        shift: $('#shift').val(),
                        date: $('#date').val(),
                        subregion: $('#subregion').val(),
                        searchname: $('#serchname').is(':checked') ? '1' : '0'
                    }

                    return searchData;
                },
                processResults: function (response) {
				return {
					results: response
				};
			},
			cache: true
            }
        });

                }
            });


            $('#vsoemployee').click(function () {

            
                $('#vsoeplist').DataTable({
                    "destroy": true,
                    "processing": true,
                    "serverSide": true,
                    ajax: {
                        url: scripturl + '/vsoemployeelist.php',
                        type: "POST",
                        data: {
                            'subregion': $('#subregion').val(), 
                           'today': $('#date').val()
                        },

                    },
                    dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                    responsive: true,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, 'All'],
                    ],
                    
                    "order": [
                        [0, "desc"]
                    ],
                    "columns": [{
                            "data": "serviceno",
                            "className": 'text-dark'
                        },
                        {
                            "data": "empfullname",
                            "className": 'text-dark'
                        },
                        {
                            "data": "birthday",
                            "className": 'text-dark'
                        },
                        
                        {
                            "data": "empnic",
                            "className": 'text-dark'
                        },
                        {
                            "data": "mobileno",
                            "className": 'text-dark'
                        }

                    ],
                    drawCallback: function (settings) {
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                });

                $('#formModal').modal('show');
            });

            $('#serachbtn').click(function () {
    var branch = $('#area').val();
    var shift = $('#shift').val();
    var todate = $('#date').val();

    $.ajax({
        method: "POST",
        dataType: "json",
        data: {
            _token: '{{ csrf_token() }}',
            branch: branch,
            shift: shift,
            todate: todate
        },
        url: "{{ route('attendencgetrequest') }}",
        success: function (data) {
            // Populate other fields here as you are doing
            $('#requestdetaillist').html(data.result.detaildata.html);
            $('#totalcount').val(data.result.detaildata.totalCount);
            $('#empontime').val(data.result.detaildata.ontime);
            $('#empofftime').val(data.result.detaildata.offtime);
            $('#requestid').val(data.result.maindata);
            $('#subregion').val(data.result.subregion);
            $('#holidaytype').val(data.result.holidaytype);
            
            $('#vsoemployee').prop('disabled', false);
            employeedropdown();
            
        }
    });
});


  
             // staffFilter in subregionId
        $("#allocationtbl").on("change", ".employee", function () {
            var employeeID = $(this).val();
            if (employeeID !== '') {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: '{{ csrf_token() }}',
                        employeeID: employeeID
                    },
                    url: "{{ route('attendenceemployeedetails') }}",
                    success: function (data) {
                        var row = $(this).closest("tr");
                        row.find('#serviceno').val(data.result.serviceno);
                        row.find('#title').val(data.result.designation);
                        row.find('#empage').val(data.result.age);
                        row.find('#empID').val(employeeID);
                    }.bind(this),
                });
            } 
        });

// create add attendance layout

        $('#btncreateorder').click(function () {
                $('#btncreateorder').prop('disabled', true).html(
                    '<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Order');

                var tbody = $("#allocationtbl tbody");

                if (tbody.children().length > 0) {
                    var jsonObj = [];
                    $("#allocationtbl tbody tr").each(function () {
                        var item = {};
                        $(this).find('td').each(function (col_idx) {
                          
                            var inputElement = $(this).find('input, select');
                            if (inputElement.length > 0) {
                                item["col_" + (col_idx + 1)] = inputElement.val();
                            } else {
                                item["col_" + (col_idx + 1)] = $(this).text();
                            }
                        });
                        jsonObj.push(item);
                    });
                


                    var customer = $('#customer').val();
                    var subcustomer = $('#subcustomer').val();
                    var branch = $('#area').val();
                    var shiftid = $('#shift').val();
                    var date = $('#date').val();
                    var requestid = $('#requestid').val();
                    var holidaytype = $('#holidaytype').val();

                   var action_url = "{{ route('attendenceinsert') }}";
              
                    
                    $.ajax({
                        method: "POST",
                        dataType: "json",
                        data: {
                            _token: '{{ csrf_token() }}',
                            tableData: jsonObj,
                            customer: customer,
                            subcustomer: subcustomer,
                            branch: branch,
                            shiftid: shiftid,
                            date: date,
                            requestid: requestid,
                            holidaytype: holidaytype
                        },
                        url: action_url,
                        success: function (result) {
                            if (result.redirectUrl) {
           
                   window.location.href = result.redirectUrl;
                        } else {
                            
                        }
                        }
                    });
                }
        }); 

        $("#lastshift").on("change", function () {

            if ($(this).is(":checked")) {
                var branch = $('#area').val();
                var shift = $('#shift').val();
                var todate = $('#date').val();

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        branch: branch,
                        shift: shift,
                        todate: todate
                    },
                    url: "{{ route('emplastshift') }}",
                    success: function (data) {
                        var newRows = data.result;
                        $("#emplistbody").prepend(newRows);
                    }
                });


            }
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


<script>
   $(document).ready(function () {
         //Get Sub Customer
         $('#customer').change(function () {
            var customerId = $(this).val();
            if (customerId !== '') {
                $.ajax({
                    url: '{!! route("getsubcustomers", ["customerId" => "id_customer"]) !!}'
                        .replace('id_customer', customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#subcustomer').empty().append(
                            '<option value="">Select Sub Customer</option>');
                        $.each(data, function (index, subCustomer) {
                            $('#subcustomer').append('<option value="' + subCustomer
                                .id + '">' + subCustomer.sub_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#subcustomer').empty().append('<option value="">Select Sub Customer</option>');
            }

            //Get branch
            if (customerId !== '') {
                $.ajax({
                    url: '{!! route("getbranch", ["customerId" => "id_customer"]) !!}'.replace(
                        'id_customer', customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#area').empty().append(
                            '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#area').append('<option value="' + branch.id + '">' +
                                branch.branch_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#area').empty().append('<option value="">Select Branch</option>');
            }
        });



        //Get branch filtering about subcustomer
        $('#subcustomer').change(function () {
            var customerId = $('#customer').val();
            var subcustomerId = $('#subcustomer').val();
            if (subcustomerId !== '') {
                $.ajax({
                    url: '{!! route("getbranchsubcustomerfilter", ["subcustomerId" => "id_subcustomer", "customerId" => "id_customer"]) !!}'
                        .replace('id_subcustomer', subcustomerId).replace('id_customer',
                            customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#area').empty().append(
                            '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#area').append('<option value="' + branch.id + '">' +
                                branch.branch_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $.ajax({
                    url: '{!! route("getbranch", ["customerId" => "id_customer"]) !!}'.replace(
                        'id_customer', customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#area').empty().append(
                            '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#area').append('<option value="' + branch.id + '">' +
                                branch.branch_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        });

        $("#formsubmit").click(function () {
            

            // need to fix

            
                var  employeename = $("#employee option:selected").text();
                var employeeID = $('#employee').val();
            

            var titlename = $("#title option:selected").text();
            var titleID = $('#title').val();
            var serviceno = $('#serviceno').val();
            var empdesignation = $('#empdesignation').val();
            var empage = $('#empage').val();
            var empontime = $('#empontime').val();
            var empofftime = $('#empofftime').val();
            var totalcount = $('#totalcount').val();

            var rowCount = $('#allocationtbl tbody tr').length;

            if (rowCount >= totalcount) {
                alert("You have added the maximum limit of " + totalcount + " employees.");
                return;
            }

            var rowClass = empage < 60 ? 'text-success' : 'text-danger';

            $('#allocationtbl > tbody:last').append('<tr class="pointer ' + rowClass + '"><td>' + employeename +
                '</td><td>' + titlename + '</td><td>' + serviceno + '</td><td>' + empage + '</td><td>' + empontime + '</td><td>' +
                empofftime + '</td><td class="d-none">' + employeeID + '</td><td class="d-none">' + titleID +
                '</td><td><button type="button" onclick="productDelete(this);" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button></td></tr>');

            // Clear input fields
            $('#employee').val('');
            $('#title').val('');
            $('#serviceno').val('');
            $('#empdesignation').val('');
            $('#empage').val('');
        });

        $(".employee").select2({
            
		// dropdownParent: $('#staticBackdrop'),
		// placeholder: 'Select supplier',
		ajax: {
			url:"{{ route('empattendancegetsearchempinfo') }}",
			type: "post",
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
                    _token: '{{ csrf_token() }}',
					search: params.term,
                    shift: $('#shift').val(),
                    date: $('#date').val(),
                    subregion: $('#subregion').val(),
                    searchname: $('#serchname').is(':checked') ? '1' : '0'
				};
			},
			processResults: function (response) {
				return {
					results: response
				};
			},
			cache: true
		}
	});

    });

      // Calculate and update the total sum in the table footer
      function updateTotalcount() {
        var totalSum = 0;

        $('#tableorder tbody tr').each(function () {
            var totalCell = $(this).find('td:eq(3)'); // Assuming total is in the 4th cell
            var totalValue = parseFloat(totalCell.text());

            if (!isNaN(totalValue)) {
                totalSum += totalValue;
            }
        });

        $('#totalField').text(totalSum);
    }
</script>



<script>
    function idgetinserch() {
        var editempid = $('.employee').val();
        $('#editempid').val(editempid);
    };

    function employeedropdown() {
    $(".employee").select2({
		// dropdownParent: $('#staticBackdrop'),
		// placeholder: 'Select supplier',
		ajax: {
			url:"{{ route('empattendancegetsearchempinfo') }}",
			type: "post",
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
                    _token: '{{ csrf_token() }}',
					search: params.term,
                    shift: $('#shift').val(),
                    date: $('#date').val(),
                    subregion: $('#subregion').val(),
                    searchname: $('#serchname').is(':checked') ? '1' : '0'
				};
			},
			processResults: function (response) {
				return {
					results: response
				};
			},
			cache: true
		}
	});
}
</script>

@endsection