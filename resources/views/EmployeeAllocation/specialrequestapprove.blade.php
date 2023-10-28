@extends('layouts.app')
@section('content')
    <main>
        <div class="page-header page-header-light bg-white shadow">

                <div class="page-header-content py-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon" style="padding-left: 12px"><i class="fas fa-user-plus"></i></div>
                        <span>Special Request Approve</span>
                    </h1>
                </div>
        </div>
        <br>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0 p-2">
                    <div class="col-12">
                       
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
    </main>  


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
                    </div>
                    <div class="form-row mb-1">
                        <div class="col-6">
                            <label class="small font-weight-bold text-dark">Region*</label>
                            <input type="text" class="form-control form-control-sm" placeholder="" name="app_region" id="app_region"  readonly>
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
                                    <th>Empolyee Region</th>
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
    

@endsection


@section('script')

    <script>
        $(document).ready(function(){
            
            $('#empallocationlist').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#empallocationlistdrop').addClass('show');
            $('#specialrequestapprove_link').addClass('active');

            
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{!! route('specialrequestslist') !!}",
                   
                },

                columns: [
                    { data: 'id', name: 'id' },
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
                    {data: 'action', name: 'action', orderable: false, searchable: false,
                    render: function (data, type, row) {
                        return '<div style="text-align: right;">' + data + '</div>';
                    }
                },
                ],
              

                "destroy": true,
                "order": [
                    [0, "desc"]
                ]
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
                    url: '{!! route("specialrequestsedit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_approve },
                    success: function (data) {
                        $('#app_customername').val(data.result.mainData.name);
                        $('#app_subcustomer').val(data.result.mainData.sub_name);
                        $('#app_branch').val(data.result.mainData.branch_name);
                        $('#app_hollyday').val(data.result.mainData.holiday);
                        $('#app_shift').val(data.result.mainData.shift_name);
                        $('#app_region').val(data.result.mainData.subregion);
                        $('#app_date').val(data.result.mainData.date);
                        $('#app_requestdetaillist').html(data.result.requestdata);
                        $('#app_Detilslist').html(data.result.detaildata);
                    
                      
                        

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
                    url: '{!! route("specialrequestsedit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_approve },
                    success: function (data) {
                        $('#app_customername').val(data.result.mainData.name);
                        $('#app_subcustomer').val(data.result.mainData.sub_name);
                        $('#app_branch').val(data.result.mainData.branch_name);
                        $('#app_hollyday').val(data.result.mainData.holiday);
                        $('#app_shift').val(data.result.mainData.shift_name);
                        $('#app_region').val(data.result.mainData.subregion);
                        $('#app_date').val(data.result.mainData.date);
                        $('#app_requestdetaillist').html(data.result.requestdata);
                        $('#app_Detilslist').html(data.result.detaildata);
                      

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
                    url: '{!! route("specialrequestsedit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_approve },
                    success: function (data) {
                        $('#app_customername').val(data.result.mainData.name);
                        $('#app_subcustomer').val(data.result.mainData.sub_name);
                        $('#app_branch').val(data.result.mainData.branch_name);
                        $('#app_hollyday').val(data.result.mainData.holiday);
                        $('#app_shift').val(data.result.mainData.shift_name);
                        $('#app_region').val(data.result.mainData.subregion);
                        $('#app_date').val(data.result.mainData.date);
                        $('#app_requestdetaillist').html(data.result.requestdata);
                        $('#app_Detilslist').html(data.result.detaildata);
                      

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
                    url: '{!! route("specialrequestsapprove") !!}',
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




        });
  
       
        
    </script>

@endsection