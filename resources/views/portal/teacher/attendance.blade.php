@extends('teacher.layouts.app')

@section('content')
<style>
    input[type=radio]                   { visibility: hidden; position: relative;width: 20px; height: 20px; }

    input[type=radio].present:before    { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio].late:before       { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0;padding: 0; }

    input[type=radio].halfday:before    { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio].absent:before     { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio].present:checked:before    { font-family: "Font Awesome 5 Free";content: "\f00c";color: green;font-size: 20px;border: 1px solid white; }

    input[type=radio].late:checked:before       { background-color: gold; }

    input[type=radio].halfday:checked:before    { background-color: #6c757d; }

    input[type=radio].absent:checked:before     { font-family: "Font Awesome 5 Pro", "Font Awesome 5 Free";content: "\f00d";color: red;font-size: 20px;border: 1px solid white; }

    td{ text-transform: uppercase !important; }

    .tableFixHead       { overflow-y: auto; height: 500px; }

    .tableFixHead table { border-collapse: collapse; width: 100%; }

    .tableFixHead th,
    .tableFixHead td    { /* padding: 8px 16px; */ }

    .tableFixHead th    { position: sticky; top: 0; background: #eee; }
</style>
<div class="row">
    <div class="col-md-12 col-xl-12">
        
        <div class="main-card mb-3 card ">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tasks"></i>
                    Beadle Attendance
                </h3>
            </div>
            <div class="card-body">
                
                @if(isset($message))
                <div class="alert alert-warning alert-dismissible">
                    <h5><i class="icon fas fa-exclamation-triangle"></i>{{$message}}</h5>
                    Possible reasons:
                    <ul>
                        <li>No assigned schedule.</li>
                    </ul>
                </div>
                @endif
                <div id="filterPanel">
                    @if(isset($gradelevel))
                    @php
                        $countQuery= count($gradelevel);
                    @endphp
                    <input type="hidden" id="countQuery" value="{{$countQuery}}" hidden >
                    
                    <form action="/beadleAttendanceUpdate" method="GET">
                            @csrf
                        <div class="col-sm-12">
                            {{-- {{$schoolyear}}
                            @if(isset($schoolyear))
                            <input type="text" id="sy" name="sy" value="{{$schoolyear[0]->id}}" >
                            @endif --}}
                            &nbsp;
                            <select id="gradeLevel" class="form-control-sm col-md-3 " style="position:relative; display:inline-block" >
                                <option>Select Grade Level</option>
                                @if(isset($gradelevel))
                                    @if($countQuery!=0)
                                        @foreach($gradelevel as $level)
                                            <option value="{{$level->id}}" >{{$level->levelname}}</option>
                                        @endforeach
                                    @endif
                                @endif
                            </select>&nbsp;
                            <select id="section" name="section" class="form-control-sm col-md-3 " style="position:relative; display:inline-block" >
                                <option value="0">Select Section</option>
                            </select>&nbsp;
                            <select id="subject" name="subject" class="form-control-sm col-md-3 " style="position:relative; display:inline-block" >
                                <option>Select Subject</option>
                            </select>
                            <br>&nbsp;
                            <div class="col-sm-3 col-xs-12" style="padding: 0px;" id="dateDiv" >
                                <label><small>Date:</small></label>
                                <input type="text" id="currentDate" name="currentDate" width="176" />
                            </div>
                            <br>
                        </div>
                        <div class="col-sm-12">
                            <div class="alert alert-warning alert-dismissible" id="noStudents">
                                <h5><i class="icon fas fa-info"></i> Alert!</h5>
                                    No students enrolled in this subject.
                            </div>
                            <div style="overflow-x:auto;" id="containerDiv">
                                <div class="btn btn-success btn-sm">Present <span id="presentBadge" class="badge badge-pill badge-light"></span></div>
                                <div class="btn btn-warning btn-sm">Late <span id="lateBadge" class="badge badge-pill badge-light"></span></div>
                                <div class="btn btn-danger btn-sm">Absent <span id="absentBadge" class="badge badge-pill badge-light"></span></div>
                                <button type="submit" class="btn btn-success float-right btn-sm" >Save&nbsp;<i class="fa fa-upload"></i>
                                        </button>
                                <br>&nbsp;
                                
                                <div class="tableFixHead">
                                <table  class="table table-bordered" >
                                    <thead>
                                    <tr>
                                        <th width="30%">Name</th>
                                        <th width="10%">Present</th>
                                        <th width="10%">Late</th>
                                        {{-- <th width="10%">Half Day</th> --}}
                                        <th width="10%">Absent</th>
                                        <th width="30%">Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody id="studentsAttendance">
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>  
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<script>
    var $ = jQuery;
    $('#noStudents').hide();
    $('#containerDiv').hide();
    $('#dateDiv').hide();
    $('#noAssignedSched').hide();
    $(document).ready(function() {
        $('#noStudents').hide();
        $('#noAssignedSched').hide();
        if($('#countQuery').val() == 0) {
            $('#noAssignedSched').show();
            $('#filterPanel').hide();
            $('#noStudents').hide();
        }
        else{
            $('#noStudents').hide();
            $('#noAssignedSched').hide();
            $('#filterPanel').show();
            var todayDate = (function(){ 
            var d = new Date();
            var day = d.getDate();
            
            day =  day > 9 ? day : '0' + day ;
            var month = (d.getMonth() + 1);
            month = month > 9 ? month : '0' + month;
            var _value =  d.getFullYear() + '-' + month  + '-' + day ; 
            
            return _value; 
            })();
            $('#currentDate').datepicker({
            format: 'yyyy-mm-dd',
            value: '{{$date}}'
            });
            
            $('#aBtnGroup button').on('click', function() {
                var thisBtn = $(this);
                thisBtn.addClass('active').siblings().removeClass('active');
                var btnText = thisBtn.text();
                if(btnText == 'Present'){
                    document.getElementById("std1").style.backgroundColor="#3ac47d";
                }
                else if(btnText == 'Late'){
                    document.getElementById("std1").style.backgroundColor="orange";
                }
                else if(btnText == 'Absent'){
                    document.getElementById("std1").style.backgroundColor="red";
                }
                var btnValue = thisBtn.val();
                $('#selectedVal').text(btnValue);
            });
                
            // You can use this to set default value
            // It will fire above click event which will do the updates for you
            $('-------').click();
            $('#gradeLevel').on('change', function(){
                $('#dateDiv').hide();
                $('#containerDiv').hide();
                var gradeLevelId = $(this).val();
                $.ajax({
                    url: '/beadleAttendance/'+gradeLevelId,
                    type:"GET",
                    dataType:"json",
                    data:{
                        getStudents:'getGradeLevel'
                        },
                    success:function(data) {
                        
                        $('#subject').empty();
                        $('#subject').append('<option value="0">Select Section</option>');
                        $('#section').empty();
                        $('#section').append('<option value="0">Select Section</option>');
                        $.each(data, function(key, value){
                            $('#section').append('<option value="'+ value.id +'">' + value.sectionname + '</option>');
                        });
                    },
                });
            });
            $('#section').on('change', function(){
                $('#dateDiv').hide();
                $('#containerDiv').hide();
                var sectionID = $(this).val();
                $.ajax({
                    url: '/beadleAttendance/'+sectionID,
                    type:"GET",
                    dataType:"json",
                    data:{
                        getStudents:'getSubjects'
                    },
                    success:function(data) {
                        $('#subject').empty();
                        $('#subject').append('<option value="0">Select Subject</option>');
                        $.each(data, function(key, value){
                            $('#subject').append('<option value="'+ value.id +'">' + value.subjdesc + '</option>');
                        });
                    },
                });
            });
            $('#subject').on('change', function(){
                var date = $('#currentDate').val();
                var sy = $('#sy').val();
                var sectionID = $('#section').val();
                var subjectID = $(this).val();
                $.ajax({
                    url: '/beadleAttendance/'+subjectID,
                    type:"GET",
                    dataType:"json",
                    data:{
                        getStudents:'getStudents',
                        date:date,
                        sy: sy,
                        section_id : sectionID
                    },
                    success:function(data) {
                        // console.log(data)
                        $('#studentsAttendance').empty();
                        var presentCounter = 0; 
                        var absentCounter = 0; 
                        var lateCounter = 0;                         
                        if(data.length == 0){
                            $('#containerDiv').hide();
                            $('#noStudents').show();
                            $('#dateDiv').hide();
                        }else{
                            $('#noStudents').hide();
                            $('#containerDiv').show();
                            $('#dateDiv').show();
                        }
                        $.each(data, function(key, value){
                            if(value[0].remarks == null){
                                var blank = " ";
                            }
                            else if(value[0].remarks != null){
                                var blank = value[0].remarks;
                            }
                            if(value[0].middlename == null){
                                var middlename = " ";
                            }
                            else if(value[0].middlename != null){
                                var middlename = value[0].middlename;
                            }
                            // console.log(blank)

                            $('#studentsAttendance')
                            .append('<tr><td>'+ value[0].lastname+', '+value[0].firstname+' '+middlename+'</td><td scope="row"><center><div class="icheck-success d-inline"><input type="radio" id="radioPrimary1'+value[0].id+'" class="present" value="present" name="'+value[0].id+'"> <label for="radioPrimary1'+value[0].id+'"></label></div></center></td><td scope="row"><center><div class="icheck-warning d-inline"><input type="radio" value="late" class="late" id="radioPrimary2'+value[0].id+'" name="'+value[0].id+'"><label for="radioPrimary2'+value[0].id+'"></label></div></center></td><td scope="row"><center><div class="icheck-danger d-inline"><input type="radio" id="radioPrimary3'+value[0].id+'" class="absent" value="absent" name="'+value[0].id+'"><label for="radioPrimary3'+value[0].id+'"></label></div></center></td><td><textarea class="form-control" style="border:none" name="'+value[0].id+'R" id="remarks" >'+blank+'</textarea></td></tr>');

                            if(value[0].status=="present"){
                                $('input[name="'+value[0].id+'"]').each(function(){
                                    if($(this).attr('class')=="present"){
                                        $(this).attr('checked',true)
                                    }
                                })
                                presentCounter+=1;
                            }
                            else if(value[0].status=="absent"){
                                $('input[name="'+value[0].id+'"]').each(function(){
                                    if($(this).attr('class')=="absent"){
                                        $(this).attr('checked',true)
                                    }
                                })
                                absentCounter+=1;
                            }
                            else if(value[0].status=="late"){
                                $('input[name="'+value[0].id+'"]').each(function(){
                                    if($(this).attr('class')=="late"){
                                        $(this).attr('checked',true)
                                    }
                                })
                                lateCounter+=1;
                            }
                        });
                        $('#presentBadge').text(presentCounter);
                        $('#absentBadge').text(absentCounter);
                        $('#lateBadge').text(lateCounter);
                    },
                });
            }); 
            $('#currentDate').unbind().on('change', function(){
                var getNewDate = $('#currentDate').val();
                var getGradeLevel = $('#gradeLevel').val();
                var getSection = $('#section').val();
                var getSubject = $('#subject').val();
                console.log(getNewDate+' '+getGradeLevel+' '+getSection+' '+getSubject)
                $.ajax({
                    url: '/beadleAttendance/'+getSubject,
                    type:"GET",
                    dataType:"json",
                    data:{
                        getStudents:'getStudents',
                        date:getNewDate,
                        section_id : getSection
                    },
                    success:function(data) {
                        $('#studentsAttendance').empty();
                        var presentCounter = 0; 
                        var absentCounter = 0; 
                        var lateCounter = 0; 
                        $.each(data, function(key, value){
                           console.log(data)
                            if(value[0].remarks == null){
                                var blank = " ";
                            }
                            else if(value[0].remarks != null){
                                var blank = value[0].remarks;
                            }
                            if(value[0].middlename == null){
                                var middlename = " ";
                                // console.log('null');
                            }
                            else if(value[0].middlename != null){
                                // console.log('nulling');
                                var middlename = value[0].middlename;
                            }
                            $('#studentsAttendance')
                            .append('<tr><td>'+ value[0].lastname+', '+value[0].firstname+' '+middlename+'</td><td scope="row"><center><div class="icheck-success d-inline"><input type="radio" id="radioPrimary1'+value[0].id+'" class="present" value="present" name="'+value[0].id+'"> <label for="radioPrimary1'+value[0].id+'"></label></div></center></td><td scope="row"><center><div class="icheck-warning d-inline"><input type="radio" value="late" class="late" id="radioPrimary2'+value[0].id+'" name="'+value[0].id+'"><label for="radioPrimary2'+value[0].id+'"></label></div></center></td><td scope="row"><center><div class="icheck-danger d-inline"><input type="radio" id="radioPrimary3'+value[0].id+'" class="absent" value="absent" name="'+value[0].id+'"><label for="radioPrimary3'+value[0].id+'"></label></div></center></td><td><textarea class="form-control" style="border:none" name="'+value[0].id+'R" id="remarks" >'+blank+'</textarea></td></tr>');

                            if(value[0].status=="present"){
                                $('input[name="'+value[0].id+'"]').each(function(){
                                    if($(this).attr('class')=="present"){
                                        $(this).attr('checked',true)
                                    }
                                })
                                presentCounter+=1;
                            }
                            else if(value[0].status=="absent"){
                                $('input[name="'+value[0].id+'"]').each(function(){
                                    if($(this).attr('class')=="absent"){
                                        $(this).attr('checked',true)
                                    }
                                })
                                absentCounter+=1;
                            }
                            else if(value[0].status=="late"){
                                $('input[name="'+value[0].id+'"]').each(function(){
                                    if($(this).attr('class')=="late"){
                                        $(this).attr('checked',true)
                                    }
                                })
                                lateCounter+=1;
                            }
                        });
                        $('#presentBadge').text(presentCounter);
                        $('#absentBadge').text(absentCounter);
                        $('#lateBadge').text(lateCounter);
                    }
                });
            }); 
        }
    });
</script>
@endsection