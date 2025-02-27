@extends('principalsportal.layouts.app2')
@section('content')

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<style>
    
    .donutTeachers{
        margin-top: 90px;
        margin: 0 auto;
        /* background: transparent url("{{asset('assets/images/corporate-grooming-20140726161024.jpg')}}") no-repeat  15% 60%; */
        background-size: 30%;
    }
    .donutStudents{
        margin-top: 90px;
        margin: 0 auto;
        /* background: transparent url("{{asset('assets/images/student-cartoon-png-2.png')}}") no-repeat  15% 60%; */
        background-size: 30%;
    }
</style>

<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<!-- InputMask -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Reports</h1>
                <h6>Attendance</h6>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Attendance</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="col-md-3">
                    <strong>Period</strong>
                    <form action="/summaryattendance/filter" method="get">
                        <input type="hidden" name="selectedgradelevel" value="{{$selectedgradelevel}}"/>
                        <input class="form-control form-control-sm" id="selectedperiod" name="selectedperiod" value="{{$periodfrom}} - {{$periodto}}"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>Students</strong>
                {{-- <button type="submit" class="btn btn-sm btn-default float-right"><i class="fa fa-print"></i> Print</button> --}}
            </div>
            <div class="card-body">
                {{-- <div class="row">
                    <div class="col-md-3">
                        <select name="selectedgradelevel" class="form-control form-control-sm">
                            <option value="all" {{'all' == $selectedgradelevel ? 'selected':''}}>All</option>
                            @foreach($gradelevels as $gradelevel)
                                <option value="{{$gradelevel->id}}" {{$gradelevel->id == $selectedgradelevel ? 'selected':''}}>{{$gradelevel->levelname}}</option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}
                <div class="chart">
                    <canvas id="barChartStudents" style="height:230px; min-height:230px"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>Departments</strong>
                {{-- <button type="submit" class="btn btn-sm btn-default float-right"><i class="fa fa-print"></i> Print</button> --}}
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="barChartDepartments" style="height:230px; min-height:230px"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready()
    $(function () {
      var areaChartDataGradeLevel = {
        labels  : [
            @foreach($attendancegradelevelsdata as $attdata)
                '{{strtoupper($attdata->gradelevel->levelname)}}',
            @endforeach
        ],
        datasets: [
          {
            label               : 'Male Absent',
            backgroundColor     : '#b3e0ff',
            borderColor         : '#b3e0ff',
            pointRadius         : false,
            pointColor          : '#b3e0ff',
            pointStrokeColor    : '#c1c7d1',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: '#b3e0ff',
            data                : [
                
                @foreach($attendancegradelevelsdata as $attdata)
                    @if($attdata->attendanceperdaystudmaleabsent == 0)
                        '0',
                    @else
                        '{{number_format($maleabsentpercentage = ((($attdata->attendanceperdaystudmaleabsent)/$attdata->noofdays)/$attdata->countmalebylevel)*100,2,".",",")}}',
                    @endif
                @endforeach
            ]
          },
          {
            label               : 'Male Present',
            backgroundColor     : '#0099ff',
            borderColor         : '#0099ff',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : '#0099ff',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: '#0099ff',
            data                : [

                @foreach($attendancegradelevelsdata as $attdata)
                    @if($attdata->attendanceperdaystudmalepresent == 0)
                        '0',
                    @else
                        '{{number_format($malepresentpercentage = ((($attdata->attendanceperdaystudmalepresent)/$attdata->noofdays)/$attdata->countmalebylevel)*100,2,".",",")}}',
                    @endif
                @endforeach
            ]
          },
          {
            label               : 'Female Present',
            backgroundColor     : '#ff66b3',
            borderColor         : '#ff66b3',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : '#ff66b3',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: '#ff66b3',
            data                : [
                @foreach($attendancegradelevelsdata as $attdata)
                    @if($attdata->attendanceperdaystudfemalepresent == 0)
                        '0',
                    @else
                        '{{number_format($femalepresentpercentage = ((($attdata->attendanceperdaystudfemalepresent)/$attdata->noofdays)/$attdata->countfemalebylevel)*100,2,".",",")}}',
                    @endif
                @endforeach
                ]
          },
          {
            label               : 'Female Absent',
            backgroundColor     : '#ffcce6',
            borderColor         : '#ffcce6',
            pointRadius         : false,
            pointColor          : '#ffcce6',
            pointStrokeColor    : '#c1c7d1',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: '#ffcce6',
            data                : [
                @foreach($attendancegradelevelsdata as $attdata)
                    @if($attdata->attendanceperdaystudfemaleabsent == 0)
                        '0',
                    @else
                        '{{number_format($femaleabsentpercentage = ((($attdata->attendanceperdaystudfemaleabsent)/$attdata->noofdays)/$attdata->countfemalebylevel)*100,2,".",",")}}',
                    @endif
                @endforeach
                ]
          },
        ]
      }
  
      var areaChartOptionsGradeLevel = {
        maintainAspectRatio : false,
        responsive : true,
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            gridLines : {
              display : false,
            }
          }],
          yAxes: [{
            gridLines : {
              display : false,
            },
            scaleLabel: {
                display: true,
                labelString: 'Percentage',
            }
          }]
        }
      }
  
      //-------------
      //- BAR CHART -
      //-------------
      var barChartCanvasGradeLevel = $('#barChartStudents').get(0).getContext('2d')
      var barChartDataGradeLevel = jQuery.extend(true, {}, areaChartDataGradeLevel)
      var temp0 = areaChartDataGradeLevel.datasets[0]
      var temp1 = areaChartDataGradeLevel.datasets[1]
      barChartDataGradeLevel.datasets[0] = temp1
      barChartDataGradeLevel.datasets[1] = temp0
  
      var barChartOptionsGradeLevel = {
        responsive              : true,
        maintainAspectRatio     : false,
        datasetFill             : false,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
      }
  
      var barChartGradeLevel = new Chart(barChartCanvasGradeLevel, {
        type: 'bar', 
        data: barChartDataGradeLevel,
        options: barChartOptionsGradeLevel
      });
  
      var areaChartDataDept = {
        labels  : [
            @foreach($attendancedepartmentsdata as $attdata)
                '{{strtoupper($attdata->department->department)}}',
            @endforeach
        ],
        datasets: [
          {
            label               : 'Male Absent',
            backgroundColor     : '#b3e0ff',
            borderColor         : '#b3e0ff',
            pointRadius         : false,
            pointColor          : '#b3e0ff',
            pointStrokeColor    : '#c1c7d1',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: '#b3e0ff',
            data                : [
                
                @foreach($attendancedepartmentsdata as $attdata)
                    @if($attdata->totalmaleabsent == 0)
                        '0',
                    @else
                        '{{number_format($maleabsentpercentage = ((($attdata->totalmaleabsent)/$attdata->noofdays)/$attdata->totalmalebydepartment)*100,2,".",",")}}',
                    @endif
                @endforeach
            ]
          },
          {
            label               : 'Male Present',
            backgroundColor     : '#0099ff',
            borderColor         : '#0099ff',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : '#0099ff',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: '#0099ff',
            data                : [

                @foreach($attendancedepartmentsdata as $attdata)
                    @if($attdata->totalmalepresent == 0)
                        '0',
                    @else
                        '{{number_format($malepresentpercentage = ((($attdata->totalmalepresent)/$attdata->noofdays)/$attdata->totalmalebydepartment)*100,2,".",",")}}',
                    @endif
                @endforeach
            ]
          },
          {
            label               : 'Female Present',
            backgroundColor     : '#ff66b3',
            borderColor         : '#ff66b3',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : '#ff66b3',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: '#ff66b3',
            data                : [
                @foreach($attendancedepartmentsdata as $attdata)
                    @if($attdata->totalfemalepresent == 0)
                        '0',
                    @else
                        '{{number_format($femalepresentpercentage = ((($attdata->totalfemalepresent)/$attdata->noofdays)/$attdata->totalfemalebydepartment)*100,2,".",",")}}',
                    @endif
                @endforeach
                ]
          },
          {
            label               : 'Female Absent',
            backgroundColor     : '#ffcce6',
            borderColor         : '#ffcce6',
            pointRadius         : false,
            pointColor          : '#ffcce6',
            pointStrokeColor    : '#c1c7d1',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: '#ffcce6',
            data                : [
                @foreach($attendancedepartmentsdata as $attdata)
                    @if($attdata->totalfemaleabsent == 0)
                        '0',
                    @else
                        '{{number_format($femaleabsentpercentage = ((($attdata->totalfemaleabsent)/$attdata->noofdays)/$attdata->totalfemalebydepartment)*100,2,".",",")}}',
                    @endif
                @endforeach
                ]
          },
        ]
      }
  
      var areaChartOptionsDept = {
        maintainAspectRatio : false,
        responsive : true,
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            gridLines : {
              display : false,
            }
          }],
          yAxes: [{
            gridLines : {
              display : false,
            }
          }]
        }
      }
  
      //-------------
      //- BAR CHART -
      //-------------
      var barChartCanvasDept = $('#barChartDepartments').get(0).getContext('2d')
      var barChartDataDept = jQuery.extend(true, {}, areaChartDataDept)
      var temp0 = areaChartDataDept.datasets[0]
      var temp1 = areaChartDataDept.datasets[1]
      barChartDataDept.datasets[0] = temp1
      barChartDataDept.datasets[1] = temp0
  
      var barChartOptionsDept = {
        responsive              : true,
        maintainAspectRatio     : false,
        datasetFill             : false,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
      }
  
      var barChartDept = new Chart(barChartCanvasDept, {
        type: 'bar', 
        data: barChartDataDept,
        options: barChartOptionsDept
      });

    })
    $(function () {
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });

        $('#selectedperiod').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            autoclose: 'true',
            todayBtn: 'true',
            todayHighlight: 'true',
            orientation: 'auto top'
        }).on('change', function() {
            //
            $(this).closest('form').submit();
            //
        })
    });

    // $(document).on('change','input[name="selectedperiod"]', function(){
    //     $(this).closest('form').submit();
    // });

    $(document).on('change','select[name="selectedgradelevel"]', function(){
        $(this).closest('form').submit();
    });
</script>
@endsection