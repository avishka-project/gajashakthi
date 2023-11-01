@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
            <div class="page-header-content py-3">
                <h1 class="page-header-title">
                    <div class="page-header-icon"><i class="fas fa-money-bill-wave-alt"></i></div>
                    <span>Petty Cash Rermbursement</span>
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
                            id="create_record" onclick="inserttable();getdocno()"><i class="fas fa-plus mr-2"></i>Add Pettycash</button>
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
                                        <th>Date</th>
                                        <th>Document No</th>
                                        <th>Service No</th>
                                        <th>Employee Name</th>
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
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Petty Cash</h5>
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
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Document No*</label>
                                        <input type="text" id="docno" name="docno" class="form-control form-control-sm"
                                            required readonly>
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
                                    {{-- <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Employees*</label>
                                        <select name="employee" id="employee" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Employees</option>
                                        </select>                                      
                                    </div> --}}
                                    <input type="hidden" name="empid" id="empid" />
                                    <div class="col-4">
                                        <br>
                                        <input style="margin-top: 5px" type="text" name="empname" id="empname" class="form-control form-control-sm" readonly>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Date*</label>
                                        <input type="date" id="pettydate" name="pettydate" class="form-control form-control-sm"
                                            required>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Petty Cash Float*</label>
                                        <input type="number" id="pettycashfloat" name="pettycashfloat" class="form-control form-control-sm"
                                            required onkeyup="assignTempInput(this.value);">
                                    </div>
                                </div>
                                <br>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <div class="center-block fix-width scroll-inner">
                                            <table
                                                class="table table-striped table-bordered table-sm small nowrap display"
                                                style="width: 100%" id="dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>Seq </th>
                                                        <th>Bill Date</th>
                                                        <th colspan="2">Paid To</th>
                                                        <th>Bill No</th>
                                                        <th>Description</th>
                                                        <th>Rs.</th>
                                                        <th>Float Balance</th>
                                                        <th>Petty Cash Category</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="dataTablebody">
                                                </tbody>
                                                <tfoot>
                                                    <tr
                                                        style="font-weight: bold;font-size: 18px;background-color: #d5dbec;">
                                                        <td class="text-center" colspan="6">Total:</td>
                                                        <td id="view_total" class="text-right">0</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="button" id="add-row"
                                        class="btn btn-outline-primary btn-sm fa-pull-right px-4">Add Row</button>

                                    <button type="button" id="add-editrow"
                                        class="btn btn-outline-primary btn-sm fa-pull-right px-4">Add Row</button>

                                    <br>
                                    <hr>
                                    <br>
                                    <button type="button" name="action_button" id="action_button"
                                        class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                            class="fas fa-plus"></i>&nbsp;Create</button>
                                    <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                                </div>
                                <input type="hidden" name="action" id="action" value="Add" />
                                <input type="hidden" name="hidden_id" id="hidden_id" />
                                <input type="hidden" name="tablerow" id="tablerow" value="20" />
                                <input type="hidden" name="edittablerow" id="edittablerow" value="0" />

                                <input type="hidden" name="thirdapprovelstatus" id="thirdapprovelstatus" value="0" />
                                <input type="hidden" name="editthirdapprovelstatus" id="editthirdapprovelstatus" value="0" />

                                <input type="hidden" name="tempfloatbalance" id="tempfloatbalance" value="0" />


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
{{-- approvel model --}}
    <div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="approveconfirmmodal-title" id="staticBackdropLabel">Approve Petty Cash</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="approvelformTitle">
                        <div class="form-row mb-1">
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">Document No*</label>
                                <input type="text" id="app_docno" name="app_docno" class="form-control form-control-sm"
                                    required readonly>
                            </div>
                            
                            <input type="hidden" name="app_empid" id="app_empid" />
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">Employees*</label>
                                <input type="text" name="app_empname" id="app_empname" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">Date*</label>
                                <input type="date" id="app_pettydate" name="app_pettydate" class="form-control form-control-sm"
                                    required readonly>
                            </div>
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">Petty Cash Float*</label>
                                <input type="number" id="app_pettycashfloat" name="app_pettycashfloat" class="form-control form-control-sm"
                                    required readonly>
                            </div>
                        </div>
                        <br>
                        <div class="form-row mb-1">
                            <div class="col-12">
                                <div class="center-block fix-width scroll-inner">
                                    <table
                                        class="table table-striped table-bordered table-sm small nowrap display"
                                        style="width: 100%" id="app_dataTable">
                                        <thead>
                                            <tr>
                                                <th>Seq </th>
                                                <th class="d-none">ID </th>
                                                <th>Bill Date</th>
                                                <th colspan="2">Paid To</th>
                                                <th>Bill No</th>
                                                <th>Description</th>
                                                <th class="text-right">Rs.</th>
                                                <th class="text-right">Float Balance</th>
                                                <th>Petty Cash Category</th>
                                            </tr>
                                        </thead>

                                        <tbody id="app_dataTablebody">
                                        </tbody>
                                        <tfoot>
                                            <tr
                                                style="font-weight: bold;font-size: 18px;background-color: #d5dbec;">
                                                <td class="text-center" colspan="6">Total:</td>
                                                <td id="app_view_total" class="text-right">0</td>
                                                
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="app_hidden_id" id="app_hidden_id" />
                        <input type="hidden" name="app_level" id="app_level" value="1" />
                        <input type="hidden" name="app_tablerow" id="app_tablerow" />

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

    {{-- view model --}}
