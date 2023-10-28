@extends('layouts.app')

@section('content')

<main>
	<div class="page-header page-header-light bg-white shadow">
		<div class="container-fluid">
			<div class="page-header-content py-3">
				<h1 class="page-header-title">
					<div class="page-header-icon"><i class="fas fa-user"></i></div>
					<span>Personal Details</span>
					<input hidden id="employeetype" name="employeetype" type="text">
					<input hidden id="hidden_id" name="hidden_id" type="text" value="{{$id}}">
					<button type="button" style="margin-left: auto"
						class="btn btn-outline-primary btn-m fa-pull-right prev_record" name="prev_record"
						id="prev_record"><i class="fa fa-arrow-circle-left"></i></button>
					<form>
						<div class="input-group" style="width: auto">
							<input type="text" style="margin-left: 10px" name="serviceno" id="serviceno"
								class="form-control-sm" required>

							<div class="input-group-append">
								<button class="form-control-sm btn-warning serchbtn" type="button" name="searchbtn"
									id="searchbtn"><i class="fas fa-search"></i></button>
							</div>
						</div>
						<input hidden type="submit" id="submitbtn" name="submitbtn">
					</form>
					<button style="margin-left: 10px" type="button"
						class="btn btn-outline-primary btn-m fa-pull-right next_record" name="next_record"
						id="next_record"><i class="fa fa-arrow-circle-right"></i></button>
				</h1>
			</div>
		</div>
	</div>
	<div class="container-fluid mt-3">
		<div class="card">
			<div class="card-body p-0 p-2">
				<div class="row">
					<div class="col-9">
						@if(session()->has('success'))
						<div class="alert alert-success">
							{{ session()->get('success') }}
						</div>
						@endif
						@if(session()->has('error'))
						<div class="alert alert-danger">
							@foreach(session()->get('error') as $er)
							{{ $er }}
							<br>
							@endforeach
						</div>
						@endif

						<form id="PdetailsForm" class="form-horizontal" method="POST"
							action="{{ route('empoyeeUpdate') }}" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="card shadow-none mb-3">
								<div class="card-body bg-light">
									<h6 class="title-style my-3"><span>Personal Information</span></h6>
									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">First Name</label>

											<input type="text" class="form-control form-control-sm" id="firstname"
												name="firstname" placeholder="First Name"
												value="{{$employee->emp_first_name}}">
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Middle Name</label>
											<input type="text" class="form-control form-control-sm" id="middlename"
												name="middlename" placeholder="Middle Name"
												value="{{$employee->emp_med_name}}">
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Last Name</label>
											<input type="text" class="form-control form-control-sm" id="lastname"
												name="lastname" placeholder="Last Name"
												value="{{$employee->emp_last_name}}">
										</div>
									</div>
									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Name with Initial</label>
											<input type="text"
												class="form-control form-control-sm {{ $errors->has('emp_name_with_initial') ? ' has-error' : '' }}"
												name="emp_name_with_initial" id="emp_name_with_initial"
												placeholder=" Name with Initial"
												value="{{$employee->emp_name_with_initial}}">
											@if ($errors->has('emp_name_with_initial'))
											<span class="help-block">
												<strong>{{ $errors->first('emp_name_with_initial') }}</strong>
											</span>
											@endif
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Calling Name</label>
											<input type="text"
												class="form-control form-control-sm {{ $errors->has('calling_name') ? ' has-error' : '' }}"
												name="calling_name" id="calling_name" placeholder="Calling Name"
												value="{{$employee->calling_name}}">
											@if ($errors->has('calling_name'))
											<span class="help-block">
												<strong>{{ $errors->first('calling_name') }}</strong>
											</span>
											@endif
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Identity Card No</label>
											<input type="text" class="form-control form-control-sm" id="nicnumber"
												name="nicnumber" value="{{$employee->emp_national_id}}">
												<span id="checknic"></span>
										</div>
									</div>
									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Full Name</label>
											<input type="text"
												class="form-control form-control-sm {{ $errors->has('emp_fullname') ? ' has-error' : '' }}"
												name="fullname" id="fullname" placeholder=" Full Name"
												value="{{$employee->emp_fullname}}">
											@if ($errors->has('emp_fullname'))
											<span class="help-block">
												<strong>{{ $errors->first('emp_fullname') }}</strong>
											</span>
											@endif
										</div>
									</div>
								</div>
							</div>

							<div class="card shadow-none mb-3">
								<div class="card-body bg-light">
									<h6 class="title-style my-3"><span>Contact Information</span></h6>
									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Employee Permanent Address
												1</label>
											<input type="text" class="form-control form-control-sm" id="address1"
												name="address1" value="{{$employee->emp_address}}">
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Employee Permanent Address
												2</label>
											<input type="text" class="form-control form-control-sm" id="address2"
												name="address2" value="{{$employee->emp_address_2}}">
										</div>
									</div>
									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Employee Temporary Address
												1</label>
											<input type="text" class="form-control form-control-sm" id="addressT1"
												name="addressT1" value="{{$employee->emp_addressT1}}">
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Employee Temporary Address
												2</label>
											<input type="text" class="form-control form-control-sm" id="addressT2"
												name="addressT2" value="{{$employee->emp_address_T2}}">
										</div>
									</div>
									<div class="form-row mb-1">
										<div class="col">
											<label class="small font-weight-bold text-dark">Telephone</label>
											<input type="text" name="telephone" id="telephone"
												value="{{$employee->tp1}}"
												class="form-control form-control-sm {{ $errors->has('telephone') ? ' has-error' : '' }}" />
											@if ($errors->has('telephone'))
											<span class="help-block">
												<strong>{{ $errors->first('telephone') }}</strong>
											</span>
											@endif
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Mobile No</label>
											<input type="text" name="emp_mobile" id="emp_mobile"
												value="{{$employee->emp_mobile}}"
												class="form-control form-control-sm {{ $errors->has('emp_mobile') ? ' has-error' : '' }}" />
											@if ($errors->has('emp_mobile'))
											<span class="help-block">
												<strong>{{ $errors->first('emp_mobile') }}</strong>
											</span>
											@endif
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Office Telephone</label>
											<input type="text" name="emp_work_telephone" id="emp_work_telephone"
												value="{{$employee->emp_work_telephone}}"
												class="form-control form-control-sm {{ $errors->has('emp_work_telephone') ? ' has-error' : '' }}" />
											@if ($errors->has('emp_work_telephone'))
											<span class="help-block">
												<strong>{{ $errors->first('emp_work_telephone') }}</strong>
											</span>
											@endif
										</div>
									</div>
									<div class="form-row mb-1">

										<div class="col">
											<label class="small font-weight-bold text-dark">Employee Email</label>
											<input type="email" name="emp_email" id="emp_email"
												value="{{$employee->emp_email}}"
												class="form-control form-control-sm {{ $errors->has('emp_email') ? ' has-error' : '' }}" />
											@if ($errors->has('emp_email'))
											<span class="help-block">
												<strong>{{ $errors->first('emp_email') }}</strong>
											</span>
											@endif
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Employee Other Email</label>
											<input type="email" name="emp_other_email" id="emp_other_email"
												value="{{$employee->emp_other_email}}"
												class="form-control form-control-sm {{ $errors->has('emp_other_email') ? ' has-error' : '' }}" />
											@if ($errors->has('emp_other_email'))
											<span class="help-block">
												<strong>{{ $errors->first('emp_other_email') }}</strong>
											</span>
											@endif
										</div>
									</div>
									<div class="form-row">
										<div class="col-6">
											<label class="small font-weight-bold text-dark">Photograph</label>
											<input type="file" data-preview="#preview"
												class="form-control form-control-sm {{ $errors->has('photograph') ? ' has-error' : '' }}"
												name="photograph" id="photograph">
											<img class="col-sm-6" id="preview" src="">
											@if ($errors->has('photograph'))
											<span class="help-block">
												<strong>{{ $errors->first('photograph') }}</strong>
											</span>
											@endif
										</div>

									</div>
								</div>
							</div>
							<div class="card shadow-none mb-3">
								<div class="card-body bg-light">
									<h6 class="title-style my-3"><span>Other Information</span></h6>
									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Gender</label><br>
											<div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="gender1" name="gender"
													class="custom-control-input" value="Male"
													{{ ($employee->emp_gender=="Male")? "checked" : "" }}>
												<label class="custom-control-label" for="gender1">Male</label>
											</div>
											<div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="gender2" name="gender"
													class="custom-control-input" value="Female"
													{{ ($employee->emp_gender=="Female")? "checked" : "" }}>
												<label class="custom-control-label" for="gender2">Female</label>
											</div>
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Marital Status</label>
											<select id="marital_status" name="marital_status"
												class="form-control form-control-sm"
												value="{{$employee->emp_marital_status}}">
												<option>Select</option>
												<option value="Married"
													{{$employee->emp_marital_status == 'Married'  ? 'selected' : ''}}>
													Married</option>
												<option value="Unmarried"
													{{$employee->emp_marital_status == 'Unmarried'  ? 'selected' : ''}}>
													Unmarried</option>
											</select>
										</div>
									</div>
									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Nationality</label>
											<select id="nationality" class="form-control form-control-sm"
												name="nationality">
												<option selected>Select</option>
												<option value="Srilankan"
													{{$employee->emp_nationality == 'Srilankan'  ? 'selected' : ''}}>
													Srilankan</option>
											</select>
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Date of Birth</label>
											<input type="date" class="form-control form-control-sm" id="birthday"
												name="birthday" value="{{$employee->emp_birthday}}">
										</div>
									</div>
								</div>
							</div>

							<div class="card shadow-none mb-3">
								<div class="card-body bg-light">
									<h6 class="title-style my-3"><span>Work Information</span></h6>
									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Employee EPF No</label>
											<input type="text" class="form-control form-control-sm" id="emp_etfno"
												name="emp_etfno" value="{{$employee->emp_etfno}}">
												<span id="checketf"></span>
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Employee No</label>
											<input type="text" class="form-control form-control-sm" id="emp_id"
												name="emp_id" value="{{$employee->emp_id}}">
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Service No</label>
											<input type="text" class="form-control form-control-sm" id="service_no"
												name="service_no" value="{{$employee->service_no}}">
												<span id="checkservice"></span>
										</div>
									</div>

									<div class="form-row">

										<div class="col">
											<label class="small font-weight-bold text-dark">Driver's License
												Number</label>
											<input type="text" class="form-control form-control-sm" id="licensenumber"
												name="licensenumber" value="{{$employee->emp_drive_license}}">
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">License Expiry Date</label>
											<input type="date" class="form-control form-control-sm"
												id="licenseexpiredate" name="licenseexpiredate"
												value="{{$employee->emp_license_expire_date}}">
										</div>
									</div>
									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Join Date</label>
											<input type="date" class="form-control form-control-sm" id="joindate"
												name="joindate" value="{{$employee->emp_join_date}}">
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Job Title</label>
											<select id="jobtitle" class="form-control form-control-sm" name="jobtitle">
												<option selected>Select</option>
												@foreach($jobtitles as $jobtitle)
												<option value="{{$jobtitle->id}}"
													{{$jobtitle->id == $employee->emp_job_code  ? 'selected' : ''}}>
													{{$jobtitle->title}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Job Status</label>
											<select id="jobstatus" class="form-control form-control-sm"
												name="jobstatus">
												<option selected>Choose...</option>
												@foreach($employmentstatus as $employmentstatu)

												<option value="{{$employmentstatu->id}}"
													{{$employmentstatu->id== $employee->emp_status  ? 'selected' : ''}}>
													{{$employmentstatu->emp_status}}</option>
												@endforeach
											</select>
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Date Assigned</label>
											<input type="date" class="form-control form-control-sm" id="dateassign"
												name="dateassign" value="{{$employee->emp_assign_date}}">
										</div>
									</div>
								</div>
							</div>
							<div class="card shadow-none mb-3">
								<div class="card-body bg-light">
									<h6 class="title-style my-3"><span>Work Location Information</span></h6>
									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Location</label>
											<select name="location"
												class="form-control form-control-sm {{ $errors->has('location') ? ' has-error' : '' }} shipClass">
												<option value="">Select</option>
												@foreach($branch as $branches)
												<option value="{{$branches->id}}"
													{{$branches->id == $employee->emp_location  ? 'selected' : ''}}>
													{{$branches->location}}</option>
												@endforeach
											</select>
											@if ($errors->has('location'))
											<span class="help-block">
												<strong>{{ $errors->first('location') }}</strong>
											</span>
											@endif
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Work Shift</label>
											<select name="shift"
												class="form-control form-control-sm {{ $errors->has('shift') ? ' has-error' : '' }} shipClass">
												<option value="">Please Select</option>
												@foreach($shift_type as $shift_types)
												<option value="{{$shift_types->id}}"
													{{$shift_types->id == $employee->emp_shift  ? 'selected' : ''}}>
													{{$shift_types->shift_name}}</option>
												@endforeach
											</select>
											@if ($errors->has('shift'))
											<span class="help-block">
												<strong>{{ $errors->first('shift') }}</strong>
											</span>
											@endif
										</div>
									</div>
									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Company</label>
											<select name="employeecompany" id="company"
												class="form-control form-control-sm {{ $errors->has('employeecompany') ? ' has-error' : '' }} shipClass">
												<option value="">Please Select</option>
												@foreach($company as $companies)
												<option value="{{$companies->id}}"
													{{$companies->id == $employee->emp_company  ? 'selected' : ''}}>
													{{$companies->name}}</option>
												@endforeach
											</select>
											@if ($errors->has('employeecompany'))
											<span class="help-block">
												<strong>{{ $errors->first('employeecompany') }}</strong>
											</span>
											@endif
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark"> </label>
											<select name="department" id="department"
												class="form-control form-control-sm shipClass {{ $errors->has('department') ? ' has-error' : '' }}">
												<option value="">Select</option>
												@foreach($departments as $dept)
												<option value="{{$dept->id}}"
													{{$dept->id == $employee->emp_department  ? 'selected' : ''}}>
													{{$dept->name}}</option>
												@endforeach
											</select>
											@if ($errors->has('department'))
											<span class="help-block">
												<strong>{{ $errors->first('department') }}</strong>
											</span>
											@endif
										</div>
									</div>
									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Region</label>
											<select name="region_id" id="region_id"
												class="form-control form-control-sm shipClass {{ $errors->has('region_id') ? ' has-error' : '' }}">
												<option value="">Select</option>
												<?= ($employee->region_id != '') ? '<option value="'.$employee->region_id.'" selected >'.$employee->region->region.'</option>' : '' ?>
											</select>
											@if ($errors->has('region_id'))
											<span class="help-block">
												<strong>{{ $errors->first('region_id') }}</strong>
											</span>
											@endif
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Sub Region</label>
											<select name="subregion_id" id="subregion_id"
												class="form-control form-control-sm shipClass {{ $errors->has('subregion_id') ? ' has-error' : '' }}">
												<option value="">Select</option>
												<?= ($employee->subregion_id != '') ? '<option value="'.$employee->subregion_id.'" selected >'.$employee->sub_region->subregion.'</option>' : '' ?>
											</select>
											@if ($errors->has('subregion_id'))
											<span class="help-block">
												<strong>{{ $errors->first('subregion_id') }}</strong>
											</span>
											@endif
										</div>
									</div>
									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Employee Type</label>
											<select name="emptype_id" id="emptype_id"
												class="form-control form-control-sm shipClass {{ $errors->has('emptype_id') ? ' has-error' : '' }}">
												<option value="">Select</option>
												<?= ($employee->emptype_id != '') ? '<option value="'.$employee->emptype_id.'" selected >'.$employee->emp_type->emptype.'</option>' : '' ?>
											</select>
											@if ($errors->has('emptype_id'))
											<span class="help-block">
												<strong>{{ $errors->first('emptype_id') }}</strong>
											</span>
											@endif
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Employee Category</label>
											<select name="employeecategory" id="empcat"
												class="form-control form-control-sm shipClass {{ $errors->has('employeecategory') ? ' has-error' : '' }}">
												<option value="">Select</option>
												@foreach($employeecat as $empcategory)
												<option value="{{$empcategory->id}}"
													{{$empcategory->id == $employee->emp_category  ? 'selected' : ''}}>
													{{$empcategory->emp_category}}</option>
												@endforeach
											</select>
											@if ($errors->has('employeecategory'))
											<span class="help-block">
												<strong>{{ $errors->first('employeecategory') }}</strong>
											</span>
											@endif
										</div>
									</div>
									<div class="form-row" hidden>
										<div class="col">
											<label class="small font-weight-bold text-dark">No of Casual Leaves</label>
											<input type="text" min="0" class="form-control form-control-sm num"
												id="no_of_casual_leaves" name="no_of_casual_leaves"
												value="{{$employee->no_of_casual_leaves}}">
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">No of Annual Leaves</label>
											<input type="text" min="0" class="form-control form-control-sm num"
												id="no_of_annual_leaves" name="no_of_annual_leaves"
												value="{{$employee->no_of_annual_leaves}}">
										</div>
									</div>

									<input type="hidden" class="form-control form-control-sm" id="id" name="id"
										placeholder="First Name" value="{{$employee->id}}" readonly>

									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Emp epf etf regnum
												verified</label>
											<input type="text" class="form-control form-control-sm"
												id="emp_epf_etf_regnum_verified" name="emp_epf_etf_regnum_verified"
												value="{{$employee->emp_epf_etf_regnum_verified}}">
										</div>
									</div>

									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Emp epf etf regnum
												verified</label><br>
											<div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="yes" name="emp_epf_etf_regnum_verified"
													class="custom-control-input" value="1"
													{{ ($employee->emp_epf_etf_regnum_verified=="1")? "checked" : "" }}>
												<label class="custom-control-label" for="yes">Yes</label>
											</div>
											<div class="custom-control custom-radio custom-control-inline">
												<input type="radio" id="no" name="emp_epf_etf_regnum_verified"
													class="custom-control-input" value="0"
													{{ ($employee->emp_epf_etf_regnum_verified=="0")? "checked" : "" }}>
												<label class="custom-control-label" for="no">No</label>
											</div>
										</div>
									</div>

									<div class="form-group mt-3 text-right">
										@can('employee-edit')
										<button type="submit" class="btn btn-outline-primary btn-sm  px-4"><i
												class="fas fa-pencil-alt mr-2"></i>&nbsp;Update</button>
										@endcan
									</div>
								</div>
							</div>
						</form>

					</div>
					@include('layouts.employeeRightBar')
				</div>
				<div class="row">

					<div class="col-12">
						<div class="card shadow-none mb-3">
							<div class="card-body bg-light">
								<hr class="border-dark">
								<h5 class="font-weight-normal">Attachments</h5>
								<form class="form-horizontal" method="POST" action="{{ route('empoyeeAttachment') }}"
									enctype="multipart/form-data">
									{{ csrf_field() }}
									<div class="form-row">
										<div class="col">
											<label class="small font-weight-bold text-dark">Select File</label><br>
											<input type="file" class="form-control form-control-sm" id="empattachment"
												name="empattachment">
											@if ($errors->has('empattachment'))
											<span class="help-block">
												<strong>{{ $errors->first('empattachment') }}</strong>
											</span>
											@endif
										</div>
										<div class="col">
											<label class="small font-weight-bold text-dark">Comment</label>
											<textarea class="form-control form-control-sm" id="empcomment"
												name="empcomment" rows="3"></textarea>
											@if ($errors->has('empcomment'))
											<span class="help-block">
												<strong>{{ $errors->first('empcomment') }}</strong>
											</span>
											@endif
										</div>
									</div>
									<div class="form-group mt-3">
										@can('employee-edit')
										<button type="submit" name="" id=""
											class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
												class="fas fa-save"></i>&nbsp;Add Attachments</button>
										@endcan
									</div>
									<input type="hidden" class="form-control" id="id" name="id"
										value="{{$employee->id}}">
								</form>

								<hr>
								<h3>Attachments</h3>


								@php $count = 1; @endphp
								<table>
									@foreach($attachments as $att)
									<tr>
										<td> <a
												href="{{route('download_file', $att->emp_ath_file_name)}}">{{'Attachment - '. $count}}</a>
										</td>
										<td style="padding-left: 25px;"> <button type="button"
												class="btn btn-sm btn-danger btn-delete" data-id="{{$att->emp_ath_id}}">
												<i class="fa fa-trash"></i> </button> </td>
									</tr>

									@php $count++ @endphp
									@endforeach
								</table>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

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


@endsection

@section('script')
<script>

	let delete_id = 0;
	$(document).on('click', '.btn-delete', function () {
		delete_id = $(this).data('id');
		$('#confirmModal').modal('show');
		$('#ok_button').text('Delete');
	});

	$('#ok_button').click(function () {
		$.ajax({
			url: "../attachment/destroy/" + delete_id,
			beforeSend: function () {
				$('#ok_button').text('Deleting...');
			},
			success: function (data) { //alert(data);
				let html = '<div class="alert alert-success">' + data.success + '</div>';
				$('#confirmModal').modal('hide');
				$('#att_msg').html(html);
				location.reload();
			}
		})
	});

	let company = $('#company');
	let department = $('#department');

	department.select2({
		placeholder: 'Select...',
		width: '100%',
		allowClear: true,
		ajax: {
			url: '{{url("department_list_sel2")}}',
			dataType: 'json',
			data: function (params) {
				return {
					term: params.term || '',
					page: params.page || 1,
					company: company.val()
				}
			},
			cache: true
		}
	});

	let region_id = $('#region_id');
	region_id.select2({
		placeholder: 'Select...',
		width: '100%',
		allowClear: true,
		ajax: {
			url: '{{url("region_list_sel2")}}',
			dataType: 'json',
			data: function (params) {
				return {
					term: params.term || '',
					page: params.page || 1
				}
			},
			cache: true
		}
	});

	let subregion_id = $('#subregion_id');
	subregion_id.select2({
		placeholder: 'Select...',
		width: '100%',
		allowClear: true,
		ajax: {
			url: '{{url("subregion_list_sel2")}}',
			dataType: 'json',
			data: function (params) {
				return {
					term: params.term || '',
					page: params.page || 1,
					region_id: region_id.val()
				}
			},
			cache: true
		}
	});

	let emptype_id = $('#emptype_id');
	emptype_id.select2({
		placeholder: 'Select...',
		width: '100%',
		allowClear: true,
		ajax: {
			url: '{{url("emptype_list_sel2")}}',
			dataType: 'json',
			data: function (params) {
				return {
					term: params.term || '',
					page: params.page || 1
				}
			},
			cache: true
		}
	});

	// $('#licenseexpiredate').datepicker({
	// 	format: "yyyy/mm/dd",
	// 	autoclose: true
	// });
	// $('#birthday').datepicker({
	// 	format: "yyyy/mm/dd",
	// 	autoclose: true
	// });
	// $('#joindate').datepicker({
	// 	format: "yyyy/mm/dd",
	// 	autoclose: true
	// });
	// $('#dateassign').datepicker({
	// 	format: "yyyy/mm/dd",
	// 	autoclose: true
	// });

	$('.num').keypress(function (event) {
		return isNumber(event, this)
	});

	function isNumber(evt, element) {
		var charCode = (evt.which) ? evt.which : event.keyCode
		if (
			(charCode != 46 || $(element).val().indexOf('.') != -1) && // “.” CHECK DOT, AND ONLY ONE.
			(charCode < 48 || charCode > 57))
			return false;
		return true;
	}
</script>
<script>
	$(document).ready(function () {
		var id_hidden = parseInt($('#hidden_id').val());
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		})

		$.ajax({
			url: '{!! route("employeetypeget") !!}',
			type: 'POST',
			dataType: "json",
			data: {
				id: id_hidden
			},
			success: function (data) {
				if (data.result) {
					// console.log(data.result.id);
					// console.log(data.result.emp_category);
					$('#employeetype').val(data.result.emp_category)
					sidebar(data.result.emp_category)
				} else {
					console.log(data.error);
					alert(data.error)
				}

			}

		})
	});
