@extends('teacher.layouts.app')

@section('headerjavascript')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <style>
        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            border: 0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }
    </style>
@endsection

@section('content')

    @php
        $subj_strand = DB::table('sh_sectionblockassignment')
                    ->join('sh_block',function($join){
                        $join->on('sh_sectionblockassignment.blockid','=','sh_block.id');
                        $join->where('sh_block.deleted',0);
                    })
                    ->join('sh_strand',function($join){
                        $join->on('sh_block.strandid','=','sh_strand.id');
                        $join->where('sh_strand.deleted',0);
                    })
                    ->where('sh_sectionblockassignment.deleted',0)
                    ->select(
                        'sectionid',
                        'strandid',
                        'strandcode'
                    )->get();
    @endphp

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Grade Summary</h1>
                    </div>
                    <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Grade Summary</li>
                    </ol>
                    </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body" >
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group mb-0">
                                        <label for="">Grade Level</label>
                                        @php
                                            $allsections = array();
                                        @endphp
                                        <select class="form-control select2" id="gradelevel">
                                            <option selected value="" >Select Grade Level</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-0">
                                        <label for="">Section</label>
                                        <select name="section" id="section" class="form-control select2">
                                            <option selected value="" >Select Section</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2" >
                                    <div class="form-group  mb-0" id="strand_holder" hidden>
                                        <label for="">Strand</label>
                                        <select name="strand" id="strand" class="form-control select2">
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                
                                </div>
                                <div class="col-md-2">
                                    <label for="">School Year</label>
                                    <select name="syid" id="syid" class="form-control select2">
                                        @foreach(DB::table('sy')->select('id','sydesc','isactive')->orderBy('sydesc')->get() as $item)
                                            @if($item->isactive == 1)
                                                <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                            @else
                                                <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="">Semester</label>
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
                        </div>
                    </div>
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="mb-0">Master Sheet</h5>
                                    <div class=" master_sheet_option">
                                        <span class="badge badge-primary">APPROVED</span>
                                        <span class="badge badge-info">POSTED</span>
                                        <span class="badge badge-success">SUBMITTED</span>
                                        <span class="badge badge-secondary">NOT SUBMITTED</span>
                                    </div>
                                </div>
                                <div class="col-md-2 pt-2">
                                        <select name="quarter" id="quarter" class="form-control select2 ">
                                            <option selected value="" >Select Quarter</option>
                                        </select>
                                </div>
                                <div class="col-md-2 pt-2">
                                        <button class="btn btn-primary btn-block btn-sm" id="filter" >VIEW MASTER SHEET</button>
                                </div>
                            </div>
                            <hr>
                            <table class="table table-bordered table-head-fixed nowrap display table-sm p-0" id="student_list" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th class="text-center">S1</th>
                                        <th class="text-center">S2</th>
                                        <th class="text-center">S3</th>
                                        <th class="text-center">S4</th>
                                        <th class="text-center">S5</th>
                                        <th class="text-center">S6</th>
                                        <th class="text-center">S7</th>
                                        <th class="text-center">S8</th>
                                        <th class="text-center">S9</th>
                                        <th class="text-center">S10</th>
                                        <th class="text-center">S11</th>
                                        <th class="text-center">S12</th>
                                        <th class="text-center">S13</th>
                                        <th class="text-center">S14</th>
                                        <th class="text-center">S15</th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="row mt-2">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-default" id="print_ms">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </button>
                                    <button class="btn btn-default" id="print_ms_excel">
                                        <i class="fas fa-file-excel"></i> EXCEL
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow">
                        <div class="card-body">
                              <div class="row">
                                    <div class="col-md-3"><h5 class="mb-0">Grading Sheet</h5></div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-4">
                                          <select name="grading_sheet_subject" id="grading_sheet_subject" class="form-control select2">
                                          <option selected value="" >SELECT SUBJECT</option>
                                          </select>
                                    </div>
                                    <div class="col-md-4">
                                          <button class="btn btn-primary btn-block btn-sm" id="grading_sheet_filter" disabled="disabled">VIEW GRADING SHEET</button>
                                    </div>
                              </div>
                              <hr>
                            <table class="table table-bordered table-head-fixed nowrap display table-sm p-0" style="width:100%" id="grading_sheet_table_list"  >
                                 <thead>
                                    <th>Student Name</th>
                                    <th class="text-center gs_q1">Q1</th>
                                    <th class="text-center gs_q2">Q2</th>
                                    <th class="text-center gs_q3">Q3</th>
                                    <th class="text-center gs_q4">Q4</th>
                                    <th class="text-center">FINAL GRADE</th>
                                    <th class="text-center">REMARK</th>
                                </thead>
                            </table>
                            <div class=" master_sheet_option mt-2">
                                <button class="btn btn-default float-right " id="print_gs" disabled="disabled">
                                    <i class="fas fa-print"></i> PRINT GRADING SHEET
                                </button>
                            </div>
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

            var tid = '{{DB::table('teacher')->where('userid',auth()->user()->id)->first()->id}}';
            var students = [];
            var sections = [];

            loaddatatable(students)
            reset_data_table()
            get_advisory()
            $('.select2').select2()

            var strand = @json($subj_strand)

            function get_advisory(){

                  var syid = $('#syid').val()
                  var semid = $('#semester').val()

                  $.ajax({
                        type:'GET',
                        url:'/teacher/get/advisory',
                        data:{
                                tid:tid,
                                syid:syid,
                                semid:semid,
                        },
                        success:function(data) {
                              sections = data;
                              var arrayUniqueByKey = [...new Map(sections.map(item =>
                              [item['levelid'], item])).values()];

                              $.each(arrayUniqueByKey,function(a,b){
                                    $('#gradelevel').append('<option value="'+b.levelid+'">'+b.levelname+'</option>')
                              })
                        }
                    })
            }

            reset_sf9_report ()
            
            function reset_sf9_report (){
                  var temp_reset_data = []
                  $("#sf9_sheet_table_list").DataTable({
                        destroy: true,
                        data:temp_reset_data,
                        "scrollX": true,
                        "columnDefs": [
                              {"title":"Student Name","targets":0},
                              {"title":"Gen. Ave.","targets":1},
                              {"title":"Remarks","targets":2},
                        ]
                  })

            }


            $(document).on('change','#semester , #syid',function(){
                  $('#strand').empty()
                  $('#gradelevel').empty()
                  $('#gradelevel').append('<option value="">SELECT GRADE LEVEL</option>')

                  $('#section').empty()
                  $('#section').append('<option value="">Select Section</option>')

                  $('#sf9_grade_table').empty();

                  $('#sf9_student_name').empty();
                  $('#sf9_student_name').append('<option value="">SELECT STUDENT</option>')
                  $('#grading_sheet_subject').empty();
                  $('#grading_sheet_subject').append('<option value="">SELECT SUBJECT</option>')
                  $("#grading_sheet_subject").select2({
                        allowClear: true,
                        placeholder: "Select Subject",
                    })
                  reset_data_table()
                  get_advisory()
                  reset_sf9_report()

                  $('#print_ms').attr('disabled','disabled')
                  $('#print_gs').attr('disabled','disabled')

            })

            $(document).on('click','#print_ms',function(){
                var gradelevel = $('#gradelevel').val();
                var section = $('#section').val();
                var quarter  = $('#quarter').val(); 
                var syid  = $('#syid').val(); 
                var semid = 1
                if(gradelevel == 14 || gradelevel == 15){
                    var semid = $('#semester').val()
                }   
                var strand  = $('#strand').val(); 
                if(section == null){
                    Swal.fire({
                            type: 'info',
                            title: 'Something went wrong!',
                            text: 'Please reload the page',
                            showConfirmButton: false,
                            timer: 1500
                    });
                }
                else{
                    window.open("/grades/report/mastersheet?gradelevel="+gradelevel+"&section="+section+"&quarter="+quarter+"&sy="+syid+"&semid="+semid+'&strand='+strand);
                }
            })

            $(document).on('click','#print_ms_excel',function(){
                var gradelevel = $('#gradelevel').val();
                var section = $('#section').val();
                var quarter  = $('#quarter').val(); 
                var syid  = $('#syid').val(); 
                var semid = 1
                if(gradelevel == 14 || gradelevel == 15){
                    var semid = $('#semester').val()
                }   
                var strand  = $('#strand').val(); 
                if(section == null){
                    Swal.fire({
                            type: 'info',
                            title: 'Something went wrong!',
                            text: 'Please reload the page',
                            showConfirmButton: false,
                            timer: 1500
                    });
                }
                else{
                    window.open("/grades/report/mastersheet/excel?gradelevel="+gradelevel+"&section="+section+"&quarter="+quarter+"&sy="+syid+"&semid="+semid+"&strand="+strand);
                }
            })


            $(document).on('click','#print_gs',function(){
                var gradelevel = $('#gradelevel').val();
                var section = $('#section').val();
                var quarter  = $('#quarter').val(); 
                var syid  = $('#syid').val(); 
                var semid = 1
                if(gradelevel == 14 || gradelevel == 15){
                    var semid = $('#semester').val()
                }   
                var subjid  = $('#grading_sheet_subject').val(); 
                var strand  = $('#strand').val(); 
                if(section == null){
                    Swal.fire({
                            type: 'info',
                            title: 'Something went wrong!',
                            text: 'Please reload the page',
                            showConfirmButton: false,
                            timer: 1500
                    });
                }
                else{
                    window.open("/grades/report/gradingsheet/bysubject?gradelevel="+gradelevel+"&section="+section+"&quarter="+quarter+"&sy="+syid+"&semid="+semid+"&subjid="+subjid+'&strandid='+strand);
                }
            })

            $(document).on('change','#gradelevel',function(){
                $('#section').empty()

                selectedGradeLevel = $(this).val()

                $('#section').append('<option value="">Select Section</option>')
                $.each(sections,function(a,b){
                    if(b.levelid == selectedGradeLevel){
                            $('#section').append('<option value="'+b.sectionid+'">'+b.sectionname+'</option>')
                    }
                })
                $("#section").select2({
                    allowClear: true,
                    placeholder: "Select Subject",
                })

                $('#quarter').empty();
                $('#quarter').append('<option value="">Select Quarter</option>')
                if($(this).val() == 14 || $(this).val() == 15){
                    if($('#semester').val() == 1){
                        $('#quarter').append('<option value="1">1st Quarter</option>')
                        $('#quarter').append('<option value="2">2nd Quarter</option>')
                        $('#quarter').append('<option value="5">Final Rating</option>')
                    }else{
                        $('#quarter').append('<option value="3">3rd Quarter</option>')
                        $('#quarter').append('<option value="4">4th Quarter</option>')
                        $('#quarter').append('<option value="5">Final Rating</option>')
                    }
                    $('#strand_holder').removeAttr('hidden')
                }else{
                    $('#quarter').append('<option value="1">1st Quarter</option>')
                    $('#quarter').append('<option value="2">2nd Quarter</option>')
                    $('#quarter').append('<option value="3">3rd Quarter</option>')
                    $('#quarter').append('<option value="4">4th Quarter</option>')
                    $('#quarter').append('<option value="5">Final Rating</option>')
                    $('#strand_holder').attr('hidden','hidden')
                }

                  reset_sf9_report()
            })

            $(document).on('change','#section',function(){
                grading_sheet_subjects = []
                reset_sf9_report()
                var temp_section = $(this).val()
                var temp_strand = strand.filter(x=>x.sectionid == temp_section)
                $("#strand").empty()
                $('#quarter').val()
                $.each(temp_strand,function(a,b){
                        b.text = b.strandcode
                        b.id = b.strandid
                })
                $("#strand").select2({
                        data: temp_strand,
                        placeholder: "Select a strand",
                })
            })

            $(document).on('change','#quarter, #gradelevel, #section, #strand',function(){
                $('#print_ms').attr('disabled','disabled')
                $('#print_ms_excel').attr('disabled','disabled')
                $('#quarter').val()
                var temp_data = []
                loaddatatable(temp_data)
            })

            var public_quarter;
            var grading_sheet_subjects = []

            $(document).on('click','#filter',function(){

                var valid_input = true

                if($('#gradelevel').val() == ''){

                    valid_input = false;
                    Swal.fire({
                        type: 'info',
                        text: "Please select gradelevel!"
                    });

                }
                else if($('#section').val() == ''){

                    valid_input = false;
                    Swal.fire({
                        type: 'info',
                        text: "Please select section!"
                    });

                }
                else if($('#quarter').val() == ''){

                    valid_input = false;
                    Swal.fire({
                        type: 'info',
                        text: "Please select quarter!"
                    });

                }

                if(valid_input){

                    reset_data_table()
                    var gradelevel = $('#gradelevel').val();
                    var section = $('#section').val();
                    var quarter  = $('#quarter').val(); 
                    var syid  = $('#syid').val(); 
                    var semid = 1
                    if(gradelevel == 14 || gradelevel == 15){
                        var semid = $('#semester').val()
                    }   
                    var strand  = $('#strand').val(); 

                    public_quarter = quarter

                    

                    $.ajax({
                        type:'GET',
                        url:'/posting/grade/getstudents',
                        data:{
                                gradelevel:gradelevel,
                                section:section,
                                quarter:quarter,
                                sy:syid,
                                semid:semid,
                                strand:strand
                        },
                        success:function(data) {

                            students = data
                            student_count = students.filter(x=>x.student != 'SUBJECTS').length


                            if(student_count > 0){
                                $('#grading_sheet_filter').removeAttr('disabled')
                                loaddatatable(students)

                                // if(grading_sheet_subjects.length == 0){
                                    grading_sheet_subjects = data.filter(x=>x.student == 'SUBJECTS')
                                    $('#grading_sheet_subject').empty()
                                    $('#grading_sheet_subject').append('<option value="">SELECT SUBJECT</option>')
                                    $.each(grading_sheet_subjects[0].grades,function(a,b){
                                        if(b.subjid != "" && b.subjid != null){
                                            $('#grading_sheet_subject').append('<option value="'+b.subjid+'"> <span style="color:gray"">'+b.subjdesc+'</span> - '+b.subjtitle+'</option>')
                                        }
                                    })
                                    $("#grading_sheet_subject").select2({
                                        allowClear: true,
                                        placeholder: "Select Subject",
                                    })
                                // }
                                

                                $('#print_ms').removeAttr('disabled')
                                $('#print_gs').removeAttr('disabled')
                                $('#print_ms_excel').removeAttr('disabled')
                            }
                            else{
                                students = []
                                loaddatatable(students)
                                Swal.fire({
                                    type: 'info',
                                    text: "No Enrolled Students!"
                                });
                                $('#print_ms').attr('disabled','disabled')
                                $('#print_gs').attr('disabled','disabled')
                                $('#print_ms_excel').attr('disabled','disabled')
                            }
                        }
                    })
                }
            })

            function reset_data_table(){

                var temp_reset_data = []

                $("#student_list").DataTable({
                    destroy: true,
                    data:temp_reset_data,
                    "scrollX": true,
                    "columnDefs": [
                        {"title":"Student Name","targets":0},
                        {"title":"Q1","targets":1},
                        {"title":"Q2","targets":2},
                        {"title":"Q3","targets":3},
                        {"title":"Q4","targets":4},
                        {"title":"Final Rating","targets":5},
                        {"title":"Remarks","targets":6},
                    ]
                })

            }


            function loaddatatable(data){

                if(data.length == 0){

                    $("#student_list").DataTable({
                        destroy: true,
                        data:data,
                        "scrollX": true,
                        "columnDefs": [
                            {"title":"Student Name","targets":0},
                            {"title":"S1","targets":1},
                            {"title":"S2","targets":2},
                            {"title":"S3","targets":3},
                            {"title":"S4","targets":4},
                            {"title":"S5","targets":5},
                            {"title":"S6","targets":6},
                            {"title":"S7","targets":7},
                            {"title":"S8","targets":8},
                            {"title":"S9","targets":9},
                            {"title":"S10","targets":10},
                            {"title":"S11","targets":11},
                            {"title":"S12","targets":12},
                            {"title":"S13","targets":13},
                            {"title":"S14","targets":14},
                            {"title":"S15","targets":15},
                        ]
                    })
                        
                }
                else{

                    var header = data[0];
                    var temp_levelid = $('#gradelevel').val()
                    if(temp_levelid == 14 || temp_levelid == 15){
                        var temp_strand = $('#strand').val()
                        var data = data.filter(x => x.student != 'SUBJECTS' && x.strand == temp_strand)
                    }else{
                        var data = data.filter(x => x.student != 'SUBJECTS')
                    }

                    $("#student_list").DataTable({
                        destroy: true,
                        data:data,
                        "scrollX": true,
                        columns: [
                                    { "data": "student" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                            ],
                            

                        "columnDefs": [
                            { "title": "STUDENT", 
                                "targets": 0,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    
                                    $(td)[0].innerHTML = rowData.student
                                    
                                } 
                            
                            },
                            { "title": header.grades[0].subjdesc, "targets": 1,
                                'createdCell':  function (td, cellData, rowData, row, col) {

                                    $(td).removeAttr('class')

                                    if(rowData.grades[0].status == 4){

                                        $(td).addClass('bg-info text-center')
                                        $(td).text(rowData.grades[0].qg)

                                    }
                                    else if(rowData.grades[0].status == 1){

                                        $(td).addClass('bg-success text-center')
                                        $(td).text(rowData.grades[0].qg)

                                    }
                                    else if(rowData.grades[0].status == 2){

                                        $(td).addClass('bg-primary text-center')
                                        $(td).text(rowData.grades[0].qg)

                                    }
                                    else if(rowData.grades[0].status == 3){

                                        $(td).addClass('bg-warning text-center')
                                        $(td).text(rowData.grades[0].qg)

                                    }
                                    else if(rowData.grades[0].status == 5){

                                        $(td).addClass('bg-danger text-center')
                                        $(td).text(rowData.grades[0].qg)

                                    }
                                    else if(rowData.grades[0].status == 6){

                                        $(td).addClass('bg-indigo text-center')
                                        $(td).text(rowData.grades[0].qg)

                                    }
                                    else{
                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')
                                    }

                                    $(td).addClass('text-center')
                                } 
                            },
                            { "title": header.grades[1].subjdesc, "targets": 2,
                                'createdCell':  function (td, cellData, rowData, row, col) {


                                    if(rowData.grades[1].status == 4){

                                        $(td).addClass('bg-info text-center')
                                        $(td).text(rowData.grades[1].qg)

                                    }
                                    else if(rowData.grades[1].status == 1){
                                        $(td).addClass('bg-success text-center')
                                        $(td).text(rowData.grades[1].qg)

                                    }
                                    else if(rowData.grades[1].status == 2){
                                        $(td).addClass('bg-primary text-center')
                                        $(td).text(rowData.grades[1].qg)

                                    }
                                    else if(rowData.grades[1].status == 3){
                                        $(td).addClass('bg-warning text-center')
                                        $(td).text(rowData.grades[1].qg)

                                    }
                                    else if(rowData.grades[1].status == 5){
                                        $(td).addClass('bg-danger text-center')
                                        $(td).text(rowData.grades[1].qg)

                                    }
                                    else{
                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')
                                    }

                                    $(td).addClass('text-center')
                                } 
                            },
                            { "title": header.grades[2].subjdesc, "targets": 3,
                                'createdCell':  function (td, cellData, rowData, row, col) {

                                    if(rowData.grades[2].status == 4){
                                        $(td).addClass('bg-info text-center')
                                        $(td).text(rowData.grades[2].qg)

                                    }
                                    else if(rowData.grades[2].status == 1){
                                        $(td).addClass('bg-success text-center')
                                        $(td).text(rowData.grades[2].qg)

                                    }
                                    else if(rowData.grades[2].status == 2){
                                        $(td).addClass('bg-primary text-center')
                                        $(td).text(rowData.grades[2].qg)

                                    }
                                    else if(rowData.grades[2].status == 3){
                                        $(td).addClass('bg-warning text-center')
                                        $(td).text(rowData.grades[2].qg)

                                    } 
                                    else if(rowData.grades[2].status == 5){
                                        $(td).addClass('bg-danger text-center')
                                        $(td).text(rowData.grades[2].qg)

                                    }
                                    else{
                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')
                                    }

                                    $(td).addClass('text-center')
                                } 
                            },
                            { "title": header.grades[3].subjdesc, "targets": 4,
                                'createdCell':  function (td, cellData, rowData, row, col) {

                                    if(rowData.grades[3].status == 4){
                                        $(td).addClass('bg-info text-center')
                                        $(td).text(rowData.grades[3].qg)
                                    }
                                    else if(rowData.grades[3].status == 1){
                                        $(td).addClass('bg-success text-center')
                                        $(td).text(rowData.grades[3].qg)
                                    }
                                    else if(rowData.grades[3].status == 2){
                                        $(td).addClass('bg-primary text-center')
                                        $(td).text(rowData.grades[3].qg)
                                    }
                                    else if(rowData.grades[3].status == 3){
                                        $(td).addClass('bg-warning text-center')
                                        $(td).text(rowData.grades[3].qg)
                                    }
                                    else if(rowData.grades[3].status == 5){
                                        $(td).addClass('bg-danger text-center')
                                        $(td).text(rowData.grades[3].qg)
                                    }
                                    else{
                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')
                                    }
                                    $(td).addClass('text-center')
                                } 
                            },
                            { "title": header.grades[4].subjdesc, "targets": 5,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    if(rowData.grades[4].status == 4){
                                        $(td).addClass('bg-info text-center')
                                        $(td).text(rowData.grades[4].qg)
                                    }
                                    else if(rowData.grades[4].status == 1){
                                        $(td).addClass('bg-success text-center')
                                        $(td).text(rowData.grades[4].qg)
                                    }
                                    else if(rowData.grades[4].status == 2){
                                        $(td).addClass('bg-primary text-center')
                                        $(td).text(rowData.grades[4].qg)
                                    }
                                    else if(rowData.grades[4].status == 3){
                                        $(td).addClass('bg-warning text-center')
                                        $(td).text(rowData.grades[4].qg)
                                    }
                                    else if(rowData.grades[4].status == 5){
                                        $(td).addClass('bg-danger text-center')
                                        $(td).text(rowData.grades[4].qg)
                                    }
                                    else{
                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')
                                    }


                                    $(td).addClass('text-center')
                                } 
                            },
                            { "title": header.grades[5].subjdesc, "targets": 6,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    if(rowData.grades[5].status == 4){
                                        $(td).addClass('bg-info text-center')
                                        $(td).text(rowData.grades[5].qg)
                                    }
                                    else if(rowData.grades[5].status == 1){
                                        $(td).addClass('bg-success text-center')
                                        $(td).text(rowData.grades[5].qg)
                                    }
                                    else if(rowData.grades[5].status == 2){
                                        $(td).addClass('bg-primary text-center')
                                        $(td).text(rowData.grades[5].qg)
                                    }
                                    else if(rowData.grades[5].status == 3){
                                        $(td).addClass('bg-warning text-center')
                                        $(td).text(rowData.grades[5].qg)
                                    }
                                    else if(rowData.grades[5].status == 5){
                                        $(td).addClass('bg-danger text-center')
                                        $(td).text(rowData.grades[5].qg)
                                    }
                                    else{
                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')
                                    }
                                    $(td).addClass('text-center')
                                } 
                            },
                            { "title": header.grades[6].subjdesc, "targets": 7,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    if(rowData.grades[6].status == 4){
                                        $(td).addClass('bg-info text-center')
                                        $(td).text(rowData.grades[6].qg)
                                    }
                                    else if(rowData.grades[6].status == 1){
                                        $(td).addClass('bg-success text-center')
                                        $(td).text(rowData.grades[6].qg)
                                    }
                                    else if(rowData.grades[6].status == 2){
                                        $(td).addClass('bg-primary text-center')
                                        $(td).text(rowData.grades[6].qg)
                                    }
                                    else if(rowData.grades[6].status == 3){
                                        $(td).addClass('bg-warning text-center')
                                        $(td).text(rowData.grades[6].qg)
                                    }
                                    else if(rowData.grades[6].status == 5){
                                        $(td).addClass('bg-danger text-center')
                                        $(td).text(rowData.grades[6].qg)
                                    }
                                    else{

                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')
                                    }
                                    $(td).addClass('text-center')
                                } 
                            },
                            { "title": header.grades[7].subjdesc, "targets": 8,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    if(rowData.grades[7].status == 4){
                                        $(td).addClass('bg-info text-center')
                                        $(td).text(rowData.grades[7].qg)
                                    }
                                    else if(rowData.grades[7].status == 1){
                                        $(td).addClass('bg-success text-center')
                                        $(td).text(rowData.grades[7].qg)
                                    }
                                    else if(rowData.grades[7].status == 2){
                                        $(td).addClass('bg-primary text-center')
                                        $(td).text(rowData.grades[7].qg)
                                    }
                                    else if(rowData.grades[7].status == 3){
                                        $(td).addClass('bg-warning text-center')
                                        $(td).text(rowData.grades[7].qg)
                                    }
                                    else if(rowData.grades[7].status == 5){
                                        $(td).addClass('bg-danger text-center')
                                        $(td).text(rowData.grades[7].qg)
                                    }
                                    else{
                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')
                                    }
                                    $(td).addClass('text-center')
                                } 
                            },
                            { "title": header.grades[8].subjdesc, "targets": 9,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    if(rowData.grades[8].status == 4){
                                        $(td).addClass('bg-info text-center')
                                        $(td).text(rowData.grades[8].qg)
                                    }
                                    else if(rowData.grades[8].status == 1){
                                        $(td).addClass('bg-success text-center')
                                        $(td).text(rowData.grades[8].qg)
                                    }
                                    else if(rowData.grades[8].status == 2){
                                        $(td).addClass('bg-primary text-center')
                                        $(td).text(rowData.grades[8].qg)
                                    }
                                    else if(rowData.grades[8].status == 3){
                                        $(td).addClass('bg-warning text-center')
                                        $(td).text(rowData.grades[8].qg)
                                    }
                                    else if(rowData.grades[8].status == 5){
                                        $(td).addClass('bg-danger text-center')
                                        $(td).text(rowData.grades[8].qg)
                                    }
                                    else{
                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')
                                    }
                                    $(td).addClass('text-center')
                                } 
                            },
                            { "title": header.grades[9].subjdesc, "targets": 10,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    if(rowData.grades[9].status == 4){
                                        $(td).addClass('bg-info text-center')
                                        $(td).text(rowData.grades[9].qg)
                                    }
                                    else if(rowData.grades[9].status == 1){
                                        $(td).addClass('bg-success text-center')
                                        $(td).text(rowData.grades[9].qg)
                                    }
                                    else if(rowData.grades[9].status == 2){
                                        $(td).addClass('bg-primary text-center')
                                        $(td).text(rowData.grades[9].qg)
                                    }
                                    else if(rowData.grades[9].status == 3){
                                        $(td).addClass('bg-warning text-center')
                                        $(td).text(rowData.grades[9].qg)
                                    }
                                    else if(rowData.grades[9].status == 5){
                                        $(td).addClass('bg-danger text-center')
                                        $(td).text(rowData.grades[9].qg)
                                    }
                                    else{
                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')
                                    }


                                    $(td).addClass('text-center')
                                } 
                            },
                            { "title": header.grades[10].subjdesc, "targets": 11,
                                'createdCell':  function (td, cellData, rowData, row, col) {

                                    if(rowData.grades[10].status == 4){
                                        $(td).addClass('bg-info text-center')
                                        $(td).text(rowData.grades[10].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[10].qg

                                    }
                                    else if(rowData.grades[10].status == 1){
                                        $(td).addClass('bg-success text-center')
                                        $(td).text(rowData.grades[10].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[10].qg

                                    }
                                    else if(rowData.grades[10].status == 2){
                                        $(td).addClass('bg-primary text-center')
                                        $(td).text(rowData.grades[10].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[10].qg

                                    }
                                    else if(rowData.grades[10].status == 3){
                                        $(td).addClass('bg-warning text-center')
                                        $(td).text(rowData.grades[10].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[10].qg

                                    }
                                    else if(rowData.grades[10].status == 5){
                                        $(td).addClass('bg-danger text-center')
                                        $(td).text(rowData.grades[10].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[10].qg

                                    }
                                    else{

                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')
                                    }


                                    $(td).addClass('text-center')

                                } 
                            },
                            { "title": header.grades[11].subjdesc, "targets": 12,
                                'createdCell':  function (td, cellData, rowData, row, col) {

                                    if(rowData.grades[11].status == 4){
                                        $(td).addClass('bg-info text-center')
                                        $(td).text(rowData.grades[11].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[11].qg

                                    }
                                    else if(rowData.grades[11].status == 1){
                                        $(td).addClass('bg-success text-center')
                                        $(td).text(rowData.grades[11].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[11].qg

                                    }
                                    else if(rowData.grades[11].status == 2){
                                        $(td).addClass('bg-primary text-center')
                                        $(td).text(rowData.grades[11].qg)
                                    }
                                    else if(rowData.grades[11].status == 3){
                                        $(td).addClass('bg-warning text-center')
                                        $(td).text(rowData.grades[11].qg)
                                    }
                                    else if(rowData.grades[11].status == 5){
                                        $(td).addClass('bg-danger text-center')
                                        $(td).text(rowData.grades[11].qg)
                                    }
                                    else{

                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')
                                    }


                                    $(td).addClass('text-center')

                                } 
                            },
                            { "title": header.grades[12].subjdesc, "targets": 13,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    

                                    if(rowData.grades[12].status == 4){
                                        $(td).addClass('bg-info text-center')
                                        $(td).text(rowData.grades[12].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[12].qg

                                    }
                                    else if(rowData.grades[12].status == 1){
                                        $(td).addClass('bg-success text-center')
                                        $(td).text(rowData.grades[12].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[12].qg

                                    }
                                    else if(rowData.grades[12].status == 2){
                                        $(td).addClass('bg-primary text-center')
                                        $(td).text(rowData.grades[12].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[12].qg

                                    }
                                    else if(rowData.grades[12].status == 3){
                                        $(td).addClass('bg-warning text-center')
                                        $(td).text(rowData.grades[12].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[12].qg

                                    }
                                    else if(rowData.grades[12].status == 5){
                                        $(td).addClass('bg-danger text-center')
                                        $(td).text(rowData.grades[12].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[12].qg

                                    }
                                    else{

                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')
                                    }


                                    $(td).addClass('text-center')
                                } 
                            },
                            { "title": header.grades[13].subjdesc, "targets": 14,
                                'createdCell':  function (td, cellData, rowData, row, col) {

                                    if(rowData.grades[13].status == 4){
                                        $(td).addClass('bg-info text-center')
                                        $(td).text(rowData.grades[13].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[13].qg

                                    }
                                    else if(rowData.grades[13].status == 1){
                                        $(td).addClass('bg-success text-center')
                                        $(td).text(rowData.grades[13].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[13].qg

                                    }
                                    else if(rowData.grades[13].status == 2){
                                        $(td).addClass('bg-primary text-center')
                                        $(td).text(rowData.grades[13].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[13].qg

                                    }
                                    else if(rowData.grades[13].status == 3){
                                        $(td).addClass('bg-warning text-center')
                                        $(td).text(rowData.grades[13].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[13].qg

                                    }
                                    else if(rowData.grades[13].status == 5){
                                        $(td).addClass('bg-danger text-center')
                                        $(td).text(rowData.grades[13].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[13].qg

                                    }
                                    else{

                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')
                                    }


                                    $(td).addClass('text-center')
                                } 
                            },
                            { "title": header.grades[14].subjdesc, "targets": 15,
                                'createdCell':  function (td, cellData, rowData, row, col) {

                                    if(rowData.grades[14].status == 4){
                                        $(td).addClass('bg-info text-center')
                                        $(td).text(rowData.grades[14].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-info">P</span> '+rowData.grades[14].qg

                                    }
                                    else if(rowData.grades[14].status == 1){
                                        $(td).addClass('bg-success text-center')
                                        $(td).text(rowData.grades[14].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-success">S</span> '+rowData.grades[14].qg

                                    }
                                    else if(rowData.grades[14].status == 2){
                                        $(td).addClass('bg-primary text-center')
                                        $(td).text(rowData.grades[14].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-primary">A</span> '+rowData.grades[14].qg

                                    }
                                    else if(rowData.grades[14].status == 3){
                                        $(td).addClass('bg-warning text-center')
                                        $(td).text(rowData.grades[14].qg)
                                        // $(td)[0].innerHTML = '<span class="badge badge-warning">PG</span> '+rowData.grades[14].qg

                                    }
                                    else if(rowData.grades[14].status == 5){
                                        $(td).addClass('bg-danger text-center')
                                        $(td).text(rowData.grades[14].qg)
                                        $(td)[0].innerHTML = '<span class="badge badge-danger">UP</span> '+rowData.grades[14].qg

                                    }
                                    else{

                                        $(td).text(null)
                                        $(td).addClass('bg-secondary text-center')

                                    }


                                    $(td).addClass('text-center')
                                } 
                            },
                            
                        ]
                        
                });

                }

        
            }


            function default_subj_option(){

                $('#subj_unpost').removeAttr('disabled')
                $('#subj_approve').removeAttr('disabled')
                $('#subj_pending').removeAttr('disabled')
                $('#subj_post').removeAttr('disabled')

            }

            function check_subj_option(){
                var checkStatus = students.filter(x=>x.student == 'SUBJECTS')[0].grades.filter(x=>x.gsdid == selected_subj)[0].status
                if(checkStatus == 5){
                    $('#subj_unpost').attr('disabled','disabled')
                    $('#subj_approve').attr('disabled','disabled')
                }
                else if(checkStatus == 4){
                    $('#subj_post').attr('disabled','disabled')
                    $('#subj_approve').attr('disabled','disabled')
                    $('#subj_pending').attr('disabled','disabled')
                }
                else if(checkStatus == 1){
                    $('#subj_unpost').attr('disabled','disabled')
                }
                else if(checkStatus == 2){
                    $('#subj_approve').attr('disabled','disabled')
                    $('#subj_unpost').attr('disabled','disabled')
                }
                else if(checkStatus == 3){
                    $('#subj_pending').attr('disabled','disabled')
                    $('#subj_unpost').attr('disabled','disabled')
                    $('#subj_post').attr('disabled','disabled')
                }
            }

            var selected_subj;

            function check_item_count_by_subject(status){

                var filter_subject  = students.filter(x => x.student == 'SUBJECTS')[0].grades
                var gradeid = filter_subject.findIndex(x => x.gsdid == selected_subj)
                var new_status = status

                students.filter(function(x){
                    if(x.grades[gradeid].gdid != ""){
                        if(x.student != 'SUBJECT' && x.grades[gradeid].status != new_status){
                            temp_item_count += 1;
                        }else if(x.student == 'SUBJECTS'){
                            temp_item_count += 1;
                        }

                    }
                })

            }
            
            var temp_percentage
            var temp_item_count


            $(document).on('click','.view_info',function(){

                $('#grade_info').modal()

                select_student = $(this).attr('data-studid');

                var data = students.filter(x => x.studid == select_student)

                $('#student_name_modal').val(data[0].student)

                load_grade_detail(data)

            })

            function load_grade_detail(data){
                $('#grade_holder').empty()
                var arrayID = students.findIndex(x => x.studid == data[0].studid)
                $.each(data[0].grades,function(a,b){
                  
                    var bg = ''
                    if(selected_subj == b.gdid){
                        bg = 'bg-secondary'
                    }

                    if(b.subjid != ""){
                        var status_string = '';
                        var this_class = 'stud_subj_tr'
                        var qg = ''
                        if(b.status == 1){
                            var status_string = '<span class="badge badge-success d-block">SUBMITTED</span>';
                        }
                        else  if(b.status == 2){
                            var status_string = '<span class="badge badge-primary d-block">APPROVED</span>';
                        }
                        else  if(b.status == 3){
                            var status_string = '<span class="badge badge-warning d-block">PENDING</span>';
                        }
                        else  if(b.status == 4){
                            var status_string = '<span class="badge badge-info d-block">POSTED</span>';
                        }
                        else  if(b.status == 5){
                            var status_string = '<span class="badge badge-danger d-block">UNPOSTED</span>';
                        }
                        else  if(b.status == 6){
                            var status_string = '<span class="badge bg-indigo d-block">CONFLICT</span>';
                        }else{
                            var status_string = '<span class="badge badge-secondary d-block">NOT SUBMITTED</span>';
                            this_class = ''
                        }

                        if(b.status != 0 ){
                            qg = b.qg
                        }

                        $('#grade_holder').append('<tr class="'+this_class+' '+bg+'" data-id="'+b.gdid+'" data-studid="'+data[0].studid+'" data-index="'+a+'" data-studindex="'+arrayID+'"  data-tid="'+b.teacherid+'"><td>'+b.subjdesc+'</td><td class="text-center">'+qg+'</td><td class="text-center">'+status_string+'</td></tr>')
                    }
                })
            }

            var temp_proccess_count = 0;
            var temp_item_count = 0;
            var selected_subjects 
            var stud_index
            var gdid
            var data_index
            var stud_index
            var teacherid

            function default_stud_subj_option(){

                $('#stud_subj_unpost').removeAttr('disabled')
                $('#stud_subj_pending').removeAttr('disabled')
                $('#stud_subj_post').removeAttr('disabled')

            }
          

            function check_stud_subj_option(){
                
                var checkStatus = students.filter(x=>x.studid == studid)[0].grades.filter(x=>x.gdid == gdid)[0].status

                if(checkStatus == 5){
                    $('#stud_subj_unpost').attr('disabled','disabled')
                }
                else if(checkStatus == 4){
                    $('#stud_subj_post').attr('disabled','disabled')
                    $('#stud_subj_pending').attr('disabled','disabled')
                }
                else if(checkStatus == 1){
                    $('#stud_subj_unpost').attr('disabled','disabled')
                }
                else if(checkStatus == 2){
                    $('#stud_subj_unpost').attr('disabled','disabled')
                }
                else if(checkStatus == 3){
                    $('#stud_subj_pending').attr('disabled','disabled')
                    $('#stud_subj_unpost').attr('disabled','disabled')
                    $('#stud_subj_post').attr('disabled','disabled')
                }
            }

        })
    </script>

    <script>
        $(document).ready(function(){

            var select_subject;
            var select_gl;
            var all_data = [];
            var all_data_grading_sheet = [];


            $(document).on('change','#grading_sheet_subject',function(){
                all_data = [];
                loaddatatable_gradingsheet(all_data)
            })

            $(document).on('change','#section , #gradelevel, #syid , #semester , #strand',function(){

                $('#grading_sheet_subject').empty()
                $("#grading_sheet_subject").select2({
                    allowClear: true,
                    placeholder: "Select Subject",
                })
                all_data = [];
                loaddatatable_gradingsheet(all_data)
            })

            $(document).on('click','#grading_sheet_filter',function(){

                var valid_input = true
                select_subject = $('#grading_sheet_subject').val()
               
                if($('#grading_sheet_subject').val() == ""){
                    Swal.fire({
                        type: 'info',
                        text: "Please select a subject!"
                    });
                    valid_input = false
                    return false;
                }
              
                select_gl = $('#gradelevel').val()

                if(valid_input){

                    var gradelevel = $('#gradelevel').val()
                    var section = $('#section').val()
                    var syid = $('#syid').val()
                    
                    var strand  = $('#strand').val(); 
                    var semid = 1
                    if(gradelevel == 14 || gradelevel == 15){
                        var semid = $('#semester').val()
                    }   

                    if(all_data_grading_sheet.length == 0){
                        $.ajax({
                            type:'GET',
                            url:'/posting/grade/getstudents',
                            data:{
                                    gradelevel:gradelevel,
                                    section:section,
                                    sy:syid,
                                    semid:semid,
                                    strand:strand
                            },
                            success:function(data) {
                                all_data_grading_sheet = data
                                loaddatatable_gradingsheet(all_data_grading_sheet)
                            }
                        })
                    }else{
                        loaddatatable_gradingsheet(all_data_grading_sheet)
                    }
                }
            })

            function loaddatatable_gradingsheet(data){
              
                if(data.length == 0){
                    $('#print_gs').attr('disabled','disabled')
                    $("#grading_sheet_table_list").DataTable({
                        destroy: true,
                        data:data,
                        "scrollX": true,
                        "columnDefs": [
                            {"title":"Student Name","targets":0},
                            {"title":"Q1","targets":1},
                            {"title":"Q2","targets":2},
                            {"title":"Q3","targets":3},
                            {"title":"Q4","targets":4},
                            {"title":"Final Rating","targets":5},
                            {"title":"Remarks","targets":6},
                        ]
                    })
                        
                }
                else{

                    var header = data[0];
                    var temp_levelid = $('#gradelevel').val()
                    if(temp_levelid == 14 || temp_levelid == 15){
                        var temp_strand = $('#strand').val()
                        var data = data.filter(x => x.student != 'SUBJECTS' && x.strand == temp_strand)
                    }else{
                        var data = data.filter(x => x.student != 'SUBJECTS')
                    }
                    $('#print_gs').removeAttr('disabled')

                    $("#grading_sheet_table_list").DataTable({
                        destroy: true,
                        data:data,
                        "scrollX": true,
                        columns: [
                                    { "data": "student" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                            ],
                        "columnDefs": [
                            { "title": "STUDENT", 
                                "targets": 0,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td)[0].innerHTML = rowData.student
                                    
                                } 
                            
                            },
                            { "title": "Q1", 
                                "targets": 1,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                   $(td).addClass('text-center')
                                   var qg = cellData.grades.filter(x=>x.subjid == select_subject)
                                   if(select_gl == 14 || select_gl == 15){
                                        if($('#semester').val() == 2){
                                            $(td).attr('hidden','hidden')
                                        }
                                    }else{
                                        $(td).removeAttr('hidden')
                                    }
                                   if(qg.length != 0){
                                        $(td).text(qg[0].q1)
                                   }else{
                                        $(td).text(null)
                                   }
                         
                                } 
                            
                            },
                            { "title": "Q2", 
                                "targets": 2,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td).addClass('text-center')
                                    var qg = cellData.grades.filter(x=>x.subjid == select_subject)
                                    if(select_gl == 14 || select_gl == 15){
                                        if($('#semester').val() == 2){
                                            $(td).attr('hidden','hidden')
                                        }
                                    }else{
                                        $(td).removeAttr('hidden')
                                    }
                                    if(qg.length != 0){
                                            $(td).text(qg[0].q2)
                                    }else{
                                            $(td).text(null)
                                    }
                                } 
                            
                            },
                            { "title": "Q3", 
                                "targets": 3,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td).addClass('text-center')
                                    var qg = cellData.grades.filter(x=>x.subjid == select_subject)
                                    if(select_gl == 14 || select_gl == 15){
                                        if($('#semester').val() == 1){
                                            $(td).attr('hidden','hidden')
                                        }
                                    }else{
                                        $(td).removeAttr('hidden')
                                    }
                                    if(qg.length != 0){
                                            $(td).text(qg[0].q3)
                                    }else{
                                            $(td).text(null)
                                    }
                                } 
                            
                            },
                            { "title": "Q4", 
                                "targets": 4,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td).addClass('text-center')
                                    if(select_gl == 14 || select_gl == 15){
                                        if($('#semester').val() == 1){
                                            $(td).attr('hidden','hidden')
                                        }
                                    }
                                    else{
                                        $(td).removeAttr('hidden')
                                    }
                                    var qg = cellData.grades.filter(x=>x.subjid == select_subject)
                                    if(qg.length != 0){
                                            $(td).text(qg[0].q4)
                                    }else{
                                            $(td).text(null)
                                    }
                                } 
                            },
                            { "title": "FINAL GRADE", 
                                "targets": 5,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td).addClass('text-center')
                                    var qg = cellData.grades.filter(x=>x.subjid == select_subject)
                                    $(td).text(qg[0].finalrating)
                                } 
                            
                            },
                            { "title": "REMARK", 
                                "targets": 6,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td).addClass('text-center')
                                    var qg = cellData.grades.filter(x=>x.subjid == select_subject)
                                    $(td).text(qg[0].actiontaken)
                                } 
                            },

                        ]
                    });

                    if(select_gl == 14 || select_gl == 15){
                        if($('#semester').val() == 1){
                            $('.gs_q3').attr('hidden','hidden')
                            $('.gs_q4').attr('hidden','hidden')
                        }else{
                            $('.gs_q1').attr('hidden','hidden')
                            $('.gs_q2').attr('hidden','hidden')
                        }
                    }
                    else{
                        $('.gs_q3').removeAttr('hidden')
                        $('.gs_q4').removeAttr('hidden')
                        $('.gs_q1').removeAttr('hidden')
                        $('.gs_q2').removeAttr('hidden')
                    }
                }
            }
        })


    </script>

    
@endsection


