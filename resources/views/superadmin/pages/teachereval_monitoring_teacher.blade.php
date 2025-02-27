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


    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Peer to Peer Monitoring</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Peer to Peer Monitoring</li>
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
                                <div class="col-md-5">

                                </div>
                              
                                <div class="col-md-2" >
                                    
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-2">
                                    <label class="mb-1">School Year</label>
                                    <select name="syid" id="syid" class="form-control select2" onchange=" get_teachers()">
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
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-sm table-bordered" style="font-size:.9rem!important;" id="eval_datatable">
                                        <thead>
                                            <tr>
                                                <th width="60%">Teacher</th>
                                                <th width="15%" class="text-center">Repondents</th>
                                                <th width="15%" class="text-center">Responses</th>
                                                <th width="10%" class="text-center"></th>
                                            </tr>
                                        </thead>
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
        var all_teacher = []
        
        get_teachers()

        function print_report(teacherid){
            var syid =  $('#syid').val()
            var semid = $('#semester').val() 
            window.open('/teacher/evaluation/monitoring/teacher/print?syid='+syid+'&teacherid='+teacherid, '_blank');
        }

        function get_teachers(){
            $.ajax({
                type:'GET',
                url:'/teacher/evaluation/monitoring/teacher/list',
                data:{
                    syid: $('#syid').val(),
                    semid:  $('#semester').val(),
                }, success:function(data) {
                    all_teacher = data
                    display_datatable()
                }
            })
        }

        function display_datatable(){
            
                                       
            $("#eval_datatable").DataTable({
                    destroy: true,
                    lengthChange: false,
                    autoWidth: false,
                    stateSave: true,
                    data:all_teacher,
                    columns: [
                            { "data": "fullname" },
                            { "data": null },
                            { "data": null },
                            { "data": null },
                    ],
                    columnDefs: [
                                    {
                                        'targets': 1,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                            $(td).text(all_teacher.length)
                                            $(td).addClass('text-center')
                                        }
                                    },
                                    {
                                        'targets': 2,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                            $(td).text(rowData.evaluations.length)
                                            $(td).addClass('text-center')
                                        }
                                    },
                                    {
                                        'targets': 3,
                                        'orderable': false, 
                                        'createdCell':  function (td, cellData, rowData, row, col) {
                                            var text = '<button class="btn btn-sm btn-default" style="font-size:.7rem !important" onclick="print_report('+rowData.id+')"><i class="fa fa-print"> </i> Print</button>'
                                            $(td)[0].innerHTML = text
                                            $(td).addClass('text-center')
                                        }
                                    },
                    ]
            })
        }

    </script>
    
@endsection

