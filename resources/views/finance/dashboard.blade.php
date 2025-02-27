@extends('finance.layouts.app')

@section('content')
<style>
    .widget-user .widget-user-image > img {
        border: hidden;
    }
    .donutTeachers{
        margin-top: 90px;
        margin: 0 auto;
        background: transparent url("{{asset('assets/images/corporate-grooming-20140726161024.jpg')}}") no-repeat  50% 80%;
        background-size: 30%;
    }
    .donutStudents{
        margin-top: 90px;
        margin: 0 auto;
        background: transparent url("{{asset('assets/images/student-cartoon-png-2.png')}}") no-repeat  50% 80%;
        background-size: 30%;
    }
</style>
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-12">
            <div class="card card-primary">
            <div class="card-header bg-orange">
              <h3 class="card-title text-white">Amount Paid </h3>
            </div>
            <div class="card-body">
              <div class="chart">
                <canvas id="areaChart" ></canvas>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          
        <div class="row">
        <div class="col-md-6 col-12">
            <!-- DONUT CHART -->
            <div class="card card-danger">
              <div class="card-header bg-warning">
                <small class="text-uppercase">Total no. of Teachers per Academic Program</small>
              </div>
              <div class="card-body p-0">
                  <div class="donutTeachers">
                    <canvas id="donutChartTeachers" style="height:230px; min-height:230px"></canvas>
                  </div>
                  
              </div>
              <!-- /.card-body -->
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card card-danger">
              <div class="card-header bg-warning">
                <small class="text-uppercase">Total no. of Students per Academic Program</small>
              </div>
              <div class="card-body p-0">
                  <div class="donutStudents">
                        <canvas id="donutChartStudents" style="height:230px; min-height:230px"></canvas>
                  </div>
                  
              </div>
              <!-- /.card-body -->
            </div>
        </div>
        </div>
        </div>
        {{-- <div class="col-md-4 col-12">
        </div> --}}
        <div class="col-md-2">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-header bg-success">
                    @php
                        $stringname = explode(" ",'Payment Setup');  
                        $ps = "";
                        foreach ($stringname as $w) {
                            $ps .= $w[0];
                        }
                    @endphp
                    <div class="text-center">
                        {{-- <div class="profile-user-img img-fluid img-circle"> --}}
                            <h1>{{$ps}}</h1>
                        {{-- </div> --}}
                    </div>
                    {{-- <h3 class="profile-username text-center">Payment Setup</h3> --}}
        
                    <p class="text-center text-white">Payment Setup</p>
                </div>
                <div class="card-body box-profile">

                    <ul class="list-group list-group-unbordered">
                        @foreach ($setup as $levelsetup)
                            @if($levelsetup->setup == 0)
                                <li class="list-group-item pt-1 pb-1">
                                    <button class="btn btn-sm btn-block btn-danger">
                                    <b>{{$levelsetup->levelname}}</b> <i class="fa fa-times float-right text-white"></i>
                                    </button>
                                </li>
                            @else
                                <li class="list-group-item pt-1 pb-1">
                                    <button class="btn btn-sm btn-block btn-light">
                                    <b>{{$levelsetup->levelname}}</b> <i class="fa fa-check float-right text-success"></i>
                                    </button>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
        </div>
        
      <!-- /.col -->
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>


<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<script>
    
    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvasTeachers = $('#donutChartTeachers').get(0).getContext('2d');
    var donutDataTeachers        = {
      labels: [
          'Pre-school',
          'Elementary', 
          'Junior High', 
          'Senior High'
      ],
      datasets: [
        {
          data: ['{{$preschoolTeachers}}','{{$elemTeachers}}','{{$juniorHighTeachers}}','{{$seniorHighTeachers}}'],
          backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef'],
        }
      ]
    }
    var donutOptionsTeachers     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var donutChartTeachers = new Chart(donutChartCanvasTeachers, {
      type: 'doughnut',
      data: donutDataTeachers,
      options: donutOptionsTeachers      
    })

    var donutChartCanvasStudents = $('#donutChartStudents').get(0).getContext('2d');
    var donutDataStudents        = {
      labels: [
          'Pre-school',
          'Elementary', 
          'Junior High', 
          'Senior High'
      ],
      datasets: [
        {
          data: ['{{$preschoolStudents}}','{{$elemStudents}}','{{$juniorHighStudents}}','{{$seniorHighStudents}}'],
          backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef'],
        }
      ]
    }
    var donutOptionsStudents     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var donutChartStudents = new Chart(donutChartCanvasStudents, {
      type: 'doughnut',
      data: donutDataStudents,
      options: donutOptionsStudents      
    })


//declar array
var january = [];
var february = [];
var march = [];
var april = [];
var may = [];
var june = [];
var july = [];
var august = [];
var september = [];
var october = [];
var november = [];
var december = [];
//loop
@foreach($jan as $jandata)
    january.push('{{$jandata->amount}}');
@endforeach
@foreach($feb as $febdata)
    february.push('{{$febdata->amount}}');
@endforeach
@foreach($mar as $mardata)
    march.push('{{$mardata->amount}}');
@endforeach
@foreach($apr as $aprdata)
    april.push('{{$aprdata->amount}}');
@endforeach
@foreach($may as $maydata)
    may.push('{{$maydata->amount}}');
@endforeach
@foreach($jun as $jundata)
    june.push('{{$jundata->amount}}');
@endforeach
@foreach($jul as $juldata)
    july.push('{{$juldata->amount}}');
@endforeach
@foreach($aug as $augdata)
    august.push('{{$augdata->amount}}');
