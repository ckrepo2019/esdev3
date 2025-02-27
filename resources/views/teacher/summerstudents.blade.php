
@extends('teacher.layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css')}}">
<style>
        /* .tscroll                    { max-width: 100%; overflow-x: scroll; margin-bottom: 10px; border: solid black 1px; font-size: 90%; height: 500px; */
        /* } */
        .table                      { font-size:90%; text-transform: uppercase; }
        /* .table thead th:first-child { position: sticky; left: 0; background-color: #fff; } */
        /* .table thead th#firstlast  { position: sticky; right: 0; background-color: #fff; } */
        .table thead th#last  { position: sticky; right: 0; background-color: #fff; }
        .table thead th#first  { position: sticky; left: 0; background-color: #fff; padding-right: 90px;padding-left: 90px}
         /* { position: sticky; right: 0; background-color: #fff; } */
        /* .table tbody td:first-child { position: sticky; left: 0; background-color: #fff; } */
        .table tbody td:last-child  { position: sticky; right: 0; background-color: #fff; }
        .table tbody td#first  { position: sticky; left: 0; background-color: #fff; }
        /* .table #stud, #hps          { position: sticky; left: 0; background-color: #ddd; }
        td, th                      { border-bottom: dashed #888 1px; font-size: 80%; border: 1px solid #ddd; } */
            th, td{
                border: 1px solid #ddd;
            }
    </style>
    <div>
        <nav class="" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="active breadcrumb-item">Summer</li>
                <li class="breadcrumb-item"><a href="/summergrades/dashboard">Subjects</a></li>
                <li class="active breadcrumb-item" aria-current="page">Grades</li>
            </ol>
        </nav>
    </div>
    <div class="card ">
        <div class="card-body">
            <h5 class="card-title"><strong>Grade level: {{$levelname}}</strong></h5><br>
            <h5 class="card-title"><strong>Subject: {{$subjectname}}</strong> ({{$subjcode}})</h5><br>
            <p>No. of students: {{count($students)}}</p>
        </div>
    </div>
    
    @if(session()->has('submitted'))
    <div class="alert alert-success alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('submitted') }}
    </div>
    @endif
    @if(session()->has('exists'))
    <div class="alert alert-danger alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('exists') }}
    </div>
    @endif
    <div name="carrier" syid="{{$syid}}" levelid="{{$levelid}}" subjid="{{$subjid}}"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    {{-- <div class="alert alert-info alert-dismissible" id="noAssignedSetup">
                        <h5><i class="icon fas fa-info"></i> Alert!</h5>
                        Grading setup is not yet configured. 
                    </div>
                    <div class="alert alert-warning alert-dismissible" id="noAssignedStudents">
                        <h5><i class="icon fas fa-info"></i> Alert!</h5>
                        No assigned students. 
                    </div> --}}
                    <div id="filterPanel">
                        {{-- <div class="card-body table-responsive p-0" style="height: 500px;">
                        </div> --}}
                        @if(count($students)==0)
                        <div class="alert alert-warning alert-dismissible col-md-12">
                            <h5><i class="icon fas fa-info"></i> Alert!</h5>
                            No students enrolled!
                        </div>
                        @elseif(count($students)>0)
                        <div id="tableContainer" style="overflow: scroll">

                            <table class="table table-head-fixed" id="example1">
                                <thead id="header">
                                    <tr>
                                        <th id="first" rowspan="2" style="width: 90px !important">Student</th>
                                        <th colspan="13" style="background-color:#16aaffe8" >
                                            <center>WRITTEN WORKS ({{$gradessetup[0]->writtenworks}}%)</center>
                                        </th>
                                        <th colspan="13" style="background-color:#d4a3e6" >
                                            <center>PEFORMANCE TASK ({{$gradessetup[0]->performancetask}}%)</center>
                                        </th>
                                        <th colspan="3" style="background-color:3ac47d" >
                                            <center>QA ({{$gradessetup[0]->qassesment}}%)</center>
                                        </th>
                                        <th rowspan="2" style="background-color: #ffdc89" >IG</th>
                                        <th rowspan="2" style="background-color: #ffdc89" id="last">QG</th>
                                    </tr>
                                    <tr>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>5</th>
                                        <th>6</th>
                                        <th>7</th>
                                        <th>8</th>
                                        <th>9</th>
                                        <th>10</th>
                                        <th>TOTAL</th>
                                        <th>PS</th>
                                        <th>WS</th>
                                        <th>1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>5</th>
                                        <th>6</th>
                                        <th>7</th>
                                        <th>8</th>
                                        <th>9</th>
                                        <th>10</th>
                                        <th>TOTAL</th>
                                        <th>PS</th>
                                        <th>WS</th>
                                        <th>1</th>
                                        <th>PS</th>
                                        <th>WS</th>
                                    </tr>
                                    <tr>
                                        <th id="first" style="padding:2px;border-top: 2px solid white" >Highest Possible Score</th>
                                        @if(count($header)==0)
                                        <th contenteditable="true" class="wwhr1"></th>
                                        <th contenteditable="true" class="wwhr2"></th>
                                        <th contenteditable="true" class="wwhr3"></th>
                                        <th contenteditable="true" class="wwhr4"></th>
                                        <th contenteditable="true" class="wwhr5"></th>
                                        <th contenteditable="true" class="wwhr6"></th>
                                        <th contenteditable="true" class="wwhr7"></th>
                                        <th contenteditable="true" class="wwhr8"></th>
                                        <th contenteditable="true" class="wwhr9"></th>
                                        <th contenteditable="true" class="wwhr0"></th>
                                        @else
                                        <th contenteditable="true" class="wwhr1">{{$header[0]->wwhr1}}</th>
                                        <th contenteditable="true" class="wwhr2">{{$header[0]->wwhr2}}</th>
                                        <th contenteditable="true" class="wwhr3">{{$header[0]->wwhr3}}</th>
                                        <th contenteditable="true" class="wwhr4">{{$header[0]->wwhr4}}</th>
                                        <th contenteditable="true" class="wwhr5">{{$header[0]->wwhr5}}</th>
                                        <th contenteditable="true" class="wwhr6">{{$header[0]->wwhr6}}</th>
                                        <th contenteditable="true" class="wwhr7">{{$header[0]->wwhr7}}</th>
                                        <th contenteditable="true" class="wwhr8">{{$header[0]->wwhr8}}</th>
                                        <th contenteditable="true" class="wwhr9">{{$header[0]->wwhr9}}</th>
                                        <th contenteditable="true" class="wwhr0">{{$header[0]->wwhr0}}</th>
                                        @endif
                                        <th class="headerwwtotal">{{$header[0]->wwtotal}}</th>
                                        <th>100.00</th>
                                        <th></th>
                                        @if(count($header)==0)
                                        <th contenteditable="true" class="pthr1"></th>
                                        <th contenteditable="true" class="pthr2"></th>
                                        <th contenteditable="true" class="pthr3"></th>
                                        <th contenteditable="true" class="pthr4"></th>
                                        <th contenteditable="true" class="pthr5"></th>
                                        <th contenteditable="true" class="pthr6"></th>
                                        <th contenteditable="true" class="pthr7"></th>
                                        <th contenteditable="true" class="pthr8"></th>
                                        <th contenteditable="true" class="pthr9"></th>
                                        <th contenteditable="true" class="pthr0"></th>
                                        @else
                                        <th contenteditable="true" class="pthr1">{{$header[0]->pthr1}}</th>
                                        <th contenteditable="true" class="pthr2">{{$header[0]->pthr2}}</th>
                                        <th contenteditable="true" class="pthr3">{{$header[0]->pthr3}}</th>
                                        <th contenteditable="true" class="pthr4">{{$header[0]->pthr4}}</th>
                                        <th contenteditable="true" class="pthr5">{{$header[0]->pthr5}}</th>
                                        <th contenteditable="true" class="pthr6">{{$header[0]->pthr6}}</th>
                                        <th contenteditable="true" class="pthr7">{{$header[0]->pthr7}}</th>
                                        <th contenteditable="true" class="pthr8">{{$header[0]->pthr8}}</th>
                                        <th contenteditable="true" class="pthr9">{{$header[0]->pthr9}}</th>
                                        <th contenteditable="true" class="pthr0">{{$header[0]->pthr0}}</th>
                                        @endif
                                        <th class="headerpttotal">{{$header[0]->pttotal}}</th>
                                        <th >100.00</th>
                                        <th></th >
                                        @if(count($header)==0)
                                        <th contenteditable="true" class="qahr1"></th>
                                        @else
                                        <th contenteditable="true" class="qahr1">{{$header[0]->qahr1}}</th>
                                        @endif
                                        <th>100.00</th>
                                        <th></th >
                                        <th style="background-color: #ffdc89" ></th >
                                        <th style="background-color: #ffdc89" id="last"></th>
                                    </tr>
                                </thead>
                                <tbody id="body">
                                    @foreach ($students as $student)
                                    <tr id="{{$student->studid}}" >
                                        <td id="first">{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="ww1">{{$student->ww1}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="ww2">{{$student->ww2}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="ww3">{{$student->ww3}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="ww4">{{$student->ww4}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="ww5">{{$student->ww5}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="ww6">{{$student->ww6}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="ww7">{{$student->ww7}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="ww8">{{$student->ww8}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="ww9">{{$student->ww9}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="ww0">{{$student->ww0}}</td>
                                        <td id="{{$student->studid}}" class="wwtotal">{{$student->wwtotal}}</td>
                                        <td id="{{$student->studid}}" class="wwps">{{number_format((float)$student->wwps, 2, '.','')}}</td>
                                        <td id="{{$student->studid}}" class="wwws">{{number_format((float)$student->wwws, 2, '.','')}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="pt1">{{$student->pt1}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="pt2">{{$student->pt2}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="pt3">{{$student->pt3}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="pt4">{{$student->pt4}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="pt5">{{$student->pt5}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="pt6">{{$student->pt6}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="pt7">{{$student->pt7}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="pt8">{{$student->pt8}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="pt9">{{$student->pt9}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="pt0">{{$student->pt0}}</td>
                                        <td id="{{$student->studid}}" class="pttotal">{{$student->pttotal}}</td>
                                        <td id="{{$student->studid}}" class="ptps">{{number_format((float)$student->ptps, 2, '.','')}}</td>
                                        <td id="{{$student->studid}}" class="ptws">{{number_format((float)$student->ptws, 2, '.','')}}</td>
                                        <td id="{{$student->studid}}" contenteditable="true" class="qa1">{{$student->qa1}}</td>
                                        <td id="{{$student->studid}}" class="qaps">{{number_format((float)$student->qaps, 2, '.','')}}</td>
                                        <td id="{{$student->studid}}" class="qaws">{{number_format((float)$student->qaws, 2, '.','')}}</td>
                                        <td id="{{$student->studid}}" class="ig">{{$student->ig}}</td>
                                        <td id="{{$student->studid}}" class="qg" style="background-color:#ffdc89;">
                                            <strong>{{$student->qg}}</strong>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                        <br>
                        @if($header[0]->submitted == 0)
                            <form action="/summergrades/submitgrades" method="get">
                                <input type="hidden" name="syid" value="{{$syid}}"/>
                                <input type="hidden" name="levelid" value="{{$levelid}}"/>
                                <input type="hidden" name="subjid" value="{{$subjid}}"/>
                                <button type="submit" class="btn btn-sm btn-primary float-right">Submit</button>
                            </form>
                        @elseif($header[0]->submitted == 1)
                            <form action="/summergrades/requestpending" method="get">
                                <input type="hidden" name="syid" value="{{$syid}}"/>
                                <input type="hidden" name="levelid" value="{{$levelid}}"/>
                                <input type="hidden" name="subjid" value="{{$subjid}}"/>
                                <input type="hidden" name="levelname" value="{{$levelname}}"/>
                                <input type="hidden" name="subjcode" value="{{$subjcode}}"/>
                                <button type="submit" class="btn btn-sm btn-secondary float-right">Request</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-fixedcolumns/js/fixedColumns.bootstrap4.js')}}"></script>
    
    
    <script>
        var $ = jQuery;
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
        });

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
        
        // var firstId = "Hello";
        $(document).on('click','th[contenteditable="true"]',function(){
            $(this).css('background-color','#ddd');
            firstId=$(this).attr('class');
        });

        var currentTh = 0;
        var thValue = 0;

        $(document).on('input','th[contenteditable="true"]',function(){
            
            if($(this).text().length === 3){

            // console.log($(this).text().length)
            $(this).text().slice(0,$(this).text().length-1);
            $(this).text($(this).text().slice(0,$(this).text().length-1))
            }
            else{
            thValue = $(this).text();


            var header_id = $('th[contenteditable="true"]').closest("tr");

            var sy = $('div[name=carrier]').attr('syid');
            var levelID = $('div[name=carrier]').attr('levelid')
            var subjectID = $('div[name=carrier]').attr('subjid')
            
            var headerClass = $(this).attr('class');
            var headerValue = $(this).text();
            console.log(headerValue)
            // console.log(headerClass)
            $.ajax({
                url: '/summergrades/updateheader',
                type:"GET",
                dataType:"json",
                data:{
                    syid: sy,
                    levelid: levelID,
                    subjid : subjectID,
                    headerClass: headerClass,
                    headerValue: headerValue
                },
                // headers: { 'X-CSRF-TOKEN': token },
                success:function(data) {
                    $('.headerwwtotal').text(data[1].wwtotal);
                    $('.headerpttotal').text(data[1].pttotal);
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
            var student_ID = header_id[0].id;
            var student_header_class = $(this).attr('class');
            var student_grade = $(this).text();
            var sy = $('div[name=carrier]').attr('syid');
            var levelID = $('div[name=carrier]').attr('levelid')
            var subjectID = $('div[name=carrier]').attr('subjid')

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
            $.ajax({
                url: '/summergrades/updatestudentgrade',
                type:"GET",
                dataType:"json",
                data:{
                    syid: sy,
                    levelid: levelID,
                    subjid : subjectID,
                    studid: student_ID,
                    header: student_header_class,
                    grade: student_grade,
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
                    console.log(data)
                    $('td#'+data.studid+'.wwtotal').text(data.wwtotal);
                    $('td#'+data.studid+'.wwps').text(data.wwps);
                    $('td#'+data.studid+'.wwws').text(data.wwws);
                    $('td#'+data.studid+'.pttotal').text(data.pttotal);
                    $('td#'+data.studid+'.ptps').text(data.ptps);
                    $('td#'+data.studid+'.ptws').text(data.ptws);
                    $('td#'+data.studid+'.qaps').text(data.qaps);
                    $('td#'+data.studid+'.qaws').text(data.qaws);
                    $('td#'+data.studid+'.ig').text(data.ig);
                    $('td#'+data.studid+'.qg').text(data.qg);
                    // $('td#'+data[0][0]+'.pttotal').text(data[0][6]);
                    // $('td#'+data[0][0]+'.ptps').text(data[0][7]);
                    // $('td#'+data[0][0]+'.ptws').text(data[0][8]);
                    // $('td#'+data[0][0]+'.qaps').text(data[0][9]);
                    // $('td#'+data[0][0]+'.qaws').text(data[0][10]);
                    // $('td#'+data[0][0]+'.ig').text(data[0][11]);
                    // $('td#'+data[0][0]+'.qg').text(data[0][12]);
                }
            })
        });
        
    $(document).ready(function(){
        window.setTimeout(function () {
            $(".alert-success").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
        window.setTimeout(function () {
            $(".alert-danger").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
        if('{{$header[0]->submitted}}' == 1){
            $('th[contenteditable=true]').attr('contenteditable',false);
            $('td[contenteditable=true]').attr('contenteditable',false);
        }
   })
</script>
@endsection