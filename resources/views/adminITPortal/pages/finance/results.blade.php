
    <div class="row">
        <div class="col-md-6" hidden>
          <div class="card shadow">
          <div class="card-header">
          <h3 class="card-title">School Fees</h3>
          <div class="card-tools text-secondary">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
          </button>
          </div>
          
          </div>
          
          <div class="card-body pt-1">
            <button type="button" class="btn btn-sm btn-default">View Details</button>
            <div class="chart card-img-top">
              <canvas id="barChart-schoolfees" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card shadow">
          <div class="card-header">
          <h3 class="card-title">Receivables</h3>
          <div class="card-tools text-secondary">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
          </button>
          </div>
          
          </div>
          
            <div class="card-body pt-1">
              <div class="row">
                <div class="col-6">
                </div>
                <div class="col-6 text-right"><button type="button" class="btn btn-sm btn-default" id="btn-export-receivables"><i class="fa fa-file-pdf"></i> Preview</button></div>
              </div>            
              <div class="chart card-img-top">
                <canvas id="barChart-receivables" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
            </div>          
          </div>
        </div>
        <div class="col-md-6">
          <div class="card shadow">
            <div class="card-header">
            <h3 class="card-title">Income</h3>
            <div class="card-tools text-secondary">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            </div>
            </div>          
              <div class="card-body pt-1">
                <div class="row">
                  <div class="col-6">
                  </div>
                  <div class="col-6 text-right"><button type="button" class="btn btn-sm btn-default" id="btn-export-income"><i class="fa fa-file-pdf"></i> Preview</button></div>
                </div>            
                <div class="chart card-img-top">
                  <canvas id="barChart-income" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>          
            </div>
        </div>
      </div>
      <script>       
        
        var barChartOptions_receivables = {
        responsive              : true,
        maintainAspectRatio     : false,
        datasetFill             : false
        }


        // RECEIVABLES
        var areaChartData_receivables = {
        labels  : {!! collect($months)->pluck('monthdesc') !!},
        datasets: [
            {
            label               : '{{$sydesc}}',
            backgroundColor     : '#fed5b3',
            borderColor         : '#fd7e14',
            pointRadius          : false,
            pointColor          : '#fed5b3',
            pointStrokeColor    : '#fed5b3',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: '#fed5b3',
            borderWidth: 1,
            data                : {!! collect($months)->pluck('totalreceivables') !!}
            }
        ]
        }

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas_receivables = $('#barChart-receivables').get(0).getContext('2d')
        var barChartData_receivables = $.extend(true, {}, areaChartData_receivables)
        var temp0 = areaChartData_receivables.datasets[0]
        barChartData_receivables.datasets[0] = temp0

        new Chart(barChartCanvas_receivables, {
        type: 'bar',
        data: barChartData_receivables,
        options: barChartOptions_receivables
        })
      
        // INCOME
        
        var barChartOptions_income = {
        responsive              : true,
        maintainAspectRatio     : false,
        datasetFill             : false
        }

        var areaChartData_income = {
        labels  : {!! collect($months)->pluck('monthdesc') !!},
        datasets: [
            {
            label               : '{{$sydesc}}',
            backgroundColor     : '#e6cada',
            borderColor         : '#cc89af',
            pointRadius          : false,
            pointColor          : '#d4a1be',
            pointStrokeColor    : '#d4a1be',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: '#d4a1be',
            borderWidth: 1,
            data                : {!! collect($months)->pluck('totalincome') !!}
            }
        ]
        }

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas_income = $('#barChart-income').get(0).getContext('2d')
        var barChartData_income = $.extend(true, {}, areaChartData_income)
        var temp0 = areaChartData_income.datasets[0]
        barChartData_income.datasets[0] = temp0

        new Chart(barChartCanvas_income, {
        type: 'bar',
        data: barChartData_income,
        options: barChartOptions_income
        })
      </script>