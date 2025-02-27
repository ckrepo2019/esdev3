@extends('finance.layouts.app')

@section('content')
  <section class="content">
  			
        <div class="row mb-2 ml-2">
			<div class="col-md-6"><h1 class="m-0 text-dark">Discounts</h1></div>
			<div class="col-md-6 text-right text-xl" >
				<button id="setup" class="btn btn-lg  btn-info" data-toggle="tooltip" title="Discount Setup">
					<i class="fas fa-cogs"></i>
				</button>	
			</div>
        </div>
		<div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        
                    </div>
                    <div class="card-body">
						<div class="row form-group">
							<div class="col-md-2">
								<select id="filter_sy" class="select2 w-100">
									@foreach(db::table('sy')->orderBy('sydesc')->get() as $sy)
										@if($sy->isactive == 1)
											<option value="{{$sy->id}}" selected>{{$sy->sydesc}}</option>
										@else
											<option value="{{$sy->id}}">{{$sy->sydesc}}</option>
										@endif
									@endforeach
								</select>
							</div>
							<div class="col-md-2">
								<select id="filter_sem" class="select2 w-100">
									<option value="0">All - Semester</option>
									@foreach(db::table('semester')->get() as $sem)
										@if($sem->isactive == 1)
											<option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
										@else
											<option value="{{$sem->id}}">{{$sem->semester}}</option>
										@endif
									@endforeach
								</select>
							</div>
							<div class="col-md-3">
								<div class="input-group mb-3">
									<input id="filter_search" class="form-control">
									<div class="input-group-append">
									<span class="input-group-text"><i class="fas fa-search"></i></span>
									</div>
									</div>
							</div>
							<div class="col-md-2"></div>
							<div class="col-md-3">
								<button class="btn btn-primary text-sm btn-block" id="discount_create">Add Student to Avail Discount</button>
							</div>
						</div>
						<div class="row">
							<div id="main_table" class="col-md-12 table-responsive p-0">
								<table id="class_list" class="table table-hover table-head-fixed table-sm text-sm">
									<thead class="bg-warning p-0">
									<tr>
										<th>NAME</th>
										<th>PARTICULARS</th>
										<th>DISCOUNT</th>
									</tr>
									</thead> 
									<tbody id="discount_body" style="cursor: pointer;"></tbody>             
								</table>
							</div>
						</div>
                    </div>
                </div>
            </div>          
        </div>
  </section>
@endsection

