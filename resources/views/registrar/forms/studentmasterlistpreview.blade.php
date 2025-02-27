
@extends('registrar.layouts.app')
@section('content')
    <style>
        .table tr{ border-bottom: 1px solid #ddd; }
        .card-header{ border-bottom: hidden; }
        td{ text-transform: uppercase; }
        th, td {
            padding: 3px !important;
        }
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
    {{-- @if(isset($sectiondetail[0]->sectionid)) --}}
    <form id="submitesc" action="/reports_studentmasterlist/preview/{{$schoolyear}}/{{$sectiondetail[0]->id}}" method="GET" class="m-0 p-0">
        <input type="hidden" value="1" name="esc"/>
        <input type="hidden" value="{{$levelid}}" name="levelid"/>
        <input type="hidden" value="Student Masterlist" name="selectedform"/>
        <input type="hidden" name="academicprogram" value="{{$academicprogram}}"/>
    </form>
    {{-- @endif --}}
    @if(isset($sectionid))
    <form id="submitesc2" action="/reports_studentmasterlist/preview/{{$schoolyear}}/{{$sectionid}}" method="GET" class="m-0 p-0">
        <input type="hidden" value="Student Masterlist" name="selectedform"/>
        <input type="hidden" value="{{$levelid}}" name="levelid"/>
        <input type="hidden" name="academicprogram" value="{{$academicprogram}}"/>
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
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                    {{$message}}
                </div>
            </div>
        </div>
    @endif
    @if(isset($escmessage))
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
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
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                    {{$escmessage}}
                </div>
            </div>
        </div>
        <script>
            $('#checkboxPrimary2').on('click', function (){
                if($(this).prop('checked')==true){
                    $('#submitesc').submit();
                }
                else{
                    $('#submitesc2').submit();
                }
            })
        </script>
    @endif
    @if(isset($data))
        @if($academicprogram == 'seniorhighschool')
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="checkescshs" name="escCheck" @if($esc > 0) value="1" checked @else value="0" @endif>
                                <label for="checkescshs">
                                    ESC Grantee
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $data = collect($data)->groupBy('strandcode');   
            @endphp
            @if(count($data)>0)
                @foreach($data as $key => $eachstrand)
                    @php
                    $malenumber = 1;
                    $femalenumber = 1;
                    @endphp
                    @if($key != "")
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-9">
                                    <strong>{{$eachstrand[0]->trackname}} - {{$eachstrand[0]->strandname}} ({{$key}})</strong>
                                </div>
                                <div class="col-md-3">
                                    <form action="/reports_studentmasterlist/print/{{$schoolyear}}/{{$eachstrand[0]->sectionid}}" target="_blank" id="exportform">
                                        <input type="hidden" name="exporttype"/>
                                        <input type="hidden" name="levelid" value="{{$levelid}}"/>
                                        <input type="hidden" name="academicprogram" value="{{$academicprogram}}"/>
                                        <input type="hidden" name="strandid" value="{{$eachstrand[0]->strandid}}"/>
                                        {{-- <button type="submit" class="btn btn-primary btn-sm float-right" style="color: white;"><i class="fa fa-print"></i> Print</button> --}}
                                        {{-- <button type="submit" class="btn btn-primary btn-sm float-right" style="color: white;"><i class="fa fa-print"></i> Print</button> --}}
                                        <div class="btn-group float-right">
                                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                            <span class="sr-only">Toggle Dropdown</span>
                                            <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item exportshspdf" href="#">PDF</a>
                                                <a class="dropdown-item exportshsexcelinfo" href="#">EXCEL (INFO)</a>
                                                <a class="dropdown-item exportshsexcellist" href="#">EXCEL (LIST)</a>
                                            </div>
                                            </button>
                                            <button type="button" class="btn btn-default">Export As</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Male</label>
                                    <table class="table" style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Student Name</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        @foreach ($eachstrand as $item)
                                            @if(strtolower($item->student_gender) == 'male')
                                                <tr>
                                                    <td>{{$malenumber}}</td>
                                                    <td>{{$item->student_lastname}}, {{$item->student_firstname}} {{$item->student_middlename}} {{$item->student_suffix}}</td>
                                                    <td>{{$item->description}}</td>
                                                </tr>
                                                @php 
                                                    $malenumber += 1;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </table>
                                    {{-- <ol>
                                        @foreach ($eachstrand as $item)
                                        @if($item->studentid == '205')
                                        <li>{{collect($item)}}</li>
                                        @endif
                                            @if(strtolower($item->student_gender) == 'male')
                                                <li>{{$malenumber}}. {{$item->student_lastname}}, {{$item->student_firstname}} {{$item->student_middlename}} {{$item->student_suffix}}</li>
                                                @php 
                                                    $malenumber += 1;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </ol> --}}
                                </div>
                                <div class="col-md-6">
                                    <label>Female</label>
                                    <table class="table" style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Student Name</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        @foreach ($eachstrand as $item)
                                            @if(strtolower($item->student_gender) == 'female')
                                                <tr>
                                                    <td>{{$femalenumber}}</td>
                                                    <td>{{$item->student_lastname}}, {{$item->student_firstname}} {{$item->student_middlename}} {{$item->student_suffix}}</td>
                                                    <td>{{$item->description}}</td>
                                                </tr>
                                                @php 
                                                    $femalenumber += 1;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </table>
                                    {{-- <ol>
                                        @foreach ($eachstrand as $item)
                                            @if(strtolower($item->student_gender) == 'female')
                                                <li>{{$femalenumber}}. {{$item->student_lastname}}, {{$item->student_firstname}} {{$item->student_middlename}} {{$item->student_suffix}}</li>
                                                @php 
                                                    $femalenumber += 1;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </ol> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            @endif
            <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
            <script>
                $(document).ready(function(){
                    $('.exportshspdf').on('click', function(){
                        $(this).closest('form').find('input[name="exporttype"]').val('pdf');
                        $(this).closest('form').submit()
                    })
                    $('.exportshsexcelinfo').on('click', function(){
                        $(this).closest('form').find('input[name="exporttype"]').val('exportexcelinfo');
                        $(this).closest('form').submit()
                    })
                    $('.exportshsexcellist').on('click', function(){
                        $(this).closest('form').find('input[name="exporttype"]').val('excellist');
                        $(this).closest('form').submit()
                    })
                    $('#checkescshs').on('click', function (){
                            console.log('checked');
                        if($(this).prop('checked')==true){
                            $('input[name=esc]').val('1');
                            $('#submitesc').submit();
                        }
                        else{
                            $('input[name=esc]').remove();
                            $('#submitesc').submit();
                        }
                    })
                })
            </script>
        @else
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
                            <form action="/reports_studentmasterlist/print/{{$syid}}/{{$sectionid}}" target="_blank" id="exportform">
                                <input type="hidden" name="exporttype" id="exporttype"/>
                                <input type="hidden" name="levelid" value="{{$levelid}}"/>
                                {{-- <button type="submit" class="btn btn-primary btn-sm float-right" style="color: white;"><i class="fa fa-print"></i> Print</button> --}}
                                {{-- <button type="submit" class="btn btn-primary btn-sm float-right" style="color: white;"><i class="fa fa-print"></i> Print</button> --}}
                                <div class="btn-group float-right">
                                    <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                    <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(-1px, 37px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        <a class="dropdown-item" href="#" id="exportpdf">PDF</a>
                                        <a class="dropdown-item" href="#" id="exportexcelinfo">EXCEL (INFO)</a>
                                        <a class="dropdown-item" href="#" id="exportexcellist">EXCEL (LIST)</a>
                                    </div>
                                    </button>
                                    <button type="button" class="btn btn-default">Export As</button>
                                </div>
                            <br>
                            <br>
                            @if($esc > 0)
                            <input type="hidden" value="1" name="esc"/>
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="checkboxesc" name="escCheck" value="1" checked>
                                <label for="checkboxesc">
                                    ESC Grantee
                                </label>
                            </div>
                            @else
                            <input type="hidden" value="0" name="esc"/>
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="checkboxesc" value="0" name="escCheck">
                                    <label for="checkboxesc">
                                        ESC Grantee
                                    </label>
                                </div>
                            @endif
                        </form>
                        </div>
                    </div>
                </div>
            </div>
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
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Status</th>
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
                                            <td width="5%">
                                                {{$male}}
                                            </td>
                                            <td width="70%">
                                                {{$student->student_lastname}}, {{$student->student_firstname}} {{$student->student_middlename}} {{$student->student_suffix}}
                                            </td>
                                            <td>
                                                {{$student->description}}
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
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Status</th>
                                </tr>
                                @php
                                    $female = 0;
                                @endphp
                                @foreach ($data as $student)
                                    @if (strtoupper($student->student_gender)=="FEMALE")
                                        @php
                                            $male+=1;   
                                        @endphp
                                        <tr>
                                            <td width="5%">
                                                {{$male}}
                                            </td>
                                            <td width="70%">
                                                {{$student->student_lastname}}, {{$student->student_firstname}} {{$student->student_middlename}} {{$student->student_suffix}}
                                            </td>
                                            <td>
                                                {{$student->description}}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
            <script>
                $(document).ready(function(){
                    $('#selectsection').on('click', function (){
                        document.getElementById('submitSelectSection').submit();
                    })
                    $('#selectschoolyear').on('click', function (){
                        document.getElementById('submitSelectSchoolyear').submit();
                    })
                    $('#checkboxesc').on('click', function (){
                            console.log('checked');
                        if($(this).prop('checked')==true){
                            $('input[name=esc]').val('1');
                            $('#submitesc').submit();
                        }
                        else{
                            $('input[name=esc]').remove();
                            $('#submitesc').submit();
                        }
                    })
                    $('#exportpdf').on('click', function(){
                        $('#exporttype').val('pdf')
                        $('#exportform').submit();
                    })
                    $('#exportexcelinfo').on('click', function(){
                        $('#exporttype').val('exportexcelinfo')
                        $('#exportform').submit();
                    })
                    $('#exportexcellist').on('click', function(){
                        $('#exporttype').val('excellist')
                        $('#exportform').submit();
                    })
                })
            </script>
        @endif
    @endif
    {{-- </div> --}}
    <!-- jQuery -->
@endsection
