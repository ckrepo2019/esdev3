@extends('finance.sa.layouts.app')

@section('content')
	<style>
  .studledger {
    font-size: 14px;
  }
  .card-body {
    /* margin:auto; */
    text-align: center;
  }
  .card-body .btn-app{
    width: 22%;
    height: 100px;
    border: none;
    padding-top:20px;
    transition: .3s;
    background-color: transparent!important;
  }
  .card-body .btn-app .fas{
    font-size: 22px;
    color: #ffc107;
    transition: .3s;
    -webkit-text-stroke: .1px #0d4019;
  }
  .card-body .btn-app:hover {
    background-color: #81f99c52;
    transition: .3s;
  }
  .card-body .btn-app:hover .fas {
    font-size: 40px;
    transition: .3s;
    /* color: green; */
  }
  .card-body .btn-app span{
    color: #fff;
    font-size: 13px;
    font-weight: 600;
  }
  .btn-app:hover{
    background-color: yellow;
  }
  </style>
  <section class="content">
  <div class="container-fluid">
 
	<div class="row">

		<div class="col-lg-12">
			<div class="col-md-12 title">
				<h1>STUDENTS ACCOUNTS</h1>
			</div>

		<!--  -->

			<div class="col-md-12">
				<div class="card bg-primary">
					<div class="card-header bg-primary">
						<h3 class="card-title"><i style="color: #ffc107" class="fas fa-coins"></i> <b>Student Ledger</b></h3>
					</div>
					<div class="card-body bg-light" style="overflow-y: auto">
						<div class="row form-group">
							<div class="col-md-6">
								<select id="studName" name="studid" class="text-secondary form-control select2 updq" value="">
								</select>
							</div>
							<div class="col-md-3">
								<select id="sa_sy"  class="text-secondary form-control select2 updq" value="">
									@foreach(db::table('sy')->orderBy('sydesc')->get() as $sy)
										@if($sy->isactive == 1)
											<option value="{{$sy->id}}" selected>{{$sy->sydesc}}</option>
										@else
											<option value="{{$sy->id}}">{{$sy->sydesc}}</option>
										@endif
									@endforeach
								</select>
							</div>
							<div class="col-md-3">
								<select id="sa_sem" name="studid" class="text-secondary form-control select2 updq" value="">
									@foreach(db::table('semester')->get() as $sem)
										@if($sy->isactive == 1)
											<option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
										@else
											<option value="{{$sem->id}}">{{$sem->semester}}</option>
										@endif
									@endforeach
								</select>
							</div>
						</div>  
						<div class="row form-group">
							<div class="col-md-10 text-left">
								<span id="ledger_info">LEVEL|SECTION: </span>
							</div>
							<div class="col-md-1">
								<button id="sa_ledgerpint" class="btn btn-primary btn-sm text-sm btn-block"><i class="fas fa-print"></i> PRINT</button>
							</div>
							<div class="col-md-1">
								<button id="sa_soaprint" class="btn btn-info btn-block btn-sm text-sm"><i class="fas fa-print"></i> SOA</button>
							</div>
						</div>
						<div class="row form-group">
							<div id="sa_tablemain" class="col-md-12 table-responsive">
								<table class="table table-sm table-striped text-sm">
									<thead>
										<tr>
											<th>DATE</th>
											<th>PARTICULARS</th>
											<th class="text-center">CHARGES</th>
											<th class="text-center">PAYMENT</th>
											<th class="text-center">BALANCE</th>
										</tr>
									</thead>
									<tbody id="sa_ledgerlist" class="text-left"></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
  	
</section>

@endsection
@section('modal')
<div class="modal fade show" id="modal-soa" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h4 class="modal-title">STATEMENT OF ACCOUNT</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="" class="row">
					<div class="col-md-2">
						<label>MONTH</label>
					</div>
					<div class="col-md-10">
						<select id="sa_soamonth" class="select2 form-control">
							@foreach(db::table('monthsetup')->get() as $month)
								<option value="{{$month->id}}">{{$month->description}}</option>
							@endforeach
						</select>
					</div>
				</div>  
			</div>
			<div class="modal-footer ">
			<button id="" type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
			<button id="sa_soapdf" type="button" class="btn btn-primary">PRINT</button>
			</div>
		</div>
		</div> {{-- dialog --}}
