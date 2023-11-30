@extends('layouts.app')

@section('content')

    <main>
        <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
                <div class="page-header-content py-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-users"></i></div>
                        <span>Supplier</span>
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
                            @if(in_array('Supplier-create',$userPermissions))
                                <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record" id="create_record"><i class="fas fa-plus mr-2"></i>Add Suppliers</button>
                                @endif
                            </div>
                        <div class="col-12">
                            <hr class="border-dark">
                        </div>
                        <div class="col-12">
                        <div class="center-block fix-width scroll-inner">
                            <table class="table table-striped table-bordered table-sm small nowrap display" style="width: 100%" id="dataTable">
                                <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Approval Status</th>
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
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header p-2">
                        <h5 class="modal-title" id="staticBackdropLabel">Add Supplier</h5>
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
                                        <div class="col-12">
                                            <label class="small font-weight-bold text-dark">Name*</label>
                                            <input type="text" name="supname" id="supname" class="form-control form-control-sm" required/>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Address*</label>
                                        <input type="text" name="line_1" id="line_1" class="form-control form-control-sm" placeholder="Line 1"  required/>
                                        <br>
                                        <input type="text" name="line_2" id="line_2" class="form-control form-control-sm" placeholder="Line 2" required/>
                                        <br>
                                        <input type="text" name="city" id="city" class="form-control form-control-sm" placeholder="City" required/>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Payment Terms*</label>
                                        <input type="text" name="paymentterms" id="paymentterms" class="form-control form-control-sm" required/>
                                    </div>
                                </div>
                                {{-- <div class="form-row mb-1">
                                    <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Contact No</label>
                                    <input type="text" name="contact" id="contact" class="form-control form-control-sm"   required/>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col-12">
                                <label class="small font-weight-bold text-dark">Email</label>
                                <input type="email" name="email" id="email" class="form-control form-control-sm"  required/>
                            </div>
                        </div> --}}

                                    <div class="form-group mt-3">
                                        <button type="submit" name="action_button" id="action_button" class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i class="fas fa-plus"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="action" id="action" value="Add" />
                                    <input type="hidden" name="hidden_id" id="hidden_id" />
                                  
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

        {{-- reject confirm and comment msg --}}
        <div class="modal fade" id="rejectModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
       <div class="modal-dialog modal-dialog-centered">
           <div class="modal-content">
               <div class="modal-header p-2">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <div class="modal-body">
                   <div class="row">
                       <div class="col text-center">
                           <h4 class="font-weight-normal">Are you sure you want to Reject this Record?</h4>
                       </div>
                   </div>
                   <div class="row">
                    <div class="col text-center">
                      <textarea class="form-control form-control-sm" id="rejectcomment" name="rejectcomment"></textarea>
                    </div>
                </div>
               </div>
               <div class="modal-footer p-2">
                   <button type="button" name="reject_ok_button" id="reject_ok_button" class="btn btn-danger px-3 btn-sm">OK</button>
                   <button type="button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Cancel</button>
               </div>
           </div>
       </div>
   </div>

        <div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header p-2">
                        <h5 class="modal-title" id="staticBackdropLabel">Approve Customer Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                          
                            <div class="form-row mb-1">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Name*</label>
                                    <input type="text" name="app_supname" id="app_supname" class="form-control form-control-sm" readonly/>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">Address*</label>
                                <input type="text" name="app_line_1" id="app_line_1" class="form-control form-control-sm" placeholder="Line 1"  readonly/>
                                <br>
                                <input type="text" name="app_line_2" id="app_line_2" class="form-control form-control-sm" placeholder="Line 2" readonly/>
                                <br>
                                <input type="text" name="app_city" id="app_city" class="form-control form-control-sm" placeholder="City" readonly/>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">Payment Terms*</label>
                                <input type="text" name="app_paymentterms" id="app_paymentterms" class="form-control form-control-sm" readonly/>
                            </div>
                        </div>
                        {{-- <div class="form-row mb-1">
                            <div class="col-12">
                            <label class="small font-weight-bold text-dark">Contact No</label>
                            <input type="text" name="app_contact" id="app_contact" class="form-control form-control-sm"   readonly/>
                        </div>
                    </div>
                    <div class="form-row mb-1">
                        <div class="col-12">
                        <label class="small font-weight-bold text-dark">Email</label>
                        <input type="email" name="app_email" id="app_email" class="form-control form-control-sm"  readonly/>
                    </div>
                </div> --}}
                            <input type="hidden" name="hidden_id" id="hidden_id" />
                            <input type="hidden" name="app_level" id="app_level" value="1" />

                        </form>
                    </div>
                    <div class="modal-footer p-2">
                        <button type="button" name="approve_button" id="approve_button"
                            class="btn btn-warning px-3 btn-sm">Approve</button>
                        <button type="button" name="reject_button" id="reject_button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Reject</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Area End -->
    </main>

@endsection


