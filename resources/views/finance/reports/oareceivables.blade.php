@extends('finance.layouts.app')

@section('content')
  {{-- <style type="text/css">
    .table thead th  { 
                position: sticky !important; left: 0 !important; 
                width: 150px !important;
                background-color: #fff !important; 
                outline: 2px solid #fff !important;
                outline-offset: -1px !important;
            }
  </style> --}}
	{{-- <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Finance</h1> -->
          
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Payment Items</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section> --}}
  <section class="content">
  			<!-- Payment Items -->
        <div class="row mb-2 ml-2">
          <h1 class="m-0 text-dark">Old Accounts Receivables</h1>
        </div>
        <div class="row form-group">
			<div class="col-md-2">
				<select id="oa_acadprog" class="select2 form-control">
					<option value="0">ACAD PROGRAM</option>
					@foreach(db::table('academicprogram')->get() as $acadprog)
						<option value="{{ $acadprog->id }}">{{ $acadprog->progname }}</option>
					@endforeach
				</select>
				</div>
            <div class="col-md-3">
                <select id="dt_sy" class="select2 form-control">
                    <option value="0">SELECT SY</option>
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
                <select id="dt_sem" class="select2 form-control">
                    <option value="0">SELECT SEMESTER</option>
                    @foreach(db::table('semester')->where('deleted', 0)->get() as $sem)
                        @if($sem->isactive == 1)
                            <option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
                        @else
                            <option value="{{$sem->id}}">{{$sem->semester}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <div class="input-group mb-3">
                    <input type="text" class="form-control float-right" id="dt_range">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <div class="input-group-append">
                        {{-- <button class="btn btn-primary" id="dc_generate">GENERATE</button> --}}
                        <button id="dt_generate" class="btn btn-primary">GENERATE</button>
                        <button id="dt_print" class="btn btn-info">PRINT</button>
                    </div>
                </div>
                
            </div>
        </div>
		<div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        
                    </div>
                    <div class="card-body">
                        <div id="main_table" class="table-responsive p-0">
                            <table class="table table-hover table-head-fixed table-sm text-sm">
                                <thead class="bg-warning p-0">
                                    <tr>
                                        <th>DATE</th>
                                        <th class="">STUDENT NAME</th>
                                        <th class="">EXPLANATION</th>
    									<th class="text-center">AMOUNT</th>
                                        {{-- <th class="text-center">RECEIVABLE</th> --}}
                                    </tr>
                                </thead> 
                                <tbody id="dt_list" style="cursor: pointer;"></tbody>             
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
  <div class="modal fade show" id="modal-item-new" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Payment Items - New</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="class-desc" class="col-sm-2 col-form-label">Item Code</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control validation" id="item-code" placeholder="Item Code" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-desc" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control validation" id="item-desc" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-glid" class="col-sm-2 col-form-label">Classification</label>
                <div class="col-sm-10">
                  <select class="form-control select2bs4" id=item-class>
                    <option></option>
                  </select>
                </div>
              </div>


              <div class="form-group row">
                <label for="class-desc" class="col-sm-2 col-form-label">Amount</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control validation" id="item-amount" placeholder="0.00">
                </div>
              </div>

              <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-3">
                  <div class="icheck-primary d-inline">
                    <input type="checkbox" id="isreceivable-new">
                    <label for="isreceivable-new">
                      Receivable
                    </label>
                  </div>
                </div>
                {{-- <div class="col-md-4">
                  <div class="icheck-primary d-inline">
                    <input type="checkbox" id="dp-new">
                    <label for="dp-new">
                      Downpayment
                    </label>
                  </div>
                </div> --}}
                <div class="col-md-3">
                  <div class="icheck-primary d-inline">
                    <input type="checkbox" id="expense-new">
                    <label for="expense-new">
                      Expense
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
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="saveItem" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

    


  

  
@endsection

@section('js')
  
  <script type="text/javascript">
    
    $(document).ready(function(){
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        $('#fc_date').daterangepicker()
        $('#dt_range').daterangepicker();

        screenadjust();

        function screenadjust()
        {
            var screen_height = $(window).height();

            $('#main_table').css('height', screen_height - 300);
            // $('.screen-adj').css('height', screen_height - 223);
        }


        function generate(action='')
        {
            var syid = $('#dt_sy').val()
            var semid = $('#dt_sem').val()
            var range = $('#dt_range').val()
			var acadprog = $('#oa_acadprog').val();

            $.ajax({
                url: '{{route('oareceivables_load')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    syid:syid,
                    semid:semid,
                    range:range,
					acadprog:acadprog
                },
                success:function(data)
                {
                    $('#dt_list tr').empty()
                    var total = 0;

                    $.each(data, function(index, val) {
						var firstname = (val.firstname != null) ? val.firstname : ''
						var lastname = (val.lastname != null) ? val.lastname : ''
						var middlename = (val.middlename != null) ? val.middlename : ''
						var suffix = (val.suffix != null) ? val.suffix : ''

                        $('#dt_list').append(`
                            <tr>
								<td>`+moment(val.date).format('M/D/Y')+`</td>
                                <td>`+lastname+`, `+firstname+` `+middlename+` `+suffix+`</td>
                                <td>`+val.particulars.toUpperCase()+`</td>
                                <td class="text-right">`+parseFloat(val.amount, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`</td>
                            </tr>
                        `)

                        total += parseFloat(val.amount)
                    });

                    $('#dt_list').append(`
                        <tr>
                            <td colspan="3" class="text-right text-bold">TOTAL: </td>
                            <td class="text-right text-bold">
                                `+parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString()+`
                            </td>
                        </tr>
                    `)
                }
            });
            
        }

        $(document).on('click', '#dt_generate', function(){
			var acadprog = $('#oa_acadprog').val();
            if(acadprog != 0)
			{
				generate('generate');
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
					title: 'No Academic program selected'
				})
			}
        })

        $(document).on('click', '#dt_print', function(){
            var syid = $('#dt_sy').val()
            var semid = $('#dt_sem').val()
            var range = $('#dt_range').val()
			var acadprog = $('#oa_acadprog').val();

			if(acadprog != 0)
			{
            	window.open('/finance/reports/oareceivables_load?syid=' + syid + '&semid=' + semid + '&acadprog='+acadprog+'&range='+range+'&action=print','_blank');
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
					title: 'No Academic program selected'
				})
			}
        });

    });

  </script>
  
@endsection