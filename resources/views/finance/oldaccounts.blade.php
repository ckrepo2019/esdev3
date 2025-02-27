@extends('finance.layouts.app')

@section('content')
  <section class="content">
    <div class="row">
      <div class="col-md-8">
        <h1>
          Old Accounts
        </h1>
      </div>
      <div class="col-md-4 text-right">
        <button id="oa_setup" class="btn btn-default btn-lg" data-toggle="tooltip" title="Old Account Setup">
          <i class="fas fa-cogs"></i>
        </button>
      </div>
    </div>
    <div class="row form-group">
      <div class="col-md-2">
	  {{--<button id="oa_createoldacc" class="btn btn-info" style="margin-top: 32px;">Create Old Account</button>--}}

      </div>
      <div class="col-md-2">
        <label>Level</label>
        <select id="oa_levelid" class="select2bs4" style="width: 100%;">
          <option value="0">Grade Level</option>
          @foreach(db::table('gradelevel')->where('deleted', 0)->orderBy('sortid')->get() as $level)
            <option value="{{$level->id}}">{{$level->levelname}}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label>Old Account from</label>
        <select id="oa_syid" class="select2bs4" style="width: 100%;">
          
        </select>
      </div>
      <div class="col-md-2">
        <label>&nbsp;</label>
          <select id="oa_semid" class="select2bs4" style="width: 100%;">
        </select>
      </div>
      <div class="col-md-4">
        <label>Forward to <span>{{App\FinanceModel::getSYDesc()}}</span> <span id="oa_txtsem" style="display: none;"> - {{App\FinanceModel::getSemDesc()}}</span></label>
        <div class="input-group">
          <input type="search" id="oa_filter" class="form-control" placeholder="Search">
          {{-- <div class="input-group-append">
            <span class="input-group-text"></span>
          </div> --}}
          <div class="input-group-append">
            <button id="oa_search" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
          </div>
        </div>
      </div>
    </div>
    <div class="row form-group">
      <div class="col-md-12">
        <div class="main-card card">
          <div class="card-header bg-info">
          </div>
          <div id="main_table" class="card-body table-responsive p-0">
            <table class="table table-striped table-sm text-sm">
              <thead class="">
                <th>Name</th>
                <th class="text-center">Charges</th>
                <th class="text-center">Payment</th>
                <th class="text-center">Balance</th>
                <th colspan="2">
                  <button id="forward_all" class="btn btn-primary btn-block" style="display: none" data-toggle="tooltip" title="Forward All">
                    <i class="fas fa-external-link-alt"></i> &nbsp;
                    <span class="badge badge-danger bal_count">0</span>
                  </button>
                </th>
              </thead>
              <tbody id="balancelist">

              </tbody>
            </table>
          </div>
        </div>
      </div> 
    </div>
  </section>

