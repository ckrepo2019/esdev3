@extends('principalsportal.layouts.app2')

@section('pagespecificscripts')
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
@endsection


@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
                <h3 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                    <span>{{strtoupper($studentInfo[0]->lastname)}}, {{strtoupper($studentInfo[0]->firstname)}} {{$studentInfo[0]->lastname}}</span>
                </h3>
            <!-- <h1 class="m-0 text-dark">{{strtoupper($studentInfo[0]->lastname)}}, {{strtoupper($studentInfo[0]->firstname)}} {{$studentInfo[0]->lastname}}</h1> -->
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">
                  {{-- <a href="/principalPortalStudentSHS/{{Crypt::encrypt($)}}">Students</a> --}}
                @if($studentInfo[0]->acadprogid == 2)
                    <a href="/principalPortalStudentPS/{{Crypt::encrypt($studentInfo[0]->acadprogid)}}">Students</a>
                @elseif($studentInfo[0]->acadprogid == 3)
                    <a href="/principalPortalStudentGS/{{Crypt::encrypt($studentInfo[0]->acadprogid)}}">Students</a>
                @elseif($studentInfo[0]->acadprogid == 4)
                    <a href="/principalPortalStudentJNS/{{Crypt::encrypt($studentInfo[0]->acadprogid)}}">Students</a>
                @elseif($studentInfo[0]->acadprogid == 5)
                    <a href="/principalPortalStudentSHS/{{Crypt::encrypt($studentInfo[0]->acadprogid)}}">Students</a>
                  @endif
                
              </li>
              <li class="breadcrumb-item active">{{strtoupper($studentInfo[0]->lastname)}}, {{strtoupper($studentInfo[0]->firstname)}} {{$studentInfo[0]->lastname}}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div>
</section>

