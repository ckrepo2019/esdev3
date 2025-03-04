
@extends('teacher.layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css')}}">
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<style>
        /* .tscroll                    { max-width: 100%; overflow-x: scroll; margin-bottom: 10px; border: solid black 1px; font-size: 90%; height: 500px; */
        /* } */
        .table                      { font-size:90%; text-transform: uppercase; }
        /* .table thead th:first-child { position: sticky; left: 0; background-color: #fff; } */
        .table thead th:last-child  { position: sticky; right: 0; background-color: #fff; }
        /* .table tbody td:first-child { position: sticky; left: 0; background-color: #fff; } */
        .table tbody td:last-child  { position: sticky; right: 0; background-color: #fff; }
        /* .table #stud, #hps          { position: sticky; left: 0; background-color: #ddd; }
        td, th                      { border-bottom: dashed #888 1px; font-size: 80%; border: 1px solid #ddd; } */
        @media screen and (max-width: 1000px) {
            #pg                     { text-align: left; margin: 0px; }
            #passinggrade           { width: 70%; /* margin: 20px 0px 0px 0px; */ }
        } 
        td{
            min-width: 30px;
        }
        .toast-top-right {
            top: 20%;
            margin-right: 21px;
        }

    </style>
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div>
                    <input id="syid" name="syid" value="{{$schoolyearid}}" hidden>
                    <input id="gradelevelid" name="gradelevelid" value="{{$gradeLevelid}}" hidden>
                    <input id="sectionid" name="sectionid" value="{{$sectionid}}" hidden>
                    <input id="subjectid" name="subjectid" value="{{$subjectid}}" hidden>
                    <div class="page-title-subheading">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <nav class="" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/grades">Sections</a></li>
                <li class="breadcrumb-item"><a href="/sections/{{$sectionid}}/{{$schoolyearid}}/{{$gradeLevelid}}">Subjects</a></li>
                <li class="active breadcrumb-item" aria-current="page">Grades</li>
            </ol>
        </nav>
    </div>
    <div class="card ">
        <div class="card-body">
            <h5 class="card-title"><strong>{{$sectionname}} / {{$subjectname}}</strong></h5>
            <div class="btn-actions-pane-lefy">
                <div role="group" class="btn-group-sm btn-group float-right">
                    <button name="quarter" value="1" class="btn btn-success">1st Quarter</button>
                    <button name="quarter" value="2" class="btn btn-success">2nd Quarter</button>
                    <button name="quarter" value="3" class="btn btn-success">3rd Quarter</button>
                    <button name="quarter" value="4" class="btn btn-success">4th Quarter</button>
                </div>
            </div>
        </div>
    </div>
    {{-- @if(session()->has('submitted')) --}}

    {{-- @endif --}}
    <div id="messageContainer">
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="btn-actions-pane-lefy">
                        <div role="group" class="btn-group-sm btn-group float-left">
                            <label id="pg" style="display:block;position:relative" class="col-md-6">Passing Grade:</span></label>&nbsp;&nbsp;
                            <input type="text" name="passinggrade" id="passinggrade" class="form-control form-control-sm col-md-4" style="position:relative; display:inline">
                        </div>
                    </div>
                </div>
                <div class="card-body" id="alertscontainer">
                    <div id="filterPanel">
                        {{-- <div class="card-body table-responsive p-0" style="height: 500px;">
                        </div> --}}
                        <div id="tableContainer">

                            <h3 class="text-center" style="color: #959596">No quarter selected!</h3>

                        </div>
                        <div class="d-block text-center card-footer" id="divForm">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
    <script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-fixedcolumns/js/fixedColumns.bootstrap4.js')}}"></script>
    
    <!-- jQuery -->
    {{-- <script type="text/javascript" src="{{asset('plugins/jquery/jquery.min.js')}}"></script> --}}
    <script type="text/javascript" src="{{asset('plugins/datatables-fixedcolumns/js/fixedColumns.bootstrap4.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script type="text/javascript" src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- Toastr -->
    <script type="text/javascript" src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
    <script>
    </script>
    <script>
        $('#passinggrade').hide();
        $('#pg').hide();
        $(document).on('click','td[contenteditable=true]',function(){
            var idtd = $(this).attr('id');
            var classtd = $(this).attr('class');
            var firstClass = classtd.slice(0, 3);
            var secondClass = classtd.substr(classtd.length - 10);
            var start = $('td#'+idtd+'.'+firstClass+'.'+secondClass)[0];
            console.log(secondClass)
            start.focus();
            // start.css('background-color','#16aaffe8')
            start.style.setProperty("background-color", "white", "important");
            start.style.color = 'black';

            function dotheneedful(sibling) {
                if (sibling != null) {
                    start.focus();
                    start.style.backgroundColor = '';
                    start.style.color = '';
                    sibling.focus();
                    sibling.style.setProperty("background-color", "white", "important");
                    sibling.style.color = 'black';
                    start = sibling;
                }
            }

            document.onkeydown = checkKey;

            function checkKey(e) {
                e = e || window.event;
                if (e.keyCode == '38') {
                    // up arrow
                    var idx = start.cellIndex;
                    var nextrow = start.parentElement.previousElementSibling;
                    if (nextrow != null) {
                    var sibling = nextrow.cells[idx];
                    dotheneedful(sibling);
                    }
                } else if (e.keyCode == '40') {
                    // down arrow
                    var idx = start.cellIndex;
                    var nextrow = start.parentElement.nextElementSibling;
                    if (nextrow != null) {
                    var sibling = nextrow.cells[idx];
                    dotheneedful(sibling);
                    }
                } else if (e.keyCode == '37') {
                    // left arrow
                    var sibling = start.previousElementSibling;
                    dotheneedful(sibling);
                } else if (e.keyCode == '39') {
                    // right arrow
                    var sibling = start.nextElementSibling;
                    dotheneedful(sibling);
                }
            }
        })
        $(document).ready(function() {
            function dataTable(){
                var table = $('#example1').DataTable( {
                    scrollY:        "300px",
                    scrollX:        true,
                    scrollCollapse: true,
                    ordering: false,
                    paging:         false,
                    info:     false,
                    fixedColumns:   {
                        leftColumns: 1,
                        rightColumns: 1
                    },
                    columnDefs: [
                        { width: 150, targets: 0 }
                    ],
                    fixedColumns: true,
                    searching: false
                
                } );
                    
            }


            $('button[name=quarter]').on('click', function() {
                $(this).siblings().removeClass('active')
                $(this).addClass('active');
                var syid= $('#syid').val();
                var gradelevelid= $('#gradelevelid').val();
                var sectionid= $('#sectionid').val();
                var subjectid= $('#subjectid').val();
                var quarter = $(this).val();
                $.ajax({
                        url: '/getgrades/'+subjectid,
                        type:"GET",
                        dataType:"json",
                        data:{
                        syid: syid,
                        gradelevelid:gradelevelid,
                        sectionid: sectionid,
                        subjectid :subjectid,
                        quarter :quarter
                    },
                    success:function(data) {
                        $('#divForm').empty();
                        $('#header').empty();
                        $('#body').empty();
                        $('#btnSubmit').hide();
                        console.log(data)
                        if(data == '1' ){
                            $('#alertscontainer').empty();
                            $('#alertscontainer').append(
                                '<div class="alert alert-info alert-dismissible" id="noAssignedSetup">'+
                                '<h5><i class="icon fas fa-info"></i> Alert!</h5>'+
                                'Grading setup is not yet configured.'+
                                '</div>'
                            );
                        }
                        // else{
                        // if(data[0].length == 0) {
                        //     $('#noAssignedSetup').show();
                        //     $('#passinggrade').hide();
                        //     $('#pg').hide();
                        //     $('#noAssignedStudents').hide();
                        // }
                        else{
                            // console.log(data[0])
                            // console.log(data);
                            if(data[1].length == 0){
                                $('#passinggrade').hide();
                                $('#pg').hide();
                                $('#alertscontainer').empty()
                                $('#alertscontainer').append(
                                    ' <div class="alert alert-warning alert-dismissible" id="noAssignedStudents">'+
                                    '<h5><i class="icon fas fa-info"></i> Alert!</h5>'+
                                    'No assigned students.'+
                                    '</div>'
                                );
                            }
                            else if(data[1].length > 0){
                                // console.log(data[2][0].wwhr1)
                                $.each(data[0], function(key, value){
                                    $('#pg').show();
                                    $('#btnSubmit').hide();
                                    $('#passinggrade').show();
                                    $('#header').empty();
                                    $('#tableContainer').empty();
                                    $('#tableContainer').append(
                                        '<table class="table table-head-fixed" id="example1">'+
                                            '<thead id="header">'+
                                                '<tr>'+
                                                    '<th id="stud" rowspan="2" style="width:20px">Student</th>'+
                                                    '<th colspan="13" style="background-color:#16aaffe8" >'+
                                                        '<center>WRITTEN WORKS ('+value.writtenworks+'%)</center>'+
                                                    '</th>'+
                                                    '<th colspan="13" style="background-color:#d4a3e6" >'+
                                                        '<center>PEFORMANCE TASK ('+value.performancetask+'%)</center>'+
                                                    '</th>'+
                                                    '<th colspan="3" style="background-color:3ac47d" >'+
                                                        '<center>QA ('+value.qassesment+'%)</center>'+
                                                    '</th>'+
                                                    '<th rowspan="2" style="background-color: #ffdc89" >IG</th>'+
                                                    '<th rowspan="2" style="background-color: #ffdc89">QG</th>'+
                                                '</tr>'+
                                                '<tr>'+
                                                    '<th>1</th>'+
                                                    '<th>2</th>'+
                                                    '<th>3</th>'+
                                                    '<th>4</th>'+
                                                    '<th>5</th>'+
                                                    '<th>6</th>'+
                                                    '<th>7</th>'+
                                                    '<th>8</th>'+
                                                    '<th>9</th>'+
                                                    '<th>10</th>'+
                                                    '<th>TOTAL</th>'+
                                                    '<th>PS</th>'+
                                                    '<th>WS</th>'+
                                                    '<th>1</th>'+
                                                    '<th>2</th>'+
                                                    '<th>3</th>'+
                                                    '<th>4</th>'+
                                                    '<th>5</th>'+
                                                    '<th>6</th>'+
                                                    '<th>7</th>'+
                                                    '<th>8</th>'+
                                                    '<th>9</th>'+
                                                    '<th>10</th>'+
                                                    '<th>TOTAL</th>'+
                                                    '<th>PS</th>'+
                                                    '<th>WS</th>'+
                                                    '<th>1</th>'+
                                                    '<th>PS</th>'+
                                                    '<th>WS</th>'+
                                                '</tr>'+
                                                '<tr id="'+data[1][0].headerid+'" style="" >'+
                                                    '<th id="hps" style="padding:2px;border-top: 2px solid white" >Highest Possible Score</th>'+
                                                    '<th contenteditable="true" class="wwhr1">'+data[2][0].wwhr1+'</th>'+
                                                    '<th contenteditable="true" class="wwhr2">'+data[2][0].wwhr2+'</th>'+
                                                    '<th contenteditable="true" class="wwhr3">'+data[2][0].wwhr3+'</th>'+
                                                    '<th contenteditable="true" class="wwhr4">'+data[2][0].wwhr4+'</th>'+
                                                    '<th contenteditable="true" class="wwhr5">'+data[2][0].wwhr5+'</th>'+
                                                    '<th contenteditable="true" class="wwhr6">'+data[2][0].wwhr6+'</th>'+
                                                    '<th contenteditable="true" class="wwhr7">'+data[2][0].wwhr7+'</th>'+
                                                    '<th contenteditable="true" class="wwhr8">'+data[2][0].wwhr8+'</th>'+
                                                    '<th contenteditable="true" class="wwhr9">'+data[2][0].wwhr9+'</th>'+
                                                    '<th contenteditable="true" class="wwhr0">'+data[2][0].wwhr0+'</th>'+
                                                    '<th id="totalWW" >'+data[3]+'</th>'+
                                                    '<th >100.00</th>'+
                                                    '<th>'+value.writtenworks+'</th>'+
                                                    '<th contenteditable="true" class="pthr1">'+data[2][0].pthr1+'</th>'+
                                                    '<th contenteditable="true" class="pthr2">'+data[2][0].pthr2+'</th>'+
                                                    '<th contenteditable="true" class="pthr3">'+data[2][0].pthr3+'</th>'+
                                                    '<th contenteditable="true" class="pthr4">'+data[2][0].pthr4+'</th>'+
                                                    '<th contenteditable="true" class="pthr5">'+data[2][0].pthr5+'</th>'+
                                                    '<th contenteditable="true" class="pthr6">'+data[2][0].pthr6+'</th>'+
                                                    '<th contenteditable="true" class="pthr7">'+data[2][0].pthr7+'</th>'+
                                                    '<th contenteditable="true" class="pthr8">'+data[2][0].pthr8+'</th>'+
                                                    '<th contenteditable="true" class="pthr9">'+data[2][0].pthr9+'</th>'+
                                                    '<th contenteditable="true" class="pthr0">'+data[2][0].pthr0+'</th>'+
                                                    '<th id="totalPT">'+data[7]+'</th>'+
                                                    '<th>100.00</th>'+
                                                    '<th>'+value.performancetask+'</th >'+
                                                    '<th contenteditable="true" class="qahr1">'+data[2][0].qahr1+'</th>'+
                                                    '<th>100.00</th>'+
                                                    '<th>'+value.qassesment+'</th >'+
                                                    '<th style="background-color: #ffdc89" ></th >'+
                                                    '<th style="background-color: #ffdc89" ></th>'+
                                                '</tr>'+
                                            '</thead>'+
                                            '<tbody id="body">'+

                                            '</tbody>'+
                                        '</table>'
                                        )

                                    $('#passinggrade').val(data[2][0].passinggrade);


                                    for(var x = 0 ; x <= 9 ; x++){
                                        $('.wwhr'+x.toString()).each(function(){
                                            if($(this).text()=='0'){
                                                // $(this).attr('class',''+('wwhr'+x.toString())+' bg-info' );
                                                $(this).css('backgroundColor','#16aaffe8')
                                            }
                                            else{
                                                $(this).css('background-color', '#16aaffe8');
                                            }
                                        })
                                    }
                                    for(var x = 0 ; x <= 9 ; x++){
                                        $('.pthr'+x.toString()).each(function(){
                                            if($(this).text()=='0'){
                                                $(this).css('backgroundColor','#d4a3e6')
                                            }
                                            else{
                                                $(this).css('background-color', '#d4a3e6');
                                            }
                                        })
                                    }
                                    $('.qahr1').each(function(){
                                        if($(this).text()=='0'){
                                                $(this).css('backgroundColor','#3ac47d')
                                        }
                                        else{
                                                $(this).css('background-color', '#3ac47d');
                                        }
                                    })
                                });
                        
                                var wwtotalIndex = 0;
                                var wwpsIndex = 0;
                                var wwwsIndex = 0;
                                var pttotalIndex = 0;
                                var ptpsIndex = 0;
                                var ptwsIndex = 0;
                                var qapsIndex = 0;
                                var qawsIndex = 0;
                                var igIndex = 0;
                                var qgIndex = 0;
                                var grade = [];
                                var getId = 0;
                                var passinggrade = $('#passinggrade').val();
                                $.each(data[1], function(key, value){
                                    console.log(data[14][igIndex])
                                    if(data[14][igIndex] < data[2][0].passinggrade){
                                        var gradestatuscolor = "rgb(234, 170, 176)";
                                    }
                                    else if(data[14][igIndex] > data[2][0].passinggrade){
                                        var gradestatuscolor = "white";
                                    }
                                    $('#pg').show();
                                    $('#passinggrade').show();
                                    grade.push(value.studid);
                                    $('#divForm').empty();
                                    $('#divForm').append(
                                        '<a href="/classrecord/pdf/'+sectionid+'/'+subjectid+'/'+quarter+'" name="downloadClassRecord" id="downloadClassRecord" class="btn btn-secondary float-sm-left" target="_blank">'+
                                            '<i class="fa fa-print"></i>'+
                                        '</a>'
                                    );
                                    $('#divForm').append(
                                        '<button name="btnSubmit" id="btnSubmit" class="btn btn-success float-sm-right btnSubmit">Submit Final Grades</button>'
                                        );
                                    $('#body').append(
                                        '<tr id="'+value.headerid+'" class="'+value.studid+'" style="background-color: '+gradestatuscolor+' !important">'+
                                            '<td id="studname" class="'+value.studid+'" >'+value.studname+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="ww1">'+value.ww1+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="ww2">'+value.ww2+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="ww3">'+value.ww3+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="ww4">'+value.ww4+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="ww5">'+value.ww5+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="ww6">'+value.ww6+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="ww7">'+value.ww7+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="ww8">'+value.ww8+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="ww9">'+value.ww9+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="ww0">'+value.ww0+'</td>'+
                                            '<td class="wwtotal" id="'+value.studid+'">'+data[4][wwtotalIndex]+'</td>'+
                                            '<td class="wwps" id="'+value.studid+'">'+data[5][wwpsIndex]+'</td>'+
                                            '<td class="wwws" id="'+value.studid+'">'+data[6][wwwsIndex]+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="pt1">'+value.pt1+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="pt2">'+value.pt2+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="pt3">'+value.pt3+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="pt4">'+value.pt4+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="pt5">'+value.pt5+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="pt6">'+value.pt6+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="pt7">'+value.pt7+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="pt8">'+value.pt8+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="pt9">'+value.pt9+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="pt0">'+value.pt0+'</td>'+
                                            '<td class="pttotal" id="'+value.studid+'">'+data[8][pttotalIndex]+'</td>'+
                                            '<td class="ptps" id="'+value.studid+'">'+data[9][ptpsIndex]+'</td>'+
                                            '<td class="ptws" id="'+value.studid+'">'+data[10][ptwsIndex]+'</td>'+
                                            '<td contenteditable="true" id="'+value.studid+'" class="qa1">'+value.qa1+'</td>'+
                                            '<td class="qaps" id="'+value.studid+'">'+data[11][qapsIndex]+'</td>'+
                                            '<td class="qaws" id="'+value.studid+'">'+data[12][qawsIndex]+'</td>'+
                                            '<td class="ig" id="'+value.studid+'">'+data[13][igIndex]+'</td>'+
                                            '<td class="qg" id="'+value.studid+'" style="background-color:#ffdc89;">'+
                                                '<strong>'+data[14][igIndex]+'</strong>'+
                                            '</td>'+
                                        '</tr>'
                                        );
                                        
                                    
                                    // <td class="inc bg-success" style="border: 1px solid green; "><center><div class="icheck-danger d-inline"><input type="checkbox" id="radioPrimary1'+value.studid+'" class="present" value="present" name="'+value.studid+'" style="width:100%"> <label for="radioPrimary1'+value.studid+'"></label></div></center></td>
                                    // var studentId = 0;
                                
                                    for(var x = 0 ; x <= 9 ; x++){
                                        $('.ww'+x.toString()).each(function(){
                                            if($(this).text()=='0'){
                                                // $(this).attr('class',''+('ww'+x.toString())+' bg-info' );
                                                $(this).css('backgroundColor','#16aaffe8')
                                            }
                                        })
                                    }
                                    // studentId+=1;
                                    for(var x = 0 ; x <= 9 ; x++){
                                        $('.pt'+x.toString()).each(function(){
                                            if($(this).text()=='0'){
                                                // $(this).attr('class',''+('pt'+x.toString())+' bg-warning' );
                                                $(this).css('backgroundColor','#d4a3e6')
                                            }
                                        })
                                    }
                                    $('.qa1').each(function(){
                                        if($(this).text()=='0'){
                                            // $(this).attr('class',''+'qa1'+' bg-warning' );
                                                $(this).css('backgroundColor','#3ac47d')
                                        }
                                    })
                                    wwtotalIndex+=1;
                                    wwpsIndex+=1;
                                    wwwsIndex+=1;
                                    pttotalIndex+=1;
                                    ptpsIndex+=1;
                                    ptwsIndex+=1;
                                    qapsIndex+=1;
                                    qawsIndex+=1;
                                    igIndex+=1;
                                    qgIndex+=1;
                                });

                                dataTable();
                                if(data[2][0].submitted==1){
                                    $('#btnSubmit').hide();
                                    $('#divForm').append(
                                        '<button name="btnRequest" id="btnRequest" class="btn btn-danger float-sm-right btnRequest">'+
                                            '<span id="pendingRequest" class="badge badge-pill badge-light"></span> Request Permission'+
                                        '</button>'
                                        );
                                    $('th[contenteditable="true"]').attr('contenteditable','false')
                                    $('td[contenteditable="true"]').attr('contenteditable','false')
                                    $('input[type=checkbox]').attr('disabled','true')
                                }
                                
                                // if(data[15][0].status == 0 && data[2][0].submitted==1){
                                //     $('#btnSubmit').remove();
                                //     $('#btnSubmit').hide();
                                //     $('#divForm').append('<button name="btnRequest" id="btnRequest" class="btn btn-danger float-sm-right btnRequest" disabled><span id="pendingRequest" class="badge badge-pill badge-light"></span> Request Permission</button>');
                                // }
                                // else if(data[15][0].status == 1 && data[2][0].submitted==1){
                                //     $('#divForm').append('<button name="btnSubmit" id="btnSubmit" class="btn btn-success float-sm-right btnSubmit">Submit Final Grades</button>');
                                //     $('th[contenteditable="true"]').attr('contenteditable','true')
                                //     $('td[contenteditable="true"]').attr('contenteditable','true')
                                //     $('input[type=checkbox]').attr('disabled','false')
                                // }
                                // else if(data[15][0].status == 'none' && data[2][0].submitted==1){
                                //     $('#divForm').append('<button name="btnRequest" id="btnRequest" class="btn btn-danger float-sm-right btnRequest" disabled><span id="pendingRequest" class="badge badge-pill badge-light"></span> Request Permission</button>');
                                //     $('th[contenteditable="true"]').attr('contenteditable','true')
                                //     $('td[contenteditable="true"]').attr('contenteditable','true')
                                //     $('input[type=checkbox]').attr('disabled','false')
                                // }
                            }
                        // }
                        }
                    }
                });
            });
        var firstId = "Hello";
        $(document).on('click','th[contenteditable="true"]',function(){
            $(this).css('background-color','#ddd');
            firstId=$(this).attr('class');
        });

        var currentTh = 0;
        var thValue = 0;

        $(document).on('input','th[contenteditable="true"]',function(){
            // console.log($(this).text().length)
            if($(this).text().length === 3){
            console.log($(this).text().length)
            $(this).text().slice(0,$(this).text().length-1);
                return;
            }
            else{
            thValue = $(this).text();


            $('.'+firstId).css('background-color','#16aaffe8')
            var header_id = $('th[contenteditable="true"]').closest("tr");
            var headerTH = header_id[0].id;
            var sy = $('#syid').val();
            var levelID = $('#gradelevelid').val();
            var sectionid = $('#sectionid').val();
            var quarterID = $('button[name=quarter].active').val();
            var subjectID = $('#subjectid').val();
            
            var headerClass = $(this).attr('class');
            var headerValue = $(this).text();
            // console.log($(this).text())
            // console.log(headerClass)
            $.ajax({
                url: '/updatedata/'+headerTH,
                type:"GET",
                dataType:"json",
                data:{
                    headerTH: headerTH,
                    syid: sy,
                    levelID: levelID,
                    sectionid: sectionid,
                    quarterID : quarterID,
                    subjectID : subjectID,
                    identifier: "th",
                    headerClass: headerClass,
                    headerValue: headerValue
                },
                // headers: { 'X-CSRF-TOKEN': token },
                success:function(data) {
                    // console.log(data);
                    $('#totalWW').text(data[1]);
                    $('#totalPT').text(data[2]);
                }
            })
            }
        });
        $(document).on('keyup','td[contenteditable="true"]',function(){

            this.textContent = this.textContent.replace(/[^\d.]/g,'');
            // console.log($(this).attr('class').slice(0,-1).split(" "))
            // console.log($(this).text())
            // console.log($(this).innerText > headerText)
            // console.log($(this).attr('class').split(" ")[0].slice(0,-1))

            var classString = $(this).attr('class').split(" ")[0].slice(0,-1)+'hr'+$(this).attr('class').split(" ")[0].slice(-1)

            // console.log(classString);

            var headerText = $('.'+classString)[0].innerText;

            // console.log(headerText)
            // console.log($(this)[0].innerText)
            // console.log(parseFloat($(this)[0].innerText) > parseFloat(headerText) )

            if(parseFloat($(this)[0].innerText) > parseFloat(headerText) ){

                $(this).text(headerText);
                
            }


            // var countwwhr1 = thValue.length/2;
            // var getwwhr1 = thValue.substring(0,countwwhr1)
            
            // console.log(getwwhr1);

            

            
            var header_id = $('td[contenteditable="true"]').closest("tr");
            var header = header_id[0].id;
            var student_ID = $(this).attr('id');
            var student_header_class = $(this).attr('class');
            var student_grade = $(this).text();
            var sy = $('#syid').val();
            var levelID = $('#gradelevelid').val();
            var sectionid = $('#sectionid').val();
            var quarterID = $('button[name=quarter].active').val();
            var subjectID = $('#subjectid').val();

            var wwtotal = $('td#'+student_ID+'.wwtotal').text();
            var wwps = $('td#'+student_ID+'.wwps').text();
            var wwws = $('td#'+student_ID+'.wwws').text();
            var pttotal = $('td#'+student_ID+'.pttotal').text();
            var ptps = $('td#'+student_ID+'.ptps').text();
            var ptws = $('td#'+student_ID+'.ptws').text();
            var qaps = $('td#'+student_ID+'.qaps').text();
            var qaws = $('td#'+student_ID+'.qaws').text();
            var ig = $('td#'+student_ID+'.ig').text();
            var qg = $('td#'+student_ID+'.qg').text();
            console.log(student_grade)
            $.ajax({
                url: '/updatedata/'+student_ID,
                type:"GET",
                dataType:"json",
                data:{
                    syid: sy,
                    levelID: levelID,
                    sectionid: sectionid,
                    quarterID : quarterID,
                    subjectID : subjectID,
                    student_ID: student_ID,
                    student_header_class: student_header_class,
                    student_grade: student_grade,
                    headerID : header,
                    identifier: "td",
                    wwtotal: wwtotal,
                    wwps: wwps,
                    wwws: wwws,
                    pttotal: pttotal,
                    ptps: ptps,
                    ptws: ptws,
                    qaps: qaps,
                    qaws: qaws,
                    ig: ig,
                    qg: qg
                },
                success:function(data) {
                    $('td#'+data[0][0]+'.wwtotal').text(data[0][3]);
                    $('td#'+data[0][0]+'.wwps').text(data[0][4]);
                    $('td#'+data[0][0]+'.wwws').text(data[0][5]);
                    $('td#'+data[0][0]+'.pttotal').text(data[0][6]);
                    $('td#'+data[0][0]+'.ptps').text(data[0][7]);
                    $('td#'+data[0][0]+'.ptws').text(data[0][8]);
                    $('td#'+data[0][0]+'.qaps').text(data[0][9]);
                    $('td#'+data[0][0]+'.qaws').text(data[0][10]);
                    $('td#'+data[0][0]+'.ig').text(data[0][11]);
                    $('td#'+data[0][0]+'.qg').text(data[0][12]);
                }
            })
        });
        $(document).on('click','#btnSubmit', function() {
            var sy = $('#syid').val();
            var gradeLevel = $('#gradelevelid').val();
            var section = $('#sectionid').val();
            var quarters = $('button[name=quarter].active').val();
            var subjects = $('#subjectid').val();
            // console.log(sy+' '+gradeLevel+' '+section+' '+quarters+' '+subjects)
            var ajax = $.ajax({
                url: '/gradesSubmit/'+quarters,
                type:"GET",
                dataType:"json",
                data:{
                    syid: sy,
                    gradelevelid: gradeLevel,
                    section: section,
                    quarter : quarters,
                    subjectid: subjects,
                    dataHolder: 'submit'

                }
            });
            ajax.complete(function(showModal){
                $('#btnSubmit').remove();
                $('th[contenteditable="true"]').attr('contenteditable','false')
                $('td[contenteditable="true"]').attr('contenteditable','false')
                
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
                toastr.success('Grades submitted successfully!')
                // $('#messageContainer').append(
                //     '<div class="alert alert-success alert-dismissible col-12">'+
                //         '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+
                //         '<h5><i class="icon fas fa-check"></i> Grades submitted successfully!</h5>'+
                //     '</div>'
                // );
            });
        });
        $(document).on('click','#btnRequest', function() {
            var sy = $('#syid').val();
            var gradeLevel = $('#gradelevelid').val();
            var section = $('#sectionid').val();
            var quarters = $('button[name=quarter].active').val();
            var subjects = $('#subjectid').val();
            var ajax = $.ajax({
                url: '/unpostrequest/'+quarters,
                type:"GET",
                dataType:"json",
                data:{
                    syid: sy,
                    gradelevelid: gradeLevel,
                    section: section,
                    quarter : quarters,
                    subjectid: subjects,
                    dataHolder: 'request'

                },
                success:function(data){
                    if(data.message == "Request sent!"){
                        $('#messageContainer').empty();
                        
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        toastr.success('Request sent!')
                    }
                    if(data.message == "Request already exist!"){
                        $('#messageContainer').empty();
                        
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "2000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        }
                        toastr.error('Request been sent! Please wait for a while...')
                    }
                    
                }
            });
        });
        $(document).on('click','.closeAlert', function() {
            $('#messageContainer').empty();
        });

    });
        //input passinggrade
    $(function() {
        $('#passinggrade').on('input', function() {
            match = (/(\d{0,3})[^.]*((?:\.\d{0,3})?)/g).exec(this.value.replace(/[^\d.]/g, ''));
            this.value = match[1] + match[2] ;
        });
    });
    $(document).on('input','#passinggrade',function(){
        var passinggrade = $('#passinggrade').val();
        var gradeLevel = $('#gradelevelid').val();
        var section = $('#sectionid').val();
        var quarters = $('button[name=quarter].active').val();
        var subjects = $('#subjectid').val();
        $.ajax({
            url: '/updatedata/'+passinggrade,
            type:"GET",
            dataType:"json",
            data:{
                passinggrade: passinggrade,
                gradeLevel: gradeLevel,
                section: section,
                quarters : quarters,
                subjects: subjects,
                identifier: "passinggrade"
            },
            success:function(data) {
                var qgStudentIDFailed = [];
                var qgStudentIDPassed = [];
                $('.qg').each(function (){
                    if($(this).text() < passinggrade){
                        qgStudentIDFailed.push($(this).attr('id'));
                    }
                    else{
                        qgStudentIDPassed.push($(this).attr('id'));
                    }
                });
                $.each(qgStudentIDFailed,function(key, value){
                        $('.'+value).css('background-color','#eaaab0');
                })
                $.each(qgStudentIDPassed,function(key, value){
                        $('.'+value).css('background-color','white');
                })
            }
        })
    })
</script>
@endsection