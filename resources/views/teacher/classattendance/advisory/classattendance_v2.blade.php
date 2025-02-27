<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<style>
    td                  { text-transform: uppercase !important; }

    .tableFixHead       { overflow-y: auto; height: 500px; }

    .tableFixHead table { border-collapse: collapse; width: 100%; }

    .tableFixHead th,
    .tableFixHead td    { /* padding: 8px 16px; */ }

    .tableFixHead th    { position: sticky; top: 0; background-color: #eee; z-index: 100;}
    /* thead{
        background-color: #eee !important;
    } */
</style>
@extends('teacher.layouts.app')

@section('content')
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
@php
if(isset($attendance)){
    $count = count($attendance);
    $promoted = 0;
    $female = 0;
    $male = 0;
    foreach ($attendance as $att) {
        if($att->promotionstatus == 1){
            $promoted+=1;
        }
        if(strtoupper($att->gender) == 'FEMALE'){
            $female+=1;
        }
        elseif(strtoupper($att->gender) == 'MALE'){
            $male+=1;
        }
    }
}

@endphp
<div>
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="active breadcrumb-item"><a href="/classattendance">Attendance</a></li>
            <li class="active breadcrumb-item" aria-current="page">Advisory</li>
            <li class="active breadcrumb-item" aria-current="page">{{$sectioninfo->sectionname}}</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card ">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label>Select School Year</label>
                        <select class="form-control" id="selectedschoolyear">
                            @if(count($schoolyears)>0)
                                @foreach($schoolyears as $schoolyear)
                                    <option value="{{$schoolyear->id}}" @if($schoolyear->isactive == 1) selected @endif>{{$schoolyear->sydesc}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Select Semester</label>
                        <select class="form-control" id="selectedsemester">
                            @if(count($semesters)>0)
                                @foreach($semesters as $semester)
                                    <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-3"></div>
                    <div class="col-md-3 text-right"><br/><button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Generate</button></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" id="attendancetablecontainer">
</div>
<script>
    $(document).ready(function(){
        $('body').addClass('sidebar-collapse');
        $('#btn-generate').unbind().click(function(){
            var selectedschoolyear = $('#selectedschoolyear').val();
            var selectedsemester = $('#selectedsemester').val();
            Swal.fire({
                title: 'Fetching data...',
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                allowOutsideClick: false
            })
            $.ajax({
                url: '/classattendance/showtable',
                type: 'GET',
                data: {
                    levelid  : '{{$gradelevelinfo->id}}',
                    sectionid: '{{$sectioninfo->id}}',
                    version: '2',
                    selectedschoolyear  : selectedschoolyear,
                    selectedsemester  : selectedsemester
                },
                success:function(data){
                    $('#attendancetablecontainer').empty();
                    $('#attendancetablecontainer').append(data)
                    
                    $(".swal2-container").remove();
                    $('body').removeClass('swal2-shown')
                    $('body').removeClass('swal2-height-auto')
                }
            })
        })
    })
</script>
@endsection