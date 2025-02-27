
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
      <style>
            .shadow {
                box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                border: 0 !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                margin-top: -9px;
            }
            .grade_option_checkbox {
                height: calc(1rem + 1px);
            }
        </style>
        @php
            $schoolinfo = DB::table('schoolinfo')->first();
        @endphp
@endsection

@section('modalSection')
      <div class="modal fade" id="modal-primary" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">RFID Registration Form</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <div class="form-group">
                              <label for="">RFID Code</label>
                              <input type="text" id="idnum" name="idnum" class="form-control" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                              <p class="text-danger"><i>Tap the card in the scanner to register.</i></p>
                        </div>
                  </div>
            </div>
            </div>
      </div>
      <div class="modal fade" id="expire_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                  <div class="row">
                              <div class="col-md-12 form-group">
                                    <button class="btn btn-primary btn-block expire_rfid" data-type="all">All</button>
                              </div>
                              <div class="col-md-12 form-group">
                                    <button class="btn btn-primary btn-block expire_rfid" data-type="7">All Students</button>
                              </div>
                              <div class="col-md-12 form-group">
                                    <button class="btn btn-primary btn-block expire_rfid" data-type="1">All FAS</button>
                              </div>
                              
                  </div>
                  </div>
            </div>
            </div>
      </div>
      <div class="modal fade" id="renew_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Renew Card</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                  <div class="row">
                              <div class="col-md-12 form-group">
                                    <button class="btn btn-primary btn-block renew_rfid" data-type="all">All</button>
                              </div>
                              <div class="col-md-12 form-group">
                                    <button class="btn btn-primary btn-block renew_rfid" data-type="7">All Students</button>
                              </div>
                              <div class="col-md-12 form-group">
                                    <button class="btn btn-primary btn-block renew_rfid" data-type="1">All FAS</button>
                              </div>
                              
                  </div>
                  </div>
            </div>
            </div>
      </div>

      <div class="modal fade" id="upload_rfid_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Upload RFID</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                        <form 
                              action="/rfid/list/upload" 
                              id="upload_rfid" 
                              method="POST" 
                              enctype="multipart/form-data"
                              >
                              @csrf
                              <div class="row">
                                    <div class="col-md-12 form-group">
                                          <label for="">RFID File</label>
                                          <input type="file" class="form-control" name="input_ecr" id="input_ecr">
                                    </div>
                                    <div class="col-md-12">
                                          <button class="btn btn-info" id="upload_ecr_button" >Upload RFID</button>
                                    </div>
                              </div>
                        </span>
                        </form>
                  </div>
            </div>
            </div>
      </div>

@endsection

