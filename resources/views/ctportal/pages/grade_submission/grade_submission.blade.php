@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">

    <style>
        .select2-container .select2-selection--single {
            height: 40px;
        }

        #student_final_grades td{
            text-align: center;
            cursor: pointer;
            vertical-align: middle !important;
        }
    </style>
   
@endsection

@section('content')

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
                            <button class="btn btn-primary btn-sm" id="save_grades">SAVE GRADES</button>
                            <button class="btn btn-success btn-sm" id="view_status">VIEW GRADES STATUS</button>
                        </div>
                        <div class="col-md-7">
                            <span class="badge badge-success float-right ml-1 mr-1">SUBMITTED</span>
                            <span class="badge badge-primary float-right ml-1 mr-1">APPROVED</span>
                            <span class="badge badge-warning float-right ml-1 mr-1">PENDING</span>
                            <span class="badge badge-info float-right ml-1 mr-1">POSTED</span>
                            <span class="badge badge-secondary float-right ml-1 mr-1">NOT SUBMITTED</span>
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

    <div class="modal fade" id="proccess_count_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-success p-2">
                    <h4 class="modal-title" id="proccess_message"></h4>
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="progress_bar">
                        </div>
                    </div>
                    <p class="mb-1"><code id="percentage">0%</code></p>
                    <div class="text-right">
                        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="proccess_done" hidden>Done</button>
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
                <li class="breadcrumb-item"><a href="#">Grade submission</a></li>
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
                                    <button class="btn btn-primary" id="filter_sched">FILTER</button>
                                </div>
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-5">
                                      <a class="btn btn-warning btn-block" id="view_warning" hidden="hidden" href="#submitted_grades_modal"><i class="fas fa-exclamation-triangle"></i> <b>SOME GRADES ARE ADDED TO PENDING!</b></a>
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
                                                <th width="65%">SUBJECT</th>
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
                    <div class="card">
                        <div class="card-header bg-warning p-1"></div>
                        <div class="card-body">
                            <h5>PENDING GRADES</h5>
                            <hr>
                            <table class="table table-bordered table-head-fixed nowrap display table-sm p-0" style="width:100%" id="pending_grades_table">
                                <thead>
                                    <tr>
                                        <th width="15%">SECTION</th>
                                        <th width="20%">SUBJECT CODE</th>
                                        <th width="65%">SUBJECT</th>
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


    <script src="{{asset('js/pagination.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
   
    <script>
        $(document).ready(function(){
            
            $('.select2').select2()

            var syid = $('#syid').val()
            var semid = $('#semester').val()
            var teacherid = '{{DB::table('teacher')->where('userid',auth()->user()->id)->first()->id}}'
            var assigned_subj
            var student_list
            var subjid
            var sectionid

            get_assign_subj()

            $('#filter_sched').click(function(){
                syid = $('#syid').val()
                semid = $('#semester').val()
                get_assign_subj()
            })

            $(document).on('click','.grade_submission',function(){
                $('#student_final_grades').empty();
                subjid = $(this).attr('data-subj')
                sectionid = $(this).attr('data-sectionid')
                $('#student_final').modal()
                var selected_sched = assigned_subj.filter(x=>x.subjectID==subjid && x.sectionID == sectionid)
                $('#selected_subject').text(selected_sched[0].subjDesc)
                $('#selected_section').text(selected_sched[0].sectionDesc)
                //get_subject_student()
                get_grade_status()
            })

            $(document).on('click','#view_status',function(){
                $('#grade_status_modal').modal()
                get_grade_status()
            })

            $(document).on('click','.submit_grade',function(){
             
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
                    if($(b).text() == ''){
                        valid = false
                    }
                })

                if(!valid){
                    Swal.fire({
                        type: 'info',
                        html: "Unable to submit grades.<br>"+
                                "Some students have no grades!"
                    });
                    return false
                }

                Swal.fire({
                    html:
                        'Are you sure you want <br>' +
                        'to submit grades?',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, submit grades!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type:'GET',
                            url:'/college/student/grade/status/submit',
                            data:{
                                    statusid:statusid,
                                    datafield:datafield
                            },
                            success:function(data) {
                                Swal.fire({
                                    type: 'info',
                                    text: "Grades submitted successfully!"
                                });

                                var grade_index = pending_grades_list.findIndex(x=>x.subjectID == subjid && x.sectionID == sectionid)
                                if(datafield == 'prelimstatus'){
                                    grade_status[0].prelimstatus = 1
                                    if(grade_index != -1){
                                        pending_grades_list[grade_index].prelimstatus = 1
                                    }
                                }
                                else if(datafield == 'midtermstatus'){
                                    grade_status[0].midtermstatus = 1
                                    if(grade_index != -1){
                                        pending_grades_list[grade_index].midtermstatus = 1
                                    }
                                }
                                else if(datafield == 'prefistatus'){
                                    grade_status[0].prefistatus = 1
                                    if(grade_index != -1){
                                        pending_grades_list[grade_index].prefistatus = 1
                                    }
                                }
                                else if(datafield == 'finalstatus'){
                                    grade_status[0].finalstatus = 1
                                    if(grade_index != -1){
                                        pending_grades_list[grade_index].finalstatus = 1
                                    }
                                  
                                }
                              
                              
                                load_student_grades_table()
                                get_grade_status()
                                get_pending_grades()
                            }
                        })
                    }
                })
            })


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
                        $('#grade_status').empty()
                        if(data[0].prelimstatus == null || data[0].prelimstatus == 4){
                            $('#grade_status').append('<tr><td>PRELIM</td><td><button class="btn btn-default btn-sm submit_grade btn-block" id="" data-id="'+data[0].statid+'" data-field="prelimstatus">SUBMIT GRADES</button></td></tr>')
                        }
                        else if(data[0].prelimstatus == 1){
                            $('#grade_status').append('<tr><td>PRELIM</td><td><button class="btn btn-success btn-sm btn-block">SUBMITTED</button></td></tr>')
                        }
                        else if(data[0].prelimstatus == 2){
                            $('#grade_status').append('<tr><td>PRELIM</td><td><button class="btn btn-primary btn-sm btn-block">GRADES APPROVED</button></td></tr>')
                        }
                        else if(data[0].prelimstatus == 3){
                            $('#grade_status').append('<tr><td>PRELIM</td><td><button class="btn btn-info btn-sm btn-block">GRADES POSTED</button></td></tr>')
                        }
                       
                        
                        if(data[0].midtermstatus == null || data[0].midtermstatus == 4){
                            $('#grade_status').append('<tr><td>MIDTERM</td><td><button class="btn btn-default btn-sm submit_grade btn-block" id="" data-id="'+data[0].statid+'" data-field="midtermstatus">SUBMIT GRADES</button></td></tr>')
                        }
                        else if(data[0].midtermstatus == 1){
                            $('#grade_status').append('<tr><td>MIDTERM</td><td><button class="btn btn-success btn-sm btn-block">SUBMITTED</button></td></tr>')
                        }
                        else if(data[0].midtermstatus == 2){
                            $('#grade_status').append('<tr><td>MIDTERM</td><td><button class="btn btn-primary btn-sm btn-block">GRADES APPROVED</button></td></tr>')
                        }
                        else if(data[0].midtermstatus == 3){
                            $('#grade_status').append('<tr><td>MIDTERM</td><td><button class="btn btn-info btn-sm btn-block">GRADES POSTED</button></td></tr>')
                        }

                        if(data[0].prefistatus == null || data[0].prefistatus == 4){
                            $('#grade_status').append('<tr><td>PREFI</td><td><button class="btn btn-default btn-sm submit_grade btn-block" id="" data-id="'+data[0].statid+'" data-field="prefistatus">SUBMIT GRADES</button></td></tr>')
                        }
                        else if(data[0].prefistatus == 1){
                            $('#grade_status').append('<tr><td>PREFI</td><td><button class="btn btn-success btn-sm btn-block">SUBMITTED</button></td></tr>')
                        }
                        else if(data[0].prefistatus == 2){
                            $('#grade_status').append('<tr><td>PREFI</td><td><button class="btn btn-primary btn-sm btn-block">GRADES APPROVED</button></td></tr>')
                        }
                        else if(data[0].prefistatus == 3){
                            $('#grade_status').append('<tr><td>PREFI</td><td><button class="btn btn-info btn-sm btn-block">GRADES POSTED</button></td></tr>')
                        }

                        if(data[0].finalstatus == null || data[0].finalstatus == 4){
                            $('#grade_status').append('<tr><td>FINAL</td><td><button class="btn btn-default btn-sm submit_grade btn-block" id="" data-id="'+data[0].statid+'" data-field="finalstatus">SUBMIT GRADES</button></td></tr>')
                        }
                        else if(data[0].finalstatus == 1){
                            $('#grade_status').append('<tr><td>FINAL</td><td><button class="btn btn-success btn-sm btn-block">SUBMITTED</button></td></tr>')
                        }
                        else if(data[0].finalstatus == 2){
                            $('#grade_status').append('<tr><td>PRELIM</td><td><button class="btn btn-primary btn-sm btn-block">GRADES APPROVED</button></td></tr>')
                        }
                        else if(data[0].finalstatus == 3){
                            $('#grade_status').append('<tr><td>PRELIM</td><td><button class="btn btn-info btn-sm btn-block">GRADES POSTED</button></td></tr>')
                        }
                        
                        load_student_grades_table()
                       
                    }
                })

            }



            function get_assign_subj(){

                $.ajax({
                    type:'GET',
                    url:'/college/assignedsubj',
                    data:{
                            syid:syid,
                            semid:semid,
                            teacherid:teacherid
                    },
                    success:function(data) {
                        assigned_subj = data
                        loadtable(assigned_subj)
                        get_pending_grades()
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
                        get_grade_status()
                        // load_student_grades_table()
                    }
                })

            }

            

            function load_student_grades_table(){

               
                $('#student_final_grades').empty()
                var male = 0;
                var female = 0;

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

                    var append_string = '<tr><th>'+b.lastname+', '+b.firstname+'</th>'

                    if(b.prelemgrade != null){
                        prelemgrade = b.prelemgrade
                    }
                    if(b.midtermgrade != null){
                        midtermgrade = b.midtermgrade
                    }
                    if(b.prefigrade != null){
                        prefigrade = b.prefigrade
                    }
                    if(b.finalgrade != null){
                        finalgrade = b.finalgrade
                    }

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

                        if(grade_status[0].prelimstatus == null  || grade_status[0].prelimstatus == 4){
                            append_string += '<td class="text-center p-0 align-middle '+bgprelim+'" data-field="prelemgrade" data-studid="'+b.id+'">'+prelemgrade+'</td>'
                        }else{
                            append_string += '<th class="text-center p-0 align-middle '+bgprelim+'">'+prelemgrade+'</th>'
                        }
                        
                    }
                    else{
                        if(grade_status[0].prelimstatus == null || grade_status[0].prelimstatus == 4){
                            append_string += '<td class="text-center p-0 align-middle '+bgprelim+'" data-field="prelemgrade" data-studid="'+b.id+'"></td>'
                        }else{
                            append_string += '<th></th>'
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
                        
                        if(grade_status[0].midtermstatus == null || grade_status[0].midtermstatus == 4){
                            append_string += '<td class="text-center p-0 align-middle '+bgmidterm+'" data-field="midtermgrade" data-studid="'+b.id+'">'+midtermgrade+'</td>'
                        }
                        else{
                            append_string += '<th class="text-center p-0 align-middle '+bgmidterm+'">'+midtermgrade+'</th>'
                        }
                    }
                    else{
                        if(grade_status[0].midtermstatus == null || grade_status[0].prelimstatus == 4){
                            append_string += '<td class="text-center p-0 align-middle '+bgmidterm+'" data-field="midtermgrade" data-studid="'+b.id+'"></td>'
                        }else{
                            append_string += '<th></th>'
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
                       
                        if(grade_status[0].prefistatus == null || grade_status[0].prefistatus == 4){
                            append_string += '<td class="text-center p-0 align-middle '+bgprefi+'" data-field="prefigrade" data-studid="'+b.id+'">'+prefigrade+'</td>'
                        }
                        else{
                            append_string += '<th class="text-center p-0 align-middle '+bgprefi+'">'+prefigrade+'</th>'
                        }
                    }
                    else{
                        if(grade_status[0].prefistatus == null || grade_status[0].prefistatus == 4){
                            append_string += '<td class="text-center p-0 align-middle '+bgprefi+'" data-field="prefigrade" data-studid="'+b.id+'"></td>'
                        }else{
                            append_string += '<th></th>'
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
                        else if(grade_status[0].finalstatus == 4){
                            bgfinal = 'bg-warning'
                        }
                        else if(grade_status[0].finalstatus == 3){
                            bgfinal = 'bg-info'
                        }
                        else{
                            bgfinal = 'bg-secondary'
                        }

                        if(grade_status[0].finalstatus == null || grade_status[0].finalstatus == 4){
                            append_string += '<td class="text-center p-0 align-middle '+bgfinal+'" data-field="finalgrade" data-studid="'+b.id+'">'+finalgrade+'</td>'
                        }
                        else{
                            append_string += '<th class="text-center p-0 align-middle '+bgfinal+'">'+finalgrade+'</th>'
                        }
                        
                    }else{
                        if(grade_status[0].finalstatus == null || grade_status[0].finalstatus == 4){
                            append_string += '<td class="text-center p-0 align-middle '+bgfinal+'" data-field="finalgrade" data-studid="'+b.id+'"></td>'
                        }else{
                            append_string += '<th></th>'
                        }
                        
                    }
                    var remarks = ''
                    var bgremarks = ''
                    if(b.remarks != null){
                        remarks = b.remarks
                        if(remarks == 'PASSED'){
                            bgremarks = 'bg-success'
                        }
                        else if(remarks == 'FAILED' || remarks == 'INC' || remarks == 'DROPPED'){
                            bgremarks = 'bg-danger'
                        }
                        append_string += '<th data-field="remarks" data-studid="'+b.id+'" class="text-center p-0 align-middle '+bgremarks+'">'+remarks+'</th></tr>'
                    }else{
                        append_string += '<th data-field="remarks" data-studid="'+b.id+'" class="text-center p-0 align-middle"></th>'
                    }
                    $('#student_final_grades').append(append_string)
                })

            }

            var pending_grades 
            var pending_grades_list

            function get_pending_grades(){
                $('#view_warning').attr('hidden','hidden')
                var count_pending_grades = 0
                pending_grades_list = []
                $.each(assigned_subj,function(a,b){
                    if(b.prelimstatus == 4){
                        count_pending_grades+=1
                        pending_grades_list.push(b)
                    }
                    else if(b.midtermstatus == 4){
                        count_pending_grades+=1
                        pending_grades_list.push(b)
                    }
                    else if(b.prefistatus == 4){
                        count_pending_grades+=1
                        pending_grades_list.push(b)
                    }
                    else if(b.finalstatus == 4){
                        count_pending_grades+=1
                        pending_grades_list.push(b)
                    }
                })

                if(count_pending_grades == 0){
                    $('#pending_grade_count').text('')
                }
                else{
                    $('#pending_grade_count').text(count_pending_grades)
                }

                if(count_pending_grades > 0){
                    $('#view_warning').removeAttr('hidden')
                    showIt();
                }

                load_pending_table()
                
            }

            function load_pending_table(){
                $("#pending_grades_table").DataTable({
                    destroy: true,
                    data:pending_grades_list,
                    "scrollX": true,
                    columns: [
                                { "data": "sectionDesc" },
                                { "data": "subjCode" },
                                { "data": "subjDesc" },
                                
                            ],
                    "columnDefs": [
                        {  "targets": 0,
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = '<a href="#" class="grade_submission" data-subj="'+rowData.subjectID+'" data-sectionID="'+rowData.sectionID+'">'+rowData.sectionDesc+'</a>'
                        
                            } 
                        
                        },

                    ]
                });
            }

            function showIt() {
                  $('#view_warning').effect( "shake", {distance  : 2}, "slow" )
                  setTimeout( showIt, 1000);
            }

           


            function loadtable(){
                $("#classsched").DataTable({
                        destroy: true,
                        data:assigned_subj,
                        "scrollX": true,
                        columns: [
                                    { "data": "sectionDesc" },
                                    { "data": "subjCode" },
                                    { "data": "subjDesc" },
                                 
                                ],
                        "columnDefs": [
                            {  "targets": 0,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td)[0].innerHTML = '<a href="#" class="grade_submission" data-subj="'+rowData.subjectID+'" data-sectionID="'+rowData.sectionID+'">'+rowData.sectionDesc+'</a>'
                         
                                } 
                            
                            },

                        ]
                    });
            }

            //----------------------------------------------

            var currentIndex 
            var string = ''
            var update_list = []

            $(document).on('click','#final_grade_table tbody td',function(){
                $('#start').removeClass('bg-danger')
                $(currentIndex).removeClass('bg-danger')
                if(currentIndex != undefined){
                    string = $(this).text();
                    currentIndex = this;
                    if($('#start').length > 0 ){
                        dotheneedful(this);
                    }
                    $('td').removeAttr('style');
                    $('#start').removeAttr('id')
                    $(this).attr('id','start')
                    $('td').css('min-width','40px')
                    var start = document.getElementById('start');
                                    start.focus();
                                    // start.style.backgroundColor = 'red';
                                    $(start).addClass('bg-danger')
                                    start.style.color = 'white';
                }
                else{
                    string = $(this).text();
                    currentIndex = this;
                    if($('#start').length > 0 ){
                        dotheneedful(this);
                    }
                    $('td').removeAttr('style');
                    $('#start').removeAttr('id')
                    $(this).attr('id','start')
                    var start = document.getElementById('start');
                                        start.focus();
                                        // start.style.backgroundColor = 'red';
                                        $(start).addClass('bg-danger')
                                        start.style.color = 'white';
                    $('td').css('min-width','40px')
                }
            })

            var can_procced = true

            function proccess_save(){
                if(update_list.length != 0){
                    var temp_data = update_list[0]
                    $.ajax({
                        type:'GET',
                        url:'/college/student/grade/save',
                        data:{
                                subjid:subjid,
                                field:temp_data.field,
                                grade:temp_data.grade,
                                studid:temp_data.studid,
                                syid:syid,
                                semid:semid,
                        },
                        success:function(data) {

                            proccess_count += 1
                            var temp_array = []
                            $.each(update_list,function(a,b){
                                if(b.studid != temp_data.studid || b.field != temp_data.field){
                                    temp_array.push(b)
                                }
                            })  

                            update_list = temp_array
                            var percentage = ( proccess_count / process_length ) * 100
                            $('#progress_bar').css('width', percentage.toFixed()+'%')
                            $('#percentage').text(percentage.toFixed()+'%')
                            if(percentage == 100){
                                proccess_count = 0
                                process_length = 0
                                get_subject_student()
                                $('#proccess_done').removeAttr('hidden')
                            }
                            proccess_save()
                        }
                    })
                }
            }

            var proccess_count = 0
            var process_length = 0

            $(document).on('click','#save_grades',function(){
                process_length = update_list.length
                $('#progress_bar').css('width', '0%')
                if(process_length > 0){
                    var selected_sched = assigned_subj.filter(x=>x.subjectID==subjid && x.sectionID == sectionid)
                    proccess_count = 0
                    $('#proccess_count_modal').modal()
                    $('#proccess_done').attr('hidden','hidden')
                    proccess_save()
                   
                }else{
                    Swal.fire({
                        type: 'info',
                        text: "No new grades added!"
                    });
                }
            })

            function dotheneedful(sibling) {
                if (sibling != null) {
                    $(currentIndex).removeClass('bg-danger')
                    currentIndex = sibling
                    start.focus();
                    start.style.backgroundColor = '';
                    start.style.color = '';
                    sibling.focus();
                    // sibling.style.backgroundColor = 'red';
                    sibling.style.color = 'white';
                    $(sibling).addClass('bg-danger')
                    start = sibling;
                    $('#message').empty();
                    string = $(currentIndex)[0].innerText
                }
            }

            document.onkeydown = checkKey;
           

            function checkKey(e) {
                console.log(e)
                e = e || window.event;
                if (e.keyCode == '38' && currentIndex != undefined)  {
                    var idx = start.cellIndex;
                    var nextrow = start.parentElement.previousElementSibling;
                    $('#curText').text(string)
                    if (nextrow != null) {
                        var sibling = nextrow.cells[idx];
                        string = sibling.innerText;
                        dotheneedful(sibling);
                    }
                }else if (e.keyCode == '40' && currentIndex != undefined) {
                    var idx = start.cellIndex;
                    var nextrow = start.parentElement.nextElementSibling;
                    $('#curText').text(string)
                    if (nextrow != null) {
                        var sibling = nextrow.cells[idx];
                        string = sibling.innerText;
                        dotheneedful(sibling);
                    }
                }else if (e.keyCode == '37' && currentIndex != undefined) {
                    var sibling = start.previousElementSibling;
                    $('#curText').text(string)
                    if($(sibling)[0].cellIndex != 0 && !$(sibling).is('th')){
                        string = sibling.innerText;
                        dotheneedful(sibling);
                    }
                }else if (e.keyCode == '39' && currentIndex != undefined) {
                   
                    var sibling = start.nextElementSibling;
                    $('#curText').text(string)
                    if($(sibling)[0].cellIndex != 0 && !$(sibling).is('th')){
                        string = sibling.innerText;
                        dotheneedful(sibling);
                    }
                }
                else if( e.key == "Backspace" && currentIndex != undefined){
                    string = currentIndex.innerText
                    string = string.slice(0 , -1);
                    if(string.length == 0){
                        string = 0
                    }
                    currentIndex.innerText = parseFloat(string)
                    store_to_array(string)
                }
                else if ( e.key == 'i' || e.key == 'I') {
                    if($(currentIndex).attr('data-field') == 'finalgrade'){
                        $('th[data-studid="'+$(currentIndex).attr('data-studid')+'"][data-field="remarks"]').text('INC')
                        remarks = 'INC'
                        store_to_array_remarks(remarks)
                        currentIndex.innerText = '0.00'
                    }
                }
                else if ( e.key >= 0 && e.key <= 9 && currentIndex != undefined) {
                    var temp_string = string+e.key
                    if(temp_string <= 5){
                        string += e.key;
                    }
                    if($(currentIndex).attr('data-field') == 'finalgrade'){
                        var remarks = ''
                        $('th[data-studid="'+$(currentIndex).attr('data-studid')+'"][data-field="remarks"]').removeClass('bg-success')
                        $('th[data-studid="'+$(currentIndex).attr('data-studid')+'"][data-field="remarks"]').removeClass('bg-danger')
                        console.log(parseFloat(string).toFixed(2))
                       
                        if(parseFloat(string).toFixed(2) == '0' || parseFloat(string).toFixed(2) == '0.00'){
                            remarks = 'DROPPED'
                            bgremarks = 'bg-secondary'
                            $('th[data-studid="'+$(currentIndex).attr('data-studid')+'"][data-field="remarks"]').text(remarks)
                        }
                        else if(parseFloat(string).toFixed(2) <= 3){
                            remarks = 'PASSED'
                            bgremarks = 'bg-success'
                            $('th[data-studid="'+$(currentIndex).attr('data-studid')+'"][data-field="remarks"]').text(remarks)
                        }
                        else{
                            remarks = 'FAILED'
                            bgremarks = 'bg-danger'
                            $('th[data-studid="'+$(currentIndex).attr('data-studid')+'"][data-field="remarks"]').text(remarks)
                        }
                        $('th[data-studid="'+$(currentIndex).attr('data-studid')+'"][data-field="remarks"]').addClass(bgremarks)
                        store_to_array_remarks(remarks)
                    }
                    if(parseFloat(string).toFixed(2) <= 5){
                        currentIndex.innerText = parseFloat(string).toFixed(2)
                        store_to_array(string)
                    }
                    else{
                        string = string.slice(0 , -1);
                    }
                }
                else if (e.key == '.' && currentIndex != undefined) {
                    var temp_string = string+e.key
                    if(temp_string <= 5){
                        string += e.key;
                    }
                    if($(currentIndex).attr('data-field') == 'finalgrade'){
                        var remarks = ''
                        $('th[data-studid="'+$(currentIndex).attr('data-studid')+'"][data-field="remarks"]').removeClass('bg-success')
                        $('th[data-studid="'+$(currentIndex).attr('data-studid')+'"][data-field="remarks"]').removeClass('bg-danger')
                        console.log(parseFloat(string).toFixed(2))
                        if(parseFloat(string).toFixed(2) == '0' || parseFloat(string).toFixed(2) == '0.00'){
                            remarks = 'DROPPED'
                            bgremarks = 'bg-secondary'
                            $('th[data-studid="'+$(currentIndex).attr('data-studid')+'"][data-field="remarks"]').text(remarks)
                        }
                        else if(parseFloat(string).toFixed(2) <= 3){
                            remarks = 'PASSED'
                            bgremarks = 'bg-secondary'
                            $('th[data-studid="'+$(currentIndex).attr('data-studid')+'"][data-field="remarks"]').text(remarks)
                        }
                        else{
                            remarks = 'FAILED'
                            bgremarks = 'bg-danger'
                            $('th[data-studid="'+$(currentIndex).attr('data-studid')+'"][data-field="remarks"]').text(remarks)
                        }
                        $('td[data-studid="'+$(currentIndex).attr('data-studid')+'"][data-field="remarks"]').addClass(bgremarks)
                        store_to_array_remarks(remarks)
                    }
                    if(parseFloat(string).toFixed(2) <= 5){
                        currentIndex.innerText = parseFloat(string).toFixed(2) 
                        store_to_array(string)
                    }
                    else{
                        string = string.slice(0 , -1);
                    }
                }
            }

            function store_to_array(grade){
                var studid = $(currentIndex).attr('data-studid')
                var field = $(currentIndex).attr('data-field')
                var temp_student_record = update_list.filter(x=>x.studid==studid && x.field == field)
                if(temp_student_record.length == 0){
                    update_list.push({
                        studid:$(currentIndex).attr('data-studid'),
                        field:$(currentIndex).attr('data-field'),
                        grade:grade
                    })
                }else{
                    var grade_index = update_list.findIndex(x=>x.studid==studid && x.field == field)
                    update_list[grade_index].studid = $(currentIndex).attr('data-studid')
                    update_list[grade_index].field = $(currentIndex).attr('data-field')
                    update_list[grade_index].grade = grade
                }
            }

            function store_to_array_remarks(grade){
                var studid = $(currentIndex).attr('data-studid')
                var field = 'remarks'
                var temp_student_record = update_list.filter(x=>x.studid==studid && x.field == 'remarks')
                if(temp_student_record.length == 0){
                    update_list.push({
                        studid:$(currentIndex).attr('data-studid'),
                        field:field,
                        grade:grade
                    })
                }else{
                    var grade_index = update_list.findIndex(x=>x.studid==studid && x.field == 'remarks')
                    update_list[grade_index].studid = $(currentIndex).attr('data-studid')
                    update_list[grade_index].field = field
                    update_list[grade_index].grade = grade
                }
            }



        })
    </script>
    
    <script>
        $(document).ready(function(){

           

        })
    </script>

@endsection