<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <div class="row" >
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header bg-info">
                                    <h3 class="card-title">
                                        <i class="fas fa-edit"></i>Grade Report
                                    </h3>
                                </div>
                                <div class="card-body p-0">
                                    <table class="mb-0 table table-bordered" style="min-width:330px">
                                        <thead>
                                            <tr>

                                                @if($studentInfo[0]->acadprogid != 5 )

                                                    <td class="p-1 align-middle text-center" rowspan="2" width="35%"><small>SUBJECTS</small></td>

                                                @else

                                                    <td class="p-1 align-middle text-center" rowspan="2" width="55%"><small>SUBJECTS</small></td>

                                                @endif

                                                @if($studentInfo[0]->acadprogid != 5 )
                                                
                                                    <td class="p-1 align-middle" align="center" colspan="4" width="40%"><small>PERIODIC RATINGS</small></td>

                                                @else

                                                    <td class="p-1 align-middle" align="center" colspan="2" width="20%"><small>PERIODIC RATINGS</small></td>

                                                @endif

                                                <td class="p-1 align-middle" align="center" rowspan="2" width="10%"><small>Final<br>Rating</small></td>

                                                <td class="p-1 align-middle" align="center" rowspan="2" width="15%"><small>Action<br>Taken</small></td>
                
                                            </tr>
                                            <tr align="center">

                                                @if($studentInfo[0]->acadprogid != 5 )
                                                    <td class="p-1"><small>1</small></td>
                                                    <td class="p-1"><small>2</small></td>
                                                    <td class="p-1"><small>3</small></td>
                                                    <td class="p-1"><small>4</small></td>
                                                @else
                                                    @if(DB::table('semester')->where('id',1)->first()->isactive == 1)
                                                        <td class="p-1"><small>1</small></td>
                                                        <td class="p-1"><small>2</small></td>
                                                    @elseif(DB::table('semester')->where('id',2)->first()->isactive == 1)
                                                        <td class="p-1"><small>3</small></td>
                                                        <td class="p-1"><small>4</small></td>
                                                    @endif

                                                @endif

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($finalGrades as $finalGrade)
                                                <tr>
                                                    <td class="p-2 @if($finalGrade->mapeh == 1) pl-4 @endif"><small>{{$finalGrade->subjectcode}} </small></td>
                                                    <td class="p-2 align-middle text-center"><small>{{$finalGrade->quarter1}}</small></td>
                                                    <td class="p-2 align-middle text-center"><small>{{$finalGrade->quarter2}}</small></td>

                                                    @if($studentInfo[0]->acadprogid != 5 )

                                                        <td class="p-2 align-middle text-center"><small>{{$finalGrade->quarter3}}</small></td>
                                                        <td class="p-2 align-middle text-center"><small>{{$finalGrade->quarter4}}</small></td>

                                                    @endif

                                                    <td class="p-2 align-middle text-center"><small>
                                                        @if($finalGrade->finalRating != null)
                                                            {{$finalGrade->finalRating}}</small>
                                                        @else

                                                        @endif
                                                    
                                                    </td>

                                                    @if($finalGrade->remarks=="PASSED")
                                                        <td class="p-2 align-middle text-center "><small>PASSED</small></td>
                                                    @elseif($finalGrade->remarks=="FAILED")
                                                        <td class="p-2 align-middle text-center "><small>FAILED</small></td>
                                                    @else
                                                        <td class="p-2 align-middle text-center"><small></small></td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td class="p-2"><small>GEN. AVE.</small></td>
                                        
                                                @if($generalave[0]->quarter1 != null)
                                                    <td class="p-2 align-middle text-center"><small>{{$generalave[0]->quarter1}}</small></td>
                                                @else
                                                    <td></td>
                                                @endif
                                                @if($generalave[0]->quarter2 != null)
                                                    <td class="p-2 align-middle text-center"><small>{{$generalave[0]->quarter2}}</small></td>
                                                @else
                                                    <td></td>
                                                @endif

                                                @if($studentInfo[0]->acadprogid != 5 )

                                                    @if($generalave[0]->quarter3 != null)
                                                        <td  class="p-2 align-middle text-center"><small>{{$generalave[0]->quarter3}}</<small></td>
                                                    @else
                                                        <td></td>
                                                    @endif

                                                    @if($generalave[0]->quarter4 != null)
                                                        <td  class="p-2 align-middle text-center"><small>{{$generalave[0]->quarter4}}</small></td>
                                                    @else
                                                        <td></td>
                                                    @endif

                                                @endif

                                                @if($generalave[0]->Final != null)
                                                    <td  class="p-2 align-middle text-center"><small>{{$generalave[0]->Final}}</small></td>
                                                @else
                                                    <td></td>
                                                @endif
                                                        
                                                @if($generalave[0]->Final != null && $generalave[0]->Final  > 75)
                                                    <td class="p-2 align-middle text-center"><small>PASSED<small></td>
                                                @elseif($generalave[0]->Final != null  && $generalave[0]->Final  < 75)
                                                    <td class="p-2 align-middle text-center"><small>PASSED<small></td>
                                                @else
                                                    <td><small><small></td>
                                                @endif

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card" >
                                <div class="card-header bg-primary">
                                    <h3 class="card-title">
                                        <i class="fas fa-edit"></i>Grade Report Graph
                                    </h3>
                                </div>
                                <div class="card-body">
                                        <canvas id="gradeChart" width="400" height="500px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info">
                                    <h3 class="card-title">
                                        <i class="fas fa-edit"></i>Daily Attendance Report
                                    </h3>
                                </div>
                                <div class="card-body table-responsive p-0" style="height: 330px;">
                                    
                                        <table class="table">
                                            <thead class="bg-warning">
                                                <tr>
                                                    <th><small>Date</small></th>
                                                    <th><small>Status</small></th>
                                                    <th><small>Time</small></th>
                                                <tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $countLate = 0;
                                                    $countOnTime = 0;
                                                @endphp
                                                @foreach(array_reverse($attSum) as $item)
                                                    @foreach($item->days->reverse() as $dayitem)
                                                            <tr>
                                                                @if($dayitem->attendance==0)

                                                                    <td>{{\Carbon\Carbon::create($dayitem->day)->isoFormat('MMM DD, YYYY')}}</td>
                                                                    <td class="text-danger">Absent</td>
                                                                

                                                                @elseif($dayitem->attendance==1)
                                                                    <td class="text-success">{{\Carbon\Carbon::create($dayitem->day)->isoFormat('MMM DD, YYYY')}}</td>
                                                                    @php
                                                                        $countOnTime += 1;
                                                                    @endphp
                                                                    <td class="text-success">Present</td>

                                                                @elseif($dayitem->attendance==2)

                                                                    <td class="text-success">{{\Carbon\Carbon::create($dayitem->day)->isoFormat('MMM DD, YYYY')}}</td>
                                                                    <td class="text-danger">Absent</td>


                                                                @elseif($dayitem->attendance==3)

                                                                    @php
                                                                        $countLate += 1;
                                                                    @endphp

                                                                    <td class="text-success">{{\Carbon\Carbon::create($dayitem->day)->isoFormat('MMM DD, YYYY')}}</td>
                                                                    <td class="text-warning">Late</td>

                                                                @elseif($dayitem->attendance==4)

                                                                    <td class="text-success">{{\Carbon\Carbon::create($dayitem->day)->isoFormat('MMM DD, YYYY')}}</td>
                                                                    <td class="text-warning"></td>
            
                                                                @endif

                                                                @if($dayitem->time == 1)

                                                                    <td class="text-primary">Beadle</td>

                                                                @else

                                                                    @if($dayitem->time=='00:00:00')
                                                                    
                                                                        <td></td>
                                                                    
                                                                    @else

                                                                        <td class="text-info">{{\Carbon\Carbon::create($dayitem->time)->isoFormat('hh:mm a')}}</td>

                                                                    @endif

                                                                   
                                                                    
                                                                @endif
                                                            </tr>
                                                     
                                                    @endforeach
                                                @endforeach

                                                {{-- @foreach ($dailyReport as $attendance)
                                                <tr>
                                                    <td class="p-2" width="80%">{{$attendance->date}}</td>
                                                    @if($attendance->status=="late")
                                                            <td class="p-2" width="10%"><span style="width:70px" class="float-right badge badge-warning badge-pill">{{$attendance->status}}
                                                        </span></td>
                                                    @elseif($attendance->status=="on time")
                                                        <td class="p-2" width="10%"><span  style="width:70px" class="float-right badge badge-success badge-pill">{{$attendance->status}}
                                                        </span></td>
                                                    @else
                                                        <td class="p-2" width="10%"><span  style="width:70px" class="float-right badge badge-danger badge-pill">{{$attendance->status}}
                                                        </span></td>
                                                    @endif
            
                                                    @if($attendance->status!="absent")
                                                        <td class="p-2" width="10%"><span class="mr-2 float-right badge badge-primary badge-pill">{{$attendance->time}}</span></td>
                                                    @else 
                                                        <td class="p-2" width="10%"><span class="mr-2 float-right badge badge-primary badge-pill">00:00</span></td>
                                                    @endif
            
                                                </tr>
                                                @endforeach --}}
                                            </tbody>
                                        </table>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-primary">
                                    <h3 class="card-title">
                                        <i class="fas fa-edit"></i>Attendance Graph
                                    </h3>
                                </div>
                                <div class="card-body h-100">
                                    <canvas id="myChart" width="400" height="370px"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        {{-- <div class="col-md-6 col-12">
                            <div class="card">
                                <div class="card-header bg-success">
                                    <h3 class="card-title">
                                        <i class="fas fa-edit"></i>Yearly Attendance Report
                                    </h3>
                                </div>
                                <div class="card-body table-responsive p-0" style="height: 330px;">
                                    <table class="mb-0 table table-bordered">
                                        <thead class="bg-warning">
                                            <tr>
                                                <th width=25% class="align-middle h6"><small>Month</small></th>
                                                <th width=25% class="align-middle h6"><small>School Days</small></th>
                                                <th width=25% class="align-middle h6"><small>Days Present</small></th>
                                                <th width=25% class="align-middle h6"><small>Days Absent</small></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($monthlyReports as $monthlyReport)
                                                <tr>
                                                    <td>{{$monthlyReport->month}} </td>
                                                    <td>{{$monthlyReport->numDays}}</td>
                                                    <td>{{$monthlyReport->present}}</td>
                                                    <td>{{$monthlyReport->absent}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-footer">
                                    <table class="table mb-0">
                                        <tr>
                                            <th width=25%>Total</th>
                                            <td width=25%>{{$yearlyReport->numDays}}</td>
                                            <td width=25%>{{$yearlyReport->present}}</td>
                                            <td width=25%>{{$yearlyReport->absent}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-success">
                                    <h3 class="card-title">
                                        <i class="fas fa-edit"></i>Yearly Attendance Report
                                    </h3>
                                </div>
                                <div class="card-body table-responsive p-0" style="height: 330px;">
                                    <table class="table">
                                        <thead class="bg-warning">
                                            <tr>
                                                <th width=25% class="align-middle h6"><small>Month</small></th>
                                                <th width=25% class="align-middle h6"><small>School Days</small></th>
                                                <th width=25% class="align-middle h6"><small>Days Present</small></th>
                                                <th width=25% class="align-middle h6"><small>Days Absent</small></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            @php
                                                $totalDays = 0;
                                                $totalAbsent = 0;
                                                $totalPresent = 0;
                                            @endphp

                                            @foreach($attSum as $monthlyReport)
                                                <tr>
                                                    <td>{{$monthlyReport->month}} </td>
                                                    <td>{{$monthlyReport->count}}</td>
                                                    <td>{{$monthlyReport->countPresent}}</td>
                                                    <td>{{$monthlyReport->countAbsent}}</td>
                                                </tr>
                                                @php
                                                    $totalDays += $monthlyReport->count;
                                                    $totalAbsent += $monthlyReport->countAbsent;
                                                    $totalPresent += $monthlyReport->countPresent;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="table-footer">
                                    <table class="table mb-0">
                                        <tr>
                                            <th width=25%>Total</th>
                                            <td width=25%>{{$totalDays}}</td>
                                            <td width=25%>{{$totalPresent}}</td>
                                            <td width=25%>{{$totalAbsent}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card card-primary">
                        <div class="card-header bg-success">
                        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i>About Student</h3>
                        <br>
                        </div>
                        <div class="card-body">
                            
                            <strong><i class="fas fa-user mr-1"></i>Student Name</strong>
                            <p class="small" style="color: #af8402">
                                {{strtoupper($studentInfo[0]->lastname)}}, {{strtoupper($studentInfo[0]->firstname)}} {{$studentInfo[0]->lastname}}
                            </p>
                            <hr>
                            <strong><i class="fas fa-user mr-1"></i>Grade Level</strong>
                            <p class="small" style="color: #af8402">
                                {{strtoupper($studentInfo[0]->levelname)}}
                            </p>
                            <hr>
                            <strong><i class="fas fa-user mr-1"></i>Section</strong>
                            <p class="small" style="color: #af8402">
                                {{strtoupper($studentInfo[0]->sectionname)}}
                            </p>
                            <hr>
                            
                            <strong><i class="fas fa-user mr-1"></i>Advicer</strong>
                            <p class="small" style="color: #af8402">
                                @if($studentInfo[0]->teacherlastname != null)
                                    {{$studentInfo[0]->teacherlastname}}, {{$studentInfo[0]->teacherfirstname}} {{$studentInfo[0]->teachermiddlename}}
                                @else
                                    NO ADVICER ASSIGNED
                                @endif
                            </p>
                            <hr>
                            <strong><i class="fas fa-user mr-1"></i>Date Enrolled</strong>
                            <p class="small" style="color: #af8402">
                                {{\Carbon\Carbon::create($studentInfo[0]->dateenrolled)->isoFormat('MMM DD, YYYY')}}
                            </p>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>

    <script>
         $(document).ready(function(){
          
           
           
            var ctx = document.getElementById('myChart');
            if($(window).width()<500){
                ctx.height = 500;
                
            }
            if($(window).width()>500){
                $('.card').addClass('h-100')
                
            }
         

            var nomi = [2017];

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: nomi,
                    datasets: [{
                        label: 'School Days',
                        data: ['{{$totalDays}}'],
                        backgroundColor: "#007bff",
                        fill: false,
                        borderColor: "#007bff",
                        borderWidth: 3
                    },
                    {
                        label: 'Present',
                        data: ['{{$totalPresent}}'],
                        backgroundColor: "#28a745",
                        fill: false,
                        borderColor: "#28a745",
                        borderWidth: 3
                    },
                    {
                        label: 'Absent',
                        data: ['{{$totalAbsent}}'],
                        backgroundColor: "#dc3545",
                        fill: false,
                        borderColor: "#dc3545",
                        borderWidth: 3
                    },
                    {
                        label: 'Ontime',
                        data: ['{{$countOnTime}}'],
                        backgroundColor: '#28a745',
                        fill: false,
                        borderColor: '#28a745',
                        borderWidth: 3
                    },
                    {
                        label: 'Late',
                        data: ['{{$countLate}}'],
                        backgroundColor: '#17a2b8',
                        fill: false,
                        borderColor: '#17a2b8',
                        borderWidth: 3
                    }       
                    ]
                },
                options: {
                    
                    legend: {
                        display : true,
                        position : "bottom"
                    },
                   
                    hover: {
                        animationDuration: 0
                    },
                    animation: {
                        duration: 1,
                        onComplete: function () {
                            var chartInstance = this.chart,
                                ctx = chartInstance.ctx;
                            ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';
                
                            this.data.datasets.forEach(function (dataset, i)
                            {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];
                                    ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                });
                            });
                        }
                    },
                    
                }
            });
     
    })
