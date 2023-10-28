@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">

        <div class="page-header-content py-3">
            <h1 class="page-header-title">
                <div class="page-header-icon"><i class="fas fa-user-tie"></i></div>
                <span>Customer Request Report</span>
            </h1>
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0 p-2">
                <div class="row">


                        <div class="col-3">
                            <label class="small font-weight-bold text-dark">Main Client*</label>
                            <select name="customer" id="customer" class="form-control form-control-sm"
                                required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="small font-weight-bold text-dark">Sub Client*</label>
                            <select name="subcustomer" id="subcustomer" class="form-control form-control-sm"
                                required>
                                <option value="">Select Sub Customer</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="small font-weight-bold text-dark">Branch*</label>
                            <select name="area" id="area" class="form-control form-control-sm" required>
                                <option value="">Select Branch</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <br>
                            <button type="button" class="btn btn-outline-primary btn-sm" name="search"
                            id="search"><i class="fa fa-search"></i>Search</button>
                        </div>
                    <div class="col-12">
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">

                        <table class="table table-striped table-bordered table-sm small" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>   
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Shift Type</th>
                                    <th>SSO</th>
                                    <th>JSO</th>
                                    <th>GUN MAN</th>
                                    <th>Level 1 Approvel</th>
                                    <th>Level 2 Approvel</th>
                                    <th>Level 3 Approvel</th>
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
   
   

</main>

@endsection


@section('script')

<script>
    $(document).ready(function () {
        //Get Sub Customer
        $('#customer').change(function () {
            var customerId = $(this).val();
            if (customerId !== '') {
                $.ajax({
                    url: '/getsubcustomers/' + customerId,
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
                    url: '/getbranch/' + subcustomerId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#area').empty().append(
                        '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#area').append('<option value="' + branch.id + '">' +
                                branch.branch_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#area').empty().append('<option value="">Select Branch</option>');
            }
        });
    });
</script>

@endsection