@php
    $refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->where('deleted',0)->select('refid')->first();
  
    
    if(Session::get('currentPortal') == 17){
        $teacherid = null;
    }else{
        $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->select('id')->first()->id;
    }
    
    if(Session::get('currentPortal') == 3){
        $xtend = 'registrar.layouts.app';
        $acadprogid = DB::table('teacheracadprog')
                        ->where('teacherid',$teacherid)
                        ->select('acadprogid')
                        ->where('deleted',0)
                        ->orderBy('acadprogid')
                        ->distinct('acadprogid')
                        ->get();
    }
    else if(Session::get('currentPortal') == 2){
        
        $acadprogid = DB::table('teacheracadprog')
                        ->where('teacherid',$teacherid)
                        ->where('acadprogutype',2)
                        ->select('acadprogid')
                        ->where('deleted',0)
                        ->orderBy('acadprogid')
                        ->distinct('acadprogid')
                        ->get();

        $xtend = 'principalsportal.layouts.app2';
    }else if(Session::get('currentPortal') == 17){
        
        $acadprogid = DB::table('academicprogram')
                        ->select(
                            'academicprogram.*',
                            'id as acadprogid'
                        )
                        ->get();
        $xtend = 'superadmin.layouts.app2';
    }
    else{
        if( $refid->refid == 20){
            $xtend = 'principalassistant.layouts.app2';
        }elseif( $refid->refid == 22){
            $xtend = 'principalcoor.layouts.app2';
        }

        $syid = DB::table('sy')->where('isactive',1)->select('id')->first()->id;

        $acadprogid = DB::table('teacheracadprog')
                        ->where('teacherid',$teacherid)
                        ->where('syid',$syid)
                        ->select('acadprogid')
                        ->where('deleted',0)
                        ->distinct('acadprogid')
                        ->get();
    }

    $all_acad = array();

    foreach( $acadprogid as $item){
        if($item->acadprogid != 6){
            array_push($all_acad,$item->acadprogid);
        }
    }
@endphp

@extends($xtend)

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <style>
        .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
    </style>

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
                    'syid',
                    'sectionid',
                    'strandid',
                    'strandcode'
                )->get();
    @endphp

    @php
        $sy = DB::table('sy')
                ->select(
                    'sydesc as text',
                    'id'
                )
                ->orderBy('sydesc')
                ->get();

        $gradelevel = DB::table('gradelevel')
                        ->where('acadprogid','!=',6)
                        ->select(
                            'levelname as text',
                            'id'
                        )
                        ->orderBy('sortid')
                        ->get();

    @endphp

@endsection

