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

    .tableFixHead th    { position: sticky; top: 0; background: #ffc107; z-index: 999;}
    [data-id=shake]
    {
        animation: blinker 0.5s linear infinite;
        /* animation-iteration-count: infinite; */

    }

    [data-id=shake]:hover, [data-id=shake]:focus {
        animation-play-state: paused;
    }

    @keyframes blinker {
        50% {
            opacity: 0.5;
        }
    }
</style>
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
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Attendance</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="active breadcrumb-item">Attendance</li>
                    <li class="active breadcrumb-item" aria-current="page">Per Subject</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<section class="content-body">
    {{-- <div class="row">
        <div class="col-md-12"> --}}
            
            <div class="card ">
                <div class="card-body">
                    <div id="filterPanel">
                        @if(isset($gradelevel))
                        @php
                            $countQuery= count($gradelevel);
                        @endphp
                        <input type="hidden" id="countQuery" value="{{$countQuery}}" hidden >
                        
                        <form action="/beadleAttendanceUpdate" method="GET">
                                @csrf
                            <div class="row">
                                @if(isset($schoolyear))
                                <input type="hidden" id="sy" name="sy" value="{{$schoolyear[0]->id}}" >
                                @endif
                                <div class="col-md-4">
                                    <label>Grade Level</label>
                                    <select id="gradeLevel" name="gradelevel" class="form-control form-control-sm">
                                        <option>Select Grade Level</option>
                                        @if(isset($gradelevel))
                                            @if($countQuery!=0)
                                                @foreach($gradelevel as $level)
                                                    <option @if($level->id == old('gradelevel')) selected @endif value="{{$level->id}}" >{{$level->levelname}}</option>
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Section</label>
                                    <select id="section" name="section" class="form-control form-control-sm">
                                        <option value="0">Select Section</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>Subject</label>
                                    <select id="subject" name="subject" class="form-control form-control-sm">
                                        <option>Select Subject</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4" id="dateDiv" >
                                    <label>Date:</label><br/>
                                    <input type="date" id="currentDate" class="form-control form-control" name="currentDate" width="176" value="{{date('Y-m-d')}}"/>
                                </div>
                            </div>
                            <div class="row mt-2">
                                {{-- <div class="col-sm-12"> --}}
                                    
                                    <div  class="col-md-12 mb-2">
                                        <div id="alertcontainer"></div>
                                    </div>
                                    <div  class="col-md-12 mb-2" id="detailscontainer">
                                        <div class="btn btn-success btn-sm">Present <span id="presentBadge" class="badge badge-pill badge-light"></span></div>
                                        <div class="btn btn-warning btn-sm">Late <span id="lateBadge" class="badge badge-pill badge-light"></span></div>
                                        <div class="btn btn-danger btn-sm">Absent <span id="absentBadge" class="badge badge-pill badge-light"></span></div>
                                
                                            <button type="submit" id="savebutton" class="btn btn-success float-right btn-sm" >Save&nbsp;<i class="fa fa-upload"></i>
                                            </button>
                                    </div>
                                    <div class="col-md-12" style="overflow-x:auto;height: 600px;" id="containerDiv">
    
                                        <table  class="table tableFixHead" >
                                            <!-- gian -->
                                            <thead class="bg-warning">
                                            <!-- end gian -->
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
                                {{-- </div> --}}
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
                {{-- <div class="card-body">
                    
                    @if(isset($message))
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fas fa-exclamation-triangle"></i>{{$message}}</h5>
                        Possible reasons:
                        <ul>
                            <li>No assigned schedule.</li>
                        </ul>
                    </div>
                    @endif
                </div>   --}}
            </div>
        {{-- </div>
    </div> --}}
</section>
{{-- <script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script> --}}
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script>
    var $ = jQuery;
    $('#noStudents').hide();
    $('#containerDiv').hide();
    $('#dateDiv').hide();
    $('#noAssignedSched').hide();
    $(document).ready(function() {
        $('#detailscontainer').hide();

        @if(old('gradelevel') != null)
            var selected = '';
            $.ajax({
                url: '/beadleAttendance/getsections',
                type:"GET",
                dataType:"json",
                data:{
                    gradelevelid : '{{old('gradelevel')}}'
                    },
                success:function(data) {
                    
                    $('#subject').empty();
                    $('#subject').append('<option value="0">Select Subject</option>');
                    $('#section').empty();
                    $('#section').append('<option value="0">Select Section</option>');
                    $.each(data, function(key, value){
                        if(value.id == '{{old('section')}}'){
                            $('#section').append('<option selected value="'+ value.id +'">' + value.sectionname + '</option>');
                        }
                        else{
                            $('#section').append('<option value="'+ value.id +'">' + value.sectionname + '</option>');
                        }
                    });
                },
            });
        @endif

        @if(old('section') != null)
                $.ajax({
                    url: '/beadleAttendance/getsubjects',
                    type:"GET",
                    dataType:"json",
                    data:{
                        sectionid : '{{old('section')}}'
                    },
                    success:function(data) {
                        $('#subject').empty();
                        $('#subject').append('<option value="0">Select Subject</option>');
                        $.each(data, function(key, value){
                            // console.log('{{old('subject')}}')

                            if(value.id == '{{old('subject')}}'){

                                $('#subject').append('<option selected value="'+ value.id +'">' + value.subjdesc + '</option>');
                            }
                            else{
                                $('#subject').append('<option value="'+ value.id +'">' + value.subjdesc + '</option>');
                            }
                        });

                        @if(old('subject')!=null)
                            loadStudentsList('{{old('subject')}}');
                        @endif

                    },
                });
        @endif
        

        


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
            // $('#currentDate').datepicker({
            // format: 'yyyy-mm-dd',
            // value: '{{$date}}'
            // });
            
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
                $('#noStudents').hide();
                        $('#subject').empty();
                        $('#section').empty();
                        $('#subject').append('<option value="0">Select Subject</option>');
                        $('#section').append('<option value="0">Select Section</option>');
                $('#dateDiv').hide();
                $('#containerDiv').hide();
                var gradelevelid = $(this).val();
                $.ajax({
                    url: '/beadleAttendance/getsections',
                    type:"GET",
                    dataType:"json",
                    data:{
                        gradelevelid:gradelevelid,
                        },
                    success:function(data) {
                        $.each(data, function(key, value){
                            $('#section').append('<option value="'+ value.id +'">' + value.sectionname + '</option>');
                        });
                    },
                });
            });
            $('#section').on('change', function(){
                $('#noStudents').hide();
                $('#dateDiv').hide();
                $('#containerDiv').hide();
                var sectionid = $(this).val();
                $.ajax({
                    url: '/beadleAttendance/getsubjects',
                    type:"GET",
                    dataType:"json",
                    data:{
                        sectionid:sectionid,
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

                loadStudentsList($(this).val());
                $('#currentDate').val(moment().format('YYYY-MM-DD'))

            }); 

            function loadStudentsList(subjid){
                $('#alertcontainer').empty()
                var date = $('#currentDate').val();
                var syid = $('#sy').val();
                var sectionid = $('#section').val();
                var subjectid = subjid;
                $.ajax({
                    url: '/beadleAttendance/getstudents',
                    type:"GET",
                    dataType:"json",
                    data:{
                        subjectid:subjectid,
                        date:date,
                        syid: syid,
                        sectionid : sectionid
                    },
                    success:function(data) {
                        $('#studentsAttendance').empty();
                        var presentCounter = 0; 
                        var absentCounter = 0; 
                        var lateCounter = 0;  
                        var status = 0;                       
                        if(data[0].length == 0){
                            $('#containerDiv').hide();
                            $('#alertcontainer').append(
                                '<div class="alert alert-warning alert-dismissible" id="noStudents">'+
                                    '<h5><i class="icon fas fa-info"></i> Alert!</h5>'+
                                    'No students enrolled in this subject.'+
                                '</div>'
                            )
                            $('#dateDiv').hide();
                        }else{
                            $('#detailscontainer').show();
                            if(data[1] == 1){
                                $('#savebutton').empty();
                                $('#savebutton').removeClass('btn-success');
                                $('#savebutton').addClass('btn-warning');
                                $('#savebutton').append('<i class="fa fa-share"></i> Update');
                                $('#savebutton').removeAttr('data-id','shake')
                            }
                            else if(data[1] == 0){
                                $('#savebutton').empty();
                                $('#savebutton').removeClass('btn-warning');
                                $('#savebutton').addClass('btn-success');
                                $('#savebutton').append('<i class="fa fa-share"></i> Save');
                                $('#savebutton').attr('data-id','shake')
                            }

                            $('#noStudents').remove();
                            $('#containerDiv').show();
                            $('#dateDiv').show();
                        }
                        $('#studentsAttendance').append(
                            '<tr class="bg-info">'+
                                '<td colspan="5"><strong>MALE</strong></td>'+
                            '</tr>'
                        )
                        $.each(data[0], function(key, value){
                            // console.log(value.lastname)
                            if(value.gender.toLowerCase() == 'male')
                            {
                            try{
                                    if(value.remarks == null){
                                        var blank = " ";
                                    }
                                    else if(value.remarks != null){
                                        var blank = value.remarks;
                                    }
                                    if(value.middlename == null){
                                        var middlename = " ";
                                        // console.log('null');
                                    }
                                    else if(value.middlename != null){
                                        // console.log('nulling');
                                        var middlename = value.middlename;
                                    }
                                }
                                catch{
                                    var blank = " ";
                                        var middlename = " ";
                                }

                                try{
                                    if(value.promotionstatus == 1){
                                        var promoted = true;
                                    }
                                }   
                                catch{
                                        var value2 = [];
                                        value2.push(value);
                                        value = value2;
                                }
                                if(value.promotionstatus == 1){
                                status+=1;
                                $('#studentsAttendance').append(
                                    '<tr>'+
                                        '<td> <span class="badge badge-warning" >'+value.description+'</span> '+ value.lastname+', '+value.firstname+' '+middlename+'</td>'+
                                        '<td scope="row">'+
                                            '<center>'+
                                                '<div class="icheck-success d-inline">'+
                                                    '<input type="radio" id="radioPrimary1'+value.id+'" class="present" value="present" name="'+value.id+'" onclick="return false">'+
                                                    '<label for="radioPrimary1'+value.id+'"></label>'+
                                                '</div>'+
                                            '</center>'+
                                        '</td>'+
                                        '<td scope="row">'+
                                            '<center>'+
                                                '<div class="icheck-warning d-inline">'+
                                                    '<input type="radio" value="late" class="late" id="radioPrimary2'+value.id+'" name="'+value.id+'" onclick="return false">'+
                                                    '<label for="radioPrimary2'+value.id+'"></label>'+
                                                '</div>'+
                                            '</center>'+
                                        '</td>'+
                                        '<td scope="row">'+
                                            '<center>'+
                                                '<div class="icheck-danger d-inline">'+
                                                    '<input type="radio" id="radioPrimary3'+value.id+'" class="absent" value="absent" name="'+value.id+'" onclick="return false">'+
                                                    '<label for="radioPrimary3'+value.id+'"></label>'+
                                                '</div>'+
                                            '</center>'+
                                        '</td>'+
                                        '<td>'+
                                            '<textarea class="form-control" style="border:none" name="'+value.id+'R" id="remarks" readonly >'+blank+'</textarea>'+
                                        '</td>'+
                                    '</tr>'
                                    );
                                }
                                else{
                                $('#studentsAttendance').append(
                                    '<tr>'+
                                        '<td> <span class="badge badge-warning" >'+value.description+'</span> '+ value.lastname+', '+value.firstname+' '+middlename+'</td>'+
                                        '<td scope="row">'+
                                            '<center>'+
                                                '<div class="icheck-success d-inline">'+
                                                    '<input type="radio" id="radioPrimary1'+value.id+'" class="present" value="present" name="'+value.id+'">'+
                                                    '<label for="radioPrimary1'+value.id+'"></label>'+
                                                '</div>'+
                                            '</center>'+
                                        '</td>'+
                                        '<td scope="row">'+
                                            '<center>'+
                                                '<div class="icheck-warning d-inline">'+
                                                    '<input type="radio" value="late" class="late" id="radioPrimary2'+value.id+'" name="'+value.id+'">'+
                                                    '<label for="radioPrimary2'+value.id+'"></label>'+
                                                '</div>'+
                                            '</center>'+
                                        '</td>'+
                                        '<td scope="row">'+
                                            '<center>'+
                                                '<div class="icheck-danger d-inline">'+
                                                    '<input type="radio" id="radioPrimary3'+value.id+'" class="absent" value="absent" name="'+value.id+'">'+
                                                    '<label for="radioPrimary3'+value.id+'"></label>'+
                                                '</div>'+
                                            '</center>'+
                                        '</td>'+
                                        '<td>'+
                                            '<textarea class="form-control" style="border:none" name="'+value.id+'R" id="remarks" >'+blank+'</textarea>'+
                                        '</td>'+
                                    '</tr>'
                                    );
                                }

                                if(value.status=="present"){
                                    $('input[name="'+value.id+'"]').each(function(){
                                        if($(this).attr('class')=="present"){
                                            $(this).attr('checked',true)
                                        }
                                    })
                                    presentCounter+=1;
                                }
                                else if(value.status=="absent"){
                                    $('input[name="'+value.id+'"]').each(function(){
                                        if($(this).attr('class')=="absent"){
                                            $(this).attr('checked',true)
                                        }
                                    })
                                    absentCounter+=1;
                                }
                                else if(value.status=="late"){
                                    $('input[name="'+value.id+'"]').each(function(){
                                        if($(this).attr('class')=="late"){
                                            $(this).attr('checked',true)
                                        }
                                    })
                                    lateCounter+=1;
                                }
                            }
                        });
                        $('#studentsAttendance').append(
                            '<tr class="bg-pink">'+
                                '<td colspan="5"><strong>FEMALE</strong></td>'+
                            '</tr>'
                        )
                        $.each(data[0], function(key, value){
                            // console.log(value.lastname)
                            if(value.gender.toLowerCase() == 'female')
                            {
                            try{
                                    if(value.remarks == null){
                                        var blank = " ";
                                    }
                                    else if(value.remarks != null){
                                        var blank = value.remarks;
                                    }
                                    if(value.middlename == null){
                                        var middlename = " ";
                                        // console.log('null');
                                    }
                                    else if(value.middlename != null){
                                        // console.log('nulling');
                                        var middlename = value.middlename;
                                    }
                                }
                                catch{
                                    var blank = " ";
                                        var middlename = " ";
                                }

                                try{
                                    if(value.promotionstatus == 1){
                                        var promoted = true;
                                    }
                                }   
                                catch{
                                        var value2 = [];
                                        value2.push(value);
                                        value = value2;
                                }
                                if(value.promotionstatus == 1){
                                status+=1;
                                $('#studentsAttendance').append(
                                    '<tr>'+
                                        '<td> <span class="badge badge-warning" >'+value.description+'</span> '+ value.lastname+', '+value.firstname+' '+middlename+'</td>'+
                                        '<td scope="row">'+
                                            '<center>'+
                                                '<div class="icheck-success d-inline">'+
                                                    '<input type="radio" id="radioPrimary1'+value.id+'" class="present" value="present" name="'+value.id+'" onclick="return false">'+
                                                    '<label for="radioPrimary1'+value.id+'"></label>'+
                                                '</div>'+
                                            '</center>'+
                                        '</td>'+
                                        '<td scope="row">'+
                                            '<center>'+
                                                '<div class="icheck-warning d-inline">'+
                                                    '<input type="radio" value="late" class="late" id="radioPrimary2'+value.id+'" name="'+value.id+'" onclick="return false">'+
                                                    '<label for="radioPrimary2'+value.id+'"></label>'+
                                                '</div>'+
                                            '</center>'+
                                        '</td>'+
                                        '<td scope="row">'+
                                            '<center>'+
                                                '<div class="icheck-danger d-inline">'+
                                                    '<input type="radio" id="radioPrimary3'+value.id+'" class="absent" value="absent" name="'+value.id+'" onclick="return false">'+
                                                    '<label for="radioPrimary3'+value.id+'"></label>'+
                                                '</div>'+
                                            '</center>'+
                                        '</td>'+
                                        '<td>'+
                                            '<textarea class="form-control" style="border:none" name="'+value.id+'R" id="remarks" readonly >'+blank+'</textarea>'+
                                        '</td>'+
                                    '</tr>'
                                    );
                                }
                                else{
                                $('#studentsAttendance').append(
                                    '<tr>'+
                                        '<td> <span class="badge badge-warning" >'+value.description+'</span> '+ value.lastname+', '+value.firstname+' '+middlename+'</td>'+
                                        '<td scope="row">'+
                                            '<center>'+
                                                '<div class="icheck-success d-inline">'+
                                                    '<input type="radio" id="radioPrimary1'+value.id+'" class="present" value="present" name="'+value.id+'">'+
                                                    '<label for="radioPrimary1'+value.id+'"></label>'+
                                                '</div>'+
                                            '</center>'+
                                        '</td>'+
                                        '<td scope="row">'+
                                            '<center>'+
                                                '<div class="icheck-warning d-inline">'+
                                                    '<input type="radio" value="late" class="late" id="radioPrimary2'+value.id+'" name="'+value.id+'">'+
                                                    '<label for="radioPrimary2'+value.id+'"></label>'+
                                                '</div>'+
                                            '</center>'+
                                        '</td>'+
                                        '<td scope="row">'+
                                            '<center>'+
                                                '<div class="icheck-danger d-inline">'+
                                                    '<input type="radio" id="radioPrimary3'+value.id+'" class="absent" value="absent" name="'+value.id+'">'+
                                                    '<label for="radioPrimary3'+value.id+'"></label>'+
                                                '</div>'+
                                            '</center>'+
                                        '</td>'+
                                        '<td>'+
                                            '<textarea class="form-control" style="border:none" name="'+value.id+'R" id="remarks" >'+blank+'</textarea>'+
                                        '</td>'+
                                    '</tr>'
                                    );
                                }

                                if(value.status=="present"){
                                    $('input[name="'+value.id+'"]').each(function(){
                                        if($(this).attr('class')=="present"){
                                            $(this).attr('checked',true)
                                        }
                                    })
                                    presentCounter+=1;
                                }
                                else if(value.status=="absent"){
                                    $('input[name="'+value.id+'"]').each(function(){
                                        if($(this).attr('class')=="absent"){
                                            $(this).attr('checked',true)
                                        }
                                    })
                                    absentCounter+=1;
                                }
                                else if(value.status=="late"){
                                    $('input[name="'+value.id+'"]').each(function(){
                                        if($(this).attr('class')=="late"){
                                            $(this).attr('checked',true)
                                        }
                                    })
                                    lateCounter+=1;
                                }
                            }
                        });
                        if(data.length == status){
                            $('#savebutton').remove();
                        }
                        $('#presentBadge').text(presentCounter);
                        $('#absentBadge').text(absentCounter);
                        $('#lateBadge').text(lateCounter);
                    },
                });
            }

            $('#currentDate').unbind().on('change', function(){
                var getNewDate = $('#currentDate').val();
                var getGradeLevel = $('#gradeLevel').val();
                var getSection = $('#section').val();
                var getSubject = $('#subject').val();
                var syid = $('#sy').val();
                $.ajax({
                    url: '/beadleAttendance/getstudents',
                    type:"GET",
                    dataType:"json",
                    data:{
                        date:getNewDate,
                        subjectid:getSubject,
                        syid: syid,
                        sectionid : getSection
                    },
                    success:function(data) {
                        // console.log(data)
                        $('#studentsAttendance').empty();
                        var presentCounter = 0; 
                        var absentCounter = 0; 
                        var lateCounter = 0; 
                        var status = 0;
                        if(data[1] == 1){
                            $('#savebutton').empty();
                            $('#savebutton').removeClass('btn-success');
                            $('#savebutton').addClass('btn-warning');
                            $('#savebutton').append('<i class="fa fa-share"></i> Update');
                            $('#savebutton').removeAttr('data-id','shake')
                        }
                        else if(data[1] == 0){
                            $('#savebutton').empty();
                            $('#savebutton').removeClass('btn-warning');
                            $('#savebutton').addClass('btn-success');
                            $('#savebutton').append('<i class="fa fa-share"></i> Save');
                            $('#savebutton').attr('data-id','shake')
                        }
                        
                        $('#studentsAttendance').append(
                            '<tr class="bg-info">'+
                                '<td colspan="5"><strong>MALE</strong></td>'+
                            '</tr>'
                        )
                        $.each(data[0], function(key, value){
                            if(value.gender.toLowerCase() == 'male')
                            {
                                try{
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
                                    }
                                    catch{
                                        var blank = " ";
                                            var middlename = " ";
                                    }

                                    try{
                                        if(value[0].promotionstatus == 1){
                                            var promoted = true;
                                        }
                                    }   
                                    catch{
                                            var value2 = [];
                                            value2.push(value);
                                            value = value2;
                                    }
                                    // console.log()

                                    if(value[0].promotionstatus == 1){
                                    $('#studentsAttendance')
                                    .append(
                                        '<tr>'+
                                            '<td> <span class="badge badge-warning" >'+value[0].description+'</span> '+ value[0].lastname+', '+value[0].firstname+' '+middlename+'</td>'+
                                            '<td scope="row">'+
                                                '<center>'+
                                                    '<div class="icheck-success d-inline">'+
                                                        '<input type="radio" id="radioPrimary1'+value[0].id+'" class="present" value="present" name="'+value[0].id+'" onclick="return false">'+
                                                        '<label for="radioPrimary1'+value[0].id+'"></label>'+
                                                    '</div>'+
                                                '</center>'+
                                            '</td>'+
                                            '<td scope="row">'+
                                                '<center>'+
                                                    '<div class="icheck-warning d-inline">'+
                                                        '<input type="radio" value="late" class="late" id="radioPrimary2'+value[0].id+'" name="'+value[0].id+'" onclick="return false">'+
                                                        '<label for="radioPrimary2'+value[0].id+'"></label>'+
                                                    '</div>'+
                                                '</center>'+
                                            '</td>'+
                                            '<td scope="row">'+
                                                '<center>'+
                                                    '<div class="icheck-danger d-inline">'+
                                                        '<input type="radio" id="radioPrimary3'+value[0].id+'" class="absent" value="absent" name="'+value[0].id+'" onclick="return false">'+
                                                        '<label for="radioPrimary3'+value[0].id+'"></label>'+
                                                    '</div>'+
                                                '</center>'+
                                            '</td>'+
                                            '<td>'+
                                                '<textarea class="form-control" style="border:none" name="'+value[0].id+'R" id="remarks" readonly>'+blank+'</textarea>'+
                                            '</td>'+
                                            '</tr>'
                                            );
                                    }
                                    else{
                                    $('#studentsAttendance')
                                    .append(
                                        '<tr>'+
                                            '<td> <span class="badge badge-warning" >'+value[0].description+'</span> '+ value[0].lastname+', '+value[0].firstname+' </td>'+
                                            '<td scope="row">'+
                                                '<center>'+
                                                    '<div class="icheck-success d-inline">'+
                                                        '<input type="radio" id="radioPrimary1'+value[0].id+'" class="present" value="present" name="'+value[0].id+'">'+
                                                        '<label for="radioPrimary1'+value[0].id+'"></label>'+
                                                    '</div>'+
                                                '</center>'+
                                            '</td>'+
                                            '<td scope="row">'+
                                                '<center>'+
                                                    '<div class="icheck-warning d-inline">'+
                                                        '<input type="radio" value="late" class="late" id="radioPrimary2'+value[0].id+'" name="'+value[0].id+'">'+
                                                        '<label for="radioPrimary2'+value[0].id+'"></label>'+
                                                    '</div>'+
                                                '</center>'+
                                            '</td>'+
                                            '<td scope="row">'+
                                                '<center>'+
                                                    '<div class="icheck-danger d-inline">'+
                                                        '<input type="radio" id="radioPrimary3'+value[0].id+'" class="absent" value="absent" name="'+value[0].id+'">'+
                                                        '<label for="radioPrimary3'+value[0].id+'"></label>'+
                                                    '</div>'+
                                                '</center>'+
                                            '</td>'+
                                            '<td>'+
                                                '<textarea class="form-control" style="border:none" name="'+value[0].id+'R" id="remarks" >'+blank+'</textarea>'+
                                            '</td>'+
                                            '</tr>'
                                            );
                                    }

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
                            }
                        });
                        $('#studentsAttendance').append(
                            '<tr class="bg-pink">'+
                                '<td colspan="5"><strong>FEMALE</strong></td>'+
                            '</tr>'
                        )
                        $.each(data[0], function(key, value){
                            if(value.gender.toLowerCase() == 'female')
                            {
                                try{
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
                                    }
                                    catch{
                                        var blank = " ";
                                            var middlename = " ";
                                    }

                                    try{
                                        if(value[0].promotionstatus == 1){
                                            var promoted = true;
                                        }
                                    }   
                                    catch{
                                            var value2 = [];
                                            value2.push(value);
                                            value = value2;
                                    }
                                    // console.log()

                                    if(value[0].promotionstatus == 1){
                                    $('#studentsAttendance')
                                    .append(
                                        '<tr>'+
                                            '<td> <span class="badge badge-warning" >'+value[0].description+'</span> '+ value[0].lastname+', '+value[0].firstname+' '+middlename+'</td>'+
                                            '<td scope="row">'+
                                                '<center>'+
                                                    '<div class="icheck-success d-inline">'+
                                                        '<input type="radio" id="radioPrimary1'+value[0].id+'" class="present" value="present" name="'+value[0].id+'" onclick="return false">'+
                                                        '<label for="radioPrimary1'+value[0].id+'"></label>'+
                                                    '</div>'+
                                                '</center>'+
                                            '</td>'+
                                            '<td scope="row">'+
                                                '<center>'+
                                                    '<div class="icheck-warning d-inline">'+
                                                        '<input type="radio" value="late" class="late" id="radioPrimary2'+value[0].id+'" name="'+value[0].id+'" onclick="return false">'+
                                                        '<label for="radioPrimary2'+value[0].id+'"></label>'+
                                                    '</div>'+
                                                '</center>'+
                                            '</td>'+
                                            '<td scope="row">'+
                                                '<center>'+
                                                    '<div class="icheck-danger d-inline">'+
                                                        '<input type="radio" id="radioPrimary3'+value[0].id+'" class="absent" value="absent" name="'+value[0].id+'" onclick="return false">'+
                                                        '<label for="radioPrimary3'+value[0].id+'"></label>'+
                                                    '</div>'+
                                                '</center>'+
                                            '</td>'+
                                            '<td>'+
                                                '<textarea class="form-control" style="border:none" name="'+value[0].id+'R" id="remarks" readonly>'+blank+'</textarea>'+
                                            '</td>'+
                                            '</tr>'
                                            );
                                    }
                                    else{
                                    $('#studentsAttendance')
                                    .append(
                                        '<tr>'+
                                            '<td> <span class="badge badge-warning" >'+value[0].description+'</span> '+ value[0].lastname+', '+value[0].firstname+' </td>'+
                                            '<td scope="row">'+
                                                '<center>'+
                                                    '<div class="icheck-success d-inline">'+
                                                        '<input type="radio" id="radioPrimary1'+value[0].id+'" class="present" value="present" name="'+value[0].id+'">'+
                                                        '<label for="radioPrimary1'+value[0].id+'"></label>'+
                                                    '</div>'+
                                                '</center>'+
                                            '</td>'+
                                            '<td scope="row">'+
                                                '<center>'+
                                                    '<div class="icheck-warning d-inline">'+
                                                        '<input type="radio" value="late" class="late" id="radioPrimary2'+value[0].id+'" name="'+value[0].id+'">'+
                                                        '<label for="radioPrimary2'+value[0].id+'"></label>'+
                                                    '</div>'+
                                                '</center>'+
                                            '</td>'+
                                            '<td scope="row">'+
                                                '<center>'+
                                                    '<div class="icheck-danger d-inline">'+
                                                        '<input type="radio" id="radioPrimary3'+value[0].id+'" class="absent" value="absent" name="'+value[0].id+'">'+
                                                        '<label for="radioPrimary3'+value[0].id+'"></label>'+
                                                    '</div>'+
                                                '</center>'+
                                            '</td>'+
                                            '<td>'+
                                                '<textarea class="form-control" style="border:none" name="'+value[0].id+'R" id="remarks" >'+blank+'</textarea>'+
                                            '</td>'+
                                            '</tr>'
                                            );
                                    }

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