@endsection
@section('modal')
  <div class="modal fade show" id="modal-setup" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header bg-secondary">
          <h4 class="modal-title">Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="particulars" class="">Classification</label>
                <div class="col-md-12">
                  <select id="oa_setupclassid" class="form-control select2bs4" style="width: 100%;">
                    <option value="0"></option>
                    @foreach(db::table('itemclassification')->where('deleted', 0)->orderBy('description')->get() as $class)
                      <option value="{{$class->id}}">{{$class->description}}</option>
                    @endforeach
                  </select> 
                </div>
              </div>

              <div class="form-group row">
                <label for="particulars" class="">Mode of Payment</label>
                <div class="col-md-12">
                  <select id="oa_setupmop" class="form-control select2bs4" style="width: 100%;">
                    <option value="0"></option>
                    @foreach(db::table('paymentsetup')->where('deleted', 0)->where('noofpayment', 1)->get() as $mop)
                      <option value="{{$mop->id}}">{{$mop->paymentdesc}}</option>
                    @endforeach
                  </select> 
                </div>
              </div>

							<div class="form-group row">
                <div class="col-md-12">
                  <div class="icheck-primary d-inline">
										<input type="checkbox" id="oa_setupclassified">
										<label for="oa_setupclassified">
											Classified
										</label>
									</div>
                </div>
              </div>
            
              
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
          <div>
            <button id="oa_setupsave" type="button" class="btn btn-primary" data-dismiss="modal" data-id="" action-id="">Save</button>
          </div>

            

        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-mbf" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Create Old Account</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <div class="col-sm-2">
                  
                </div>
                <div class="col-md-6">
                  <label>
                    Old Account from: <span id="mbf_sy" data-sy=""></span> <span id="mbf_sem" data-sem=""></span>
                </label>
                </div>
              </div>
              <div class="form-group row">
                <label for="particulars" class="col-sm-2 col-form-label">Student</label>
                <div class="col-md-10">
                  <select id="studlist" class="form-control select2bs4">
                    <option value="0"></option>
                    @php
                      $students = db::table('studinfo')
                        ->select(db::raw('id, sid, concat(lastname, ", ", firstname) as fullname, middlename'))
                        ->where('deleted', 0)
                        ->orderBy('lastname')
                        ->orderBy('firstname')
                        ->get();
                    @endphp

                    @foreach($students as $stud)
                      <option value="{{$stud->id}}">{{$stud->sid}} - {{$stud->fullname}} {{$stud->middlename}}</option>
                    @endforeach
                  </select> 
                </div>
              </div>
              <div class="form-group row">
                <label for="class-desc" class="col-sm-2 col-form-label">Amount</label>
                <div class="col-md-6">
                  <input type="number" class="form-control validation" id="fwdamount" placeholder="0.00">
                </div>
              </div>
            
              
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
          <div>
            <button id="modalfwdBal" type="button" class="btn btn-primary" data-dismiss="modal" data-id="" action-id="">Forward Balance</button>
          </div>

            

        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-ledger" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Student Ledger</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <span id="oa_studname" class="text-bold"></span>
            </div>
          </div>
        </div>
        <div class="modal-body" style="height: 380px; overflow-y: auto;">
          <div class="row">
            <div class="col-md-12 table-responsive p-0">
              <table class="table table-striped table-head-fixed table-sm text-sm">
                <thead>
                  <th>DATE</th>
                  <th>PARTICULARS</th>
                  <th class="text-right">CHARGES</th>
                  <th class="text-right">PAYMENT</th>
                  <th class="text-right">BALANCE</th>
                </thead>
                <tbody id="ledger-list">
                 
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <div class="">
         {{--    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
          </div>
          <div>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade" id="modal-old_add" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="margin-top: 110px;">
                <div id="modalhead" class="modal-header bg-success">
                    <h4 class="modal-title">Add Old Accounts</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <select id="old_add_studlist" class="select2bs4 old_req is-invalid" style="width: 100%;">
                                <option value="0">NAME</option>
                                {{-- @foreach(db::table('studinfo')->where('deleted', 0)->orderBy('lastname')->orderBy('firstname')->get() as $stud)
                                    <option value="{{$stud->id}}">
                                        {{$stud->sid . ' - ' . $stud->lastname . ', ' . $stud->firstname}}
                                    </option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            Level: <span id="old_add_levelname" class="text-bold"></span>
                        </div>
                        <div class="form-group col-md-6">
                            Section/Course: <span id="old_add_section" class="text-bold"></span>
                        </div>
                        <div class="form-group col-md-2 old_add_granteelabel" style="display: block;">
                            Grantee: <span id="old_add_grantee" class="text-bold"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <h6>Old Account Info</h6>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-8">
                            <div class="form-group col-md-12" style="display: block;">
                                Level|Section: <span id="old_info_level" class="text-bold"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Old Accounts from</label>            
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <select id="old_add_sy" class="select2bs4 w-100 old_req is-invalid" style="width: 100%;">
                                        <option value="0">SCHOOL YEAR</option>
                                        {{-- @foreach(db::table('sy')->orderBy('sydesc')->where('sydesc', '<', App\CashierModel::getSYDesc())->get() as $sy)
                                            <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <select id="old_add_sem" class="select2bs4 w-100 old_req is-invalid" style="width: 100%;">
                                        <option value="0">SEMESTER</option>
                                        {{-- @foreach(db::table('semester')->where('deleted', 0)->get() as $sem)
                                            <option value="{{$sem->id}}">{{$sem->semester}}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Amount</label>
                                    <input id="old_add_amount" type="number" class="form-control is-invalid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="old_post" type="button" class="btn btn-primary" disabled="">POST</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

  <div class="modal fade show" id="modal-lbf" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form action="{{route('fwdbalpdf')}}" method="GET">
          <div class="modal-header bg-info">
            <input type="hidden" name="hsy" id="hsy">
            <input type="hidden" name="hsem" id="hsem">
            <input type="hidden" name="hclassid" id="hclassid ">
            <h4 class="modal-title">List of Balance Forwarded</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                Forwarded to: <span id="modalfwdsy" class="text-bold"></span>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="table-responsive" style="height: 350px">
                  <table class="table table-striped p-0">
                    <thead class="bg-warning">
                      <th>Name</th>
                      <th>Amount</th>
                    </thead>
                    <tbody id="balList">
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <div class="">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            <div>
              <button class="btn btn-primary" id="btnprint" formtarget="_blank"><i class="fas fa-print"></i> Print</button>
            </div>      
          </div>
        </form>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade" id="modal-overlay" data-backdrop="static" aria-modal="true" style="display: none;">
      <div class="modal-dialog modal-sm">
          <div class="modal-content bg-gray-dark" style="opacity: 78%; margin-top: 15em">
              <div class="modal-body" style="height: 250px">
                  <div class="row">
                      <div class="col-md-12 text-center text-lg text-bold b-close">
                          Please Wait
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12">
                          <div class="loader"></div>
                      </div>
                  </div>
                  <div class="row" style="margin-top: -30px">
                      <div class="col-md-12 text-center text-lg text-bold">
                          Processing...
                      </div>
                  </div>
              </div>
          </div>
      </div> {{-- dialog --}}
  </div>
@endsection
@section('js')
  <style type="text/css">
    .pointer{
        cursor: pointer;
    }

    .loader{
        width: 100px;
        height: 100px;
        margin: 50px auto;
        position: relative;
    }
    .loader:before,
    .loader:after{
        content: "";
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: solid 8px transparent;
        position: absolute;
        -webkit-animation: loading-1 1.4s ease infinite;
        animation: loading-1 1.4s ease infinite;
    }
    .loader:before{
        border-top-color: #d72638;
        border-bottom-color: #07a7af;
    }
    .loader:after{
        border-left-color: #ffc914;
        border-right-color: #66dd71;
        -webkit-animation-delay: 0.7s;
        animation-delay: 0.7s;
    }
    @-webkit-keyframes loading-1{
        0%{
            -webkit-transform: rotate(0deg) scale(1);
            transform: rotate(0deg) scale(1);
        }
        50%{
            -webkit-transform: rotate(180deg) scale(0.5);
            transform: rotate(180deg) scale(0.5);
        }
        100%{
            -webkit-transform: rotate(360deg) scale(1);
            transform: rotate(360deg) scale(1);
        }
    }
    @keyframes loading-1{
        0%{
            -webkit-transform: rotate(0deg) scale(1);
            transform: rotate(0deg) scale(1);
        }
        50%{
            -webkit-transform: rotate(180deg) scale(0.5);
            transform: rotate(180deg) scale(0.5);
        }
        100%{
            -webkit-transform: rotate(360deg) scale(1);
            transform: rotate(360deg) scale(1);
        }
    }
</style>
  	<script type="text/javascript">
    	$(document).ready(function(){

			$('.select2bs4').select2({
				theme: 'bootstrap4'
			});

			$(window).resize(function(){
				screenadjust()    
			})

        	screenadjust()

			function screenadjust()
			{
				var screen_height = $(window).height();

				$('#main_table').css('height', screen_height - 315)
				// $('.screen-adj').css('height', screen_height - 223);
			}

			$('#mop').val('');
			$('#mop').trigger('change');

				// loadbalfwdsetup();
				// checksetup();
				// oa_load();

			function oa_load()
			{
				levelid = $('#oa_levelid').val();
				syid = $('#oa_syid').val();
				semid = $('#oa_semid').val();
				filter = $('#oa_filter').val();

				$.ajax({
				url: '{{route('oa_load')}}',
				type: 'GET',
				dataType: 'json',
				data: {
					levelid:levelid,
					syid:syid,
					semid:semid,
					filter:filter
				},
				success:function(data)
				{
					$('#balancelist').html(data.list);
				}
				});
			
			}

      		var forwardtype = 1;

			function forwardbalance(studid, studname, syid, semid, manualamount)
			{
				$.ajax({
				url:"{{route('fwdbal')}}",
				method:'GET',
				data:{
					studid:studid,
					syid:syid,
					semid:semid,
					manualamount:manualamount
				},
				dataType:'',
				success:function(data)
				{
					if($('#glevel').val() == 14 || $('#glevel').val() == 15)
					{
					loadstudbalance($('#glevel').val(), $('#sy').val(), $('#sem').val());  
					}
					else if($('#glevel').val() >= 17 && $('#glevel').val() <= 20)
					{
					loadstudbalance($('#glevel').val(), $('#sy').val(), $('#sem').val());   
					}
					else
					{
					loadstudbalance($('#glevel').val(), $('#sy').val(), 0);   
					}

					if(forwardtype == 1)
					{
					Swal.fire(
						'Success',
						'Balance successfully forwarded.',
						'success'
					);  
					}
					else
					{
					row_count += 1;
					console.log(row_count);
					if(row_count == length_count)
					{
						$('#modal-overlay').modal('hide');
					}
					}
				}
				});          
			}

			function loadstudbalance(levelid, syid, semid)
			{
				$.ajax({
				url:"{{route('studbal')}}",
				method:'GET',
				data:{
					levelid:levelid,
					syid:syid,
					semid:semid,
				},
				dataType:'json',
				success:function(data)
				{
					$('#balancelist').html(data.list);
					if($('#balancelist tr').length > 0)
					{
					$('#forward_all').show();
					$('.bal_count').text($('#balancelist tr').length)
					}
					else
					{
					$('#forward_all').hide();
					$('.bal_count').text(0);
					}
				}
				});
			}

			function loadbalfwdsetup()
			{
				$.ajax({
				url:"{{route('loadbalfwdsetup')}}",
				method:'GET',
				data:{
					
				},
				dataType:'json',
				success:function(data)
				{
					$('#fsy').val(data.syid);
					$('#fsem').val(data.semid);
					$('#fmop').val(data.mopid);
					$('#fclass').val(data.classid);

					$('.select2bs4').trigger('change');


				}
				});
			}

			function checksetup()
			{
				var syid = $('#fsy').val();
				var semid = $('#fsem').val();
				var mopid = $('#fmop').val();
				var classid = $('#fclass').val();



				$.ajax({
				url:"{{route('checkbalfwdsetup')}}",
				method:'GET',
				data:{
					syid:syid,
					semid:semid,
					mopid:mopid,
					classid:classid
				},
				dataType:'',
				success:function(data)
				{
					if(data == 0)
					{
					$('#savesetup').prop('disabled', true);
					}
					else
					{
					$('#savesetup').prop('disabled', false);
					}
				}
				});
			}

			$(document).on('change', '#glevel', function(){
				if($(this).val() == 14 || $(this).val() == 15)
				{
				$('.divsem').prop('hidden', false);
				// loadstudbalance(levelid, syid, 0);
				}
				else if($(this).val() >= 17 && $(this).val() <= 20)
				{
				$('.divsem').prop('hidden', false);
				}
				else
				{
				$('.divsem').prop('hidden', true); 
				// loadstudbalance(levelid, syid, sem);
				}
			})
      
			$(document).on('change', '.bal-field', function(){
				var levelid = $('#glevel').val();
				var syid = $('#sy').val();
				var semid = $('#sem').val();

				// console.log(levelid);

				if(levelid == 14 || levelid == 15)
				{
				loadstudbalance(levelid, syid, semid)  
				}
				else if(levelid >= 17 && levelid <= 20)
				{
				loadstudbalance(levelid, syid, semid)   
				}
				else
				{
				loadstudbalance(levelid, syid, 0)
				}
			});

			$(document).on('click', '#mbf', function(){
				$('#fwdamount').val('');
				$.ajax({
				url:"{{route('loadstud')}}",
				method:'GET',
				data:{
					
				},
				dataType:'json',
				success:function(data)
				{
					$('#studlist').html(data.list);
					$('#studlist').val('');
					$('studlist').trigger('change');
				}
				});
			}); 

			$(document).on('click', '#savesetup', function(){
				var syid = $('#fsy').val();
				var semid = $('#fsem').val();
				var mopid = $('#fmop').val();
				var classid = $('#fclass').val();
				$.ajax({
				url:"{{route('savefsetup')}}",
				method:'GET',
				data:{
					syid:syid,
					semid:semid,
					mopid:mopid,
					classid:classid
				},
				dataType:'',
				success:function(data)
				{
					checksetup();
					const Toast = Swal.mixin({
					toast: true,
					position: 'top',
					showConfirmButton: false,
					timer: 3000,
					timerProgressBar: true,
					onOpen: (toast) => {
						toast.addEventListener('mouseenter', Swal.stopTimer)
						toast.addEventListener('mouseleave', Swal.resumeTimer)
						}
					})

					Toast.fire({
						type: 'success',
						title: 'Setup saved.'
					}) 
				}
				});
			}); 

			$(document).on('change', '.setup', function(){
				checksetup();
			});

			$(document).on('click', '.bal-fwd', function(){
				var studname = $(this).attr('data-value');

				forwardtype = 1;

				Swal.fire({
				title: 'Balance forwarding.',
				text: "Forward balance of "+ studname +" to  SY: " + $('#fsy option:selected').text() + ' - ' + $('#fsem option:selected').text(),
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes!'
				}).then((result) => {
				if (result.value) {
					forwardbalance($(this).attr('data-id'), studname, $('#sy').val(), $('#sem').val(), 0)  
				}
				})
			});

			$(document).on('click', '.v-ledger', function(){
				var studid = $(this).attr('data-id');
				var syid = $('#oa_syid').val();
				var semid = $('#oa_semid').val();

				$.ajax({
				url:"{{route('fwdVledger')}}",
				method:'GET',
				data:{
					studid:studid,
					syid:syid,
					semid:semid,
				},
				// dataType:'json',
				success:function(data)
				{
					console.log('studname: ' + data.studname)

					$('#oa_studname').text(data.studname);
					$('#ledger-list').html(data.list);
					$('#modal-ledger').modal('show');
				}
				});

			});

			$(document).on('click', '#modalfwdBal', function(){

				var studid = $(this).attr('data-id');
				var syfrom = $('#oa_syid').val();
				var semfrom = $('#oa_semid').val();
				var amount = $('#fwdamount').val();

				Swal.fire({
				title: studname,
				text: "Forward balance to " + sydesc,
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Forward Balance'
				}).then((result) => {
				if (result.value == true) {
					$.ajax({
					url: '{{route('oa_forward')}}',
					type: 'GET',
					data: {
						studid:studid,
						syfrom:syfrom,
						semfrom:semfrom,
						amount:amount
					},
					success:function(data)
					{
						if(data == 'done')
						{
						oa_load();
						const Toast = Swal.mixin({
							toast: true,
							position: 'top',
							showConfirmButton: false,
							timer: 3000,
							timerProgressBar: true,
							didOpen: (toast) => {
							toast.addEventListener('mouseenter', Swal.stopTimer)
							toast.addEventListener('mouseleave', Swal.resumeTimer)
							}
						})

						Toast.fire({
							type: 'success',
							title: 'Forward successfully'
						})
						}
						else if(data == 'error')
						{
						const Toast = Swal.mixin({
							toast: true,
							position: 'top',
							showConfirmButton: false,
							timer: 4000,
							timerProgressBar: true,
							didOpen: (toast) => {
							toast.addEventListener('mouseenter', Swal.stopTimer)
							toast.addEventListener('mouseleave', Swal.resumeTimer)
							}
						})

						Toast.fire({
							type: 'error',
							title: 'Something went wrong. Please Check Old Account Setup'
						})
						}
						else
						{
						const Toast = Swal.mixin({
							toast: true,
							position: 'top',
							showConfirmButton: false,
							timer: 3000,
							timerProgressBar: true,
							didOpen: (toast) => {
							toast.addEventListener('mouseenter', Swal.stopTimer)
							toast.addEventListener('mouseleave', Swal.resumeTimer)
							}
						})

						Toast.fire({
							type: 'error',
							title: 'Old accounts already forwarded'
						})
						}
					}
					});
				}
				})
			});

			$(document).on('click', '#listFwdBal', function(){
			
				$('#modalfwdsy').text('SY ' + $('#fsy option:selected').text())
				$('#hsy').val($('#fsy').val())
				$('#hsem').val($('#fsem').val())
				$('#hclassid').val($('#fclass').val())

				$.ajax({
				url:"{{route('listfwdbal')}}",
				method:'GET',
				data:{
					
				},
				dataType:'json',
				success:function(data)
				{
					$('#balList').html(data.list);
				}
				});
			});

			$(document).on('change', '.bal-field', function(){
				if($('#sy').val() != 0)
				{
				// $('#mbf').prop('disabled', false);
				if($('#glevel').val() == 14 || $('#glevel').val() == 15)
				{
					// console.log($('#sem').val() + '!='+ $('#fsem()').val());
					if($('#sy').val() != 0)
					{
					if($('#sem').val() != 0)
					{
						if($('#sy').val() == $('#fsy').val())
						{
						if($('#sem').val() == $('#fsem').val())
						{
							$('#mbf').prop('disabled', true);
						}
						else
						{
							$('#mbf').prop('disabled', false);
						}
						}
						else
						{
						$('#mbf').prop('disabled', false);
						}
					}
					else
					{
						$('#mbf').prop('disabled', true);
					}
					}
					else
					{
					$('#mbf').prop('disabled', true);
					}
				}
				else
				{
					if($('#sy').val() == $('#fsy').val())
					{
					$('#mbf').prop('disabled', true);
					}
					else
					{
					$('#mbf').prop('disabled', false); 
					}
				}
				}
				else
				{
				$('#mbf').prop('disabled', true);
				}
			});

			var length_count = 0;
			var row_count = 0;

			$(document).on('click', '#forward_all', function(){
				forwardtype = 2;
				row_count = 0;
				length_count = $('#balancelist tr').length;

				Swal.fire({
					title: 'Balance forwarding',
					text: "Forward all?",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes!'
				}).then((result) => {
					if (result.value) {
					$('#modal-overlay').modal('show');
					$('#balancelist tr').each(function(){
						var studid = $(this).find('.bal-fwd').attr('data-id');
						var studname = $(this).find('.bal-fwd').attr('data-value');

						forwardbalance(studid, studname, $('#sy').val(), $('#sem').val(), 0)  
						// return false;
					})  
					}
				});
			});

			syactive = 0;
			semactive = 0;
			shssetup = 0;

			$(document).on('select2:close', '#oa_levelid', function(){
				var levelid = $(this).val();

				$.ajax({
				url: '{{route('oa_loadsy')}}',
				type: 'GET',
				dataType: 'json',
				data: {
					levelid:levelid
				},
				success:function(data)
				{
					syactive = data.syactive;
					semactive = data.semactive;
					shssetup = data.shssetup;
					$('#oa_syid').html(data.sylist);
					$('#oa_syid').trigger('select2:close');

					if(levelid == 14 || levelid == 15)
					{
					if($shssetup == 0)
					{
						$('#oa_txtsem').show();
					}
					else
					{
						$('#oa_txtsem').hide(); 
					}
					}
					else if(levelid >= 17 && levelid <= 21)
					{
					$('#oa_txtsem').show();
					}
					else
					{
					$('#oa_txtsem').hide();
					}
				}
				});  
			});

			$(document).on('select2:close', '#oa_syid', function(){
				var levelid = $('#oa_levelid').val();
				var syid = $(this).val();

				$('#oa_semid').empty();

				if(levelid == 14 || levelid == 15)
				{
				if(shssetup == 0)
				{
					if(syid == syactive)
					{
					if(semactive == 2)
					{
						$('#oa_semid').append(
							`
							<option value="1">1st Semester</option>
							`
						);
					}  
					}
					else
					{
					$('#oa_semid').append(
						`
							<option value="1">1st Semester</option>
							<option value="2">2nd Semester</option>
							<option value="3">Summer</option>
						`
						); 
					}
				}
				else
				{

				}
				}
				else if(levelid >= 17 && levelid <= 20 )
				{
				if(syid == syactive)
				{
					console.log(semactive)
					if(semactive == 2)
					{
					$('#oa_semid').append(
						`
							<option value="1">1st Semester</option>
						`
						);
					}  
				}
				else
				{
					$('#oa_semid').append(
						`
							<option value="1">1st Semester</option>
							<option value="2">2nd Semester</option>
							<option value="3">Summer</option>
						`
						);
				}
				}
			});

			$(document).on('click', '#oa_search', function(){
				oa_load();
			});

			$(document).on('click', '.oa_forward', function(){
				var studid = $(this).attr('data-id');
				var syfrom = $('#oa_syid').val();
				var semfrom = $('#oa_semid').val();
				var amount = $(this).attr('data-amount');

				var studname = $(this).closest('tr').find('.fullname').text();
				var studinfo = $(this).find('.fullname').text();
				var sydesc = "{{App\FinanceModel::getSYDesc()}}";


				// alert(studname);

				Swal.fire({
				title: studname,
				text: "Forward balance to " + sydesc,
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Forward Balance'
				}).then((result) => {
				if (result.value == true) {
					$.ajax({
					url: '{{route('oa_forward')}}',
					type: 'GET',
					data: {
						studid:studid,
						syfrom:syfrom,
						semfrom:semfrom,
						amount:amount
					},
					success:function(data)
					{
						if(data == 'done')
						{
							oa_load();
							const Toast = Swal.mixin({
								toast: true,
								position: 'top',
								showConfirmButton: false,
								timer: 3000,
								timerProgressBar: true,
								didOpen: (toast) => {
								toast.addEventListener('mouseenter', Swal.stopTimer)
								toast.addEventListener('mouseleave', Swal.resumeTimer)
								}
							})

							Toast.fire({
								type: 'success',
								title: 'Forward successfully'
							})
						}
						else if(data == 'error')
						{
						const Toast = Swal.mixin({
							toast: true,
							position: 'top',
							showConfirmButton: false,
							timer: 4000,
							timerProgressBar: true,
							didOpen: (toast) => {
							toast.addEventListener('mouseenter', Swal.stopTimer)
							toast.addEventListener('mouseleave', Swal.resumeTimer)
							}
						})

						Toast.fire({
							type: 'error',
							title: 'Something went wrong. Please Check Old Account Setup'
						})
						}
						else
						{
						const Toast = Swal.mixin({
							toast: true,
							position: 'top',
							showConfirmButton: false,
							timer: 3000,
							timerProgressBar: true,
							didOpen: (toast) => {
							toast.addEventListener('mouseenter', Swal.stopTimer)
							toast.addEventListener('mouseleave', Swal.resumeTimer)
							}
						})

						Toast.fire({
							type: 'error',
							title: 'Old accounts already forwarded'
						})
						}
					}
					});
				}
				})
			});

			$(document).on('click', '#oa_setup', function(){
				$.ajax({
				url: '{{route('oa_setup')}}',
				type: 'GET',
				success:function(data)
				{
					$('#oa_setupclassid').val(data.classid);
					$('#oa_setupclassid').trigger('change');
					$('#oa_setupmop').val(data.mopid);
					$('#oa_setupmop').trigger('change');

					if(data.classified == 0)
					{
						$('#oa_setupclassified').prop('checked', false)
					}
					else{
						$('#oa_setupclassified').prop('checked', true)
					}

					$('#modal-setup').modal('show');
				}
				});
			})

			$(document).on('click', '#oa_setupsave', function(){
				var classid = $('#oa_setupclassid').val();
				var mop = $('#oa_setupmop').val();
				
				if($('#oa_setupclassified').prop('checked') == true)
				{
					var classified = 1
				}
				else{
					var classified = 0
				}

				$.ajax({
				url: '{{route('oa_setupsave')}}',
				type: 'GET',
				data: {
					classid:classid,
					mop:mop,
					classified:classified
				},
				success:function(data)
				{
					const Toast = Swal.mixin({
					toast: true,
					position: 'top',
					showConfirmButton: false,
					timer: 3000,
					timerProgressBar: true,
					didOpen: (toast) => {
						toast.addEventListener('mouseenter', Swal.stopTimer)
						toast.addEventListener('mouseleave', Swal.resumeTimer)
					}
					})

					Toast.fire({
					type: 'success',
					title: 'Successfully saved.'
					})

					$('#modal-setup').modal('hide');
				}
				});
			});

			$(document).on('click', '#oa_createoldacc', function(){
				old_add_clearinputs();
				$('#modal-old_add').modal('show');
			});


				//-====---------=====-----------------==========-----------------------

			$(document).on('click', '#old_add', function(){
			
			});

			function old_add_clearinputs()
			{
				$('#old_add_studlist').val(0);
				$('#old_add_studlist').trigger('change');
				$('#old_add_sy').val(0);
				$('#old_add_sy').trigger('change');
				$('#old_add_sem').val(0);
				$('#old_add_sem').trigger('change');
				$('#old_add_amount').val('');
				$('#old_add_amount').removeClass('is-valid');
				$('#old_add_amount').addClass('is-invalid');
			}

			function old_generate()
			{
				var syid = $('#old_sy').val();
				var semid = $('#old_sem').val();
				var levelid = $('#old_gradelevel').val();

				$.ajax({
					url: '{{route('old_load')}}',
					type: 'GET',
					dataType: 'json',
					data: {
						syid:syid,
						semid:semid,
						levelid:levelid
					},
					success:function(data)
					{
						$('#old_list').html(data.list);
					}
				});
				
			}  

			$(document).on('click', '#old_generate', function(){
				old_generate();
			});

			$(document).on('change', '#old_add_studlist', function(){
				var studid = $(this).val();
				console.log('aaa');
				$.ajax({
					url: '{{route('old_add_studlist')}}',
					type: 'GET',
					dataType: 'json',
					data: {
						studid:studid
					},
					success:function(data)
					{
						if(studid > 0)
						{
							$('#old_add_levelname').text(data.levelname);
							$('#old_add_section').text(data.section);
							$('#old_add_grantee').text(data.grantee);
							$('#old_add_sy').html(data.sylist);
							$('#old_add_sem').html(data.semlist);

							console.log(data.levelid);

							if(data.levelid >= 17 && data.levelid <= 21)
							{
								$('.old_add_granteelabel').hide();
							}
							else
							{
								$('.old_add_granteelabel').show();
							}
						}
						else
						{
							$('#old_add_studlist').html(data.studlist);
						}
					}
				});
				
			});

			var valcount = 0;
			$(document).on('change', '.old_req', function(){
				if($(this).val() > 0)
				{
					$(this).removeClass('is-invalid');
					$(this).addClass('is-valid');
				}
				else
				{
					$(this).addClass('is-invalid');
					$(this).removeClass('is-valid');   
				}

				checkreq();
			});

			$(document).on('keyup', '#old_add_amount', function(){
				if($(this).val() != '')
				{
					$(this).removeClass('is-invalid');
					$(this).addClass('is-valid');
				}
				else
				{
					$(this).addClass('is-invalid');
					$(this).removeClass('is-valid');   
				}

				checkreq();
			});

			$(document).on('change', '#old_add_amount', function(){
				if($(this).val() < 0)
				{
					$(this).val(0)
				}
			})

			function checkreq()
			{
				thiscount = 0;

				$('.old_req').each(function(){
					if($(this).hasClass('is-invalid'))
					{
						thiscount = 0;
					}
					else
					{
						thiscount += 1;
					}
				}); 

				if(thiscount == 3 && $('#old_add_amount').hasClass('is-valid'))
				{
					$('#old_post').attr('disabled', false);
				}
				else
				{
					$('#old_post').attr('disabled', true);
				}
			}

			$(document).on('click', '#old_post', function(){
				var studid = $('#old_add_studlist').val();
				var syfrom = $('#old_add_sy').val();
				var semfrom = $('#old_add_sem').val();
				var amount = $('#old_add_amount').val();
				var action = 'create'

				$.ajax({
					url: '{{route('oa_forward')}}',
					type: 'GET',
					dataType: '',
					data: {
						studid:studid,
						syfrom:syfrom,
						semfrom:semfrom,
						amount:amount,
						action:action
					},
					success:function(data)
					{
						if(data == 'done')
						{
						oa_load();
						const Toast = Swal.mixin({
							toast: true,
							position: 'top',
							showConfirmButton: false,
							timer: 3000,
							timerProgressBar: true,
							didOpen: (toast) => {
							toast.addEventListener('mouseenter', Swal.stopTimer)
							toast.addEventListener('mouseleave', Swal.resumeTimer)
							}
						})

						Toast.fire({
							type: 'success',
							title: 'Forward successfully'
						})

						$('#modal-old_add').modal('hide');
						}
						else if(data == 'error')
						{
						const Toast = Swal.mixin({
							toast: true,
							position: 'top',
							showConfirmButton: false,
							timer: 4000,
							timerProgressBar: true,
							didOpen: (toast) => {
							toast.addEventListener('mouseenter', Swal.stopTimer)
							toast.addEventListener('mouseleave', Swal.resumeTimer)
							}
						})

						Toast.fire({
							type: 'error',
							title: 'Something went wrong. Please Check Old Account Setup'
						})
						}
						else
						{
						const Toast = Swal.mixin({
							toast: true,
							position: 'top',
							showConfirmButton: false,
							timer: 3000,
							timerProgressBar: true,
							didOpen: (toast) => {
							toast.addEventListener('mouseenter', Swal.stopTimer)
							toast.addEventListener('mouseleave', Swal.resumeTimer)
							}
						})

						Toast.fire({
							type: 'error',
							title: 'Old accounts already forwarded'
						})
						}
					}
				});

			});

			function old_getsem()
			{
				var syid = $('#old_add_sy').val();

				$.ajax({
					url: '{{route('old_getsem')}}',
					type: 'GET',
					data: {
						syid:syid
					},
					success:function(data)
					{
						$('#old_add_sem').html(data);
					}
				});
				
			}

			$(document).on('change', '#old_add_sy', function(){
				old_getsem();
			});
    	});
  	</script>
@endsection