<div class="modal fade" id="viewconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="viewmodal-title" id="staticBackdropLabel">View Petty Cash Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="viewformTitle">
                    <div class="form-row mb-1">
                        <div class="col-3">
                            <label class="small font-weight-bold text-dark">Document No*</label>
                            <input type="text" id="view_docno" name="view_docno" class="form-control form-control-sm"
                                required readonly>
                        </div>
                        
                        <input type="hidden" name="view_empid" id="view_empid" />
                        <div class="col-3">
                            <label class="small font-weight-bold text-dark">Employees*</label>
                            <input type="text" name="view_empname" id="view_empname" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-3">
                            <label class="small font-weight-bold text-dark">Date*</label>
                            <input type="date" id="view_pettydate" name="view_pettydate" class="form-control form-control-sm"
                                required readonly>
                        </div>
                        <div class="col-3">
                            <label class="small font-weight-bold text-dark">Petty Cash Float*</label>
                            <input type="number" id="view_pettycashfloat" name="view_pettycashfloat" class="form-control form-control-sm"
                                required readonly>
                        </div>
                    </div>
                    <br>
                    <div class="form-row mb-1">
                        <div class="col-12">
                            <div class="center-block fix-width scroll-inner">
                                <table
                                    class="table table-striped table-bordered table-sm small nowrap display"
                                    style="width: 100%" id="view_dataTable">
                                    <thead>
                                        <tr>
                                            <th>Seq </th>
                                            <th>Bill Date</th>
                                            <th colspan="2">Paid To</th>
                                            <th>Bill No</th>
                                            <th>Description</th>
                                            <th class="text-right">Rs.</th>
                                            <th class="text-right">Float Balance</th>
                                            <th>Petty Cash Category</th>
                                        </tr>
                                    </thead>

                                    <tbody id="view_dataTablebody">
                                    </tbody>
                                    <tfoot>
                                        <tr
                                            style="font-weight: bold;font-size: 18px;background-color: #d5dbec;">
                                            <td class="text-center" colspan="6">Total:</td>
                                            <td id="view_view_total" class="text-right">0</td>
                                            
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="view_hidden_id" id="view_hidden_id" />

                </form>
            </div>
            <div class="modal-footer p-2">
                <button type="button" name="printbutton" id="printbutton"
                    class="btn btn-warning px-3 btn-sm">Print</button>
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
        $('#collapexpenses').addClass('show');
        $('#pettycash_link').addClass('active');

        var approvel01 = {{$approvel01permission}};
        var approvel02 = {{$approvel02permission}};
        var approvel03 = {{$approvel03permission}};

        var listcheck = {{$listpermission}};
        var editcheck = {{$editpermission}};
        var statuscheck = {{$statuspermission}};
        var deletecheck = {{$deletepermission}};
        
        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: scripturl + '/pettycashlist.php',

                type: "POST", // you can use GET
                // data: {
                //     },

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
                    title: 'Petty Cash Details',
                    text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                },
                {
                    extend: 'print',
                    title: 'Petty Cash Details',
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
                    "data": "date",
                    "className": 'text-dark'
                },

                {
                    "data": "document_no",
                    "className": 'text-dark'
                },
                {
                    "data": "service_no",
                    "className": 'text-dark'
                },
                {
                    "data": "emp_name_with_initial",
                    "className": 'text-dark'
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
                                    button += ' <button name="appL1" id="' + full['id'] + '" class="appL1 btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                        if (approvel02) {
                            if (full['approve_01'] == 1 && full['approve_02']==0) {
                                    button += ' <button name="appL2" id="' + full['id'] + '" class="appL2 btn btn-outline-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>'; 
                            }
                        }
                        if (approvel03) {
                            if (full['approve_02'] == 1 && full['approve_03']==0) {
                                    button += ' <button name="appL3" id="' + full['id'] + '" class="appL3 btn btn-outline-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                        if (editcheck) {
                            if (full['approve_03']==1) {
                                    button += ' <button name="view" id="' + full['id'] + '" class="view btn btn-outline-secondary btn-sm" type="submit"><i class="fas fa-eye"></i></button>';      
                            }
                        }
                        if (editcheck) {
                                    button += ' <button name="edit" id="' + full['id'] + '" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';                      
                        }
                        if (statuscheck) {
                                if (full['status'] == 1) {
                                    button += ' <a href="pettycashstatus/' + full['id'] + '/2 " onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                                } else {
                                    button += '&nbsp;<a href="pettycashstatus/' + full['id'] + '/1 "  onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                                }
                        }
                        if (deletecheck) {
                            button += ' <button name="delete" id="' + full['id'] + '" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
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
            $('.modal-title').text('Add New Petty Cash');
            $('#action_button').html('Create');
            $('#action').val('Add');
            $('#form_result').html('');
            $('#formTitle')[0].reset();
            $('#add-row').show();
            $('#add-editrow').hide();
            $('#formModal').modal('show');
        });

        $("#action_button").click(function () {
            if (!$("#formTitle")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn").click();
            } else {

                var action_url = '';
                var Counter='';

                if ($('#action').val() == 'Add') {
                    action_url = "{{ route('pettycashinsert') }}";
                    Counter = $('#tablerow').val();
                }
                if ($('#action').val() == 'Edit') {
                    action_url = "{{ route('pettycashupdate') }}";
                    Counter = $('#edittablerow').val();
                }

                $('#btncreateorder').prop('disabled', true).html(
                    '<i class="fas fa-circle-notch fa-spin mr-2"></i> Creating');

                var documentno = $('#docno').val();
                var employee = $('#empid').val();
                var pettydate = $('#pettydate').val();
                var pettycashfloat = $('#pettycashfloat').val();
                var totalcost = $('#view_total').text();
                var hidden_id = $('#hidden_id').val();

                // console.log(Counter);
                var valuesArray = [];

                for (var i = 1; i <= Counter; i++) {
                    var rowValues = {
                        bill_date: $("#bill_date" + i).val(),
                        emp_type: getempTypeValue(i),
                        paid_to: getPaidToValue(i),
                        bill_no: $("#bill_no" + i).val(),
                        description: $("#description" + i).val(),
                        rs: $("#rs" + i).val(),
                        floatbalance: $("#floatbalance" + i).val(),
                        category: $("#remark" + i).val()
                    };

                    valuesArray.push(rowValues);
                   
                }

                var filteredArray = valuesArray.filter(function (rowValues) {
                    return !isEmptyRow(rowValues);
                });

                    function getPaidToValue(i) {
                    var emptypeValue = $("#emptype" + i).val();
                    if (emptypeValue === "Reg_Emp") {
                        return $("#emp" + i).val();
                    } else if (emptypeValue === "Non_reg_Emp") {
                        return $("#non_reg_emp" + i).val();
                    } else {
                        return ""; // Handle the case when emptype is not selected
                    }
                }
                function getempTypeValue(i) {
                    var regemp = $("#emp" + i).val();
                    var nonregemp = $("#non_reg_emp" + i).val();

                    // console.log("reg"+regemp,"nonreg"+nonregemp);

                    if ((regemp === '' && nonregemp === '') || (regemp === null && nonregemp === null)) {
                        return "";
                    }else {
                        return $("#emptype" + i).val();
                    }
                }

                function isEmptyRow(rowValues) {
                    for (var key in rowValues) {
                        if (rowValues.hasOwnProperty(key) && rowValues[key]) {
                            return false;
                        }
                    }
                    return true;
                }

                // console.log(valuesArray);
                // console.log(filteredArray);
                // console.log(documentno, employee, totalcost, hidden_id);

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        filteredArray: filteredArray,
                        documentno: documentno,
                        employee: employee,
                        pettydate: pettydate,
                        pettycashfloat: pettycashfloat,
                        totalcost: totalcost,               
                        hidden_id: hidden_id,

                    },
                    url: action_url,
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
                url: '{!! route("pettycashedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#docno').val(data.result.mainData.document_no);
                    $('#empid').val(data.result.mainData.employee_id);
                    $('#empname').val(data.result.mainData.service_no+"-"+data.result.mainData.emp_name_with_initial);
                    $('#pettydate').val(data.result.mainData.date);
                    $('#pettycashfloat').val(data.result.mainData.pettycashfloat);
                    $('#editthirdapprovelstatus').val(data.result.mainData.approve_02);
                   var array=(data.result.requestdata);
                   edittabledata(array);

                    $('#hidden_id').val(id);
                    $('.modal-title').text('Edit Petty Cash');
                    $('#action_button').html('Edit');
                    $('#action').val('Edit');
                    $('#add-row').hide();
                    $('#add-editrow').show();
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
                url: '{!! route("pettycashdelete") !!}',
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
                url: '{!! route("pettycashedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_docno').val(data.result.mainData.document_no);
                    $('#app_empid').val(data.result.mainData.employee_id);
                    $('#app_empname').val(data.result.mainData.service_no+"-"+data.result.mainData.emp_name_with_initial);
                    $('#app_pettydate').val(data.result.mainData.date);
                    $('#app_pettycashfloat').val(data.result.mainData.pettycashfloat);
                    $('#editthirdapprovelstatus').val(data.result.mainData.approve_02);
                   var app_array=(data.result.requestdata);
                   approvetabledata(app_array);

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
                url: '{!! route("pettycashedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_docno').val(data.result.mainData.document_no);
                    $('#app_empid').val(data.result.mainData.employee_id);
                    $('#app_empname').val(data.result.mainData.service_no+"-"+data.result.mainData.emp_name_with_initial);
                    $('#app_pettydate').val(data.result.mainData.date);
                    $('#app_pettycashfloat').val(data.result.mainData.pettycashfloat);
                    $('#editthirdapprovelstatus').val(data.result.mainData.approve_02);
                    var app_array=(data.result.requestdata);
                   approvetabledata(app_array);

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
                url: '{!! route("pettycashedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_docno').val(data.result.mainData.document_no);
                    $('#app_empid').val(data.result.mainData.employee_id);
                    $('#app_empname').val(data.result.mainData.service_no+"-"+data.result.mainData.emp_name_with_initial);
                    $('#app_pettydate').val(data.result.mainData.date);
                    $('#app_pettycashfloat').val(data.result.mainData.pettycashfloat);
                    $('#editthirdapprovelstatus').val(data.result.mainData.approve_02);
                    var app_array=(data.result.requestdata);
                   approvetabledata(app_array);

                    $('#app_hidden_id').val(id_approve);
                    $('#app_level').val('3');
                    $('#approveconfirmModal').modal('show');

                }
            })


        });



        $('#approve_button').click(function () {
            var id_hidden = $('#app_hidden_id').val();
            var applevel = $('#app_level').val();

            Counter = $('#app_tablerow').val();
                        var valuesArray = [];

            for (var i = 1; i <= Counter; i++) {
                var rowValues = {
                    id: $("#app_id" + i).val(),
                    bill_date: $("#app_bill_date" + i).val(),
                    bill_no: $("#app_bill_no" + i).val(),
                    description: $("#app_description" + i).val(),
                    rs: $("#app_rs" + i).val(),
                    category: $("#app_remark" + i).val()
                };

                valuesArray.push(rowValues);
            
            }

            var filteredArray = valuesArray.filter(function (rowValues) {
                return !isEmptyRow(rowValues);
            });

            function isEmptyRow(rowValues) {
                for (var key in rowValues) {
                    if (rowValues.hasOwnProperty(key) && rowValues[key]) {
                        return false;
                    }
                }
                return true;
            }

            // console.log(valuesArray);
            console.log(filteredArray);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("pettycashapprove") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_hidden,
                    applevel: applevel,
                    filteredArray: filteredArray   
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


         // view 
         $(document).on('click', '.view', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("pettycashedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#view_docno').val(data.result.mainData.document_no);
                    $('#view_empid').val(data.result.mainData.employee_id);
                    $('#view_empname').val(data.result.mainData.service_no+"-"+data.result.mainData.emp_name_with_initial);
                    $('#view_pettydate').val(data.result.mainData.date);
                    $('#view_pettycashfloat').val(data.result.mainData.pettycashfloat);
                    var view_array=(data.result.requestdata);
                   viewtabledata(view_array);

                   $('#view_hidden_id').val(id_approve);
                    $('#viewconfirmModal').modal('show');

                }
            })
        });
