@extends('layouts.app')

@section('content')

    <main>
        <div class="page-header page-header-light bg-white shadow">
            <div class="container-fluid">
                <div class="page-header-content py-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-users"></i></div>
                        <span>Client</span>
                    </h1>
                </div>
        </div>
        </div>
        <div class="container-fluid mt-2 p-0 p-2">
            <div class="card">
                <div class="card-body p-0 p-2">
                    <div class="row">
                        <div class="col-12">
                                <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record" id="create_record"><i class="fas fa-plus mr-2"></i>Add Client</button>
                        </div>
                        <div class="col-12">
                            <hr class="border-dark">
                        </div>
                        <div class="col-12">
                            <div class="center-block fix-width scroll-inner">
                            <table class="table table-striped table-bordered table-sm small nowrap display" style="width: 100%;" id="dataTable">
                                <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>Category</th>
                                    <th>Name</th>
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
                        <h5 class="modal-title" id="staticBackdropLabel">Add Client</h5>
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
                                        <label class="small font-weight-bold text-dark">Category</label>
                                        <select name="category_id" id="category_id" class="form-control form-control-sm" required>
                                            <option value="">Select Category</option>
                                            @foreach($category as $categories)
                                                <option value="{{$categories->id}}">{{$categories->category}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col-12">
                                            <label class="small font-weight-bold text-dark">Name*</label>
                                            <input type="text" name="cusname" id="cusname" class="form-control form-control-sm" required/>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Area</label>
                                        <select name="subregion_id" id="subregion_id" class="form-control form-control-sm">
                                            <option value="">Select Area</option>
                                            @foreach($subregions as $region)
                                                <option value="{{$region->id}}">{{$region->subregion}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                    <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Address*</label>
                                        <input type="text" name="line_1" id="line_1" class="form-control form-control-sm" placeholder="Line 1"  />
                                        <br>
                                        <input type="text" name="line_2" id="line_2" class="form-control form-control-sm" placeholder="Line 2" />
                                        <br>
                                        <input type="text" name="line_3" id="line_3" class="form-control form-control-sm" placeholder="Line 2" />
                                    </div>
                                </div>
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

        <div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header p-2">
                        <h5 class="modal-title" id="staticBackdropLabel">Approve Client Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-row mb-1">
                                <div class="col-12">
                                <label class="small font-weight-bold text-dark">Category</label>
                                <select name="app_category_id" id="app_category_id" class="form-control form-control-sm" readonly>
                                    <option value="">Select Category</option>
                                    @foreach($category as $categories)
                                        <option value="{{$categories->id}}">{{$categories->category}}</option>
                                    @endforeach
                                </select>
                            </div>
                            </div>

                            <div class="form-row mb-1">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Name*</label>
                                    <input type="text" name="app_cusname" id="app_cusname" class="form-control form-control-sm" readonly/>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col-12">
                                <label class="small font-weight-bold text-dark">Area</label>
                                <select name="app_subregion_id" id="app_subregion_id" class="form-control form-control-sm" readonly>
                                    <option value="">Select Area</option>
                                    @foreach($subregions as $region)
                                        <option value="{{$region->id}}">{{$region->subregion}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="form-row mb-1">
                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">Address*</label>
                                <input type="text" name="app_line_1" id="app_line_1" class="form-control form-control-sm" placeholder="Line 1"  readonly/>
                                <br>
                                <input type="text" name="app_line_2" id="app_line_2" class="form-control form-control-sm" placeholder="Line 2" readonly/>
                                <br>
                                <input type="text" name="app_line_3" id="app_line_3" class="form-control form-control-sm" placeholder="Line 2" readonly/>
                            </div>
                        </div>
                        
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

        <!-- Modal Area End -->
    </main>

@endsection


@section('script')

    <script>
        $(document).ready(function(){

            $('#clientmanagement').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#collapscustomer').addClass('show');
            $('#customerlist').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#customerlistdrop').addClass('show');
            $('#customer_link').addClass('active');

            var customerStatusactive = '{{ route("customerstatus", ["id" => "cus_id", "status" => 1]) }}';
           var customerStatusdeactive = '{{ route("customerstatus", ["id" => "cus_id", "status" => 2]) }}';
           var customerContact = '{{ route("cuscontact", ["id" => "cus_id"]) }}';

            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: scripturl + '/customerlist.php',
                    type: "POST",
                },
                "order": [[ 0, "desc" ]],
                "columns": [
                    {
                        "data": "id"
                    },
                    {
                        "data": "category"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "targets": -1,
                        "className": 'text-right',
                        "data": null,
                        "render": function(data, type, full) {


                            var button='';
                            if (full['approve_status'] == 0) {
                                if (full['approve_01'] == 0) {
                                    button+='<button name="appL1" id="' + full['id'] + '" class="appL1 btn btn-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>&nbsp;';
                                } else if (full['approve_01'] == 1 && full['approve_02'] == 0) {
                                    button+='<button name="appL2" id="' + full['id'] + '" class="appL2 btn btn-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>&nbsp;';
                                } else if (full['approve_02'] == 1 && full['approve_03'] == 0) {
                                    button+='<button name="appL3" id="' + full['id'] + '" class="appL3 btn btn-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>&nbsp;';
                                }
                            }

                            button+='<button class="btn btn-primary btn-sm edit mr-1" id="'+full['id']+'"><i class="fas fa-pen"></i></button>';
                            button+='<a href="' + customerContact.replace('cus_id', full['id'])+'" target="_self" class="btn btn-info btn-sm mr-1 "><i class="fas fa-phone"></i></a>';
                            if(full['status']==1){
                                button+='<a href="' + customerStatusdeactive.replace('cus_id', full['id'])+'" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1"><i class="fas fa-check"></i></a>';
                            }else{
                                button+='<a href="' + customerStatusactive.replace('cus_id', full['id'])+'" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1"><i class="fas fa-times"></i></a>';
                            }
                            button+='<button name="delete" id="'+ full['id'] + '" class="delete btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                            
                            return button;
                        }
                    }
                ],
                drawCallback: function(settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

            $('#create_record').click(function(){
                $('.modal-title').text('Add New Customer');
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
                    action_url = "{{ route('customerinsert') }}";
                }
                if ($('#action').val() == 'Edit') {
                    action_url = "{{ route('customerupdate') }}";
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
                    url: '{!! route("customeredit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id },
                    success: function (data) {
                        $('#cusname').val(data.result.name);
                        $('#subregion_id').val(data.result.subregion_id);
                        $('#category_id').val(data.result.category_id);
                        $('#line_1').val(data.result.address1);
                        $('#line_2').val(data.result.address2);
                        $('#line_3').val(data.result.city);
                        

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
                    url: '{!! route("customerdelete") !!}',
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
                    url: '{!! route("customeredit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_approve },
                    success: function (data) {
                        $('#app_cusname').val(data.result.name);
                        $('#app_subregion_id').val(data.result.subregion_id);
                        $('#app_category_id').val(data.result.category_id);
                        $('#app_line_1').val(data.result.address1);
                        $('#app_line_2').val(data.result.address2);
                        $('#app_line_3').val(data.result.city);
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
                    url: '{!! route("customeredit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_approve },
                    success: function (data) {
                        $('#app_cusname').val(data.result.name);
                        $('#app_subregion_id').val(data.result.subregion_id);
                        $('#app_category_id').val(data.result.category_id);
                        $('#app_line_1').val(data.result.address1);
                        $('#app_line_2').val(data.result.address2);
                        $('#app_line_3').val(data.result.city);
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
                    url: '{!! route("customeredit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_approve },
                    success: function (data) {
                        $('#app_cusname').val(data.result.name);
                        $('#app_subregion_id').val(data.result.subregion_id);
                        $('#app_category_id').val(data.result.category_id);
                        $('#app_line_1').val(data.result.address1);
                        $('#app_line_2').val(data.result.address2);
                        $('#app_line_3').val(data.result.city);
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
                    url: '{!! route("customerapprove") !!}',
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
        });
        function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }

    </script>

@endsection
