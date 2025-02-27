

@extends('hr.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<style>
    table.table td h2.table-avatar {
    align-items: center;
    display: inline-flex;
    font-size: inherit;
    font-weight: 400;
    margin: 0;
    padding: 0;
    vertical-align: middle;
    white-space: nowrap;
}
.avatar {
    background-color: #aaa;
    border-radius: 50%;
    color: #fff;
    display: inline-block;
    font-weight: 500;
    height: 38px;
    line-height: 38px;
    margin: 0 10px 0 0;
    text-align: center;
    text-decoration: none;
    text-transform: uppercase;
    vertical-align: middle;
    width: 38px;
    position: relative;
    white-space: nowrap;
}
table.table td h2 span {
    color: #888;
    display: block;
    font-size: 12px;
    margin-top: 3px;
}
.avatar > img {
    border-radius: 50%;
    display: block;
    overflow: hidden;
    width: 100%;
}
img {
    vertical-align: middle;
    border-style: none;
}
.dataTables_filter, .dataTables_info { display: none; }
@media screen and (max-width : 1920px){
  .div-only-mobile{
  visibility:hidden;
  }
}
@media screen and (max-width : 906px){
 .desk{
  visibility:hidden;
  }
 .div-only-mobile{
  visibility:visible;
  }
  .viewtime{
      width: 200px !important;
  }
}

</style>

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-chart-line nav-icon"></i> ATTENDANCE</h4>
          <!-- <h1>Attendance</h1> -->
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Attendance</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
{{-- <form name="changeattendance" action="/changeattendance" method="get"> --}}
    <div class="row">
        <div class="col-md-4 col-12" id="dateDiv">
            <label><small>Date:</small></label>
            {{-- <form name="changedate" action="/attendance/{{Crypt::encrypt('dashboard')}}" method="get"> --}}
                <input type="text" id="currentDate" name="currentDate" width="176" />
            {{-- </form> --}}
        </div>
    </div>
{{-- </form> --}}
<br>
<div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="fa fa-search"></i></span>
    </div>
    <input type="text" id="myInput" class="form-control" placeholder="Search employee...">
</div>
<div class="card">
    <div class="card-body">
        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
            {{-- <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow: scroll"> --}}
            <div class="row">
                <div class="col-sm-12">

                    <br>
                    <span class="div-only-mobile bg-info row">Swipe left to view more informations</span>
                    <br>
                    <div class="row" style="overflow: scroll;">
            
                        <table id="example1" class="table table-bordered table-striped dataTable text-uppercase" role="grid" aria-describedby="example1_info">
                            <thead class="text-center bg-warning">
                                <tr>
                                    <th rowspan="2" style="width: 30%;">Employee</th>
                                    <th colspan="2">AM</th>
                                    <th colspan="2">PM</th>
                                </tr>
                                <tr>
                                    <th>IN</th>
                                    <th>OUT</th>
                                    <th>IN</th>
                                    <th>OUT</th>
                                </tr>
                            </thead>
                            <tbody id="attendancecontainer">
                                @foreach ($attendance as $att)
                                    <tr>
                                        <td>
                                            <h2 class="table-avatar">
                                                
                                                @php
                                                    $number = rand(1,3);
                                                    if(strtoupper($att->employeeinfo->gender) == 'FEMALE'){
                                                        $avatar = 'avatar/T(F) '.$number.'.png';
                                                    }
                                                    elseif(strtoupper($att->employeeinfo->gender) == 'MALE'){
                                                        $avatar = 'avatar/T(M) '.$number.'.png';
                                                    }else{
                                                        
                                                        $avatar = 'assets/images/avatars/unknown.png';
                                                    }
                                                @endphp
                                                <a href="#" class="avatar">
                                                        <img src="{{asset($att->employeeinfo->picurl)}}" alt="" onerror="this.onerror = null, this.src='{{asset($avatar)}}'"/>
                                                <a href="/employeeprofile/{{$att->employeeinfo->id}}"> {{$att->employeeinfo->firstname}} {{$att->employeeinfo->lastname}}<span>{{$att->employeeinfo->utype}}</span></a>
                                            </h2>
                                        </td>
                                        <td>
                                            <input id="timepickeramin{{$att->employeeinfo->id}}"  employeeid="{{$att->employeeinfo->id}}" datevalue="{{$currentdate}}" value="{{$att->attendance->in_am}}" class="timepick" name="am_in"/>
                                            <script>
                                                $(document).ready(function(){
                                                    var employeeid = $('#timepickeramin{{$att->employeeinfo->id}}').attr('employeeid');
                                                    $('#timepickeramin'+employeeid).timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});
                                                    $('#timepickeramin'+employeeid).on('change', function(){
                                                        var timepickeramin = $(this).val().split(':');
                                                        if(timepickeramin[0] == '00'){
                                                            $(this).val('12:'+timepickeramin[1])
                                                        }
                                                        $.ajax({
                                                            url: '/attendance/{{Crypt::encrypt('am_in')}}',
                                                            type:"GET",
                                                            dataType:"json",
                                                            data:{
                                                                employeeid:$(this).attr('employeeid'),
                                                                selecteddate:$(this).attr('datevalue'),
                                                                am_in:$(this).val()
                                                            },
                                                            success:function(data) {
                                                                $(this).val(data.am_in)
                                                            }
                                                        });
                                                    })
                                                })
                                            </script>
                                        </td>
                                        <td>
                                            <input id="timepickeramout{{$att->employeeinfo->id}}"  employeeid="{{$att->employeeinfo->id}}" datevalue="{{$currentdate}}" value="{{$att->attendance->out_am}}" class="timepick" name="am_out"/>
                                            <script>
                                                $(document).ready(function(){
                                                    var employeeid = $('#timepickeramout{{$att->employeeinfo->id}}').attr('employeeid');
                                                    $('#timepickeramout'+employeeid).timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});
                                                    $('#timepickeramout'+employeeid).on('change', function(){
                                                        var timepickeramout = $(this).val().split(':');
                                                        if(timepickeramout[0] == '00'){
                                                            $(this).val('12:'+timepickeramout[1])
                                                        }
                                                        $.ajax({
                                                            url: '/attendance/{{Crypt::encrypt('am_out')}}',
                                                            type:"GET",
                                                            dataType:"json",
                                                            data:{
                                                                employeeid:$(this).attr('employeeid'),
                                                                selecteddate:$(this).attr('datevalue'),
                                                                am_out:$(this).val()
                                                            },
                                                            success:function(data) {
                                                                $(this).val(data.am_out)
                                                            }
                                                        });
                                                    })
                                                })
                                            </script>
                                        </td>
                                        <td>
                                            <input id="timepickerpmin{{$att->employeeinfo->id}}"   employeeid="{{$att->employeeinfo->id}}" datevalue="{{$currentdate}}"value="{{$att->attendance->in_pm}}" class="timepick" name="pm_in"/>
                                            <script>
                                                $(document).ready(function(){
                                                    var employeeid = $('#timepickerpmin{{$att->employeeinfo->id}}').attr('employeeid');
                                                    $('#timepickerpmin'+employeeid).timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});
                                                    $('#timepickerpmin'+employeeid).on('change', function(){
                                                        var timepickerpmin = $(this).val().split(':');
                                                        if(timepickerpmin[0] == '00'){
                                                            $(this).val('12:'+timepickerpmin[1])
                                                        }
                                                        $.ajax({
                                                            url: '/attendance/{{Crypt::encrypt('pm_in')}}',
                                                            type:"GET",
                                                            dataType:"json",
                                                            data:{
                                                                employeeid:$(this).attr('employeeid'),
                                                                selecteddate:$(this).attr('datevalue'),
                                                                pm_in:$(this).val()
                                                            },
                                                            success:function(data) {
                                                                $(this).val(data.pm_in)
                                                            }
                                                        });
                                                    })
                                                })
                                            </script>
                                        </td>
                                        <td>
                                            <input id="timepickerpmout{{$att->employeeinfo->id}}"   employeeid="{{$att->employeeinfo->id}}" datevalue="{{$currentdate}}"value="{{$att->attendance->out_pm}}" class="timepick" name="pm_out"/>
                                            <script>
                                                $(document).ready(function(){
                                                    var employeeid = $('#timepickerpmout{{$att->employeeinfo->id}}').attr('employeeid');
                                                    $('#timepickerpmout'+employeeid).timepicker({ modal: false, header: false, footer: false, mode: 'ampm', format: 'HH:MM'});
                                                    $('#timepickerpmout'+employeeid).on('change', function(){
                                                        var timepickerpmout = $(this).val().split(':');
                                                        if(timepickerpmout[0] == '00'){
                                                            $(this).val('12:'+timepickerpmout[1])
                                                        }
                                                        $.ajax({
                                                            url: '/attendance/{{Crypt::encrypt('pm_out')}}',
                                                            type:"GET",
                                                            dataType:"json",
                                                            data:{
                                                                employeeid:$(this).attr('employeeid'),
                                                                selecteddate:$(this).attr('datevalue'),
                                                                pm_out:$(this).val()
                                                            },
                                                            success:function(data) {
                                                                $(this).val(data.pm_out)
                                                            }
                                                        });
                                                    })
                                                })
                                            </script>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script>
   $(function () {
    var table =  $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']],
        // scrollY:        "500px",
        // scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   true
        });
        // / #myInput is a <input type="text"> element
        $('#myInput').on( 'keyup', function () {
            table.search( this.value ).draw();
        } );
    });
    
    $(document).ready(function(){
        $('#currentDate').datepicker({
            format: 'mm-dd-yyyy',
            value: '{{$currentdate}}'
        });
        $('select[name=monthselection]').on('change', function(){
            // var monthid = $(this).val();
            // var yearid = $('select[name=yearselection]').val();
            $('form[name=changeattendance]').submit();
        })
        $('[data-dismiss=modal]').on('click', function (e) {
            var $t = $(this),
                target = $t[0].href || $t.data("target") || $t.parents('.modal') || [];

            $(target)
                .find("input,textarea,select")
                .val('')
                .end()
                .find("input[type=checkbox], input[type=radio]")
                .prop("checked", "")
                .end();
        });
        $('#currentDate').on('change', function(){
                $.ajax({
                    url: '/attendance/{{Crypt::encrypt('dashboard')}}',
                    type:"GET",
                    dataType:"json",
                    data:{
                        changedate:$(this).val()
                    },
                    success:function(data) {
            $('#attendancecontainer').empty();
                        $.each(data[1], function(key, value){
                            console.log(value)
                            $('#attendancecontainer').append(
                                '<tr>'+
                                    '<td>'+
                                        '<h2 class="table-avatar">'+
                                            '<a href="#" class="avatar">'+
                                                    '<img src="{{ asset('assets/images/avatars/256-512.png') }}" alt="" />'+
                                            '<a href="#"> '+value.employeeinfo.firstname+' '+value.employeeinfo.lastname+'<span>'+value.employeeinfo.utype+'</span></a>'+
                                        '</h2>'+
                                    '</td>'+
                                    '<td>'+
                                        '<input id="timepickeramin'+value.employeeinfo.id+'"  employeeid="'+value.employeeinfo.id+'" datevalue="'+data[0]+'" value="'+value.attendance.in_am+'" class="timepick" name="am_in"/>'+
                                        '<script type="text/javascript">'+
                                            '$(document).ready(function(){'+
                                                'var employeeid = $("#timepickeramin'+value.employeeinfo.id+'").attr("employeeid");'+
                                                '$("#timepickeramin"+employeeid).timepicker({ modal: false, header: false, footer: false, mode: "ampm", format: "HH:MM"});'+
                                                '$("#timepickeramin"+employeeid).on("change", function(){'+
                                                    'var timepickeramin = $(this).val().split(":");'+
                                                    'if(timepickeramin[0] == "00"){'+
                                                        '$(this).val("12:"+timepickeramin[1])'+
                                                    '}'+
                                                    '$.ajax({'+
                                                        'url: "/attendance/{{Crypt::encrypt("am_in")}}",'+
                                                        'type:"GET",'+
                                                        'dataType:"json",'+
                                                        'data:{'+
                                                            'employeeid:$(this).attr("employeeid"),'+
                                                            'selecteddate:$(this).attr("datevalue"),'+
                                                            'am_in:$(this).val()'+
                                                        '},'+
                                                        'success:function(data) {'+
                                                            '$(this).val(data.am_in)'+
                                                        '}'+
                                                    '});'+
                                                '})'+
                                            '})'+
                                        '</' + 'script>'+
                                    '</td>'+
                                    '<td>'+
                                        '<input id="timepickeramout'+value.employeeinfo.id+'"  employeeid="'+value.employeeinfo.id+'" datevalue="'+data[0]+'" value="'+value.attendance.out_am+'" class="timepick" name="am_out"/>'+
                                        '<script type="text/javascript">'+
                                            '$(document).ready(function(){'+
                                                'var employeeid = $("#timepickeramout'+value.employeeinfo.id+'").attr("employeeid");'+
                                                '$("#timepickeramout"+employeeid).timepicker({ modal: false, header: false, footer: false, mode: "ampm", format: "HH:MM"});'+
                                                '$("#timepickeramout"+employeeid).on("change", function(){'+
                                                    'var timepickeramout = $(this).val().split(":");'+
                                                    'if(timepickeramout[0] == "00"){'+
                                                        '$(this).val("12:"+timepickeramout[1])'+
                                                    '}'+
                                                    '$.ajax({'+
                                                        'url: "/attendance/{{Crypt::encrypt("am_out")}}",'+
                                                        'type:"GET",'+
                                                        'dataType:"json",'+
                                                        'data:{'+
                                                            'employeeid:$(this).attr("employeeid"),'+
                                                            'selecteddate:$(this).attr("datevalue"),'+
                                                            'am_out:$(this).val()'+
                                                        '},'+
                                                        'success:function(data) {'+
                                                            '$(this).val(data.am_out)'+
                                                        '}'+
                                                    '});'+
                                                '})'+
                                            '})'+
                                        '</' + 'script>'+
                                    '</td>'+
                                    '<td>'+
                                        '<input id="timepickerpmin'+value.employeeinfo.id+'"  employeeid="'+value.employeeinfo.id+'" datevalue="'+data[0]+'" value="'+value.attendance.in_pm+'" class="timepick" name="pm_in"/>'+
                                        '<script type="text/javascript">'+
                                            '$(document).ready(function(){'+
                                                'var employeeid = $("#timepickerpmin'+value.employeeinfo.id+'").attr("employeeid");'+
                                                '$("#timepickerpmin"+employeeid).timepicker({ modal: false, header: false, footer: false, mode: "ampm", format: "HH:MM"});'+
                                                '$("#timepickerpmin"+employeeid).on("change", function(){'+
                                                    'var timepickerpmin = $(this).val().split(":");'+
                                                    'if(timepickerpmin[0] == "00"){'+
                                                        '$(this).val("12:"+timepickerpmin[1])'+
                                                    '}'+
                                                    '$.ajax({'+
                                                        'url: "/attendance/{{Crypt::encrypt("pm_in")}}",'+
                                                        'type:"GET",'+
                                                        'dataType:"json",'+
                                                        'data:{'+
                                                            'employeeid:$(this).attr("employeeid"),'+
                                                            'selecteddate:$(this).attr("datevalue"),'+
                                                            'pm_in:$(this).val()'+
                                                        '},'+
                                                        'success:function(data) {'+
                                                            '$(this).val(data.pm_in)'+
                                                        '}'+
                                                    '});'+
                                                '})'+
                                            '})'+
                                        '</' + 'script>'+
                                    '</td>'+
                                    '<td>'+
                                        '<input id="timepickerpmout'+value.employeeinfo.id+'"  employeeid="'+value.employeeinfo.id+'" datevalue="'+data[0]+'" value="'+value.attendance.out_pm+'" class="timepick" name="pm_out"/>'+
                                        '<script type="text/javascript">'+
                                            '$(document).ready(function(){'+
                                                'var employeeid = $("#timepickerpmout'+value.employeeinfo.id+'").attr("employeeid");'+
                                                '$("#timepickerpmout"+employeeid).timepicker({ modal: false, header: false, footer: false, mode: "ampm", format: "HH:MM"});'+
                                                '$("#timepickerpmout"+employeeid).on("change", function(){'+
                                                    'var timepickerpmout = $(this).val().split(":");'+
                                                    'if(timepickerpmout[0] == "00"){'+
                                                        '$(this).val("12:"+timepickerpmout[1])'+
                                                    '}'+
                                                    '$.ajax({'+
                                                        'url: "/attendance/{{Crypt::encrypt("pm_out")}}",'+
                                                        'type:"GET",'+
                                                        'dataType:"json",'+
                                                        'data:{'+
                                                            'employeeid:$(this).attr("employeeid"),'+
                                                            'selecteddate:$(this).attr("datevalue"),'+
                                                            'pm_out:$(this).val()'+
                                                        '},'+
                                                        'success:function(data) {'+
                                                            '$(this).val(data.pm_out)'+
                                                        '}'+
                                                    '});'+
                                                '})'+
                                            '})'+
                                        '</' + 'script>'+
                                    '</td>'+
                                '</tr>'
                            )
                        })
            }
                });
        })
        
    })
  </script>
@endsection

