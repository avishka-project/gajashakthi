@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
            <div class="page-header-content py-3">
                <h1 class="page-header-title">
                    <div class="page-header-icon"><i class="fas fa-user"></i></div>
                    <span>Work Shift</span>
                </h1>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-4">
        <div class="card">
            <div class="card-body p-0 p-2">
                <div class="row">
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record" id="create_record"><i class="fas fa-plus mr-2"></i>Add Work Shift</button>
                    </div>
                    <div class="col-12">
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">
                        <table class="table table-striped table-bordered table-sm small" id="divicestable">
                            <thead>
                                <tr>
                                    <th>Id </th>
                                    <th>Leave Type</th> 
                                    <th>Onduty time</th>                                                
                                    <th>Offduty time</th>                                                
                                    <th>Begining checkin</th>                                                
                                    <th>Begining checkout</th>                                                
                                    <th>Ending checkin</th>                                                
                                    <th>Ending checkout</th>                                                
                                    <th class="text-right">Action</th>                                      
                                </tr>
                            </thead>                          
                            <tbody>
                            @foreach($shifttype as $shifttypes)
                                <tr>
                                    <td>{{$shifttypes->id}}</td>
                                    <td>{{$shifttypes->shift_name}}</td>
                                    <td>{{$shifttypes->onduty_time}}</td>
                                    <td>{{$shifttypes->offduty_time}}</td>                                             
                                    <td>{{$shifttypes->begining_checkin}}</td>                                             
                                    <td>{{$shifttypes->begining_checkout}}</td>                                             
                                    <td>{{$shifttypes->ending_checkin}}</td>                                             
                                    <td>{{$shifttypes->ending_checkout}}</td>
                                    <td class="text-right">  
                                        <button name="edit" id="{{$shifttypes->id}}" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>  
                                        <button type="submit" name="delete" id="{{$shifttypes->id}}" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>
                                    </td> 
                                </tr>
                                @endforeach                              
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Area Start -->
    <div class="modal fade" id="formModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Location</h5>
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
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Shift Name</label>
                                        <input type="text" name="shiftname" id="shiftname" class="form-control form-control-sm" />
                                    </div>                                  
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">On Duty time</label>
                                        <input type="time" name="ondutytime" id="ondutytime" class="form-control form-control-sm" />
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Off Duty time</label>
                                        <input type="time" name="offdutytime" id="offdutytime" class="form-control form-control-sm" />
                                    </div>                                    
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Late Time</label>
                                        <input type="time" name="latetime" id="latetime" class="form-control form-control-sm" />
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Leave Early Time</label>
                                        <input type="time" name="leaveearlytime" id="leaveearlytime" class="form-control form-control-sm" />
                                    </div>                                    
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Begining Checkin</label>
                                        <input type="time" name="beginingcheckin" id="beginingcheckin" class="form-control form-control-sm" />
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Begining Checkout</label>
                                        <input type="time" name="beginingcheckout" id="beginingcheckout" class="form-control form-control-sm" />
                                    </div>                                    
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Ending Checkin</label>
                                        <input type="time" name="endingcheckin" id="endingcheckin" class="form-control form-control-sm" />
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Ending Checkout</label>
                                        <input type="time" name="endingcheckout" id="endingcheckout" class="form-control form-control-sm" />
                                    </div>                                    
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Workdays Count</label>
                                        <input type="text" name="workdayscount" id="workdayscount" class="form-control form-control-sm" />
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Minute Count</label>
                                        <input type="text" name="minutecount" id="minutecount" class="form-control form-control-sm" />
                                    </div>                                    
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <div class="custom-control custom-checkbox">
                                          <input type="checkbox" class="custom-control-input" id="mustcheckin" name="mustcheckin">
                                          <label class="custom-control-label" for="mustcheckin">Must CheckIn</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                          <input type="checkbox" class="custom-control-input" id="mustcheckout" name="mustcheckout">
                                          <label class="custom-control-label" for="mustcheckout">Must CheckOut</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Color</label>
                                        <input type="color" name="color" id="color" class="form-control form-control-sm" />
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
    <!-- Modal Area End -->
</main>
              
@endsection


@section('script')

<script>
$(document).ready(function(){

    $('#shift_main_nav_link').prop('aria-expanded', 'true').removeClass('collapsed');
    $('#shift_collapse').addClass('show');
    $('#work_shift_link').addClass('active');

    $('#divicestable').DataTable();

    $('#create_record').click(function () {
        $('.modal-title').text('Add Leave Type');
        $('#action_button').val('Add');
        $('#action').val('Add');
        $('#form_result').html('');

        $('#formModal').modal('show');
    });

    $('#formTitle').on('submit', function (event) {
        event.preventDefault();
        var action_url = '';


        if ($('#action').val() == 'Add') {
            action_url = "{{ route('addShiftType') }}";
        }


        if ($('#action').val() == 'Edit') {
            action_url = "{{ route('ShiftType.update') }}";
        }


        $.ajax({
            url: action_url,
            method: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (data) {

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
                    // $('#titletable').DataTable().ajax.reload();
                    location.reload();
                }
                $('#form_result').html(html);
            }
        });
    });
    
    $(document).on('click', '.edit', function () {
        var id = $(this).attr('id');
        $('#form_result').html('');
        $.ajax({
            url: "ShiftType/" + id + "/edit",
            dataType: "json",
            success: function (data) {
                $('#shiftname').val(data.result.shift_name);
                $('#ondutytime').val(data.result.onduty_time);
                $('#offdutytime').val(data.result.offduty_time);
                $('#latetime').val(data.result.late_time);
                $('#leaveearlytime').val(data.result.leave_early_time);
                $('#beginingcheckin').val(data.result.begining_checkin);
                $('#beginingcheckout').val(data.result.begining_checkout);
                $('#endingcheckin').val(data.result.ending_checkin);
                $('#endingcheckout').val(data.result.ending_checkout);
                $('#workdayscount').val(data.result.workdays_count);
                $('#minutecount').val(data.result.minute_count);
                $('#mustcheckin').val(data.result.must_checkin);
                $('#mustcheckout').val(data.result.must_checkout);
                $('#color').val(data.result.color);
                $('#hidden_id').val(id);
                $('.modal-title').text('Edit  Shift Type');
                $('#action_button').val('Edit');
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
        $.ajax({
            url: "ShiftType/destroy/" + user_id,
            beforeSend: function () {
                $('#ok_button').text('Deleting...');
            },
            success: function (data) {
                setTimeout(function () {
                    $('#confirmModal').modal('hide');
                    $('#user_table').DataTable().ajax.reload();
                    alert('Data Deleted');
                }, 2000);
                location.reload();
            }
        })
    });

});
</script>

@endsection