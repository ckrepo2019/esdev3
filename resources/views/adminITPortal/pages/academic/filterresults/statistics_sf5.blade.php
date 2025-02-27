
    <div class="row">
        <div class="col-12">
        
            <div class="card shadow">
                <div class="card-header p-0">

                    <div class="row">
                        <div class="col-md-12">
                            <p class="pt-3 pr-3 pl-3">Learning Progress & Achievement<br/><small>(Based on Learners' General Average) - <strong>ELEM & JHS</strong> </small></p>

                        </div>
                        <div class="col-md-12 d-flex">
                            <ul class="nav nav-pills ml-auto p-2">
                                <li class="nav-item pr-2">
                                    <select class="form-control" id="select-levelid">
                                        @foreach(collect($gradelevels)->whereIn('acadprogid',[3,4])->values() as $gradelevel)
                                            <option value="{{$gradelevel->id}}" {{$gradelevel->id == $levelid ? 'selected' : ''}}>{{$gradelevel->levelname}}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <li class="nav-item"><a class="nav-link active" href="#sf5tab_1" data-toggle="tab">Graphical Statistics</a></li>
                                <li class="nav-item"><a class="nav-link" href="#sf5tab_2" data-toggle="tab">Per Section</a></li>
                                <li class="nav-item"><a class="nav-link" href="#sf5tab_3" data-toggle="tab">List</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="sf5tab_1">
                            <div class="chart card-img-top">
                              <canvas id="barChart-learningprogress" style="min-height: 280px; height: 280px; max-height: 280px; max-width: 100%;"></canvas>
                            </div>
                            <table class="table table-striped" style="font-size: 12px !important;">
                                <thead>
                                    <tr>
                                        <th class="text-center">Did Not Meet Expectations</th>
                                        <th class="text-center">Fairly Satisfactory</th>
                                        <th class="text-center">Satisfactory</th>
                                        <th class="text-center">Very Satisfactory</th>
                                        <th class="text-center">Outstanding</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="text-center">(74 and below)</th>
                                        <th class="text-center">(75-79)</th>
                                        <th class="text-center">(80-84)</th>
                                        <th class="text-center">(85 -89)</th>
                                        <th class="text-center">(90 -100)</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="tab-pane p-0" id="sf5tab_2">
                            @if(count($results[0]->enrollees)>0)
                                @php
                                    $sections = collect($results[0]->enrollees)->groupBY('sectionname');
                                @endphp
                                <table class="table table-bordered" style="font-size: 12px;">
                                    <thead class="text-center">
                                        <tr>
                                            <th rowspan="2">Section - Adviser</th>
                                            <th colspan="3">Did Not Meet Expectations<br/>(74 and below)</th>
                                            <th colspan="3">Fairly Satisfactory<br/>(75-79)</th>
                                            <th colspan="3">Satisfactory<br/>(80-84)</th>
                                            <th colspan="3">Very Satisfactory<br/>(85 -89)</th>
                                            <th colspan="3">Outstanding<br/>(90 -100)</th>
                                        </tr>
                                        <tr>
                                            <th>M</th>
                                            <th>F</th>
                                            <th>Total</th>
                                            <th>M</th>
                                            <th>F</th>
                                            <th>Total</th>
                                            <th>M</th>
                                            <th>F</th>
                                            <th>Total</th>
                                            <th>M</th>
                                            <th>F</th>
                                            <th>Total</th>
                                            <th>M</th>
                                            <th>F</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sections as $keysection => $eachsection)
                                            <tr>
                                                <td><strong>{{$keysection}}</strong> - {{ucwords(strtolower($eachsection[0]->teachername))}}</td>
                                                <td class="text-center">{{collect($eachsection)->where('genave','>',0)->where('genave','<','75')->whereIn('gender',['MALE','Male','male'])->count()}}</td>
                                                <td class="text-center">{{collect($eachsection)->where('genave','>',0)->where('genave','<','75')->whereIn('gender',['FEMALE','Female','female'])->count()}}</td>
                                                <td class="text-center">{{collect($eachsection)->where('genave','>',0)->where('genave','<','75')->count()}}</td>
                                                <td class="text-center">{{collect($eachsection)->where('genave','>',0)->where('genave','>=','75')->where('genave','<=','79')->whereIn('gender',['MALE','Male','male'])->count()}}</td>
                                                <td class="text-center">{{collect($eachsection)->where('genave','>',0)->where('genave','>=','75')->where('genave','<=','79')->whereIn('gender',['FEMALE','Female','female'])->count()}}</td>
                                                <td class="text-center">{{collect($eachsection)->where('genave','>',0)->where('genave','>=','75')->where('genave','<=','79')->count()}}</td>
                                                <td class="text-center">{{collect($eachsection)->where('genave','>',0)->where('genave','>=','80')->where('genave','<=','84')->whereIn('gender',['MALE','Male','male'])->count()}}</td>
                                                <td class="text-center">{{collect($eachsection)->where('genave','>',0)->where('genave','>=','80')->where('genave','<=','84')->whereIn('gender',['FEMALE','Female','female'])->count()}}</td>
                                                <td class="text-center">{{collect($eachsection)->where('genave','>',0)->where('genave','>=','80')->where('genave','<=','84')->count()}}</td>
                                                <td class="text-center">{{collect($eachsection)->where('genave','>',0)->where('genave','>=','85')->where('genave','<=','89')->whereIn('gender',['MALE','Male','male'])->count()}}</td>
                                                <td class="text-center">{{collect($eachsection)->where('genave','>',0)->where('genave','>=','85')->where('genave','<=','89')->whereIn('gender',['FEMALE','Female','female'])->count()}}</td>
                                                <td class="text-center">{{collect($eachsection)->where('genave','>',0)->where('genave','>=','85')->where('genave','<=','89')->count()}}</td>
                                                <td class="text-center">{{collect($eachsection)->where('genave','>',0)->where('genave','>=','90')->whereIn('gender',['MALE','Male','male'])->count()}}</td>
                                                <td class="text-center">{{collect($eachsection)->where('genave','>',0)->where('genave','>=','90')->whereIn('gender',['FEMALE','Female','female'])->count()}}</td>
                                                <td class="text-center">{{collect($eachsection)->where('genave','>',0)->where('genave','>=','90')->count()}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                        
                        <div class="tab-pane" id="sf5tab_3">
                            @php
                                $allenrollees = array();
                                foreach($results as $gradelevel)
                                {
                                    if(count($gradelevel->enrollees)>0)
                                    {
                                        foreach($gradelevel->enrollees as $enrollee)
                                        {
                                            $enrollee->levelname = $gradelevel->levelname;
                                            $enrollee->sortname = $enrollee->lastname.', '.$enrollee->firstname.' '.$enrollee->middlename;
                                            array_push($allenrollees, $enrollee);
                                        }
                                    }
                                }
                                $allenrollees = collect($allenrollees)->sortBy('sortname');
                            @endphp
                            <table class="table" id="table-studentssf5" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th>LRN</th>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Grade/Year Level</th>
                                        <th>Section</th>
                                        <th>College/Track</th>
                                        <th>Course/Strand</th>
                                        <th>Gen. Ave</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allenrollees as $eachenrollee)
                                        <tr>
                                            <td>{{$eachenrollee->lrn}}</td>
                                            <td>{{$eachenrollee->sid}}</td>
                                            <td>{{$eachenrollee->sortname}}</td>
                                            <td>{{$eachenrollee->levelname}}</td>
                                            <td>{{$eachenrollee->sectionname ?? ''}}</td>
                                            <td>{{$eachenrollee->collegeabrv ?? $eachenrollee->trackname ?? ''}}</td>
                                            <td>{{$eachenrollee->coursename ?? $eachenrollee->strandcode ?? ''}}</td>
                                            <td class="text-center">{{$eachenrollee->genave}}</td>
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
    <script>
        var areaChartData_enrollees = {
            labels  : ['Did Not Meet Expectations','Fairly Satisfactory','Satisfactory','Very Satisfactory','Outstanding'],
            datasets: [
              {
                label               : 'Female',
                backgroundColor     : 'rgba(60,141,188,0.9)',
                borderColor         : 'rgba(60,141,188,0.8)',
                pointRadius          : false,
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                borderWidth: 1,
                data                : ['{{$didnotmeet_f}}','{{$fairlysatisfactory_f}}','{{$satisfactory_f}}','{{$verysatisfactory_f}}','{{$outsatanding_f}}']
              },
              {
                label               : 'Male',
                backgroundColor     : '#cce5ff',
                borderColor         : '#007bff',
                pointRadius         : false,
                pointColor          : 'rgba(210, 214, 222, 1)',
                pointStrokeColor    : '#cce5ff',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: '#cce5ff',
                borderWidth: 1,
                data                : ['{{$didnotmeet_m}}','{{$fairlysatisfactory_m}}','{{$satisfactory_m}}','{{$verysatisfactory_m}}','{{$outsatanding_m}}']
              },
              {
                label               : 'Total',
                backgroundColor     : 'rgba(210, 214, 222, 1)',
                borderColor         : 'rgba(210, 214, 222, 1)',
                pointRadius         : false,
                pointColor          : 'rgba(210, 214, 222, 1)',
                pointStrokeColor    : '#c1c7d1',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                borderWidth: 1,
                data                : ['{{$didnotmeet_t}}','{{$fairlysatisfactory_t}}','{{$satisfactory_t}}','{{$verysatisfactory_t}}','{{$outsatanding_t}}']
              },
            ]
          }
          //-------------
          //- BAR CHART -
          //-------------
          var barChartCanvas_enrollees = $('#barChart-learningprogress').get(0).getContext('2d')
          var barChartData_enrollees = $.extend(true, {}, areaChartData_enrollees)
          var temp0 = areaChartData_enrollees.datasets[0]
          var temp1 = areaChartData_enrollees.datasets[1]
          barChartData_enrollees.datasets[0] = temp1
          barChartData_enrollees.datasets[1] = temp0

          var barChartOptions = {
          responsive              : true,
          maintainAspectRatio     : false,
          datasetFill             : false
          }

          new Chart(barChartCanvas_enrollees, {
          type: 'bar',
          data: barChartData_enrollees,
          options: barChartOptions
          })
        $('#table-studentssf5').DataTable({
            // "paging": false,
            // "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });

      </script>