@section('content')

    

    <div class="modal fade" id="teacher_monitoring_form" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">Teacher Monitoring</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-md-6">
                            <label class="mb-0" for="">Teacher : </label> <span id="teacher_monitoring_label_teacher">Teacher 1</span>
                        </div>
                        <div class="col-md-6">
                            <label for="" class="mb-0" >Term : </label> <span id="teacher_monitoring_label_term"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">School Year : </label> <span id="eacher_monitoring_label_sy">2021 - 2022</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-sm table-bordered" style="font-size:.9rem!important;" id="teacher_monitoring_datatable">
                                <thead>
                                    <tr>
                                        <th width="15%">Grade Level</th>
                                        <th width="26%">Section</th>
                                        <th width="37%">Subject</th>
                                        <th width="11%" class="text-center">Students</th>
                                        <th width="11%" class="text-center">Responses</th>
                                    </tr>
                                </thead>
                                {{-- <tbody id="teacher_monitoring_datatable">
                                    <tr>
                                        <td >Grade 12</td>
                                        <td>Sample Section</td>
                                        <td >Sample Subject</td>
                                        <td class="text-center">5</td>
                                        <td class="text-center"><a href="#response_stud_form" role="button" data-toggle="modal" >5</a></td>
                                    </tr>
                                </tbody> --}}
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   

    <div class="modal fade" id="response_stud_form" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header pb-2 pt-2 border-0">
                    <h4 class="modal-title" id="modal_1_title">Respondents</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pt-0" >
                    <div class="row">
                        <div class="col-md-6">
                            <label for="" class="mb-0" >Teacher : </label> <span id="teacher_holder">Teacher 1</span>
                        </div>
                        <div class="col-md-6">
                            {{-- <label for="" class="mb-0" >Term : </label> <span id="label_term">1</span> --}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="" class="mb-0">Section : </label> <span id="label_section">Section 1</span>
                        </div>
                       
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="" class="mb-0">Subject : </label> <span id="label_subject">Subject </span>
                        </div>
                    </div>
                    <hr>
                    <table class="table table-sm table-bordered" id="response_stud_datatable">
                        <thead>
                            <tr>
                                <th width="60%">Student</th>
                                <th width="15%" class="text-center">Status</th>
                                <th width="25%">Status Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>   

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Teacher Evaluaton Monitoring</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Teacher Monitoring</li>
                </ol>
                </div>
            </div>
        </div>
    </section>
    
    <section class="content ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="error-container"></div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" class="mb-1">Grade Level</label>
                                       
                                        <select class="form-control select2" id="gradelevel">
                                            <option selected value="" >Select Grade Level</option>
                                            @foreach ($all_acad as $item)
                                                @php
                                                    $gradelevel = DB::table('gradelevel')
                                                            ->where('acadprogid',$item)->orderBy('sortid')->where('deleted',0)->select('id','levelname')->get();
                                                @endphp
                                                @foreach ($gradelevel as $levelitem)
                                                    <option value="{{$levelitem->id}}">{{$levelitem->levelname}}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="mb-1">Section</label>
                                        <select name="section" id="section" class="form-control select2" onchange="get_monitoring()">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2" >
                                    <div class="form-group strand_holder" hidden id="starnd_holder">
                                        <label class="mb-1">Strand</label>
                                        <select name="strand" id="strand" class="form-control select2">
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-2">
                                    <label class="mb-1">School Year</label>
                                    <select name="syid" id="syid" class="form-control select2" onchange="get_sections()">
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
                                    <label class="mb-1">Semester</label>
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
                            <div class="row">
                                <div class="col-md-2">
                                    <label class="mb-1">Term</label>
                                    <select name="syid" id="syid" class="form-control select2" >
                                        <option value="1">Term 1</option>
                                        <option value="2">Term 2</option>
                                        <option value="3">Term 3</option>
                                        <option value="4">Term 4</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-sm table-bordered" style="font-size:.9rem!important;">
                                        <thead>
                                            <tr>
                                                <th width="60%">Subject</th>
                                                <th width="20%">Teacher</th>
                                                <th width="10%" class="text-center">Students</th>
                                                <th width="10%" class="text-center">Responses</th>
                                            </tr>
                                        </thead>
                                        <tbody  id="eval_datatable">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footerjavascript')

    <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $('.select2').select2()
        var all_section_data = []
        var strand = @json($subj_strand);
         
        get_sections()
        function get_sections() {
            $.ajax({
                type: 'GET',
                url: '/sections/info/list',
                data: { syid: $('#syid').val() },
                success: function(data){
                    sections = data.map(b => ({ ...b, id: b.sectionid }))
                    display_section()
                }
            });
        }

        $('#eacher_monitoring_label_sy').text($('#syid option:selected').text())

        function print_report(teacherid = null, subjid = null){

            var schedtype = "teacher"
            var levelid = $('#gradelevel').val()
            var sectionid = $('#section').val()
            var syid =  $('#syid').val()
            var semid = ($('#gradelevel').val() == 14 || $('#gradelevel').val() == 15) ? $('#semester').val() : null
            var strand = $('#strand').val()


            window.open('/teacher/evaluation/monitoring/print?schedtype=teacher&teacherid='+teacherid+'&levelid='+levelid+'&sectionid='+sectionid+'&syid='+syid+'&semid='+semid+'&strand='+strand+'&subjid='+subjid, '_blank');
        }

        function response_stud_datatable(subjid,teacherid){

            var tempdata = all_section_data.find(x=>x.subjid == subjid)
            var students = tempdata.students

            var teacher = null
            if(tempdata.schedule.length > 0){
                teacher = tempdata.schedule[0].teacher
            }

            $('#label_subject').text(tempdata.subjdesc)
            $('#teacher_holder').text(teacher)
            $('#label_section').text($('#section option:selected').text())

            $("#response_stud_datatable").DataTable({
                    destroy: true,
                    lengthChange: false,
                    autoWidth: false,
                    stateSave: true,
                    data:students,
                    columns: [
                            { "data": "student" },
                            { "data": null },
                            { "data": null },
                    ],
                    columnDefs: [
                                    {
                                        'targets': 1,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                var status = tempdata.evaluations.filter(x=>x.studid == rowData.studid)
                                                if(status.length > 0){
                                                    $(td).addClass('text-center')
                                                    $(td)[0].innerHTML = '<span class="badge badge-success">Evaluated</span>'
                                                }else{
                                                    $(td).text(null)
                                                }
                                        }
                                    },
                                    {
                                        'targets': 2,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                var status = tempdata.evaluations.filter(x=>x.studid == rowData.studid)
                                                if(status.length > 0){
                                                    $(td)[0].innerHTML = '<i style="font-size:.8rem !important">'+status[0].createddatetime+'</i>'
                                                }else{
                                                    $(td).text(null)
                                                }
                                        }
                                    },
                            ]
                })
        }

        function get_monitoring(schedtype="student", teacherid=null, teacher = null){

            if(teacherid != null){
                $('#teacher_monitoring_label_teacher').text(teacher)
            }

            if($('#gradelevel').val() == 14 || $('#gradelevel').val() == 15){
                $('.strand_holder').removeAttr('hidden')
            }else{
                $('.strand_holder').attr('hidden','hidden')
            }
            

            if($('#gradelevel').val() == 14 || $('#gradelevel').val() == 15){
                var temp_section = $('#section').val()
                var temp_sy = $('#syid').val()
                var temp_strand = strand.filter(x=>x.sectionid == temp_section && x.syid == temp_sy)
                $("#strand").empty()
                $.each(temp_strand,function(a,b){
                        b.text = b.strandcode
                        b.id = b.strandid
                })
                $("#strand").select2({
                        data: temp_strand,
                        allowClear: true,
                        placeholder: "Select a strand",
                })
            }


            $.ajax({
                type:'GET',
                url:'/teacher/evaluation/monitoring/list',
                data:{
                    schedtype:schedtype,
                    teacherid:teacherid,
                    levelid: $('#gradelevel').val(),
                    sectionid: $('#section').val(),
                    syid: $('#syid').val(),
                    semid: ($('#gradelevel').val() == 14 || $('#gradelevel').val() == 15) ? $('#semester').val() : null,
                    strandid: $('#strand').val()
                }, success:function(data) {


                    if(schedtype == "teacher"){
                        display_teacher_sched(data)
                    }else{
                        all_section_data = data
                        $('#eval_datatable').empty()
                      
                        if($('#gradelevel').val() == 14 || $('#gradelevel').val() == 15){
                            var temp_data = data
                        }else{
                            var temp_data = data.filter(x=>x.isCon == 0)
                        }
                        
                        $.each(temp_data,function(a,b){
                            var teacher = ''
                            var teacherid = null
                            if(b.schedule.length > 0){
                                teacher = b.schedule[0].teacher
                                teacherid = b.schedule[0].teacherid
                            }
                            $('#eval_datatable').append(`
                                <tr>
                                    <td>`+b.subjdesc+`</td>
                                    <td><a href="#teacher_monitoring_form" role="button" data-toggle="modal" onclick="get_monitoring('teacher',`+teacherid+`,'`+teacher+`')">`+teacher+`</a></td>
                                    <td class="text-center">`+b.students.length+`</td>
                                    <td class="text-center"><a href="#response_stud_form" role="button" data-toggle="modal" onclick="response_stud_datatable(`+b.subjid+`,`+teacherid+`)">`+b.evaluations.length+`</a></td>
                                </tr>
                            `)
                        })
                    }
                   
                }
            })
        }

        function display_teacher_sched(data){
            $("#teacher_monitoring_datatable").DataTable({
                    destroy: true,
                    lengthChange: false,
                    autoWidth: false,
                    stateSave: true,
                    data:data,
                    columns: [
                            { "data": "levelname" },
                            { "data": "sectionname" },
                            { "data": "subjdesc" },
                            { "data": null },
                            { "data": null },
                    ],
                    columnDefs: [
                                    {
                                        'targets': 2,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                            var text = `<a href="javascript:void(0)" onclick="print_report(`+rowData.tid+`,`+rowData.subjid+`)">`+rowData.subjdesc+`</a>`
                                            $(td)[0].innerHTML = text
                                        }
                                    },
                                    {
                                        'targets': 3,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                var status = rowData.students.length
                                                $(td).text(status)
                                                $(td).addClass('text-center')
                                        }
                                    },
                                    {
                                        'targets': 4,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                                var status = rowData.evaluations.length
                                                var text = `<a href="#response_stud_form" role="button" data-toggle="modal" onclick="response_stud_datatable(`+rowData.subjid+`,`+rowData.teacherid+`)">`+status+`</a>`
                                                $(td)[0].innerHTML = text
                                                $(td).addClass('text-center')
                                        }
                                    },
                            ]
                })
        }
         
        $(document).on('change','#gradelevel',function(){
            display_section()
        })

        function display_section(){
            var temp_sections = sections.filter(x=> x.levelid == $('#gradelevel').val())
            $("#section").empty()
                         .append('<option value="">Section</option>')
                         .select2({
                                data: temp_sections,
                                placeholder: "Section",
                                allowClear:true
                        })
        }

       
        
        // $(document).on('change','#section',function(){
        //     var temp_section = $(this).val()
        //     var temp_sy = $('#syid').val()
        //     var temp_strand = strand.filter(x=>x.sectionid == temp_section && x.syid == temp_sy)
        //     $("#strand").empty()
        //     $.each(temp_strand,function(a,b){
        //             b.text = b.strandcode
        //             b.id = b.strandid
        //     })
        //     $("#strand").select2({
        //             data: temp_strand,
        //             allowClear: true,
        //             placeholder: "Select a strand",
        //     })

           
        // })


    </script>
    
@endsection

