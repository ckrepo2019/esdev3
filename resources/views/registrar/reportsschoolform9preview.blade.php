
@extends('registrar.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/jquery-year-picker/css/yearpicker.css')}}" />
    <style>
        td                                          { border-bottom: hidden; }
        input[type=text], .input-group-text, .select{ background-color: white !important; border: hidden; border-bottom: 2px solid #ddd; font-size: 12px !important; }
        .input-group-text                           { border-bottom: hidden; }
        .fontSize                                   { font-size: 12px; }
        .fontSize td, .fontSize th{
            border: 1px solid black;
        }
        .container                                  { overflow-x: scroll !important; }
        table                                       { width: 100%; }
        .inputClass                                 { width: 100%; }
        .tdInputClass                               { padding: 0px !important; }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button            { -webkit-appearance: none; margin: 0; }
        
    </style>

<form id="submitSelectSchoolyear" action="/reports/selectSy" method="GET" class="m-0 p-0">
    <input type="hidden" value="{{$schoolyear}}" name="syid"/>
    <input type="hidden" value="School Form 9" name="selectedform"/>
    <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
</form>
<form id="submitSelectSection" action="/reports/selectSection" method="GET" class="m-0 p-0">
    <input type="hidden" value="{{$schoolyear}}" name="syid"/>
    <input type="hidden" value="School Form 9" name="selectedform"/>
    <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
</form>
    <section class="content-header">
        <div class="col-12">
            @if($academicprogram == 'elementary')
                <h4>Elementary</h4>
            @elseif($academicprogram == 'juniorhighschool')
                <h4>Junior High School</h4>
            @elseif($academicprogram == 'seniorhighschool')
                <h4>Senior High School</h4>
            @endif
        </div>
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/reports/{{$academicprogram}}">{{$selectedform}}</a></li>
                        <li class="breadcrumb-item"><a href="#" id="selectschoolyear" class="text-info">{{$schoolyeardesc}}</a></li>
                        <li class="breadcrumb-item"><a id="selectsection" class="text-info">Select Section</a></li>
                        <li class="breadcrumb-item active">Students</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-file"></i>
                        <strong>Learner's Progress Report Card</strong>
                    </h3>
                    <br>
                    <small><em>(Formerly Form 138)</em></small>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @if(count($students) == 0)
            <div class="alert alert-warning alert-dismissible col-12">
                <h5><i class="icon fas fa-exclamation-triangle"></i> No records shown!</h5>
            </div>
            @else
            <div class="card">
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow: scroll">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example1" style="table-layout: fixed;font-size: 12px" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                <thead>
                                    <tr>
                                        {{-- <th>#</th> --}}
                                        <th>Name</th>
                                        <th style="width:20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                                            <td>
                                                <form action="/prinsf9print/{{$student->id}}" method="get" name="{{$student->id}}" target="_blank">
                                                    <input type="hidden" name="studid" class="{{$student->id}}" value="{{$student->id}}"/>
                                                    <input type="hidden" name="syid" value="{{$schoolyear}}"/>
                                                    <button type="submit" class="btn btn-sm btn-info btn-block">View Record</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
            </div>
            @endif
    </div>
    {{-- </div> --}}
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- fullCalendar 2.2.5 -->
    <!-- InputMask -->
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('plugins/jquery-year-picker/js/yearpicker.js')}}"></script>
    <!-- date-range-picker -->
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
    <!-- DataTables -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script>
        
    $(function () {
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });
        
    })
   
    $('#selectschoolyear').on('click', function (){
            document.getElementById('submitSelectSchoolyear').submit();
        });
        $('#selectsection').on('click', function (){
            document.getElementById('submitSelectSection').submit();
        });
        $('a').on('click', function (){
            $(this).closest('form').submit();
        });
    </script>
@endsection

                                        

                                        
                                        