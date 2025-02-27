@extends('registrar.layouts.app')

@section('content')
    <style>
        .table th, .table td      { font-size: 12px; border:1px solid black !important; /* text-align: center; table-layout: fixed; */ padding: 3px; table-layout: fixed !important;}
        /* #header, #header th, #header td         { font-size: 12px; border: none !important; border:1px solid black !important; padding:2px; text-align: right; } */
        /* input[type=text]                        { text-align: center; width:100%; } */
        /* .leftAlign                              { text-align: left !important; } */
        /* #female                                 { width: 5%; } */
        /* .guidelines                             { font-size: 11px; } */
        .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
        background-color: gold;
        }
    </style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h5><strong>School Form 4 (SF4)</strong> Monthly Learner's Movement and Attendance</h5>
                    <small><em>(This replaces Form 3 & STS Form 4-Absenteeism and Dropout Profile)</em></small>
                </div>
                <div class="col-sm-4">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">School Form 4</li>
                </ol>
                </div>
            </div>
        </div>
    </section>
    {{-- <div class="row">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-file"></i>
                        <strong>School Form 4 (SF4) Monthly Learner's Movement and Attendance</strong>
                    </h3>
                    <br>
                    <small><em>(This replaces Form 3 & STS Form 4-Absenteeism and Dropout Profile)</em></small>
                    
                    <button class="btn btn-sm btn-primary btnprint text-white float-right">
                            <i class="fa fa-upload"></i>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-12">
            <div class="main-card mb-3 card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Select S.Y</label>
                            <select class="form-control" style="border: none; border-bottom: 1px solid #ddd" id="selectedsy">
                                @foreach($schoolyears as $schoolyear)
                                    <option value="{{$schoolyear->id}}" @if($schoolyear->isactive == 1) selected @endif>{{$schoolyear->sydesc}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Select Year</label>
                            <select class="form-control" style="border: none; border-bottom: 1px solid #ddd" id="selectedyear">
                                @for($to = date('Y'); 2000<$to; $to--)
                                  <option value="{{$to}}">{{$to}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Month</label>
                            <select id="selectedmonth" class="form-control form-control" style="border: none; border-bottom: 1px solid #ddd">
                                {{-- <select id="currentmonth" name="selectedmonth" class="col-md-12" style="text-transform:uppercase;"> --}}
                                    <option value="01">January</option>
                                    <option value="02" >February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                {{-- </select> --}}
                                
                            </select>
                        </div>
                        <div class="col-md-3 text-right">
                            <label>&nbsp;</label><br/>
                            <button type="button" class="btn btn-primary" id="btn-view-calendar"><i class="fa fa-calendar"></i> &nbsp;Pick dates</button>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="results-container">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="show-calendar" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Pick Dates</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
              {{-- <div class="row">
                  <div class="col-md-12">
                      <em class="text-success">Note: Please click dates to add to the setup!</em>
                  </div>
              </div> --}}
              <div class="row">
                  <div class="col-md-12" id="calendar-container"></div>
                  {{-- <div class="col-md-12" >
                      <label>Selected dates:</label>
                      <br/>
                      <div id="selected-dates-container"></div>
                  </div> --}}
              </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal" >Close</button>
            <button type="button" id="btn-generate" class="btn btn-primary"><i class="fa fa-sync"></i> Generate</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#results-container').hide();
            var selecteddates = [];
            $('#selectedyear').on('change', function(){
                selecteddates = [];
            })
            $('#selectedmonth').on('change', function(){
                selecteddates = [];
            })
            
            $('#btn-view-calendar').on('click', function(){
                selecteddates = [];
                $('#show-calendar').modal('show')
                var selectedyear = $('#selectedyear').val();
                var selectedmonth = $('#selectedmonth').val();

                $.ajax({
                    url: '/reports_schoolform4/calendar',
                    type: 'GET',
                    data: {
                        selectedyear    : selectedyear,
                        selectedmonth   : selectedmonth
                    }, success:function(data){
                        $('#calendar-container').empty()
                        $('#calendar-container').append(data)
                    }
                })
            })
            $(document).on('click','.active-date', function(){
                $('#selected-dates-container').empty()
                var idx = $.inArray($(this).attr('data-id'), selecteddates);
                if (idx == -1) {
                    selecteddates.push($(this).attr('data-id'));
                    $(this).addClass('btn-success')
                } else {
                    selecteddates.splice(idx, 1);
                    $(this).removeClass('btn-success')
                }
            })
            $('#btn-generate').on('click', function(){
                $('.btn-close-modal').click()
                $('body').removeClass('modal-open')
                var selectedsy = $('#selectedsy').val();
                var selectedyear = $('#selectedyear').val();
                var selectedmonth = $('#selectedmonth').val();
                $.ajax({
                    url: '/reports_schoolform4/generate',
                    type: 'GET',
                    data: {
                        selectedsy   : selectedsy,
                        dates   : selecteddates,
                        selectedyear    : selectedyear,
                        selectedmonth   : selectedmonth
                    }, success:function(data){
                        $('#results-container').empty()
                        $('#results-container').append(data)
                        $('#results-container').show()
                    }
                })
            })
            $(document).on('click','#btn-reload', function(){
                var selectedsy = $('#selectedsy').val();
                var selectedyear = $('#selectedyear').val();
                var selectedmonth = $('#selectedmonth').val();
                $.ajax({
                    url: '/reports_schoolform4/generate',
                    type: 'GET',
                    data: {
                        selectedsy   : selectedsy,
                        dates   : selecteddates,
                        selectedyear    : selectedyear,
                        selectedmonth   : selectedmonth
                    }, success:function(data){
                        $('#results-container').empty()
                        $('#results-container').append(data)
                        $('#results-container').show()
                    }
                })
            })
            $(document).on("keyup",".filter", function() {
                var input = $(this).val().toUpperCase();
                var visibleCards = 0;
                var hiddenCards = 0;

                $(".container").append($("<div class='card-group card-group-filter'></div>"));


                $(".eachdata").each(function() {
                    if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                    $(".card-group.card-group-filter:first-of-type").append($(this));
                    $(this).hide();
                    hiddenCards++;

                    } else {

                    $(".card-group.card-group-filter:last-of-type").prepend($(this));
                    $(this).show();
                    visibleCards++;

                    if (((visibleCards % 4) == 0)) {
                        $(".container").append($("<div class='card-group card-group-filter'></div>"));
                    }
                    }
                });

            });
            $(document).on('click','#btn-export-pdf', function(){
                var selectedsy = $('#selectedsy').val();
                var selectedyear = $('#selectedyear').val();
                var selectedmonth = $('#selectedmonth').val();
                var paramet = {
                    selectedsy: selectedsy,
                    selectedyear: selectedyear,
                    selectedmonth: selectedmonth,
                    dates: selecteddates,
                }
                window.open("/reports_schoolform4/export?exporttype=pdf&"+$.param(paramet));
            })
            $(document).on('click','#btn-export-excel', function(){
                var selectedsy = $('#selectedsy').val();
                var selectedyear = $('#selectedyear').val();
                var selectedmonth = $('#selectedmonth').val();
                var paramet = {
                    selectedsy: selectedsy,
                    selectedyear: selectedyear,
                    selectedmonth: selectedmonth,
                    dates: selecteddates,
                }
                window.open("/reports_schoolform4/export?exporttype=excel&"+$.param(paramet));
            })
        })
    </script>
@endsection