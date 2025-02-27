@extends('registrar.layouts.app')

@section('headerjavascript')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
    <style>
        .select2-container .select2-selection--single {
            height: 40px !important;
        }

        #modal-adddata .modal-dialog {
            max-width: 800px
        }

        .alert {
            position: relative;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
        }

        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }
    </style>
    <!-- DataTables -->

    <section class="content-header">
        @php
              $sy = DB::table('sy')->orderBy('sydesc', 'desc')->get();
              $semester = DB::table('semester')->orderBy('id', 'asc')->get();
        @endphp
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">OFFICIAL TRANSCRIPT OF RECORDS</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">OFFICIAL TRANSCRIPT OF RECORDS</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ccsa')
        <div class="card shadow" style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3">
                        <div class="each-signatory">
                            <label>School Treasurer</label>
                            <input type="text" class="form-control form-control-sm" id="input-schooltreasure"
                                value="{{ collect($signatories)->where('title', 'School Treasurer')->first()->name ?? '' }}"
                                placeholder="School Treasurer" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="each-signatory">
                            <label>OIC - Registrar</label>
                            <input type="text" class="form-control form-control-sm" id="input-oicregistrar"
                                value="{{ collect($signatories)->where('title', 'OIC - Registrar')->first()->name ?? '' }}"
                                placeholder="OIC - Registrar" />
                        </div>
                    </div>
                    <div class="col-md-6 text-right align-self-end">
                        <button type="button" class="btn btn-sm btn-primary" id="btn-updatesignatories"><i
                                class="fa fa-share"></i>&nbsp;&nbsp;Update Signatories</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="card shadow" style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;"
        id="card-students">
        <div class="card-body">
            <table id="example2" class="table table-hover" style="font-size: 12.5px;">
                <thead>
                    <tr>
                        {{-- <th>#</th> --}}
                        <th>ID Number</th>
                        <th>Student</th>
                        <th>Current Grade Level</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row"  id="card-record-result">
        <div class="card shadow col-md-3 mr-4" style="max-height: 500px">
            <div class="card-header" id="picture_append">
                <img id="studentPhoto" src="{{ asset('avatar/S(F) 1.png') }}" alt="Student Photo"
                                class="card-img-top"
                                style="height: 240px; object-fit: cover; object-position: center; border-radius: 10%; padding:10px">
            </div>
            <div class="card-body">
                <table class="table table-sm" style="font-size:12px">
                    <tr>
                        <th>ID Number</th>
                        <td id="studentIdNumber"></td>
                    </tr>
                    <tr>
                        <th>Level</th>
                        <td id="studentLevel"></td>
                    </tr>
                    <tr>
                        <th>Course</th>
                        <td id="studentCourse"></td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button id="updateCourse" class="btn btn-primary btn-sm">Update Profile Picture</button>
                </div>
            </div>
        </div>
        <div class="card shadow col-md-8" style="border: none !important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-4">
                        <button class="btn btn-default" id="btn-back"><i class="fa fa-arrow-left"></i> Back</button>
                        <button class="btn btn-default" id="btn-reload"><i class="fa fa-sync"></i> Reload Results</button>
                    </div>
                    <div class="col-md-8 text-right">
                        <button type="button" class="btn btn-success btn-sm ml-1" id="credSubjects"><i class="fa fa-file-pdf"></i> View Credited Subject</button>
                        <button type="button" class="btn btn-primary btn-sm ml-1" id="btn-exporttopdf">Print</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="div-result">

                </div>
            </div>
        </div>
    </div>
    

    <div class="modal fade" id="modal-adddata" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" id="closeremarks" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 p-0">
                            <table class="table">
                                <thead class="text-center">
                                    <tr>
                                        <th style="width: 15%;">Subject Code</th>
                                        <th style="width: 10%;">Units</th>
                                        <th>Description</th>
                                        <th style="width: 11%;">Grade</th>
                                        <th style="width: 11%;">Credits</th>
                                        <th style="width: 11%;"></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-adddata">
                                    @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc' ||
                                            strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ccsa')
                                        <tr class="tr-adddata">
                                            <td class="p-0"><input type="text" class="form-control input-subjcode"
                                                    placeholder="Code" /></td>
                                            <td class="p-0"><input type="number" class="form-control input-subjunit"
                                                    placeholder="Units" /></td>
                                            <td class="p-0"><input type="text" class="form-control input-subjdesc"
                                                    placeholder="Description" /></td>
                                            <td class="p-0"><input type="number" class="form-control input-subjgrade"
                                                    placeholder="Grade" /></td>
                                            <td class="p-0"><input type="number" class="form-control input-subjreex"
                                                    placeholder="Re-Ex" /></td>
                                            <td class="p-0"><input type="number"
                                                    class="form-control input-subjcredit" placeholder="Credit" /></td>
                                            <td class="p-0"><button type="button"
                                                    class="btn btn-default btn-adddata-save"><i
                                                        class="fa fa-check text-success"></i></button></td>
                                        </tr>
                                    @else
                                        <tr class="tr-adddata">
                                            <td class="p-0"><input type="text" class="form-control input-subjcode"
                                                    placeholder="Code" /></td>
                                            <td class="p-0"><input type="number" class="form-control input-subjunit"
                                                    placeholder="Units" /></td>
                                            <td class="p-0"><input type="text" class="form-control input-subjdesc"
                                                    placeholder="Description" /></td>
                                            <td class="p-0"><input type="number" class="form-control input-subjgrade"
                                                    placeholder="Grade" /></td>
                                            <td class="p-0"><input type="number"
                                                    class="form-control input-subjcredit" placeholder="Credit" /></td>
                                            <td class="p-0"><button type="button"
                                                    class="btn btn-default btn-adddata-save"><i
                                                        class="fa fa-check text-success"></i></button></td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"><button type="button" class="btn btn-info btn-block btn-sm"
                                                id="btn-adddata-row"><i class="fa fa-plus"></i> Add more</button></td>
                                        <td colspan="4"></td>
                                        {{-- <td colspan="2"><button type="button" class="btn btn-primary btn-block btn-sm" id="btn-adddata-submit"><i class="fa fa-share"></i> Submit</button></td> --}}
                                    </tr>
                                    {{-- <tr>
                                    <td colspan="4"></td>
                                    <td colspan="2"><button type="button" class="btn btn-info btn-block btn-sm" id="btn-adddata-submit"><i class="fa fa-share"></i> Submit</button></td>
                                </tr> --}}
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="creditedSubjects" aria-labelledby="creditedSubjects" aria-hidden="true"
    data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content" style="height: 680px!important;">
            <div class="modal-header">
                <h5 class="modal-title" id="">Credited Subjects</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" style="overflow-y: scroll">
                <hr>
                <div id="credited_table">
                    <div class="no_credit text-center">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
@endsection
@section('footerjavascript')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    {{-- <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script> --}}
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script>
        $(function() {

            $('#example2').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });

        $(document).ready(function() {
            var studid;
            var studname;
            $('#card-record-result').hide()

           


            var onerror_url = @json(asset('dist/img/download.png'));

            function getStudents() {

                $('#example2').DataTable({
                    // "paging": false,
                    // "lengthChange": false,
                    "searching": true,
                    "ordering": false,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "destroy": true,
                    serverSide: true,
                    processing: true,
                    // ajax:'/student/preregistration/list',
                    ajax: {
                        url: '/schoolform/tor/index',
                        type: 'GET',
                        data: {
                            action: 'getstudents'
                        }
                    },
                    columns: [
                        // { "data": null },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        },
                        {
                            "data": null
                        }
                    ],
                    columnDefs: [
                        // {
                        //     'targets': 0,
                        //     'orderable': false, 
                        //     'createdCell':  function (td, cellData, rowData, row, col) {
                        //         $(td)[0].innerHTML = rowData.no;
                        //             // $(td).addClass('align-middle')
                        //     }
                        // },
                        {
                            'targets': 0,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = rowData.sid
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = rowData.lastname + ', ' + rowData.firstname;
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                if (rowData.levelid >= 22) {
                                    $(td)[0].innerHTML = rowData.courseabrv + ' - ' + rowData
                                        .levelname
                                } else {
                                    $(td)[0].innerHTML = rowData.levelname
                                }
                                $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false,
                            'createdCell': function(td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML =
                                    '<button type="button" class="btn btn-sm btn-info btn-block btn-view-record" data-id="' +
                                    rowData.id + '" data-name="' + rowData.last_fullname +
                                    '" data-levelid="' + rowData.levelid + '">View Record</button>'
                                $(td).addClass('align-middle')
                            }
                        }
                    ]
                });
            }
            getStudents();
            $(document).on('click', '.btn-view-record', function() {
                $('#div-result').empty()

                studid = $(this).attr('data-id')
                studname = $(this).attr('data-name')
                Swal.fire({
                    title: 'Fetching...',
                    allowOutsideClick: false,
                    closeOnClickOutside: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    }
                })
                $.ajax({
                    url: '{{ route('torgetrecords') }}',
                    type: 'GET',
                    dataType: '',
                    data: {
                        studid: $(this).attr('data-id')
                    },
                    success: function(data) {
                        // console.log(data);

                        $('#div-result').empty()
                        $('#h3-studentname').text(studname)
                        $('#div-result').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        $('.select2').select2()
                        $('#small-selectsy').hide();
                        $('#small-inputsy').hide();
                        $('#small-selectcourse').hide();
                        $('#small-inputcoursename').hide();
                        $('.auto-disabled').find('input').attr('disabled')
                        $('#card-students').hide()
                        $('#card-record-result').show()
                        // $('.auto-disabled').find('button').hide()
                    }
                });
            })
            $('#btn-back').on('click', function() {
                $('#card-students').show()
                $('#card-record-result').hide()
            })
            $('#btn-reload').on('click', function() {
                $('.btn-view-record[data-id="' + studid + '"]').click()
            })
            $(document).on('click', '.btn-editrecord', function() {
                var torid = $(this).attr('data-torid');
                $('#modal-updaterecord').modal('show')
                $('#btn-submit-updaterecord').attr('data-torid', torid)
                $.ajax({
                    url: '{{ route('torgetrecord') }}',
                    type: 'GET',
                    data: {
                        studid: studid,
                        torid: torid
                    },
                    success: function(data) {
                        $('#container-editrecord').empty()
                        $('#container-editrecord').append(data)
                        $('.auto-disabled').find('input').attr('disabled')
                        $('.auto-disabled').find('button').hide()
                    }
                });

            })
            $(document).on('click', '#btn-submit-updaterecord', function() {

                var torid = $(this).attr('data-torid');
                var checkvalidation = 0;

                var schoolid = $('#editinput-schoolid').val();
                var syid = $('#editselect-sy').val();
                var schoolname = $('#editinput-schoolname').val();
                var schooladdress = $('#editinput-schooladdress').val();

                var customsy = $('#editinput-sy').val();

                if (syid == 0 && customsy.replace(/^\s+|\s+$/g, "").length == 0) {

                    $('#editinput-sy').css('border', '1px solid red');
                    $('#editsmall-selectsy').show();
                    checkvalidation = 1;
                } else {
                    $('#editinput-sy').removeAttr('style')
                    $('#editsmall-selectsy').hide();
                }

                var semid = $('#editselect-sem').val();

                var courseid = $('#editselect-course').val();
                var customcourse = $('#editinput-coursename').val();
                if (courseid == 0 && customcourse.replace(/^\s+|\s+$/g, "").length == 0) {
                    $('#editinput-coursename').css('border', '1px solid red');
                    $('#editsmall-selectcourse').show();
                    checkvalidation = 1;
                } else {
                    $('#editsmall-selectcourse').hide();
                }
                if (checkvalidation == 1) {
                    toastr.warning('Please fill in required fields!', 'Add new record')
                } else {
                    $(this).prop('disabled', true)
                    $.ajax({
                        url: '{{ route('torupdaterecord') }}',
                        type: 'GET',
                        dataType: '',
                        data: {
                            studid: studid,
                            torid: torid,
                            schoolid: schoolid,
                            schoolname: schoolname,
                            schooladdress: schooladdress,
                            syid: syid,
                            customsy: customsy,
                            semid: semid,
                            courseid: courseid,
                            customcourse: customcourse
                        },
                        success: function(data) {
                            if (data == 1) {
                                toastr.success('Updated successfully!', 'Update record')
                                $('#btn-close-updaterecord').click();
                                $('#modal-updaterecord').find('input,select').val('')
                                console.log('UPDATING...');
                                $('#btn-generate').trigger('click')
                                $('#btn-reload').click()

                                $('body').removeClass('modal-open');

                                $('.modal-backdrop').removeClass('show')
                                $('.modal-backdrop').remove()
                            } else {
                                toastr.warning('The same form already exists!',
                                    'Add new record')
                            }
                        }
                    });
                }
            })
            $(document).on('click', '#btn-details-save', function() {
                var parentguardian = $('#input-parentguardian').val();
                var address = $('#input-address').val();
                var cityaddress = $('#input-cityaddress').val();
                var elemcourse = $('#input-elemcourse').val();
                var elemdatecomp = $('#input-elemdatecomp').val();
                var elemschoolyear = $('#input-elemschoolyear').val();
                var secondcourse = $('#input-secondcourse').val();
                var seconddatecomp = $('#input-seconddatecomp').val();
                var secondschoolyear = $('#input-secondschoolyear').val();
                var admissiondate = $('#input-admissiondate').val();
                var degree = $('#input-degree').val();
                var thirdschoolyear = $('#input-thirdschoolyear').val();
                var basisofadmission = $('#input-basisofadmission').val();
                var major = $('#input-major').val();
                var minor = $('#input-minor').val();
                var specialorder = $('#input-specialorder').val();
                var graduationdate = $('#input-graduationdate').val();
                var remarks = $('#input-remarks').val();
                var pob = $('#input-placeofbirth').val();
                var mailingaddress = $('#input-mailingaddress').val();

                var intermediatecourse = $('#input-intermediatecourse').val();
                var intermediateschoolyear = $('#input-intermediateschoolyear').val();


                var dateadmitted = $('#input-dateadmitted').val();
                var collegeof = $('#input-collegeof').val();
                var entrancedata = $('#input-entrancedata').val();
                var admissionsem = $('#input-admissionsem').val();
                var admissionsy = $('#input-admissionsy').val();


                var intermediategrades = $('#input-intermediategrades').val();
                var secondarygrades = $('#input-secondarygrades').val();

                var placeofbirth = $('#input-placeofbirth').val();
                var acrno = $('#input-acrno').val();
                var citizenship = $('#input-citizenship').val();
                var civilstatus = $('#input-civilstatus').val();
                var parentaddress = $('#input-parentaddress').val();
                var guardianaddress = $('#input-guardianaddress').val();

                var entrancedate = $('#input-entrancedate').val();
                var schoolnameprimary = $('#input-schoolname-primary').val();
                var schooladdressprimary = $('#input-schooladdress-primary').val();
                var schoolyearprimary = $('#input-schoolyear-primary').val();
                var schoolnameintermediate = $('#input-schoolname-intermediate').val();
                var schooladdressintermediate = $('#input-schooladdress-intermediate').val();
                var schoolnamejunior = $('#input-schoolname-junior').val();
                var schooladdressjunior = $('#input-schooladdress-junior').val();
                var schoolyearjunior = $('#input-schoolyear-junior').val();
                var schoolnamesenior = $('#input-schoolname-senior').val();
                var schooladdresssenior = $('#input-schooladdress-senior').val();
                var schoolyearsenior = $('#input-schoolyear-senior').val();

                var nstpserialno = $('#input-nstpserialno').val();

                var graduationdegree = $('#input-graduationdegree').val();;
                var graduationmajor = $('#input-graduationmajor').val();;
                var graduationhonors = $('#input-graduationhonors').val();;
                var otherrecords = $('#input-otherrecords').val();;


                $.ajax({
                    url: '{{ route('torsavedetail') }}',
                    type: 'GET',
                    data: {
                        studid: studid,
                        pob: pob,
                        mailingaddress: mailingaddress,
                        parentguardian: parentguardian,
                        address: address,
                        cityaddress: cityaddress,
                        elemcourse: elemcourse,
                        elemdatecomp: elemdatecomp,
                        secondcourse: secondcourse,
                        seconddatecomp: seconddatecomp,
                        admissiondate: admissiondate,
                        degree: degree,
                        basisofadmission: basisofadmission,
                        major: major,
                        minor: minor,
                        specialorder: specialorder,
                        elemschoolyear: elemschoolyear,
                        secondschoolyear: secondschoolyear,
                        thirdschoolyear: thirdschoolyear,
                        remarks: remarks,
                        graduationdate: graduationdate,
                        dateadmitted: dateadmitted,
                        collegeof: collegeof,
                        entrancedata: entrancedata,
                        intermediategrades: intermediategrades,
                        secondarygrades: secondarygrades,
                        placeofbirth: placeofbirth,
                        acrno: acrno,
                        citizenship: citizenship,
                        civilstatus: civilstatus,
                        parentaddress: parentaddress,
                        guardianaddress: guardianaddress,
                        entrancedate: entrancedate,
                        schoolnameprimary: schoolnameprimary,
                        schooladdressprimary: schooladdressprimary,
                        schoolyearprimary: schoolyearprimary,
                        schoolnameintermediate: schoolnameintermediate,
                        schooladdressintermediate: schooladdressintermediate,
                        schoolnamejunior: schoolnamejunior,
                        schooladdressjunior: schooladdressjunior,
                        schoolyearjunior: schoolyearjunior,
                        schoolnamesenior: schoolnamesenior,
                        schooladdresssenior: schooladdresssenior,
                        schoolyearsenior: schoolyearsenior,
                        nstpserialno: nstpserialno,
                        intermediatecourse: intermediatecourse,
                        intermediateschoolyear: intermediateschoolyear,
                        admissionsem: admissionsem,
                        admissionsy: admissionsy,
                        graduationdegree: graduationdegree,
                        graduationmajor: graduationmajor,
                        graduationhonors: graduationhonors,
                        otherrecords: otherrecords
                    },
                    success: function(data) {
                        if (data == 1) {
                            toastr.success('Updated successfully!', 'Other details')
                        } else {
                            toastr.warning('Something went wrong!', 'Other details')
                        }
                    }
                })
            })
            $(document).on('click', '.btn-deleterecord', function() {
                var torid = $(this).attr('data-torid');
                var thisbutton = $(this);
                Swal.fire({
                    title: 'Are you sure you want to delete this record?',
                    // text: "You won't be able to revert this!",
                    html: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('tordeleterecord') }}',
                            type: "GET",
                            data: {
                                torid: torid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data) {
                                if (data == 1) {
                                    toastr.success('Deleted successfully!',
                                        'Delete Record')
                                    thisbutton.closest('.card').remove()
                                } else {
                                    toastr.warning('Something went wrong!',
                                        'Delete Record')
                                }
                            }
                        })
                    }
                })

            })
            $(document).on('change', '#editselect-sy', function() {
                if ($(this).val() == 0) {
                    $('#editdiv-customsy').show()
                } else {

                    $('#editdiv-customsy').hide()
                }
                console.log($(this).val())
                syid = $(this).val()
            })
            $(document).on('change', '#editselect-course', function() {
                if ($(this).val() == 0) {
                    $('#editdiv-customcourse').show()
                } else {
                    $('#editdiv-customcourse').hide()
                }
            })
            $(document).on('change', '#select-sy', function() {
                if ($(this).val() == 0) {
                    $('#div-customsy').show()
                } else {

                    $('#div-customsy').hide()
                }
                console.log($(this).val())
                syid = $(this).val()
            })
            $(document).on('change', '#select-course', function() {
                if ($(this).val() == 0) {
                    $('#div-customcourse').show()
                } else {
                    $('#div-customcourse').hide()
                }
            })

            $(document).on('click', '#btn-submit-addnewrecord', function() {
                var checkvalidation = 0;

                var schoolid = $('#input-schoolid').val();
                var schoolname = $('#input-schoolname').val();
                var schooladdress = $('#input-schooladdress').val();

                var customsy = $('#input-sy').val();
                var syid = $('#select-sy').val();

                console.log(syid)
                if (syid == 0 && customsy.replace(/^\s+|\s+$/g, "").length == 0) {

                    $('#input-sy').css('border', '1px solid red');
                    $('#small-selectsy').show();
                    checkvalidation = 1;
                } else {
                    $('#input-sy').removeAttr('style')
                    $('#small-selectsy').hide();
                }

                var semid = $('#select-sem').val();

                var courseid = $('#select-course').val();
                var customcourse = $('#input-coursename').val();
                if (courseid == 0 && customcourse.replace(/^\s+|\s+$/g, "").length == 0) {
                    console.log('course')
                    $('#input-coursename').css('border', '1px solid red');
                    $('#small-selectcourse').show();
                    checkvalidation = 1;
                } else {
                    $('#small-selectcourse').hide();
                }
                if (checkvalidation == 1) {
                    toastr.warning('Please fill in required fields!', 'Add new record')
                } else {
                    $(this).prop('disabled', true)
                    $.ajax({
                        url: '{{ route('toraddnewrecord') }}',
                        type: 'GET',
                        dataType: '',
                        data: {
                            studid: studid,
                            schoolid: schoolid,
                            schoolname: schoolname,
                            schooladdress: schooladdress,
                            syid: syid,
                            customsy: customsy,
                            semid: semid,
                            courseid: courseid,
                            customcourse: customcourse
                        },
                        success: function(data) {
                            if (data == 1) {
                                toastr.success('Added successfully!', 'Add new record')
                                $('#btn-close-addnewrecord').click();
                                $('#modal-newrecord').find('input,select').val('')
                                $('#btn-reload').click()
                                $('body').removeClass('modal-open');

                                $('.modal-backdrop').removeClass('show')
                                $('.modal-backdrop').remove()
                            } else {
                                toastr.warning('The same form already exists!',
                                    'Add new record')
                            }
                        }
                    });
                }
            })
            var torid = 0;

            function getsubjects(torid, semid, sydesc, courseid, studentid) {


                $.ajax({
                    url: '{{ route('torgetsubjects') }}',
                    type: 'GET',
                    data: {
                        torid: torid,
                        semid: semid,
                        sydesc: sydesc,
                        courseid: courseid,
                        studentid: studentid
                    },
                    success: function(data) {
                        if (data.length == 0) {

                            @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc' ||
                                    strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ccsa')
                                $('#tbody-adddata').append(

                                    '<tr class="tr-adddata">' +
                                    '<td class="p-0"><input type="text" class="form-control input-subjcode" placeholder="Code"/></td>' +
                                    '<td class="p-0"><input type="number" class="form-control input-subjunit" placeholder="Units"/></td>' +
                                    '<td class="p-0"><input type="text" class="form-control input-subjdesc" placeholder="Description"/></td>' +
                                    '<td class="p-0"><input type="number" class="form-control input-subjgrade" placeholder="Grade"/></td>' +
                                    '<td class="p-0"><input type="number" class="form-control input-subjreex" placeholder="Re-Ex"/></td>' +
                                    '<td class="p-0"><input type="number" class="form-control input-subjcredit" placeholder="Credit"/></td>' +
                                    '<td class="p-0"><button type="button" class="btn btn-default btn-adddata-save"><i class="fa fa-check text-success"></i></button><button type="button" class="btn btn-default btn-remove-row"><i class="fa fa-times text-danger"></i></button></td>' +
                                    '</tr>'
                                )
                            @else
                                $('#tbody-adddata').append(
                                    '<tr class="tr-adddata">' +
                                    '<td class="p-0"><input type="text" class="form-control input-subjcode" placeholder="Code"/></td>' +
                                    '<td class="p-0"><input type="number" class="form-control input-subjunit" placeholder="Units"/></td>' +
                                    '<td class="p-0"><input type="text" class="form-control input-subjdesc" placeholder="Description"/></td>' +
                                    '<td class="p-0"><input type="number" class="form-control input-subjgrade" placeholder="Grade"/></td>' +
                                    '<td class="p-0"><input type="number" class="form-control input-subjcredit" placeholder="Credit"/></td>' +
                                    '<td class="p-0"><button type="button" class="btn btn-default btn-adddata-save"><i class="fa fa-check text-success"></i></button><button type="button" class="btn btn-default btn-remove-row"><i class="fa fa-times text-danger"></i></button></td>' +
                                    '</tr>'
                                )
                            @endif

                        } else {
                            var selectoptions = '';
                            $.each(data, function(key, value) {
                                selectoptions += '<option value="' + value.id + '">' + value
                                    .subjdesc + '</option>'
                                if (key == 0) {
                                    selectoptions += '<option value="0">Custom Subject</option>'
                                }
                            })
                            $('#tbody-adddata').append(
                                '<tr class="tr-adddata">' +
                                '<td class="p-0"><input type="text" class="form-control input-subjcode" placeholder="Code" disabled value="' +
                                data[0].subjcode + '"/></td>' +
                                '<td class="p-0"><input type="number" class="form-control input-subjunit" placeholder="Units" disabled value="' +
                                (data[0].lecunits) + (data[0].labunits) + '"/></td>' +
                                '<td class="p-0"><select class="form-control select-subjdesc select2">' +
                                selectoptions + '</select></td>' +
                                '<td class="p-0"><input type="number" class="form-control input-subjgrade" placeholder="Grade"/></td>' +
                                '<td class="p-0"><input type="number" class="form-control input-subjcredit" placeholder="Credit"/></td>' +
                                '<td class="p-0"><button type="button" class="btn btn-default btn-adddata-save"><i class="fa fa-check text-success"></i></button><button type="button" class="btn btn-default btn-remove-row"><i class="fa fa-times text-danger"></i></button></td>' +
                                '</tr>'
                            )
                            $('.select2').select2()

                            $('.select-subjdesc').on('change', function() {
                                var thisrow = $(this).closest('tr')
                                if ($(this).val() == 0) {
                                    thisrow.find('.input-subjcode').prop('disabled', false)
                                    thisrow.find('.input-subjunit').prop('disabled', false)
                                    thisrow.find('.input-subjcode').val('')
                                    thisrow.find('.input-subjunit').val('')
                                    var thistd = thisrow.find('.select-subjdesc').closest('td');
                                    thistd = thistd.empty()
                                    thistd.append(
                                        '<input type="text" class="form-control input-subjdesc" placeholder="Description"/>'
                                    )
                                } else {
                                    $.ajax({
                                        url: '{{ route('torgetsubjects') }}',
                                        type: 'GET',
                                        data: {
                                            action: 'getinfo',
                                            subjectid: $(this).val()
                                        },
                                        success: function(data) {
                                            thisrow.find('.input-subjcode').val(data
                                                .subjcode)
                                            thisrow.find('.input-subjunit').val((
                                                data.lecunits) + (data
                                                .labunits))
                                        }
                                    });
                                }
                            })
                        }
                    }
                })
            }
            $(document).on('click', '.btn-adddata', function() {

                torid = $(this).attr('data-torid');
                var semid = $(this).attr('data-semid');
                var sydesc = $(this).attr('data-sydesc');
                var courseid = $(this).attr('data-courseid');
                var studentid = studid
                $('#btn-adddata-row').attr('data-torid', torid);
                $('#btn-adddata-row').attr('data-semid', semid);
                $('#btn-adddata-row').attr('data-sydesc', sydesc);
                $('#btn-adddata-row').attr('data-courseid', courseid);
                $('#modal-adddata').modal('show')

                getsubjects(torid, semid, sydesc, courseid, studentid)
            })
            $('#btn-adddata-row').on('click', function() {
                var semid = $(this).attr('data-semid');
                var sydesc = $(this).attr('data-sydesc');
                var courseid = $(this).attr('data-courseid');
                var studentid = studid
                @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc' ||
                        strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ccsa')
                    $('#tbody-adddata').append(

                        '<tr class="tr-adddata">' +
                        '<td class="p-0"><input type="text" class="form-control input-subjcode" placeholder="Code"/></td>' +
                        '<td class="p-0"><input type="number" class="form-control input-subjunit" placeholder="Units"/></td>' +
                        '<td class="p-0"><input type="text" class="form-control input-subjdesc" placeholder="Description"/></td>' +
                        '<td class="p-0"><input type="number" class="form-control input-subjgrade" placeholder="Grade"/></td>' +
                        '<td class="p-0"><input type="number" class="form-control input-subjreex" placeholder="Re-Ex"/></td>' +
                        '<td class="p-0"><input type="number" class="form-control input-subjcredit" placeholder="Credit"/></td>' +
                        '<td class="p-0"><button type="button" class="btn btn-default btn-adddata-save"><i class="fa fa-check text-success"></i></button><button type="button" class="btn btn-default btn-remove-row"><i class="fa fa-times text-danger"></i></button></td>' +
                        '</tr>'
                    )
                @else
                    getsubjects(torid, semid, sydesc, courseid, studentid)
                @endif
            })
            $(document).on('click', '.btn-remove-row', function() {
                $(this).closest('tr').remove()
            })
            $(document).on('click', '.btn-adddata-save', function() {
                console.log('ADDIING DATA');
                var thisrow = $(this).closest('tr');
                if (thisrow.find('.select-subjdesc').length == 0) {
                    var subjDescVal = thisrow.find('.input-subjdesc').val();
                    var subjGradeVal = thisrow.find('.input-subjgrade').val();

                    if (subjDescVal && subjGradeVal &&
                        subjDescVal.replace(/^\s+|\s+$/g, "").length > 0 &&
                        subjGradeVal.replace(/^\s+|\s+$/g, "").length > 0) {

                        var thisbutton = $(this);
                        var subjcode = $(this).closest('tr').find('.input-subjcode').val();
                        var subjunit = $(this).closest('tr').find('.input-subjunit').val();
                        var subjdesc = $(this).closest('tr').find('.input-subjdesc').val();
                        var subjgrade = $(this).closest('tr').find('.input-subjgrade').val();
                        var subjreex = $(this).closest('tr').find('.input-subjreex').val();
                        var subjcredit = $(this).closest('tr').find('.input-subjcredit').val();

                        $.ajax({
                            url: '{{ route('toraddnewdata') }}',
                            type: 'GET',
                            data: {
                                torid: torid,
                                studid: studid,
                                subjcode: subjcode,
                                subjunit: subjunit,
                                subjdesc: subjdesc,
                                subjgrade: subjgrade,
                                subjreex: subjreex,
                                subjcredit: subjcredit
                            },
                            success: function(data) {
                                if (data == 0) {
                                    toastr.warning('The same form already exists!',
                                        'Add new data')
                                } else {
                                    toastr.success('Added successfully!', 'Add new data')
                                    thisbutton.closest('td').empty()
                                    thisbutton.closest('td').append(
                                        '<button type="button" class="btn btn-default btn-editdata-save" data-subjgradeid="' +
                                        data +
                                        '"><i class="fa fa-share text-success"></i></button>' +
                                        '<button type="button" class="btn btn-default btn-delete-subjdata" data-subjgradeid="' +
                                        data +
                                        '"><i class="fa fa-trash text-danger"></i></button>'
                                    )
                                    thisbutton.remove()
                                }
                            }
                        })
                    } else {
                        $(this).closest('tr').find('.input-subjdesc').css('border', '1px solid red');
                        $(this).closest('tr').find('.input-subjgrade').css('border', '1px solid red');
                        toastr.warning('Please fill in required fields!', 'Add new data')
                    }
                } else {
                    if (thisrow.closest('tr').find('.input-subjgrade').val() && thisrow.closest('tr').find(
                            '.input-subjgrade').val().replace(/^\s+|\s+$/g, "").length > 0) {
                        var thisbutton = $(this);
                        var subjcode = $(this).closest('tr').find('.input-subjcode').val();
                        var subjunit = $(this).closest('tr').find('.input-subjunit').val();
                        var subjid = $(this).closest('tr').find('.select-subjdesc').val();
                        var getsubjdesc = $(this).closest('tr').find('.select-subjdesc option:selected');
                        var subjdesc = getsubjdesc.text();
                        var subjgrade = $(this).closest('tr').find('.input-subjgrade').val();
                        var subjreex = $(this).closest('tr').find('.input-subjreex').val();
                        var subjcredit = $(this).closest('tr').find('.input-subjcredit').val();

                        $.ajax({
                            url: '{{ route('toraddnewdata') }}',
                            type: 'GET',
                            data: {
                                torid: torid,
                                studid: studid,
                                subjcode: subjcode,
                                subjid: subjid,
                                subjunit: subjunit,
                                subjdesc: subjdesc,
                                subjgrade: subjgrade,
                                subjreex: subjreex,
                                subjcredit: subjcredit
                            },
                            success: function(data) {
                                if (data == 0) {
                                    toastr.warning('The same form already exists!',
                                        'Add new data')
                                } else {
                                    var thistd = thisbutton.closest('td');
                                    thistd.empty()
                                    toastr.success('Added successfully!', 'Add new data')
                                    thistd.append(
                                        '<button type="button" class="btn btn-default btn-editdata-save" data-subjgradeid="' +
                                        data +
                                        '"><i class="fa fa-share text-success"></i></button>' +
                                        '<button type="button" class="btn btn-default btn-delete-subjdata" data-subjgradeid="' +
                                        data +
                                        '"><i class="fa fa-trash text-danger"></i></button>'
                                    )
                                    // thisbutton.remove()
                                }
                            }
                        })
                    } else {
                        $(this).closest('tr').find('.input-subjdesc').css('border', '1px solid red');
                        $(this).closest('tr').find('.input-subjgrade').css('border', '1px solid red');
                        toastr.warning('Please fill in required fields!', 'Add new data')
                    }
                }
            })
            $(document).on('click', '.btn-editdata-save', function() {
                var subjgradeid = $(this).attr('data-subjgradeid');
                if ($(this).closest('tr').find('.input-subjdesc').val().replace(/^\s+|\s+$/g, "").length >
                    0 && $(this).closest('tr').find('.input-subjgrade').val().replace(/^\s+|\s+$/g, "")
                    .length > 0) {
                    var thisbutton = $(this);
                    var subjcode = $(this).closest('tr').find('.input-subjcode').val();
                    var subjunit = $(this).closest('tr').find('.input-subjunit').val();
                    var subjdesc = $(this).closest('tr').find('.input-subjdesc').val();
                    var subjgrade = $(this).closest('tr').find('.input-subjgrade').val();
                    var subjreex = $(this).closest('tr').find('.input-subjreex').val();
                    var subjcredit = $(this).closest('tr').find('.input-subjcredit').val();

                    $.ajax({
                        url: '{{ route('toreditsubjgrade') }}',
                        type: 'GET',
                        data: {
                            torid: torid,
                            subjgradeid: subjgradeid,
                            studid: studid,
                            subjcode: subjcode,
                            subjunit: subjunit,
                            subjdesc: subjdesc,
                            subjgrade: subjgrade,
                            subjreex: subjreex,
                            subjcredit: subjcredit
                        },
                        success: function(data) {
                            if (data == 1) {
                                toastr.success('Updated successfully!', 'Update data')
                            } else {
                                toastr.warning('The same form already exists!', 'Update data')
                            }
                        }
                    })
                } else {
                    $(this).closest('tr').find('.input-subjdesc').css('border', '1px solid red');
                    $(this).closest('tr').find('.input-subjgrade').css('border', '1px solid red');
                    toastr.warning('Please fill in required fields!', 'Update data')
                }
            })
            $(document).on('click', '.btn-delete-subjdata', function() {
                var subjgradeid = $(this).attr('data-subjgradeid');
                var thisbutton = $(this);
                Swal.fire({
                    title: 'Are you sure you want to delete this row?',
                    // text: "You won't be able to revert this!",
                    html: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('tordeletesubjgrade') }}',
                            type: "GET",
                            data: {
                                subjgradeid: subjgradeid
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data) {
                                if (data == 1) {
                                    toastr.success('Deleted successfully!',
                                        'Delete data')
                                    thisbutton.closest('tr').remove()
                                } else {
                                    toastr.warning('Something went wrong!',
                                        'Delete data')
                                }
                            }
                        })
                    }
                })
            })
            $(document).on('click', '.btn-editdata', function() {
                $(this).closest('tr').find('input,button').prop('disabled', false)
            })
            $("#modal-adddata").on("hidden.bs.modal", function() {
                $('#btn-generate').click();
            });
            $(document).on('click', '.btn-save-text', function() {
                var thisbutton = $(this);
                var removebutton = $(this).closest('.row').find('.btn-remove');
                var id = $(this).attr('data-id');
                var semid = $(this).attr('data-semid');
                var sydesc = $(this).attr('data-sydesc');
                var thistext = $(this).closest('.row').find('input').val();
                if (thistext.replace(/^\s+|\s+$/g, "").length == 0) {
                    $(this).closest('.row').find('input').css('border', '1px solid red')
                    toastr.warning('Fill in important field first!', 'Add text')
                } else {
                    $.ajax({
                        url: '{{ route('torsavetext') }}',
                        type: 'GET',
                        data: {
                            id: id,
                            studid: studid,
                            semid: semid,
                            sydesc: sydesc,
                            thistext: thistext
                        },
                        success: function(data) {
                            if (data == 1) {
                                toastr.success('Updated successfully!', 'Other details')
                                thisbutton.prop('disabled');
                                removebutton.remove()
                            } else {
                                toastr.warning('Something went wrong!', 'Other details')
                            }
                        }
                    })
                }
            })
            $(document).on('click', '.btn-delete-text', function() {
                var id = $(this).attr('data-id');
                var thisrow = $(this).closest('.row');
                Swal.fire({
                    title: 'Are you sure you want to delete this text?',
                    html: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('tordeletetext') }}',
                            type: "GET",
                            data: {
                                id: id
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            success: function(data) {
                                if (data == 1) {
                                    toastr.success('Deleted successfully!',
                                        'Delete Text')
                                    thisrow.remove()
                                } else {
                                    toastr.warning('Something went wrong!',
                                        'Delete Text')
                                }
                            }
                        })
                    }
                })
            })
            $(document).on('click', '#btn-exporttopdf', function() {
                var preparedby = $('#input-preparedby').val();
                var checkedby = $('#input-checkedby').val();
                var registrar = $('#input-registrar').val();
                var assistantreg = $('#input-assistantreg').val();
                var or = $('#input-or').val();
                var dateissued = $('#input-date-issued').val();
                window.open('/schoolform/tor/exporttopdf?studid=' + studid + '&registrar=' + registrar +
                    '&assistantreg=' + assistantreg + '&or=' + or + '&dateissued=' + dateissued +
                    '&preparedby=' + preparedby + '&checkedby=' + checkedby, '_blank')
            })

            $(document).on('click', '.btn-exportform', function() {
                var preparedby = $('#input-preparedby').val();
                var checkedby = $('#input-checkedby').val();
                var registrar = $('#input-registrar').val();
                var assistantreg = $('#input-assistantreg').val();
                var or = $('#input-or').val();
                var dateissued = $('#input-date-issued').val();
                var template = $(this).attr('data-template');
                window.open('/schoolform/tor/exporttopdf?studid=' + studid + '&registrar=' + registrar +
                    '&assistantreg=' + assistantreg + '&or=' + or + '&dateissued=' + dateissued +
                    '&preparedby=' + preparedby + '&checkedby=' + checkedby + '&template=' + template,
                    '_blank')
            })
            $('#btn-updatesignatories').on('click', function() {
                var signatories = []
                $('.each-signatory').each(function() {
                    obj = {
                        title: $(this).find('label').text(),
                        name: $(this).find('input').val()
                    }
                    signatories.push(obj)
                })
                // console.log(signatories)
                // var schooltreasure = $('#input-schooltreasure').val();
                // var oicregistrar   = $('#input-oicregistrar').val();
                $.ajax({
                    url: '{{ route('torsavesignatories') }}',
                    type: 'GET',
                    data: {
                        signatories: JSON.stringify(signatories)
                    },
                    success: function(data) {
                        if (data == 1) {
                            toastr.success('Updated successfully!', 'TOR Signatories')
                        } else {
                            toastr.warning('Something went wrong!', 'TOR Signatories')
                        }
                    }
                })
            })
        })
    </script>
@endsection
