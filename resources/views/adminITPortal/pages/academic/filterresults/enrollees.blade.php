<style>
    td, th{
        padding: 2px !important;
        /* font-size: 11px; */
    }
</style>
<div class="row boxes-container">

  <div class="col-md-4 col-sm-6 col-12">
    <div class="info-box shadow-lg">
      <span class="info-box-icon bg-warning"><i class="far fa-star"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Enrolled</span>
        <span class="info-box-number enrolled">{{$enrolled}}</span>
      </div>
  
    </div>
  </div>
  <div class="col-md-4 col-sm-6 col-12">
    <div class="info-box shadow-lg">
      <span class="info-box-icon bg-warning"><i class="far fa-star"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Dropped Out</span>
        <span class="info-box-number dropout">{{$droppedout}}</span>
      </div>
  
    </div>
  </div>
  <div class="col-md-4 col-sm-6 col-12">
    <div class="info-box shadow-lg">
      <span class="info-box-icon bg-warning"><i class="far fa-star"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Transferred Out</span>
        <span class="info-box-number transferredout">{{$transferredout}}</span>
      </div>
  
    </div>
  </div>
  <div class="col-md-4 col-sm-6 col-12">
    <div class="info-box shadow-lg">
      <span class="info-box-icon bg-warning"><i class="far fa-star"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Transferred In</span>
        <span class="info-box-number transferredin">{{$transferredin}}</span>
      </div>
    </div>
  </div>
  <div class="col-md-4 col-sm-6 col-12">
    <div class="info-box shadow-lg">
      <span class="info-box-icon bg-warning"><i class="far fa-star"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Withdrawn</span>
        <span class="info-box-number withdrawn">{{$withdrawn}}</span>
      </div>
    </div>
  </div>
</div>

<div class="row">
    <div class="col-12">
    
        <div class="card shadow">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">Enrollment</h3>
                <ul class="nav nav-pills ml-auto p-2">
                    <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Graphical Statistics</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Per Grade Level</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Students</a></li>
                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                        Dropdown <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu">
                        <a class="dropdown-item" tabindex="-1" href="#">Action</a>
                        <a class="dropdown-item" tabindex="-1" href="#">Another action</a>
                        <a class="dropdown-item" tabindex="-1" href="#">Something else here</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" tabindex="-1" href="#">Separated link</a>
                        </div>
                    </li> --}}
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <div class="chart card-img-top">
                          <canvas id="barChart-enrollees" style="min-height: 280px; height: 280px; max-height: 280px; max-width: 100%;"></canvas>
                        </div>
                        <table class="table table-striped" style="font-size: 15px !important;">
                            <thead>
                                <tr>
                                    <th style="width: 50%;" class="text-right">S.Y. {{$sydesc}}</th>
                                    <th class="text-right">Male</th>
                                    <th class="text-right">Female</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th style="width: 50%;" class="text-right">{{$semdesc}}</th>
                                    <th class="text-right">{{number_format(collect($gradelevels)->sum('enrolleesmalecount'))}}</th>
                                    <th class="text-right">{{number_format(collect($gradelevels)->sum('enrolleesfemalecount'))}}</th>
                                    <th class="text-right">{{number_format(collect($gradelevels)->sum('enrolleescount'))}}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="tab-pane p-0" id="tab_2">
                        {{-- <div class="row mb-2">
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-default btn-sm" id="btn-export-enrollment-gradelevel-table"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                            </div>
                        </div> --}}
                        <table class="table table-bordered m-0" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th colspan="2">Level Name</th>
                                    <th class="text-center">Male</th>
                                    <th class="text-center">Female</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gradelevels as $gradelevel)
                                    @if(count($gradelevel->sections) == 0)
                                        <tr>
                                            <th style="vertical-align: middle;" class="text-center"> {{$gradelevel->levelname}}</th>
                                            <th class="text-right">TOTAL</th>
                                            <th class="text-center">{{$gradelevel->enrolleesmalecount}}</th>
                                            <th class="text-center">{{$gradelevel->enrolleesfemalecount}}</th>
                                            <th class="text-center">{{$gradelevel->enrolleescount}}</th>
                                        </tr>
                                    @else
                                        <tr>
                                            <th rowspan="{{count($gradelevel->sections)+2}}" style="vertical-align: middle;" class="text-center"> {{$gradelevel->levelname}}</th>
                                            <th>Section(s)</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        @foreach($gradelevel->sections as $section)
                                            <tr>
                                                <td class="text-right">{{$section->sectionname}}</td>
                                                <td class="text-center">{{$section->male}}</td>
                                                <td class="text-center">{{$section->female}}</td>
                                                <td class="text-center">{{$section->total}}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th class="text-right">TOTAL</th>
                                            <th class="text-center">{{$gradelevel->enrolleesmalecount}}</th>
                                            <th class="text-center">{{$gradelevel->enrolleesfemalecount}}</th>
                                            <th class="text-center">{{$gradelevel->enrolleescount}}</th>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="tab-pane" id="tab_3">
                        {{-- <div class="row mb-2">
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-default btn-sm" id="btn-export-enrollment-gradelevel-table"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                            </div>
                        </div> --}}
                        @php
                            $allenrollees = array();
                            foreach($gradelevels as $gradelevel)
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
                        <table class="table" id="table-students" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>LRN</th>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Grade/Year Level</th>
                                    <th>Section</th>
                                    <th>College/Track</th>
                                    <th>Course/Strand</th>
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
              labels  : {!!collect($gradelevels)->pluck('label')!!},
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
                  data                : {!!collect($gradelevels)->pluck('enrolleesfemalecount')!!}
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
                  data                : {!!collect($gradelevels)->pluck('enrolleesmalecount')!!}
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
                  data                : {!!collect($gradelevels)->pluck('enrolleescount')!!}
                },
              ]
            }
            //-------------
            //- BAR CHART -
            //-------------
            var barChartCanvas_enrollees = $('#barChart-enrollees').get(0).getContext('2d')
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

            
        $('#table-students').DataTable({
            // "paging": false,
            // "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });
    </script>