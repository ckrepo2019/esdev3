
    <div class="row">
        <div class="col-md-4">
        
            <div class="card" style="border: none !important; box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="../../dist/img/user4-128x128.jpg" alt="User profile picture">
                    </div>
                    <h3 class="profile-username text-center">{{$employeeinfo->firstname}} {{$employeeinfo->middlename[0] ? $employeeinfo->middlename[0].'.' : ''}} {{$employeeinfo->lastname}} {{$employeeinfo->suffix}}</h3>
                    <p class="text-muted text-center">
                        @if(count($promotions) == 0)
                            {{$currentdesignation ? $currentdesignation->utype : ''}}
                        @else
                            {{$promotions[0]->utype}}
                        @endif
                    </p>
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <small class="text-bold">Times Promoted</small> <a class="float-right">{{count($promotions)}}</a>
                        </li>
                        <li class="list-group-item">
                            <small class="text-bold">No. of Years in the Service</small> <a class="float-right"></a>
                        </li>
                        <li class="list-group-item">
                            <small class="text-bold">No. of years in the Institution</small> <a class="float-right"></a>
                        </li>
                    </ul>
                    {{-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> --}}
                </div>            
            </div>       
        </div>
        <div class="col-md-8">
            <div class="card" style="border: none !important; box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        {{-- <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li> --}}
                        <li class="nav-item"><a class="nav-link active" href="#timeline" data-toggle="tab">Timeline</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        {{-- <div class="active tab-pane" id="activity">
                            <div class="row">
                                <div class="col-md-9">
                                    <label>Promote to:</label>
                                    <select class="form-control select2" id="select-usertype">
                                        @foreach($usertypes as $usertype)
                                            <option value="{{$usertype->id}}">{{$usertype->utype}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 text-right align-self-end">
                                    <button type="button" class="btn btn-outline-success btn-block" data-currentusertypeid="{{$employeeinfo->usertypeid}}" id="btn-promote">Promote</button>
                                </div>
                            </div>                            
                            <div class="chart" id="chart-container">
                                <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>                     --}}
                        <div class="active tab-pane" id="timeline">       
                            <div class="row mb-2">
                                <div class="col-md-9">
                                    <label>Promote to:</label>
                                    <select class="form-control select2" id="select-usertype">
                                        @foreach($usertypes as $usertype)
                                            <option value="{{$usertype->id}}">{{$usertype->utype}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 text-right align-self-end">
                                    <button type="button" class="btn btn-outline-success btn-block" data-currentusertypeid="{{$employeeinfo->usertypeid}}" id="btn-promote">Promote</button>
                                </div>
                            </div>              
                            @if(count($promotions)>0)              
                                <div class="timeline timeline-inverse">     
                                    @foreach($promotions as $promotion)
                                        <div class="time-label">
                                            <span class="bg-success">
                                            {{$promotion->pyear}}
                                            </span>
                                        </div>
                                        {{-- <div>
                                            <i class="fas fa-envelope bg-primary"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> 12:05</span>
                                                <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>
                                                <div class="timeline-body">
                                                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                                    weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                                    jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                                    quora plaxo ideeli hulu weebly balihoo...
                                                </div>
                                                <div class="timeline-footer">
                                                    <a href="#" class="btn btn-primary btn-sm">Read more</a>
                                                    <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div>
                                            <i class="fas fa-user bg-info"></i>
                                            <div class="timeline-item">
                                                <span class="time">{{date('M d, Y h:i A', strtotime($promotion->createddatetime))}}</span>
                                                <h3 class="timeline-header border-0">Promoted to <u class="text-success">{{$promotion->utype}}</u>
                                                </h3>
                                            </div>
                                        </div>
                                        {{-- <div>
                                            <i class="fas fa-comments bg-warning"></i>
                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> 27 mins ago</span>
                                                <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>
                                                <div class="timeline-body">
                                                    Take me to your leader!
                                                    Switzerland is small and neutral!
                                                    We are more like Germany, ambitious and misunderstood!
                                                </div>
                                                <div class="timeline-footer">
                                                    <a href="#" class="btn btn-warning btn-flat btn-sm">View comment</a>
                                                </div>
                                            </div>
                                        </div>    --}}
                                    @endforeach                                          
                                    <div>
                                        <i class="far fa-clock bg-gray"></i>
                                    </div>     
                                </div>
                            @endif  
                        </div>                    
                    </div>                
                </div>
            </div>        
        </div>    
    </div>
    <script>
        @if(count($promotions) > 0)
        console.log({!!collect(collect($promotions)->pluck('utype')->toArray())!!})
            var areaChartData = {
                labels  : {!!collect(collect($promotions)->pluck('utype')->toArray())!!},
                datasets: [
                    {
                        label               : 'Results',
                        backgroundColor     : 'rgba(60,141,188,0.9)',
                        borderColor         : 'rgba(60,141,188,0.8)',
                        pointRadius         : false,
                        pointColor          : '#3b8bba',
                        pointStrokeColor    : 'rgba(60,141,188,1)',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data                :  {!!collect(collect($promotions)->pluck('pyear')->toArray())!!}
                    }
                ]
            }

            var areaChartOptions = {
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
            var barChartCanvas = $('#barChart').get(0).getContext('2d')
            var barChartData = $.extend(true, {}, areaChartData)
            // var temp0 = areaChartData.datasets[0]
            // var temp1 = areaChartData.datasets[1]
            // barChartData.datasets[0] = temp0
            // barChartData.datasets[1] = temp0

            var barChartOptions = {
            responsive              : true,
            maintainAspectRatio     : false,
            datasetFill             : false
            }

            new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
            })
            let chartsData = $("#chart-container").html();
            $("#chartInputData").val(chartsData);
        @endif
    </script>