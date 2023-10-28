@extends('layouts.app')
@section('content')
    <main>
        <div class="page-header page-header-light bg-white shadow">

                <div class="page-header-content py-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-user-plus"></i></div>
                        <span>Employee Attendance</span>
                    </h1>
                </div>
        </div>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0 p-2">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Customer Branch*</label>
                                <select name="customer" id="customer" class="form-control form-control-sm">
                                    <option value="">Select Customer Branch</option>
                                    @foreach($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->branch_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                               <br>
                         
                        </div>
                        <div class="col-12">
                            <h3>Allocated List</h3>
                            <hr class="border-dark">
                        </div>
                        <div class="col-12">
                            <table class="table table-striped table-bordered table-sm small" id="dataTable">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Sub Customer</th>
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
        </div>
 
        <br>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0 p-2">
                    <div class="col-12">
                        <h3>Attendance Marked List</h3>
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">
                        <table class="table table-striped table-bordered table-sm small" id="dataTable2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sub Customer</th>
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
        


        <!-- Modal on time  Start -->
        <div class="modal fade" id="formModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header p-2">
                        <h5 class="modal-title" id="staticBackdropLabel">Add Attendance</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                           
                                <span id="form_result"></span>
                                <form method="post" id="formTitle" class="form-horizontal">
                                    {{ csrf_field() }}

                                            <input type="hidden" id="cusid" name="cusid" class="form-control form-control-sm" >
                                             <input type="hidden" id="subcustomerid" name="subcustomerid" class="form-control form-control-sm">
                                             <input type="hidden" id="branchid" name="branchid" class="form-control form-control-sm">
                                            <input type="hidden" id="shift_id" name="shift_id" class="form-control form-control-sm">
                                            <input type="hidden" id="hollyday_id" name="hollyday_id" class="form-control form-control-sm">
                                            <input type="hidden" id="allocationid" name="allocationid" class="form-control form-control-sm">
                                      
                                            <div class="col-4">
                                                <input type="date" id="date" name="date" class="form-control form-control-sm">
                                                <br>
                                                <label><input type="checkbox" name="selectAll" id="selectAllDomainList" /> Check All</label>
                                            </div>
                                            <div class="col-12">
                                            <table class="table table-striped table-bordered  nowrap" id="tableonattendence">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Empolyee Name</th>
                                                        <th>Job Title</th>
                                                        <th>Shift On time</th>
                                                        <th>Shift Out time</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="onattendencelist"></tbody>
                                            </table>
                                        </div>
                                    
                                    <div class="form-group mt-2">
                                        <button type="button" name="btncreateorder" id="btncreateorder" class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i class="fas fa-plus"></i>&nbsp;Add Attendance</button>
                                        <input type="hidden" name="action" id="action" value="In" />
                                        <input type="hidden" name="hidden_id" id="hidden_id" />
                                    </div>
                                </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

         <!-- Modal on time off time edit  Start -->
         <div class="modal fade" id="formModaledit" data-backdrop="static" data-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered modal-xl">
             <div class="modal-content">
                 <div class="modal-header p-2">
                     <h5 class="modal-title" id="staticBackdropLabel">Edit Employee Attendance</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <div class="modal-body">
                     <div class="row">
                        
                             <span id="form_result"></span>
                             <form method="post" id="formTitle" class="form-horizontal">
                                 {{ csrf_field() }}
                                         <div class="col-12">
                                         <table class="table table-striped table-bordered  nowrap" id="editattendence">
                                             <thead>
                                                 <tr>
                                                     <th>#</th>
                                                     <th>Empolyee Name</th>
                                                     <th>Job Title</th>
                                                     <th>On time</th>
                                                     <th>New On time</th>
                                                     <th>Off time</th>
                                                     <th>New Offs time</th>
                                                 </tr>
                                             </thead>
                                             <tbody id="attendencelist"></tbody>
                                         </table>
                                     </div>
                                 
                                 <div class="form-group mt-2">
                                     <button type="button" name="btnupdate" id="btnupdate" class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i class="fas fa-plus"></i>&nbsp;Edit Attendance</button>
                                     <input type="hidden" name="hidden_idedit" id="hidden_idedit" />
                                    </div>
                             </form>
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
            $('#empattendancelist').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#empattendancelistdrop').addClass('show');
            $('#empattendace_link').addClass('active');

            $(':checkbox[name=selectAll]').click(function () {
                $(':checkbox').prop('checked', this.checked);
            });

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
                        url: "/allocationlist", // Replace this with the actual URL for the AJAX request
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
                            data: 'subcustomer',
                            name: 'subcustomer'
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
                        [0, "desc"]
                    ]
                });
                $('#dataTable2').DataTable({
                    "destroy": true,
                    "processing": true,
                    "serverSide": true,
                    ajax: {
                        url: "/attendancelist", // Replace this with the actual URL for the AJAX request
                        type: "POST",
                        data: function (d) {
                            return $.extend({}, d, {
                                "cusid": $("#customer").val()
                            });
                        },
                    },
                    "columns": [{
                            data: 'allocation_id',
                            name: 'allocation_id'
                        },
                        {
                            data: 'cussub',
                            name: 'cussub'
                        },
                        {
                            data: 'branch',
                            name: 'branch'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'shift',
                            name: 'shift'
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
            $(document).on('click', '.btnAdd', function () {
                var id = $(this).attr('id');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })

                $('#form_result').html('');
                $.ajax({
                    url: '{!! route("attendenceonlist") !!}',
                    dataType: "json",
                     type: "POST",
                       data: {id: id  },
                    success: function (data) {

                        $('#cusid').val(data.result.mainData.customer_id);
                        $('#subcustomerid').val(data.result.mainData.subcustomer_id);
                        $('#branchid').val(data.result.mainData.customerbranch_id);
                        $('#hollyday_id').val(data.result.mainData.holiday_id);
                        $('#shift_id').val(data.result.mainData.shift_id);
                        $('#date').val(data.result.mainData.date);
                        $('#onattendencelist').html(data.result.detaildata);
                        $('#allocationid').val(id);
                         $('#formModal').modal('show');
                    }
                })
              
            });

    

            $('#btncreateorder').click(function () {
           
                $('#btncreateorder').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Add Attendance');



                 var tbody = $('#tableonattendence tbody');
                if (tbody.children().length > 0) {
                    jsonObj = []

                    $("#tableonattendence tbody tr").each(function () {
                        item = {}
                        //var tablelist = $("#cashdailytbl tbody input[type=checkbox]:checked");

                        $(this).find('td').each(function (col_idx) {
                            var r='';
                            if(col_idx==0){
                                var c=$(this).find('input[type="checkbox"]');
                                r=$(c).is(":checked")?1:0;
                            }
                            else if (col_idx === 3) {
                                        // This handles the 'empontime' column (datetime-local input field)
                                        var datetimeLocalInput = $(this).find('input[type="datetime-local"]');
                                        r = datetimeLocalInput.val(); // Get the value of the datetime-local input field
                                    }
                                    else if (col_idx === 4) {
                                        // This handles the 'empontime' column (datetime-local input field)
                                        var datetimeLocalInput2 = $(this).find('input[type="datetime-local"]');
                                        r = datetimeLocalInput2.val(); // Get the value of the datetime-local input field
                                    }
                            else{
                                r=$(this).text();
                            }
                            item["col_" + (col_idx + 1)] = r;
                        });
                        jsonObj.push(item);
                    });
                }


                var cusid = $('#cusid').val();
                var allocationid = $('#allocationid').val();
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
                        recordID: allocationid,
                        customer: cusid,
                        subcustomer: subcusidid,
                        branch: branchid,
                        shift: shift_id,
                        hollyday: hollyday_id,
                        date: date,
                        hidden_id: hidden_id
                    },
                    url:'{!! route("attendenceontimeinsert") !!}',
                    success: function (result) {
                        if (result.status == 1) {
                            $('#formModal').modal('hide');
                            setTimeout(function () {
                                location.reload();
                            }, 500);
                        }
                        action(result.action);
                    }
                });
            

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
                    url: '{!! route("attendenceedit") !!}',
                    type: 'POST',
                    dataType: "json",
                    data: {id: id },
                    success: function (data) {
                        $('#attendencelist').html(data.result);
                        $('#hidden_idedit').val(id);
                        $('#formModaledit').modal('show');
                    }
                })
            });


            // attendance update function 

            $('#btnupdate').click(function () {
               
                $('#btnupdate').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Update Attendance');



                 var tbody = $('#editattendence tbody');
                if (tbody.children().length > 0) {
                    jsonObj = []

                    $("#editattendence tbody tr").each(function () {
                        item = {}
                        //var tablelist = $("#cashdailytbl tbody input[type=checkbox]:checked");

                        $(this).find('td').each(function (col_idx) {
                            var r='';
                       
                          if (col_idx === 4) {
                              // This handles the 'empontime' column (datetime-local input field)
                              var datetimeLocalInput = $(this).find('input[type="datetime-local"]');
                              r = datetimeLocalInput.val(); // Get the value of the datetime-local input field
                          } else if (col_idx === 6) {
                              // This handles the 'empontime' column (datetime-local input field)
                              var datetimeLocalInput = $(this).find('input[type="datetime-local"]');
                              r = datetimeLocalInput.val(); // Get the value of the datetime-local input field
                          } else {
                              r = $(this).text();
                          }
                            item["col_" + (col_idx + 1)] = r;
                        });
                        jsonObj.push(item);
                    });
                }

                var hidden_id = $('#hidden_idedit').val();

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
                        hidden_id: hidden_id
                    },
                    url: '{!! route("attendenceupdate") !!}',
                    success: function (result) {
                        if (result.status == 1) {
                            $('#formModal').modal('hide');
                            setTimeout(function () {
                                location.reload();
                            }, 500);
                        }
                        action(result.action);
                    }
                });
            

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