</script>
<script>
	$('#prev_record').click(function () {
		var id_hidden = parseInt($('#hidden_id').val());
		var employeetype = $('#employeetype').val();
		$.ajax({
			url: '{!! route("employeeprerecord") !!}',
			type: 'POST',
			dataType: "json",
			data: {
				id: id_hidden,
				employeetype: employeetype
			},
			success: function (data) {
				if (data.result) {
					if (!isNaN(data.result.id)) {
						var href = data.result.id;
						window.location.href = href;
					}
					else{
						console.log("data get error");
					}

				} else {
					console.log(data.error);
					alert(data.error)
				}

			}

		})
	});

	$('#next_record').click(function () {
		var id_hidden = parseInt($('#hidden_id').val());
		var employeetype = $('#employeetype').val();
		$.ajax({
			url: '{!! route("employeenextrecord") !!}',
			type: 'POST',
			dataType: "json",
			data: {
				id: id_hidden,
				employeetype: employeetype
			},
			success: function (data) {
				if (data.result) {
					if (!isNaN(data.result.id)) {
						var href = data.result.id;
						console.log(data.result.id);
						window.location.href = href;
					}
					else{
						console.log("data get error");
					}

				} else {
					console.log(data.error);
					alert(data.error)
				}

			}

		})

	});


	$('#searchbtn').click(function () {
		var serviceno = $('#serviceno').val();
		var employeetype = $('#employeetype').val();
		
		var empcategory='';
		if(employeetype==1){
			empcategory="Office Staffs";
		}else{
			empcategory="Security Staffs";
		}

		if (serviceno == '') {
			$("#submitbtn").click();
		} else {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			})
			var emptype = $('#employeetype').val()
			$.ajax({
				url: '{!! route("employeeidgetsearch") !!}',
				type: 'POST',
				dataType: "json",
				data: {
					id: serviceno
				},
				success: function (data) {
					if (data.result) {
						if (data.result.emp_category == emptype) {
							// console.log("securitystaff");
							getDetailsBysearchbtn(data.result.id);
						} else {
							// console.log("not securitystaff");
							alert("This is Not "+empcategory+" Employee.")
						}

					} else {
						console.log(data.error);
						alert(data.error)
					}

				}

			})
		}

	});

	function getDetailsBysearchbtn(id) {
		if (!isNaN(id)) {
			var href = "" + id;
			window.location.href = href;
		}
	}
