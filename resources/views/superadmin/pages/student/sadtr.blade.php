@php

      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

      if(Session::get('currentPortal') == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(Session::get('currentPortal') == 6 ){
            $extend =  'adminportal.layouts.app2';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }else if(Session::get('currentPortal') == 14){    
		$extend = 'deanportal.layouts.app2';
	}else if(Session::get('currentPortal') == 7){
            $extend = 'studentPortal.layouts.app2';
      }else if(Session::get('currentPortal') == 9){
            $extend = 'parentsportal.layouts.app2';
      }else if(Session::get('currentPortal') == 1){
        $extend = 'teacher.layouts.app';
      }else{
            if(isset($check_refid->refid)){
                  if($check_refid->refid == 26){
                        $extend = 'registrar.layouts.app';
                  }else if($check_refid->refid == 28){
                        $extend = 'officeofthestudentaffairs.layouts.app2';
                  }else if($check_refid->refid == 29){
                        $extend = 'idmanagement.layouts.app2';
                  }else if($check_refid->refid == 31){
                        $extend = 'guidance.layouts.app2';
                  }else if($check_refid->refid == 30){
                        $extend = 'encoder.layouts.app2';
                  }
            }
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


<div class="modal fade" id="late_stud_timeselection" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Print Late Students</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                       
                        <div class="row">
                              <div class="col-md-12">
                                    <label for="">Date: <span class="selected_dateholder"></span></label>
                              </div>
                              <div class="col-md-12">
                                    <hr>
                                    <label for="">Morning Late Time</label>
                              </div>
                              <div class="col-md-6 form-group">
                                    <label for="">Start</label>
                                    <input type="time" class="form-control form-control-sm" id="am_time_start" value="07:30">
                              </div>
                              <div class="col-md-6 form-group">
                                    <label for="">End</label>
                                    <input type="time" class="form-control form-control-sm" id="am_time_end" value="12:00">
                              </div>
                             
                              <div class="col-md-12 ">
                                    <hr>
                                    <label for="">Afternoon Late Time</label>
                              </div>
                              <div class="col-md-6 form-group">
                                    <label for="">Start</label>
                                    <input type="time" class="form-control form-control-sm" id="pm_time_start" value="13:00">
                              </div>
                              <div class="col-md-6 form-group">
                                    <label for="">End</label>
                                    <input type="time" class="form-control form-control-sm" id="pm_time_end" value="17:00">
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="print_late">Print</button>
                              </div>
                        </div>
                        
                    
                  </div>
            </div>
      </div>
</div>   


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
                                      <div class="col-md-7">
                                          <h5 class="float-right">Active S.Y.: {{collect($schoolyears)->where('isactive',1)->first()->sydesc}}</h5>
                                      </div>
                              </div>
                              <div class="row">
                                    @if(Session::get('currentPortal') != 7 && Session::get('currentPortal') != 9)
                                          <div class="col-2">
                                                      <label>School Year</label>
                                                      <select class="form-control select2 form-control-sm" id="filter_sy">
                                                            @foreach ($schoolyears as $item)
                                                                  @if($item->isactive == 1)
                                                                        <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                                  @else
                                                                        <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                                  @endif
                                                            @endforeach
                                                      </select>
                                                </div>
                                          <div class="col-5">
                                                <label>Students</label>
                                                <select class="form-control select2" id="filter_student">
                                                
                                                </select>
                                          </div>
                                          <div class="col-2">
                                                <label>Tap State</label>
                                                <select class="form-control select2" id="filter_tapstate">
                                                      <option value="">All</option>
                                                      <option value="IN">IN</option>
                                                      <option value="OUT">OUT</option>
                                                </select>
                                          </div>
                                    @endif
                                    <div class="col-md-3">
                                          <label for=""  >Date</label>
                                          <input class="form-control  form-control-sm-form" id="filter_date" style="height: calc(1.7rem + 1px);" placeholder="Select Date">
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
                                          <div class="col-md-12 text-right">
                                                @if(auth()->user()->id != 7 && auth()->user()->id != 9 )
                                                      <button  class="btn btn-primary btn-sm " id="print_pdf"><i class="fa fa-print"></i> Print</button>
                                                      <button  class="btn btn-primary btn-sm " id="print_late_to_modal"><i class="fa fa-print"></i> Late Students</button>
                                                @endif
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12">
                                                <table class=" table-sm table table-sm table-bordered text-sm" width="100%" id="time_holder">
                                                      <thead>
                                                            <tr>
                                                                  <th width="30%"  class="align-middle">Student</th>
                                                                  <th width="20%"  class="text-center align-middle">Date</th>
                                                                  <th  width="20%" class="text-center">Time</th>
                                                                  <th  width="30%" class="text-center">Tap</th>
                                                            </tr>
                                                            {{-- <tr>
                                                                  <th  width="30%" class="text-center">Time</th>
                                                                  <th  width="30%" class="text-center">Tap</th>
                                                            </tr> --}}
                                                      </thead>
                                                     
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
      <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>



      <script>

            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000,
            })

            var type = @json(Session::get('currentPortal'))

            if(type != 7 && type != 9){
                  sadtrstudents()
                  function sadtrstudents(){
                        $.ajax({
                              url: '/sadtr/students',
                              type: 'GET',
                              data:{
                                    syid:$('#filter_sy').val()
                              },
                              success:function(data){

                                    if(data.length > 0){
                                          // Toast.fire({
                                          //       type: 'info',
                                          //       title: data.length + ' student(s) found!'
                                          // })
                                    }else{
                                          Toast.fire({
                                                type: 'error',
                                                title: 'No student found!'
                                          })
                                    }

                                    $("#filter_student").empty();
                                    $("#filter_student").append('<option value="">Select Name</option>');
                                    $("#filter_student").select2({
                                          data: data,
                                          placeholder: "All",
                                          allowClear:true
                                    })
                                   
                              }
                        })
                  }

            }else{
                  sadtrattendancelogs()
            }

            $("#filter_student").select2()
            $("#filter_sy").select2()
            $("#filter_tapstate").select2({
                  placeholder: "All",
                  allowClear:true
            })

            $('#filter_date').daterangepicker({
                  autoUpdateInput: false,
                  locale: {
                        cancelLabel: 'Clear'
                  }
            });

            // $('#filter_date').daterangepicker({
            //       startDate: new Date()
            // })


            $(document).on('change','#filter_student , #filter_tapstate', function() {
                  // sadtrattendancelogs()
                  taphistory()
            });

            $(document).on('change','#filter_sy', function() {
                  // $('#logs_holder').empty()
                  sadtrstudents()
                  taphistory()
            });

            $('#filter_date').on('apply.daterangepicker', function(ev, picker) {
                  $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                  // sadtrattendancelogs()
                  taphistory()
            });

            $('#filter_date').on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  // sadtrattendancelogs()
                  taphistory()
            });

            $(document).on('click','#print_pdf',function(){
                  var studid = $('#filter_student').val()
                  var date =$('#filter_date').val()
                  window.open('/sadtr/print?studid='+studid+'&date='+date, '_blank');
            })

            $(document).on('click','#print_late_to_modal',function(){
                  if($('#filter_date').val() == ""){
                        Toast.fire({
                              type: 'warning',
                              title:  'No Date Selected!'
                        })
                        return false
                  }
                  $('.selected_dateholder').text($('#filter_date').val())
                  $('#late_stud_timeselection').modal();
            })

            $(document).on('click','#print_late',function(){
                  var studid = $('#filter_student').val()
                  var date =$('#filter_date').val()
                  var am_time_start = $('#am_time_start').val()
                  var am_time_end =  $('#am_time_end').val()
                  var pm_time_start = $('#pm_time_start').val() 
                  var pm_time_end =  $('#pm_time_end').val()
                  
                  var request_string = ''
                  request_string += '&amtimestart=' + am_time_start
                  request_string += '&amtimeend=' + am_time_end
                  request_string += '&pmtimestart=' + pm_time_start
                  request_string += '&pmtimeend=' + pm_time_end

                  window.open('/sadtr/print/late?studid='+studid+'&date='+date+request_string, '_blank');
            })
            


            taphistory()

            function taphistory(){

                 
                  $("#time_holder").DataTable({
                        destroy: true,
                        autoWidth: false,
                        stateSave: true,
                        serverSide: true,
                        processing: true,
                        lengthChange:false,
                        ajax:{
                              url: '/sadtr/taphistory',
                              type: 'GET',
                              data: {
                                    syid:$('#filter_sy').val(),
                                    studid:$('#filter_student').val(),
                                    date:$('#filter_date').val(),
                                    tapstate:$('#filter_tapstate').val()
                              },
                              dataSrc: function ( json ) {
                                    return json.data;
                              }
                        },
                        columns: [
                              { "data": "studentname" },
                              { "data": "newdate" },
                              { "data": "tapstate"},
                              { "data": "newtime"}
                        ],
                        columnDefs: [
                              {
                                    'targets': [0],
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': [2,3],
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': [1],
                                    'orderable': true, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                        ]

                  })
            }

            
            function sadtrattendancelogs(){

                  return false;
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

                              if(data.length > 0){
                                    Toast.fire({
                                          type: 'info',
                                          title:  'Log(s) found!'
                                    })
                              }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: 'No logs found!'
                                    })
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