@endforeach
@foreach($sep as $sepdata)
    september.push('{{$sepdata->amount}}');
@endforeach
@foreach($oct as $octdata)
    october.push('{{$octdata->amount}}');
@endforeach
@foreach($nov as $novdata)
    november.push('{{$novdata->amount}}');
@endforeach
@foreach($dec as $decdata)
    december.push('{{$decdata->amount}}');
@endforeach
//append data to array

    var data = {
        labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31],
        // labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        datasets: [
            {
                label: "Jan",
                // fillColor: "rgba(220,220,220,0.2)",
                // strokeColor: "rgba(220,220,220,1)",
                // pointColor: "rgba(220,220,220,1)",
                // pointStrokeColor: "#fff",
                // pointHighlightFill: "#fff",
                // pointHighlightStroke: "rgba(220,220,220,1)",
                fill: false,
                borderColor: "#781c2e",
                //    borderDash: [5, 5],
                backgroundColor: "#781c2e",
                pointBackgroundColor: "#781c2e",
                pointBorderColor: "#781c2e",
                pointHoverBackgroundColor: "#781c2e",
                pointHoverBorderColor: "#781c2e",
                data: january
            },
            {
                label: "Feb",
                fill: false,
                borderColor: "#9966cc",
                //    borderDash: [5, 5],
                backgroundColor: "#9966cc",
                pointBackgroundColor: "#9966cc",
                pointBorderColor: "#9966cc",
                pointHoverBackgroundColor: "#9966cc",
                pointHoverBorderColor: "#9966cc",
                data: february
            },
            {
                label: "Mar",
                fill: false,
                borderColor: "#7fffd4",
                //    borderDash: [5, 5],
                backgroundColor: "#7fffd4",
                pointBackgroundColor: "#7fffd4",
                pointBorderColor: "#7fffd4",
                pointHoverBackgroundColor: "#7fffd4",
                pointHoverBorderColor: "#7fffd4",
                data: march
            },
            {
                label: "Apr",
                fill: false,
                borderColor: "#b9f2ff",
                //    borderDash: [5, 5],
                backgroundColor: "#b9f2ff",
                pointBackgroundColor: "#b9f2ff",
                pointBorderColor: "#b9f2ff",
                pointHoverBackgroundColor: "#b9f2ff",
                pointHoverBorderColor: "#b9f2ff",
                data: april
            },
            {
                label: "May",
                fill: false,
                borderColor: "#50c878",
                //    borderDash: [5, 5],
                backgroundColor: "#50c878",
                pointBackgroundColor: "#50c878",
                pointBorderColor: "#50c878",
                pointHoverBackgroundColor: "#50c878",
                pointHoverBorderColor: "#50c878",
                data: may
            },
            {
                label: "Jun",
                fill: false,
                borderColor: "#eae0c8",
                //    borderDash: [5, 5],
                backgroundColor: "#eae0c8",
                pointBackgroundColor: "#eae0c8",
                pointBorderColor: "#eae0c8",
                pointHoverBackgroundColor: "#eae0c8",
                pointHoverBorderColor: "#eae0c8",
                data: june
            },
            {
                label: "Jul",
                fill: false,
                borderColor: "#E0115F",
                //    borderDash: [5, 5],
                backgroundColor: "#E0115F",
                pointBackgroundColor: "#E0115F",
                pointBorderColor: "#E0115F",
                pointHoverBackgroundColor: "#E0115F",
                pointHoverBorderColor: "#E0115F",
                data: july
            },
            {
                label: "Aug",
                fill: false,
                borderColor: "#e6e200",
                //    borderDash: [5, 5],
                backgroundColor: "#e6e200",
                pointBackgroundColor: "#e6e200",
                pointBorderColor: "#e6e200",
                pointHoverBackgroundColor: "#e6e200",
                pointHoverBorderColor: "#e6e200",
                data: august
            },
            {
                label: "Sep",
                fill: false,
                borderColor: "#0f52ba",
                //    borderDash: [5, 5],
                backgroundColor: "#0f52ba",
                pointBackgroundColor: "#0f52ba",
                pointBorderColor: "#0f52ba",
                pointHoverBackgroundColor: "#0f52ba",
                pointHoverBorderColor: "#0f52ba",
                data: september
            },
            {
                label: "Oct",
                fill: false,
                borderColor: "#a8c3bc",
                //    borderDash: [5, 5],
                backgroundColor: "#a8c3bc",
                pointBackgroundColor: "#a8c3bc",
                pointBorderColor: "#a8c3bc",
                pointHoverBackgroundColor: "#a8c3bc",
                pointHoverBorderColor: "#a8c3bc",
                data: october
            },
            {
                label: "Nov",
                fill: false,
                borderColor: "#ffc87c",
                //    borderDash: [5, 5],
                backgroundColor: "#ffc87c",
                pointBackgroundColor: "#ffc87c",
                pointBorderColor: "#ffc87c",
                pointHoverBackgroundColor: "#ffc87c",
                pointHoverBorderColor: "#ffc87c",
                data: november
            },
            {
                label: "Dec",
                fill: false,
                borderColor: "#ddd",
                //    borderDash: [5, 5],
                backgroundColor: "#40E0D0",
                pointBackgroundColor: "#40E0D0",
                pointBorderColor: "#40E0D0",
                pointHoverBackgroundColor: "#40E0D0",
                pointHoverBorderColor: "#40E0D0",
                data: december
            }
        ]
    };
    var ctx = document.getElementById("areaChart").getContext("2d");
    var options = { };
    var lineChart = new Chart(ctx, {
      type: 'line',
      data: data,
      options: options      
    })

</script>
@endsection