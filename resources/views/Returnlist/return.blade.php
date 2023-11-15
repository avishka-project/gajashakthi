@extends('layouts.app')

@section('content')
<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
            <div class="page-header-content py-3">
                <h1 class="page-header-title">
                    <div class="page-header-icon"><i class="fas fa-users"></i></div>
                    <span>Add Return</span>
                </h1>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-4">
        <div class="card mb-2">
            <div class="card-body">
                <form class="form-horizontal" id="formFilter">
                    <div class="form-row mb-1">
                        <div class="col-md-2">
                                <label class="small font-weight-bold text-dark">Location*</label>
                                <select name="location" id="location" class="form-control form-control-sm">
                                    <option value="">Select Location</option>
                                    @foreach($locations as $location)
                                    <option value="{{$location->id}}">{{$location->branch_name}}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small font-weight-bold text-dark">Department</label>
                            <select name="department_f" id="department_f" class="form-control form-control-sm">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{$dept->id}}">{{$dept->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small font-weight-bold text-dark">Emp Search Option</label>
                            <select name="search_option" id="search_option" class="form-control form-control-sm">
                                <option value="serviceno">Service No</option>
                                <option value="employee_name">Employee Name</option>
                                <option value="employee_nic">Employee NIC</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="serviceno_div">
                            <label class="small font-weight-bold text-dark">Service No</label>
                            <select name="serviceno" id="serviceno" class="form-control form-control-sm">
                                <!-- Options for Service No -->
                                <option value="">Service No</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3" id="employee_name_div">
                            <label class="small font-weight-bold text-dark">Employee Name</label>
                            <select name="employee_name" id="employee_name" class="form-control form-control-sm">
                                <!-- Options for Employee Name -->
                                <option value="">Employee Name</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3" id="employee_nic_div">
                            <label class="small font-weight-bold text-dark">Employee NIC</label>
                            <select name="employee_nic" id="employee_nic" class="form-control form-control-sm">
                                <!-- Options for Employee NIC -->
                                <option value="">Employee NIC</option>
                            </select>
                        </div>
                        <div class="col-md-11">
                            <button type="submit" class="btn btn-primary btn-sm filter-btn float-right" id="btn-filter"> Filter</button>
                        </div>
                        <div class="col-md-1">
                            <button style="margin-top: 5px;width: 100px;" type="button" class="btn btn-secondary btn-sm reset-btn float-left" id="btn-reset"> Reset</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0 p-2">
                <div class="row">
                    {{-- <div class="col-12">
                        @can('employee-create')
                            <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record" id="create_record"><i class="fas fa-plus mr-2"></i>Add Return List</button>
                        @endcan
                    </div> --}}
                    <div class="col-12">
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">
                        <div class="center-block fix-width scroll-inner">
                        <table class="table table-striped table-bordered table-sm small nowrap display" style="width: 100%" id="emptable">
                            <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>Issuing</th>
                                    <th>Department</th>
                                    <th>Employee</th>
                                    <th>Location</th>
                                    <th>Issue Month</th>
                                    <th>Issue Type</th>
                                    <th>Payment Type</th>
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
    <div class="modal fade" id="addReturnModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Add Return Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <span id="form_result"></span>
                        <form method="post" id="formTitle" class="form-horizontal">
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Issuing*</label>
                                    <select name="issuing" id="issuing" class="form-control form-control-sm"
                                        readonly>
                                        <option value="">Select Type</option>
                                        <option value="location">Location</option>
                                        <option value="department">Department</option>
                                        <option value="employee">Employee</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row mb-1">
                                <div id="app_locationDiv" style="display: none;" class="col-12">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Location*</label>
                                        <select name="location1" id="location1"
                                            class="form-control form-control-sm" readonly>
                                            <option value="">Select Location</option>
                                            @foreach($locations as $location)
                                            <option value="{{$location->id}}">{{$location->branch_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="app_departmentDiv" style="display: none;" class="col-12">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Department*</label>
                                        <select name="department1" id="department1" class="form-control form-control-sm" readonly>
                                            <option value="">Select Department</option>
                                            @foreach($departments as $department)
                                            <option value="{{$department->id}}">{{$department->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="app_employeeDiv" style="display: none;" class="col-12">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Employee*</label><br>
                                        <select name="employee" id="employee"
                                            class="form-control form-control-sm custom-select-width" readonly>
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $employee)
                                            <option value="{{$employee->id}}">{{$employee->service_no}} -
                                                {{$employee->emp_name_with_initial}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="app_selectTypeFirst" style="display: none;" class="col-12">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-danger">Please select an issue
                                            type first.</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col-6">
                                    <label class="small font-weight-bold text-dark">Month*</label>
                                    <input type="month" id="month" name="month"
                                        class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div id="app_PaymenttypeDiv" style="display: none;">
                                <div class="form-row mb-1">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Issue Type</label>
                                        <input type="text" id="issuetype" name="issuetype"
                                            class="form-control form-control-sm" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Payment Type</label>
                                        <input type="text" id="paymenttype" name="paymenttype"
                                            class="form-control form-control-sm" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row mb-1">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Remark*</label>
                                    <textarea type="text" id="remark" name="remark"
                                        class="form-control form-control-sm" readonly></textarea>
                                </div>
                            </div>

                            <input type="hidden" name="hidden_id" id="hidden_id" />
                            <input type="hidden" name="app_level" id="app_level" value="1" />

                        </form>
                    </div>
                    <div class="col-8">
                        <table class="table table-striped table-bordered table-sm small" id="tableorder">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Rate</th>
                                    <th>QTy</th>
                                    <th>Total</th>
                                    <th>Asset value</th>
                                    <th>Store</th>
                                    <th class="d-none">ItemID</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody id="tableorderlist"></tbody>
                            <tfoot>
                                <tr style="font-weight: bold;font-size: 18px">
                                    <td colspan="3">Total:</td>
                                    <td id="totalField" class="text-left">0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-2">
                <button type="button" name="add" id="add"
                    class="btn btn-warning px-3 btn-sm">Add</button>
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
    var listcheck = {{ $listpermission }};
    var editcheck = {{ $editpermission }};
    var deletecheck = {{ $deletepermission }};

    $('#collapseCorporation').addClass('show');
        $('#collapsgrninfo').addClass('show');
        $('#returndrop').addClass('show');
        $('#return_link').addClass('active');

    


    $("#etfno").focusout(function(){
        let val = $(this).val();
        $('#emp_id').val(val);
    });



    function load_dt(department,location,employee){
        
        $('#emptable').DataTable({
                "destroy": true,
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: scripturl + '/returnlist.php',

                    type: "POST", // you can use GET
                    data: {'department':department, 
                           'location':location, 
                           'employee':employee, 
                        },
                    
                },
                dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            "buttons": [
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Add Return Details', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { 
                    extend: 'print', 
                    title: 'Add Return Details',
                    className: 'btn btn-primary btn-sm', 
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function ( win ) {
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    }, 
                },
            ],
                "order": [[ 5, "desc" ]],
                "columns": [
                    {
                        "data": "id",
                        "className": 'text-dark'
                    },
                    {
                        "data": "issuing",
                        "className": 'text-dark'
                    },
                    {
                        "data": "name",
                        "className": 'text-dark'
                    },  
                    {
                        "data": null,
                        "className": 'text-dark',
                        "render": function (data, type, full, meta) { 
                            if ((data.service_no == '') || (data.service_no== null)) {
                                return '';
                            } else {
                                return data.service_no + '-' + data.emp_name_with_initial;
                            }
                        }
                    },
                    {
                        "data": "branch_name",
                        "className": 'text-dark'
                    },
                    {
                        "data": "month",
                        "className": 'text-dark'
                    }, 
                    {
                        "data": "issue_type",
                        "className": 'text-dark'
                    },
                    {
                        "data": "payment_type",
                        "className": 'text-dark'
                    },
                    
                    {
                        "data": "remark",
                        "className": 'text-dark'
                    },

                    {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {

                        var button = '';

                        if (listcheck) {
                                    button += ' <button name="returnview" id="' + full['id'] + '" class="returnview btn btn-outline-secondary btn-sm" title="Add Return" type="submit"><i class="fas fa-exchange-alt"></i></button>';
                        }
                       
                        return button;
                    }
                }
                ],
                drawCallback: function(settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
    }

    load_dt('', '', '');

    $('#formFilter').on('submit',function(e) {
        e.preventDefault();
        let department = $('#department_f').val();
        let location = $('#location').val();
        var employee;

        var selecttype= $('#search_option').val();
        if(selecttype=='serviceno'){
            employee = $('#serviceno').val();
        }
        else if(selecttype=='employee_name'){
                    employee = $('#employee_name').val();
        }
        else if(selecttype=='employee_nic'){
                    employee = $('#employee_nic').val();
        }


        load_dt(department,location,employee);
    });

});
$(document).ready(function () {

    let company = $('#company');
    let department = $('#department');
    department.select2({
        placeholder: 'Select...',
        width: '100%',
        allowClear: true,
        ajax: {
            url: '{{url("department_list_sel2")}}',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1,
                    company: company.val()
                }
            },
            cache: true
        }
    });


    $('#add').click(function () {
        var issuing = $('#issuing').val();

                if(issuing=='location'){
                var location = $('#location1').val();
                var department = '';
                var employee = '';
                var month = $('#month').val();
                var issuetype = '';
                var paymenttype = '';
                var remark = $('#remark').val();
                var hidden_id = $('#hidden_id').val();
                }
                else if(issuing=='department'){
                var location = '';
                var department = $('#department1').val();
                var employee = '';
                var month = $('#month').val();
                var issuetype = '';
                var paymenttype = '';
                var remark = $('#remark').val();
                var hidden_id = $('#hidden_id').val();
                }
                else if(issuing=='employee'){
                var location = '';
                var department = '';
                var employee = $('#employee').val();
                var month = $('#month').val();
                var issuetype = $('#issuetype').val();
                var paymenttype = $('#paymenttype').val();
                var remark = $('#remark').val();
                var hidden_id = $('#hidden_id').val();
                }

        var tableDataArray = [];
        var isAnyFieldEmpty = false;

        $('#tableorder tbody tr').each(function() {
            var item = $(this).find('td#itemid').text();
            var itemname = $(this).find('td#itemname').text();
            var rate = $(this).find('td#rate').text();
            var qty = $(this).find('td#qty').text();
            var total = $(this).find('td#total').text();
            var assetvalue = $(this).find('input[type="text"]').val();
            var stockId = $(this).find('select').val();

            // Check if any input or select is empty
            if (!assetvalue || stockId === "") {
                // Display an alert if any input or select is empty
                alert("Please fill in all fields for row with item: " + itemname);
                isAnyFieldEmpty = true; // Set the flag to true
                return false; // Exit the loop
            }

            // Proceed with creating the rowData object and pushing it to the array
            var rowData = {
                'item': item,
                'rate': rate,
                'qty': qty,
                'total': total,
                'assetvalue': assetvalue,
                'stockId': stockId,
                // Add other properties as needed
            };

            // Push the current row data object to the array
            tableDataArray.push(rowData);
        });

        // Log the array if all rows have valid data
        if (!isAnyFieldEmpty) {
            // console.log(tableDataArray);
            // console.log(issuing,location,department,employee,month,issuetype,paymenttype,remark,hidden_id);
              $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        tableDataArray: tableDataArray,
                        issuing: issuing,
                        location: location,
                        department: department,
                        employee: employee,
                        month: month,
                        issuetype: issuetype,
                        paymenttype: paymenttype,
                        remark: remark,
                        hidden_id: hidden_id,

                    },
                    url: '{!! route("returnadd") !!}',
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
                            window.location.reload(); // Use window.location.reload()
                        }

                        $('#form_result').html(html);
                        // resetfield();

                    }
                });
        }

    });

    $(document).on('click', '.returnview', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("returnedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#issuing').val(data.result.mainData.issuing);

                    app_issuingChanges(data.result.mainData.issuing);

                    if (data.result.mainData.issuing == "employee") {
                        $('#employee').val(data.result.mainData.employee_id);
                        $('#issuetype').val(data.result.mainData.issue_type);
                        $('#paymenttype').val(data.result.mainData.payment_type);
                    } else if(data.result.mainData.issuing == "department"){
                        $('#department1').val(data.result.mainData.department_id);
                    }else {
                        $('#location1').val(data.result.mainData.location_id);
                    }

                    $('#month').val(data.result.mainData.month);
                    $('#remark').val(data.result.mainData.remark);

                    $('#tableorderlist').html(data.result.requestdata);
                    ApproveTotalSum();

                    $('#hidden_id').val(id_approve);
                    $('#app_level').val('1');
                    $('#addReturnModal').modal('show');

                }
            })


        });

});
function app_issuingChanges(app_issuing) {

if (app_issuing === "location") {
    $("#app_locationDiv").show();
    $("#app_departmentDiv").hide();
    $("#app_employeeDiv").hide();
    $("#app_selectTypeFirst").hide();
    $("#app_PaymenttypeDiv").hide();
} else if (app_issuing === "employee") {
    $("#app_locationDiv").hide();
    $("#app_departmentDiv").hide();
    $("#app_employeeDiv").show();
    $("#app_selectTypeFirst").hide();
    $("#app_PaymenttypeDiv").show();
} else if(app_issuing === "department"){
        $("#app_departmentDiv").show();
        $("#app_locationDiv").hide();
        $("#app_employeeDiv").hide();
        $("#app_selectTypeFirst").hide();
        $("#app_PaymenttypeDiv").hide();
    }else {
    $("#app_locationDiv").hide();
    $("#app_departmentDiv").hide();
    $("#app_employeeDiv").hide();
    $("#app_selectTypeFirst").show();
    $("#app_PaymenttypeDiv").hide();
}

}