</div>
@endsection
  @section('js')
    <script>
      $(document).ready(function(){
		    actvglvlload()

		$('.select2').select2({
			theme: 'bootstrap4'
		});

		screenadjust();
        function screenadjust()
        {
            var screen_height = $(window).height() - 384;
            $('#sa_tablemain').css('height', screen_height);
        }

        function actvglvlload()
        {
          	var syid = $('#sy').val()
          	var semid = $('#sem').val()
          	$.ajax({
				type: "GET",
				url: "{{route('actvglvlload')}}",
				data: {
					syid:syid,
					semid:semid
				},
				// dataType: "dataType",
				success: function (data) {
					$('#levellist').html(data)
				}
          })
        }

		function loadStudents()
		{
			$.ajax({
				type: "GET",
				url: "{{route('loadStudents')}}",
				success: function (data) {
					$('#studName').append(`
						<option value="0">SELECT STUDENT</option>
					`)
					$.each(data, function (index, value) { 
						var middlename = (value.middlename == null) ? '':value.middlename
						var suffix = (value.suffix == null) ? '':value.suffix

						 $('#studName').append(`
						 	<option value="`+value.id+`">`+value.sid+` - `+value.lastname+`, `+value.firstname+` `+middlename+` `+suffix+`</option>
						 `)
					});
				}
			});
		}

		loadStudents()

		$(document).on('change', '.updq', function(){
			var syid = $('#sa_sy').val();
			var semid = $('#sa_sem').val(); 
			var studid = $('#studName').val();
			var batchid = $('#tvlbatch').val();

			$('#studid').val(studid);
			$('#syid').val(syid);

			// console.log(studid);
			$.ajax({
				url:"{{route('getStudLedger')}}",
				method:'GET',
				data:{
					syid:syid,
					studid:studid,
					semid:semid,
					batchid:batchid
				},
				dataType:'json',
				success:function(data)
				{
					$('#sa_ledgerlist').html(data.list);
					
					$('#btnstudyload').attr('data-level', data.levelid);
					$('#btnstudyload').attr('data-status', data.studstatus);
					
					if(data.levelid >= 17 && data.levelid <= 21)
					{
						$('.div_studyload').show();
					}
					else
					{
						$('.div_studyload').hide();
					}
					
					if(data.istvl == 1)
					{
						$('.filters').hide();
						$('.tv_filters').show();
					}
					else
					{
						$('.filters').show();
						$('.tv_filters').hide(); 
					}

					if(data.levelid == 14 || data.levelid == 15)
					{
						$('#ledger_info').text('Grade Level|Section: ' + data.levelname + ' - ' + data.section +' | '+ data.strand +' | '+ data.grantee + ' | ' + data.studstatus);
					}
					else if(data.levelid >=17 && data.levelid <= 21)
					{
						$('#ledger_info').text('Grade Level|Section: ' + data.levelname +' '+ data.section + ' | ' + data.studstatus);
					}
					else
					{
						$('#ledger_info').text('Grade Level|Section: ' + data.levelname +' '+data.section +' | ' + data.grantee + ' | ' + data.studstatus);
					}

					// $('#ledger_info_status').text('Status: ' + );
					$('#feesname').text(data.feesname);
					$('#feesname').attr('data-id', data.feesid);
					
				}
			}); 
		});

		$(document).on('click', '#sa_ledgerpint', function(){
			if($('#studName').val() > 0)
			{
				var syid = $('#sa_sy').val();
				var semid = $('#sa_sem').val();
				var studid = $('#studName').val();

				// window.open('/finance/pdfledger/' + studid + '/' + syid + '/' + semid , '_blank');
				window.open('/finance/pdfledger?studid=' + studid + '&syid=' + syid + '&semid='+ semid,'_blank');
			}
			else{
				const Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
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
					title: 'Please select a student'
				})
			}
		})

		
		$(document).on('click', '#sa_soapdf', function(){
			if($('#studName').val() > 0)
			{
				var selectedschoolyear = $('#sa_sy').val();
				var selectedsemester = $('#sa_sem').val();
				var selectedmonth = $('#sa_soamonth').val();
				var studid = $('#studName').val();
				var exporttype = 'print'
				var paramet = {
					selectedschoolyear  : selectedschoolyear,
					selectedsemester    : selectedsemester, 
					selectedmonth       : selectedmonth, 
					studid              : studid
				}

				$('#modal-soa').modal('hide')

				window.open("/statementofacctgetaccount?exporttype="+exporttype+"&"+$.param(paramet));
			}
			else{
				const Toast = Swal.mixin({
					toast: true,
					position: 'top-end',
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
					title: 'Please select a student'
				})
			}
		})

		$(document).on('click', '#sa_soaprint', function(){
			$('#modal-soa').modal('show')
		})

    });

    </script>
  @endsection