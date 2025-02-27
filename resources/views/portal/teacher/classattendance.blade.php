<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
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

    td                  { text-transform: uppercase !important; }

    .tableFixHead       { overflow-y: auto; height: 500px; }

    .tableFixHead table { border-collapse: collapse; width: 100%; }

    .tableFixHead th,
    .tableFixHead td    { /* padding: 8px 16px; */ }

    .tableFixHead th    { position: sticky; top: 0; background-color: #eee; }
    /* thead{
        background-color: #eee !important;
    } */
</style>
@extends('teacher.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card ">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-tasks"></i>
                    Class Attendance
                </h3>
            </div>
            @if($section=='note')
                <div class="card-body">
                    <div class="alert alert-info alert-dismissible" id="noAssignedSched">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                        You are not yet assigned to a section!
                    </div>
                </div>
            @else
                <div class="card-body">
                    <form action="/classAttendance" method="POST">
                        @csrf
                        <div class="col-sm-12">
                            <input  class="form-control-sm" id="section_id" name="section_id" value="{{$section[0]->id}}" hidden/>
                            <h3>
                                <center>
                                    {{$gradelevel[0]->levelname}} - {{$section[0]->sectionname}}
                                </center>
                            </h3>
                            
                            @if(isset($attendance))
                            <div class="col-sm-3 col-xs-12" style="padding: 0px;">
                            {{-- <input  class="form-control-sm" id="datepicker" /> --}}
                                <label>
                                    <small>Date:</small>
                                </label>
                                @if(isset($date))
                                <input type="text" id="currentDate" name="currentDate" width="176" value="{{$date}}"/>
                                @endif
                            </div>
                            @endif
                            <br>
                        </div>
                        
                        @if(isset($attendance))
                            <div class="col-sm-12">
                                <div style="overflow-x:auto;" >
                                    {{-- @if(isset($present)) --}}
                                    <div class="btn btn-success btn-sm">Present <span id="presentBadge" class="badge badge-pill badge-light">{{$present[0]}}</span>
                                    </div>
                                    {{-- @endif
                                    @if(isset($late)) --}}
                                    <div class="btn btn-warning btn-sm">Late <span id="lateBadge" class="badge badge-pill badge-light">{{$late[0]}}</span>
                                    </div>
                                    {{-- @endif
                                    @if(isset($halfday)) --}}
                                    <div class="btn btn-secondary btn-sm">Half Day<span id="halfdayBadge"  class="badge badge-pill badge-light">{{$halfday[0]}}</span>
                                    </div>
                                    {{-- @endif
                                    @if(isset($absent)) --}}
                                    <div class="btn btn-danger btn-sm">Absent <span id="absentBadge" class="badge badge-pill badge-light">{{$absent[0]}}</span>
                                    </div>
                                    {{-- @endif --}}
                                    <button type="submit" class="btn btn-success btn-sm float-right" >Save&nbsp;<i class="fa fa-upload"></i>
                                            </button>
                                    <br>&nbsp;
                                    <div class="tableFixHead">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="th-sm">Name</th>
                                                    <th class="th-sm">Present</th>
                                                    <th class="th-sm">Late</th>
                                                    <th class="th-sm">Half Day</th>
                                                    <th class="th-sm">Absent</th>
                                                    <th class="th-sm">Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody id="studentsAttendance">
                                                {{-- @if(isset($attendance)) --}}
                                                @foreach ($attendance as $student)
                                                    <tr>
                                                        <td>
                                                            {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} 
                                                        </td>
                                                        <td>
                                                            @if ($student->present == 1)
                                                                <center>
                                                                    <div class="icheck-success d-inline">
                                                                        <input type="radio" id="radioPrimary1{{$student->id}}" class="present" value="present" name="{{$student->id}}"checked>
                                                                        <label for="radioPrimary1{{$student->id}}">
                                                                        </label>
                                                                    </div>
                                                                </center>
                                                            @elseif($student->present == 0)
                                                                <center>
                                                                    <div class="icheck-success d-inline">
                                                                        <input type="radio" id="radioPrimary1{{$student->id}}" class="present" value="present" name="{{$student->id}}">
                                                                        <label for="radioPrimary1{{$student->id}}">
                                                                        </label>
                                                                    </div>
                                                                </center>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($student->tardy == 1)
                                                                <center>
                                                                    <div class="icheck-warning d-inline">
                                                                        <input type="radio" id="radioPrimary2{{$student->id}}" class="late" value="late" name="{{$student->id}}"checked>
                                                                        <label for="radioPrimary2{{$student->id}}">
                                                                        </label>
                                                                    </div>
                                                                </center>
                                                            @elseif($student->tardy == 0)
                                                                <center>
                                                                    <div class="icheck-warning d-inline">
                                                                        <input type="radio" id="radioPrimary2{{$student->id}}" class="late" value="late"name="{{$student->id}}">
                                                                        <label for="radioPrimary2{{$student->id}}">
                                                                        </label>
                                                                    </div>
                                                                </center>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($student->cc == 1)
                                                                <center>
                                                                    <div class="icheck-secondary d-inline">
                                                                        <input type="radio" id="radioPrimary3{{$student->id}}" class="halfday" value="halfday" name="{{$student->id}}"checked>
                                                                        <label for="radioPrimary3{{$student->id}}">
                                                                        </label>
                                                                    </div>
                                                                </center>
                                                            @elseif($student->cc == 0)
                                                                <center>
                                                                    <div class="icheck-secondary d-inline">
                                                                        <input type="radio" id="radioPrimary3{{$student->id}}" class="halfday" value="halfday" name="{{$student->id}}">
                                                                        <label for="radioPrimary3{{$student->id}}">
                                                                        </label>
                                                                    </div>
                                                                </center>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($student->absent == 1)
                                                                <center>
                                                                    <div class="icheck-danger d-inline">
                                                                        <input type="radio" id="radioPrimary4{{$student->id}}" class="absent" value="absent" name="{{$student->id}}"checked>
                                                                        <label for="radioPrimary4{{$student->id}}">
                                                                        </label>
                                                                    </div>
                                                                </center>
                                                            @elseif($student->absent == 0)
                                                                <center>
                                                                    <div class="icheck-danger d-inline">
                                                                        <input type="radio" id="radioPrimary4{{$student->id}}" class="absent" value="absent" name="{{$student->id}}">
                                                                        <label for="radioPrimary4{{$student->id}}">
                                                                        </label>
                                                                    </div>
                                                                </center>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <textarea class="form-control" style="border: none" name="{{$student->id}}R" id="remarks">{{$student->remarks}}
                                                        </textarea>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                {{-- @endif --}}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </form>
                    @endif
                    @if(isset($message))
                        <div class="card-body">
                            <div class="alert alert-warning alert-dismissible">
                                <h5><i class="icon fas fa-info"></i> {{$message}}</h5>
                                Possible reasons:
                                <ul>
                                    <li>No students enrolled</li>
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
    <script>
        var $ = jQuery;
        $(document).ready(function() {
            $('.dataTables_length').addClass('bs-select');
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
                value: '{{$date ?? ''}}'
            });
        });
        $(document).ready(function() {
            // Get click event, assign button to var, and get values from that var
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
                // console.log(btnText + ' - ' + btnValue);
                
                $('#selectedVal').text(btnValue);
            });
            
            // You can use this to set default value
            // It will fire above click event which will do the updates for you
            $('-------').click();
            $('#gradeLevel').on('change', function(){
                var gradeLevelId = $(this).val();
                // console.log("HELLO");
                $.ajax({
                    url: '/attendance/'+gradeLevelId,
                    type:"GET",
                    dataType:"json",
                    data:{
                        getStudents:'getGradeLevel'
                    },
                    success:function(data) {
                        $('#section').empty();
                        $.each(data, function(key, value){
                            $('#section').append('<option value="'+ value.id +'">' + value.sectionname + '</option>');
                        });
                    },
                });
            }); 
            $('#currentDate').unbind().on('change', function(){
                var getNewDate = $('#currentDate').val();
                var getSection = $('#section_id').val();
                $.ajax({
                    url: '/classAttendance/'+getNewDate,
                    type:"GET",
                    dataType:"json",
                    data:{
                        newDate:getNewDate,
                        getStudents:'getStudents',
                        getSection:getSection
                    },
                    success:function(data) {
                        $('#studentsAttendance').empty();
                        $('#presentBadge').text('0');
                        $('#lateBadge').text('0');
                        $('#halfdayBadge').text('0');
                        $('#absentBadge').text('0');
                        var present = 0;
                        var late = 0;
                        var halfday = 0;
                        var absent = 0;
                        console.log(data)
                        $.each(data, function(key, value){
                                if(value.present=='1'){
                                    present+=1;
                                }
                                else if(value.tardy=='1'){
                                    late+=1;
                                }
                                else if(value.cc=='1'){
                                    halfday+=1;
                                }
                                else if(value.absent=='1'){
                                    absent+=1;
                                }
                                if(value.remarks==null){
                                    var blank = " ";
                                } else if(value.remarks!="null"){
                                    var blank = value.remarks;
                                }
                                if(value.middlename==null){
                                    var middlename = " ";
                                } else if(value.middlename!=null){
                                    var middlename = value.middlename;
                                }
                                
                                                                    
                                $('#studentsAttendance')
                                .append('<tr><td>'+ value.lastname+', '+value.firstname+' '+middlename+'</td><th scope="row"><center><div class="icheck-success d-inline"><input type="radio" class="present" id="radioPrimary1'+value.id+'" value="present" name="'+value.id+'"><label for="radioPrimary1'+value.id+'"></label></div></center></th><th scope="row"><center><div class="icheck-warning d-inline"><input type="radio" value="late" id="radioPrimary2'+value.id+'" class="late" name="'+value.id+'"><label for="radioPrimary2'+value.id+'"></label></div></center></th><th scope="row"><center><div class="icheck-secondary d-inline"><input type="radio" id="radioPrimary3'+value.id+'" class="halfday" value="halfday" name="'+value.id+'"><label for="radioPrimary3'+value.id+'"></label></div></center></th><th scope="row"><center><div class="icheck-danger d-inline"><input type="radio" id="radioPrimary4'+value.id+'" class="absent" value="absent" name="'+value.id+'"><label for="radioPrimary4'+value.id+'"></label></div></center></th><td><textarea class="form-control" style="border: none" name="'+value.id+'R" id="remarks">'+blank+'</textarea></td></tr>');
                            

                            $('input[name="'+value.id+'"]').each(function(){
                                // console.log($(this).attr('class'));
                            })
                            if(value.present==1){
                                $('input[name="'+value.id+'"]').each(function(){
                                    if($(this).attr('class')=="present"){
                                        $(this).attr('checked',true)
                                    }
                                })
                            }
                            else if(value.absent==1){
                                $('input[name="'+value.id+'"]').each(function(){
                                    if($(this).attr('class')=="absent"){
                                        $(this).attr('checked',true)
                                    }
                                })
                            }
                            else if(value.tardy==1){
                                $('input[name="'+value.id+'"]').each(function(){
                                    if($(this).attr('class')=="late"){
                                        $(this).attr('checked',true)
                                    }
                                })
                            }
                            else if(value.cc==1){
                                $('input[name="'+value.id+'"]').each(function(){
                                    if($(this).attr('class')=="halfday"){
                                        $(this).attr('checked',true)
                                    }
                                })
                            }
                            
                        });
                        $('#presentBadge').text(present);
                        $('#lateBadge').text(late);
                        $('#halfdayBadge').text(halfday);
                        $('#absentBadge').text(absent);
                    }
                });
            }); 
        });
    </script>
@endsection