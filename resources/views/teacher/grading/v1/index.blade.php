@extends('teacher.layouts.app')


@section('content')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }
    </style>

    <style>
        .ribbon-wrapper .ribbon {
            box-shadow: 0 0 3px rgb(0 0 0 / 30%);
            font-size: 0.7rem;
            line-height: 100%;
            padding: 0.375rem 0;
            position: relative;
            right: -1px;
            text-align: center;
            text-shadow: 0 -1px 0 rgb(0 0 0 / 40%);
            text-transform: uppercase;
            top: 2px;
            -webkit-transform: rotate(45deg);
            transform: rotate(45deg);
            width: 100px;
        }
    </style>
    @php
        $userid = auth()->user()->id;

        $userref = DB::table('users')->join('faspriv', 'users.id', '=', 'faspriv.userid')->join('usertype', 'usertype.id', '=', 'faspriv.usertype')->where('users.id', $userid)
            ->select(
                    'usertype.refid',
            )
            ->get();

    @endphp
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>System Grading</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">System Grading</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5><i class="fa fa-filter"></i> Filter</h5>
            <h5 class="ml-auto">Active S.Y.: {{ collect($schoolyears)->where('isactive', 1)->first()->sydesc }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="mb-2 col-sm-12 col-md-4">
                    <label for="selectedschoolyear">School Year</label>
                    <select class="form-control select2" id="selectedschoolyear" style="width: 100%;">
                        @foreach (collect($schoolyears)->sortByDesc('sydesc')->values() as $schoolyear)
                            <option value="{{ $schoolyear->id }}" @if ($schoolyear->isactive == 1) selected @endif>
                                {{ $schoolyear->sydesc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2 col-sm-12 col-md-4">
                    <label for="selectedsemester">Semester</label>
                    <select class="form-control select2" id="selectedsemester" style="width: 100%;">
                        @foreach ($semesters as $semester)
                            <option value="{{ $semester->id }}" @if ($semester->isactive == 1) selected @endif>
                                {{ $semester->semester }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="sections-container">
    </div>

    <div class=" d-none" id="tesda_container">
        <h5>Tesda Batches</h5>
        <hr>
        <div class="row" id="tesda_batches_container">

        </div>
    </div>

    <div class="modal fade" id="gradeStatusSubject" aria-hidden="true" data-backdrop="static" >
        <div class="modal-dialog modal-xl">
              <div class="modal-content">
                    <div class="modal-header pb-2 pt-2 border-0 bg-secondary">
                          <h6 class="modal-title  mb-0 gradeStatusSubject_title"></h6>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body pt-3" >
                          <div class="row" style="font-size:.7rem">
                                <div class="col-md-2">
                                      <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Teacher</label>
                                      <p class="mb-0" id="teacherName"></p>
                                </div>
                                <div class="col-md-3">
                                      <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Subject</label>
                                      <p class="mb-0" id="subjectDesc"></p>
                                </div>
                                <div class="col-md-2">
                                      <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Level</label>
                                      <p class="mb-0" id="levelName"></p>
                                </div>
                                <div class="col-md-3">
                                      <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Section</label>
                                      <p class="mb-0" id="sectionName"></p>
                                </div>
                                <div class="col-md-2">
                                      <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Grade Status</label>
                                      <br>
                                      <p class="mb-0" id="gradeStatus"></p>
                                </div>
                          </div>
                          <div class="row mt-5">
                                <div class="col-md-6 row">
                                      <div class="col-md-3 text-center">
                                            <button class="btn btn-sm btn-primary w-100 py-special">Approve</button>
                                      </div>
                                      <div class="col-md-3 text-center">
                                            <button class="btn btn-sm btn-info w-100 py-special">Post</button>
                                      </div>
                                      <div class="col-md-3 text-center">
                                            <button class="btn btn-sm btn-warning w-100 py-special">Pending</button>
                                      </div>
                                      <div class="col-md-3 text-center">
                                            <button class="btn btn-sm btn-danger w-100 py-special">Unpost</button>
                                      </div>
                                </div>
                          </div>
                          <div id="ecr_table_container">
                                
                          </div>
                          <div class="row mt-3" style="font-size:.7rem">
                                <div class="col-md-3"></div>
                                <div class="col-md-3">
                                      <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Number of Students Enrolled</label>
                                      <div>Male: <span id="maleCount" class="font-weight-bold"></span></div>
                                      <div>Female: <span id="femaleCount"  class="font-weight-bold"></span></div>
                                      <div>Total: <span id="totalCount"  class="font-weight-bold"></span></div>
                                </div>
                                <div class="col-md-3">
                                      <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Grade Remarks</label>
                                      <div>Passed: <span id="passedCount"  class="font-weight-bold">0</span></div>
                                      <div>Failed: <span id="failedCount"  class="font-weight-bold">0</span></div>
                                </div>
                                <div class="col-md-3">
                                      <label for="" class="mb-0 p-0"><i class="fa fa-book"></i> Grade Status</label>
                                      <div class="row">
                                            <div class="col-md-6">Not Submitted: <span id="notSubmittedCount"  class="font-weight-bold">0</span></div>
                                            <div class="col-md-6">Pending: <span id="pendingCount"  class="font-weight-bold">0</span></div>
                                      </div>
                                      <div class="row">
                                            <div class="col-md-6">Submitted: <span id="submittedCount"  class="font-weight-bold"></span>0</div>
                                            <div class="col-md-6">Posted: <span id="postedCount"  class="font-weight-bold">0</span></div>
                                      </div>
                                      <div>Approved: <span id="approvedCount"  class="font-weight-bold">0</span></div>
                                </div>
                          </div>
                    </div>
              </div>
        </div>
  </div>   

    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        var $ = jQuery;
        $(document).ready(function() {
            var refid = @json($userref);
            var userid = @json($userid);
            
            
            $('.select2').select2()

            var selectedschoolyear = $('#selectedschoolyear').val();
            var selectedsemester = $('#selectedsemester').val();

            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            view_sections()

            $(document).on('change', '#selectedsemester , #selectedschoolyear', function() {
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                view_sections()
            })

            function view_sections() {
                $.ajax({
                    url: '/grades/getsections',
                    type: 'GET',
                    data: {
                        selectedschoolyear: selectedschoolyear,
                        selectedsemester: selectedsemester
                    },
                    success: function(data) {

                        if (data.filter(x => x.levelid == 14 || x.levelid == 15).length > 0) {
                            $('#strand_holder').removeAttr('hidden')
                        } else {
                            if (selectedsemester != 2) {
                                $('#strand_holder').attr('hidden', 'hidden')
                            }
                        }

                        $('#sections-container').empty()
                        if (data.length > 0) {
                            $.each(data, function(key, value) {

                                var with_pending = '<div class="ribbon-wrapper">' +
                                    '<div class="ribbon bg-warning">' +
                                    'With <br>Pending' +
                                    '</div>' +
                                    '</div>'

                                if (!value.with_pending) {
                                    with_pending = ''
                                }

                                $('#sections-container').append(
                                    '<div class="col-lg-3 col-sm-12 col-md-4 mb-2 ">' +
                                    '<div class="small-box bg-info  h-100 shadow"  style="width: 100%; display: grid;">' +
                                    with_pending +
                                    '<div class="inner" >' +
                                    '<p>' + value.sectionname + '</p>' +
                                    '<sup>' + value.levelname + '</sup>' +
                                    '</div>' +
                                    '<a href="/grades/getsubjects?selectedschoolyear=' +
                                    selectedschoolyear + '&selectedsemester=' +
                                    selectedsemester + '&selectedlevelid=' + value.levelid +
                                    '&selectedsectionid=' + value.sectionid +
                                    '" class="small-box-footer">' +
                                    'Select <i class="fas fa-arrow-circle-right"></i>' +
                                    '</a>' +
                                    '</div>' +
                                    '</div>'
                                )
                            })
                        } else {
                            $('#sections-container').append(
                                '<div class=" col-md-12"><div class="card shadow"><div class="card-body p-2">No Records Found.</div></div></div>'
                            )
                        }
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                        view_tesda_schedule()

                    }
                })
            }
            function view_tesda_schedule(){
                if (refid.some(r => r.refid == 36)) {
                    $.ajax({
                        type: 'GET',
                        url: '/tesda/batch_setup/get/trainer/scheds',
                        data: {
                            userid: userid
                        },
                        success: function(data) {
                            if(data.length > 0){                                
                                $('#tesda_container').removeClass('d-none')
                                $.each(data, function(key, value) {
                                    $('#tesda_batches_container').append(
                                        '<div class="col-lg-3 col-sm-12 col-md-4 mb-2 ">' +
                                        '<div class="small-box bg-info  h-100 shadow"  style="width: 100%; display: grid;">' +
                                        '<div class="inner pb-0" >' +
                                        '<p class="mb-0">' + value.batch_desc + '</p>' +
                                        '</div>' +
                                        '<div class="inner pt-0" >' +
                                        '<p>' + value.competency_code + ' - ' + value.competency_desc +  '</p>' +
                                        '</div>' +
                                        '<a href="#" class="small-box-footer" id="show_grading">' +
                                        'Select <i class="fas fa-arrow-circle-right"></i>' +
                                        '</a>' +
                                        '</div>' +
                                        '</div>'
                                    )
                                })
                            }else{
                                $('#tesda_container').removeClass('d-none')
                                $('#tesda_batches_container').append(
                                    '<div class=" col-md-12"><div class="card shadow"><div class="card-body p-2">No Tesda Records Found.</div></div></div>'
                                )
                            }
                            
                        }
                    })
                    
                }
            }
            
            $(document).on('click', '#show_grading', function() {
                $('#gradeStatusSubject').modal('show')

            })

            $('#selectedschoolyear').on('change', function() {
                selectedschoolyear = $(this).val();
            })

            $('#selectedsemester').on('change', function() {
                selectedsemester = $(this).val();
            })
        });
    </script>
@endsection
