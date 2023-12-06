@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
            @include('layouts.corporate_nav_bar')
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0 p-2">
                <div class="row">
                    <div class="col-12">
                        @if(in_array('Fixedasset-create',$userPermissions))
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record"
                            id="create_record"><i class="fas fa-plus mr-2"></i>Add Fixed Asset</button>
                            @endif
                    </div>
                    <div class="col-12">
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">
                        <div class="center-block fix-width scroll-inner">
                            <table class="table table-striped table-bordered table-sm small nowrap display" style="width: 100%"
                                id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Code </th>
                                        <th>Category</th>
                                        <th>Particular</th>
                                        <th>Employee Name</th>
                                        <th>VSO Region</th>
                                        <th>Department</th>
                                        <th>Client Branch</th>
                                        <th>Opening value at cost</th>
                                        <th>Date of purchase</th>
                                        <th>Rate</th>
                                        <th>Additions/ Deletions</th>
                                        <th>Closing vlaue at cost</th>
                                        <th>Acc.Depreciation AS ON 31/12/2022</th>
                                        <th>Depreciation For 2023</th>
                                        <th>Acc.Depreciation AS ON 31/12/2023</th>
                                        <th>Written Down Value AS ON 31/12/2023</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th></th>
                                      <th class="text-right"></th>
                                      <th colspan="8"></th>
                                    </tr>
                                  </tfoot>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Add Fixed Asset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <span id="form_result"></span>
                            <form method="post" id="formTitle" class="form-horizontal">
                                {{ csrf_field() }}
                                <h3>Fixed Asset</h3>
                                <div class="form-row mb-1">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Code*</label>
                                        <input type="text" id="code" name="code" class="form-control form-control-sm"
                                            required readonly>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Asset Category*</label>
                                        <select name="asset_category" id="asset_category" class="form-control form-control-sm"
                                            required onchange="getItemCode();">
                                            <option value="">Select Category</option>
                                            @foreach($assetcategories as $assetcategorie)
                                            <option value="{{$assetcategorie->id}}">{{$assetcategorie->asset_category}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Particular*</label>
                                        <select name="particular" id="particular" class="form-control form-control-sm"
                                        required>
                                        <option value="">Select Particular</option>
                                    </select>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Employees*</label>
                                        <select name="employee" id="employee" class="form-control form-control-sm" required>
                                            <option value="">Select Employees</option>
                                        </select>          
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">VSO Region*</label>
                                        <input type="text" id="region" name="region" class="form-control form-control-sm"
                                             readonly>  
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Department*</label>
                                       <input type="text" id="department" name="department" class="form-control form-control-sm"
                                             readonly>        
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Client Branch*</label>
                                        <input type="text" id="clientbranch" name="clientbranch" class="form-control form-control-sm"
                                             readonly> 
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Opening Value At Cost*</label>
                                        <input type="text" id="opening_value" name="opening_value" class="form-control form-control-sm"
                                            >
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Date of Purchase*</label>
                                        <input type="date" id="dateofpurchase" name="dateofpurchase" class="form-control form-control-sm"
                                            >
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Rate*</label>
                                        <input type="text" id="rate" name="rate" class="form-control form-control-sm"
                                            >
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Additions/Deletions*</label>
                                        <input type="text" id="addition_deletion" name="addition_deletion" class="form-control form-control-sm"
                                            >
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Closing Value At Cost*</label>
                                        <input type="text" id="closing_value" name="closing_value" class="form-control form-control-sm"
                                            >
                                    </div>
                                </div>
                                <hr>
                                <h3>Depreciation</h3>
                                <div class="form-row mb-1">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Acc.Depreciation AS ON 31/12/2022*</label>
                                        <input type="text" id="acc_dep_2022" name="acc_dep_2022" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Depreciation For 2023*</label>
                                        <input type="text" id="dep_2023" name="dep_2023" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Acc.Depreciation AS ON 31/12/2023*</label>
                                        <input type="text" id="acc_dep_2023" name="acc_dep_2023" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Written Down Value AS ON 31/12/2023*</label>
                                        <input type="text" id="writtendown_2023" name="writtendown_2023" class="form-control form-control-sm">
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
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="approvelmodal-title" id="staticBackdropLabel">Approve Fixed Asset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <h3>Fixed Asset</h3>
                                <div class="form-row mb-1">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Code*</label>
                                        <input type="text" id="app_code" name="app_code" class="form-control form-control-sm"
                                            required readonly>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Asset Category*</label>
                                        <select name="app_asset_category" id="app_asset_category" class="form-control form-control-sm"
                                            required onchange="getItemCode();" readonly>
                                            <option value="">Select Category</option>
                                            @foreach($assetcategories as $assetcategorie)
                                            <option value="{{$assetcategorie->id}}">{{$assetcategorie->asset_category}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Particular*</label>
                                        <select name="app_particular" id="app_particular" class="form-control form-control-sm"
                                        required readonly>
                                        <option value="">Select Particular</option>
                                    </select>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Employees*</label>
                                        <select name="app_employee" id="app_employee" class="form-control form-control-sm" required readonly>
                                            <option value="">Select Employees</option>
                                        </select>          
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">VSO Region*</label>
                                        <input type="text" id="app_region" name="app_region" class="form-control form-control-sm"
                                             readonly readonly>  
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Department*</label>
                                       <input type="text" id="app_department" name="app_department" class="form-control form-control-sm"
                                             readonly readonly>        
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Client Branch*</label>
                                        <input type="text" id="app_clientbranch" name="app_clientbranch" class="form-control form-control-sm"
                                             readonly readonly> 
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Opening Value At Cost*</label>
                                        <input type="text" id="app_opening_value" name="app_opening_value" class="form-control form-control-sm"
                                            readonly>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Date of Purchase*</label>
                                        <input type="date" id="app_dateofpurchase" name="app_dateofpurchase" class="form-control form-control-sm"
                                            readonly>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Rate*</label>
                                        <input type="text" id="app_rate" name="app_rate" class="form-control form-control-sm"
                                            readonly>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Additions/Deletions*</label>
                                        <input type="text" id="app_addition_deletion" name="app_addition_deletion" class="form-control form-control-sm"
                                            readonly>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Closing Value At Cost*</label>
                                        <input type="text" id="app_closing_value" name="app_closing_value" class="form-control form-control-sm"
                                            readonly>
                                    </div>
                                </div>
                                <hr>
                                <h3>Depreciation</h3>
                                <div class="form-row mb-1">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Acc.Depreciation AS ON 31/12/2022*</label>
                                        <input type="text" id="app_acc_dep_2022" name="app_acc_dep_2022" class="form-control form-control-sm" readonly>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Depreciation For 2023*</label>
                                        <input type="text" id="app_dep_2023" name="app_dep_2023" class="form-control form-control-sm" readonly>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Acc.Depreciation AS ON 31/12/2023*</label>
                                        <input type="text" id="app_acc_dep_2023" name="app_acc_dep_2023" class="form-control form-control-sm" readonly>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Written Down Value AS ON 31/12/2023*</label>
                                        <input type="text" id="app_writtendown_2023" name="app_writtendown_2023" class="form-control form-control-sm" readonly>
                                    </div>
                                </div>
                        <input type="hidden" name="app_hidden_id" id="app_hidden_id" />
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
    $(document).ready(function () {

        $("#companylink").addClass('navbtnactive');
   $('#corporate_link').addClass('active');

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
                url: scripturl + '/fixedassetlist.php',

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
                    title: 'Fixed Asset Details',
                    text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                },
                {
                    extend: 'print',
                    title: 'Fixed Asset Details',
                    className: 'btn btn-primary btn-sm',
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function (win) {
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    },                 
                },
                {
                    extend: 'excel',
                    title: 'Fixed Asset Details',
                    className: 'btn btn-success btn-sm',
                    text: '<i class="fas fa-file-excel mr-2"></i> Excel',
                },
            ],
            "order": [
                [0, "desc"]
            ],
            "columns": [{
                    "data": "code",
                    "className": 'text-dark'
                },
                {
                    "data": "asset_category",
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
                    "data": "region",
                    "className": 'text-dark'
                },
                {
                    "data": "department",
                    "className": 'text-dark'
                },

                {
                    "data": "clientbranch",
                    "className": 'text-dark'
                },
                {
                    data: 'opening_value',
                    className: 'text-dark text-right',
                    render: function(data, type, row) {
                    // Format the 'opening_value' to fixed(2) for display
                    if (type === 'display' || type === 'filter') {
                        return parseFloat(data).toFixed(2);
                    }
                    return data;
                    }

                },
                {
                    "data": "dateofpurchase",
                    "className": 'text-dark'
                },
                {
                    "data": "rate",
                    "className": 'text-dark'
                },
                {
                    "data": "addition_deletion",
                    "className": 'text-dark'
                },
                {
                    "data": "closing_value",
                    "className": 'text-dark'
                },
                {
                    "data": "acc_dep_2022",
                    "className": 'text-dark'
                },
                {
                    "data": "dep_2023",
                    "className": 'text-dark'
                },
                {
                    "data": "acc_dep_2023",
                    "className": 'text-dark'
                },
                {
                    "data": "writtendown_2023",
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
                            if (full['approve_status']==0) {
                                button += ' <button name="edit" id="' + full['id'] + '" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';                      
                            }
                        }
                        if (statuscheck) {
                                if (full['status'] == 1) {
                                    button += ' <a href="fixedassetstatus/' + full['id'] + '/2 " onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                                } else {
                                    button += '&nbsp;<a href="fixedassetstatus/' + full['id'] + '/1 "  onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                                }
                        }
                        if (deletecheck) {
                            button += ' <button name="delete" id="' + full['id'] + '" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }

                        return button;
                    }
                }
            ],
            "footerCallback": function (row, data, start, end, display) {
          var api = this.api();

          var intVal = function (i) {
            return typeof i === 'string' ?
              i.replace(/[\$,]/g, '') * 1 :
              typeof i === 'number' ?
              i : 0;
          };

          var opening_value = api
            .column(7)
            .data()
            .reduce(function (a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          var opening_valueTotal = api
            .column(7, {
              page: 'current'
            })
            .data()
            .reduce(function (a, b) {
              return parseFloat(intVal(a) + intVal(b)).toFixed(2);
            }, 0);

          $(api.column(7).footer()).html(
            opening_valueTotal
          );
        },
        "drawCallback": function (settings) {
          $('[data-toggle="tooltip"]').tooltip();
        }
      });
      // Add a footer element for the 'opening_value' column
      $('#dataTable tfoot th').eq(7).html('Total:');
  

        $('#create_record').click(function(){
                $('.modal-title').text('Add New Fixed Asset');
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
                    action_url = "{{ route('fixedassetinsert') }}";
                }
                if ($('#action').val() == 'Edit') {
                    action_url = "{{ route('fixedassetupdate') }}";
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
                    url: '{!! route("fixedassetedit") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id },
                    success: function (data) {
                        $('#code').val(data.result.code);
                        $('#asset_category').val(data.result.asset_category_id);
                        getparticular(data.result.asset_category_id,data.result.particular_id)

                        var empid = data.result.empid;
                        var empname = (data.result.service_no+"-"+data.result.emp_name_with_initial);
                        var newOption = new Option(empname, empid, true, true);
                        $('#employee').append(newOption).trigger('change');

                        $('#region').val(data.result.region);
                        $('#department').val(data.result.department);
                        $('#clientbranch').val(data.result.clientbranch);
                        $('#opening_value').val(data.result.opening_value);
                        $('#dateofpurchase').val(data.result.dateofpurchase);
                        $('#rate').val(data.result.rate);
                        $('#addition_deletion').val(data.result.addition_deletion);
                        $('#closing_value').val(data.result.closing_value);
                        $('#acc_dep_2022').val(data.result.acc_dep_2022);
                        $('#dep_2023').val(data.result.dep_2023);
                        $('#acc_dep_2023').val(data.result.acc_dep_2023);
                        $('#writtendown_2023').val(data.result.writtendown_2023);

                        $('#hidden_id').val(id);
                        $('.modal-title').text('Edit Fixed Asset');
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
                    url: '{!! route("fixedassetdelete") !!}',
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
                url: '{!! route("fixedassetedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_code').val(data.result.code);
                        $('#app_asset_category').val(data.result.asset_category_id);
                        app_getparticular(data.result.asset_category_id,data.result.particular_id)

                        var empid = data.result.empid;
                        var empname = (data.result.service_no+"-"+data.result.emp_name_with_initial);
                        var newOption = new Option(empname, empid, true, true);
                        $('#app_employee').append(newOption).trigger('change');

                        $('#app_region').val(data.result.region);
                        $('#app_department').val(data.result.department);
                        $('#app_clientbranch').val(data.result.clientbranch);
                        $('#app_opening_value').val(data.result.opening_value);
                        $('#app_dateofpurchase').val(data.result.dateofpurchase);
                        $('#app_rate').val(data.result.rate);
                        $('#app_addition_deletion').val(data.result.addition_deletion);
                        $('#app_closing_value').val(data.result.closing_value);
                        $('#app_acc_dep_2022').val(data.result.acc_dep_2022);
                        $('#app_dep_2023').val(data.result.dep_2023);
                        $('#app_acc_dep_2023').val(data.result.acc_dep_2023);
                        $('#app_writtendown_2023').val(data.result.writtendown_2023);

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
                url: '{!! route("fixedassetedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_code').val(data.result.code);
                        $('#app_asset_category').val(data.result.asset_category_id);
                        app_getparticular(data.result.asset_category_id,data.result.particular_id)

                        var empid = data.result.empid;
                        var empname = (data.result.service_no+"-"+data.result.emp_name_with_initial);
                        var newOption = new Option(empname, empid, true, true);
                        $('#app_employee').append(newOption).trigger('change');

                        $('#app_region').val(data.result.region);
                        $('#app_department').val(data.result.department);
                        $('#app_clientbranch').val(data.result.clientbranch);
                        $('#app_opening_value').val(data.result.opening_value);
                        $('#app_dateofpurchase').val(data.result.dateofpurchase);
                        $('#app_rate').val(data.result.rate);
                        $('#app_addition_deletion').val(data.result.addition_deletion);
                        $('#app_closing_value').val(data.result.closing_value);
                        $('#app_acc_dep_2022').val(data.result.acc_dep_2022);
                        $('#app_dep_2023').val(data.result.dep_2023);
                        $('#app_acc_dep_2023').val(data.result.acc_dep_2023);
                        $('#app_writtendown_2023').val(data.result.writtendown_2023);

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
                url: '{!! route("fixedassetedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_code').val(data.result.code);
                        $('#app_asset_category').val(data.result.asset_category_id);
                        app_getparticular(data.result.asset_category_id,data.result.particular_id)

                        var empid = data.result.empid;
                        var empname = (data.result.service_no+"-"+data.result.emp_name_with_initial);
                        var newOption = new Option(empname, empid, true, true);
                        $('#app_employee').append(newOption).trigger('change');

                        $('#app_region').val(data.result.region);
                        $('#app_department').val(data.result.department);
                        $('#app_clientbranch').val(data.result.clientbranch);
                        $('#app_opening_value').val(data.result.opening_value);
                        $('#app_dateofpurchase').val(data.result.dateofpurchase);
                        $('#app_rate').val(data.result.rate);
                        $('#app_addition_deletion').val(data.result.addition_deletion);
                        $('#app_closing_value').val(data.result.closing_value);
                        $('#app_acc_dep_2022').val(data.result.acc_dep_2022);
                        $('#app_dep_2023').val(data.result.dep_2023);
                        $('#app_acc_dep_2023').val(data.result.acc_dep_2023);
                        $('#app_writtendown_2023').val(data.result.writtendown_2023);
                    
                    $('#app_hidden_id').val(id_approve);
                    $('#app_level').val('3');
                    $('#approveconfirmModal').modal('show');

                }
            })


        });



        $('#approve_button').click(function () {
            var id_hidden = $('#app_hidden_id').val();
            var applevel = $('#app_level').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("fixedassetapprove") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_hidden,
                    applevel: applevel
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


        // particular filter insert part
        $('#asset_category').change(function () {
            var asset_category = $(this).val();
            if (asset_category !== '') {
                $.ajax({
                    url: '{!! route("fixedassetgetparticularfilter", ["categoryId" => "id_category"]) !!}'
                        .replace('id_category', asset_category),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#particular').empty().append(
                            '<option value="">Select Particular</option>');
                        $.each(data, function (index, particular) {
                            $('#particular').append('<option value="' + particular
                                .id + '">' + particular.name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#particular').empty().append('<option value="">Select Particular</option>');
            }
        });

         
        //select employee
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#employee').select2({
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

        // employee other details get
        $('#employee').change(function () {
            var employee = $(this).val();
           
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("fixedassetgetempdetails") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: employee
                },
                success: function (data) {
                    $('#region').val(data.result.subregionname);
                    $('#department').val(data.result.departmentname);
                    $('#clientbranch').val(data.result.branchname);
                }
            })
        });

    });

       // get category code
       function getItemCode() {
        var asset_category = $('#asset_category').val();

            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.ajax({
            url: '{!! route("fixedassetGetItemCode") !!}',
            type: 'POST',
            dataType: "json",
            data: {
                id: asset_category
            },
            success: function (data) {
                    $('#code').val(data.result);
               
            }
        })   
    }

    // particular filter edit part
    function getparticular(asset_category,particular_id){
            if (asset_category !== '') {
                $.ajax({
                    url: '{!! route("fixedassetgetparticularfilter", ["categoryId" => "id_category"]) !!}'
                        .replace('id_category', asset_category),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#particular').empty().append(
                            '<option value="">Select Particular</option>');
                        $.each(data, function (index, particular) {
                            $('#particular').append('<option value="' + particular
                                .id + '">' + particular.name + '</option>');
                        });
                        $('#particular').val(particular_id);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#particular').empty().append('<option value="">Select Particular</option>');
            }
        };

        // particular filter approvel part
    function app_getparticular(asset_category,particular_id){
            if (asset_category !== '') {
                $.ajax({
                    url: '{!! route("fixedassetgetparticularfilter", ["categoryId" => "id_category"]) !!}'
                        .replace('id_category', asset_category),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#app_particular').empty().append(
                            '<option value="">Select Particular</option>');
                        $.each(data, function (index, particular) {
                            $('#app_particular').append('<option value="' + particular
                                .id + '">' + particular.name + '</option>');
                        });
                        $('#app_particular').val(particular_id);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#app_particular').empty().append('<option value="">Select Particular</option>');
            }
        };


    function productDelete(row) {
        $(row).closest('tr').remove();
    }

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }
</script>

@endsection