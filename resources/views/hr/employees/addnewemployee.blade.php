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

    .toggle-container {
        display: flex;
        align-items: center;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 30px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 5px;
        font-size: 12px;
        color: white;
        font-weight: bold;
        box-sizing: border-box;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 24px;
        width: 24px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #4caf50;
    }

    input:checked+.slider:before {
        transform: translateX(30px);
    }

    .label-on {
        position: absolute;
        left: 8px;
    }

    .label-off {
        position: absolute;
        right: 8px;
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
                        <li class="breadcrumb-item"><a href="/hr/employees/indexV4">Employees</a></li>
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

        date_default_timezone_set('Asia/Manila');

        $myid = DB::table('teacher')
            ->select('id')
            ->where('userid', auth()->user()->id)
            ->where('isactive', '1')
            ->where('deleted', '0')
            ->first();

        use App\Models\HR\HREmployeeAttendance;
        // use Illuminate\Support\Facades\DB;

        $employee = DB::table('teacher')->first();

        $currentmonthworkdays = [];
        $beginmonth = new DateTime(date('Y-m-01'));
        $endmonth = new DateTime(date('Y-m-t'));
        $endmonth->modify('+1 day');
        $intervalmonth = new DateInterval('P1D');
        $daterangemonth = new DatePeriod($beginmonth, $intervalmonth, $endmonth);

        foreach ($daterangemonth as $datemonth) {
            $currentmonthworkdays[] = $datemonth->format('Y-m-d');
        }

        $employeeattendance = [];
        foreach ($currentmonthworkdays as $currentmonthworkday) {
            $att = HREmployeeAttendance::getattendance($currentmonthworkday, $employee);

            $timerecord = (object) [
                'amin' => $att->amin === '00:00:00' ? '' : $att->amin,
                'amout' => $att->amout === '00:00:00' ? '' : $att->amout,
                'pmin' => $att->pmin === '00:00:00' ? '' : $att->pmin,
                'pmout' => $att->pmout === '00:00:00' ? '' : $att->pmout,
            ];

            $employeeattendance[] = (object) [
                'date' => date('M d Y', strtotime($currentmonthworkday)),
                'day' => date('l', strtotime($currentmonthworkday)),
                'timerecord' => $timerecord,
            ];
        }

        $currentmonthfirstday = date('m-01-Y');
        $currentmonthlastday = date('m-t-Y');
    @endphp

    <form action="/hr/employees/addnewemployee/save" method="get" class="m-0 p-0">
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
    </form>
    <br>






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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>


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

        $('#save_employeebtn__employeeInformation').on('click', function(event) {
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
                    if (data.status == 2) {
                        Toast.fire({
                            type: 'warning',
                            title: data.message
                        });
                    } else if (data.status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully created'
                        });
                        $('#selected_employee_id').val(data.employee_id);
                    } else {
                        Toast.fire({
                            type: 'warning',
                            title: 'Please complete all fields or verify your input'
                        });
                    }
                }
            });
        }


        $('#save_employeebtn__employmentdetails').on('click', function(event) {
            // event.preventDefault();
            add_newemployee_employmentDetails()

        });

        /*************  ✨ Codeium Command 🌟  *************/
        function add_newemployee_employmentDetails() {

            var selected_employeeid = $('#selected_employee_id').val();
            var designation = $('#select-designation').val();
            var department = $('#select-department').val();
            var office = $('#select-office').val();
            var date_hired = $('#date_hired').val();
            var yos = $('#accumulated_years_service').val();
            var job_status = $('#select-jobstatus').val();
            var prob_start_date = $('#probationary_start_date').val();
            var prob_end_date = $('#probationary_end_date').val();

            var scheduleData = {};

            var days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            days.forEach(day => {
                scheduleData[day] = {
                    dayType: $(`#${day}_select_general`).val(),
                    amin: $(`#${day}_amin_time_general`).val() || null,
                    amout: $(`#${day}_amout_time_general`).val() || null,
                    pmin: $(`#${day}_pmin_time_general`).val() || null,
                    pmout: $(`#${day}_pmout_time_general`).val() || null
                };
            });



            var formData = new FormData();


            formData.append('selected_employeeid', selected_employeeid);
            formData.append('designation', designation);
            formData.append('department', department);
            formData.append('office', office);
            // formData.append('office', office);
            formData.append('date_hired', date_hired);
            formData.append('yos', yos);
            formData.append('job_status', job_status);
            formData.append('prob_start_date', prob_start_date);
            formData.append('prob_end_date', prob_end_date);
            // formData.append('prob_start_date', prob_start_date);
            // formData.append('prob_end_date', prob_end_date);


            Object.keys(scheduleData).forEach((key, index) => {
                formData.append(`scheduleData[${index}][day]`, key);
                formData.append(`scheduleData[${index}][dayType]`, scheduleData[key].dayType);
                formData.append(`scheduleData[${index}][amin]`, scheduleData[key].amin);
                formData.append(`scheduleData[${index}][amout]`, scheduleData[key].amout);
                formData.append(`scheduleData[${index}][pmin]`, scheduleData[key].pmin);
                formData.append(`scheduleData[${index}][pmout]`, scheduleData[key].pmout);
            });

            $.ajax({
                type: 'POST',
                url: '/newempoloyee/employmentdetails/create',
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.status == 2) {
                        Toast.fire({
                            type: 'warning',
                            title: data.message
                        });
                    } else if (data.status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully created'
                        });

                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data.message
                        });
                    }
                }
            });
        }

        /******  3d9d03dd-eb3d-4965-90ed-c9e3dfd19a78  *******/
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


        $(document).on("click", ".mployment_details_nav", function(e) {
            e.preventDefault();

            $('.employee_information').hide()
            $('.employment_details').show()

            $('.mployment_details_nav').addClass('active')
            $('.employee_information_nav').removeClass('active')

        });

        $(document).on("click", ".employee_information_nav", function(e) {
            e.preventDefault();

            $('.employment_details').hide()
            $('.employee_information').show()

            $('.employee_information_nav').addClass('active')
            $('.mployment_details_nav').removeClass('active')

        });


        // function select_generalworkdays() {
        //     $.ajax({
        //         type: "GET",
        //         url: "/newempoloyee/select_generalworkdays",
        //         success: function(data) {
        //             $('#select-generalworkdays').empty()
        //             $('#select-generalworkdays').append('<option value="">Select General Workdays</option>')
        //             $('#select-generalworkdays').select2({
        //                 data: data.map(function(item) {
        //                     return {
        //                         id: item.id,
        //                         text: item.text
        //                     };
        //                 }),
        //                 allowClear: true,
        //                 placeholder: 'Select General Workdays',
        //                 dropdownCssClass: 'custom-dropdown'
        //             });
        //         }
        //     });
        // }

        // select_generalworkdays()

        // Function to populate the general workdays dropdown
        function select_generalworkdays() {
            $.ajax({
                type: "GET",
                url: "/newempoloyee/select_generalworkdays",
                success: function(data) {
                    // Clear and initialize the dropdown
                    $('#select-generalworkdays').empty();
                    $('#select-generalworkdays').append('<option value="">Select General Workdays</option>');
                    data.forEach(function(item) {
                        $('#select-generalworkdays').append(
                            `<option value="${item.id}">${item.text}</option>`);
                    });
                },
                error: function() {
                    Toast.fire({
                        icon: 'error',
                        title: 'Failed to load workdays!'
                    });
                }
            });
        }

        // Call the function to populate workdays on page load
        select_generalworkdays();

        // Event listener for when the dropdown value changes
        $('#select-generalworkdays').on('change', function() {
            const dataId = $(this).val();
            console.log(dataId);

            if (!dataId) {
                // Clear the modal or table if no workday is selected
                clearWorkdayDetails();
                return;
            }

            // Fetch and populate workday details
            $.ajax({
                url: '/hr/setup/edit_workday',
                method: 'GET',
                data: {
                    id: dataId
                },
                success: function(data) {
                    if (data) {
                        // Populate the workday details
                        populateWorkdayDetails(data);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'No data found!'
                        });
                    }
                },
                error: function() {
                    Toast.fire({
                        icon: 'error',
                        title: 'Failed to load data!'
                    });
                }
            });
        });

        // Function to populate workday details
        function populateWorkdayDetails(data) {
            // Clear and repopulate the table
            const scheduleTable = document.getElementById('generalworkdaysTable');
            scheduleTable.innerHTML = ''; // Clear existing rows

            // Days of the week
            const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            // Generate rows dynamically
            days.forEach(day => {
                scheduleTable.innerHTML += `
            <tr>
                <td>${day.charAt(0).toUpperCase()}${day.charAt(1)}${day.charAt(2)}</td>
                <td>
                    <option id="${day.toLowerCase()}_select_general" name="${day.toLowerCase()}_select_general" value="${data[day]}">${data[day] == 1 ? 'Full Day' : data[day] == 2 ? 'Half AM' : data[day] == 3 ? 'Half PM' : 'Day Off'}</option>
                </td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day}_amin_time_general" name="${day}_amin_time_general" value="${data[`${day}_amin`]}"></td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day}_amout_time_general" name="${day}_amout_time_general" value="${data[`${day}_amout`]}"></td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day}_pmin_time_general" name="${day}_pmin_time_general" value="${data[`${day}_pmin`]}"></td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day}_pmout_time_general" name="${day}_pmout_time_general" value="${data[`${day}_pmout`]}"></td>
            </tr>
        `;
                // <input type="text" class="form-control days form-control-sm" id="${day}_select" name="${day}_select" value="${data[day]}">
            });
        }

        // Function to clear workday details
        function clearWorkdayDetails() {
            $('#workday_description').val('');
            const scheduleTable = document.getElementById('scheduleTable');
            scheduleTable.innerHTML = ''; // Clear existing rows
        }


        $(document).on('click', '#btn_addworkday', function() {
            workday_select()
            $('#modal_desc').text('Cuztomized Workday')
            $('.modal_addworkday').modal('show')
        })

        $('.modal_addworkday').on('hidden.bs.modal', function() {
            $('#workday_description').val('')
            $('.modal_addworkday').find('input').val('')
            $('#scheduleTable').empty()
        });

        // function workday_select() {
        //     // Days of the week
        //     const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']

        //     // Reference to table body
        //     const scheduleTable = document.getElementById('scheduleTable')

        //     // Clear table content
        //     scheduleTable.innerHTML = ``

        //     // Generate rows dynamically
        //     days.forEach(day => {
        //         scheduleTable.innerHTML += `
    //             <tr>
    //                 <td>${day.charAt(0)}${day.charAt(1)}${day.charAt(2)}${day.charAt(3)}${day.charAt(4)}${day.charAt(5)}${day.charAt(6)}${day.charAt(7)}${day.charAt(8)}</td>
    //                 <td>
    //                     <select class="form-control days form-control-sm" id="${day.toLowerCase()}_select" name="${day.toLowerCase()}_select">
    //                         <option value="1" selected>Full Day</option>
    //                         <option value="2">Half AM</option>
    //                         <option value="3">Half PM</option>
    //                         <option value="0">Day Off</option>
    //                     </select>
    //                 </td>
    //                 <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_amin_time" name="${day.toLowerCase()}_amin_time" value="08:00"></td>
    //                 <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_amout_time" name="${day.toLowerCase()}_amout_time" value="12:00"></td>
    //                 <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_pmin_time" name="${day.toLowerCase()}_pmin_time" value="13:00"></td>
    //                 <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_pmout_time" name="${day.toLowerCase()}_pmout_time" value="17:00"></td>
    //                 <td><input type="text" class="form-control bg-white form-control-sm" id="${day.toLowerCase()}_hours" name="${day.toLowerCase()}_hours"></td>
    //                 <td><input type="text" class="form-control bg-white form-control-sm" id="${day.toLowerCase()}_minutes" name="${day.toLowerCase()}_minutes"></td>
    //                 </tr>
    //         `;
        //     });
        // }

        function workday_select() {
            // Days of the week
            const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

            // Reference to table body
            const scheduleTable = document.getElementById('scheduleTable');

            // Clear table content
            scheduleTable.innerHTML = ``;

            // Generate rows dynamically
            days.forEach(day => {
                scheduleTable.innerHTML += `
            <tr>
                <td>${day}</td>
                <td>
                    <select class="form-control days form-control-sm" id="${day.toLowerCase()}_select" name="${day.toLowerCase()}_select">
                        <option value="1" selected>Full Day</option>
                        <option value="2">Half AM</option>
                        <option value="3">Half PM</option>
                        <option value="0">Day Off</option>
                    </select>
                </td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_amin_time" name="${day.toLowerCase()}_amin_time" value="08:00"></td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_amout_time" name="${day.toLowerCase()}_amout_time" value="12:00"></td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_pmin_time" name="${day.toLowerCase()}_pmin_time" value="13:00"></td>
                <td><input type="time" class="js-flatpickr form-control bg-white form-control-sm" id="${day.toLowerCase()}_pmout_time" name="${day.toLowerCase()}_pmout_time" value="17:00"></td>
            </tr>
        `;
            });
        }

        //working get total hours

        // function calculateTotalHours() {
        //     const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        //     let totalHours = 0;
        //     let totalMinutes = 0;

        //     days.forEach(day => {
        //         const amin_time = $(`#${day.toLowerCase()}_amin_time`).val();
        //         const amout_time = $(`#${day.toLowerCase()}_amout_time`).val();
        //         const pmin_time = $(`#${day.toLowerCase()}_pmin_time`).val();
        //         const pmout_time = $(`#${day.toLowerCase()}_pmout_time`).val();

        //         if (amin_time && amout_time && pmin_time && pmout_time) {
        //             const [amin_hour, amin_minute] = amin_time.split(':').map(Number);
        //             const [amout_hour, amout_minute] = amout_time.split(':').map(Number);
        //             const [pmin_hour, pmin_minute] = pmin_time.split(':').map(Number);
        //             const [pmout_hour, pmout_minute] = pmout_time.split(':').map(Number);

        //             totalHours += (amout_hour - amin_hour) + (pmout_hour - pmin_hour);
        //             totalMinutes += (amout_minute - amin_minute) + (pmout_minute - pmin_minute);
        //         }
        //     });

        //     // Convert total minutes to hours and minutes
        //     totalHours += Math.floor(totalMinutes / 60);
        //     totalMinutes %= 60;

        //     // Update the total hours and minutes in the respective input fields
        //     $('#fullday_overtime').val(`${totalHours} hours and ${totalMinutes} minutes`);
        // }


        function calculateTotalMinutes() {
            // const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            // let totalMinutes = 0;

            // days.forEach(day => {
            //     const amin_time = $(`#${day.toLowerCase()}_amin_time`).val();
            //     const amout_time = $(`#${day.toLowerCase()}_amout_time`).val();
            //     const pmin_time = $(`#${day.toLowerCase()}_pmin_time`).val();
            //     const pmout_time = $(`#${day.toLowerCase()}_pmout_time`).val();

            //     if (amin_time && amout_time && pmin_time && pmout_time) {
            //         const [amin_hour, amin_minute] = amin_time.split(':').map(Number);
            //         const [amout_hour, amout_minute] = amout_time.split(':').map(Number);
            //         const [pmin_hour, pmin_minute] = pmin_time.split(':').map(Number);
            //         const [pmout_hour, pmout_minute] = pmout_time.split(':').map(Number);

            //         totalMinutes += (amout_hour * 60 + amout_minute) - (amin_hour * 60 + amin_minute) + (
            //             pmout_hour * 60 + pmout_minute) - (pmin_hour * 60 + pmin_minute);
            //     }
            // });

            // var Fullday_totalMinutes = totalMinutes;

            var Fullday_totalMinutes = 480;

            // Update the total minutes in the respective input fields
            $('#fullday_overtime').val(`${Fullday_totalMinutes}`);
            $('#fullday_undertime').val(`${Fullday_totalMinutes}`);

            var Halfday_totalMinutes = Fullday_totalMinutes / 2;

            $('#halfday_overtime').val(`${Halfday_totalMinutes}`);
            $('#halfday_undertime').val(`${Halfday_totalMinutes}`);


        }

        calculateTotalMinutes();


        // Apply the change event listener
        $(document).on('change', '.js-flatpickr', calculateTotalMinutes);

        $(document).on('input', '.js-flatpickr', function() {
            if ($(this).val()) {
                calculateTotalMinutes();

            }
        });

        // Initialize workday schedule
        $(document).ready(function() {
            workday_select();
            calculateTotalMinutes();
        });



        $('#tarday_status').change(function() {
            if ($(this).is(':checked')) {
                $('.tardiness_main_section').show();
            } else {
                $('.tardiness_main_section').hide();
            }
        });

        // Initialize the visibility based on the checkbox status on page load
        if ($('#tarday_status').is(':checked')) {
            $('.tardiness_main_section').show();
        } else {
            $('.tardiness_main_section').hide();
        }



        $(document).on("click", "#add_tardiness", function(e) {
            e.preventDefault();
            var tardiness_section = $(".tardiness_section");
            var tardiness_row = $(".tardiness_row").first().clone();
            tardiness_row.find("input").val("");
            tardiness_row.find(".remove_tardiness").prop('hidden', false);
            tardiness_section.append(tardiness_row);
        });

        $(document).on("click", ".remove_tardiness", function(e) {
            e.preventDefault();
            $(this).closest('.tardiness_row').remove();
        });


        var currentDate = moment().format('YYYY-MM-DD');
        $('#dtrdaterange').daterangepicker({
            startDate: currentDate,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        $('#dtrdaterange').on('apply.daterangepicker', function(ev, picker) {
            var startDate = picker.startDate.format('YYYY-MM-DD');
            var endDate = picker.endDate.format('YYYY-MM-DD');
            filterTimerecords(startDate, endDate);
        });

        function filterTimerecords(startDate, endDate) {
            $('#timerecord').empty(); // Clear existing table data

            var workdays = [];
            var currentDate = new Date(startDate);
            var lastDate = new Date(endDate);

            while (currentDate <= lastDate) {
                workdays.push({
                    date: formatDate(currentDate),
                    day: getDayName(currentDate)
                });
                currentDate.setDate(currentDate.getDate() + 1);
            }

            var employeeattendance = [];
            workdays.forEach(function(workday) {
                employeeattendance.push({
                    date: workday.date,
                    day: workday.day,
                    am_in: '',
                    am_out: '',
                    pm_in: '',
                    pm_out: ''
                });
            });

            // Render the filtered records in the table
            employeeattendance.forEach(function(record) {
                var row = '<tr>';
                row += '<td>';
                row += record.date + '<br>';
                if (record.day === 'Saturday' || record.day === 'Sunday') {
                    row += '<span class="right badge badge-secondary">' + record.day + '</span>';
                } else {
                    row += '<span class="right badge badge-default">' + record.day + '</span>';
                }
                row += '</td>';
                row += '<td class="text-center">' + record.am_in + '</td>';
                row += '<td class="text-center">' + record.am_out + '</td>';
                row += '<td class="text-center">' + record.pm_in + '</td>';
                row += '<td class="text-center">' + record.pm_out + '</td>';
                row += '<td class="text-center">' + record.pm_in + '</td>';
                row += '<td class="text-center">' + record.pm_out + '</td>';
                row += '</tr>';
                $('#timerecord').append(row);
            });
        }

        function formatDate(date) {
            var options = {
                year: 'numeric',
                month: 'short',
                day: '2-digit'
            }; // Short month format (e.g., Jan)
            return new Date(date).toLocaleDateString('en-US', options).replace(',', '');
        }

        function getDayName(date) {
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            return days[new Date(date).getDay()];
        }

        $(document).on('click', '#btn_saveworkday', function() {
            const scheduleData = {};
            const description = $('#workday_description').val();
            const selected_employeeid = $('#selected_employee_id').val();

            // Gather schedule data
            const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            days.forEach(day => {
                scheduleData[day.toLowerCase()] = {
                    dayType: $(`#${day.toLowerCase()}_select`).val(),
                    amin: $(`#${day.toLowerCase()}_amin_time`).val() || null,
                    amout: $(`#${day.toLowerCase()}_amout_time`).val() || null,
                    pmin: $(`#${day.toLowerCase()}_pmin_time`).val() || null,
                    pmout: $(`#${day.toLowerCase()}_pmout_time`).val() || null
                };
            });

            $.ajax({
                url: '/hr/setup/foremployee_store_workday',
                method: 'GET',
                data: {
                    schedule: scheduleData,
                    description: description,
                    employeeid: selected_employeeid
                },
                success: function(data) {
                    Toast.fire({
                        type: 'success',
                        title: 'Workday Saved Successfully!'
                    });
                },
                error: function(error) {
                    Toast.fire({
                        type: 'error',
                        title: 'Failed to save workday!'
                    });
                }
            });
        });





        // function calculateFullday_overtime() {
        //     const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        //     const scheduleTable = document.getElementById('scheduleTable');

        //     days.forEach(day => {
        //         var amin_time = $(`#${day.toLowerCase()}_amin_time`).val();
        //         var amout_time = $(`#${day.toLowerCase()}_amout_time`).val();
        //         var pmin_time = $(`#${day.toLowerCase()}_pmin_time`).val();
        //         var pmout_time = $(`#${day.toLowerCase()}_pmout_time`).val();

        //         var total = amin_time + amout_time + pmin_time + pmout_time;

        //         var hours = Math.floor(total / 60);
        //         var minutes = total % 60;

        //         $(`#${day.toLowerCase()}_hours`).val(hours);
        //         $(`#${day.toLowerCase()}_minutes`).val(minutes);

        //     });
        // }

        // function calculateTotalHours() {
        //     const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        //     var totalHours = 0;
        //     var totalMinutes = 0;

        //     days.forEach(day => {
        //         var amin_time = $(`#${day.toLowerCase()}_amin_time`).val();
        //         var amout_time = $(`#${day.toLowerCase()}_amout_time`).val();
        //         var pmin_time = $(`#${day.toLowerCase()}_pmin_time`).val();
        //         var pmout_time = $(`#${day.toLowerCase()}_pmout_time`).val();

        //         var amin_hour = parseInt(amin_time.split(':')[0]);
        //         var amin_minute = parseInt(amin_time.split(':')[1]);
        //         var amout_hour = parseInt(amout_time.split(':')[0]);
        //         var amout_minute = parseInt(amout_time.split(':')[1]);
        //         var pmin_hour = parseInt(pmin_time.split(':')[0]);
        //         var pmin_minute = parseInt(pmin_time.split(':')[1]);
        //         var pmout_hour = parseInt(pmout_time.split(':')[0]);
        //         var pmout_minute = parseInt(pmout_time.split(':')[1]);

        //         totalHours += (amout_hour - amin_hour) + (pmout_hour - pmin_hour);
        //         totalMinutes += (amout_minute - amin_minute) + (pmout_minute - pmin_minute);
        //     });

        //     // $('#fullday_overtime').val(totalHours);
        //     $('#fullday_overtime').val(totalMinutes);
        // }


        // function calculateTotalHours() {
        //     const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        //     var totalHours = 0;
        //     var totalMinutes = 0;

        //     days.forEach(day => {
        //         var amin_time = $(`#${day.toLowerCase()}_amin_time`).val();
        //         var amout_time = $(`#${day.toLowerCase()}_amout_time`).val();
        //         var pmin_time = $(`#${day.toLowerCase()}_pmin_time`).val();
        //         var pmout_time = $(`#${day.toLowerCase()}_pmout_time`).val();

        //         var amin_hour = parseInt(amin_time.split(':')[0]);
        //         var amin_minute = parseInt(amin_time.split(':')[1]);
        //         var amout_hour = parseInt(amout_time.split(':')[0]);
        //         var amout_minute = parseInt(amout_time.split(':')[1]);
        //         var pmin_hour = parseInt(pmin_time.split(':')[0]);
        //         var pmin_minute = parseInt(pmin_time.split(':')[1]);
        //         var pmout_hour = parseInt(pmout_time.split(':')[0]);
        //         var pmout_minute = parseInt(pmout_time.split(':')[1]);

        //         totalHours += (amout_hour - amin_hour) + (pmout_hour - pmin_hour);
        //         totalMinutes += (amout_minute - amin_minute) + (pmout_minute - pmin_minute);
        //     });

        //     // $('#fullday_overtime').val(totalHours);
        //     $('#fullday_overtime').val(totalMinutes);
        // }






        // function add_newemployee() {

        // var rfid = $('#rfid').val();
        // var firstName = $('#firstName').val();
        // var middleName = $('#middleName').val();
        // var lastName = $('#lastName').val();
        // var suffix = $('#suffix').val();
        // var sex = $('#sex').val();
        // var civilStatus = $('#civilStatus').val();
        // var birthdate = $('#birthdate').val();
        // var cellphone = $('#cellphone').val();
        // var email = $('#email').val();
        // var address = $('#address').val();


        // var contactFirstname = $('#contactFirstname').val();
        // var contactMiddlename = $('#contactMiddlename').val();
        // var contactLastname = $('#contactLastname').val();
        // var contactSuffix = $('#contactSuffix').val();
        // var Relationship = $('#Relationship').val();
        // var Telephone = $('#Telephone').val();
        // var Cellphone = $('#Cellphone').val();
        // var Email = $('#Email').val();


        // var higher_educational_attainment = [];

        // $('.highest_education_section .highest_education_row').each(function() {
        // var Schoolname = $(this).find('#school_name').val();
        // var Year_graduated = $(this).find('#year_graduated').val();
        // var Course = $(this).find('#course').val();
        // var Award = $(this).find('#award').val();

        // if (Schoolname && Year_graduated && Course && Award) {
        // higher_educational_attainment.push({
        // Schoolname: Schoolname,
        // Year_graduated: Year_graduated,
        // Course: Course,
        // Award: Award
        // });
        // }
        // });


        // var work_experience = [];

        // $('.work_experience_section .work_experience_row').each(function() {
        // var Company = $(this).find('#Company').val();
        // var Designation = $(this).find('#Designation').val();
        // var Datefrom = $(this).find('#Datefrom').val();
        // var Dateto = $(this).find('#Dateto').val();

        // if (Company && Designation && Datefrom && Dateto) {
        // work_experience.push({
        // Company: Company,
        // Designation: Designation,
        // Datefrom: Datefrom,
        // Dateto: Dateto
        // });
        // }
        // });

        // var bank_account = [];


        // $('.addbank_account_section .addbank_account_row').each(function() {
        // var append_bank_name = $(this).find('#append_bank_name').val();
        // var append_bank_number = $(this).find('#append_bank_number').val();

        // if (append_bank_name && append_bank_number) {
        // bank_account.push({
        // append_bank_name: append_bank_name,
        // append_bank_number: append_bank_number
        // });
        // }
        // });

        // var sss = $('#sss').val();
        // var pagibig = $('#pagibig').val();
        // var philhealth = $('#philhealth').val();
        // var tin = $('#tin').val();

        // var formData = new FormData();
        // formData.append('employee_picture', $('#employee_picture')[0].files[0]);

        // formData.append('firstName', firstName);
        // formData.append('middleName', middleName);
        // formData.append('lastName', lastName);
        // formData.append('suffix', suffix);
        // formData.append('sex', sex);
        // formData.append('civilStatus', civilStatus);
        // formData.append('birthdate', birthdate);
        // formData.append('cellphone', cellphone);
        // formData.append('email', email);
        // formData.append('address', address);
        // formData.append('rfid', rfid);

        // formData.append('contactFirstname', contactFirstname);
        // formData.append('contactMiddlename', contactMiddlename);
        // formData.append('contactLastname', contactLastname);
        // formData.append('contactSuffix', contactSuffix);
        // formData.append('Relationship', Relationship);
        // formData.append('Telephone', Telephone);
        // formData.append('Cellphone', Cellphone);
        // formData.append('Email', Email);

        // formData.append('work_experience', JSON.stringify(work_experience));
        // formData.append('higher_educational_attainment', JSON.stringify(higher_educational_attainment));

        // formData.append('SSS', sss);
        // formData.append('PagIbig', pagibig);
        // formData.append('Philhealth', philhealth);
        // formData.append('TIN', tin);

        // formData.append('bank_account', JSON.stringify(bank_account));

        // $.ajax({
        // type: 'POST',
        // url: '/newempoloyee/basicinformation/create',
        // data: formData,
        // contentType: false,
        // processData: false,
        // headers: {
        // 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        // },
        // success: function(data) {
        // if (data[0].status == 2) {
        // Toast.fire({
        // type: 'warning',
        // title: data[0].message
        // });
        // } else if (data[0].status == 1) {
        // Toast.fire({
        // type: 'success',
        // title: 'Successfully created'
        // });
        // } else {
        // Toast.fire({
        // type: 'error',
        // title: data[0].message
        // });
        // }
        // }
        // });
        // }

        // function add_newemployee() {



        // var rfid = $('#rfid').val();
        // var firstName = $('#firstName').val();
        // var middleName = $('#middleName').val();
        // var lastName = $('#lastName').val();
        // var suffix = $('#suffix').val();
        // var sex = $('#sex').val();
        // var civilStatus = $('#civilStatus').val();
        // var birthdate = $('#birthdate').val();
        // var cellphone = $('#cellphone').val();
        // var email = $('#email').val();
        // var address = $('#address').val();


        // var contactFirstname = $('#contactFirstname').val();
        // var contactMiddlename = $('#contactMiddlename').val();
        // var contactLastname = $('#contactLastname').val();
        // var contactSuffix = $('#contactSuffix').val();
        // var Relationship = $('#Relationship').val();
        // var Telephone = $('#Telephone').val();
        // var Cellphone = $('#Cellphone').val();
        // var Email = $('#Email').val();


        // // var educational_attainment = [];
        // // $("#gradingPointsTable").find("tbody tr").each(function() {

        // // var Company = $('#Company').val();
        // // var year_graduated = $('#year_graduated').val();
        // // var course = $('#course').val();
        // // var award = $('#award').val();

        // // if (Company && year_graduated && course &&
        // // award) {
        // // educational_attainment.push({
        // // Company: Company,
        // // year_graduated: year_graduated,
        // // course: course,
        // // award: award
        // // });
        // // }

        // // });

        // var higher_educational_attainment = [];

        // $('.highest_education_section .highest_education_row').each(function() {
        // var Schoolname = $(this).find('#school_name').val();
        // var Year_graduated = $(this).find('#year_graduated').val();
        // var Course = $(this).find('#course').val();
        // var Award = $(this).find('#award').val();

        // if (Schoolname && Year_graduated && Course && Award) {
        // higher_educational_attainment.push({
        // Schoolname: Schoolname,
        // Year_graduated: Year_graduated,
        // Course: Course,
        // Award: Award
        // });
        // }
        // });


        // var work_experience = [];

        // $('.work_experience_section .work_experience_row').each(function() {
        // var Company = $(this).find('#Company').val();
        // var Designation = $(this).find('#Designation').val();
        // var Datefrom = $(this).find('#Datefrom').val();
        // var Dateto = $(this).find('#Dateto').val();

        // if (Company && Designation && Datefrom && Dateto) {
        // work_experience.push({
        // Company: Company,
        // Designation: Designation,
        // Datefrom: Datefrom,
        // Dateto: Dateto
        // });
        // }
        // });

        // var bank_account = [];


        // $('.addbank_account_section .addbank_account_row').each(function() {
        // var append_bank_name = $(this).find('#append_bank_name').val();
        // var append_bank_number = $(this).find('#append_bank_number').val();

        // if (append_bank_name && append_bank_number) {
        // bank_account.push({
        // append_bank_name: append_bank_name,
        // append_bank_number: append_bank_number
        // });
        // }
        // });


        // var employee_picture = $('#employee_picture').val();
        // var sss = $('#sss').val();
        // var pagibig = $('#pagibig').val();
        // var philhealth = $('#philhealth').val();
        // var tin = $('#tin').val();








        // $.ajax({
        // type: 'GET',
        // url: '/newempoloyee/basicinformation/create',
        // data: {
        // firstName: firstName,
        // middleName: middleName,
        // lastName: lastName,
        // suffix: suffix,
        // sex: sex,
        // civilStatus: civilStatus,
        // birthdate: birthdate,
        // cellphone: cellphone,
        // email: email,
        // address: address,
        // rfid: rfid,

        // contactFirstname: contactFirstname,
        // contactMiddlename: contactMiddlename,
        // contactLastname: contactLastname,
        // contactSuffix: contactSuffix,
        // Relationship: Relationship,
        // Telephone: Telephone,
        // Cellphone: Cellphone,
        // Email: Email,

        // work_experience: work_experience,

        // higher_educational_attainment: higher_educational_attainment,

        // SSS: sss,
        // PagIbig: pagibig,
        // Philhealth: philhealth,
        // TIN: tin,
        // employee_picture: employee_picture,

        // bank_account: bank_account

        // },

        // success: function(data) {
        // if (data[0].status == 2) {
        // Toast.fire({
        // type: 'warning',
        // title: data[0].message
        // })
        // } else if (data[0].status == 1) {
        // Toast.fire({
        // type: 'success',
        // title: 'Successfully created'
        // })


        // } else {
        // Toast.fire({
        // type: 'error',
        // title: data[0].message
        // })
        // }
        // }
        // });
        // }
    </script>
@endsection
