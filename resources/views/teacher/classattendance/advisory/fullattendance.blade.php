<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<style>
    
    /* input[type=radio]                   { visibility: hidden; position: relative;width: 20px; height: 20px; }

    input[type=radio].present:before    { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio].late:before       { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0;padding: 0; }

    input[type=radio].halfday:before    { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio].absent:before     { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio].present:checked:before    { font-family: "Font Awesome 5 Free";content: "\f00c";color: green;font-size: 20px;border: 1px solid white; }

    input[type=radio].late:checked:before       { background-color: gold; }

    input[type=radio].halfday:checked:before    { background-color: #6c757d; }

    input[type=radio].absent:checked:before     { font-family: "Font Awesome 5 Pro", "Font Awesome 5 Free";content: "\f00d";color: red;font-size: 20px;border: 1px solid white; }

    td                  { text-transform: uppercase !important; } */

    .tableFixHead       { overflow-y: auto; height: 500px; }

    .tableFixHead table { border-collapse: collapse; width: 100%; }

    .tableFixHead th,
    .tableFixHead td    { /* padding: 8px 16px; */ }

    .tableFixHead th    { position: sticky; top: 0; background-color: #eee; z-index: 100;}
    thead{
        background-color: #eee !important;
    }
    
    .table                      {width:1500px; font-size:90%; text-transform: uppercase; }

/* .table thead th:first-child { position: sticky; left: 0; background-color: #fff;
    z-index: 9999999 } */
    
.table thead th:first-child  { 
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    z-index: 999 !important
}
.table thead th:last-child  { 
    position: sticky !important; 
    right: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    z-index: 999 !important
}
/* .table thead {
    
    z-index: 999
} */

.table tbody td:last-child  { 
    position: sticky; 
    right: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    /* z-index: 999 */
    }

.table tbody td:first-child  {  
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    width: 150px !important;
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

.table thead th:first-child  { 
        position: sticky; left: 0; 
        width: 150px !important;
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
}

</style>
@extends('teacher.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Attendance</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="active breadcrumb-item"><a href="/classattendance">Attendance</a></li>
                        <li class="active breadcrumb-item" aria-current="page">Advisory</li>
                        <li class="active breadcrumb-item" aria-current="page">Full Attendance</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content-body">
        <div class="card" style="border: 1px solid #ddd; box-shadow: none !important">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3">
                        <label>Date</label>
                        <input type="date" class="form-control" value="{{$date}}" id="selecteddate">
                    </div>
                    <div class="col-md-2">
                        <label>Day</label>
                        <br/>
                        <strong id="selectedday">{{$today}}</strong>
                    </div>
                    <div class="col-md-4">
                        <label>Subjects for the day</label>
                        <br>
                        <div id="subjectsfortheday">
                            @if(count($subjectsfortoday)==0)
                            <button type="button" class="btn btn-sm btn-warning">No subjects scheduled</button>
                            @else
                                @foreach($subjectsfortoday as $subjtoday)
                                    <button type="button" class="btn btn-sm btn-info">{{$subjtoday->subjectcode}}</button>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 text-right">
                        <label>Export</label>
                        <br>
                        <button type="button" class="btn btn-sm btn-default" id="exporttopdf"><i class="fa fa-file-pdf"></i>  PDF</button>
                        {{-- <button type="button" class="btn btn-sm btn-default"><i class="fa fa-file-pdf"></i>  Excel</button> --}}
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive p-0" style="height: 600px;" id="attendancecontainer">
              <table class="table table-head-fixed text-nowrap">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        @if(count($classsubjects)>0)
                            @foreach($classsubjects as $classsubject)
                                <th class="text-center">{{$classsubject->subjectcode}}</th>
                            @endforeach
                        @endif
                        <th >Advisory</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($students)>0)
                        @foreach($students as $student)
                            <tr>
                                <td>
                                    {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                </td>
                                @if(count($student->subjectattendance)>0)
                                    @foreach($student->subjectattendance as $subjectattendance)
                                        <td class="text-center">
                                            @if(strtolower($subjectattendance->status) == 'present')
                                                <span class="badge bg-success">PRESENT</span>
                                            @elseif(strtolower($subjectattendance->status) == 'late')
                                                <span class="badge bg-warning" data-toggle="tooltip" data-placement="bottom" title="{{$subjectattendance->remarks}}" >LATE</span>
                                            @elseif(strtolower($subjectattendance->status) == 'absent')
                                                <span class="badge bg-danger" data-toggle="tooltip" data-placement="bottom" title="{{$subjectattendance->remarks}}" >ABSENT</span>
                                            @endif
                                        </td>
                                    @endforeach
                                @endif
                                <td >
                                    @if($student->classattendance == 1)
                                    <span class="badge bg-success">PRESENT</span>
                                    @elseif($student->classattendance == 2)
                                    <span class="badge bg-warning" data-toggle="tooltip" data-placement="bottom" title="{{$student->remarks}}">LATE</span>
                                    @elseif($student->classattendance == 3)
                                    <span class="badge bg-warning" data-toggle="tooltip" data-placement="bottom" title="{{$student->remarks}}">CUTTING CLASS</span>
                                    @elseif($student->classattendance == 4)
                                    <span class="badge bg-danger" data-toggle="tooltip" data-placement="bottom" title="{{$student->remarks}}">ABSENT</span>
                                    @else
                                    <span class="badge bg-secondary">UNCHECKED</span>
                                    @endif
                                    {{-- <select class="form-control form-control-sm" >
                                        <option value="" {{null == $student->classattendance ? 'selected' : ''}}>UNCHECKED</option>
                                        <option value="1" {{1 == $student->classattendance ? 'selected' : ''}}>PRESENT</option>
                                        <option value="2" {{2 == $student->classattendance ? 'selected' : ''}}>LATE</option>
                                        <option value="3" {{3 == $student->classattendance ? 'selected' : ''}}>CUTTING CLASS</option>
                                        <option value="4" {{4 == $student->classattendance ? 'selected' : ''}}>ABSENT</option>
                                    </select> --}}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
              </table>
            </div>
            {{-- <div class="card-body">
                <div id="tableContainer" class=" table-responsive tableFixHead" style="height: 400px">
                <table  class="table table-bordered mb-0" disabled>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            @if(count($classsubjects)>0)
                                @foreach($classsubjects as $classsubject)
                                    <th>{{$classsubject->subjectcode}}</th>
                                @endforeach
                            @endif
                            <th>SF2 Attendance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($students)>0)
                            @foreach($students as $student)
                                <tr>
                                    <td>
                                        {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                    </td>
                                        @if(count($student->subjectattendance)>0)
                                            @foreach($student->subjectattendance as $subjectattendance)
                                                <th>{{$subjectattendance->status}}</th>
                                            @endforeach
                                        @endif
                                    <th>SF2</th>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
              </table>
            </div>
            </div> --}}
        </div>
    </section>
    {{-- <div>
        <nav class="" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="active breadcrumb-item"><a href="/classattendance">Attendance</a></li>
                <li class="active breadcrumb-item" aria-current="page">Advisory</li>
                <li class="active breadcrumb-item" aria-current="page">Full Attendance</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card ">
                <div class="card-header bg-info">
                    <h3 class="card-title">
                        <i class="fas fa-tasks"></i>
                        Class Attendance
                    </h3>
                </div>
                </div>
            </div>
        </div>
    </div> --}}
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
    <script>
        
        $(document).ready(function(){
            
            $('[data-toggle="tooltip"]').tooltip();
            $('#selecteddate').on('change', function(){
                $.ajax({
                    url:'/teacher/classattendance/full/changedate',
                    type: 'GET',
                    data: {
                        sectionid: '{{$sectionid}}',
                        levelid: '{{$levelid}}',
                        date: $(this).val()
                    },
                    success:function(data){
                        $('#attendancecontainer').empty()
                        $('#attendancecontainer').append(data)
                    }

                })
                $.ajax({
                    url:'/teacher/classattendance/full/daydetails',
                    type: 'GET',
                    data: {
                        sectionid: '{{$sectionid}}',
                        levelid: '{{$levelid}}',
                        date: $(this).val()
                    },
                    success:function(data){
                        // console.log(data)
                        $('#selectedday').text(data.selecteddate);
                        $('#subjectsfortheday').empty()
                        if(data.subjects.length == 0)
                        {
                                $('#subjectsfortheday').append(
                                    '<button type="button" class="btn btn-sm btn-warning">No subjects scheduled</button>'
                                )
                        }else{
                            $.each(data.subjects, function(key, value){
                                $('#subjectsfortheday').append(
                                    '<button type="button" class="btn btn-sm btn-info">'+value.subjectcode+'</button> '
                                )
                            })
                        }         

                    }

                })
            })
            $('#exporttopdf').on('click', function(){
                
                var paramet = {
                        sectionid: '{{$sectionid}}',
                        levelid: '{{$levelid}}',
                        date: $('#selecteddate').val()
                }
				window.open("/teacher/classattendance/full/print?exporttype=pdf&"+$.param(paramet));
            })
        })
    </script>

@endsection