function ApproveTotalSum() {
        var totalSum = 0;

        $('#tableorder tbody tr').each(function () {
            var totalCell = $(this).find('td:eq(3)'); // Assuming total is in the 4th cell
            var totalValue = parseFloat(totalCell.text());

            if (!isNaN(totalValue)) {
                totalSum += totalValue;
            }
        });

        $('#totalField').text(totalSum.toFixed(2));
    }

</script>
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#serviceno').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("returnserviceno") !!}',
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
                                text: item.service_no + ' - ' + item.emp_name_with_initial +
                                    ' - ' + item.emp_national_id,
                                id: item.id
                            };
                        })
                    };
                }
            }
        });

        $("#employee_name_div, #employee_nic_div").hide();

        // Add change event listener to the search option select
        $("#search_option").change(function () {
            // Hide all divs
            $("#serviceno_div, #employee_name_div, #employee_nic_div").hide();
            var selectedOption = $(this).val();
            $("#" + selectedOption + "_div").show();

if(selectedOption=='serviceno'){
 $('#serviceno').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("returnserviceno") !!}',
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
                                text: item.service_no + ' - ' + item.emp_name_with_initial +
                                    ' - ' + item.emp_national_id,
                                id: item.id
                            };
                           
                        })
                    };
                }
            }
        });
}
else if(selectedOption=='employee_name'){
    $('#employee_name').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("returngetempname") !!}',
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
                                text: item.service_no + ' - ' + item.emp_name_with_initial +
                                    ' - ' + item.emp_national_id,
                                id: item.id
                            };
                        })
                    };
                }
            }
        });
}
else if(selectedOption=='employee_nic'){
    $('#employee_nic').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("returngetempnic") !!}',
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
                                text: item.service_no + ' - ' + item.emp_name_with_initial +
                                    ' - ' + item.emp_national_id,
                                id: item.id
                            };
                        })
                    };
                }
            }
        });
}
        });


        // Store the initial/default values of the select elements
        var initialEmployeeNameValue ='';
        var initialEmployeeNicValue = '';
        var initialServiceNoValue ='';

        // Add a click event listener to the Reset button
        $("#btn-reset").click(function () {

            // Reset the Select2 element
            $("#serviceno").val(initialServiceNoValue).trigger("change");
            $("#employee_name").val(initialEmployeeNameValue).trigger("change");
            $("#employee_nic").val(initialEmployeeNicValue).trigger("change");
            
            // Clear other form fields if needed
            $("#department_f").val("");
            $("#location").val("");
        });
    });
</script>

@endsection