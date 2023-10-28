@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
            <div class="page-header-content py-3">
                <h1 class="page-header-title">
                    <div class="page-header-icon"><i class="far fa-calendar-check"></i></div>
                    <span>Attendance Approvel</span>
                </h1>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-4">
        <div class="card mb-2">
            <div class="card-body">
                <form class="form-horizontal" id="formFilter">
                    <div class="form-row mb-1">
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Company</label>
                            <select name="company" id="company" class="form-control form-control-sm" required>
                            </select>
                        </div>
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Department</label>
                            <select name="department" id="department" class="form-control form-control-sm" required>
                            </select>
                        </div>
                        <div class="col">
                            <label class="small font-weight-bold text-dark">Month</label>
                            <input type="month" id="month" name="month" class="form-control form-control-sm" placeholder="yyyy-mm" required>
                        </div>
                        <div class="col">
                            <br>
                            <button type="submit" class="btn btn-primary btn-sm filter-btn" id="btn-filter"> Filter</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0 p-2 main_card">
                <div class="row">
                    <div class="col-12">
                        <div class="message"></div>
                        <div class="table-responsive table_outer">
                            <table class="table table-striped table-bordered table-sm small" style="width: 100%" id="attendtable">
                                <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Employee Name</th>
                                    <th>Work Month</th>
                                    <th>Department</th>
                                    <th>Company</th>
                                    <th>Work Days</th>
                                    <th>Leave Days</th>
                                    <th>No Pay Days</th>
                                    <th>Last Time Stamp</th>
                                    <th> </th>
                                </tr>
                                </thead>

                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        <button id="approve_att" class="btn btn-primary btn-sm float-right mt-2"> Approve All</button>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Area Start -->
    <div class="modal fade" id="AttendviewModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Attendent Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div id="message"></div>
                            <table id='attendTable' class="table table-striped table-bordered table-sm small">
                                <thead>

                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <div id="htmlbutton"></div>
                        </div>
                    </div>
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

    $('#attendance_main_nav_link').prop('aria-expanded', 'true').removeClass('collapsed');
    $('#collapse_employee_info').addClass('show');
    $('#collapsattendance').addClass('show');
    $('#attendance_collapse').addClass('show');
    $('#attendance_approve_link').addClass('active');

    $('.table_outer').css('display', 'none');
    $('#approve_att').css('display', 'none');

    let msg = '<div class="alert alert-info alert-dismissible fade show" role="alert">' +
        'Please select Department, Month and filter to load records.' +
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
        '<span aria-hidden="true">&times;</span>' +
        '</button>' +
        '</div>';

    $('.main_card').append(msg);

    let company = $('#company');
    let department = $('#department');

    company.select2({
        placeholder: 'Select...',
        width: '100%',
        allowClear: true,
        ajax: {
            url: '{{url("company_list_sel2")}}',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true
        }
    });

    department.select2({
        placeholder: 'Select...',
        width: '100%',
        allowClear: true,
        ajax: {
            url: '{{url("department_list_sel2")}}',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1,
                    company: company.val()
                }
            },
            cache: true
        }
    });

    //load_dt('');
    function load_dt(company,department, month){

        $('.alert').remove();
        $('.table_outer').css('display', 'block');
        $('#approve_att').css('display', 'block');

        $('#attendtable').DataTable({
            "columnDefs": [ {
                "targets": -1,
                "orderable": false
            } ],
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{{url('/attendance_list_for_approve')}}",
                "data": {'company':company, 'department':department, 'month':month},
            },
            columns: [
                { data: 'uid' },
                { data: 'emp_name_with_initial' },
                { data: 'date' },
                { data: 'dept_name' },
                { data: 'location' },
                { data: 'work_days' },
                { data: 'leave_days' },
                { data: 'no_pay_days' },
                { data: 'lasttimestamp' },
                { data: 'uid' ,
                    render : function ( data, type, row, meta ) {

                        return type === 'display'  ?
                            ' <a href="Attendentdetails/'+row['uid']+'"> view </a> '
                            : data;
                    }},
            ],
            "bDestroy": true,
            "order": [[ 8, "desc" ]],
        });
    }

    $('#formFilter').on('submit',function(e) {
        e.preventDefault();
        let department = $('#department').val();
        let company = $('#company').val();
        let month = $('#month').val();

        load_dt(company, department, month );
    });

    $(document).on('click', '#approve_att', function (e) {
        e.preventDefault();
        let department = $('#department').val();
        let company = $('#company').val();
        let month = $('#month').val();

        //js confirm alert
        if (confirm("Are you sure you want to approve this attendance?")) {

            $.ajax({
                url: "AttendentAprovelBatch",
                method: "POST",
                data: {
                    department: department,
                    company: company,
                    month: month,
                    _token: $('input[name=_token]').val(),
                },
                success: function (data) {
                    $('.message').html("<div class='alert alert-success'>"+data.msg+"</div>");
                    $('#attendtable').DataTable().clear().destroy();

                    $('.table_outer').css('display', 'none');
                    $('#approve_att').css('display', 'none');
                }
            });

        }

    });

});

