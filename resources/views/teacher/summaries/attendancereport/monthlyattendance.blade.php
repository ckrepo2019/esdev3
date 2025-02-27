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
                        <label>Section</label>
                        <select class="form-control form-control-sm">
                            @if(count($sections) == 0)
                                <option value="0">No sections assigned</option>
                            @else
                                @foreach($sections as $section)
                                    <option value="{{$section->sectionid}}">{{$section->levelname}} - {{$section->sectionname}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    {{-- <div class="col-md-3">
                        <label>Date</label>
                        <input type="date" class="form-control" value="{{$date}}" id="selecteddate">
                    </div>
                    <div class="col-md-2">
                        <label>Day</label>
                        <br/>
                        <strong id="selectedday">{{$today}}</strong>
                    </div>
                    <div class="col-md-7">
                        <label>Subjects for the day</label>
                        <br>
                        <div id="subjectsfortheday">
                            
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="card-body table-responsive p-0" style="height: 600px;" id="attendancecontainer">
            
            </div>
        </div>
    </section>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
    <script>
        
    </script>

@endsection