@section('script')

    <script>
        $(document).ready(function(){
            $('#collapseCorporation').addClass('show');
            $('#collapsgrninfo').addClass('show');
            $('#supplier_link').addClass('active');

            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{!! route('supplierlist') !!}",
                   
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'supplier_name', name: 'supplier_name' },
                    {
                        data: 'address2',
                        name: 'address2',
                        render: function(data, type, row) {
                        return row.address1 + ', ' + row.address2 + ', ' + row.city;
                        }
                    },  
                    {
                    data: 'approve_status',
                    name: 'approve_status',
                    render: function (data, type, row) {
                        if (data == 0) {
                            return '<i style="color:red" class="fas fa-times"></i>&nbsp;&nbsp Pending';
                        } else if (data == 1) {
                            return '<i style="color:green" class="fas fa-check"></i>&nbsp;&nbsp Approved';
                        } else {
                            return '<i style="color:red" class="fas fa-ban"></i>&nbsp;&nbsp Reject';
                        }
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
                },
                ],
                "bDestroy": true,
                "order": [
                    [2, "desc"]
                ]
            });

            $('#create_record').click(function(){
                $('.modal-title').text('Add New Supplier');
                $('#action_button').html('Add');
                $('#action').val('Add');
                $('#form_result').html('');
                $('#formTitle')[0].reset();

                $('#formModal').modal('show');
            });

            $('#formTitle').on('submit', function(event){
                event.preventDefault();
                var action_url = '';

                if ($('#action').val() == 'Add') {
                    action_url = "{{ route('supplierinsert') }}";
                }
                if ($('#action').val() == 'Edit') {
                    action_url = "{{ route('supplierupdate') }}";
                }

                $.ajax({
                    url: action_url,
                    method: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function (data) {//alert(data);
                        var html = '';
                        if (data.errors) {
                            html = '<div class="alert alert-danger">';
                            for (var count = 0; count < data.errors.length; count++) {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '</div>';
                        }
                        if (data.success) {
                            html = '<div class="alert alert-success">' + data.success + '</div>';
                            $('#formTitle')[0].reset();
                            //$('#titletable').DataTable().ajax.reload();
                            location.reload()
                        }
                        $('#form_result').html(html);
                    }
                });
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
                    url: '{!! route("supplieredit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id },
                    success: function (data) {
                        $('#supname').val(data.result.supplier_name);
                        $('#line_1').val(data.result.address1);
                        $('#line_2').val(data.result.address2);
                        $('#city').val(data.result.city);
                        $('#paymentterms').val(data.result.payment_terms);
                        // $('#contact').val(data.result.contact_no);
                        // $('#email').val(data.result.email);
                        

                        // var valueToCheck = data.result.pay_by;
                       
                        // if (valueToCheck == 1 ) {
                        //     $('#company').prop('checked', true);
                        // } else {
                        //      $('#branch').prop('checked', true);
                        // }

                        $('#hidden_id').val(id);
                        $('.modal-title').text('Edit Customer');
                        $('#action_button').html('Edit');
                        $('#action').val('Edit');
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
                    url: '{!! route("supplierdelete") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: user_id },
                    beforeSend: function () {
                        $('#ok_button').text('Deleting...');
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
                    url: '{!! route("supplieredit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_approve },
                    success: function (data) {
                        $('#app_supname').val(data.result.supplier_name);
                        $('#app_line_1').val(data.result.address1);
                        $('#app_line_2').val(data.result.address2);
                        $('#app_city').val(data.result.city);
                        $('#app_paymentterms').val(data.result.payment_terms);
                        // $('#app_contact').val(data.result.contact_no);
                        // $('#app_email').val(data.result.email);
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
                    url: '{!! route("supplieredit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_approve },
                    success: function (data) {
                        $('#app_supname').val(data.result.supplier_name);
                        $('#app_line_1').val(data.result.address1);
                        $('#app_line_2').val(data.result.address2);
                        $('#app_city').val(data.result.city);
                        $('#app_paymentterms').val(data.result.payment_terms);
                        // $('#app_contact').val(data.result.contact_no);
                        // $('#app_email').val(data.result.email);
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
                    url: '{!! route("supplieredit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_approve },
                    success: function (data) {
                        $('#app_supname').val(data.result.supplier_name);
                        $('#app_line_1').val(data.result.address1);
                        $('#app_line_2').val(data.result.address2);
                        $('#app_city').val(data.result.city);
                        $('#app_paymentterms').val(data.result.payment_terms);
                        // $('#app_contact').val(data.result.contact_no);
                        // $('#app_email').val(data.result.email);
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
                    url: '{!! route("supplierapprove") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_hidden,
                            applevel: applevel },
                    success: function (data) {//alert(data);
                        setTimeout(function () {
                            $('#approveconfirmModal').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();
                            alert('Data Approved');
                        }, 2000);
                        location.reload()
                    }
                })
            });

            $(document).on('click', '#reject_button', function () {
                $('#rejectModal').modal('show');
            });
          
            $('#reject_ok_button').click(function () {
                var reject_id = $('#hidden_id').val();
                var reject_comment = $('#rejectcomment').val();
                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                    $.ajax({
                url: '{!! route("supplierreject") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: reject_id,
                    comment: reject_comment,
                },
                success: function (data) { //alert(data);
                    setTimeout(function () {
                        $('#approveconfirmModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        // alert('Data Rejected');
                    }, 2000);
                    location.reload()
                }
            })
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