@section('modal')
    <div class="modal fade show" id="modal-setup" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                <h4 class="modal-title">Discount Setup</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div id="setup_table" class="modal-body table-responsive">
                    <div class="row form-group">
						<div class="col-md-9"></div>
						<div class="col-md-3">
							<button id="setup_create" class="btn btn-primary">
								Create Discount Setup
							</button>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table class="table table-hover table-sm text-sm">
								<thead>
									<tr>
										<th>Discount Name</th>
										<th>Discount Amount/Percentage</th>
									</tr>
								</thead>
								<tbody id="setuplist" style="cursor: pointer"></tbody>
							</table>
						</div>
					</div>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="class_delete" type="button" class="btn btn-danger" style="display: none">Delete</button>
                    <button id="class_save" type="button" class="btn btn-primary" data-id="0">Save</button>
                </div> --}}
            </div>
        </div> {{-- dialog --}}
    </div>

	<div class="modal fade show" id="modal-setupdetail" aria-modal="true" style="padding-right: 17px; display: none;">
		<div class="modal-dialog modal-md">
		  	<div class="modal-content" style="margin-top: 9em">
				<div class="modal-header bg-info">
					<h4 class="modal-title">Setup - <span id="stat"></span></h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
			  		<form class="form-horizontal">
						<div class="card-body">
				  			<div class="form-group row">
								<label for="particulars" class="col-sm-3 col-form-label">Particulars</label>
								<div class="col-md-9">
					  				<input type="text" class="form-control validation" id="particulars" placeholder="Particulars" onkeyup="this.value = this.value.toUpperCase();">
								</div>
				  			</div>
				  			<div class="form-group row">
								<label for="class-desc" class="col-sm-3 col-form-label" id="percent_label">Percent</label>
								<div class="col-md-6">
					  				<input type="number" class="form-control validation" id="txtpercent" placeholder="Amount" value="0">
								</div>
								<div class="icheck-primary d-inline">
					  				<input type="checkbox" id="chkpercent" checked="">
					  				<label for="chkpercent" id="">
										Percent
					  				</label>
								</div>
				  			</div>
						</div>
			  		</form>
				</div>
				<div class="modal-footer justify-content-between">
			  		<div class="">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  		</div>
			  		<div>
						<button id="deleteDiscount" type="button" class="btn btn-danger"  action-id="">Delete</button>
						<button id="saveDiscount" type="button" class="btn btn-primary" data-id="" action-id="">Save</button>
			  		</div>
				</div>
		  	</div>
		</div> {{-- dialog --}}
	</div>

	<div class="modal fade show" id="modal-discount" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info">
                <h4 class="modal-title">Add Student to Avail Discount</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div id="setup_table" class="modal-body">
                    <div class="row form-group">
						<div class="col-md-6">
							<select id="discount_students" class="select2 w-100"></select>
						</div>
						<div class="col-md-3">
							<select id="discount_sy" class="select2 w-100 discount_sysem">
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
							<select id="discount_sem" class="select2 w-100 discount_sysem">
								@foreach(db::table('semester')->get() as $sem)
									@if($sem->isactive == 1)
										<option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
									@else
										<option value="{{$sem->id}}">{{$sem->semester}}</option>
									@endif
								@endforeach
							</select>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-6">
							<select id="discount_setup" class="select2 w-100">
								<option value="0">SELECT DISCOUNT</option>
								{{-- @foreach(db::table('discounts')->where('deleted', 0)->get() as $disc)
									<option value="{{$disc->id}}">{{$disc->particulars}}</option>
								@endforeach --}}
							</select>
						</div>
					</div>
					<div class="row">
						<div class="cold-md-12 table-responsive">
							<table cellspacing="0" cellpadding="0" class="table table-stripe table-sm">
								<thead>
									<tr>
										<th></th>
										<th>Particulars</th>
										<th>Amount</th>
										<th style="width: 150px">Discount</th>
										<th class="text-center">Discounted Amount</th>
									</tr>
								</thead>
								<tbody id="discount_data"></tbody>
								<tfoot>
									<tr>
										<th colspan="2" class="text-right">TOTAL:</th>
										<th class="text-right" id="discount_totalamount"></th>
										<th colspan="2" class="text-right" id="discount_totaldiscamount"></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="class_delete" type="button" class="btn btn-danger" style="display: none">Delete</button>
                    <button id="discount_post" type="button" class="btn btn-primary" data-id="0">Post</button>
                </div>
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