// print
        $('#printbutton').click(function () {
    var id_hidden = $('#view_hidden_id').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '{!! route("pettycashprint") !!}',
        type: 'POST',
        dataType: "json", // Set the response type to 'text' as you want to display the PDF in a new tab
        data: {
            id: id_hidden,
        },
        success: function (data) {
            // Create a Blob containing the PDF data
            var pdfBlob = base64toBlob(data.pdf, 'application/pdf');

            // Create a URL for the Blob
            var pdfUrl = URL.createObjectURL(pdfBlob);

            // Trigger a download of the PDF file in the browser
            var a = document.createElement('a');
            a.href = pdfUrl;
            a.download = 'Petty Cash.pdf'; // Set the desired filename
            a.style.display = 'none';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        },
                error: function () {
                    console.log('PDF request failed.');
                }
            });
        });

        
        function base64toBlob(base64Data, contentType) {
            contentType = contentType || '';
            var sliceSize = 1024;
            var byteCharacters = atob(base64Data);
            var byteArrays = [];

            for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                var slice = byteCharacters.slice(offset, offset + sliceSize);
                var byteNumbers = new Array(slice.length);
                for (var i = 0; i < slice.length; i++) {
                    byteNumbers[i] = slice.charCodeAt(i);
                }
                var byteArray = new Uint8Array(byteNumbers);
                byteArrays.push(byteArray);
            }

            return new Blob(byteArrays, {
                type: contentType
            });
        }

    });

    function getdocno() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.ajax({
            url: '{!! route("pettycashgetdocno") !!}',
            type: 'POST',
            dataType: "json",
            // data: {
            //     id: supplierID
            // },
            success: function (data) {
                $('#docno').val('PETTY-' + data.result);

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
var thirdapprovelstatus = $('#thirdapprovelstatus').val();
// insert table load
    function inserttable(){
        var seqCounter = 1;
    var Counter = 1;

    function addRow() {
        var tbody = document.getElementById("dataTablebody");
        var newRow = document.createElement("tr");

        var seqCell = document.createElement("td");
        seqCell.textContent = seqCounter++;
        newRow.appendChild(seqCell);

        // Add the Bill Date column with an input of type date
        var billDateCell = document.createElement("td");
        var dateInput = document.createElement("input");
        dateInput.type = "date";
        dateInput.id = "bill_date" + Counter;
        dateInput.name = "bill_date" + Counter;
        billDateCell.appendChild(dateInput);
        newRow.appendChild(billDateCell);

        // Create a new column to the left of emptype with the select options
        var emptypeCell = document.createElement("td");
        var emptypeInput = document.createElement("select");
        emptypeInput.id = "emptype" + Counter;
        emptypeInput.name = "emptype" + Counter;

        // Create and append the "Reg Emp" option
        var optionRegEmp = document.createElement("option");
        optionRegEmp.value = "Reg_Emp";
        optionRegEmp.text = "Reg Emp";
        emptypeInput.appendChild(optionRegEmp);
        // Create and append the "Non-reg Emp" option
        var optionNonRegEmp = document.createElement("option");
        optionNonRegEmp.value = "Non_reg_Emp";
        optionNonRegEmp.text = "Non-reg Emp";
        emptypeInput.appendChild(optionNonRegEmp);

        emptypeCell.appendChild(emptypeInput);
        // Add the new column with select options to the left of empCell
        newRow.insertBefore(emptypeCell, empCell);
       
        // Add the Employee column with an select 2 dropdown
        var empCell = document.createElement("td");
        var empInput = document.createElement("select");
        empInput.id = "emp" + Counter;
        empInput.name = "emp" + Counter;
        empCell.appendChild(empInput);
        newRow.appendChild(empCell);

        var defaultEmpOption = document.createElement("option");
        defaultEmpOption.value = ""; 
        defaultEmpOption.text = "Select Employee";
        empInput.appendChild(defaultEmpOption);
       employeeselct2(Counter);

        // Add the "Nonreg Emp" column with a specific width (55%)
        var non_reg_empCell = document.createElement("td");
        var non_reg_empInput = document.createElement("input");
        non_reg_empInput.type = "text";
        non_reg_empInput.id = "non_reg_emp" + Counter;
        non_reg_empInput.name = "non_reg_emp" + Counter;
        non_reg_empCell.appendChild(non_reg_empInput);
                // billNoCell.style.width = "55%";
        newRow.appendChild(non_reg_empCell);

                                    // Add change event listener to the emptypeInput
                                    emptypeInput.addEventListener("change", function () {
                                    if (emptypeInput.value === "Reg_Emp") {
                                        // Show "Reg Emp" in empCell and reset non_reg_empInput
                                        empCell.style.display = "block";
                                        non_reg_empCell.style.display = "none";
                                        non_reg_empInput.value = ""; // Reset non_reg_empInput
                                    } else if (emptypeInput.value === "Non_reg_Emp") {
                                        // Show "Non-reg Emp" in non_reg_empCell and reset empInput
                                        empCell.style.display = "none";
                                        non_reg_empCell.style.display = "block";
                                        empInput.value = ""; // Reset empInput
                                    } else {
                                        // Hide both empCell and non_reg_empCell and reset both inputs
                                        empCell.style.display = "none";
                                        non_reg_empCell.style.display = "none";
                                        non_reg_empInput.value = ""; // Reset non_reg_empInput
                                        empInput.value = ""; // Reset empInput
                                    }
                                });


                            // Initialize the display based on the default value
                            emptypeInput.dispatchEvent(new Event("change"));

        // Add the "Bill No" column with a specific width (55%)
        var billNoCell = document.createElement("td");
        var billNoInput = document.createElement("input");
        billNoInput.type = "text";
        billNoInput.id = "bill_no" + Counter;
        billNoInput.name = "bill_no" + Counter;
        billNoCell.style.width = "100px";
        billNoInput.style.width = "100px";
        billNoCell.appendChild(billNoInput);
        // billNoCell.style.width = "55%";
        newRow.appendChild(billNoCell);

        // Add the "Description" column with a textarea
        var descriptionCell = document.createElement("td");
        var descriptionTextarea = document.createElement("textarea");
        descriptionTextarea.id = "description" + Counter;
        descriptionTextarea.name = "description" + Counter;
        descriptionCell.appendChild(descriptionTextarea);
        descriptionTextarea.style.width = "100%";
        newRow.appendChild(descriptionCell);

        // Add the "Rs." column with a specific width (55%)
        var rsCell = document.createElement("td");
        var rsInput = document.createElement("input");
        rsInput.id = "rs" + Counter;
        rsInput.type = "text";
        rsInput.name = "rs" + Counter;
        rsInput.style.width = "80px";
        rsCell.style.width = "80px";
        rsCell.appendChild(rsInput);
        // rsCell.style.width = "55%";
        newRow.appendChild(rsCell);

        // Add the "float balance" column with a specific width (55%)
        var floatbalanceCell = document.createElement("td");
        var floatbalanceInput = document.createElement("input");
        floatbalanceInput.id = "floatbalance" + Counter;
        floatbalanceInput.type = "text";
        floatbalanceInput.name = "floatbalance" + Counter;
        floatbalanceInput.readOnly = true;
        floatbalanceInput.style.border = "none";
        floatbalanceInput.style.width = "80px";
        floatbalanceCell.style.width = "80px";
        floatbalanceCell.appendChild(floatbalanceInput);
        // rsCell.style.width = "55%";
        newRow.appendChild(floatbalanceCell);
        floatbalancrCalc(Counter);

// categoy row section
    var remarkCell = document.createElement("td");
        var remarkSelect = document.createElement("select");
        remarkSelect.id = "remark" + Counter;
        remarkSelect.name = "remark" + Counter;
        remarkSelect.readOnly = true;
        remarkSelect.style.border = "none";
        remarkSelect.style.display = "none";

        var defaultOption = document.createElement("option");
        defaultOption.value = ""; 
        defaultOption.text = "Select Category";
        remarkSelect.appendChild(defaultOption);

        @foreach($categories as $category)
            var option{{ $category->id }} = document.createElement("option");
            option{{ $category->id }}.value = "{{ $category->id }}"; 
            option{{ $category->id }}.text = "{{ $category->pettycash_category }}";
            remarkSelect.appendChild(option{{ $category->id }});
        @endforeach

        remarkCell.appendChild(remarkSelect);
        newRow.appendChild(remarkCell);


        // Add the new row to the table
        tbody.appendChild(newRow);

        rsInput.addEventListener("keyup", updateTotal);
        $('#tablerow').val(Counter);
        Counter++;

    }

    // Function to update the total
    function updateTotal() {
        var total = 0;
        // Select input fields with names like "rs1," "rs2," etc.
        $('input[name^="rs"]').each(function () {
            var inputValue = $(this).val();
            if (!isNaN(parseFloat(inputValue))) {
                total += parseFloat(inputValue);
            }
        });

        // Update the total element
        $('#view_total').text(total.toFixed(2));
    }


    // Add 20 rows initially
    for (var i = 0; i < 20; i++) {
        addRow();
    }

    // Add a row when the "Add Row" button is clicked
    document.getElementById("add-row").addEventListener("click", addRow);
    }
   

// edit table feilds data load
    function edittabledata(array){
        var editthirdapprovelstatus = $('#editthirdapprovelstatus').val();
        var seqCounter = 1;
    var Counter = 1;

    function addRow_editform() {
        var tbody = document.getElementById("dataTablebody");
        var newRow = document.createElement("tr");

        var rowData = array[Counter - 1];

        var seqCell = document.createElement("td");
        seqCell.textContent = seqCounter++;
        newRow.appendChild(seqCell);


        // Add the Bill Date column with an input of type date
        var billDateCell = document.createElement("td");
        var dateInput = document.createElement("input");
        dateInput.type = "date";
        dateInput.id = "bill_date" + Counter;
        dateInput.name = "bill_date" + Counter;
        dateInput.value = rowData.bill_date; 
        billDateCell.appendChild(dateInput);
        newRow.appendChild(billDateCell);

        // Create a new column to the left of emptype with the select options
        var emptypeCell = document.createElement("td");
        var emptypeInput = document.createElement("select");
        emptypeInput.id = "emptype" + Counter;
        emptypeInput.name = "emptype" + Counter;
     
        // Create and append the "Reg Emp" option
        var optionRegEmp = document.createElement("option");
        optionRegEmp.value = "Reg_Emp";
        optionRegEmp.text = "Reg Emp";
        emptypeInput.appendChild(optionRegEmp);
        // Create and append the "Non-reg Emp" option
        var optionNonRegEmp = document.createElement("option");
        optionNonRegEmp.value = "Non_reg_Emp";
        optionNonRegEmp.text = "Non-reg Emp";
        emptypeInput.appendChild(optionNonRegEmp);

        emptypeInput.value = rowData.emp_type; 
        emptypeCell.appendChild(emptypeInput);
        newRow.insertBefore(emptypeCell, empCell);

         // Add the Employee column with an select 2 dropdown
         var empCell = document.createElement("td");
        var empInput = document.createElement("select");
        empInput.id = "emp" + Counter;
        empInput.name = "emp" + Counter;
        // empCell.style.width = "15%";
        // empInput.style.width = "15%";
        empCell.appendChild(empInput);
        newRow.appendChild(empCell);

        var defaultOption = document.createElement("option");
        defaultOption.value = (rowData.emp_id==null?'':rowData.emp_id);
        defaultOption.text = (rowData.emp_id==null?'':rowData.emp_serviceno+"-"+rowData.emp_name);
        empInput.appendChild(defaultOption);

        employeeselct2(Counter);

         // Add the "Nonreg Emp" column with a specific width (55%)
         var non_reg_empCell = document.createElement("td");
        var non_reg_empInput = document.createElement("input");
        non_reg_empInput.type = "text";
        non_reg_empInput.id = "non_reg_emp" + Counter;
        non_reg_empInput.name = "non_reg_emp" + Counter;
        non_reg_empInput.value = rowData.non_reg_emp; 
        non_reg_empCell.appendChild(non_reg_empInput);
                // billNoCell.style.width = "55%";
        newRow.appendChild(non_reg_empCell);

                                    // Add change event listener to the emptypeInput
                                    emptypeInput.addEventListener("change", function () {
                                    if (emptypeInput.value === "Reg_Emp") {
                                        // Show "Reg Emp" in empCell and reset non_reg_empInput
                                        empCell.style.display = "block";
                                        non_reg_empCell.style.display = "none";
                                        non_reg_empInput.value = ""; // Reset non_reg_empInput
                                    } else if (emptypeInput.value === "Non_reg_Emp") {
                                        // Show "Non-reg Emp" in non_reg_empCell and reset empInput
                                        empCell.style.display = "none";
                                        non_reg_empCell.style.display = "block";
                                        empInput.value = ""; // Reset empInput
                                    } else {
                                        // Hide both empCell and non_reg_empCell and reset both inputs
                                        empCell.style.display = "none";
                                        non_reg_empCell.style.display = "none";
                                        non_reg_empInput.value = ""; // Reset non_reg_empInput
                                        empInput.value = ""; // Reset empInput
                                    }
                                });


                            // Initialize the display based on the default value
                            emptypeInput.dispatchEvent(new Event("change"));

        // Add the "Bill No" column with a specific width (55%)
        var billNoCell = document.createElement("td");
        var billNoInput = document.createElement("input");
        billNoInput.type = "text";
        billNoInput.id = "bill_no" + Counter;
        billNoInput.name = "bill_no" + Counter;
        billNoInput.value = rowData.bill_no;
        billNoCell.style.width = "100px";
        billNoInput.style.width = "100px";
        billNoCell.appendChild(billNoInput);
        // billNoCell.style.width = "55%";
        newRow.appendChild(billNoCell);

        // Add the "Description" column with a textarea
        var descriptionCell = document.createElement("td");
        var descriptionTextarea = document.createElement("textarea");
        descriptionTextarea.id = "description" + Counter;
        descriptionTextarea.name = "description" + Counter;
        descriptionCell.appendChild(descriptionTextarea);
        descriptionTextarea.value = rowData.description;
        descriptionTextarea.style.width = "100%";
        newRow.appendChild(descriptionCell);

        // Add the "Rs." column with a specific width (55%)
        var rsCell = document.createElement("td");
        var rsInput = document.createElement("input");
        rsInput.id = "rs" + Counter;
        rsInput.type = "text";
        rsInput.name = "rs" + Counter;
        rsInput.value = rowData.rs;
        rsCell.appendChild(rsInput);
        rsCell.style.width = "80px";
        rsInput.style.width = "80px";
        newRow.appendChild(rsCell);

         // Add the "float balance" column with a specific width (55%)
         var floatbalanceCell = document.createElement("td");
        var floatbalanceInput = document.createElement("input");
        floatbalanceInput.id = "floatbalance" + Counter;
        floatbalanceInput.type = "text";
        floatbalanceInput.name = "floatbalance" + Counter;
        floatbalanceInput.value = rowData.float_balance;
        floatbalanceInput.readOnly = true;
        floatbalanceInput.style.border = "none";
        floatbalanceInput.style.width = "80px";
        floatbalanceCell.style.width = "80px";
        floatbalanceCell.appendChild(floatbalanceInput);
        // rsCell.style.width = "55%";
        newRow.appendChild(floatbalanceCell);
        floatbalancrCalc(Counter);


        if(editthirdapprovelstatus==1){
        var remarkCell = document.createElement("td");
        var remarkSelect = document.createElement("select");
        remarkSelect.id = "remark" + Counter;
        remarkSelect.name = "remark" + Counter;
        

        var defaultOption = document.createElement("option");
        defaultOption.value = ""; 
        defaultOption.text = "Select Category";
        remarkSelect.appendChild(defaultOption);

        @foreach($categories as $category)
            var option{{ $category->id }} = document.createElement("option");
            option{{ $category->id }}.value = "{{ $category->id }}"; 
            option{{ $category->id }}.text = "{{ $category->pettycash_category }}";
            remarkSelect.appendChild(option{{ $category->id }});
        @endforeach

        if (rowData.category == "" || rowData.category == null) {
            defaultOption.selected = true;
                }
                else{
                    remarkSelect.value = rowData.category;
                }

        remarkCell.appendChild(remarkSelect);
        newRow.appendChild(remarkCell);
}
else{
    var remarkCell = document.createElement("td");
        var remarkSelect = document.createElement("select");
        remarkSelect.id = "remark" + Counter;
        remarkSelect.name = "remark" + Counter;
        remarkSelect.readOnly = true;
        remarkSelect.style.border = "none";
        remarkSelect.style.display = "none";


        var defaultOption = document.createElement("option");
        defaultOption.value = ""; 
        defaultOption.text = "Select Category";
        remarkSelect.appendChild(defaultOption);

        @foreach($categories as $category)
            var option{{ $category->id }} = document.createElement("option");
            option{{ $category->id }}.value = "{{ $category->id }}"; 
            option{{ $category->id }}.text = "{{ $category->pettycash_category }}";
            remarkSelect.appendChild(option{{ $category->id }});
            @endforeach

            if (rowData.category == "" || rowData.category == null) {
            defaultOption.selected = true;
                }
                else{
                    remarkSelect.value = rowData.category;
                }
       
        remarkCell.appendChild(remarkSelect);
        newRow.appendChild(remarkCell);
}

        // Add the new row to the table
        tbody.appendChild(newRow);
        updateTotal();
        rsInput.addEventListener("keyup", updateTotal);
        $('#edittablerow').val(Counter);
        Counter++;

    }

    // Function to update the total
    function updateTotal() {
    var total = 0;

    $('input[name^="rs"]').each(function () {
        var inputValue = $(this).val();
        if (!isNaN(parseFloat(inputValue))) {
            total += parseFloat(inputValue);
        }
    });

    // Update the total element
    $('#view_total').text(total.toFixed(2));
}


for (var i = 0; i < array.length; i++) {
    addRow_editform(array);
}

function addRow() {
    var Counter1 = parseInt($('#edittablerow').val())
Counter1 += 1;
var seqCounter1 = parseInt($('#edittablerow').val())
seqCounter1 += 1;
        var tbody = document.getElementById("dataTablebody");
        var newRow = document.createElement("tr");

        var seqCell = document.createElement("td");
        seqCell.textContent = seqCounter1;
        newRow.appendChild(seqCell);

        // Add the Bill Date column with an input of type date
        var billDateCell = document.createElement("td");
        var dateInput = document.createElement("input");
        dateInput.type = "date";
        dateInput.id = "bill_date" + Counter1;
        dateInput.name = "bill_date" + Counter1;
        billDateCell.appendChild(dateInput);
        newRow.appendChild(billDateCell);

        // Create a new column to the left of emptype with the select options
        var emptypeCell = document.createElement("td");
        var emptypeInput = document.createElement("select");
        emptypeInput.id = "emptype" + Counter1;
        emptypeInput.name = "emptype" + Counter1;

        // Create and append the "Reg Emp" option
        var optionRegEmp = document.createElement("option");
        optionRegEmp.value = "Reg_Emp";
        optionRegEmp.text = "Reg Emp";
        emptypeInput.appendChild(optionRegEmp);
        // Create and append the "Non-reg Emp" option
        var optionNonRegEmp = document.createElement("option");
        optionNonRegEmp.value = "Non_reg_Emp";
        optionNonRegEmp.text = "Non-reg Emp";
        emptypeInput.appendChild(optionNonRegEmp);

        emptypeCell.appendChild(emptypeInput);
        // Add the new column with select options to the left of empCell
        newRow.insertBefore(emptypeCell, empCell);

        // Add the Employee column with an select 2 dropdown
        var empCell = document.createElement("td");
        var empInput = document.createElement("select");
        empInput.id = "emp" + Counter1;
        empInput.name = "emp" + Counter1;
        // empCell.style.width = "15%";
        // empInput.style.width = "15%";
        empCell.appendChild(empInput);
        newRow.appendChild(empCell);

        employeeselct2(Counter1);

        // Add the "Nonreg Emp" column with a specific width (55%)
        var non_reg_empCell = document.createElement("td");
        var non_reg_empInput = document.createElement("input");
        non_reg_empInput.type = "text";
        non_reg_empInput.id = "non_reg_emp" + Counter1;
        non_reg_empInput.name = "non_reg_emp" + Counter1;
        non_reg_empCell.appendChild(non_reg_empInput);
                // billNoCell.style.width = "55%";
        newRow.appendChild(non_reg_empCell);

                                    // Add change event listener to the emptypeInput
                                    emptypeInput.addEventListener("change", function () {
                                    if (emptypeInput.value === "Reg_Emp") {
                                        // Show "Reg Emp" in empCell and reset non_reg_empInput
                                        empCell.style.display = "block";
                                        non_reg_empCell.style.display = "none";
                                        non_reg_empInput.value = ""; // Reset non_reg_empInput
                                    } else if (emptypeInput.value === "Non_reg_Emp") {
                                        // Show "Non-reg Emp" in non_reg_empCell and reset empInput
                                        empCell.style.display = "none";
                                        non_reg_empCell.style.display = "block";
                                        empInput.value = ""; // Reset empInput
                                    } else {
                                        // Hide both empCell and non_reg_empCell and reset both inputs
                                        empCell.style.display = "none";
                                        non_reg_empCell.style.display = "none";
                                        non_reg_empInput.value = ""; // Reset non_reg_empInput
                                        empInput.value = ""; // Reset empInput
                                    }
                                });


                            // Initialize the display based on the default value
                            emptypeInput.dispatchEvent(new Event("change"));


        // Add the "Bill No" column with a specific width (55%)
        var billNoCell = document.createElement("td");
        var billNoInput = document.createElement("input");
        billNoInput.type = "text";
        billNoInput.id = "bill_no" + Counter1;
        billNoInput.name = "bill_no" + Counter1;
        billNoCell.style.width = "100px";
        billNoInput.style.width = "100px";
        billNoCell.appendChild(billNoInput);
        // billNoCell.style.width = "55%";
        newRow.appendChild(billNoCell);

        // Add the "Description" column with a textarea
        var descriptionCell = document.createElement("td");
        var descriptionTextarea = document.createElement("textarea");
        descriptionTextarea.id = "description" + Counter1;
        descriptionTextarea.name = "description" + Counter1;
        descriptionCell.appendChild(descriptionTextarea);
        descriptionTextarea.style.width = "100%";
        newRow.appendChild(descriptionCell);

        // Add the "Rs." column with a specific width (55%)
        var rsCell = document.createElement("td");
        var rsInput = document.createElement("input");
        rsInput.id = "rs" + Counter1;
        rsInput.type = "text";
        rsInput.name = "rs" + Counter1;
        rsCell.style.width = "80px";
        rsInput.style.width = "80px";
        rsCell.appendChild(rsInput);
        // rsCell.style.width = "55%";
        newRow.appendChild(rsCell);

        // Add the "float balance" column with a specific width (55%)
        var floatbalanceCell = document.createElement("td");
        var floatbalanceInput = document.createElement("input");
        floatbalanceInput.id = "floatbalance" + Counter1;
        floatbalanceInput.type = "text";
        floatbalanceInput.name = "floatbalance" + Counter1;
        floatbalanceInput.readOnly = true;
        floatbalanceInput.style.border = "none";
        floatbalanceInput.style.width = "80px";
        floatbalanceCell.style.width = "80px";
        floatbalanceCell.appendChild(floatbalanceInput);
        // rsCell.style.width = "55%";
        newRow.appendChild(floatbalanceCell);
        floatbalancrCalc(Counter1);

        if(editthirdapprovelstatus==1){
        var remarkCell = document.createElement("td");
        var remarkSelect = document.createElement("select");
        remarkSelect.id = "remark" + Counter1;
        remarkSelect.name = "remark" + Counter1;

        var defaultOption = document.createElement("option");
        defaultOption.value = ""; 
        defaultOption.text = "Select Category";
        remarkSelect.appendChild(defaultOption);

        @foreach($categories as $category)
            var option{{ $category->id }} = document.createElement("option");
            option{{ $category->id }}.value = "{{ $category->id }}"; 
            option{{ $category->id }}.text = "{{ $category->pettycash_category }}";
            remarkSelect.appendChild(option{{ $category->id }});
        @endforeach

        remarkCell.appendChild(remarkSelect);
        newRow.appendChild(remarkCell);
}else{
    var remarkCell = document.createElement("td");
        var remarkSelect = document.createElement("select");
        remarkSelect.id = "remark" + Counter1;
        remarkSelect.name = "remark" + Counter1;
        remarkSelect.readOnly = true;
        remarkSelect.style.border = "none";
        remarkSelect.style.display = "none";

        var defaultOption = document.createElement("option");
        defaultOption.value = ""; 
        defaultOption.text = "Select Category";
        remarkSelect.appendChild(defaultOption);

        @foreach($categories as $category)
            var option{{ $category->id }} = document.createElement("option");
            option{{ $category->id }}.value = "{{ $category->id }}"; 
            option{{ $category->id }}.text = "{{ $category->pettycash_category }}";
            remarkSelect.appendChild(option{{ $category->id }});
        @endforeach

        remarkCell.appendChild(remarkSelect);
        newRow.appendChild(remarkCell);
}

        // Add the new row to the table
        tbody.appendChild(newRow);

        rsInput.addEventListener("keyup", updateTotal);
        $('#edittablerow').val(Counter1);

    }
    // Add a row when the "Add Row" button is clicked
    document.getElementById("add-editrow").addEventListener("click", addRow);
    }



    // arrovel table feilds data load
    function approvetabledata(app_array){
        var editthirdapprovelstatus = $('#editthirdapprovelstatus').val();
        var seqCounter = 1;
        var Counter = 1;

    function addRow_approveform() {
        var tbody = document.getElementById("app_dataTablebody");
        var newRow = document.createElement("tr");

        var rowData = app_array[Counter - 1];

        var seqCell = document.createElement("td");
        seqCell.textContent = seqCounter++;
        newRow.appendChild(seqCell);

        var idCell = document.createElement("td");
        var idInput = document.createElement("input");
        idInput.type = "text";
        idInput.id = "app_id" + Counter;
        idInput.name = "app_id" + Counter;
        idInput.value = rowData.id; 
        idInput.readOnly = true;
        idInput.style.border = "none";
        idInput.classList.add("d-none");
        idCell.classList.add("d-none");
        idCell.appendChild(idInput);
        newRow.appendChild(idCell);

        // Add the Bill Date column with an input of type date
        var billDateCell = document.createElement("td");
        var dateInput = document.createElement("input");
        dateInput.type = "date";
        dateInput.id = "app_bill_date" + Counter;
        dateInput.name = "app_bill_date" + Counter;
        dateInput.value = rowData.bill_date; 
        dateInput.readOnly = true;
        dateInput.style.border = "none";
        billDateCell.appendChild(dateInput);
        newRow.appendChild(billDateCell);

         // Create a new column to the left of emptype with the select options
         var emptypeCell = document.createElement("td");
        var emptypeInput = document.createElement("select");
        emptypeInput.id = "app_emptype" + Counter;
        emptypeInput.name = "app_emptype" + Counter;
        emptypeInput.readOnly = true;
        emptypeInput.style.border = "none";
        emptypeInput.style.appearance = "none";
     
        // Create and append the "Reg Emp" option
        var optionRegEmp = document.createElement("option");
        optionRegEmp.value = "Reg_Emp";
        optionRegEmp.text = "Reg Emp";
        emptypeInput.appendChild(optionRegEmp);
        // Create and append the "Non-reg Emp" option
        var optionNonRegEmp = document.createElement("option");
        optionNonRegEmp.value = "Non_reg_Emp";
        optionNonRegEmp.text = "Non-reg Emp";
        emptypeInput.appendChild(optionNonRegEmp);

        emptypeInput.value = rowData.emp_type; 
        emptypeCell.appendChild(emptypeInput);
        newRow.insertBefore(emptypeCell, empCell);

         // Add the Employee column with an select 2 dropdown
         var empCell = document.createElement("td");
        var empInput = document.createElement("select");
        empInput.id = "app_emp" + Counter;
        empInput.name = "app_emp" + Counter;
        empInput.readOnly = true;
        empInput.style.border = "none";
        empInput.style.appearance = "none";
        empCell.appendChild(empInput);
        newRow.appendChild(empCell);

        var defaultOption = document.createElement("option");
        defaultOption.value = (rowData.emp_id==null?'':rowData.emp_id);
        defaultOption.text = (rowData.emp_id==null?'':rowData.emp_serviceno+"-"+rowData.emp_name);
        empInput.appendChild(defaultOption);

         // Add the "Nonreg Emp" column with a specific width (55%)
         var non_reg_empCell = document.createElement("td");
        var non_reg_empInput = document.createElement("input");
        non_reg_empInput.type = "text";
        non_reg_empInput.id = "app_non_reg_emp" + Counter;
        non_reg_empInput.name = "app_non_reg_emp" + Counter;
        non_reg_empInput.value = rowData.non_reg_emp; 
        non_reg_empInput.readOnly = true;
        non_reg_empInput.style.border = "none";
        non_reg_empCell.appendChild(non_reg_empInput);
                // billNoCell.style.width = "55%";
        newRow.appendChild(non_reg_empCell);

                                    // Add change event listener to the emptypeInput
                                    emptypeInput.addEventListener("change", function () {
                                    if (emptypeInput.value === "Reg_Emp") {
                                        // Show "Reg Emp" in empCell and reset non_reg_empInput
                                        empCell.style.display = "block";
                                        non_reg_empCell.style.display = "none";
                                        non_reg_empInput.value = ""; // Reset non_reg_empInput
                                    } else if (emptypeInput.value === "Non_reg_Emp") {
                                        // Show "Non-reg Emp" in non_reg_empCell and reset empInput
                                        empCell.style.display = "none";
                                        non_reg_empCell.style.display = "block";
                                        empInput.value = ""; // Reset empInput
                                    } else {
                                        // Hide both empCell and non_reg_empCell and reset both inputs
                                        empCell.style.display = "none";
                                        non_reg_empCell.style.display = "none";
                                        non_reg_empInput.value = ""; // Reset non_reg_empInput
                                        empInput.value = ""; // Reset empInput
                                    }
                                });


                            // Initialize the display based on the default value
                            emptypeInput.dispatchEvent(new Event("change"));

        // Add the "Bill No" column with a specific width (55%)
        var billNoCell = document.createElement("td");
        var billNoInput = document.createElement("input");
        billNoInput.type = "text";
        billNoInput.id = "app_bill_no" + Counter;
        billNoInput.name = "app_bill_no" + Counter;
        billNoInput.value = rowData.bill_no;
        billNoInput.readOnly = true;
        billNoInput.style.border = "none";
        billNoCell.style.width = "100px";
        billNoInput.style.width = "100px";
        billNoCell.appendChild(billNoInput);
        // billNoCell.style.width = "55%";
        newRow.appendChild(billNoCell);

        // Add the "Description" column with a textarea
        var descriptionCell = document.createElement("td");
        var descriptionTextarea = document.createElement("textarea");
        descriptionTextarea.id = "app_description" + Counter;
        descriptionTextarea.name = "app_description" + Counter;
        descriptionCell.appendChild(descriptionTextarea);
        descriptionTextarea.value = rowData.description;
        descriptionTextarea.readOnly = true;
        descriptionTextarea.style.border = "none";
        descriptionTextarea.style.width = "100%";
        newRow.appendChild(descriptionCell);

        // Add the "Rs." column with a specific width (55%)
        var rsCell = document.createElement("td");
        var rsInput = document.createElement("input");
        rsInput.id = "app_rs" + Counter;
        rsInput.type = "text";
        rsInput.name = "app_rs" + Counter;
        rsInput.value = rowData.rs;
        rsCell.appendChild(rsInput);
        rsInput.readOnly = true;
        rsInput.style.border = "none";
        rsInput.style.textAlign = "right";
        rsCell.style.width = "80px";
        rsInput.style.width = "80px";
        newRow.appendChild(rsCell);

         // Add the "float balance" column with a specific width (55%)
         var floatbalanceCell = document.createElement("td");
        var floatbalanceInput = document.createElement("input");
        floatbalanceInput.id = "app_floatbalance" + Counter;
        floatbalanceInput.type = "text";
        floatbalanceInput.name = "app_floatbalance" + Counter;
        floatbalanceInput.value = rowData.float_balance;
        floatbalanceInput.readOnly = true;
        floatbalanceInput.style.border = "none";
        floatbalanceInput.style.width = "80px";
        floatbalanceCell.style.width = "80px";
        floatbalanceInput.style.textAlign = "right";
        floatbalanceCell.appendChild(floatbalanceInput);
        // rsCell.style.width = "55%";
        newRow.appendChild(floatbalanceCell);


        if(editthirdapprovelstatus==1){
        var remarkCell = document.createElement("td");
        var remarkSelect = document.createElement("select");
        remarkSelect.id = "app_remark" + Counter;
        remarkSelect.name = "app_remark" + Counter;

        var defaultOption = document.createElement("option");
        defaultOption.value = ""; 
        defaultOption.text = "Select Category";
        remarkSelect.appendChild(defaultOption);

        @foreach($categories as $category)
            var option{{ $category->id }} = document.createElement("option");
            option{{ $category->id }}.value = "{{ $category->id }}"; 
            option{{ $category->id }}.text = "{{ $category->pettycash_category }}";
            remarkSelect.appendChild(option{{ $category->id }});
        @endforeach

        if (rowData.category == "" || rowData.category == null) {
            defaultOption.selected = true;
                }
                else{
                    remarkSelect.value = rowData.category;
                }

        remarkCell.appendChild(remarkSelect);
        newRow.appendChild(remarkCell);
}

        // Add the new row to the table
        tbody.appendChild(newRow);
        updateTotal();
        $('#app_tablerow').val(Counter);
        Counter++;

    }

    // Function to update the total
    function updateTotal() {
    var total = 0;

    $('input[name^="app_rs"]').each(function () {
        var inputValue = $(this).val();
        if (!isNaN(parseFloat(inputValue))) {
            total += parseFloat(inputValue);
        }
    });

    // Update the total element
    $('#app_view_total').text(total.toFixed(2));
}


for (var i = 0; i < app_array.length; i++) {
    addRow_approveform(app_array);
}

    }



        // view table feilds data load
        function viewtabledata(view_array){
        var seqCounter = 1;
    var Counter = 1;

    function addRow_approveform() {
        var tbody = document.getElementById("view_dataTablebody");
        var newRow = document.createElement("tr");

        var rowData = view_array[Counter - 1];

        var seqCell = document.createElement("td");
        seqCell.textContent = seqCounter++;
        newRow.appendChild(seqCell);

        // Add the Bill Date column with an input of type date
        var billDateCell = document.createElement("td");
        var dateInput = document.createElement("input");
        dateInput.type = "date";
        dateInput.id = "view_bill_date" + Counter;
        dateInput.name = "view_bill_date" + Counter;
        dateInput.value = rowData.bill_date; 
        dateInput.readOnly = true;
        dateInput.style.border = "none";
        billDateCell.appendChild(dateInput);
        newRow.appendChild(billDateCell);

        // Create a new column to the left of emptype with the select options
        var emptypeCell = document.createElement("td");
        var emptypeInput = document.createElement("select");
        emptypeInput.id = "view_emptype" + Counter;
        emptypeInput.name = "view_emptype" + Counter;
        emptypeInput.readOnly = true;
        emptypeInput.style.border = "none";
        emptypeInput.style.appearance = "none";
     
        // Create and append the "Reg Emp" option
        var optionRegEmp = document.createElement("option");
        optionRegEmp.value = "Reg_Emp";
        optionRegEmp.text = "Reg Emp";
        emptypeInput.appendChild(optionRegEmp);
        // Create and append the "Non-reg Emp" option
        var optionNonRegEmp = document.createElement("option");
        optionNonRegEmp.value = "Non_reg_Emp";
        optionNonRegEmp.text = "Non-reg Emp";
        emptypeInput.appendChild(optionNonRegEmp);

        emptypeInput.value = rowData.emp_type; 
        emptypeCell.appendChild(emptypeInput);
        newRow.insertBefore(emptypeCell, empCell);

         // Add the Employee column with an select 2 dropdown
         var empCell = document.createElement("td");
        var empInput = document.createElement("select");
        empInput.id = "view_emp" + Counter;
        empInput.name = "view_emp" + Counter;
        empInput.readOnly = true;
        empInput.style.border = "none";
        empInput.style.appearance = "none";
        empCell.appendChild(empInput);
        newRow.appendChild(empCell);

        var defaultOption = document.createElement("option");
        defaultOption.value = (rowData.emp_id==null?'':rowData.emp_id);
        defaultOption.text = (rowData.emp_id==null?'':rowData.emp_serviceno+"-"+rowData.emp_name);
        empInput.appendChild(defaultOption);

         // Add the "Nonreg Emp" column with a specific width (55%)
         var non_reg_empCell = document.createElement("td");
        var non_reg_empInput = document.createElement("input");
        non_reg_empInput.type = "text";
        non_reg_empInput.id = "view_non_reg_emp" + Counter;
        non_reg_empInput.name = "view_non_reg_emp" + Counter;
        non_reg_empInput.value = rowData.non_reg_emp; 
        non_reg_empInput.readOnly = true;
        non_reg_empInput.style.border = "none";
        non_reg_empCell.appendChild(non_reg_empInput);
                // billNoCell.style.width = "55%";
        newRow.appendChild(non_reg_empCell);

                                    // Add change event listener to the emptypeInput
                                    emptypeInput.addEventListener("change", function () {
                                    if (emptypeInput.value === "Reg_Emp") {
                                        // Show "Reg Emp" in empCell and reset non_reg_empInput
                                        empCell.style.display = "block";
                                        non_reg_empCell.style.display = "none";
                                        non_reg_empInput.value = ""; // Reset non_reg_empInput
                                    } else if (emptypeInput.value === "Non_reg_Emp") {
                                        // Show "Non-reg Emp" in non_reg_empCell and reset empInput
                                        empCell.style.display = "none";
                                        non_reg_empCell.style.display = "block";
                                        empInput.value = ""; // Reset empInput
                                    } else {
                                        // Hide both empCell and non_reg_empCell and reset both inputs
                                        empCell.style.display = "none";
                                        non_reg_empCell.style.display = "none";
                                        non_reg_empInput.value = ""; // Reset non_reg_empInput
                                        empInput.value = ""; // Reset empInput
                                    }
                                });


                            // Initialize the display based on the default value
                            emptypeInput.dispatchEvent(new Event("change"));

        // Add the "Bill No" column with a specific width (55%)
        var billNoCell = document.createElement("td");
        var billNoInput = document.createElement("input");
        billNoInput.type = "text";
        billNoInput.id = "view_bill_no" + Counter;
        billNoInput.name = "view_bill_no" + Counter;
        billNoInput.value = rowData.bill_no;
        billNoInput.readOnly = true;
        billNoInput.style.border = "none";
        billNoCell.style.width = "100px";
        billNoInput.style.width = "100px";
        billNoCell.appendChild(billNoInput);
        // billNoCell.style.width = "55%";
        newRow.appendChild(billNoCell);

        // Add the "Description" column with a textarea
        var descriptionCell = document.createElement("td");
        var descriptionTextarea = document.createElement("textarea");
        descriptionTextarea.id = "view_description" + Counter;
        descriptionTextarea.name = "view_description" + Counter;
        descriptionCell.appendChild(descriptionTextarea);
        descriptionTextarea.value = rowData.description;
        descriptionTextarea.readOnly = true;
        descriptionTextarea.style.border = "none";
        descriptionTextarea.style.width = "100%";
        newRow.appendChild(descriptionCell);

        // Add the "Rs." column with a specific width (55%)
        var rsCell = document.createElement("td");
        var rsInput = document.createElement("input");
        rsInput.id = "view_rs" + Counter;
        rsInput.type = "text";
        rsInput.name = "view_rs" + Counter;
        rsInput.value = rowData.rs;
        rsCell.appendChild(rsInput);
        rsInput.readOnly = true;
        rsInput.style.border = "none";
        rsInput.style.textAlign = "right";
        rsCell.style.width = "80px";
        rsInput.style.width = "80px";
        newRow.appendChild(rsCell);

 // Add the "float balance" column with a specific width (55%)
 var floatbalanceCell = document.createElement("td");
        var floatbalanceInput = document.createElement("input");
        floatbalanceInput.id = "view_floatbalance" + Counter;
        floatbalanceInput.type = "text";
        floatbalanceInput.name = "view_floatbalance" + Counter;
        floatbalanceInput.value = rowData.float_balance;
        floatbalanceInput.readOnly = true;
        floatbalanceInput.style.border = "none";
        floatbalanceInput.style.width = "80px";
        floatbalanceCell.style.width = "80px";
        floatbalanceInput.style.textAlign = "right";
        floatbalanceCell.appendChild(floatbalanceInput);
        // rsCell.style.width = "55%";
        newRow.appendChild(floatbalanceCell);

        var remarkCell = document.createElement("td");
        var remarkSelect = document.createElement("select");
        remarkSelect.id = "view_remark" + Counter;
        remarkSelect.name = "view_remark" + Counter;
        remarkSelect.readOnly = true;
        remarkSelect.style.border = "none";
        remarkSelect.style.cssText = "appearance: none; -moz-appearance: none; -webkit-appearance: none; border: none;";

        var defaultOption = document.createElement("option");
        defaultOption.value = ""; 
        defaultOption.text = "";
        remarkSelect.appendChild(defaultOption);

        @foreach($categories as $category)
            var option{{ $category->id }} = document.createElement("option");
            option{{ $category->id }}.value = "{{ $category->id }}"; 
            option{{ $category->id }}.text = "{{ $category->pettycash_category }}";
            remarkSelect.appendChild(option{{ $category->id }});
        @endforeach

        if (rowData.category == "" || rowData.category == null) {
            defaultOption.selected = true;
                }
                else{
                    remarkSelect.value = rowData.category;
                }

        remarkCell.appendChild(remarkSelect);
        newRow.appendChild(remarkCell);


        // Add the new row to the table
        tbody.appendChild(newRow);
        updateTotal();
        Counter++;

    }

    // Function to update the total
    function updateTotal() {
    var total = 0;

    $('input[name^="view_rs"]').each(function () {
        var inputValue = $(this).val();
        if (!isNaN(parseFloat(inputValue))) {
            total += parseFloat(inputValue);
        }
    });

    // Update the total element
    $('#view_view_total').text(total.toFixed(2));
}


for (var i = 0; i < view_array.length; i++) {
    addRow_approveform(view_array);
}

    }
</script>

<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

//         $('#employee').select2({
//             width: '100%',
//             minimumInputLength: 1,
//             ajax: {
//                 url: '{!! route("getemployeeinselect2") !!}',
//                 type: 'POST',
//                 dataType: 'json',
//                 data: function (params) {
//                     return {
//                         search: params.term
//                     };
//                 },
//                 processResults: function (data) {
//                     return {
//                         results: $.map(data, function (item) {
//                             return {
//                                 text: item.service_no + ' - ' + item.emp_name_with_initial,
//                                 id: item.id
//                             };
//                         })
//                     };
//                 }
//             }
//         });

//         $('#employee').on('change', function (e) {
//     var selectedOption = $(this).select2('data')[0];
//     if (selectedOption) {
//         // Assign values to #empid and #empname
//         $('#empid').val(selectedOption.id);
//         $('#empname').val(selectedOption.text);
//     }
// });


$('#serviceno').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("pettycashserviceno") !!}',
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term,
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

        $('#serviceno').on('change', function (e) {
    var servicenoselectedOption = $(this).select2('data')[0];
    if (servicenoselectedOption) {
        // Assign values to #empid and #empname
        $('#empid').val(servicenoselectedOption.id);
        $('#empname').val(servicenoselectedOption.text);
    }
});

        $("#employee_name_div, #employee_nic_div").hide();


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
                url: '{!! route("pettycashserviceno") !!}',
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term,
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

        $('#serviceno').on('change', function (e) {
    var servicenoselectedOption = $(this).select2('data')[0];
    if (servicenoselectedOption) {
        // Assign values to #empid and #empname
        $('#empid').val(servicenoselectedOption.id);
        $('#empname').val(servicenoselectedOption.text);
    }
});

}
else if(selectedOption=='employee_name'){
    $('#employee_name').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("pettycashgetempname") !!}',
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term,
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

        $('#employee_name').on('change', function (e) {
    var employee_nameselectedOption = $(this).select2('data')[0];
    if (employee_nameselectedOption) {
        // Assign values to #empid and #empname
        $('#empid').val(employee_nameselectedOption.id);
        $('#empname').val(employee_nameselectedOption.text);
    }
});

}
else if(selectedOption=='employee_nic'){
    $('#employee_nic').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("pettycashgetempnic") !!}',
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term,
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

        $('#employee_nic').on('change', function (e) {
    var employee_nicselectedOption = $(this).select2('data')[0];
    if (employee_nicselectedOption) {
        // Assign values to #empid and #empname
        $('#empid').val(employee_nicselectedOption.id);
        $('#empname').val(employee_nicselectedOption.text);
    }
});

}
        });

    });
    
