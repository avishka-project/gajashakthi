@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
    <div class="container-fluid">
        <div class="page-header-content py-3">
            <h1 class="page-header-title">
                <div class="page-header-icon"><i class="fas fa-users"></i></div>
                <span>Supplier Contact</span>
                <input type="hidden" id="supplierid" value="{{$id}}">
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
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record"
                            id="create_record"><i class="fas fa-plus mr-2"></i>Add Contact</button>
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
                                    <th>Contact</th>
                                    <th>Email</th>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Add Contact</h5>
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
                                    <input type="hidden" id="customer_id" name="customer_id">
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Contact</label>
                                        <input type="text" name="contact" id="contact"
                                            class="form-control form-control-sm" />
                                    </div>
                                </div>

                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Email*</label>&nbsp;&nbsp;&nbsp;
                                        <input type="text" name="email" id="email" class="form-control form-control-sm"
                                             />
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" name="action_button" id="action_button"
                                        class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                            class="fas fa-plus"></i>&nbsp;Add</button>
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


    <!-- Modal Area End -->
</main>

@endsection


@section('script')

<script>
    $(document).ready(function () {
        $('#collapseCorporation').addClass('show');
            $('#collapsgrninfo').addClass('show');
            $('#supplier_link').addClass('active');

        $('#dataTable').DataTable({

            processing: true,
            serverSide: true,

            ajax: {
                "url": "{!! route('supcontactlist') !!}",
                "data": {
                    "sup_id": $('#supplierid').val()
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'contact',
                    name: 'contact'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            "bDestroy": true,
            "order": [
                [2, "desc"]
            ]
        });

        $('#create_record').click(function () {
            $('.modal-title').text('Add New Contact');
            $('#action_button').html('Add');
            $('#action').val('Add');
            $('#form_result').html('');
            $('#formTitle')[0].reset();

            $('#formModal').modal('show');
        });

        $('#formTitle').on('submit', function (event) {
            event.preventDefault();
            var action_url = '';

            if ($('#action').val() == 'Add') {
                action_url = "{{ route('supcontactinsert') }}";
            }
            if ($('#action').val() == 'Edit') {
                action_url = "{{ route('supcontactupdate') }}";
            }


            var supid = $('#supplierid').val();
            var contact = $('#contact').val();
            var email = $('#email').val();
            var hidden_id = $('#hidden_id').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: action_url,
                method: "POST",
                data: {
                    contact: contact,
                    email: email,
                    supid: supid,
                    hidden_id: hidden_id

                },
                dataType: "json",
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
                        //$('#dataTable').DataTable().ajax.reload();
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
                url: '{!! route("supcontactedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#contact').val(data.result.contact);
                    $('#email').val(data.result.email);
                    $('#hidden_id').val(id);
                    $('.modal-title').text('Edit Contact');
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
                url: '{!! route("supcontactdelete") !!}',
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
                        alert('Data Deleted');
                    }, 2000);
                    location.reload()
                }
            })
        });


    });
</script>

@endsection