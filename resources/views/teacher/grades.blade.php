@extends('teacher.layouts.app')
@section('content')
<style>
    .color-palette                  { display: block; height: 35px; line-height: 35px; text-align: left; padding-left: .75rem; }
    .color-palette.disabled         { text-align: center; padding-right: 0; display: block; }
    .color-palette-set              { margin-bottom: 15px; }
    .color-palette.disabled span    { display: block; text-align: left; padding-left: .75rem; }
    .color-palette-box h4           { position: absolute; left: 1.25rem; margin-top: .75rem; color: rgba(255, 255, 255, 0.8); font-size: 12px; display: block; z-index: 7; }
</style>
<div>
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="active breadcrumb-item">Sections</li>
            <li class="breadcrumb-item"><a href="/summergrades/dashboard">Subjects</a></li>
            <li class="active breadcrumb-item" aria-current="page">Grades</li>
        </ol>
    </nav>
</div>
<form action="/gradesSubmit" method="PUT" id="formSubmit">
    @csrf
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card ">
                <div class="card-header bg-info">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i>
                        SECTIONS
                    </h3>
                </div>
                <div class="card-body" id="alertscontainer">
                    
                    @if(isset($message))
                        <div class="alert alert-warning alert-dismissible">
                            <h5><i class="icon fas fa-exclamation-triangle"></i> {{$message}}</h5>
                            Possible reasons:
                            <ul>
                                <li>No students enrolled</li>
                                <li>No assigned schedule</li>
                            </ul>
                        </div>
                    @endif
                    @if(isset($gradeLevel))
                    @if(count($gradeLevel)==0)
                        <div class="alert alert-info alert-dismissible" id="noAssignedSched">
                            <h5><i class="icon fas fa-info"></i> Alert!</h5>
                            You are not yet assigned to a schedule.
                        </div>
                    @endif
                    <div id="filterPanel">
                        @if(isset($schoolyear))
                            <input type="hidden" id="sy" name="sy" value="{{$schoolyear[0]->id}}" hidden >
                        @endif
                        @php
                            $countQuery= count($gradeLevel);
                        @endphp
                        <input type="hidden" id="countQuery" value="{{$countQuery}}" hidden >
                        <select id="gradeLevel" name="gradeLevel" class="form-control-sm col-md-4 " style="position:relative; display:inline-block" >
                            <option>Grade Level</option>
                            @if(isset($gradeLevel))
                                @if($countQuery!=0)
                                    @foreach($gradeLevel as $level)
                                        <option value="{{$level->id}}" >{{$level->levelname}}</option>
                                    @endforeach
                                @endif
                            @endif
                        </select>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card-body">
            <div class="row">
                <div class="card-deck" id="viewSections">

                </div>
            </div>
        </div>
    </div>
</form>
{{-- <script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script> --}}
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<script>
    var $ = jQuery;
    $(document).ready(function() {
        if($('#countQuery').val() == 0) {
            $('#alertsconteiner').append(
                '<div class="alert alert-info alert-dismissible" id="noAssignedSched">'+
                '<h5><i class="icon fas fa-info"></i> Alert!</h5>'+
                'You are not yet assigned to any subjects.'+
                '</div>'
            );
            $('#filterPanel').hide();
        }
        else{
            $('#filterPanel').show();
            $('#gradeLevel').on('change', function(){
                var gradeLevelId = $(this).val();
                $.ajax({
                    url: '/grades/'+gradeLevelId,
                    type:"GET",
                    dataType:"json",
                    data:{
                        getStudents:'getGradeLevel'
                    },
                    success:function(data) {
                            console.log(data)
                        $('#section').empty();
                        $('#viewSections').empty();
                        $('#section').append('<option value="0">Section</option>');
                        var syid = $('#sy').val();
                        var gradelevelid = $('#gradeLevel').val()
                        $.each(data, function(key, value){
                            // $.each(value, function(keys, values){
                            // console.log(data)
                            $('#viewSections').append(
                                '<a href="/sections/'+value.id+'/'+syid+'/'+gradelevelid+'">'+
                                    '<div class="card mb-3" style="border: 2px solid orange;">'+
                                        '<div class="card-body">'+
                                            '<h5 class="card-title text-muted"><strong>'+value.sectionname+'</strong></h5>'+
                                            '<p class="card-text"><small class="text-muted">Select</small></p>'+
                                        '</div>'+
                                    '</div>'+
                                '</a>'
                            );
                            // });
                        });
                    }
                });
            });
        }
    });
</script>
@endsection