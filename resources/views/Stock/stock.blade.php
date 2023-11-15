@extends('layouts.app')

@section('content')
<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
            <div class="page-header-content py-3">
                <h1 class="page-header-title">
                    <div class="page-header-icon"><i class="fas fa-users"></i></div>
                    <span>Stock</span>
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
                            <label class="small font-weight-bold text-dark">Store*</label>
                            <select name="store" id="store" class="form-control form-control-sm">
                                <option value="">Select Store</option>
                                @foreach($stores as $store)
                                        <option value="{{$store->id}}">{{$store->name}}</option>
                                        @endforeach
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
                        <h3>Brand New Item Stock</h3>
                        <div class="center-block fix-width scroll-inner">
                        <table class="table table-striped table-bordered table-sm small nowrap display" style="width: 100%" id="newstock">
                            <thead>
                                <tr>
                                    <th>Item Code </th>
                                    <th>Item</th>
                                    <th>Batch No</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Store</th>
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
                        <h3>Return Item Stock</h3>
                        <div class="center-block fix-width scroll-inner">
                        <table class="table table-striped table-bordered table-sm small nowrap display" style="width: 100%" id="returnstock">
                            <thead>
                                <tr>
                                    <th>Item Code </th>
                                    <th>Item</th>
                                    <th>Qulity Percentage</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Store</th>
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
 

    <!-- Modal Area End -->
</main>
        
              
@endsection
@section('script')
<script>
$(document).ready(function () {
        var approvel01 = {{$approvel01permission}};
        var approvel02 = {{$approvel02permission}};
        var approvel03 = {{$approvel03permission}};

        var listcheck = {{$listpermission}};
        var editcheck = {{$editpermission}};
        var statuscheck = {{$statuspermission}};
        var deletecheck = {{$deletepermission}};

    $('#collapseCorporation').addClass('show');
        $('#collapsgrninfo').addClass('show');
    
        $('#stock_link').addClass('active');

    


    $("#etfno").focusout(function(){
        let val = $(this).val();
        $('#emp_id').val(val);
    });


// new stock datatable
    function load_dt(store){
        
        $('#newstock').DataTable({
                "destroy": true,
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: scripturl + '/newstocklist.php',

                    type: "POST", // you can use GET
                    data: {'store':store, 
                        },
                    
                },
                dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            "buttons": [
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Brand New Stock Details', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { 
                    extend: 'print', 
                    title: 'Brand New Stock Details',
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
                        "data": "inventorylist_id",
                        "className": 'text-dark'
                    },
                    {
                        "data": null,
                        "className": 'text-dark',
                        "render": function (data, type, full, meta) { 
                            if ((data.uniform_size == '') || (data.uniform_size== null)) {
                                return data.name;
                            } else {
                                return data.name + ' ' + data.uniform_size+'"';
                            }
                        }
                    },
                    {
                        "data": "batch_no",
                        "className": 'text-dark'
                    },
                    {
                        "data": "qty",
                        "className": 'text-dark'
                    },  
                    {
                        "data": "unit_price",
                        "className": 'text-dark'
                    },
                    {
                        "data": "storename",
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

                        if (statuscheck) {
                                if (full['status'] == 1) {
                                    button += ' <a href="customerrequeststatus/' + full['id'] + '/2 " onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                                } else {
                                    button += '&nbsp;<a href="customerrequeststatus/' + full['id'] + '/1 "  onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                                }
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

    load_dt('');

$('#formFilter').on('submit',function(e) {
    e.preventDefault();
    let store = $('#store').val();
    
    load_dt(store);
    });



// return stock datatable
function returnload_dt(store){
        
        $('#returnstock').DataTable({
                "destroy": true,
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: scripturl + '/returnstocklist.php',

                    type: "POST", // you can use GET
                    data: {'store':store, 
                        },
                    
                },
                dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            "buttons": [
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Return Stock Details', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { 
                    extend: 'print', 
                    title: 'Return Stock Details',
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
                        "data": "inventorylist_id",
                        "className": 'text-dark'
                    },
                    {
                        "data": null,
                        "className": 'text-dark',
                        "render": function (data, type, full, meta) { 
                            if ((data.uniform_size == '') || (data.uniform_size== null)) {
                                return data.name;
                            } else {
                                return data.name + ' ' + data.uniform_size+'"';
                            }
                        }
                    },
                    {
                        "data": "quality_percentage",
                        "className": 'text-dark'
                    },
                    {
                        "data": "qty",
                        "className": 'text-dark'
                    },  
                    {
                        "data": "unit_price",
                        "className": 'text-dark'
                    },
                    {
                        "data": "storename",
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

                        if (statuscheck) {
                                if (full['status'] == 1) {
                                    button += ' <a href="customerrequeststatus/' + full['id'] + '/2 " onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                                } else {
                                    button += '&nbsp;<a href="customerrequeststatus/' + full['id'] + '/1 "  onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                                }
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

    returnload_dt('');

$('#formFilter').on('submit',function(e) {
    e.preventDefault();
    let store = $('#store').val();
    
    returnload_dt(store);
    });

});

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
            
            // Clear other form fields if needed
            $("#store").val("");
        });
    });
</script>

@endsection