$(document).on('click', '.view_button', function () {
    id = $(this).attr('uid');
    date = $(this).attr('data-date');
    emp_name_with_initial = $(this).attr('data-name');

    var formdata = {
        _token: $('input[name=_token]').val(),
        id: id,
        date: date
    };
    // alert(date);
    $('#form_result').html('');
    $.ajax({
        url: "getAttendanceApprovel",
        dataType: "json",
        data: formdata,
        success: function (data) {
            $('#AttendviewModal').modal('show');
            $('.modal-title').text('View Attendent');
            var htmlhead = '';
            htmlhead += '<tr><td>Emp ID :' + id + '</td><td >Name :' + emp_name_with_initial + '</td></tr>';
            htmlhead += '<tr><th>Date</th><th>Check in</th><th>Check out</th></tr>';
            var html = '';
            var htmlbutton = '';

            html += '<tr>';


            var errorcount = 0;
            for (var count = 0; count < data.length; count++) {
                html += '<tr>';
                if (data[count].firsttimestamp >= data[count].lasttimestamp) {
                    errorcount++

                    html += '<td contenteditable class="timestamp" data-timestamp="timestamp" data-id="' + data[count].id + '">' + data[count].date + '</td>';
                    html += '<td contenteditable class="timestamp " data-timestamp="timestamp" data-id="' + data[count].id + '">' + data[count].firsttimestamp + '</td>';
                    html += '<td contenteditable class="timestamp text-danger" data-timestamp="timestamp" data-id="' + data[count].id + '">' + data[count].lasttimestamp + '</td>';

                } else {
                    html += '<td contenteditable class="timestamp" data-timestamp="timestamp" data-id="' + data[count].id + '">' + data[count].date + '</td>';
                    html += '<td contenteditable class="timestamp " data-timestamp="' + data[count].id + '" data-id="' + data[count].id + '">' + data[count].firsttimestamp + '</td>';
                    html += '<td contenteditable class="timestamp " data-timestamp="timestamp" data-id="' + data[count].id + '">' + data[count].lasttimestamp + '</td>';

                }

            }
            if (errorcount == 0) {
                htmlbutton += '<tr > <td > <button type="button" class="btn btn-success pull-left" id="approvel">Approval</button></td><tr >';
            }

            $('#attendTable thead').html(htmlhead);
            $('#attendTable tbody').html(html);
            $('#htmlbutton').html(htmlbutton);
        }
    })
});

$(document).on('click', '#approvel', function () {
    var _token = $('input[name="_token"]').val();
    var emp_id = $('#emp_id').text();

    if (emp_id != '') {
        $.ajax({
            url: "AttendentAprovel",
            method: "POST",
            data: {
                emp_id: emp_id,
                _token: _token
            },
            success: function (data) {
                $('#message').html(data);
                fetch_data();
            }
        });
    } else {
        $('#message').html("<div class='alert alert-danger'>Both Fields are required</div>");
    }
});
</script>

@endsection