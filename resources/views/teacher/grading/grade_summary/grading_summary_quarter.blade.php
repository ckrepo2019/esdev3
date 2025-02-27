@extends('teacher.layouts.app')

@section('headerjavascript')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <style>
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
                        <h1>Quarterly Grades</h1>
                    </div>
                    <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Quarterly Grades</li>
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
                        <div class="card-header bg-primary p-1">
                            <div class="row">
                            </div>
                        </div>
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
                                    <div class="form-group mb-0" id="strand_holder" hidden>
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
                        <div class="card-header p-1 bg-primary">
                        </div>
                        <div class="card-body">
                              <div class="row">
                                    <div class="col-md-3"><h5 class="mb-0 pt-1">Summary of Quarterly Grades</h5></div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-4">
                                          <select name="grading_sheet_subject" id="grading_sheet_subject" class="form-control select2">
                                          <option selected value="" >SELECT SUBJECT</option>
                                          </select>
                                    </div>
                                    <div class="col-md-4">
                                          <button class="btn btn-primary btn-block btn-sm" id="grading_sheet_filter" disabled>VIEW GRADING SHEET</button>
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
                                    <i class="fas fa-print"></i> PRINT SUMMARY OF QUARTERLY GRADES
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
            var all_subject = [];
            var all_data = []

            
            get_advisory()
            $('.select2').select2()

            function get_advisory(){
                  var syid = $('#syid').val()
                  var semid = $('#semester').val()
                  $.ajax({
                        type:'GET',
                        url:'/teacher/section/all',
                        data:{
                                syid:syid,
                                semid:semid,
                        },
                        success:function(data) {
                              sections = data;
                              all_subject = data;
                              var arrayUniqueByKey = [...new Map(sections.map(item =>
                              [item['levelid'], item])).values()];
                              $.each(arrayUniqueByKey,function(a,b){
                                    $('#gradelevel').append('<option value="'+b.levelid+'">'+b.levelname+'</option>')
                              })
                        }
                    })
            }

            var strand = @json($subj_strand);
            $(document).on('change','#section',function(){

                if($(this).val() == ""){
                    $('#grading_sheet_subject').val("")
                    $('#grading_sheet_subject').empty()
                    $('#strand').empty()
                    $('#grading_sheet_subject').append('<option value="">SELECT SUBJECT</option>')
                    $("#grading_sheet_subject").select2({
                        allowClear: true,
                        placeholder: "Select Subject",
                    })
                    $('#grading_sheet_filter').attr('disabled','disabled')
                    data = []
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
                    $('#print_gs').attr('disabled','disabled')
                    return false;
                }

                var semid  = $('#semester').val(); 
                var temp_section = $(this).val()
                var gradelevel = $('#gradelevel').val();
                if(gradelevel == 14 || gradelevel == 15){
                    var temp_strand = strand.filter(x=>x.sectionid == temp_section)
                    $("#strand").empty()
                    $.each(temp_strand,function(a,b){
                            b.text = b.strandcode
                            b.id = b.strandid
                    })
                    $("#strand").select2({
                            data: temp_strand,
                            placeholder: "Select a strand",
                    })
                    get_subjects()
                }else{
                    // $('#grading_sheet_filter').removeAttr('disabled')
                    var temp_subjects = all_subject.filter(x=>x.sectionid == $(this).val())
                    $('#grading_sheet_subject').empty()
                    $('#grading_sheet_subject').append('<option value="">SELECT SUBJECT</option>')
                    $.each(temp_subjects,function(a,b){
                            if(b.subjid != "" && b.subjid != null){
                                $('#grading_sheet_subject').append('<option value="'+b.subjid+'"> <span style="color:gray"">'+b.subjcode+'</span> - '+b.subjdesc+'</option>')
                            }
                    })
                }

                $("#grading_sheet_subject").select2({
                    allowClear: true,
                    placeholder: "Select Subject",
                })
            })



            $(document).on('change','#strand',function(){
                var strand  = $('#strand').val(); 
                var semid = 1
                var gradelevel = $('#gradelevel').val()
                if(gradelevel == 14 || gradelevel == 15){
                    var semid = $('#semester').val()
                }  
                $('#grading_sheet_subject').empty()
                $('#grading_sheet_subject').append('<option value="">SELECT SUBJECT</option>')
                student_count = all_data.filter(x=>x.student != 'SUBJECTS').length
                if(student_count > 0){
                    if(gradelevel == 14 || gradelevel == 15){
                        var grading_sheet_subjects = all_data.filter(x=>x.student == 'SUBJECTS' )[0].grades.filter(x=>x.subjid != "" && x.semid == semid && x.strandid == strand)
                    }
                    $.each(grading_sheet_subjects,function(a,b){
                        $('#grading_sheet_subject').append('<option value="'+b.subjid+'"> <span style="color:gray"">'+b.subjdesc+'</span> - '+b.subjtitle+'</option>')
                    })
                }
            })

            function get_subjects(){

                var gradelevel = $('#gradelevel').val()
                var section = $('#section').val()
                var syid = $('#syid').val()
                var semid = 1
                if(gradelevel == 14 || gradelevel == 15){
                    var semid = $('#semester').val()
                }  
                var subjid = $('#grading_sheet_subject').val()
                var strand  = $('#strand').val(); 


                $.ajax({
                    type:'GET',
                    url:'/posting/grade/getstudents',
                    data:{
                            gradelevel:gradelevel,
                            section:section,
                            sy:syid,
                            semid:semid,
                            subjid:subjid,
                            status:0,
                    },
                    success:function(data) {
                        all_data = data
                        student_count = all_data.filter(x=>x.student != 'SUBJECTS').length
                        $('#grading_sheet_subject').empty()
                        $('#grading_sheet_subject').append('<option value="">SELECT SUBJECT</option>')
                        if(student_count > 0){
                            $('#grading_sheet_filter').removeAttr('disabled')
                            if(gradelevel == 14 || gradelevel == 15){
                                var grading_sheet_subjects = data.filter(x=>x.student == 'SUBJECTS' )[0].grades.filter(x=>x.subjid != "" && x.semid == semid && x.strandid == strand)
                            }else{
                                var grading_sheet_subjects = data.filter(x=>x.student == 'SUBJECTS' )[0].grades.filter(x=>x.subjid != "")
                            }
                            $.each(grading_sheet_subjects,function(a,b){
                                $('#grading_sheet_subject').append('<option value="'+b.subjid+'"> <span style="color:gray"">'+b.subjdesc+'</span> - '+b.subjtitle+'</option>')
                            })
                        }else{
                            $('#grading_sheet_filter').attr('disabled','disabled')
                        }
                    }
                })

            }




            $(document).on('change','#semester , #syid',function(){

                  $('#gradelevel').empty()
                  $('#gradelevel').append('<option value="">SELECT GRADE LEVEL</option>')

                  $('#section').empty()
                  $('#section').append('<option value="">SELECT SECTION</option>')

                  $('#sf9_grade_table').empty();

                  $('#sf9_student_name').empty();
                  $('#sf9_student_name').append('<option value="">SELECT STUDENT</option>')
                  $('#grading_sheet_subject').empty();
                  $('#grading_sheet_subject').append('<option value="">SELECT SUBJECT</option>')
                  get_advisory()

                  $('#print_ms').attr('disabled','disabled')
                  $('#print_gs').attr('disabled','disabled')

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
                var strand  = $('#strand').val(); 
                var subjid  = $('#grading_sheet_subject').val(); 
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
                    window.open("/grades/report/gradingsheet/bysubject?gradelevel="+gradelevel+"&section="+section+"&quarter="+quarter+"&sy="+syid+"&semid="+semid+"&subjid="+subjid+"&status=1&strand="+strand);
                }
            })

            $(document).on('change','#gradelevel',function(){

                $('#section').empty()

                var temp_sy = $('#syid').val();
                var temp_sem = $('#semester').val();
                var gradelevel = $('#gradelevel').val();

                selectedGradeLevel = $(this).val()

                if(gradelevel == 14 || gradelevel == 15){
                    var temp_sections = sections.filter(x=>x.semid == temp_sem)
                    $('#strand_holder').removeAttr('hidden')
                }else{
                    var temp_sections = sections.filter(x=>x.levelid == gradelevel)
                    $('#strand_holder').attr('hidden','hidden')
                }
                
                var temp_sections = [...new Map(temp_sections.map(item =>
                                    [item['id'], item])).values()];

               

                $('#section').append('<option value="">Select Section</option>')
                $.each(temp_sections,function(a,b){
                  if(b.levelid == selectedGradeLevel){
                        $('#section').append('<option value="'+b.sectionid+'">'+b.sectionname+'</option>')
                  }
                })

                  if($(this).val() == 14 || $(this).val() == 15){
                        $('#quarter option').each(function(a,b){
                              if($(b).attr('value') == 3 || $(b).attr('value') == 4){
                                    $(b).attr('disabled','disabled')
                              }
                        })
                  }

                $("#section").select2({
                    allowClear: true,
                    placeholder: "Select Section",
                })

            })

        })
    </script>

    <script>
        $(document).ready(function(){

            var select_subject;
            var select_gl;
            var all_data = [];

            $(document).on('change','#grading_sheet_subject',function(){
                if($(this).val() == ""){
                    $('#grading_sheet_filter').attr('disabled','disabled')
                    $('#print_gs').attr('disabled','disabled')
                    temp_data = []
                    loaddatatable(temp_data)
                }else{
                    temp_data = []
                    loaddatatable(temp_data)
                    $('#grading_sheet_filter').removeAttr('disabled')
                    $('#print_gs').attr('disabled','disabled')
                }
            })

            $(document).on('click','#grading_sheet_filter',function(){

                var valid_input = true
                $('#print_gs').removeAttr('disabled','disabled')

                select_subject = $('#grading_sheet_subject').val()
                select_gl = $('#gradelevel').val()

                if(valid_input){
                    var gradelevel = $('#gradelevel').val()
                    var section = $('#section').val()
                    var syid = $('#syid').val()
                    var semid = 1
                    if(gradelevel == 14 || gradelevel == 15){
                        var semid = $('#semester').val()
                    }  
                    var subjid = $('#grading_sheet_subject').val()
                    var strand  = $('#strand').val(); 
                    $.ajax({
                        type:'GET',
                        url:'/posting/grade/getstudents',
                        data:{
                                gradelevel:gradelevel,
                                section:section,
                                sy:syid,
                                semid:semid,
                                subjid:subjid,
                                status:0,
                                strand:strand
                        },
                        success:function(data) {
                            all_data = data
                            student_count = all_data.filter(x=>x.student != 'SUBJECTS').length
                            if(student_count > 0){
                                loaddatatable(all_data)
                            }
                            else{
                                all_data = []
                                loaddatatable(all_data)
                                Swal.fire({
                                    type: 'info',
                                    text: "No Enrolled Students!"
                                });
                            }
                        }
                    })
                    
                }
            })

            function loaddatatable(data){
                var header = data[0];
               
                    var temp_levelid = $('#gradelevel').val()
                    if(temp_levelid == 14 || temp_levelid == 15){
                        var temp_strand = $('#strand').val()
                        var data = data.filter(x => x.student != 'SUBJECTS' && x.strand == temp_strand)
                    }else{
                        var data = data.filter(x => x.student != 'SUBJECTS')
                    }


                if(data.length == 0){

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
                                    if(qg.length > 0 ){
                                        $(td).text(qg[0].finalrating)
                                    }else{
                                        $(td).text(null)
                                    }
                                   
                                } 
                            
                            },
                            { "title": "REMARK", 
                                "targets": 6,
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td).addClass('text-center')
                                    var qg = cellData.grades.filter(x=>x.subjid == select_subject)
                                    var qg = cellData.grades.filter(x=>x.subjid == select_subject)
                                    if(qg.length > 0 ){
                                        $(td).text(qg[0].actiontaken)
                                    }else{
                                        $(td).text(null)
                                    }
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


