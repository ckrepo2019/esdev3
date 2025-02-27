@extends('teacher.layouts.app')
@section('content')

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Summary</h1>
                <h6>Attendance Per Subject</h6>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item"><a href="/summary">Summaries</a></li>
                    <li class="breadcrumb-item active">Attendance per Subject</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-filter"></i> Filter Attendance
            </div>
            <div class="card-body">
                <form name="changesubject" action="/teacher/summaryattendancepersubject/changesubject" method="get">
                    @if(isset($selectedacademicprogram))
                        <input type="hidden" name="academicprogram" value="{{$selectedacademicprogram}}"/>
                    @else
                        <input type="hidden" name="academicprogram" />
                    @endif
                    @if(isset($selectedacademicprogram))
                        <input type="hidden" name="sectionid" value="{{$selectedsectionid}}"/>
                    @else
                        <input type="hidden" name="sectionid" />
                    @endif
                    @if(isset($selectedacademicprogram))
                        <input type="hidden" name="gradelevelid" value="{{$selectedgradelevelid}}"/>
                    @else
                        <input type="hidden" name="gradelevelid" />
                    @endif
                    <label>Subject</label>
                    <select class="form-control form-control-sm" name="subjectid">
                        <option></option>
                            @foreach($subjects as $subject)
                                <option value="{{$subject->id}}" acadprog="{{$subject->academicprogram}}" sectionid="{{$subject->sectionid}}" gradelevelid="{{$subject->glevelid}}" {{$subject->id == $selectedsubject ? 'selected' : ''}}>{{$subject->subjectcode}} - {{$subject->sectionname}}</option>
                            @endforeach
                    </select>
                </form>
                <hr/>
                <form name="print" action="/summaryattendancepersubjectprint" method="get" target="_blank">
                    @if(isset($selectedacademicprogram))
                        <input type="hidden" name="printacademicprogram" value="{{$selectedacademicprogram}}"/>
                    @else
                        <input type="hidden" name="printacademicprogram" />
                    @endif
                    @if(isset($selectedacademicprogram))
                        <input type="hidden" name="printsectionid" value="{{$selectedsectionid}}"/>
                    @else
                        <input type="hidden" name="printsectionid" />
                    @endif
                    @if(isset($selectedacademicprogram))
                        <input type="hidden" name="printgradelevelid" value="{{$selectedgradelevelid}}"/>
                    @else
                        <input type="hidden" name="printgradelevelid" />
                    @endif
                    @if(isset($selectedacademicprogram))
                        <input type="hidden" name="printsubjectid" value="{{$selectedsubject}}"/>
                    @else
                        <input type="hidden" name="printsubjectid" />
                    @endif
                    <label>Date</label>
                    @if(isset($selectedacademicprogram))
                        <input type="date" name="changedate" class="form-control form-control" value="{{$currentdate}}"/>
                    @else
                        <input type="date" name="changedate" class="form-control form-control" value="{{$currentdate}}" readonly/>
                    @endif
                    <br>
                    <button type="submit" class="btn btn-block btn-sm btn-primary">Print</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name of Students</th> 
                            <th>Status</th> 
                            <th>Remarks</th> 
                        </tr>
                    </thead>
                    <tbody class="studentscontainer text-uppercase">
                        @if(isset($attendance))
                            @foreach($attendance as $studatt)
                                <tr>
                                    <td>{{$studatt->studentname}}</td>
                                    <td>{{$studatt->status}}</td>
                                    <td>{{$studatt->remarks}}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
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

    $(document).on('change','select[name=subjectid]', function(){
        $('input[name=academicprogram]').val($(this)[0].selectedOptions[0].attributes[1].value);
        $('input[name=sectionid]').val($(this)[0].selectedOptions[0].attributes[2].value);
        $('input[name=gradelevelid]').val($(this)[0].selectedOptions[0].attributes[3].value);
        console.log()
        $(this).closest('form').submit();
    });

    $(document).on('change','input[name="changedate"]', function(){

        var selectedacademicprogram = $('input[name=academicprogram]').val();
        var selectedsectionid       = $('input[name=sectionid]').val();
        var selectedgradelevelid    = $('input[name=gradelevelid]').val();
        var selectedsubjectid       = $('select[name=subjectid]').val();
        var selecteddate            = $(this).val();

        $.ajax({
            url: '/teacher/summaryattendancepersubject/changedate',
            type:"GET",
            dataType:"json",
            data:{
                academicprogram : selectedacademicprogram,
                sectionid       : selectedsectionid,
                subjectid       : selectedsubjectid,
                gradelevelid    : selectedgradelevelid,
                selecteddate    : selecteddate
            },
            success:function(data) {
                console.log(data)
                $('.studentscontainer').empty();

                $.each(data, function(key, value){
                    if(value.remarks == null){

                        value.remarks = '';

                    }
                    $('.studentscontainer').append(
                        '<tr>'+
                            '<td>'+value.studentname+'</td>'+
                            '<td>'+value.status+'</td>'+
                            '<td>'+value.remarks+'</td>'+
                        '</tr>'
                    )
                });
            }
        });
    })
</script>
@endsection