@section('content')
      <section class="content-header">
            <div class="container-fluid">
                  <div class="row mb-2">
                        <div class="col-sm-6">
                              <h1>RFID Registration</h1>
                        </div>
                        <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                              <li class="breadcrumb-item"><a href="/home">Home</a></li>
                              <li class="breadcrumb-item active">RFID Registration</li>
                        </ol>
                        </div>
                  </div>
            </div>
      </section>
      <section class="content pt-0">
            <div class="container-fluid">
                  <div class="row">
                        <div class="col-md-8">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="">Status</label>
                                            <select class="form-control select2" id="filter_status">
                                                <option value="">All</option>
                                                <option value="1">Expired</option>
                                                <option value="0">Not Expired</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">Assignment</label>
                                            <select class="form-control select2" id="filter_assignment">
                                                <option value="">All</option>
                                                <option value="1">Assigned</option>
                                                <option value="0">Not Assigned</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="">Type</label>
                                            <select class="form-control select2" id="filter_type">
                                                <option value="">All</option>
                                                <option value="expired">Expired</option>
                                                <option value="new">New</option>
                                                @if($schoolinfo->RFIDRenewal == 1)
                                                      <option value="renewal">Renew</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                          <label for="">Holder Type</label>
                                          <select class="form-control select2" id="filter_holdertype">
                                              <option value="">All</option>
                                              <option value="7">Student</option>
                                              <option value="1">Faculty & Staff</option>
                                          </select>
                                      </div>
                                     
                                    </div>
                                    <div class="row mt-2">
                                          <div class="col-md-6">
                                                <label for="">Status Date</label>
                                                <input class="form-control  form-control-sm-form" id="filter_enrollmentdate" style="height: calc(1.7rem + 1px);">
                                          </div>
                                          <div class="col-md-6">
                                                <label for="">Registration Date</label>
                                                <input class="form-control  form-control-sm-form" id="filter_regdate" style="height: calc(1.7rem + 1px);">
                                          </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div class="col-md-4">
                              <div class="row">
                                    <div class="col-md-6">
                                          <div class="info-box mb-3 bg-warning">
                                                <span class="info-box-icon"><i class="fa fa-credit-card"></i></span>
                                  
                                                <div class="info-box-content">
                                                  <span class="info-box-text">RFID </span>
                                                  <span class="info-box-number" id="rfid_count"></span>
                                                </div>
                                          </div>
                                    </div>
                                    <div class="col-md-6">
                                          <div class="info-box mb-3 bg-success">
                                                <span class="info-box-icon"><i class="fa fa-check-square"></i></span>
                                                <div class="info-box-content">
                                                  <span class="info-box-text">Active</span>
                                                  <span class="info-box-number" id="activecard_count"></span>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-12">
                                          <div class="badge badge-success">Not Expired</div>
                                          <div class="badge badge-danger">Expired</div>
                                          <div class="badge badge-primary">Student</div>
                                          <div class="badge badge-success">Faculty and Staff</div>
                                          <div class="badge badge-success">New</div>
                                          @if($schoolinfo->RFIDRenewal == 1)
                                                <div class="badge badge-warning">Renew</div>
                                          @endif
                                    </div>
                              </div>
                        </div>
                      
                 </div>
                 <div class="row">
                        <div class="col-md-12">
                              <div class="card shadow" >
                                    <div class="card-body p-1 text-right">
                                          <div class="row">
                                                <div class="col-md-12" style="font-size:.9rem !important">
                                                      <button class="btn btn-default btn-sm ml-2" id="download_list" ><i class="fas fa-file-excel"></i> Download List</button><button class="btn btn-secondary btn-sm ml-2" id="upload_list"><i class="fa fa-upload"></i> Upload List</button> 
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-12">
                              <div class="card shadow" >
                                    <div class="card-body">
                                          <div class="row">
                                                <div class="col-md-12" style="font-size:.9rem !important">
                                                      <table class="table-hover table table-striped table-sm table-bordered mb-0" id="rfid_table" width="100%" >
                                                            <thead>
                                                                  <tr>
                                                                        <th width="30%">RFID # / Reg. Date</th>
                                                                        <th width="30%">Holder / Holder Type</th>
                                                                        <th width="25%">Status / Status Date</th>
                                                                        <th width="10%"></th>
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
                  <div class="row">
                        <div class="col-md-4" hidden>
                              <div class="card shadow" style="">
                                    <div class="card-body">
                                          <div class="row">
                                                <div class="col-md-12">
                                                     <h5> Summary Report  </h5>
                                                </div>
                                          </div>
                                          <div class="row mt-2">
                                                <div class="col-md-12" style="font-size:.9rem !important">
                                                      <table class="table-hover table table-striped table-sm table-bordered mb-0" id="rfid_table" width="100%" >
                                                            <thead>
                                                                  <tr>
                                                                        <th width="28%">Status</th>
                                                                        <th width="18%">Stud.</th>
                                                                        <th width="18%" class="text-center">FAS</th>
                                                                        <th width="18%"  class="text-center">NA</th>
                                                                        <th width="18%" class="text-center">Total</th>
                                                                  </tr>
                                                            </thead>
                                                            <thead>
                                                                  <tr>
                                                                        <th>New</th>
                                                                        <td class="text-center" id="new_7"></td>
                                                                        <td class="text-center" id="new_1"></td>
                                                                        <td class="text-center" id="new_na"></td>
                                                                        <td class="text-center" id="new_total"></td>
                                                                  </tr>
                                                                  <tr>
                                                                        <th >Expired</th>
                                                                        <td class="text-center" id="expired_7"></td>
                                                                        <td class="text-center" id="expired_1"></td>
                                                                        <td class="text-center" id="expired_na"></td>
                                                                        <td class="text-center" id="expired_total"></td>
                                                                  </tr>
                                                                  @if($schoolinfo->RFIDRenewal == 1)
                                                                        <tr>
                                                                              <th >Renew</th>
                                                                              <td class="text-center" id="renew_7"></td>
                                                                              <td class="text-center" id="renew_1"></td>
                                                                              <td class="text-center" id="renew_na"></td>
                                                                              <td class="text-center" id="renew_total"></td>
                                                                        </tr>
                                                                  @endif
                                                                  <tr>
                                                                        <th>NA</th>
                                                                        <td class="text-center" >0</td>
                                                                        <td class="text-center" >0</td>
                                                                        <td class="text-center" id="na_na">0</td>
                                                                        <td class="text-center" id="na_total"></td>
                                                                  </tr>
                                                                  <tr>
                                                                        <th >Total</th>
                                                                        <td class="text-center" id="total_7"></td>
                                                                        <td class="text-center" id="total_1"></td>
                                                                        <td class="text-center" id="total_na"></td>
                                                                        <td class="text-center" id="total_total"></td>
                                                                  </tr>
                                                            </thead>
                                                      </table>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                        <div class="col-md-4">
                              <div class="card shadow" style="">
                                    <div class="card-body ">
                                          <div class="row">
                                                <div class="col-md-12">
                                                     <h5>Registration Date Count </h5>
                                                </div>
                                          </div>
                                          <div class="row mt-2">
                                                <div class="col-md-12 table-responsive" style="font-size:.9rem !important; height: 200px !important">
                                                      <table class="table-hover table-head-fixed table table-striped table-sm table-bordered mb-0" id="rfid_table" width="100%" >
                                                            <thead>
                                                                  <tr>
                                                                        <th width="70%">Date</th>
                                                                        <th width="30%" class="text-center">Count</th>
                                                                        
                                                                  </tr>
                                                            </thead>
                                                           <tbody id="regdate_holder">
                                                                
                                                           </tbody>
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
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
      <script src="{{asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

      <script>
            $(document).ready(function(){


                  var schoolinfo = @json($schoolinfo);
                  var all_rfid = []
                  load_rfid_datatable()
                  rfid_list()
                  rfid_regcount()
            
                  $('.select2').select2({
                        placeholder:'All',
                        allowClear:true
                  })

                  $( '#upload_rfid' )
                    .submit( function( e ) {
                        var inputs = new FormData(this)
                        $('#upload_ecr_button').attr('disabled','disabled')
                        $('#upload_ecr_button').text('Uploading...')
                        $.ajax({
                              url: '/rfid/list/upload',
                              type: 'POST',
                              data: inputs,
                              processData: false,
                              contentType: false,
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                    }else{
                                          Toast.fire({
                                                type: 'danger',
                                                title: 'Something went wrong'
                                          })
                                    }

                                    $('#upload_ecr_button').removeAttr('disabled')
                                    $('#upload_ecr_button').text('Upload RFID')

                                    load_rfid_datatable()
                              },
                              error:function(){
                                    $('#upload_ecr_button').removeAttr('disabled')
                                    $('#upload_ecr_button').text('Upload RFID')
                              }
                        })
                        e.preventDefault();
                  })

                  $(document).on('click','#download_list',function(){
                        window.open('rfid/list/download', '_blank');
                  })

                  $(document).on('click','#upload_list',function(){
                       $('#upload_rfid_modal').modal()
                  })
                  
                  $(document).on('change','#filter_assignment , #filter_status , #filter_type, #filter_holdertype',function(){
                        load_rfid_datatable()
                  })

                  function rfid_list(){
                        load_rfid_datatable()
                        // $.ajax({
                        //       type:'GET',
                        //       url: '/rfid/list',
                        //       success:function(data) {
                        //             if(data.length == 0){
                        //                   Toast.fire({
                        //                         type: 'warning',
                        //                         title: 'No RFID found!'
                        //                   })
                        //             }else{
                        //                   // Toast.fire({
                        //                   //       type: 'warning',
                        //                   //       title: data.length+' RFID found!'
                        //                   // })
                        //             }
                        //             all_rfid = data
                        //             load_rfid_datatable()

                                    
                        //             $('#rfid_count').text(data.length)
                        //             $('#activecard_count').text(data.filter(x=>x.type == 'renewal' ||  x.type == 'new').length)
                        //       }
                        // })
                  }

                  function rfid_regcount(){
                        $.ajax({
                              type:'GET',
                              url: '/rfidcard/summary/regdate',
                              success:function(data) {
                                   if(data[0].length == 0){
                                          $('#regdate_holder').append('<tr><td colspan="2">No data found.</td></tr>')
                                   }else{
                                          $.each(data,function(a,b){
                                                $('#regdate_holder').append('<tr><td>'+b.regdate+'</td><td class="text-center">'+b.count+'</td></tr>')
                                          })
                                   }
                              }
                        })
                  }

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  $('#filter_enrollmentdate , #filter_regdate').daterangepicker({
                        autoUpdateInput: false,
                        locale: {
                        cancelLabel: 'Clear'
                        }
                  });

                  $('#filter_enrollmentdate ,  #filter_regdate').on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                        var activeRequestsTable = $('#update_info_request_table').DataTable();
                        activeRequestsTable.state.clear();
                        activeRequestsTable.destroy();
                        load_rfid_datatable()
                  });

                  $('#filter_enrollmentdate ,  #filter_regdate').on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        var activeRequestsTable = $('#update_info_request_table').DataTable();
                        activeRequestsTable.state.clear();
                        activeRequestsTable.destroy();
                        load_rfid_datatable()
                  });

                  $(document).on('click','.expire_renew',function(){

                        var dataid = $(this).attr('data-id')
                        var rfidinfo = all_rfid.filter(x=>x.id == dataid)
                        var datatype = $(this).attr('data-type')

                        Swal.fire({
                              text: 'Are you sure you want to renew Card?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Renew Card'
                        }).then((result) => {
                              if (result.value) {

                                    if(rfidinfo[0].holdertype == 1){
                                          var url = '/adminemployeesetup/update'
                                    }else{
                                          var url = '/adminstudentrfidassign/update'
                                    }

                                    $.ajax({
                                          type:'GET',
                                          url: url,
                                          data:{
                                                studentid   : rfidinfo[0].holder,
                                                employeeid   : rfidinfo[0].holder,
                                                rfid        : rfidinfo[0].rfidcode,
                                                renew       : 'renew',
                                                datatype    : datatype
                                          },
                                          success:function(data){
                                                if(data == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: 'RFID renewed!'
                                                      })
                                                      rfid_list()
                                                }else{
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: data[0].message
                                                      })
                                                }
                                          }
                                    })
                              }
                        })
                  })

                  $(document).on('click','.expire_rfid_tomodal',function(){
                        $('#expire_modal').modal()
                  })

                  $(document).on('click','.renew_rfid_tomodal',function(){
                        $('#renew_modal').modal()
                  })

                  $(document).on('click','.expire_rfid',function(){

                        var dataid = $(this).attr('data-id')
                        var datatype = $(this).attr('data-type')

                        Swal.fire({
                              text: 'Are you sure you want to expire Card?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Expire Card'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'GET',
                                          url: '/rfid/expire/rfid',
                                          data:{
                                                dataid:dataid,
                                                datatype:datatype
                                          },
                                          success:function(data){
                                                if(data[0].status == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: data[0].message
                                                      })
                                                      rfid_list()
                                                }else{
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: data[0].message
                                                      })
                                                }
                                          }
                                    })
                              }
                        })
                  })

                  $(document).on('click','.delete_card',function(){

                        var dataid = $(this).attr('data-id')
                        Swal.fire({
                              text: 'Are you sure you want to delete Card?',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Delete Card'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'GET',
                                          url: '/rfidcard/delete',
                                          data:{
                                                dataid:dataid,
                                          },
                                          success:function(data){
                                                if(data[0].status == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: data[0].message
                                                      })
                                                      rfid_list()
                                                }else{
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: data[0].message
                                                      })
                                                }
                                          }
                                    })
                              }
                        })
                  })

                 

                  function load_rfid_datatable(){


                        $("#rfid_table").DataTable({
                              destroy: true,
                              // data:data,
                              lengthChange : false,
                              autoWidth: false,
                              stateSave: true,
                              serverSide: true,
                              processing: true,
                              ajax:{
                                    url: '/rfid/list',
                                    type: 'GET',
                                    data: {
                                          'filter_assignment':$('#filter_assignment').val(),
                                          'filter_status':$('#filter_status').val(),
                                          'filter_type':$('#filter_type').val(),
                                          'filter_holdertype':$('#filter_holdertype').val(),
                                          'filter_enrollmentdate':$('#filter_enrollmentdate').val(),
                                          'filter_regdate':$('#filter_regdate').val()
                                    },
                                    dataSrc: function ( json ) {
                                          $('#rfid_count').text(json.recordsTotal)
                                          $('#activecard_count').text(json.active)
                                          all_rfid = json.data
                                          return json.data;
                                    }
                              },
                              columns: [
                                    { "data": "rfidcode" },
                                    { "data": "holdername" },
                                    { "data": "type" },
                                    { "data": null },
                                    { "data": null },
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var badge = ''
                                               if(rowData.isexpired == 1){
                                                      badge = '<span class="badge badge-danger mr-2">&nbsp;</span>'
                                               }else if(rowData.isexpired == 0){
                                                      badge = '<span class="badge badge-success  mr-2">&nbsp;</span>'
                                               }
                                               $(td)[0].innerHTML = badge + rowData.rfidcode + '<p class="mb-0" style="font-size:.7rem !important">Reg. Date: '+rowData.createddatetime+'</p>'
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var holdertype = ''
                                                if(rowData.holdertype == 7){
                                                      holdertype = '<span class="badge badge-primary">Student</span>'
                                                }else if(rowData.holdertype == 1){
                                                      holdertype = '<span class="badge badge-success">Faculty and Staff</span>'
                                                }
                                                if(rowData.holdername != null){
                                                      $(td)[0].innerHTML = rowData.holdername + '<p class="mb-0" style="font-size:.7rem !important">'+holdertype+'</p>'
                                                }else{
                                                      $(td)[0].innerHTML = null
                                                }
                                               
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                          
                                                var date =''
                                                var typedis = ''
                                                if(rowData.type == 'expired'){
                                                      date = rowData.datetime
                                                      typedis = '<span class="badge badge-danger">Expired</span>'
                                                }else if(rowData.type == 'renewal' && schoolinfo.RFIDRenewal == 1){
                                                      date = rowData.datetime
                                                      typedis = '<span class="badge badge-warning">Renew</span>'
                                                }else if(rowData.type == 'new'){
                                                      date = rowData.datetime
                                                      typedis = '<span class="badge badge-success">New</span>'
                                                }else{
                                                      typedis = '<span class="badge badge-secondary">Not Assinged</span>'
                                                }
                                               
                                               $(td)[0].innerHTML = typedis + '<p class="mb-0" style="font-size:.7rem !important">'+date+'</p>'
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttontype = ''
                                                if(rowData.type == 'expired' && rowData.holder != null && schoolinfo.RFIDRenewal == 1){
                                                      var buttontype = '<button style="font-size:.9rem !important" class="btn-block btn btn-success btn-sm expire_renew" data-id="'+rowData.id+'"><i class="fas fa-sync-alt"></i> Renew</button>'
                                                }else if(rowData.type == 'renewal' || rowData.type == 'new'){
                                                      var buttontype = '<button style="font-size:.9rem !important" class="btn-block btn btn-danger btn-sm expire_rfid" data-id="'+rowData.id+'"><i class="fas fa-ban"></i> Expire</button>'
                                                }

                                                $(td)[0].innerHTML = buttontype
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                
                                                var buttons = '<a href="#" class="delete_card" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                                $(td)[0].innerHTML =  buttons
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },
                              ]
                              
                        });

                        var label_text = $($("#rfid_table_wrapper")[0].children[0])[0].children[0]
                        $(label_text)[0].innerHTML = '<button class="btn btn-primary btn-sm"  data-toggle="modal"  data-target="#modal-primary">Register RFID</button><button class="btn btn-danger btn-sm ml-2 expire_rfid_tomodal" ><i class="fas fa-ban"></i> Expire Card</button><button class="btn btn-success btn-sm ml-2 renew_rfid_tomodal" hidden><i class="fas fa-sync-alt"></i> Renew Card</button> '

                  }

                  function storerfid(){

                        $.ajax({
                              type:'POST',
                              url: '/storerfid/'+$('input[name=idnum]').val()+'/'+$('select[name=rfidschool]').val(),
                              data: {'_token': '{{ csrf_token() }}'},
                              success:function(data){
                                    if(data[0].status == 2){
                                          $('#idnum').val("")
                                          Toast.fire({
                                                type: 'warning',
                                                title: data[0].message
                                          })
                                    }else if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: data[0].message
                                          })
                                          $('#idnum').val("")
                                          rfid_list()
                                    }else{
                                          $('#idnum').val("")
                                          Toast.fire({
                                                type: 'error',
                                                title: data[0].message
                                          })
                                    }
                              }
                        })

                  }

                  $(document).on('keypress',function(e) {
                        if(e.which == 13) {
                              if($('input[name=idnum]').val() == ''){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'No input field selected!'
                                    })
                                    return false
                              }
                              storerfid()
                        }
                  });

            
            })
      </script>

      
@endsection

