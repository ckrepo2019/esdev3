@extends('hr.layouts.app')
<style>
    .highlighted-tab {
        background-color: #002833;
        border-radius: 5px;
        padding: 10px;
        font-weight: bold;
    }

    #studentInfoTabs .nav-link.active {
        background-color: #002833;
        color: white !important;
        border: 2px solid #002833 !important;
        border-radius: 5px;
    }

    #studentInfoTabs .nav-link {
        color: #002833;
    }

    #studentInfoTabs .nav-link:hover {
        background-color: #002833;
        color: #ffffff;
    }

    .profile-card {
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 20px;
        text-align: center;
        /* Center-align content */
    }

    .profile-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        /* Make it circular */
        margin-bottom: 15px;
    }

    .custom-dropdown .select2-selection {
        height: 50px;
        /* Customize the height as needed */
    }
</style>
@section('content')
    <section class="content-header p-0">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>+ New Employee</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="/hr/employees/index">Employees</a></li>
                        <li class="breadcrumb-item active">Add new employee</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <hr class="m-1" />
    @php
        $offices = DB::table('hr_offices')
            ->select('hr_offices.*', 'sy.sydesc')
            ->join('sy', 'hr_offices.syid', '=', 'sy.id')
            ->where('hr_offices.deleted', '0')
            ->get();
        $departments = DB::table('hr_departments')->where('deleted', '0')->get();

        $usertypes = DB::table('usertype')
            ->where('deleted', '0')
            ->where('id', '!=', '7')
            ->where('id', '!=', '9')
            ->get();

        $employees = DB::table('teacher')
            ->select(DB::raw("concat(lastname,', ',firstname) as full_name"), 'id')
            ->where('deleted', '0')
            ->orderBy('lastname', 'ASC')
            ->groupBy('full_name')
            ->get();

        $job_status = DB::table('hr_empstatus')
            ->select('description as text', 'id')
            ->where('deleted', '0')
            ->orderBy('description', 'ASC')
            ->get();

    @endphp
    {{-- <form action="/hr/employees/addnewemployee/save" method="get" class="m-0 p-0">
        <div class="row mb-2">
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Submit</button>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="card shadow"
                    style="border: 1px solid #ddd; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                    <div class="card-header p-2 bg-warning">
                        <h5 class="m-0">Personal Information</h5>
                    </div>
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control form-control-sm" />
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Last Name</label>
                                <input type="text" name="lastname" class="form-control form-control-sm" required />
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>First Name</label>
                                <input type="text" name="firstname" class="form-control form-control-sm" required />
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Middle Name</label>
                                <input type="text" name="middlename" class="form-control form-control-sm" />
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Suffix</label>
                                <input type="text" name="suffix" class="form-control form-control-sm" />
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Gender</label>
                                <select name="gender" class="form-control form-control-sm text-uppercase">
                                    <option value="male">MALE</option>
                                    <option value="female">FEMALE</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Date of Birth</label>
                                <input type="date" name="dob" class="form-control form-control-sm" />
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Civil Status</label>
                                <select name="civilstatus" class="form-control form-control-sm text-uppercase">
                                    @foreach ($civilstatus as $status)
                                        <option value="{{ $status->id }}">{{ $status->civilstatus }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Contact No.</label>
                                <input type="text" name="contactnumber" class="form-control form-control-sm"
                                    minlength="11" maxlength="11" data-inputmask-clearmaskonlostfocus="true" />
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Email Address</label>
                                <input type="text" name="emailaddress" class="form-control form-control-sm" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow"
                    style="border: 1px solid #ddd; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                    <div class="card-header p-2 bg-warning">
                        <h5 class="m-0">Present Address</h5>
                    </div>
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Street</label>
                                <input type="text" name="presstreet" class="form-control form-control-sm" />
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Barangay</label>
                                <input type="text" name="presbarangay" class="form-control form-control-sm" />
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>City</label>
                                <input type="text" name="prescity" class="form-control form-control-sm" />
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Province</label>
                                <input type="text" name="presprovince" class="form-control form-control-sm" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow"
                    style="border: 1px solid #ddd; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                    <div class="card-header p-2 bg-warning">
                        <h5 class="m-0">Emergency Contact Info</h5>
                    </div>
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Name</label>
                                <input type="text" name="emername" class="form-control form-control-sm" />
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Relation</label>
                                <input type="text" name="emerrelation" class="form-control form-control-sm" />
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Contact No.</label>
                                <input type="text" name="emercontactno" class="form-control form-control-sm"
                                    minlength="11" maxlength="11" data-inputmask-clearmaskonlostfocus="true" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow"
                    style="border: 1px solid #ddd; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                    <div class="card-header p-2 bg-warning">
                        <h5 class="m-0">Other Info</h5>
                    </div>
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label>Spouse Employment</label>
                                <input type="text" name="spouseemp" class="form-control form-control-sm" />
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>No. of children</label>
                                <input type="number" name="noofchildren" class="form-control form-control-sm" />
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Religion</label>
                                <select name="religionid" class="form-control form-control-sm text-uppercase">
                                    <option>Select Religion</option>
                                    @foreach ($religions as $religion)
                                        <option value="{{ $religion->id }}">{{ $religion->religionname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Nationality</label>
                                <select name="nationalityid" class="form-control form-control-sm text-uppercase">
                                    <option value="0">None</option>
                                    @foreach ($nationalities as $nationality)
                                        <option value="{{ $nationality->id }}"
                                            {{ $nationality->nationality == 'Filipino' ? 'selected' : '' }}>
                                            {{ $nationality->nationality }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Office assigned to</label>
                                <select name="officeid" class="form-control form-control-sm text-uppercase">
                                    <option value="0">None</option>
                                    @foreach ($offices as $office)
                                        <option value="{{ $office->id }}">{{ $office->sydesc }} -
                                            {{ $office->officename }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Department</label>
                                <select name="departmentid" class="form-control form-control-sm text-uppercase">
                                    <option value="0">None</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->department }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Designation</label>
                                <select name="designationid" class="form-control form-control-sm text-uppercase">
                                    <option value="0">None</option>
                                    @foreach ($usertypes as $usertype)
                                        <option value="{{ $usertype->id }}">{{ $usertype->utype }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group clearfix" id="academicprogram"></div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>License No.</label>
                                <input type="text" name="licensenumber" class="form-control form-control-sm" />
                            </div>
                            <div class="col-md-12 mb-2">
                                <label>Date Hired</label>
                                <input type="date" name="datehired" class="form-control form-control-sm" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form> --}}
    <br>

    {{-- <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card profile-card">
                    <img src="placeholder-image.jpg" alt="Profile Picture" class="profile-image img-fluid mx-auto d-block">
                    <!-- Replace with actual image -->
                    <div class="card-body">
                        <div class="form-group">
                            <label>Employee ID</label>
                            <p>Not Assigned</p>
                        </div>
                        <div class="form-group">
                            <label>Designation</label>
                            <p>Not Assigned</p>
                        </div>
                        <button type="button" class="btn btn-success btn-block">Update Profile Picture</button>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}



    <div class="container-fluid">
        <div class="row  d-flex">
            <div class="col-md-4">
                <div class="card profile-card">
                    {{-- <img src="placeholder-image.jpg" alt="Profile Picture" class="profile-image img-fluid mx-auto d-block"> --}}

                    <img src="{{ asset('avatar/S(F) 1.png') }}" alt="Profile Picture"
                        class="profile-image img-fluid mx-auto d-block" id="profile_picture">

                    <div class="card-body">
                        <div class="d-flex justify-content-between form-group">
                            <label>Employee ID</label>
                            <p class="mb-0">Not Assigned</p>
                        </div>
                        <hr />

                        <div class="d-flex justify-content-between form-group">
                            <label>Designation</label>
                            <p class="mb-0">Not Assigned</p>
                        </div>
                        <hr />

                        <br>
                        <label class="btn btn-success btn-block" for="employee_picture" style="cursor: pointer;">Upload
                            Profile Picture</label>
                        <input type="file" id="employee_picture" style="display: none;" />

                        {{-- <button type="button" class="btn btn-success btn-block">Update Profile Picture</button> --}}
                    </div>
                </div>
            </div>

            <div class="col-md-8">

                <ul class="nav nav-tabs" id="studentInfoTabs" role="tablist">

                    {{-- <li class="nav-item mr-3" role="presentation">
                        <a class="nav-link" href="{{ url('setup/gradingsetup') }}">Employee Information</a>
                    </li> --}}

                    <li class="nav-item mr-3" role="presentation">
                        <a class="nav-link " href="{{ url('hr/employees/addnewemployee/index') }}">Employee
                            Information</a>
                    </li>

                    <li class="nav-item mr-3" role="presentation">
                        <a class="nav-link active" href="{{ url('hr/employees/newemployee_employmentDetails') }}">Employment
                            Details</a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link ">Ledger of Leave Credits</a>
                    </li>
                </ul>

                <hr class="mt-4" />

                <div class="container">
                    <h6>Employment Details</h1>
                        <br>
                        <form>


                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="firstName" class="form-label">Designation</label>
                                    <select class="form-control" id="select-designation">
                                        <option value="male"></option>
                                        <option value="female"></option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="middleName" class="form-label">Department</label>
                                    <select class="form-control" id="select-department">

                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="lastName" class="form-label">Office</label>
                                    <select class="form-control" id="select-office">

                                    </select>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="firstName" class="form-label">Date Hired</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="middleName" class="form-label">Accumulated Y.0.S</label>
                                    <input type="text" class="form-control">
                                </div>

                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="firstName" class="form-label">Job Status</label>
                                    <select class="form-control" id="select-jobstatus">
                                        @foreach ($job_status as $job)
                                            <option value="{{ $job->id }}">{{ $job->text }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="middleName" class="form-label">Probationary Start Date</label>
                                    <input type="date" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="lastName" class="form-label">Probationary End Date</label>
                                    <input type="date" class="form-control">
                                </div>
                            </div>

                        </form>
                </div>

                <hr class="mt-4" />

                <div class="container">
                    <div class="d-flex align-items-center">
                        <h6>Approval Sequence <span class="badge badge-success ml-4" style="cursor: pointer;"
                                id="addapproval"><i class="fas fa-plus fa-sm"></i></span></h3>
                    </div>
                    <br>
                    <form>
                        <div class="approval_section">
                            <div class="approval_row">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="" class="form-label">1st Approval</label>

                                    </div>
                                    <div class="col-md-5 ml-5 d-flex">
                                        <select class="form-control" id="select-approval">
                                            <option value="">Select Employee</option>
                                            @foreach ($employees as $item)
                                                <option value="{{ $item->id }}">{{ $item->full_name }}
                                                </option>
                                            @endforeach

                                        </select>

                                        <button type="button" class="btn btn-sm btn-danger remove_approval mt-2 ml-1"
                                            style="cursor: pointer;width:26px;height: 25px;" hidden><i
                                                class="fas fa-times"></i></button>
                                    </div>

                                </div>
                                <br>
                            </div>

                        </div>





                    </form>
                </div>


                <hr class="mt-4" />

                <div class="container">
                    <div class="d-flex align-items-center">
                        <h6>Workday</h6>
                    </div>

                    <div class="col-md-5 d-flex">
                        <select class="form-control" id="sex">
                            <option value="male"></option>
                            <option value="female"></option>
                        </select>
                        <span class="badge badge-primary mt-2 ml-4" style="cursor: pointer;width:26px;height: 25px;"
                            id=""><i class="fas fa-th-large mt-1"></i></span>

                    </div>


                </div>



                <br>

                <div class="container">
                    <div class="d-flex align-items-center">
                        <h6>Daily Time Record</h3>
                    </div>
                    <br>
                    <form>
                        <div class="work_experience_section">
                            <div class="work_experience_row">
                                <div class="row g-3">

                                    <div class="col-md-3">
                                        <label for="Datefrom" class="form-label">Period</label>
                                        <input type="date" class="form-control" id="Datefrom" style="width: 170px">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="Dateto" class="form-label ml-4">Office Hours</label>
                                        <div class="d-flex align-items-center">
                                            <input type="time" class="form-control ml-4" id="Dateto"
                                                style="width: 200px">
                                            <button type="button"
                                                class="btn btn-sm btn-danger remove_work_experience ml-1" hidden><i
                                                    class="fas fa-times"></i></button>
                                        </div>

                                    </div>


                                </div>
                            </div>
                        </div>

                    </form>
                </div>



                <div class="submit-section text-center mt-4">
                    <button type="submit" id="save_employeebtn_employmentdetails" class="btn btn-success submit-btn"
                        id="btn-save">Save</button>
                </div>



            </div>
        </div>
    </div>



    <div>

    </div>

    <div id="addmoretables" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-12 text-success mb-3">
                            New employee added successfully!
                        </div>
                        <div class="col-12">
                            Do you want to add another employee?
                        </div>
                    </div>
                    <div class="submit-section">
                        <a href="/hr/employees/index" class="btn btn-secondary submit-btn float-left text-white">No</a>
                        <a href="/hr/employees/addnewemployee/index"
                            class="btn btn-primary submit-btn float-right text-white">Yes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>

    <script>
        @if (session()->has('feedback'))
            @if (session()->get('feedback') == '1')
                $("#addmoretables").modal("toggle");
            @endif
        @endif
        $(document).ready(function() {
            $('input.form-control').on('input', function() {
                if ($(this).val().replace(/^\s+|\s+$/g, "").length == 0) {
                    $(this).removeClass('is-valid')
                } else {
                    $(this).addClass('is-valid')
                }
            })
        })
        // $(document).on('change','select[name="departmentid"]', function(){
        //     $.ajax({
        //         url: '/hr/employees/getdesignations',
        //         type:"GET",
        //         dataType:"json",
        //         data:{
        //             departmentid:$(this).val(),
        //         },
        //         success:function(data) {
        //             $('select[name="designationid"]').empty();
        //             if(data.length == '0'){
        //                 $('select[name="designationid"]').append(
        //                         '<option value="">NO DESIGNATIONS ASSIGNED!</option>'
        //                 );

        //             }else{
        //                 $('select[name="designationid"]').append(
        //                     '<option>Select Designation</option>'
        //                 );
        //                 $.each(data, function(key, value){
        //                     $('select[name="designationid"]').append(
        //                         '<option value="'+value.id+'">'+value.utype+'</option>'
        //                     );
        //                 });
        //             }
        //         }
        //     });
        // });
        $(document).on('change', 'select[name="designationid"]', function() {
            $('#academicprogram').empty();
            if ($(this)[0].selectedOptions[0].text == 'TEACHER' || $(this)[0].selectedOptions[0].text ==
                'PRINCIPAL') {
                $('.submit-btn').attr('id', 'submitbutton');
                $('.submit-btn').prop('type', 'button');
                $.ajax({
                    url: '/hr/employees/getacademicprogram',
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(key, value) {
                            $('#academicprogram').append(
                                '<div class="icheck-primary d-inline">' +
                                '<input type="checkbox" id="checkboxPrimary' + value.id +
                                '" name="academicprogram[]" value="' + value.id +
                                '" checked="">' +
                                '<label for="checkboxPrimary' + value.id + '">' +
                                value.progname +
                                '</label>' +
                                '</div>' +
                                '<br>'
                            );
                        })
                    }
                });
            }
        });
        $(document).ready(function() {

            // $('#sex').select2()

            // $('body').addClass('sidebar-collapse')
            $('input[name="contactno"]').inputmask({
                mask: "9999-999-9999"
            });
            $('input[name="emercontactno"]').inputmask({
                mask: "9999-999-9999"
            });

        });
        $(document).on('click', '#submitbutton', function() {
            if ($('input[name="academicprogram[]"]:checked').length == 0) {
                $('#academicprogram').prepend(
                    '<div class="row">' +
                    '<div class="text-danger">' +
                    'Please select an academic program!' +
                    '</div>' +
                    '</div>'
                )
            } else {
                $(this).prop('type', 'submit');
            }
        })
        $(document).on("input", 'input[name="noofchildren"]', function() {
            this.value = this.value.replace(/\D/g, '');
        });
        // $(document).on("input", 'input[name="licensenumber"]', function() {
        //     this.value = this.value.replace(/\D/g,'');
        // });
        $(document).on("click", "#addhighest_education", function(e) {
            e.preventDefault();
            var work_experience_section = $(".highest_education_section");
            var work_experience_row = $(".highest_education_row").first().clone();
            work_experience_row.find("input").val("");
            work_experience_row.find(".remove_highest_education").prop('hidden', false);
            work_experience_section.append(work_experience_row);
        });

        $(document).on("click", ".remove_highest_education", function(e) {
            e.preventDefault();
            $(this).closest('.highest_education_row').remove();
        });



        $(document).on("click", "#addwork_expereiences", function(e) {
            e.preventDefault();
            var work_experience_section = $(".work_experience_section");
            var work_experience_row = $(".work_experience_row").first().clone();
            work_experience_row.find("input").val("");
            work_experience_row.find(".remove_work_experience").prop('hidden', false);
            work_experience_section.append(work_experience_row);
        });

        $(document).on("click", ".remove_work_experience", function(e) {
            e.preventDefault();
            $(this).closest('.work_experience_row').remove();
        });

        $(document).on("click", "#addbank_account", function(e) {
            e.preventDefault();
            var addbank_account_section = $(".addbank_account_section");
            var addbank_account_row = $(".addbank_account_row").first().clone();
            addbank_account_row.find("input").val("");
            addbank_account_row.find(".bank_name").prop('hidden', false);
            addbank_account_row.find(".bank_number").prop('hidden', false);
            addbank_account_row.find(".remove_bank_account_static").prop('hidden', true);
            addbank_account_row.find(".bpi_name").prop('hidden', true);
            addbank_account_row.find(".bpi_number").prop('hidden', true);
            addbank_account_row.find(".remove_bank_account").prop('hidden', false);
            addbank_account_section.append(addbank_account_row);
        });


        $(document).on("click", ".remove_bank_account", function(e) {
            e.preventDefault();
            $(this).closest('.addbank_account_row').remove();
        });

        $(document).on("click", ".remove_bank_account_static", function(e) {
            e.preventDefault();
            $(this).closest('.addbank_account_row').remove();
        });

        $(document).on("click", "#save_employeebtn", function(e) {
            e.preventDefault();
            add_newemployee();
        });

        function add_newemployee() {

            var minGradeRequirement = $('#minGradeRequirement').val();
            var setActivePointEquivalency = $('#input_Active_recognitiontype').is(':checked') ? 1 : 0;
            var recognitionTypeDescription = $('#recognitionTypeDescription').val();

            $.ajax({
                type: 'GET',
                url: '/setup/academicrecognition/create',
                data: {
                    recognitionTypeDesc: recognitionTypeDescription,
                    minGradeRequirement: minGradeRequirement,
                    setActive: setActivePointEquivalency

                },
                success: function(data) {
                    if (data[0].status == 2) {
                        Toast.fire({
                            type: 'warning',
                            title: data[0].message
                        })


                    } else if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully created'
                        })


                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            });
        }

        $(document).on('change', '#employee_picture', function() {
            var file = $(this)[0].files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#profile_picture').attr('src', e.target.result);
            }

            reader.readAsDataURL(file);
        });

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        })

        $('#save_employeebtn').on('click', function(event) {
            // event.preventDefault();
            add_newemployee()

        });


        function add_newemployee() {

            var rfid = $('#rfid').val();
            var firstName = $('#firstName').val();
            var middleName = $('#middleName').val();
            var lastName = $('#lastName').val();
            var suffix = $('#suffix').val();
            var sex = $('#sex').val();
            var civilStatus = $('#civilStatus').val();
            var birthdate = $('#birthdate').val();
            var cellphone = $('#cellphone').val();
            var email = $('#email').val();
            var address = $('#address').val();


            var contactFirstname = $('#contactFirstname').val();
            var contactMiddlename = $('#contactMiddlename').val();
            var contactLastname = $('#contactLastname').val();
            var contactSuffix = $('#contactSuffix').val();
            var Relationship = $('#Relationship').val();
            var Telephone = $('#Telephone').val();
            var Cellphone = $('#Cellphone').val();
            var Email = $('#Email').val();


            higher_educational_attainment = [];

            $('.highest_education_row').each(function() {
                var Schoolname = $(this).find('#school_name').val();
                var Year_graduated = $(this).find('#year_graduated').val();
                var Course = $(this).find('#course').val();
                var Award = $(this).find('#award').val();

                var higherEducational = {
                    Schoolname: Schoolname,
                    Year_graduated: Year_graduated,
                    Course: Course,
                    Award: Award
                };

                higher_educational_attainment.push(higherEducational);
            });


            work_experience = [];

            $('.work_experience_row').each(function() {
                var Company = $(this).find('#Company').val();
                var Designation = $(this).find('#Designation').val();
                var Datefrom = $(this).find('#Datefrom').val();
                var Dateto = $(this).find('#Dateto').val();

                var workExperience = {
                    Company: Company,
                    Designation: Designation,
                    Datefrom: Datefrom,
                    Dateto: Dateto
                };

                work_experience.push(workExperience);
            });


            bank_account = [];


            $('.addbank_account_row').each(function() {
                var append_bank_name = $(this).find('#append_bank_name').val();
                var append_bank_number = $(this).find('#append_bank_number').val();

                // Create an object for each bank account
                var bankAccount = {
                    append_bank_name: append_bank_name,
                    append_bank_number: append_bank_number
                };

                // Push bank account object to the array
                bank_account.push(bankAccount);
            });

            var formData = new FormData();

            higher_educational_attainment.forEach(function(higherEducational, index) {

                formData.append('higher_educational_attainments[' + index + '][Schoolname]', higherEducational
                    .Schoolname);
                formData.append('higher_educational_attainments[' + index + '][Year_graduated]', higherEducational
                    .Year_graduated);
                formData.append('higher_educational_attainments[' + index + '][Course]', higherEducational.Course);
                formData.append('higher_educational_attainments[' + index + '][Award]', higherEducational.Award);

            });

            work_experience.forEach(function(workExperience, index) {

                formData.append('work_experiences[' + index + '][Company]', workExperience.Company);
                formData.append('work_experiences[' + index + '][Designation]', workExperience
                    .Designation);
                formData.append('work_experiences[' + index + '][Datefrom]', workExperience.Datefrom);
                formData.append('work_experiences[' + index + '][Dateto]', workExperience.Dateto);

            });


            bank_account.forEach(function(bankAccount, index) {
                if (index > 0) {
                    formData.append('bank_accounts[' + index + '][append_bank_name]', bankAccount.append_bank_name);
                    formData.append('bank_accounts[' + index + '][append_bank_number]', bankAccount
                        .append_bank_number);
                }
            });

            var sss = $('#sss').val();
            var pagibig = $('#pagibig').val();
            var philhealth = $('#philhealth').val();
            var tin = $('#tin').val();


            formData.append('employee_picture', $('#employee_picture')[0].files[0]);

            formData.append('firstName', firstName);
            formData.append('middleName', middleName);
            formData.append('lastName', lastName);
            formData.append('suffix', suffix);
            formData.append('sex', sex);
            formData.append('civilStatus', civilStatus);
            formData.append('birthdate', birthdate);
            formData.append('cellphone', cellphone);
            formData.append('email', email);
            formData.append('address', address);
            formData.append('rfid', rfid);

            formData.append('contactFirstname', contactFirstname);
            formData.append('contactMiddlename', contactMiddlename);
            formData.append('contactLastname', contactLastname);
            formData.append('contactSuffix', contactSuffix);
            formData.append('Relationship', Relationship);
            formData.append('Telephone', Telephone);
            formData.append('Cellphone', Cellphone);
            formData.append('Email', Email);

            formData.append('SSS', sss);
            formData.append('PagIbig', pagibig);
            formData.append('Philhealth', philhealth);
            formData.append('TIN', tin);


            $.ajax({
                type: 'POST',
                url: '/newempoloyee/basicinformation/create',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data[0].status == 2) {
                        Toast.fire({
                            type: 'warning',
                            title: data[0].message
                        });
                    } else if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully created'
                        });
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        });
                    }
                }
            });
        }

        // function add_newemployee() {

        //     var rfid = $('#rfid').val();
        //     var firstName = $('#firstName').val();
        //     var middleName = $('#middleName').val();
        //     var lastName = $('#lastName').val();
        //     var suffix = $('#suffix').val();
        //     var sex = $('#sex').val();
        //     var civilStatus = $('#civilStatus').val();
        //     var birthdate = $('#birthdate').val();
        //     var cellphone = $('#cellphone').val();
        //     var email = $('#email').val();
        //     var address = $('#address').val();


        //     var contactFirstname = $('#contactFirstname').val();
        //     var contactMiddlename = $('#contactMiddlename').val();
        //     var contactLastname = $('#contactLastname').val();
        //     var contactSuffix = $('#contactSuffix').val();
        //     var Relationship = $('#Relationship').val();
        //     var Telephone = $('#Telephone').val();
        //     var Cellphone = $('#Cellphone').val();
        //     var Email = $('#Email').val();


        //     var higher_educational_attainment = [];

        //     $('.highest_education_section .highest_education_row').each(function() {
        //         var Schoolname = $(this).find('#school_name').val();
        //         var Year_graduated = $(this).find('#year_graduated').val();
        //         var Course = $(this).find('#course').val();
        //         var Award = $(this).find('#award').val();

        //         if (Schoolname && Year_graduated && Course && Award) {
        //             higher_educational_attainment.push({
        //                 Schoolname: Schoolname,
        //                 Year_graduated: Year_graduated,
        //                 Course: Course,
        //                 Award: Award
        //             });
        //         }
        //     });


        //     var work_experience = [];

        //     $('.work_experience_section .work_experience_row').each(function() {
        //         var Company = $(this).find('#Company').val();
        //         var Designation = $(this).find('#Designation').val();
        //         var Datefrom = $(this).find('#Datefrom').val();
        //         var Dateto = $(this).find('#Dateto').val();

        //         if (Company && Designation && Datefrom && Dateto) {
        //             work_experience.push({
        //                 Company: Company,
        //                 Designation: Designation,
        //                 Datefrom: Datefrom,
        //                 Dateto: Dateto
        //             });
        //         }
        //     });

        //     var bank_account = [];


        //     $('.addbank_account_section .addbank_account_row').each(function() {
        //         var append_bank_name = $(this).find('#append_bank_name').val();
        //         var append_bank_number = $(this).find('#append_bank_number').val();

        //         if (append_bank_name && append_bank_number) {
        //             bank_account.push({
        //                 append_bank_name: append_bank_name,
        //                 append_bank_number: append_bank_number
        //             });
        //         }
        //     });

        //     var sss = $('#sss').val();
        //     var pagibig = $('#pagibig').val();
        //     var philhealth = $('#philhealth').val();
        //     var tin = $('#tin').val();

        //     var formData = new FormData();
        //     formData.append('employee_picture', $('#employee_picture')[0].files[0]);

        //     formData.append('firstName', firstName);
        //     formData.append('middleName', middleName);
        //     formData.append('lastName', lastName);
        //     formData.append('suffix', suffix);
        //     formData.append('sex', sex);
        //     formData.append('civilStatus', civilStatus);
        //     formData.append('birthdate', birthdate);
        //     formData.append('cellphone', cellphone);
        //     formData.append('email', email);
        //     formData.append('address', address);
        //     formData.append('rfid', rfid);

        //     formData.append('contactFirstname', contactFirstname);
        //     formData.append('contactMiddlename', contactMiddlename);
        //     formData.append('contactLastname', contactLastname);
        //     formData.append('contactSuffix', contactSuffix);
        //     formData.append('Relationship', Relationship);
        //     formData.append('Telephone', Telephone);
        //     formData.append('Cellphone', Cellphone);
        //     formData.append('Email', Email);

        //     formData.append('work_experience', JSON.stringify(work_experience));
        //     formData.append('higher_educational_attainment', JSON.stringify(higher_educational_attainment));

        //     formData.append('SSS', sss);
        //     formData.append('PagIbig', pagibig);
        //     formData.append('Philhealth', philhealth);
        //     formData.append('TIN', tin);

        //     formData.append('bank_account', JSON.stringify(bank_account));

        //     $.ajax({
        //         type: 'POST',
        //         url: '/newempoloyee/basicinformation/create',
        //         data: formData,
        //         contentType: false,
        //         processData: false,
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(data) {
        //             if (data[0].status == 2) {
        //                 Toast.fire({
        //                     type: 'warning',
        //                     title: data[0].message
        //                 });
        //             } else if (data[0].status == 1) {
        //                 Toast.fire({
        //                     type: 'success',
        //                     title: 'Successfully created'
        //                 });
        //             } else {
        //                 Toast.fire({
        //                     type: 'error',
        //                     title: data[0].message
        //                 });
        //             }
        //         }
        //     });
        // }

        // function add_newemployee() {



        //     var rfid = $('#rfid').val();
        //     var firstName = $('#firstName').val();
        //     var middleName = $('#middleName').val();
        //     var lastName = $('#lastName').val();
        //     var suffix = $('#suffix').val();
        //     var sex = $('#sex').val();
        //     var civilStatus = $('#civilStatus').val();
        //     var birthdate = $('#birthdate').val();
        //     var cellphone = $('#cellphone').val();
        //     var email = $('#email').val();
        //     var address = $('#address').val();


        //     var contactFirstname = $('#contactFirstname').val();
        //     var contactMiddlename = $('#contactMiddlename').val();
        //     var contactLastname = $('#contactLastname').val();
        //     var contactSuffix = $('#contactSuffix').val();
        //     var Relationship = $('#Relationship').val();
        //     var Telephone = $('#Telephone').val();
        //     var Cellphone = $('#Cellphone').val();
        //     var Email = $('#Email').val();


        //     // var educational_attainment = [];
        //     // $("#gradingPointsTable").find("tbody tr").each(function() {

        //     //     var Company = $('#Company').val();
        //     //     var year_graduated = $('#year_graduated').val();
        //     //     var course = $('#course').val();
        //     //     var award = $('#award').val();

        //     //     if (Company && year_graduated && course &&
        //     //         award) {
        //     //         educational_attainment.push({
        //     //             Company: Company,
        //     //             year_graduated: year_graduated,
        //     //             course: course,
        //     //             award: award
        //     //         });
        //     //     }

        //     // });

        //     var higher_educational_attainment = [];

        //     $('.highest_education_section .highest_education_row').each(function() {
        //         var Schoolname = $(this).find('#school_name').val();
        //         var Year_graduated = $(this).find('#year_graduated').val();
        //         var Course = $(this).find('#course').val();
        //         var Award = $(this).find('#award').val();

        //         if (Schoolname && Year_graduated && Course && Award) {
        //             higher_educational_attainment.push({
        //                 Schoolname: Schoolname,
        //                 Year_graduated: Year_graduated,
        //                 Course: Course,
        //                 Award: Award
        //             });
        //         }
        //     });


        //     var work_experience = [];

        //     $('.work_experience_section .work_experience_row').each(function() {
        //         var Company = $(this).find('#Company').val();
        //         var Designation = $(this).find('#Designation').val();
        //         var Datefrom = $(this).find('#Datefrom').val();
        //         var Dateto = $(this).find('#Dateto').val();

        //         if (Company && Designation && Datefrom && Dateto) {
        //             work_experience.push({
        //                 Company: Company,
        //                 Designation: Designation,
        //                 Datefrom: Datefrom,
        //                 Dateto: Dateto
        //             });
        //         }
        //     });

        //     var bank_account = [];


        //     $('.addbank_account_section .addbank_account_row').each(function() {
        //         var append_bank_name = $(this).find('#append_bank_name').val();
        //         var append_bank_number = $(this).find('#append_bank_number').val();

        //         if (append_bank_name && append_bank_number) {
        //             bank_account.push({
        //                 append_bank_name: append_bank_name,
        //                 append_bank_number: append_bank_number
        //             });
        //         }
        //     });


        //     var employee_picture = $('#employee_picture').val();
        //     var sss = $('#sss').val();
        //     var pagibig = $('#pagibig').val();
        //     var philhealth = $('#philhealth').val();
        //     var tin = $('#tin').val();








        //     $.ajax({
        //         type: 'GET',
        //         url: '/newempoloyee/basicinformation/create',
        //         data: {
        //             firstName: firstName,
        //             middleName: middleName,
        //             lastName: lastName,
        //             suffix: suffix,
        //             sex: sex,
        //             civilStatus: civilStatus,
        //             birthdate: birthdate,
        //             cellphone: cellphone,
        //             email: email,
        //             address: address,
        //             rfid: rfid,

        //             contactFirstname: contactFirstname,
        //             contactMiddlename: contactMiddlename,
        //             contactLastname: contactLastname,
        //             contactSuffix: contactSuffix,
        //             Relationship: Relationship,
        //             Telephone: Telephone,
        //             Cellphone: Cellphone,
        //             Email: Email,

        //             work_experience: work_experience,

        //             higher_educational_attainment: higher_educational_attainment,

        //             SSS: sss,
        //             PagIbig: pagibig,
        //             Philhealth: philhealth,
        //             TIN: tin,
        //             employee_picture: employee_picture,

        //             bank_account: bank_account

        //         },

        //         success: function(data) {
        //             if (data[0].status == 2) {
        //                 Toast.fire({
        //                     type: 'warning',
        //                     title: data[0].message
        //                 })
        //             } else if (data[0].status == 1) {
        //                 Toast.fire({
        //                     type: 'success',
        //                     title: 'Successfully created'
        //                 })


        //             } else {
        //                 Toast.fire({
        //                     type: 'error',
        //                     title: data[0].message
        //                 })
        //             }
        //         }
        //     });
        // }

        $(document).on("click", "#addapproval", function(e) {
            e.preventDefault();
            var approval_section = $(".approval_section");
            var approval_row = $(".approval_row").first().clone();
            // approval_row.find("input").val("");
            approval_row.find(".remove_approval").prop('hidden', false);
            var current_count = approval_section.children(".approval_row").length + 1; // Count existing rows + 1
            var ordinal_suffix = getOrdinalSuffix(current_count); // Get the ordinal suffix (e.g., 1st, 2nd, 3rd)
            approval_row.find("label").text(`${current_count}${ordinal_suffix} Approval`);

            approval_section.append(approval_row);

        });

        // Function to remove an approval row
        $(document).on("click", ".remove_approval", function(e) {
            e.preventDefault();
            $(this).closest(".approval_row").remove();

            // Update labels after removing a row
            $(".approval_row").each(function(index) {
                var updated_count = index + 1; // Update index starting from 1
                var ordinal_suffix = getOrdinalSuffix(updated_count);
                $(this).find("label").text(`${updated_count}${ordinal_suffix} Approval`);
            });
        });

        // Function to determine the ordinal suffix (e.g., st, nd, rd, th)
        function getOrdinalSuffix(number) {
            const j = number % 10,
                k = number % 100;
            if (j == 1 && k != 11) return "st";
            if (j == 2 && k != 12) return "nd";
            if (j == 3 && k != 13) return "rd";
            return "th";
        }

        function select_departments() {
            $.ajax({
                type: "GET",
                url: "/payrollclerk/employees/profile/select_departments",
                success: function(data) {
                    console.log(data);
                    $('#select-department').empty()
                    $('#select-department').append('<option value="">Select Department</option>')
                    $('#select-department').select2({
                        data: data,
                        allowClear: true,
                        placeholder: 'Select Department'
                    });

                }
            });
        }


        select_departments()

        function select_designations() {
            $.ajax({
                type: "GET",
                url: "/payrollclerk/employees/profile/select_designations",
                success: function(data) {
                    $('#select-designation').empty()
                    $('#select-designation').append('<option value="">Select Designation</option>')
                    $('#select-designation').select2({
                        data: data,
                        allowClear: true,
                        placeholder: 'Select Designation',
                        dropdownCssClass: 'custom-dropdown'
                    });
                }
            });
        }

        select_designations()


        function select_offices() {
            $.ajax({
                type: "GET",
                url: "/newempoloyee/select_offices",
                success: function(data) {
                    $('#select-office').empty()
                    $('#select-office').append('<option value="">Select Office</option>')
                    $('#select-office').select2({
                        data: data,
                        allowClear: true,
                        placeholder: 'Select Office',
                        dropdownCssClass: 'custom-dropdown'
                    });
                }
            });
        }

        select_offices()

        // $(document).on("click", "#save_employeebtn_employmentdetails", function(e) {
        //     e.preventDefault();

        //     add_newemployee_employmentDetails()

        // });
    </script>
@endsection
