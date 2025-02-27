@extends('adminITPortal.layouts.app')


@section('pagespecificscripts')
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection

@section('content')
<style>
    .shadow{
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
.shadow-lg{
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
}
.card{
    border: none !important;
}
table td{
  padding: 2px !important;
}
</style>
@php
  $allstatus = DB::table('hr_empstatus')
    ->where('deleted','0')
    ->get();
@endphp
  <section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{Session::get('schoolinfo')->schoolname}}</h1>
        </div>
        <div class="col-sm-6"></div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-4">
        <div class="card shadow">
          <div class="chart card-img-top">
            <canvas id="chart-gender" style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col-6">
                  <h5 class="card-title text-bold">Gender</h5>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card shadow">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <table class="table responsive data-tables" id="data-table" style="table-layout: fixed; font-size: 13px;">
                    <thead>
                        <tr>
                            <th style="width: 20%;">Name</th>
                            <th>Main Portal</th>	
                            <th>Date Hired</th>	
                            <th>Worked for</th>	
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                        <td>
                          <a href="#" data-toggle="modal" data-target="#modal-view-{{$employee->id}}">{{ucwords(strtolower($employee->lastname))}}, {{ucwords(strtolower($employee->firstname))}} </a>
                          <div class="modal fade" id="modal-view-{{$employee->id}}">
                            <div class="modal-dialog modal-xl">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">{{ucwords(strtolower($employee->lastname))}}, {{ucwords(strtolower($employee->firstname))}}</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  @php
                                  $avatar = $employee->gender != null ? (strtolower($employee->gender) == 'female' ? 'avatar/T(F) 1.png' : 'avatar/T(M) 1.png') : 'assets/images/avatars/unknown.png';
                                  @endphp
                                    <div class="row">
                                      <div class="col-md-3">                                      
                                        <div class="card card-outline shadow">
                                          <div class="card-body box-profile">
                                            <div class="text-center">

                                              <img class="profile-user-img img-fluid" src="{{$url->eslink.'/'.$employee->picurl}}" onerror="this.onerror = null, this.src='{{asset($avatar)}}'" alt="User profile picture">
                                            </div>
                                            <p class="text-muted text-center mt-2">{{$employee->designation}}</p> 
                                            @if(count($employee->otherportals)>0)
                                              <ul class="list-group list-group-unbordered mb-3">
                                                @foreach($employee->otherportals as $eachportal)
                                                <li class="list-group-item">
                                                  <b>{{$eachportal->utype}}</b> 
                                                </li>
                                                @endforeach
                                              </ul>
                                            @else
                                            <p class="text-muted text-center">No other portals</p> 
                                            <ul class="list-group list-group-unbordered mb-3">
                                              <li class="list-group-item">
                                              </li>
                                              <li class="list-group-item">
                                              </li>
                                              <li class="list-group-item">
                                              </li>
                                            </ul>
                                            @endif
                                          </div>
                                        
                                        </div>
                                      </div>
                                      <div class="col-md-9">
                                        <div class="card shadow">
                                          <div class="card-header">
                                            <h3 class="card-title">About </h3>
                                          </div>
                                          
                                          <div class="card-body">
                                            <strong><i class="fas fa-book mr-1"></i> Birth date</strong>
                                            <p class="text-muted">{{$employee->dob != null ? date('F d, Y', strtotime($employee->dob)) : ''}}</p>
                                            <hr>
                                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                                            <p class="text-muted">{{$employee->address != null ? $employee->address : ''}}</p>
                                            <hr>
                                            @if(count($employee->educationinfo)>0)
                                              <strong><i class="fas fa-book mr-1"></i> Education</strong>
                                              @foreach($employee->educationinfo as $eacheducationinfo)
                                                <p class="text-muted">
                                                  {{$eacheducationinfo->schoolyear}} - {{$eacheducationinfo->coursetaken}} - {{$eacheducationinfo->schoolname}}
                                                </p>
                                              @endforeach
                                              <hr>
                                            @endif
                                          </div>
                                        
                                        </div>
                                        
                                      </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                              </div>
                            
                            </div>
                            
                          </div>
                        </td>
                        <td>{{ucwords(strtolower($employee->designation))}}</td>
                        <td>
                          @if($employee->datehired == null)
                          
                          @else
                            {{date('M d, Y', strtotime($employee->datehired))}}
                          @endif
                        </td>
                        <td>{{$employee->worked}}</td>
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
  </section>
