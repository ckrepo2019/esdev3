@php
      if(Session::get('currentPortal') == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(Session::get('currentPortal') == 6 ){
            $extend =  'adminportal.layouts.app2';
      }
@endphp

@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
      <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }
      </style>
@endsection


@section('content')

@php
      $schoolyears = DB::table('sy')->get();
@endphp

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Tap Monitoring</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Tap Monitoring</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
    
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12 col-xl-12">
                      <div class="card shadow">
                          <div class="card-header">
                              <div class="row">
                                      <div class="col-md-5">
                                          <h5><i class="fa fa-filter"></i> Filter</h5> 
                                      </div>
                                      <div class="col-md-8">
                                          <h5 class="float-right">Active S.Y.: {{collect($schoolyears)->where('isactive',1)->first()->sydesc}}</h5>
                                      </div>
                              </div>
                              <div class="row">
                                  <div class="col-4">
                                      <label>Students</label>
                                      <select class="form-control select2" id="filter_student">
                                         
                                      </select>
                                  </div>
                                  <div class="col-md-3">
                                          <label for=""  >Date</label>
                                          <input class="form-control  form-control-sm-form" id="filter_date" style="height: calc(1.7rem + 1px);">
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
                                    <div class="row mb-2">
                                          <div class="col-md-12">
                                                <button disabled class="btn btn-primary btn-sm float-right" id="print_pdf"><i class="fa fa-print"></i> Print</button>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class="table-hover table table-striped table-sm table-bordered" width="100%" >
                                                      <thead>
                                                            <tr>
                                                                  <th width="20%" rowspan="2" class="align-middle">Date</th>
                                                                  <th width="20%" rowspan="2" class="text-center align-middle">Day</th>
                                                                  <th width="60%" colspan="2" class="text-center">Time Logs</th>
                                                            </tr>
                                                            <tr>
                                                                  <th  width="30%" class="text-center">Time</th>
                                                                  <th  width="30%" class="text-center">Tap</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="logs_holder" >
            
                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</section>

@endsection

@section('footerjavascript')
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
      <script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
     
      <script>

            $("#filter_student").select2()

            $('#filter_date').daterangepicker({
                  autoUpdateInput: false,
                  locale: {
                        cancelLabel: 'Clear'
                  }
            });


            $(document).on('change','#filter_student', function() {
                  sadtrattendancelogs()
            });


            $('#filter_date').on('apply.daterangepicker', function(ev, picker) {
                  $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                  sadtrattendancelogs()
            });

            $('#filter_date').on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  sadtrattendancelogs()
            });

            $(document).on('click','#print_pdf',function(){
                  var studid = $('#filter_student').val()
                  var date =$('#filter_date').val()
                  window.open('/sadtr/print?studid='+studid+'&date='+date, '_blank');
            })

            sadtrstudents()
            function sadtrstudents(){
                  $.ajax({
                        url: '/sadtr/students',
                        type: 'GET',
                        success:function(data){
                              $("#filter_student").empty();
                              $("#filter_student").append('<option value="">Select Student</option>');
                              $("#filter_student").select2({
                                    data: data,
                                    placeholder: "Select Student",
                              })
                        }
                  })
            }

            function sadtrattendancelogs(){
                  $.ajax({
                        url: '/sadtr/attendancelogs',
                        type: 'GET',
                        data:{
                              studid:$('#filter_student').val(),
                              date:$('#filter_date').val()
                        },
                        success:function(data){
                              $('#print_pdf').removeAttr('disabled','disabled')
                              if(data.length == 0){
                                    $('#logs_holder').empty()
                                    $('#logs_holder').append('<tr><td colspan="4" class="text-center">No logs found.</td></tr>')
                              }else{
                                    plotattendancelogs(data)
                              }
                        }
                  })
            }

            function plotattendancelogs(logs){
                  $('#logs_holder').empty()
                  $.each(logs,function(a,b){
                        if(b.logs.length > 0 ){
                              var firstdata = b.logs[0]
                              $('#logs_holder').append('<tr><td class="align-middle" rowspan="'+b.logs.length+'">'+b.newdate+'</td><td rowspan="'+b.logs.length+'" class="text-center align-middle">'+b.day+'</td><td class="text-center">'+firstdata.newtime+'</td><td class="text-center">'+firstdata.tapstate+'</td></tr>')

                              $.each(b.logs,function(c,d){
                                    if(c != 0){
                                          $('#logs_holder').append('<tr><td class="text-center">'+d.newtime+'</td><td  class="text-center">'+d.tapstate+'</td></tr>')
                                    }
                              })
                        }else{
                              $('#logs_holder').append('<tr><td>'+b.newdate+'</td><td class="text-center">'+b.day+'</td><td colspan="2" class="text-center">No logs found.</td></tr>')
                        }
                  })


            }



      </script>

@endsection