@section('jsUP')
	<style>
		input[type=checkbox] {
			transform: scale(1.5);
			
			
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

	
@endsection

@section('js')
  
  <script type="text/javascript">
    
    $(document).ready(function(){
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        $(window).resize(function(){
            screenadjust()    
        })

        screenadjust()

        function screenadjust()
        {
            var screen_height = $(window).height();

            $('#main_table').css('height', screen_height - 302)
			$('#setup_table').css('height', screen_height - 150)
            // $('.screen-adj').css('height', screen_height - 223);
        }

		// $('.numberonly').keypress(function (e) {    
		$(document).on('keypress', '.discount_amount', function(e){
			var charCode = (e.which) ? e.which : event.keyCode    

			if (String.fromCharCode(charCode).match(/[^0-9%.]/g))
			{
				return false;
			}

		});

		$(document).on('keypress', '#txtpercent', function(e){
			var charCode = (e.which) ? e.which : event.keyCode    

			if (String.fromCharCode(charCode).match(/[^0-9.]/g))
			{
				return false;
			}
		})

		function getStudents()
		{
			var syid = $('#discount_sy').val()
			var semid = $('#discount_sem').val()

			$.ajax({
				type: "GET",
				url: "{{route('discount_getstudents')}}",
				data: {
					syid:syid,
					semid:semid
				},
				// dataType: "dataType",
				success: function (data) {
					$('#discount_students').empty()
					$('#discount_students').append(`
						<option value="0">SELECT STUDENT</option>
					`)
					// $('#modal-discount').modal({backdrop: 'static'}, 'show')
					$('#discount_students').select2({
						data:data,
						theme: 'bootstrap4'
					})
				}
			});
		}

		getStudents()
		getDiscounts()

		function getDiscounts()
		{
			var syid = $('#filter_sy').val()
			var semid = $('#filter_sem').val()
			var filter = $('#filter_search').val()

			$.ajax({
				type: "GET",
				url: "{{route('discount_getdiscount')}}",
				data: {
					syid:syid,
					semid:semid,
					filter:filter
				},
				// dataType: "dataType",
				success: function (data) {
					$('#discount_body').empty()
					$.each(data, function (index, val) { 
						 $('#discount_body').append(`
						 	<tr data-id="`+val.studdiscountid+`" group-id="`+val.groupid+`">
								<td>`+val.fullname+`</td>
								<td>`+val.particulars+`</td>
								<td>`+val.discount+`</td>
							</tr>
						 `)
					});
				}
			});
		}

		function GetDiscountSetup()
		{
			$.ajax({
				type: "GET",
				url: "{{route('discount_setup')}}",
				// data: "data",
				// dataType: "dataType",
				success: function (data) {
					$('#setuplist').empty()
					$.each(data, function (key, value) { 
						if(value.percent == 1)
						{
							$('#setuplist').append(`
								<tr data-id="`+value.id+`">
									<td>`+value.particulars+`</td>
									<td> `+value.amount+` %</td>
								</tr>
							`)
						}
						else{
							$('#setuplist').append(`
								<tr data-id="`+value.id+`">
									<td>`+value.particulars+`</td>
									<td> &#8369 `+value.amount+`</td>
								</tr>
							`)
						}
					})

					
				}
			});
		}

		function discount_charges()
		{
			var syid = $('#discount_sy').val()
			var semid = $('#discount_sem').val()
			var studid = $('#discount_students').val()

			// console.log('groups: ' + groups[0].classname)

			$.ajax({
				type: "GET",
				url: "{{route('discount_charges')}}",
				data: {
					syid:syid,
					semid:semid,
					studid:studid
				},
				// dataType: "dataType",
				success: function (data) {
					$('#discount_data').empty()

					var totalcharge = 0;

					$.each(data, function (index, val) { 

						$('#discount_data').append(`
						 	<tr data-id="`+val.id+`" class-id="`+val.classid+`">
								<td class="text-center"><input type="checkbox" class="discount_include" id="`+val.id+`"></td>
								<td><label for="`+val.id+`">`+val.particulars+`</label></td>
								<td class="text-right discount_balance">`+val.balance+`</td>
								<td>
									<input type="text" class="discount_amount form-control">
								</td>
								<td class="text-right discount_discamount">0.00</td>
							</tr>
						`)

						totalcharge += parseFloat(val.balance.replace(',', ''))
					});

					$('#discount_totalamount').text(parseFloat(totalcharge, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
				}
			});
		}

		$(document).on('keyup', '#filter_search', function(){
			getDiscounts()
		})

		$(document).on('change', '#filter_sy', function(){
			getDiscounts()
		})

		$(document).on('change', '#filter_sem', function(){
			getDiscounts()
		})

		$(document).on('click', '#setup', function(){
			GetDiscountSetup()
			$('#modal-setup').modal('show')
		})

		$(document).on('click', '#setup_create', function(){
			$('#modal-setupdetail').modal('show')
			$('#particulars').val('')
			$('#txtpercent').val('0')
			$('#chkpercent').prop('checked', false)
			$('#stat').text('Create')
			$('#saveDiscount').attr('data-id', '')
			$('#deleteDiscount').hide()
			
			setTimeout(() => {
				$('#particulars').focus()
			}, 500)
		})

		$(document).on('click', '#saveDiscount', function(){
			var particulars = $('#particulars').val()
			var amount = $('#txtpercent').val()
			var dataid = $('#saveDiscount').attr('data-id')

			if($('#chkpercent').prop('checked') == true)
			{
				var percent = 1
			}
			else{
				var percent = 0
			}
			
			if($('#chkpercent').prop('checked') == true)
			{
				var percent = 1
			}
			else{
				var percent = 0
			}

			if(dataid == '')
			{
				$.ajax({
					type: "GET",
					url: "{{route('discount_setup_create')}}",
					data: {
						particulars:particulars,
						amount:amount,
						percent:percent
					},
					// dataType: "dataType",
					success: function (data) {
						if(data == 'done')
						{
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
								type: 'success',
								title: 'Save successfully'
							})

							$('#modal-setupdetail').modal('hide')
							GetDiscountSetup()
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
								title: 'Discount already exist'
							})
						}
					}
				})
			}
			else{
				$.ajax({
					type: "GET",
					url: "{{route('discount_setup_update')}}",
					data: {
						particulars:particulars,
						amount:amount,
						percent:percent,
						dataid:dataid
					},
					// dataType: "dataType",
					success: function (data) {
						if(data == 'done')
						{
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
								type: 'success',
								title: 'Discount updated successfully'
							})

							$('#modal-setupdetail').modal('hide')
							GetDiscountSetup()
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
								title: 'Discount already exist'
							})

						}
					}
				});
			}
		})

		$(document).on('click', '#setuplist tr', function(){
			var dataid = $(this).attr('data-id')
			$('#deleteDiscount').show()
			$.ajax({
				type: "GET",
				url: "{{route('discount_setup_read')}}",
				data: {
					dataid:dataid
				},
				// dataType: "json",
				success: function (data) {
					$('#particulars').val(data.particulars)
					$('#txtpercent').val(data.amount)
					$('#saveDiscount').attr('data-id', data.id)
					
					if(data.percent == 1)
					{
						$('#chkpercent').prop('checked', true)
					}
					else{
						$('#chkpercent').prop('checked', false)
					}


					$('#modal-setupdetail').modal('show')
				}
			});
		})

		$(document).on('click', '#deleteDiscount', function(){
			var dataid = $('#saveDiscount').attr('data-id')

			Swal.fire({
				title: 'Delete Discount?',
				text: "",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Delete'
			}).then((result) => {
				if (result.value == true) {
					$.ajax({
						type: "GET",
						url: "{{route('discount_setup_delete')}}",
						data: {
							dataid:dataid
						},
						// dataType: "dataType",
						success: function (data) {
							Swal.fire(
								'Deleted!',
								'Discount has been Deleted',
								'success'
							)

							GetDiscountSetup()
							$('#modal-setupdetail').modal('hide')
						}
					});



				}
			})
		})

		$(document).on('click', '#discount_create', function(){
			onCreate()
			viewsetup = 'create';
			$('#modal-discount').modal('show')
		})

		$(document).on('change', '.discount_sysem', function(){
			getStudents()
		})

		$(document).on('change', '#discount_students', function(){
			// discount_charges();
			if(viewsetup == 'create')
			{
				discount_charges()
			}
		})

		$(document).on('change', '#discount_setup', function(){
			discountid = $(this).val()

			if(viewsetup == 'create')
			{
				$.ajax({
					type: "GET",
					url: "{{route('discount_getsetup')}}",
					data: {
						discountid:discountid
					},
					// dataType: "dataType",
					success: function (data) {
						if($('#discount_setup').val() != 0)
						{
							var totaldiscAmount = 0
							if(data[0].percent == 1)
							{
								$('.discount_amount').val(data[0].amount + '%')
							}
							else{
								$('.discount_amount').val(data[0].amount)
							}

							$('#discount_data tr').each(function(){
								if($(this).find('.discount_include').prop('checked') == true)
								{
									var chargeAmount = $(this).find('.discount_balance').text().replace(',', '')
									var discountAmount = $(this).find('.discount_amount').val().replace(',', '')
									var discAmount = 0

									$(this).find('.discount_discamount').text(calcDiscount(chargeAmount, discountAmount))

									discAmount = $(this).find('.discount_discamount').text()
									discAmount = discAmount.replace(',', '')

									totaldiscAmount += parseFloat(discAmount)
									// console.log(totaldiscAmount)
								}
								else{
									$(this).find('.discount_discamount').text('0.00')
								}
							})

							$('#discount_totaldiscamount').text(parseFloat(totaldiscAmount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
						}
						else{
							$('#discount_setup').empty()
							$('#discount_setup').append('<option value="0">SELECT DISCOUNT</option>')
							$.each(data, function (indexInArray, value) { 
								$('#discount_setup').append(`
									<option value="`+value.id+`">`+value.particulars+`</option>
								`)
							});
						}
					}
				});
			}
		})

		$(document).on('click', '.discount_include', function(){
			var totaldiscAmount = 0

			$('#discount_data tr').each(function(){
				if($(this).find('.discount_include').prop('checked') == true)
				{
					var chargeAmount = $(this).find('.discount_balance').text().replace(',', '')
					var discountAmount = $(this).find('.discount_amount').val().replace(',', '')
					var discAmount = 0

					$(this).find('.discount_discamount').text(calcDiscount(chargeAmount, discountAmount))

					discAmount = $(this).find('.discount_discamount').text()
					discAmount = discAmount.replace(',', '')
					totaldiscAmount += parseFloat(discAmount)
				}
				else{
					$(this).find('.discount_discamount').text('0.00')
				}
			})

			$('#discount_totaldiscamount').text(parseFloat(totaldiscAmount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString())
			
		})

		$(document).on('keyup', '.discount_amount', function(){
			$('#discount_data tr').each(function(){
				if($(this).find('.discount_include').prop('checked') == true)
				{
					var chargeAmount = $(this).find('.discount_balance').text()
					var discountAmount = $(this).find('.discount_amount').val().replace(',', '')
					// console.log(calcDiscount(chargeAmount, discountAmount))

					$(this).find('.discount_discamount').text(calcDiscount(chargeAmount, discountAmount))
					$('.discount_include').trigger('click')
				}
				else{
					$(this).find('.discount_discamount').text('0.00')
				}
			})
		})

		$(document).on('change', '.discount_amount', function(){

			var value = $(this).val().replace(',', '')

			if(value.indexOf('%') == -1)
			{
				value = parseFloat(value, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
				$(this).val(value)
			}
			else{
				$(this).val(value)
			}

			
		})

		function calcDiscount(charge_amount, discount)
		{
			var totalDiscount = 0;
			if(discount.indexOf('%') == -1)
			{
				// console.log('without percent')
				return parseFloat(discount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
			}
			else{
				// console.log('with percent')
				charge_amount = charge_amount.replace(',', '')
				discamount = discount.replace("%", "")
				totalDiscount = (parseFloat(charge_amount) /100) * parseFloat(discamount)

				totalDiscount = parseFloat(totalDiscount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()
				return totalDiscount;
				// console.log(totalDiscount)
			}

			

			// $(".totalSum").text('$' + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
		}

		$(document).on('click', '#discount_post', function(){
			if($('#discount_students').val() == 0)
			{
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
					title: 'No student selected'
				})
			}
			else{
				if($('#discount_setup').val() == 0)
				{
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
						title: 'No discount selected'
					})
				}
				else{
					Swal.fire({
						title: 'Post Discount',
						text: "Post student discount?",
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'POST'
						}).then((result) => {
						if (result.value == true) {

							disc_array = []

							$('#discount_data tr').each(function(){
								if($(this).find('.discount_include').prop('checked') == true)
								{
									disc_array.push({
										'id':$(this).attr('data-id'), 
										'classid':$(this).attr('class-id'), 
										'chargeamount':$(this).find('.discount_balance').text(),
										'discountamount':$(this).find('.discount_discamount').text(),
										'discount':$(this).find('.discount_amount').val()
									})
								}
							})

							var syid = $('#discount_sy').val()
							var semid = $('#discount_sem').val()
							var discountid = $('#discount_setup').val()
							var studid = $('#discount_students').val()

							$.ajax({
								type: "GET",
								url: "{{route('discount_post')}}",
								data: {
									info:disc_array,
									syid:syid,
									semid:semid,
									discountid:discountid,
									studid:studid
								},
								// dataType: "dataType",
								success: function (data) {
									Swal.fire(
										'Posted',
										'Discount has been posted',
										'success'
									)

									getDiscounts()
									$('#modal-discount').modal('hide')
								}
							});

							
						}
					})
				}
			}
				
		})

		function onCreate()
		{
			manageuiunlock()
			$('#discount_setup').val(0).trigger('change')
			$('#discount_data').empty()
			$('#discount_totaldiscamount').text('')
			$('#discount_totalamount').text('')
			
			setTimeout(() => {
				$('#discount_students').val(0).trigger('change')
			}, 500);
		}

		function manageuilock()
		{
			$('#discount_students').prop('disabled', true)
			$('#discount_setup').prop('disabled', true)
			$('#discount_sy').prop('disabled', true)
			$('#discount_sem').prop('disabled', true)

			$('#discount_post').removeClass('btn-primary')
			$('#discount_post').addClass('btn-success')
			$('#discount_post').text('Posted')
			$('#discount_post').prop('disabled', true)

			$('#discount_data :input').attr("disabled", "disabled")
		}

		function manageuiunlock()
		{
			$('#discount_students').prop('disabled', false)
			$('#discount_setup').prop('disabled', false)
			$('#discount_sy').prop('disabled', false)
			$('#discount_sem').prop('disabled', false)

			$('#discount_post').removeClass('btn-success')
			$('#discount_post').addClass('btn-primary')
			$('#discount_post').text('Post')
			$('#discount_post').prop('disabled', false)
		}

		getdiscount_setup()

		function getdiscount_setup()
		{
			$.ajax({
				type: "GET",
				url: "{{ route('discount_getsetup') }}",
				// data: "data",
				// dataType: "dataType",
				success: function (data) {
					$('#discount_setup').empty()

					$.each(data, function (indexInArray, value) { 
						 $('#discount_setup').append(`
						 	<option value="`+value.id+`">`+value.particulars+`</option></option>
						 `)
					});
				}
			});
		}

		// $(document).on('click', '#discount_body tr', function(){
		// 	var dataid = $(this).attr('data-id')
		// 	viewsetup = 'detail'

		// 	$('#modal-overlay').modal('show')
			
		// 	setTimeout(function(){
		// 		$.ajax({
		// 			type: "GET",
		// 			url: "{{route('discount_read')}}",
		// 			data: {
		// 				dataid:dataid
		// 			},
		// 			// dataType: "dataType",
		// 			success: function (data) {
		// 				$('#discount_students').val(data.studid).trigger('change')
		// 				discount_charges();
						
		// 				setTimeout(function(){
		// 					$.each(data.groups, function (index, val) { 
		// 						// console.log('val: ' + val.classid)
		// 						// $('#discount_data tr[class-id="'+val.classid+'"]').find('.discount_balance').text()
		// 						// $('#discount_data tr[class="'+val.classid+'"]').find('.discount_include').prop('checked', true)
		// 						// $('#discount_data tr[class="'+val.classid+'"]').find('.discount_amount').val(val.discount)
		// 						// $('#discount_data tr[class="'+val.classid+'"]').find('.discount_amount').trigger('keyup')
							

		// 						// $('#discount_data tr[class-id="'+val.classid+'"]').find('.discount_include').prop('checked', true);
		// 						$('#discount_data tr[class-id="'+val.classid+'"]').find('.discount_include').trigger('click');
		// 						$('#modal-discount').modal('show')
								
		// 					});
		// 				}, 1500)

		// 				setTimeout(() => {
		// 					manageuilock()
		// 					$('#modal-overlay').modal('hide')
		// 				}, 2000);
							
		// 				setTimeout(function(){
		// 					$('#discount_setup').val(data.discountid).trigger('change')
		// 				}, 500)

						
						
		// 			}
		// 		});
		// 	}, 0)		
		// })

		$(document).on('click', '#discount_body tr', function(){
			viewsetup = 'detail'
			var dataid = $(this).attr('data-id')

			// $('#modal-overlay').modal('show')

			$.ajax({
				type: "GET",
				url: "{{ route('discount_read') }}",
				data: {
					dataid:dataid
				},
				// dataType: "dataType",
				success: function (data) {
					$('#discount_students').val(data.studid).trigger('change')
					$('#discount_setup').val(data.discountid).trigger('change')
					$('#discount_sy').val(data.syid)
					$('#discount_sem').val(data.semid)
					$('#discount_totalamount').text(data.totalcharges)
					$('#discount_totaldiscamount').text(data.totaldiscount)

					$('#discount_data').empty()
					$('#discount_data').html(data.discountlist)

					manageuilock()
					$('#modal-overlay').modal('hide')
					$('#modal-discount').modal('show')
					
				}
			});
		})

		var viewsetup = ''

	})

  </script>

  
@endsection