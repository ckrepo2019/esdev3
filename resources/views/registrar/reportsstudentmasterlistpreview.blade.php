
@extends('registrar.layouts.app')
@section('content')
    <style>
        .table tr{ border-bottom: 1px solid #ddd; }
        .card-header{ border-bottom: hidden; }
        td{ text-transform: uppercase; }
    </style>
    <form id="submitSelectSection" action="/reports/selectSection" method="GET" class="m-0 p-0">
        <input type="hidden" value="{{$schoolyear}}" name="syid"/>
        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
        <input type="hidden" value="Student Masterlist" name="selectedform"/>
    </form>
    <form id="submitSelectSchoolyear" action="/reports/selectSy" method="GET" class="m-0 p-0">
        <input type="hidden" value="{{$schoolyear ?? ''}}" name="syid"/>
        <input type="hidden" value="{{$academicprogram}}" name="academicprogram"/>
        <input type="hidden" value="Student Masterlist" name="selectedform"/>
    </form>
    @if(isset($data[0]->sectionid))
    <form id="submitesc" action="/reports_studentmasterlist/preview/{{$schoolyear}}/{{$data[0]->sectionid}}" method="GET" class="m-0 p-0">
        <input type="hidden" value="1" name="esc"/>
        <input type="hidden" value="Student Masterlist" name="selectedform"/>
    </form>
    @endif
    @if(isset($sectionid))
    <form id="submitesc2" action="/reports_studentmasterlist/preview/{{$schoolyear}}/{{$sectionid}}" method="GET" class="m-0 p-0">
        <input type="hidden" value="Student Masterlist" name="selectedform"/>
    </form>
    @endif
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
                        <li class="breadcrumb-item"><a id="selectschoolyear" class="text-info">{{$schoolyeardesc}}</a></li>
                        <li class="breadcrumb-item"><a id="selectsection" class="text-info">{{$selectedsection}}</a></li>
                        <li class="breadcrumb-item active">Student Masterlist</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    @if(isset($message))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-warning alert-dismissible">
                    {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                    {{$message}}
                </div>
            </div>
        </div>
    @endif
    @if(isset($escmessage))
        <div class="row">
            <div class="col-12">
                <div class="card card-default color-palette-box">
                    <div class="card-header">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="checkboxPrimary2" name="escCheck2" checked>
                            <label for="checkboxPrimary2">
                                ESC Grantee
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-warning alert-dismissible">
                    {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                    {{$escmessage}}
                </div>
            </div>
        </div>
    @endif
    @if(isset($data[0]))
        <div class="row">
            <div class="col-12">
                <div class="card card-default color-palette-box">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-file"></i>
                            <strong>{{$data[0]->gradelevelname}} - {{$data[0]->sectionname}}</strong>
                        </h3>
                        <br>
                        {{-- <small>Teacher Incharged: <u>{{$data[0]->teacher_lastname}}, {{$data[0]->teacher_firstname}} {{$data[0]->teacher_middlename[0]}}. {{$data[0]->teacher_suffix}}</u></small> --}}
                        <form action="/reports_studentmasterlist/print/{{$schoolyear}}/{{$data[0]->sectionid}}" target="_blank" id="exportform">
                            <input type="hidden" name="exporttype" id="exporttype"/>
                            {{-- <button type="submit" class="btn btn-primary btn-sm float-right" style="color: white;"><i class="fa fa-print"></i> Print</button> --}}
                            {{-- <button type="submit" class="btn btn-primary btn-sm float-right" style="color: white;"><i class="fa fa-print"></i> Print</button> --}}
                            <div class="btn-group float-right">
                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                  <span class="sr-only">Toggle Dropdown</span>
                                  <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="#" id="exportpdf">PDF</a>
                                    <a class="dropdown-item" href="#" id="exportexcel">EXCEL</a>
                                  </div>
                                </button>
                                <button type="button" class="btn btn-default">Export As</button>
                              </div>
                        <br>
                        <br>
                        @if(isset($esc))
                        <input type="hidden" value="1" name="esc"/>
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="checkboxPrimary2" name="escCheck" value="1" checked>
                            <label for="checkboxPrimary2">
                                ESC Grantee
                            </label>
                        </div>
                        @else
                        <input type="hidden" value="0" name="esc"/>
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="checkboxPrimary1" value="0" name="escCheck2">
                                <label for="checkboxPrimary1">
                                    ESC Grantee
                                </label>
                            </div>
                        @endif
                    </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(isset($data))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-male"></i>
                            <strong>Male</strong>
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Suffix</th>
                            </tr>
                            @php
                                $male = 0;
                            @endphp
                            @foreach ($data as $student)
                                @if (strtoupper($student->student_gender)=="MALE")
                                    @php
                                        $male+=1;   
                                    @endphp
                                    <tr>
                                        <td width="40%">
                                            {{$male}}.  {{$student->student_lastname}},
                                        </td>
                                        <td>
                                            {{$student->student_firstname}}
                                        </td>
                                        <td width="20%">
                                            {{$student->student_middlename}}
                                        </td>
                                        <td width="5%">
                                            {{$student->student_suffix}}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fa fa-female"></i>
                            <strong>Female</strong>
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Suffix</th>
                            </tr>
                            @php
                                $female = 0;
                            @endphp
                            @foreach ($data as $student)
                                @if (strtoupper($student->student_gender)=="FEMALE")
                                    @php
                                        $female+=1;   
                                    @endphp
                                    <tr>
                                        <td width="40%">
                                            {{$female}}. {{$student->student_lastname}},
                                        </td>
                                        <td>
                                            {{$student->student_firstname}}
                                        </td>
                                        <td width="20%">
                                            {{$student->student_middlename}}
                                        </td>
                                        <td width="5%">
                                            {{$student->student_suffix}}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- </div> --}}
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <script>
        
        $('#selectsection').on('click', function (){
            document.getElementById('submitSelectSection').submit();
        })
        $('#selectschoolyear').on('click', function (){
            document.getElementById('submitSelectSchoolyear').submit();
        })
        $('input[name=escCheck]').on('click', function (){
            if($(this).prop('checked')==true){
                console.log('checked');
                $('input[name=esc]').val('1');
                $('#submitesc').submit();
            }
            else{
                $('input[name=esc]').remove();
                $('#submitesc').submit();
            }
        })
        $('input[name=escCheck2]').on('click', function (){
            if($(this).prop('checked')==true){
                console.log('checked');
                $('#submitesc').submit();
            }
            else{
                $('#submitesc2').submit();
            }
        })
        $('#exportpdf').on('click', function(){
            $('#exporttype').val('pdf')
            $('#exportform').submit();
        })
        $('#exportexcel').on('click', function(){
            $('#exporttype').val('excel')
            $('#exportform').submit();
        })
    </script>
@endsection