</script>
{{-- side bar expand --}}
<script>

function sidebar(sidebaractive){
	if(sidebaractive==1){
		console.log(sidebaractive);
		$('#collapseemployee').addClass('show');
		$('#office_staff_collapse').addClass('show');
		$('#employee_collapse').addClass('show');
		$('#employee_add_link').addClass('active');
	}else{
		console.log(sidebaractive);
		$('#collapseemployee').addClass('show');
		$('#security_staff_collapse').addClass('show');
		$('#securityemployee_collapse').addClass('show');
		$('#securityemployee_add_link').addClass('active');
	}
}
	
	</script>

	<script>
		// service no check
		$('#service_no').keyup(function () {
    		var serviceno = $(this).val();

			if(serviceno){
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			})
			$.ajax({
				url: '{!! route("employeecheckserviceno") !!}',
				type: 'POST',
				dataType: "json",
				data: {
					service_no: serviceno
				},
				success: function (data) {
					var checkservice = $('#checkservice');
            if (data.success) {
                checkservice.text(data.success).css('color', 'red');
            } else if (data.error) {
                checkservice.text(data.error).css('color', 'green');
            }

				}

			})
		}else{
			var checkservice = $('#checkservice');
			checkservice.text('');
		}
		});

		// epf no check
		$('#emp_etfno').keyup(function () {
    		var emp_etfno = $(this).val();

			if(emp_etfno){
				$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			})
			$.ajax({
				url: '{!! route("employeecheckemp_etfno") !!}',
				type: 'POST',
				dataType: "json",
				data: {
					emp_etfno: emp_etfno
				},
				success: function (data) {
					var checketf = $('#checketf');
            if (data.success) {
                checketf.text(data.success).css('color', 'red');
            } else if (data.error) {
                checketf.text(data.error).css('color', 'green');
            }
				}

			})
			}
			else{
				var checketf = $('#checketf');
				checketf.text('');
			}
			
		});

		// nic no check
		$('#nicnumber').keyup(function () {
    		var nicnumber = $(this).val();

			if(nicnumber){
				$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			})
			$.ajax({
				url: '{!! route("employeechecknicnumber") !!}',
				type: 'POST',
				dataType: "json",
				data: {
					nicnumber: nicnumber
				},
				success: function (data) {
					var checknic = $('#checknic');
            if (data.success) {
                checknic.text(data.success).css('color', 'red');
            } else if (data.error) {
                checknic.text(data.error).css('color', 'green');
            }
				}

			})
			}
			else{
				var checknic = $('#checknic');
				checknic.text('');
			}
			
		});
	</script>

@endsection