</script>

<script>
    //insert table reset
    $('#formModal').on('hidden.bs.modal', function () {
        // Reset the form
        $('#formTitle')[0].reset();

        $('#serviceno').val('').trigger('change');
        $('#employee_name').val('').trigger('change');
        $('#employee_nic').val('').trigger('change');
     // Clear the table rows in dataTablebody
    $('#dataTablebody').empty();
    $('#edittablerow').val(0);
    $('#tablerow').val(0);

        //reset table footercount
        $('#view_total').text('0');
        // Clear the result message
        $('#form_result').html('');
    });

    //approvel table reset
    $('#approveconfirmModal').on('hidden.bs.modal', function () {
        // Reset the form
        $('#approvelformTitle')[0].reset();

     // Clear the table rows in dataTablebody
    $('#app_dataTablebody').empty();
        //reset table footercount
        $('#app_view_total').text('0');

    });

     //view table reset
     $('#viewconfirmModal').on('hidden.bs.modal', function () {
        // Reset the form
        $('#viewformTitle')[0].reset();

     // Clear the table rows in dataTablebody
    $('#view_dataTablebody').empty();
        //reset table footercount
        $('#view_view_total').text('0');

    });

</script>
<script>
    function employeeselct2(Counter){
        $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#emp' + Counter).select2({
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
    }
</script>
<script>
    function assignTempInput(value){
        $('#tempfloatbalance').val(value);
    }
    function  floatbalancrCalc(Counter){
        $(document).on("keyup", '#rs' + Counter, function () {
            var rs = $('#rs' + Counter).val();
            var floatvalue = $('#pettycashfloat').val();
            if($('#floatbalance' + (Counter - 1)).length > 0){
                var prefloatbalance=$('#floatbalance' + (Counter - 1)).val();
                var newfloatbalance=prefloatbalance-rs;
                $('#floatbalance' + Counter).val(newfloatbalance)
                console.log("no");
            }else{        
                var floatbalance=floatvalue-rs;
                $('#floatbalance' + Counter).val(floatbalance);
                console.log("yes");
            }
           if(rs==''||rs==null){
            $('#floatbalance' + Counter).val('');
           }
    });

    }
</script>
@endsection