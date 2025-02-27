@php

      if(!Auth::check()){
            header("Location: " . URL::to('/'), true, 302);
            exit();
      }

      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
      
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }else{
            if(isset($check_refid->refid)){
                  if($check_refid->refid == 27){
                        $extend = 'academiccoor.layouts.app2';
                  }
            }else{
                  $extend = 'general.defaultportal.layouts.app';
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
      <link rel="stylesheet" href="{{asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css')}}">

      <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }
            .no-border-col{
                  border-left: 0 !important;
                  border-right: 0 !important;
            }
            /* input[type=search]{
                  height: calc(1.7em + 2px) !important;
            } */
      </style>


@endsection


@section('content')

@php
   $sy = DB::table('sy')
            ->orderBy('sydesc','desc')
            ->select(
                  'sy.*',
                  'sydesc as text'
            )
            ->get(); 
   $activesy = DB::table('sy')->where('isactive',1)->first()->id; 
   $schoolinfo = DB::table('schoolinfo')->first(); 

   $months = array();

   for($x = 1; $x <= 12; $x++){
      array_push( $months, (object)['id'=>$x,'desc'=> \Carbon\Carbon::create('2023',$x,'1')->isoFormat('MMM')]);
   }



@endphp


<div class="modal fade" id="schooldays_copy_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Copy Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Copy from grade level</label>
                                    <select class="form-control select2" id="copy_gradelevel" >
                                    </select>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="copy_to"><i class="fas fa-copy"></i> Copy</button>
                              </div>
                        </div>
                  </div>
                 
            </div>
      </div>
</div>   

<div class="modal fade" id="active_month_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Month Status</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                       <div class="row">
                              <div class="col-md-12">
                                    <table class="table table-sm table-bordered" style="font-size:.9rem !important;">
                                          <thead>
                                                <tr>
                                                      <td width="30%"></td>
                                                      @foreach($months as $month)
                                                            <th class="text-center" width="{{70 / 12}}%">{{$month->desc}}</th>
                                                      @endforeach
                                                </tr>
                                                <tr>
                                                      <td></td>
                                                      @foreach($months as $month)
                                                            <td class="text-center" >
                                                                  <div class="icheck-primary d-inline pt-2">
                                                                        <input class="all_month month_status" type="checkbox" id="all_month_{{$month->id}}" data-month="{{$month->id}}">
                                                                        <label for="all_month_{{$month->id}}"></label>
                                                                  </div>
                                                            </td>
                                                      @endforeach
                                                </tr>
                                          </thead>
                                          <tbody id="active_detail">
                                          </tbody>
                                    </table>
                              </div>
                       </div>
                  </div>
                 
            </div>
      </div>
</div>   

<div class="modal fade" id="attendance_setup_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Add Month Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Dates</label>
                                    <input type="text" class="form-control form-control-sm date" id="input_date" autocomplete="off">
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Year</label>
                                    <input disabled="disabled" id="input_year" class="form-control form-control-sm"/>
                              </div>
                              <div class="col-md-12 form-group sem_holder" hidden>
                                    <label for="">Semester</label>
                                    <select class="form-control form-control-sm select2" id="input_sem">
                                          <option value="1">1st Semester</option>
                                          <option value="2">2nd Semester</option>
                                    </select>
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Days</label>
                                    <input class="form-control form-control-sm" id="input_day" oninput="this.value=this.value.replace(/[^0-9]/g,'');" autocomplete="off" disabled>
                              </div>
                           
                              <div class="col-md-12 form-group">
                                    <label for="">Month</label>
                                    <select class="form-control form-control-sm select2" id="input_month" disabled="disabled">
                                          <option value="">No dates selected</option>
                                          <option value="1">January</option>
                                          <option value="2">February</option>
                                          <option value="3">March</option>
                                          <option value="4">April</option>
                                          <option value="5">May</option>
                                          <option value="6">June</option>
                                          <option value="7">July</option>
                                          <option value="8">August</option>
                                          <option value="9">September</option>
                                          <option value="10">October</option>
                                          <option value="11">November</option>
                                          <option value="12">December</option>
                                    </select>
                              </div>
                              <div class="col-md-12 form-group">
                                    <label for="">Sort</label>
                                    <input class="form-control form-control-sm" id="input_sort" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" onkeydown="return /[a-z]/i.test(event.key)">
                              </div>
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="attendance_setup_create">Create</button>
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
                        <h1>School Days</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">School Days</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="info-box shadow-lg">
                          <div class="info-box-content">
                              <div class="row">
                                    <div class="col-md-4">
                                         <h5><i class="fa fa-filter"></i> Filter</h5> 
                                    </div>
                                    <div class="col-md-8">
                                          <h5 class="float-right">Active S.Y.: {{collect($sy)->where('isactive',1)->first()->sydesc}}</h5>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-2">
                                          <label for="">School Year</label>
                                          <select class="form-control select2" id="input_sy">
                                                @foreach ($sy as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-2">
                                          <label for="">Grade Level</label>
                                          <select class="form-control select2" id="filter_gradelevel">
                                          </select>
                                    </div>
                                    <div class="col-md-2">
                                          <label for="">Class</label>
                                          <select class="form-control select2" id="filter_class">
                                                <option value="0">Regular</option>
                                                <option value="1">MWSP</option>
                                          </select>
                                    </div>
                              </div>
                          </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow" style="">
                              <div class="card-body">
                                    <div class="row">
                                         
                                    </div>
                                    <div class="row mt-2">
                                          <div class="col-md-12">
                                                <table class="table table-striped table-sm table-bordered " id="attendance_setup" width="100%">
                                                      <thead>
                                                            <tr>
                                                                  <th width="5%"></th>
                                                                  <th width="10%">Year</th>
                                                                  <th width="20%">Month</th>
                                                                  <th width="45%">Dates</th>
                                                                  <th width="10%" class="text-center">Days</th>
                                                                  <th width="5%"></th>
                                                                  <th width="5%"></th>
                                                            </tr>
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
      <script src="{{asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
      <script>
            $(document).ready(function(){

                  $(document).on('click','#active_month_to',function(){
                        active_month_display()
                        $('#active_month_modal').modal()
                  })

                  $('.date').datepicker({
                        changeMonth: false,
                        multidate: true,
                        stepMonths: 0,
	                  format: 'yyyy-mm-dd',
                        stepMonths: 0,
                        clearBtn:true,
                        beforeShowDay: function(date) {
                              var currentDate = (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();
                              var selectedDates = $('.date').datepicker('getDates');
                              for (var i = 0; i < selectedDates.length; i++) {
                                    if (selectedDates[i].getMonth() !== date.getMonth()) {
                                          return {
                                                enabled: false
                                          };
                                    }
                              }
                              return {
                                    enabled: true
                              };
                        }
                  }).on('change', function (ev) {
                        var alldates = $(this).val();
                        if(alldates != ""){
                              var tempdate = alldates.split(",")[0]
                              const d = new Date(tempdate);
                              $('#input_month').val(d.getMonth()+1).change()
                              $('#input_year').val(d.getFullYear()).change()
                              $('#input_day').val(alldates.split(",").length).change()
                              $('#input_year').attr('disabled','disabled')
                        }else{
                              $('#input_month').val("").change()
                              $('#input_year').val("").change()
                        }
                        
                  })

            })
      </script>
   

      <script>
            var school_setup = @json($schoolinfo);
            var userid = @json(auth()->user()->id);
            var months = @json($months)
            
    
            function get_last_index(tablename){
                $.ajax({
                        type:'GET',
                        url: '/monitoring/tablecount',
                        data:{
                            tablename: tablename
                        },
                        success:function(data) {
                            lastindex = data[0].lastindex
                            update_local_table_display(tablename,lastindex)
                        },
                })
            }
    
            function update_local_table_display(tablename,lastindex){
                $.ajax({
                        type:'GET',
                        url: school_setup.es_cloudurl+'/monitoring/table/data',
                        data:{
                            tablename:tablename,
                            tableindex:lastindex
                        },
                        success:function(data) {
                            if(data.length > 0){
                                    process_create(tablename,0,data)
                            }
                        },
                        error:function(){
                            $('td[data-tablename="'+tablename+'"]')[0].innerHTML = 'Error!'
                        }
                })
            }
    
            function process_create(tablename,process_count,createdata){
                if(createdata.length == 0){
                        Toast.fire({
                              type: 'success',
                              title: 'Created Successfully!'
                        })
                      get_attendance_setup()
                      return false;
                }
                var b = createdata[0]
                $.ajax({
                        type:'GET',
                        url: '/synchornization/insert',
                        data:{
                            tablename: tablename,
                            data:b
                        },
                        success:function(data) {
                            process_count += 1
                            createdata = createdata.filter(x=>x.id != b.id)
                            process_create(tablename,process_count,createdata)
                        },
                        error:function(){
                            process_count += 1
                            createdata = createdata.filter(x=>x.id != b.id)
                            process_create(tablename,process_count,createdata)
                        }
                })
            }
    
            //get_updated
            function get_updated(tablename){
                var date = moment().subtract(2, 'minute').format('YYYY-MM-DD HH:mm:ss');
                $.ajax({
                    type:'GET',
                    url: school_setup.es_cloudurl+'/monitoring/table/data/updated',
                    data:{
                            tablename: tablename,
                            date: date
                    },
                    success:function(data) {
                        process_update(tablename,data)
                    }
                })
            }
    
    
            function process_update(tablename , updated_data){
                  if (updated_data.length == 0){
                        Toast.fire({
                              type: 'success',
                              title: 'Updated Successfully!'
                        })
                        get_attendance_setup()
                        return false
                  }
    
                var b = updated_data[0]
    
                $.ajax({
                    type:'GET',
                    url: '/synchornization/update',
                    data:{
                        tablename: tablename,
                        data:b
                    },
                    success:function(data) {
                        updated_data = updated_data.filter(x=>x.id != b.id)
                        process_update(tablename,updated_data)
                    },
                })
            }
    
            //get_updated
            function get_deleted(tablename){
                var date = moment().subtract(1, 'minute').format('YYYY-MM-DD HH:mm:ss');
                $.ajax({
                    type:'GET',
                    url: school_setup.es_cloudurl+'/monitoring/table/data/deleted',
                    data:{
                            tablename: tablename,
                            date: date
                    },
                    success:function(data) {
                        process_deleted(tablename,data)
                    }
                })
            }
    
            function process_deleted(tablename , deleted_data){
                if (deleted_data.length == 0){
                  Toast.fire({
                        type: 'success',
                        title: 'Deleted Successfully!'
                  })
                   get_attendance_setup()
                    return false
                }
                var b = deleted_data[0]
                $.ajax({
                    type:'GET',
                    url: '/synchornization/delete',
                    data:{
                        tablename: tablename,
                        data:b
                    },
                    success:function(data) {
                        deleted_data = deleted_data.filter(x=>x.id != b.id)
                        process_deleted(tablename,deleted_data)
                    },
                })
            }
    
    
      </script>


      <script>

            const Toast = Swal.mixin({          
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000,
            })

            get_gradelevel()

            function get_gradelevel(){

                  $.ajax({
                        type:'GET',
                        url: '/superadmin/attendance/getgradelevel',
                        data:{
                              syid:$('#input_sy').val(),
                        },
                        success:function(data) {

                              gradelevel = data
                              $('#filter_gradelevel').empty()
                              $("#filter_gradelevel").append('<option value="">Select Grade Level</option>');
                              $("#filter_gradelevel").select2({
                                    data: gradelevel,
                                    placeholder: "Select Grade Level",
                                    allowClear:true
                              })

                              gradelevel = data
                              $('#copy_gradelevel').empty()
                              $("#copy_gradelevel").append('<option value="">Select Grade Level</option>');
                              $("#copy_gradelevel").select2({
                                    data: gradelevel,
                                    placeholder: "Select Grade Level",
                                    allowClear:true
                              })

                              active_month_display()

                              
                        }
                  })

            }

            function active_month_display(){
                  $('#active_detail').empty()
                 $.each(gradelevel,function(a,b){
                        var month = '<tr><td>'+b.text+'</td>'
                        $.each(months, function(c,d){
                              month += `<td class="text-center"> <div class="icheck-primary d-inline pt-2">
                                                <input class="month_status per_level_month_status" disabled="disabled" type="checkbox" id="setup_`+b.id+`_`+d.id+`" data-month="`+d.id+`" data-level="`+b.id+`">
                                                <label for="setup_`+b.id+`_`+d.id+`"></label>
                                             </div>
                                          </td>`
                        })
                        month += '</tr>'
                        $('#active_detail').append(month)
                 })

                 $('.all_month').prop('checked',false)

                 setup_summary()
            }

            $(document).on('change','.month_status',function(){
                  var month = $(this).attr('data-month')
                  var levelid = $(this).attr('data-level')
                  var status = 1 ;
                  if(!$(this).prop('checked')){
                        status = 0
                  }
                  $.ajax({
                        type:'GET',
                        url: '/superadmin/setup/settoactive',
                        data:{
                              syid:$('#input_sy').val(),
                              month:month,
                              status:status,
                              levelid:levelid
                        },
                        success:function(data) {
                             if(data[0].status == 1){
                                    Toast.fire({
                                          type: 'success',
                                          title: 'Status Updated!'
                                    })
                                    active_month_display()
                             }else{
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                             }
                        }
                  })
            })

            function setup_summary(){
                  $.ajax({
                        type:'GET',
                        url: '/superadmin/setup/summary',
                        data:{
                              schoolyear:$('#input_sy').val(),
                              attclass:$('#filter_class').val()
                        },
                        success:function(data) {
                             $.each(data,function(a,b){
                                    $.each(b.attsetup,function(c,d){
                                          $('#setup_'+b.id+'_'+d.month).removeAttr('disabled')
                                          if(d.isactive == 1){
                                                $('#setup_'+b.id+'_'+d.month).prop('checked',true)
                                          }
                                    })
                             })

                             $.each(months,function(a,b){
                                    var notchecked = $('.per_level_month_status:not(":checked"):not(":disabled")[data-month="'+b.id+'"]').length
                                    var notdisabled = $('.per_level_month_status:not(":disabled")[data-month="'+b.id+'"]').length
                                    if(notchecked == 0 && notdisabled > 0){
                                          $('#all_month_'+b.id).prop('checked',true)
                                    }
                                    if(notdisabled == 0){
                                          $('#all_month_'+b.id).attr('disabled','disabled')
                                    }
                             })
                        }
                  })
            }

            var syid = @json($activesy);
            var all_sy = @json($sy);
            var allgradelevel = []
            var all_attendance_setup = []

            function get_attendance_setup(){
                  $.ajax({
                        type:'GET',
                        url: '/superadmin/attendance/list',
                        data:{
                              schoolyear:$('#input_sy').val(),
                              levelid:$('#filter_gradelevel').val(),
                              attclass:$('#filter_class').val()
                        },
                        success:function(data) {
                              all_attendance_setup = data
                              Toast.fire({
                                    type: 'info',
                                    title: all_attendance_setup.length+' month(s) found!'
                              })
                              loaddatatable()
                        }
                  })
            }

            function loaddatatable(){

                  var total = 0;

                  $.each(all_attendance_setup.filter(x=>x.sort != 'ZZ'),function(a,b){
                        total += parseInt(b.days)
                  })

                  $('#total_number_of_schooldays').text(total)
                  $('#total_months').text(all_attendance_setup.length)

                  var check = all_attendance_setup.filter(x=>x.sort == 'ZZ')

                  if(check.length > 0){
                        var index = all_attendance_setup.findIndex(x=>x.sort == 'ZZ')
                        all_attendance_setup[index].days = total 
                  }else{
                        all_attendance_setup.push({
                              'sort':'ZZ',
                              'year':'',
                              'monthdesc':'',
                              'days':total
                        })

                  }

                  var temp_sy = all_sy.filter(x=>x.id == $('#input_sy').val())[0]



                  $("#attendance_setup").DataTable({
                        destroy: true,
                        data:all_attendance_setup,
                        lengthChange: false,
                        pageLength: 50,
                        paging: false,
                        bInfo: false,
                        autoWidth: false,
                        order: [
                                    [ 0, "asc" ],
                                    [ 1, "asc" ]
                              ],
                        columns: [
                              { "data": "sort" },
                              { "data": "year" },
                              { "data": "monthdesc" },
                              { "data": null },
                              { "data": "days" },
                              { "data": null },
                              { "data": null },
                        ],
                        columnDefs: [
                                          {
                                                'targets': 0,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.sort == 'ZZ'){
                                                            $(td).text(null)
                                                      }
                                                      $(td).addClass('align-middle')
                                                      $(td).addClass('text-center')
                                                }
                                          },
                                          {
                                                'targets': 1,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.sort != 'ZZ'){
                                                            if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                                                                  var semid = '1st Sem';
                                                                  if(rowData.semid == 2){
                                                                        var semid = '2nd Sem';
                                                                  }
                                                                  $(td)[0].innerHTML = rowData.year+' : '+'<span class="text-success">'+semid+'</span>'
                                                            }
                                                      }
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 2,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.sort == 'ZZ'){
                                                            $(td).addClass('text-right pr-3 font-weight-bold')
                                                            return false
                                                      }
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 3,
                                                'orderable': true, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.sort == 'ZZ'){
                                                            $(td).addClass('font-weight-bold')
                                                            $(td).text('Total')
                                                            return false
                                                      }else{
                                                            var text = ''
                                                            $.each(rowData.dates,function(a,b){
                                                                  var date = new Date(b)
                                                                  text += date.getDate()
                                                                  if((a+1) != rowData.dates.length){
                                                                        text  += ' , '
                                                                  }
                              
                                                            })
                                                            $(td).text(text)
                                                      }
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 4,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      $(td).addClass('text-center')
                                                      if(rowData.sort == 'ZZ'){
                                                            $(td).addClass('font-weight-bold')
                                                            return false
                                                      }
                                                      $(td).addClass('align-middle')
                                                }
                                          },
                                          {
                                                'targets': 5,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.sort == 'ZZ'){
                                                            $(td).text(null)
                                                            return false;
                                                      }
                                                      if(temp_sy.ended == 0){
                                                            var buttons = '<a href="#" class="edit" data-id="'+rowData.id+'"><i class="far fa-edit"></i></a>';
                                                            $(td)[0].innerHTML =  buttons
                                                            $(td).addClass('text-center')
                                                      }else{
                                                            $(td).text(null)
                                                      }
                                                      $(td).addClass('align-middle')
                                                      
                                                }
                                          },
                                          {
                                                'targets': 6,
                                                'orderable': false, 
                                                'createdCell':  function (td, cellData, rowData, row, col) {
                                                      if(rowData.sort == 'ZZ'){
                                                            $(td).text(null)
                                                            return false;
                                                      }
                                                      if(temp_sy.ended == 0){
                                                            var buttons = '';
                                                            buttons += '<a href="#" class="delete" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                            $(td)[0].innerHTML =  buttons
                                                            $(td).addClass('text-center')
                                                            $(td).addClass('p-0')
                                                            $(td).addClass('align-middle')
                                                      }else{
                                                            $(td).text(null)
                                                      }
                                                }
                                          },
                                    ]
                  });

                  var disabled = ''
                  if($('#filter_gradelevel').val() == "" || $('#filter_gradelevel').val() == null){
                        disabled = 'disabled'
                  }

                  console.log(disabled)

                  if(temp_sy.ended == 0){
                        var label_text = $($('#attendance_setup'+'_wrapper')[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = ' <button class="btn btn-primary btn-sm" id="attendance_setup_button" '+disabled+'><i class="fas fa-plus" ></i> Add Month</button>  <button class="btn btn-primary btn-sm btn-warning " id="schooldays_copy_to" '+disabled+'><i class="fas fa-copy"></i> Copy From</button><button class="btn btn-primary btn-sm btn-secondary ml-2" id="active_month_to" ><i class="fas fa-copy"></i> Active Month</button>'
                  }

            }

      </script>

      <script>
            $(document).ready(function(){
                
                  var selected_setup
                  var process = 'create'

                  $('.select2').select2()

                  $(document).on('change','#filter_gradelevel',function(){
                        if($(this).val() == ""){
                              $('#attendance_setup_button').attr('disabled','disabled')
                              $('#schooldays_copy_to').attr('disabled','disabled')
                              get_attendance_setup()
                        }else{
                              $('#attendance_setup_button').removeAttr('disabled')
                              $('#schooldays_copy_to').removeAttr('disabled')
                              get_attendance_setup()
                        }

                        if($(this).val() == 14 || $(this).val() == 15){
                              $('.sem_holder').removeAttr('hidden')
                        }else{
                              $('.sem_holder').attr('hidden','hidden')
                        }
                  })

                  $(document).on('click','#copy_to',function(){

                        $('#copy_to').attr('disabled','disabled')
                        $('#copy_to').text('Processing...')

                        $.ajax({
                              type:'GET',
                              url: '/superadmin/setup/schooldays/copy',
                              data:{
                                    syid_from:$('#copy_sy').val(),
                                    syid_to:$('#input_sy').val(),
                                    gradelevel_from:$('#copy_gradelevel').val(),
                                    gradelevel_to:$('#filter_gradelevel').val(),
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          $('#copy_to').removeAttr('disabled','disabled')
                                          $('#copy_to')[0].innerHTML = '<i class="fas fa-copy"></i> Copy'
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].copied+'(s) Months Copied!'
                                          })
                                          get_attendance_setup()
                                    }else{
                                          Toast.fire({
                                                type: 'danger',
                                                title: data[0].data
                                          }) 
                                    }
                              }
                        })
                  })

                  $(document).on('input','#input_sort',function(){
                        if($(this).val().length > 1){
                              $(this).val($(this).val().slice(0,-1))
                        }
                  })

                  loaddatatable()

                  $(document).on('click','#attendance_setup_button',function(){

                        process = 'create'
                        $('#attendance_setup_create')[0].innerHTML = '<i class="fas fa-save"></i> Create'
                        $('#attendance_setup_modal').modal()    
                        $('#input_month').val("").change()
                        $('#input_day').val("")
                        $('#input_sort').val("")                
                        $('#input_date').val("")                
                        $('.date').datepicker().datepicker("setDate", []);

                        if(all_attendance_setup.length > 1){
                              $('#input_sort').attr('placeholder','Last sort '+all_attendance_setup[all_attendance_setup.length-2].sort)
                        }else{
                              $('#input_sort').attr('placeholder','Sort')
                        }
                  })

                  $(document).on('change','#input_sy',function(){
                        get_attendance_setup()
                  })

                  $(document).on('change','#filter_class',function(){
                        get_attendance_setup()
                  })


                  $(document).on('click','#attendance_setup_create',function(){
                        
                        if(process == 'create'){
                              attendance_setup_create()           
                        }else if(process == 'edit'){
                              attendance_setup_update()  
                        }
                  })

                  $(document).on('click','.delete',function(){
                        selected_setup = $(this).attr('data-id')
                        attendance_setup_delete()
                  })

                  $(document).on('click','.edit',function(){
                        selected_setup = $(this).attr('data-id')
                        process = 'edit'
                        $('#input_date').val("")
                        var temp_attendance_id = all_attendance_setup.filter(x=>x.id == selected_setup)
                        $('#input_month').val(temp_attendance_id[0].month).change(),
                        $('#input_day').val(temp_attendance_id[0].days),
                        $('#input_year').val(temp_attendance_id[0].year),
                        $('#input_sort').val(temp_attendance_id[0].sort)
                        $('.date').datepicker().datepicker("setDate", temp_attendance_id[0].dates);

                        $('#attendance_setup_modal').modal()       
                        $('#attendance_setup_create')[0].innerHTML = '<i class="fas fa-save"></i> Update'
                        $('#input_month').attr('disabled','disabled')
                  })

                  
      
                  function attendance_setup_create(){

                        var valid_input = true

                        var check_duplications = all_attendance_setup.filter(x=>x.month == $('#input_month').val())
                        if(check_duplications.length > 0){
                              valid_input = false
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Month already exist!'
                              })
                        }
                        else if($('#input_date').val() == ""){
                              valid_input = false
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Date is empty!'
                              })
                        }
                        else if($('#input_sort').val() == ""){
                              valid_input = false
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Sort is empty!'
                              })
                        }

                        var semid = 1;
                        if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                              var semid = $('#input_sem').val()
                        }

                        var url = '/superadmin/attendance/create'

                        if(school_setup.setup == 1 && school_setup.projectsetup == 'offline'){
                              url = school_setup.es_cloudurl+'/superadmin/attendance/create'
                              
                        }

                        if(valid_input){

                              $('#attendance_setup_create').attr('disabled','disabled')
                              $('#attendance_setup_create').text('Processing...')


                              $.ajax({
					      type:'GET',
                                    url: url,
                                    data:{
                                          syid:$('#input_sy').val(),
                                          sort:$('#input_sort').val(),
                                          year:$('#input_year').val(),
                                          attclass:$('#filter_class').val(),
                                          semid:semid,
                                          dates:$('#input_date').val(),
                                          levelid:$('#filter_gradelevel').val(),
                                          userid:userid
                                    },
                                    success:function(data) {
                                          if(data[0].status == 1){
                                                $('#attendance_setup_create').removeAttr('disabled','disabled')
                                                $('#attendance_setup_create')[0].innerHTML = '<i class="fas fa-save"></i> Create'
                                                if(school_setup.setup == 1 && school_setup.projectsetup == 'offline'){
                                                      get_last_index('studattendance_setup')
                                                }else{
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: 'Created Successfully!'
                                                      })
                                                      get_attendance_setup()
                                                }
                                               
                                          }
                                          else if(data[0].status == 2){
                                                Toast.fire({
                                                      type: 'warning',
                                                      title: data[0].data
                                                })
                                          }
                                          else{
                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'Something went wrong!'
                                                })
                                          }
                                    }
                              })
                        }



                       
                  }

                  function attendance_setup_update(){

                        var check_duplications = all_attendance_setup.filter(x=>x.month == $('#input_month').val() && x.id != selected_setup)
                        valid_input = true
                        if(check_duplications.length > 0){
                              valid_input = false
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Month already exist!'
                              })
                        }
                        else if($('#input_date').val() == ""){
                              valid_input = false
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Date is empty!'
                              })
                        }
                        else if($('#input_sort').val() == ""){
                              valid_input = false
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Sort is empty!'
                              })
                        }

                        var semid = 1;
                        if($('#filter_gradelevel').val() == 14 || $('#filter_gradelevel').val() == 15){
                              var semid = $('#input_sem').val()
                        }

                        var url = '/superadmin/attendance/update'

                        if(school_setup.setup == 1 && school_setup.projectsetup == 'offline'){
                              url = school_setup.es_cloudurl+'/superadmin/attendance/update'
                              
                        }

                        if(valid_input){

                              $('#attendance_setup_create').attr('disabled','disabled')
                              $('#attendance_setup_create').text('Processing...')

                              $.ajax({
                                    type:'GET',
                                    url: url,
                                    data:{
                                          syid:$('#input_sy').val(),
                                          sort:$('#input_sort').val(),
                                          year:$('#input_year').val(),
                                          attclass:$('#filter_class').val(),
                                          attsetupid:selected_setup,
                                          semid:semid,
                                          dates:$('#input_date').val(),
                                          levelid:$('#filter_gradelevel').val(),
                                          userid:userid
                                    },
                                    success:function(data) {
                                          if(data[0].status == 1){
                                                $('#attendance_setup_create').removeAttr('disabled','disabled')
                                                $('#attendance_setup_create')[0].innerHTML = '<i class="fas fa-save"></i> Update'

                                                if(school_setup.setup == 1 && school_setup.projectsetup == 'offline'){
                                                      get_updated('studattendance_setup')
                                                }else{
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: 'Updated Successfully!'
                                                      })
                                                      get_attendance_setup()
                                                }
                                          }else if(data[0].status == 2){
                                                Toast.fire({
                                                      type: 'warning',
                                                      title: data[0].data
                                                })
                                          }else{
                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'Something went wrong!'
                                                })
                                          }
                                    },
                                    error:function(){
                                          Toast.fire({
                                                      type: 'error',
                                                      title: 'Something went wrong!'
                                                })
                                    }
                              })
                        }
                  }


                  function attendance_setup_delete(){

                        Swal.fire({
                              title: 'Do you want to remove month?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        }).then((result) => {
                              if (result.value) {

                                    var url = '/superadmin/attendance/delete'

                                    if(school_setup.setup == 1 && school_setup.projectsetup == 'offline'){
                                          url = school_setup.es_cloudurl+'/superadmin/attendance/delete'
                                          
                                    }

                                    $.ajax({
                                          type:'GET',
                                          url: url,
                                          data:{
                                                attsetupid:selected_setup,
                                                syid:$('#input_sy').val(),
                                                userid:userid
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      if(school_setup.setup == 1 && school_setup.projectsetup == 'offline'){
                                                            get_deleted('studattendance_setup')
                                                      }else{
                                                            Toast.fire({
                                                                  type: 'success',
                                                                  title: 'Deleted Successfully!'
                                                            })
                                                            all_attendance_setup = all_attendance_setup.filter(x=>x.id != selected_setup)
                                                            loaddatatable()
                                                      }
                                                }else{
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: 'Something went wrong!'
                                                      })
                                                }
                                          }
                                    })
                              }
                        })

                        
                  }

                  $(document).on('click','#schooldays_copy_to',function(){
                        var temp_sy = all_sy.filter(x=>x.id != $('#input_sy').val())
                        $('#schooldays_copy_modal').modal()
                        $('#copy_gradelevel').val("").change()
                  })

                  


            })
      </script>


@endsection


