
   <style>
    .each-bracket
    {
      cursor: pointer !important;
    }
    </style>
   {{-- <div class="card-deck mb-3">
        <div class="card shadow">
            <div class="chart card-img-top">
              <canvas id="barChart-enrollees" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          <div class="card-body">
            <div class="row">
                <div class="col-6">
                  <h5 class="card-title text-bold">Number of Enrollees</h5>
                </div>
                <div class="col-6 text-right">
                    <button type="button" class="btn btn-default btn-sm text-bold" id="btn-view-noofenrollees">View details</button>
                </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        
        <div class="col-md-4 col-sm-6 col-6">
          <div class="info-box shadow each-bracket" data-category="B">
            <span class="info-box-icon bg-danger">B</span>

            <div class="info-box-content">
              <span class="info-box-text" style="font-size: 11px;">BEGINNING</span>
              <span class="info-box-text" style="font-size: 15px;"><small>(74% and below)</small></span>
            </div>
          </div>
        </div>
        
        <div class="col-md-4 col-sm-6 col-6">
          <div class="info-box shadow each-bracket" data-category="D">
            <span class="info-box-icon bg-warning">D</span>

            <div class="info-box-content">
              <span class="info-box-text" style="font-size: 11px;">DEVELOPING</span>
              <span class="info-box-text" style="font-size: 15px;"><small>(75%-79%)
                </small></span>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-6 col-6">

          <div class="info-box shadow each-bracket" data-category="AP">
            <span class="info-box-icon bg-info">AP</span>

            <div class="info-box-content">
              <span class="info-box-text" style="font-size: 11px;">APPROACHING<br/>
                PROFICIENCY</span>
                <span class="info-box-text" style="font-size: 15px;"><small>(80%-84%)</small></span>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-sm-6 col-6">

          <div class="info-box shadow each-bracket" data-category="P">
            <span class="info-box-icon bg-success">P</span>

            <div class="info-box-content">
              <span class="info-box-text" style="font-size: 11px;">PROFICIENT</span>
              <span class="info-box-text" style="font-size: 15px;"><small>(85% -89%)
              </small></span>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6 col-6">

          <div class="info-box shadow each-bracket" data-category="A">
            <span class="info-box-icon bg-success">A</span>

            <div class="info-box-content">
              <span class="info-box-text" style="font-size: 11px;">ADVANCED</span>
              <span class="info-box-text" style="font-size: 15px;"><small>(90% and above)
                </small></span>
            </div>
          </div>
        </div> --}}
        {{-- <div class="col-md-12">
            <div class="card shadow">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-4 mb-2">                    
                      <label>Pre-school / Elem / JHS</label>
                      <select class="form-control" id="select-basiced-levelid">
                        @foreach($gradelevels as $gradelevel)
                          @if($gradelevel->acadprogid <= 4)
                          <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
                  <div class="col-md-5 mb-2">                    
                      <label>Grade Category</label>
                      <select class="form-control" id="select-basiced-category">
                          <option value="B">BEGINNING</option>
                          <option value="D">DEVELOPING</option>
                          <option value="AP">APPROACHING PROFICIENCY</option>
                          <option value="P">PROFICIENT</option>
                          <option value="A">ADVANCED</option>
                      </select>
                  </div>
                  <div class="col-md-3">
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-default btn-block" id="btn-view-basiced-catstudents">View list</button>
                  </div>
                  <div class="col-md-12" id="container-catheader-basiced"></div>
                </div>
              </div>
              <div class="card-body text-center"  style="font-size: 300px; text-align: center; color: #ddd;">
               
                <div class="row mb-2">
                    <div class="col-md-12" id="container-catchart-basiced">
                      <i class="fa fa-arrow-alt-circle-up"></i>
                    </div>
                </div>
              </div>
            </div>
            <div class="card shadow">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-4 mb-2">                           
                      <label>SHS</label>
                      <select class="form-control" id="select-shs-levelid">
                        @foreach($gradelevels as $gradelevel)
                          @if($gradelevel->acadprogid == 5)
                            <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
                  <div class="col-md-5 mb-2">                    
                      <label>Grade Category</label>
                      <select class="form-control" id="select-shs-category">
                          <option value="B">BEGINNING</option>
                          <option value="D">DEVELOPING</option>
                          <option value="AP">APPROACHING PROFICIENCY</option>
                          <option value="P">PROFICIENT</option>
                          <option value="A">ADVANCED</option>
                      </select>
                  </div>
                  <div class="col-md-3">
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-default btn-block" id="btn-view-shs-catstudents">View list</button>
                  </div>
                  <div class="col-md-12" id="container-catheader-shs"></div>
                </div>
              </div>
              <div class="card-body text-center"  style="font-size: 300px; text-align: center; color: #ddd;">
               
                <div class="row mb-2">
                    <div class="col-md-12" id="container-catchart-shs">
                      <i class="fa fa-arrow-alt-circle-up"></i>
                    </div>
                </div>
              </div>
              <div class="overlay dark" id="loading-shs">
                <i class="fas fa-2x fa-sync-alt"></i>
              </div>
            </div>
        </div> --}}
        {{-- <div class="col-md-6">
            <div class="card shadow h-100">
              <div class="card-header" id="container-catheader">
                  Please select a grade category.
              </div>
              <div class="card-body text-center"  style="font-size: 300px; text-align: center; color: #ddd;">
               
                <div class="row mb-2">
                    <div class="col-md-12" id="container-catchart">
                      <i class="fa fa-arrow-alt-circle-left"></i>
                    </div>
                </div>
              </div>
            </div>
        </div> --}}
      </div>
      <script>          
          // var areaChartData_enrollees = {
          //     labels  : {!!collect($gradelevels)->pluck('levelname')!!},
          //     datasets: [
          //       {
          //         label               : 'Female',
          //         backgroundColor     : 'rgba(60,141,188,0.9)',
          //         borderColor         : 'rgba(60,141,188,0.8)',
          //         pointRadius          : false,
          //         pointColor          : '#3b8bba',
          //         pointStrokeColor    : 'rgba(60,141,188,1)',
          //         pointHighlightFill  : '#fff',
          //         pointHighlightStroke: 'rgba(60,141,188,1)',
          //         borderWidth: 1,
          //         data                : {!!collect($gradelevels)->pluck('female')!!}
          //       },
          //       {
          //         label               : 'Male',
          //         backgroundColor     : '#cce5ff',
          //         borderColor         : '#007bff',
          //         pointRadius         : false,
          //         pointColor          : 'rgba(210, 214, 222, 1)',
          //         pointStrokeColor    : '#cce5ff',
          //         pointHighlightFill  : '#fff',
          //         pointHighlightStroke: '#cce5ff',
          //         borderWidth: 1,
          //         data                : {!!collect($gradelevels)->pluck('male')!!}
          //       },
          //       {
          //         label               : 'Total',
          //         backgroundColor     : 'rgba(210, 214, 222, 1)',
          //         borderColor         : 'rgba(210, 214, 222, 1)',
          //         pointRadius         : false,
          //         pointColor          : 'rgba(210, 214, 222, 1)',
          //         pointStrokeColor    : '#c1c7d1',
          //         pointHighlightFill  : '#fff',
          //         pointHighlightStroke: 'rgba(220,220,220,1)',
          //         borderWidth: 1,
          //         data                : {!!collect($gradelevels)->pluck('total')!!}
          //       },
          //     ]
          //   }

            //-------------
            //- BAR CHART -
            //-------------
            // var barChartCanvas_enrollees = $('#barChart-enrollees').get(0).getContext('2d')
            // var barChartData_enrollees = $.extend(true, {}, areaChartData_enrollees)
            // var temp0 = areaChartData_enrollees.datasets[0]
            // var temp1 = areaChartData_enrollees.datasets[1]
            // barChartData_enrollees.datasets[0] = temp1
            // barChartData_enrollees.datasets[1] = temp0

            // var barChartOptions = {
            // responsive              : true,
            // maintainAspectRatio     : false,
            // datasetFill             : false
            // }

            // new Chart(barChartCanvas_enrollees, {
            // type: 'bar',
            // data: barChartData_enrollees,
            // options: barChartOptions
            // })
      </script>