@endsection
@section('footerjavascript')
  <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
  <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
  <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
  <script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
  <script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
  <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
  <script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
  <script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
  <script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
  <script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
  <script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
  <script>
    $(function () {
      $(".data-tables").DataTable({
        "searching" : true,
        "ordering"  : false,
        "info"      : true,
        "autoWidth" : false,
        "responsive": true
      });
      @if(count($allstatus)>0)
        $('.dt-buttons').closest('.col-md-6').append('<button type="button" class="btn btn-secondary" id="btn-exporttopdf"><i class="fa fa-download"></i> Export to PDF</button>')
      @endif
    })
    $(document).ready(function(){
      //GENDER
      var genderdata        = {
        labels: [
            'Male',
            'Female',
            'Unspecified'
        ],
        datasets: [
          {
            data: [{!!collect($employees)->where('isactive',1)->whereIn('gender',['MALE','male','Male'])->count() !!},{!!collect($employees)->where('isactive',1)->whereIn('gender',['FEMALE','female','Female'])->count() !!},{!!collect($employees)->where('isactive',1)->where('gender',null)->count() !!}],
            backgroundColor : ['#80dfff','#ffb3cc', '#d2d6de']
          }
        ]
      }
      var chartgenderCanvas = $('#chart-gender').get(0).getContext('2d')
      var chartOptions     = {
        maintainAspectRatio : false,
        responsive          : true,
      }
      //Create pie or douhnut chart
      // You can switch between pie and douhnut using the method below.
      new Chart(chartgenderCanvas, {
        type    : 'doughnut',
        data    : genderdata,
        options : chartOptions
      })

      //EMPLOYMENT STATUS      
      @if(count($allstatus)>0)
        var employmentdata = {
          labels  : ['Casual', 'Provisionary', 'Regular', 'Part-time', 'Substitute','Not set'],
          datasets: [
            {
              label               : 'Female',
              backgroundColor     : '#ffccdd',
              borderColor         : '#ffb3cc',
              pointRadius         : false,
              pointColor          : '#ffb3cc',
              pointStrokeColor    : '#ffb3cc',
              pointHighlightFill  : '#ffb3cc',
              pointHighlightStroke: '#ffb3cc',
              borderWidth: 1,
              data                : [{!!collect($employees)->where('isactive',1)->where('employmentstatus',1)->where('gender','FEMALE')->count() !!}, {!!collect($employees)->where('isactive',1)->where('employmentstatus',2)->where('gender','FEMALE')->count() !!}, {!!collect($employees)->where('isactive',1)->where('employmentstatus',3)->where('gender','FEMALE')->count() !!}, {!!collect($employees)->where('isactive',1)->where('employmentstatus',4)->where('gender','FEMALE')->count() !!}, {!!collect($employees)->where('isactive',1)->where('employmentstatus',5)->where('gender','FEMALE')->count() !!}, {!!collect($employees)->where('isactive',1)->where('gender','FEMALE')->where('employmentstatus',null)->count() !!}]
            },
            {
              label               : 'Male',
              backgroundColor     : '#b3ecff',
              borderColor         : '#4dd2ff',
              pointRadius         : false,
              pointColor          : '#80dfff',
              pointStrokeColor    : '#80dfff',
              pointHighlightFill  : '#80dfff',
              pointHighlightStroke: '#80dfff',
              borderWidth: 1,
              data                : [{!!collect($employees)->where('isactive',1)->where('employmentstatus',1)->where('gender','MALE')->count() !!}, {!!collect($employees)->where('isactive',1)->where('employmentstatus',2)->where('gender','MALE')->count() !!}, {!!collect($employees)->where('isactive',1)->where('employmentstatus',3)->where('gender','MALE')->count() !!}, {!!collect($employees)->where('isactive',1)->where('employmentstatus',4)->where('gender','MALE')->count() !!}, {!!collect($employees)->where('isactive',1)->where('employmentstatus',5)->where('gender','MALE')->count() !!}, {!!collect($employees)->where('isactive',1)->where('gender','MALE')->where('employmentstatus',null)->count() !!}]
            }
          ]
        }
        var chartemployment = $('#chart-employmentstatus').get(0).getContext('2d')
        var chartemploymentdata = $.extend(true, {}, employmentdata)
        var temp0 = chartemploymentdata.datasets[0]
        var temp1 = chartemploymentdata.datasets[1]
        chartemploymentdata.datasets[0] = temp1
        chartemploymentdata.datasets[1] = temp0

        var chartemploymentOptions = {
          responsive              : true,
          maintainAspectRatio     : false,
          datasetFill             : false
        }

        new Chart(chartemployment, {
          type: 'bar',
          data: chartemploymentdata,
          options: chartemploymentOptions
        })
        $(document).on('click','#btn-exporttopdf', function(){
          window.open('/hr/index?action=exportpdf','_blank')
        })
      @endif
    })
  </script>
@endsection