</script>
<script>
        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
       
        var gradedata = [];
       

        @foreach($finalGrades as $item)
        var quarterGrade = [];

            @if($item->quarter1!="")
                quarterGrade.push('{{$item->quarter1}}')
            @endif

            @if($item->quarter2!="")
                quarterGrade.push('{{$item->quarter2}}')
            @endif

            @if($studentInfo[0]->acadprogid != 5)

                @if($item->quarter3!="")
                    quarterGrade.push('{{$item->quarter3}}')
                @endif

                @if($item->quarter4!="")
                    quarterGrade.push('{{$item->quarter4}}')
                @endif

            @endif

            gradedata.push({
                    lineTension:.5,
                    label:'{{$item->subjectcode}}',
                    data:quarterGrade,
                    borderColor:getRandomColor(),
                    fill: false,
                })
            console.log(quarterGrade);
        @endforeach
        
        console.log( gradedata);

        var ctxL = document.getElementById("gradeChart");

        var labels = [];


        @if($studentInfo[0]->acadprogid != 5)
                labels = ["1st","2nd","3rd","4th"];
        @else
            @if(Session::get('semester')->id == 1)
                labels = ["1st","2nd"]
            @elseif(Session::get('semester')->id == 2)
                labels = ["3rd","4th"]
            @endif
        @endif

        if($(window).width()<500){
            ctxL.height = 500;
        }

        var myLineChart = new Chart(ctxL, {
        type: 'line',
        data: {
            labels: labels,
            datasets:gradedata
        },
        options: {
            legend:{
                display:true,
                position:'bottom',
                labels: {
                    usePointStyle: true,
                }
            },
            scales: {
                   
                    yAxes: [{
                            display: true,
                            ticks: {
                                beginAtZero: true,
                                steps: 10,
                                stepValue: 5,
                                max: 100,
                                min: 60
                            }
                        }]
                },
            responsive: true
        }
        });

</script>

@endsection
