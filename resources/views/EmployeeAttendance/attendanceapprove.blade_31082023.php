@extends('layouts.app')
@section('content')
    <main>
        <div class="page-header page-header-light bg-white shadow">

                <div class="page-header-content py-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-user-plus"></i></div>
                        <span>Employee Attendance Approve</span>
                    </h1>
                </div>
        </div>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0 p-2">
                    <div class="row">
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Main Client*</label>
                            <select name="customer" id="customer" class="form-control form-control-sm"
                                required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Sub Client*</label>
                            <select name="subcustomer" id="subcustomer" class="form-control form-control-sm"
                                required>
                                <option value="">Select Sub Customer</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Client Branch*</label>
                                <select name="branch" id="branch" class="form-control form-control-sm">
                                    <option value="">Select Customer Branch</option>
                                  
                                </select>
                            </div>
                               <br>
                         
                        </div>
                        
                        <div class="col-12">
                            <hr>
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

         {{-- <!-- Modal on time off time edit  Start -->
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
     </div> --}}

     <div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-xl">
         <div class="modal-content">
             <div class="modal-header p-2">
                 <h5 class="modal-title" id="staticBackdropLabel">Approve Employee Attendance </h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                <div class="col-12">
                    <table class="table table-striped table-bordered  nowrap" id="editattendence">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Empolyee Name</th>
                                <th>Job Title</th>
                                <th>On time</th>
                                <th>Off time</th>
                            </tr>
                        </thead>
                        <tbody id="attendencelist"></tbody>
                    </table>
                </div>
            <form>
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
{{-- delete model --}}
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
        
    </main>

@endsection


@section('script')

    <script>
        $(document).ready(function(){
            $('#empattendancelist').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#empattendancelistdrop').addClass('show');
            $('#empattendaceapprove_link').addClass('active');


            $(document).on("change", "#branch", function () {

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
                        url: "/attendanceapprovelist", // Replace this with the actual URL for the AJAX request
                        type: "POST",
                        data: function (d) {
                            return $.extend({}, d, {
                                "cusid": $("#branch").val()
                            });
                        },
                    },
                    "columns": [{
                            data: 'allocation_id',
                            name: 'allocation_id'
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
                    url: '{!! route("attendanceapproveedit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_approve },
                    success: function (data) {
                      
                        $('#attendencelist').html(data.result);
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
                    url: '{!! route("attendanceapproveedit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_approve },
                    success: function (data) {
                        $('#attendencelist').html(data.result);
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
                    url: '{!! route("attendanceapproveedit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_approve },
                    success: function (data) {
                        $('#attendencelist').html(data.result);
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
                    url: '{!! route("attendanceapprove") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_hidden,
                            applevel: applevel},
                    success: function (data) {//alert(data);
                        setTimeout(function () {
                            $('#approveconfirmModal').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();
                            
                        }, 1000);
                        location.reload()
                    }
                })
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
                        url: '{!! route("attendencedelete") !!}',
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



      //Get Sub Customer
      $('#customer').change(function () {
            var customerId = $(this).val();
            if (customerId !== '') {
                $.ajax({
                    url: '/getsubcustomersattendance/' + customerId,
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
        });

        //Get Branches
        $('#subcustomer').change(function () {
            var subcustomerId = $(this).val();
            if (subcustomerId !== '') {
                $.ajax({
                    url: '/getbranchattendance/' + subcustomerId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#branch').empty().append(
                        '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#branch').append('<option value="' + branch.id + '">' +
                                branch.branch_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#branch').empty().append('<option value="">Select Branch</option>');
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