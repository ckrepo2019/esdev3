
@extends('registrar.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                    <b>SCHOOL FORM 9</b></h4>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">School Form 9</li>
                </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row" id="filtercontainer">
       <div class="col-md-3">
           <div class="card">
               <div class="card-header">
                <i class="fa fa-filter"></i> Filter
               </div>
               <div class="card-body">
                    <form action="/reportsschoolform9/changeschoolyear" method="get">
                        <label>Schoolyear</label>
                        <select class="form-control form-control-sm" name="schoolyearid">
                            @foreach($schoolyears as $schoolyear)
                                <option value="{{$schoolyear->id}}" {{$schoolyear->id == $selectedschoolyear ? 'selected' : ''}}>{{$schoolyear->sydesc}}</option>
                            @endforeach
                        </select>
                    </form>
                    <br>
                    <form action="/reportsschoolform9/changegradelevel" method="get">
                        <input type="hidden" name="schoolyearid" value="{{$selectedschoolyear}}"/>
                        <label>Gradelevel</label>
                        <select class="form-control form-control-sm" name="gradelevelid">
                            @if($selectedgradelevel == 'default')
                                <option value="all">All</option>
                                @foreach ($gradelevels as $gradelevel)
                                    <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                                @endforeach
                            @else
                                <option value="all">All</option>
                                @foreach ($gradelevels as $gradelevel)
                                    <option value="{{$gradelevel->id}}" {{$gradelevel->id == $selectedgradelevel ? 'selected' : ''}}>{{$gradelevel->levelname}}</option>
                                @endforeach
                            @endif
                        </select>
                    </form>
                    <br>
                    {{-- <form action="/reportsschoolform9/print" method="get">
                        <input type="hidden" name="schoolyearid" value="{{$selectedschoolyear}}"/>
                        <input type="hidden" name="gradelevelid" value="{{$selectedgradelevel}}"/> --}}
                        @if(isset($sections))
                            <label>Section</label>
                            <select class="form-control form-control-sm" name="sectionid">
                                <option value="all">All</option>
                                @if(count($sections) == 0)
                                @else
                                    @foreach ($sections as $section)
                                        <option value="{{$section->id}}" >{{$section->sectionname}}</option>
                                    @endforeach
                                @endif
                            </select>
                            <br>
                        @else
                        @endif
                        {{-- <button type="" class="btn btn-sm btn-block btn-primary"><i class="fa fa-print"></i> Print</button> --}}
                    {{-- </form> --}}
               </div>
           </div>
       </div>
       <div class="col-md-9" id="studentscontainer">
           <div class="card">
               <div class="card-header">
                   Total Students : <span class="badge badge-info countstudents"> {{count($students)}} </span>
               </div>
               <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Students</th>    
                                <th>Grade Level</th>    
                            </tr>
                        </thead>
                        <tbody class="studentscontainer">
                            @foreach($students as $student)
                                <tr id="{{$student->id}}">
                                    <td>
                                        <a href="#" class="studidlink">{{$student->lastname.', '}}{{$student->firstname}}</a>
                                    </td>
                                    <td>
                                        {{$student->levelname}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
               </div>
           </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- DataTables -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script>

        $(function () {

            $("#example1").DataTable({
                pageLength : 10,
                lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
            });

        });

        $(document).ready(function(){

            $('body').addClass('sidebar-collapse');

        });

        $(document).on('change','select[name="schoolyearid"]', function(){

            $(this).closest('form').submit();

        });

        $(document).on('change','select[name="gradelevelid"]', function(){

            $(this).closest('form').submit();

        });

        $(document).on('change','select[name="sectionid"]', function(){

            var schoolyearid = $('select[name="schoolyearid"]').val();
            var gradelevelid = $('select[name="gradelevelid"]').val();

            $.ajax({
                url: '/reportsschoolform9/changesection',
                type:"GET",
                dataType:"json",
                data:{
                    schoolyearid    :   schoolyearid,
                    gradelevelid    :   gradelevelid,
                    sectionid       :   $(this).val()
                },
                success:function(data) {
                    console.log(data)
                    $('.studentscontainer').empty();
                    var countstudents = 0;
                    $.each(data, function(key, value){
                        countstudents+=1;
                        $('.studentscontainer').append(
                            '<tr id="'+value.id+'">'+
                                '<td><a href="#" class="studidlink">'+value.lastname+', '+value.firstname+'</a></td>'+
                                '<td>'+value.levelname+'</td>'+
                            '</tr>'
                        )
                    });
                    $('.countstudents').text(countstudents)

                }
            });

        });

        $(document).on('click','.studidlink', function(){
            $('#studentscontainer').removeClass('col-md-9');
            $('#studentscontainer').addClass('col-md-6');
            $('#gradescontainer').remove();
            $('#filtercontainer').append(
                '<div class="col-md-3" id="gradescontainer">'+
                    '<div class="card">'+
                        '<div class="card-header">'+
                            '<h5 id="studentname"></h5>'+
                        '</div>'+
                        '<div class="card-body" id="studentgradescontainer">'+
                        '</div>'+
                    '</div>'+
                '</div>'
            )
            var schoolyearid = $('select[name="schoolyearid"]').val();
            var gradelevelid = $('select[name="gradelevelid"]').val();
            var sectionid    = $('select[name="sectionid"]').val();
            var studentid     = $(this).closest('tr').attr('id');

            $.ajax({
                url: '/reportsschoolform9/studentgrades',
                type:"GET",
                dataType:"json",
                data:{
                    schoolyearid    :   schoolyearid,
                    gradelevelid    :   gradelevelid,
                    sectionid       :   sectionid,
                    studentid       :   studentid
                },
                success:function(data) {
                    console.log(data)

                    if(data.length == 1){

                        if(data[0].suffix == null){

                            $('#studentname').text(data[0].lastname+', '+data[0].firstname+' '+data[0].middlename[0]+'. ')

                        }else{

                            $('#studentname').text(data[0].lastname+', '+data[0].firstname+' '+data[0].middlename[0]+'. '+data[0].suffix)

                        }

                        
                        $('#studentgradescontainer').append(
                            '<strong>No records shown!</strong>'
                        )

                    }else{

                        if(data[0][0].suffix == null){

                            data[0][0].suffix = "";

                        }
                        if(data[0][0].middlename == null){

                            data[0][0].middlename = "";

                        }else{

                            data[0][0].middlename = data[0][0].middlename[0]+'.';

                        }
                        
                        $('#studentname').text(data[0][0].lastname+', '+data[0][0].firstname+' '+data[0][0].middlename+' '+data[0][0].suffix)

                        $.each(data[2], function(key, value){

                            if(value.q1 == null){

                                value.q1 = "";

                            }
                            if(value.q2 == null){

                                value.q2 = "";

                            }
                            if(value.q3 == null){

                                value.q3 = "";

                            }
                            if(value.q4 == null){

                                value.q4 = "";

                            }

                            $('#studentgradescontainer').append(
                                '<table class="table table-bordered text-center">'+
                                    '<tr>'+
                                        '<th colspan="4" class="text-center">'+value.subjcode+'</th>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td class="text-center">Q1</td>'+
                                        '<td class="text-center">Q2</td>'+
                                        '<td class="text-center">Q3</td>'+
                                        '<td class="text-center">Q4</td>'+
                                    '</tr>'+
                                    '<tr>'+
                                        '<td>'+value.q1+'</td>'+
                                        '<td>'+value.q2+'</td>'+
                                        '<td>'+value.q3+'</td>'+
                                        '<td>'+value.q4+'</td>'+
                                    '</tr>'+
                                '</table>'
                            )
                        });

                        $('#studentgradescontainer').append(
                            '<br/>'+
                            '<form action="/reportsschoolform9/studentgrades" method="get">'+
                                '<input name="schoolyearid" type="hidden" value="'+schoolyearid+'"/>'+
                                '<input name="gradelevelid" type="hidden" value="'+gradelevelid+'"/>'+
                                '<input name="sectionid" type="hidden" value="'+sectionid+'"/>'+
                                '<input name="studentid" type="hidden" value="'+studentid+'"/>'+
                                '<input name="action" type="hidden" value="print"/>'+
                                '<button type="submit" class="btn btn-sm btn-primary btn-block"><i class="fa fa-print"></i> Print</button>'+
                            '</form>'
                        )

                    }

                }
            });
        });

        $(document).on('click','.studidlink', function(){



        })

    </script>
@endsection
