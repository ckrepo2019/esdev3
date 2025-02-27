@extends('chairpersonportal.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <style>
        .select2-container .select2-selection--single {
            height: 40px;
        }
    </style>
@endsection

@section('content')
      <div class="modal fade" id="submitted_grade_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                        <div class="modal-header bg-warning">
                              <h4 class="modal-title">Submitted Grades!</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                        </div>
                        <div class="modal-body">
                            
                        </div>
                  </div>
            </div>
      </div>

    <div class="modal fade" id="student_final" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Student Grade Submission</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>   
                </div>
                <div class="modal-body">
                    <div class="row">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th width="15%">Subject:</th>
                                    <td width="85%" id="selected_subject"></td>
                                </tr>
                                <tr>
                                    <th>Section:</th>
                                    <td id="selected_section"></td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <hr class="mb-0">
                    <div class="row">
                        <div class="card-body table-responsive p-0" style="height: 300px;">
                            <table class="table table-sm table-bordered" id="final_grade_table">
                                <thead>
                                    <tr>
                                        <th rowspan="2" width="50%" class="text-center align-middle">Student</th>
                                        <th colspan="4" width="40%" class="text-center">Term</th>
                                        <th rowspan="2" width="10%" class="text-center align-middle p-0">Remarks</th>
                                    </tr>
                                    <tr>
                                        <th class="align-middle text-center p-0"  width="10%">Prelim</th>
                                        <th class="align-middle text-center p-0" width="10%">Midterm</th>
                                        <th class="align-middle text-center p-0" width="10%">Prefinal</th>
                                        <th class="align-middle text-center p-0" width="10%">Final</th>
                                    </tr>
                                </thead>
                                <tbody id="student_final_grades">
    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mt-0">
                    <div class="row">
                        <div class="col-md-5">
                            <button class="btn btn-success btn-sm" id="view_status">VIEW GRADES STATUS</button>
                        </div>
                        <div class="col-md-7">
                            <span class="badge badge-success float-right ml-1 mr-1">SUBMITTED</span>
                            <span class="badge badge-primary float-right ml-1 mr-1">APPROVED</span>
                            <span class="badge badge-warning float-right ml-1 mr-1">PENDING</span>
                            <span class="badge badge-info float-right ml-1 mr-1">POSTED</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="grade_status_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Student Grade Submission</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>   
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="40%">Term</th>
                                        <th width="60%">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="grade_status">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h4>GRADE SUBMISSION</h4>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item"><a href="/principalPortalSchedule">Grade submission</a></li>
            </ol>
            </div>
        </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary p-1">
                        </div>
                        <div class="card-body" >
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">SCHOOL YEAR</label>
                                    <select name="syid" id="syid" class="form-control select2">
                                        @foreach(DB::table('sy')->select('id','sydesc','isactive')->get() as $item)
                                            @if($item->isactive == 1)
                                                <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                            @else
                                                <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="">SEMESTER</label>
                                    <select name="semester" id="semester" class="form-control select2">
                                        @foreach(DB::table('semester')->select('id','semester','isactive')->get() as $item)
                                            @if($item->isactive == 1)
                                                <option value="{{$item->id}}" selected="selected">{{$item->semester}}</option>
                                            @else
                                                <option value="{{$item->id}}">{{$item->semester}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                    <div class="col-md-4">
                                          <button class="btn btn-primary" id="filter_sched"><i class="fas fa-filter"></i> FILTER</button>
                                    </div>
                                    <div class="col-md-4">

                                    </div>
                                    <div class="col-md-4">
                                          <a class="btn btn-warning btn-block" id="view_warning" hidden="hidden" href="#submitted_grades_modal"><i class="fas fa-exclamation-triangle"></i> <b>NEW GRADES AVAILABLE!</b></a>
                                    </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-head-fixed nowrap display table-sm p-0" style="width:100%" id="classsched">
                                        <thead>
                                            <tr>
                                                <th width="15%">SECTION</th>
                                                <th width="20%">SUBJECT CODE</th>
                                                <th width="40%">SUBJECT</th>
                                                <th width="25%">TEACHER</th>
                                            </tr>
                                        </thead>
                                        
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer  pt-1 pb-1 pl-2  bg-white d-flex justify-content-center">
                            <div id="data-container"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card" id="submitted_grades_modal">
                        <div class="card-header bg-warning p-1"></div>
                        <div class="card-body">
                            <h5>SUBMITTED GRADES</h5>
                            <hr>
                            <table  class="table table-bordered table-head-fixed nowrap display table-sm" id="submitted_grades_table" width="100%">
                                <thead>
                                    <tr>
                                        <th width="15%">SECTION</th>
                                        <th width="20%">SUBJECT CODE</th>
                                        <th width="40%">SUBJECT</th>
                                        <th width="25%">TEACHER</th>
                                    </tr>
                                </thead>
                          </table>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footerjavascript')

    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
   
    <script>
        $(document).ready(function(){
            
            $('.select2').select2()

            var syid = $('#syid').val()
            var semid = $('#semester').val()
            var section_subject
            var assigned_subj
            var student_list
            var subjid
            var sectionid

            get_assign_subj()

            function showIt() {
                  $('#view_warning').effect( "shake", {distance  : 2}, "slow" )
                  setTimeout( showIt, 1000);
            }

            showIt();

            $('#filter_sched').click(function(){
                syid = $('#syid').val()
                semid = $('#semester').val()
                get_assign_subj()
            })

            $(document).on('click','.approve_grades',function(){

                var statusid = $(this).attr('data-id')
                var datafield = $(this).attr('data-field')
                var valid = true;
                var student_field;

                if(datafield == 'prelimstatus' ){
                    student_field = 'prelemgrade'
                }
                else if(datafield == 'midtermstatus' ){
                    student_field = 'midtermgrade'
                }
                else if(datafield == 'prefistatus' ){
                    student_field = 'prefigrade'
                }
                else if(datafield == 'finalstatus' ){
                    student_field = 'finalgrade'
                }

                $('td[data-field="'+student_field+'"]').each(function(a,b){
                    console.log($(b).text())
                    if($(b).text() == ''){
                        valid = false
                    }
                })

                if(!valid){
                    Swal.fire({
                        type: 'info',
                        html: "Unable to approve grades.<br>"+
                                "Some students have no grades!"
                    });
                    return false
                }

                Swal.fire({
                    html:
                        'Are you sure you want <br>' +
                        'to approve grades?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve grades!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type:'GET',
                            url:'/college/student/grade/status/approve',
                            data:{
                                    statusid:statusid,
                                    datafield:datafield
                            },
                            success:function(data) {
                            
                                var grade_index = submitted_grades.findIndex(x=>x.subjectID == subjid && x.sectionID == sectionid)

                                if(datafield == 'prelimstatus'){
                                    grade_status[0].prelimstatus = 2
                                    submitted_grades[grade_index].prelimstatus = 2
                                }
                                else if(datafield == 'midtermstatus'){
                                    grade_status[0].midtermstatus = 2
                                    submitted_grades[grade_index].midtermstatus = 2
                                }
                                else if(datafield == 'prefistatus'){
                                    grade_status[0].prefistatus = 2
                                    submitted_grades[grade_index].prefistatus = 2
                                }
                                else if(datafield == 'finalstatus'){
                                    grade_status[0].finalstatus = 2
                                    submitted_grades[grade_index].finalstatus = 2
                                }

                                submitted_grades = submitted_grades.filter(function(x){
                                    if(x.prelimstatus == 1){
                                        return x
                                    }
                                    else if(x.midtermstatus == 1){
                                        return x
                                    }
                                    else if(x.prefistatus == 1){
                                        return x
                                    }
                                    else if(x.finalstatus == 1){
                                        return x
                                    }
                                })

                                student_load_grades()
                                load_grade_status_table()
                                load_submitted_grades()
                                load_submitted_datatable()
                            }
                        })
                    }
                })
                
            })

            $(document).on('click','.pending_grade',function(){

                var statusid = $(this).attr('data-id')
                var datafield = $(this).attr('data-field')

                Swal.fire({
                    html:
                        'Are you sure you want <br>' +
                        'to add grades to pendomg?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, add grades to pending!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type:'GET',
                            url:'/college/student/grade/status/pending',
                            data:{
                                    statusid:statusid,
                                    datafield:datafield
                            },
                            success:function(data) {
                            
                                var grade_index = submitted_grades.findIndex(x=>x.subjectID == subjid && x.sectionID == sectionid)

                                if(datafield == 'prelimstatus'){
                                    grade_status[0].prelimstatus = 4
                                    submitted_grades[grade_index].prelimstatus = 4
                                }
                                else if(datafield == 'midtermstatus'){
                                    grade_status[0].midtermstatus = 4
                                    submitted_grades[grade_index].midtermstatus = 4
                                }
                                else if(datafield == 'prefistatus'){
                                    grade_status[0].prefistatus = 4
                                    submitted_grades[grade_index].prefistatus = 4
                                }
                                else if(datafield == 'finalstatus'){
                                    grade_status[0].finalstatus = 4
                                    submitted_grades[grade_index].finalstatus = 4
                                }

                                submitted_grades = submitted_grades.filter(function(x){
                                    if(x.prelimstatus == 1){
                                        return x
                                    }
                                    else if(x.midtermstatus == 1){
                                        return x
                                    }
                                    else if(x.prefistatus == 1){
                                        return x
                                    }
                                    else if(x.finalstatus == 1){
                                        return x
                                    }
                                })

                                student_load_grades()
                                load_grade_status_table()
                                load_submitted_grades()
                                load_submitted_datatable()
                            }
                        })
                    }
                })

            })

            $(document).on('click','.post_grade',function(){
                var statusid = $(this).attr('data-id')
                var datafield = $(this).attr('data-field')

                Swal.fire({
                    html:
                        'Are you sure you want <br>' +
                        'to add grades to post?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, add grades to post!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type:'GET',
                            url:'/college/student/grade/status/post',
                            data:{
                                    statusid:statusid,
                                    datafield:datafield
                            },
                            success:function(data) {
                            
                                var grade_index = submitted_grades.findIndex(x=>x.subjectID == subjid && x.sectionID == sectionid)

                                if(datafield == 'prelimstatus'){
                                    grade_status[0].prelimstatus = 3
                                    submitted_grades[grade_index].prelimstatus = 3
                                }
                                else if(datafield == 'midtermstatus'){
                                    grade_status[0].midtermstatus = 3
                                    submitted_grades[grade_index].midtermstatus = 3
                                }
                                else if(datafield == 'prefistatus'){
                                    grade_status[0].prefistatus = 3
                                    submitted_grades[grade_index].prefistatus = 3
                                }
                                else if(datafield == 'finalstatus'){
                                    grade_status[0].finalstatus = 3
                                    submitted_grades[grade_index].finalstatus = 3
                                }

                                submitted_grades = submitted_grades.filter(function(x){
                                    if(x.prelimstatus == 3){
                                        return x
                                    }
                                    else if(x.midtermstatus == 3){
                                        return x
                                    }
                                    else if(x.prefistatus == 3){
                                        return x
                                    }
                                    else if(x.finalstatus == 3){
                                        return x
                                    }
                                })

                                student_load_grades()
                                load_grade_status_table()
                                load_submitted_grades()
                                load_submitted_datatable()
                            }
                        })
                    }
                })
            })

            function load_grade_status_table(){

                $('#grade_status').empty()

                if(grade_status[0].prelimstatus == null || grade_status[0].prelimstatus == 4){
                    $('#grade_status').append('<tr><td>PRELIM</td><td><button class="btn btn-default btn-sm btn-block" >NOT SUBMITTED</button></td></tr>')
                }
                else if(grade_status[0].prelimstatus == 1){
                    $('#grade_status').append('<tr><td class="align-middle">PRELIM</td><td><button class="btn btn-primary btn-sm btn-block approve_grades" data-id="'+grade_status[0].statid+'" data-field="prelimstatus">APPROVE GRADES</button><button class="btn btn-warning btn-sm btn-block pending_grade" data-id="'+grade_status[0].statid+'" data-field="prelimstatus">ADD TO PENDING</button><button class="btn btn-info btn-sm btn-block post_grade" data-id="'+grade_status[0].statid+'" data-field="prelimstatus">POST GRADES</button></td></tr>')
                }
                else if(grade_status[0].prelimstatus == 2){
                    $('#grade_status').append('<tr><td>PRELIM</td><td><button class="btn btn-warning btn-sm btn-block pending_grade" data-id="'+grade_status[0].statid+'" data-field="prelimstatus">ADD TO PENDING</button></td></tr>')
                }
                else if(grade_status[0].midtermstatus == 3){
                    $('#grade_status').append('<tr><td>PRELIM</td><td><button class="btn btn-info btn-sm btn-block">POSTED</button></td></tr>')
                }
              
                
                if(grade_status[0].midtermstatus == null || grade_status[0].midtermstatus == 4){
                    $('#grade_status').append('<tr><td >MIDTERM</td><td><button class="btn btn-default btn-sm btn-block ">NOT SUBMITTED</button></td></tr>')
                }
                else if(grade_status[0].midtermstatus == 1 ){
                    $('#grade_status').append('<tr><td class="align-middle">MIDTERM</td><td><button class="btn btn-primary btn-sm btn-block approve_grades" data-id="'+grade_status[0].statid+'" data-field="midtermstatus">APPROVE GRADES</button><button class="btn btn-warning btn-sm btn-block pending_grade" data-id="'+grade_status[0].statid+'" data-field="midtermstatus">ADD TO PENDING</button><button class="btn btn-info btn-sm btn-block post_grade" data-id="'+grade_status[0].statid+'" data-field="midtermstatus">POST GRADES</button></td></tr>')
                }
                else if(grade_status[0].midtermstatus == 2){
                    $('#grade_status').append('<tr><td>MIDTERM</td><td><button class="btn btn-warning btn-sm btn-block pending_grade" data-id="'+grade_status[0].statid+'" data-field="midtermstatus">ADD TO PENDING</button></td></tr>')
                }
                else if(grade_status[0].midtermstatus == 3){
                    $('#grade_status').append('<tr><td>MIDTERM</td><td><button class="btn btn-info btn-sm btn-block">POSTED</button></td></tr>')
                }

                if(grade_status[0].prefistatus == null || grade_status[0].prefistatus == 4){
                    $('#grade_status').append('<tr><td >PREFI</td><td><button class="btn btn-default btn-sm btn-block approve_grades" >NOT SUBMITTED</button></td></tr>')
                }
                else if(grade_status[0].prefistatus == 1){
                    $('#grade_status').append('<tr><td class="align-middle">PREFI</td><td><button class="btn btn-primary btn-sm btn-block approve_grades" data-id="'+grade_status[0].statid+'" data-field="prefistatus">APPROVE GRADES</button><button class="btn btn-warning btn-sm btn-block pending_grade" data-id="'+grade_status[0].statid+'" data-field="prefistatus">ADD TO PENDING</button><button class="btn btn-info btn-sm btn-block post_grade" data-id="'+grade_status[0].statid+'" data-field="prefistatus">POST GRADES</button></td></tr>')
                }
                else if(grade_status[0].prefistatus == 2){
                    $('#grade_status').append('<tr><td>PREFI</td><td><button class="btn btn-warning btn-sm btn-block pending_grade" data-id="'+grade_status[0].statid+'" data-field="prefistatus">ADD TO PENDING</button></td></tr>')
                }
                else if(grade_status[0].prefistatus == 3){
                    $('#grade_status').append('<tr><td>PREFIL</td><td><button class="btn btn-info btn-sm btn-block">POSTED</button></td></tr>')
                }

                if(grade_status[0].finalstatus == null || grade_status[0].finalstatus == 4){
                    $('#grade_status').append('<tr><td>FINAL</td><td><button class="btn btn-default btn-sm btn-block approve_grades">NOT SUBMITTED</button></td></tr>')
                }
                else if(grade_status[0].finalstatus == 1){
                    $('#grade_status').append('<tr><td >FINAL</td><td><button class="btn btn-primary btn-sm btn-block approve_grades" data-id="'+grade_status[0].statid+'" data-field="finalstatus">APPROVE GRADES</button><button class="btn btn-warning btn-sm btn-block pending_grade" data-id="'+grade_status[0].statid+'" data-field="finalstatus">ADD TO PENDING</button><button class="btn btn-info btn-sm btn-block post_grade" data-id="'+grade_status[0].statid+'" data-field="finalstatus">POST GRADES</button></td></tr>')
                }
                else if(grade_status[0].finalstatus == 2){
                    $('#grade_status').append('<tr><td>FINAL</td><td><button class="btn btn-warning btn-sm btn-block pending_grade" data-id="'+grade_status[0].statid+'" data-field="finalstatus">ADD TO PENDING</button><button class="btn btn-info btn-sm btn-block post_grade" data-id="'+grade_status[0].statid+'" data-field="finalstatus">POST GRADES</button></td></tr>')
                }
                else if(grade_status[0].finalstatus == 3){
                    $('#grade_status').append('<tr><td>FINAL</td><td><button class="btn btn-info btn-sm btn-block">POSTED</button></td></tr>')
                }
            }

            $(document).on('click','#view_status',function(){

                $('#grade_status_modal').modal()
                $('#grade_status').empty();
                load_grade_status_table()
               
            })

            $(document).on('click','.grade_submission',function(){
                $('#student_final_grades').empty();
                subjid = $(this).attr('data-subj')
                sectionid = $(this).attr('data-sectionid')
                $('#student_final').modal()
                var selected_sched = section_subject.filter(x=>x.subjectID==subjid && x.sectionID == sectionid)
                $('#selected_subject').text(selected_sched[0].subjDesc)
                $('#selected_section').text(selected_sched[0].sectionDesc)
                get_grade_status()
                get_subject_student()
           
            })

            // $(document).on('click','#view_warning',function(){
            //       load_submitted_datatable()
            // })

            function load_submitted_datatable(){
                $("#submitted_grades_table").DataTable({
                    destroy: true,
                    data:submitted_grades,
                    "scrollX": true,
                    columns: [
                                { "data": "sectionDesc" },
                                { "data": "subjCode" },
                                { "data": "subjDesc" },
                                { "data": "lastname" },
                                
                            ],
                    "columnDefs": [
                        {  "targets": 2,
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = '<a href="#" class="grade_submission" data-subj="'+rowData.subjectID+'" data-sectionID="'+rowData.sectionID+'">'+rowData.subjDesc+'</a>'
                                
                            } 
                        }
                        
                    ]
                });
            }



            
            function get_assign_subj(){

                $.ajax({
                    type:'GET',
                    url:'/chairperson/section/subject',
                    data:{
                            syid:syid,
                            semid:semid,
                    },
                    success:function(data) {
                        section_subject = data
                        loadtable()
                        load_submitted_grades()
                        load_submitted_datatable()
                       
                    }
                })

            }
     
            function get_subject_student(){

                $.ajax({
                    type:'GET',
                    url:'/college/subject/students',
                    data:{
                            syid:syid,
                            semid:semid,
                            subjid:subjid,
                            sectionid:sectionid
                    },
                    success:function(data) {
                        student_list = data
                        student_load_grades()
                    }
                })

            }

            function student_load_grades(){

                var male = 0;
                var female = 0;
                $('#student_final_grades').empty()
                $.each(student_list,function(a,b){

                    if( ( b.gender == 'FEMALE' || b.gender == 'Female' ) && female == 0){
                        $('#student_final_grades').append('<tr class="bg-secondary"><th colspan="6">FEMALE</th></tr>')
                        female = 1
                    }
                    if( ( b.gender == 'MALE' || b.gender == 'Male' ) && male == 0){
                        $('#student_final_grades').append('<tr class="bg-secondary"><th colspan="6">MALE</th></tr>')
                        male = 1
                    }

                    var prelemgrade = ''
                    var midtermgrade = ''
                    var prefigrade = ''
                    var finalgrade = ''
                    var bgprelim
                    var bgmidterm
                    var bgprefi
                    var bgfinal
                 
                    if(b.prelemgrade != null){
                        prelemgrade = b.prelemgrade
                        if(grade_status[0].prelimstatus == 1){
                            bgprelim = 'bg-success'
                        }else if(grade_status[0].prelimstatus == 2){
                            bgprelim = 'bg-primary'
                        }
                        else if(grade_status[0].prelimstatus == 4){
                            bgprelim = 'bg-warning'
                        }
                        else if(grade_status[0].prelimstatus == 3){
                            bgprelim = 'bg-info'
                        }
                        else{
                            bgprelim = 'bg-secondary'
                        }
                    }
                    if(b.midtermgrade != null){
                        midtermgrade = b.midtermgrade
                        if(grade_status[0].midtermstatus == 1){
                            bgmidterm = 'bg-success'
                        }
                        else if(grade_status[0].midtermstatus == 2){
                            bgmidterm = 'bg-primary'
                        }
                        else if(grade_status[0].midtermstatus == 4){
                            bgmidterm = 'bg-warning'
                        }
                        else if(grade_status[0].midtermstatus == 3){
                            bgmidterm = 'bg-info'
                        }
                        else{
                            bgmidterm = 'bg-secondary'
                        }
                    }
                    if(b.prefigrade != null){
                        prefigrade = b.prefigrade
                        if(grade_status[0].prefistatus == 1){
                            bgprefi = 'bg-success'
                        }
                        else if(grade_status[0].prefistatus == 2){
                            bgprefi = 'bg-primary'
                        }
                        else if(grade_status[0].prefistatus == 4){
                            bgprefi = 'bg-warning'
                        }
                        else if(grade_status[0].prefistatus == 3){
                            bgprefi = 'bg-info'
                        }
                        else{
                            bgprefi = 'bg-secondary'
                        }
                    }
                    if(b.finalgrade != null){
                        finalgrade = b.finalgrade
                        if(grade_status[0].finalstatus == 1){
                            bgfinal = 'bg-success'
                        }
                        else if(grade_status[0].finalstatus == 2){
                            bgfinal = 'bg-primary'
                        }
                        else if(grade_status[0].finalstatus == 3){
                            bgfinal = 'bg-info'
                        }
                        else if(grade_status[0].finalstatus == 4){
                            bgfinal = 'bg-warning'
                        }
                        else{
                            bgfinal = 'bg-secondary'
                        }
                    }

                    var remarks = ''
                    var bgremarks = ''
                    if(b.remarks != null){
                        remarks = b.remarks
                        if(remarks == 'PASSED'){
                            bgremarks = 'bg-success'
                        }
                        else if(remarks == 'FAILED' || remarks == 'DROPPED' || remarks == 'INC'){
                            bgremarks = 'bg-danger'
                        }
                    }

                    $('#student_final_grades').append('<tr><th>'+b.lastname+', '+b.firstname+'</th><td class="text-center p-0 align-middle '+bgprelim+'" data-field="prelemgrade">'+prelemgrade+'</td><td class="text-center p-0 align-middle '+bgmidterm+'" data-field="midtermgrade ">'+midtermgrade+'</td><td class="text-center p-0 align-middle '+bgprefi+'" data-field="prefigrade">'+prefigrade+'</td><td class="text-center p-0 align-middle '+bgfinal+'" data-field="finalgrade">'+finalgrade+'</td><td class="text-center align-middle p-0 '+bgremarks+'">'+remarks+'</td></tr>')

                })

            }

            var submitted_grades = [];
            var grade_status;

            function get_grade_status(){

                $.ajax({
                    type:'GET',
                    url:'/college/student/grade/status',
                    data:{
                            syid:syid,
                            semid:semid,
                            subjid:subjid,
                            sectionid:sectionid
                    },
                    success:function(data) {
                        grade_status = data
                        load_grade_status_table()
                    }
                })

            }

            function loadtable(){

                  $("#classsched").DataTable({
                        destroy: true,
                        data:section_subject,
                        "scrollX": true,
                        columns: [
                                    { "data": "sectionDesc" },
                                    { "data": "subjCode" },
                                    { "data": "subjDesc" },
                                    { "data": "lastname" },
                                 
                                ],
                        "columnDefs": [
                              {  "targets": 2,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td)[0].innerHTML = '<a href="#" class="grade_submission" data-subj="'+rowData.subjectID+'" data-sectionID="'+rowData.sectionID+'">'+rowData.subjDesc+'</a>'
                                } 
                              } ,
                              {  "targets": 3,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td)[0].innerHTML = rowData.lastname+', '+rowData.firstname
                              } 
                            }
                        ]
                  });

            }

            function load_submitted_grades(){
                submitted_grades = []
                var submitted_grades_count = 0;
                $.each(section_subject,function(a,b){
                    if(b.prelimstatus == 1){
                        submitted_grades.push(b)
                        submitted_grades_count += 1
                    }
                    else if(b.midtermstatus == 1){
                        submitted_grades.push(b)
                        submitted_grades_count += 1
                    }
                    else if(b.prefistatus == 1){
                        submitted_grades.push(b)
                        submitted_grades_count += 1
                    }
                    else if(b.finalstatus == 1){
                        submitted_grades.push(b)
                        submitted_grades_count += 1
                    }
                })
                
                if(submitted_grades_count == 0){
                    $('#pending_grade_count').text('')
                }
                else{
                    $('#pending_grade_count').text(submitted_grades_count)
                }

                if(submitted_grades_count > 0){
                    $('#view_warning').removeAttr('hidden')
                    showIt();
                }

                // load_pending_table()
            }



        })
    </script>
 

@endsection


