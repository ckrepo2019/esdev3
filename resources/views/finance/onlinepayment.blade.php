@extends('finance.layouts.app')

@section('content')

  <section class="content">
    <div class="row mb-2 ml-2">
        <h1 class="m-0 text-dark">Online Payments</h1>
    </div>
    <div class="row">
      <div class="col-3">
      </div>
      <div class="col-2">
        <select class="form-control" name="status" id="ol_filter_status">
            <option value="all">ALL</option>
            <option value="0">PENDING</option>
            <option value="1">APPROVED</option>
            <option value="2">DISAPPROVED</option>
            <option value="5">COMPLETED</option>
        </select>
      </div>
      <div class="col-md-2">
        <select id="ol_syid" class="select2bs4 filters" style="width: 100%;">
          <option value="0">SCHOOL YEAR</option>
          @foreach(db::table('sy')->orderBy('sydesc')->get() as $sy)
            @if($sy->isactive == 1)
              <option value="{{$sy->id}}" selected>{{$sy->sydesc}}</option>
            @else
              <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
            @endif
          @endforeach
        </select>
      </div>
      {{-- <div class="col-md-2">
        <select id="ol_semid" class="select2bs4 filters" style="width: 100%;">
          <option value="0">SEMESTER</option>
          @foreach(db::table('semester')->where('deleted', 0)->get() as $sem)
            @if($sem->isactive == 1)
              <option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
            @else
              <option value="{{$sem->id}}">{{$sem->semester}}</option>
            @endif
          @endforeach
        </select>
      </div> --}}
      <div class="col-3">
          <div class="input-group mb-3">
              <input id="ol_search" type="text" class="form-control filters" placeholder="Search" onkeyup="this.value = this.value.toUpperCase();">
              <div class="input-group-append">
                  <span class="input-group-text ol_filter"><i class="fas fa-search"></i></span>
              </div>

          </div>
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-primary btn-block" id="ol_print"><i class="fas fa-print"></i> Print</button>
      </div>

          <div class="card-body">
            <div class="row">
              <div class="col-md-12 table-responsive">
                <table class="table table-striped table-sm text-sm">
                  <thead class="">
                    <th>DATE1</th>
                    <th>NAME</th>
                    <th>REFERENCE</th>
                    <th>PAYMENT TYPE</th>
                    <th>AMOUNT</th>
                    <th>SY/SEM</th>
                    <th>STATUS</th>
                  </thead>
                  <tbody id="item-list">

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
@section('modal')
  <div class="modal fade show" id="modal-approve" aria-modal="true" style="display: none; margin-top: -25px;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">
            Proof of payment - <span id="_status"></span>
        </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-7">
              <h5><span id="_studname"></span> - <span id="levelname"></span></h3>
            </div>
            <div class="col-md-5 text-right">
              <h5>CONTACT NO.: <span id="contactno"></span></h3>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <h5>
                AMOUNT: <span style="cursor: pointer" id="amount" class="text-bold e-amount"></span>
                <i class="fas fa-edit e-amount text-primary text-sm" data-toggle="tooltip" title="Edit" style="cursor: pointer;"></i>
              </h5>
            </div>
            <div class="col-md-6 text-right">
              <h5>
                PAYMENT TYPE: <span id="paymenttype" class="text-bold e-paymenttype" style="cursor: pointer"></span>
                <i class="fas fa-edit e-paymenttype text-primary text-sm" data-toggle="tooltip" title="Edit" style="cursor: pointer;"></i>
              </h5>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <h5>
                TRANSACTION DATE: <span id="transdate" style="cursor: pointer" class="text-bold e-transdate"></span>
                <i class="fas fa-edit e-transdate text-primary text-sm" data-toggle="tooltip" title="Edit" style="cursor: pointer;"></i>
              </h5>
            </div>
            <div class="col-md-6 text-right">
              <h5>
                REFERENCE NO.: <span id="refnum" style="cursor: pointer" class="text-bold e-refnum"></span>
                <i class="fas fa-edit e-refnum text-primary text-sm" data-toggle="tooltip" title="Edit" style="cursor: pointer;"></i>
              </h5>
            </div>
          </div>

          <hr>
          <div class="row" style="height: 305px; overflow-y: auto;">
            <div class="col-md-12 text-center">
              <img id="picurl" style="max-width: 90% !important" src="{{asset('')}}" class="product-image">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mt-2">
              <button type="button" class="btn btn-default" data-dismiss="modal">
                Close
              </button>
            </div>
            <div class="col-md-4 mt-2 payment_control">
              <div class="form-group clearfix text-right">
                <div class="icheck-primary d-inline">
                  <input type="checkbox" id="chknodp" >
                  <label for="chknodp">
                    Allow no Downpayment
                  </label>
                </div>
              </div>
            </div>
            <div class="col-md-2 text-right payment_control">
              <button id="btndisapprove" type="button" class="btn btn-danger mt-1" data-toggle="tooltip" title="Disapprove">
                <i class="fas fa-thumbs-down"></i>
              </button>
              <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
                <i class="fas fa-thumbs-up"></i>
              </button>
            </div>
          </div>
        </div>




        {{-- <div class="modal-footer justify-content-between">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
          </div>
          <div>
            <button id="btndisapprove" type="button" class="btn btn-danger" data-toggle="tooltip" title="Disapprove">
              <i class="fas fa-thumbs-down"></i>
            </button>
            <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
              <i class="fas fa-thumbs-up"></i>
            </button>
          </div>
        </div> --}}
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="amountchange" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Change Amount</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="col-12">
            <input type="number" class="form-control" name="" id="txtamount" placeholder="0.00" onkeyup="this.value = this.value.toUpperCase();">
          </div>
        </div>

        <div class="modal-footer justify-content-between">
          {{--  --}}

          <div class="float-left">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
          <div class="float-right">
            <button id="savechangeamount" type="button" class="btn btn-primary" style="width: 90px"><i class="fas fa-save"></i> Save</button>
          </div>
        </div>
      </div>
    </div>
      <!-- /.modal-content -->
  </div>

  <div class="modal fade show" id="datechange" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Change Transaction Date</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="col-12">
            <input type="date" class="form-control" name="" id="txtdate" onkeyup="this.value = this.value.toUpperCase();">
          </div>
        </div>

        <div class="modal-footer justify-content-between">
          {{--  --}}

          <div class="float-left">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
          <div class="float-right">
            <button id="savechangedate" type="button" class="btn btn-primary" style="width: 90px"><i class="fas fa-save"></i> Save</button>
          </div>
        </div>
      </div>
    </div>
      <!-- /.modal-content -->
  </div>

  <div class="modal fade show" id="paytypechange" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Change Payment Type</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="col-12">
            <select class="form-control select2bs4" name="" id="txtpaytype">
              @foreach(App\FinanceModel::paymenttype() as $paytype)
                <option value="{{$paytype->id}}">{{$paytype->description}}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="modal-footer justify-content-between">
          {{--  --}}

          <div class="float-left">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
          <div class="float-right">
            <button id="savechangepaytype" type="button" class="btn btn-primary" style="width: 90px"><i class="fas fa-save"></i> Save</button>
          </div>
        </div>
      </div>
    </div>
      <!-- /.modal-content -->
  </div>

  <div class="modal fade show" id="refnumchange" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Change Reference Number</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="col-12">
            <input type="text" name="" id="txtrefnum" class="form-control" placeholder="Reference Number">
          </div>
        </div>

        <div class="modal-footer justify-content-between">
          {{--  --}}

          <div class="float-left">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
          <div class="float-right">
            <button id="savechangerefnum" type="button" class="btn btn-primary" style="width: 90px"><i class="fas fa-save"></i> Save</button>
          </div>
        </div>
      </div>
    </div>
      <!-- /.modal-content -->
  </div>

  <div class="modal fade show" id="modal-remarks" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h4 class="modal-title">Remarks - Disapprove</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row"></div>
            <div class="col-12">
              <div class="form-group">
                <textarea id="txtremarks" class="form-control" placeholder="Remarks"></textarea>
              </div>
            </div>
        </div>

        <div class="modal-footer justify-content-between">
          {{--  --}}

          <div class="float-left">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
          <div class="float-right">
            <button id="saveDisapprove" type="button" class="btn btn-danger" style="width: 190px" disabled=""><i class="fas fa-thumbs-down"></i> Disapprove</button>
          </div>
        </div>
      </div>
    </div>
      <!-- /.modal-content -->
  </div>
@endsection
@section('js')
  <script type="text/javascript">
    var olpaycounter;




    $(document).ready(function(){

      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });
      onlinepaymentlist();

      function onlinepaymentlist(action="")
      {
        var syid = $('#ol_syid').val();
        var semid = $('#ol_semid').val();
        var filter = $('#ol_search').val();
        var status = $('#ol_filter_status').val()

        $.ajax({
          url:"{{route('onlinepaymentlist')}}",
          method:'GET',
          data:{
            syid:syid,
            semid:semid,
            filter:filter,
            status:status,
            action:action
          },
          dataType:'json',
          success:function(data)
          {
            $('#item-list').html(data.list);
          }
        });
      }

      $(document).on('click', '#item-list tr', function(){
        var dataid = $(this).attr('data-id');
        $('#chknodp').prop('checked', false)
        // console.log(dataid);
        $.ajax({
          url:"{{route('paydata')}}",
          method:'GET',
          data:{
            dataid:dataid
          },
          dataType:'json',
          success:function(data)
          {

            olpaycounter = $('#olpayCount').text();
            status = '';

            if(data.isapproved == 1)
            {
                status = 'APPROVED'
            }
            else if(data.isapproved == 2)
            {
                status = 'DISAPPROVED'
            }
            else if(data.isapproved == 0)
            {
                status = 'PENDING'
            }
            else if(data.isapproved == 5)
            {
                status = 'COMPLETED'
            }

            if(status == 'PENDING')
            {
                $('.payment_control').show();
            }
            else{
                $('.payment_control').hide();
            }

            $('#_status').text(status);
            $('#_studname').text(data.studname);
            $('#contactno').text(data.contactno);
            $('#levelname').text(data.levelname);
            $('#paymenttype').text(data.paymenttype);
            $('#amount').text(data.amount);
            $('#picurl').attr('src', data.picurl);
            $('#btnapprove').attr('data-id', dataid)
            $('#btndisapprove').attr('data-id', dataid)
            $('#transdate').text(data.transdate);
            $('#refnum').text(data.refnum);
            $('#modal-approve').modal('show');
          }
        });


        // if($('#checkStatus', this).text() != 'NOT REGISTRED')
        // {
        //   $('#modal-approve').modal('show');
        // }
      });

      $(document).on('mouseenter', '#item-list tr', function(){
        $(this).addClass('bg-gray');
      });

      $(document).on('mouseout', '#item-list tr', function(){
        $(this).removeClass('bg-gray');
      });

      $(document).on('click', '#btnapprove', function(){
        var dataid = $(this).attr('data-id');

        if($('#chknodp').prop('checked') == 1)
          var nodp = 1;
        else
          var nodp = 0;

        if(nodp == 0)
        {
          Swal.fire({
            title: 'Approve Online Payment?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<i class="fas fa-thumbs-up"></i> Approve'
          }).then((result) => {
            if (result.value) {
              $.ajax({
                url:"{{route('approvepay')}}",
                method:'GET',
                data:{
                  dataid:dataid,
                  nodp:nodp
                },
                dataType:'',
                success:function(data)
                {
                  onlinepaymentlist();
                  olpaycounter -= 1;

                  // olpayCount
                  $('#olpayCount').text(olpaycounter);
                  Swal.fire(
                    'Approved',
                    'Payment successfully approved',
                    'success'
                  );
                }
              });
            }
          })
        }
        else
        {
          Swal.fire({
            title: 'Approve No Downpayment?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<i class="fas fa-thumbs-up"></i> Approve'
          }).then((result) => {
            if (result.value) {
              $.ajax({
                url:"{{route('approvepay')}}",
                method:'GET',
                data:{
                  dataid:dataid,
                  nodp:nodp
                },
                dataType:'',
                success:function(data)
                {
                  if(data == 1)
                  {
                    onlinepaymentlist();
                    olpaycounter -= 1;

                    // olpayCount
                    $('#olpayCount').text(olpaycounter);
                    Swal.fire(
                      'Approved',
                      'No DP successfully approved',
                      'success'
                    );
                  }
                  else if(data == 2)
                  {
                    Swal.fire(
                      'Warning',
                      'No student found.',
                      'error'
                    );
                  }
                }
              });
            }
          })
        }
      });

      function callEditreturn(status)
      {
        const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
        });

        Toast.fire({
            type: "warning",
            title: "Online payment already " + status
        });
      }

      $(document).on('click', '.e-amount', function(){
        status = $('#_status').text()
        if(status == 'PENDING')
        {
            $('#amountchange').modal('show');
            $('#txtamount').val('');
        }
        else{
            callEditreturn(status)
        }
      });

      $(document).on('click', '#savechangeamount', function(){
        var amount = $('#txtamount').val();
        var dataid = $('#btnapprove').attr('data-id');
        $.ajax({
          url:"{{route('saveolAmount')}}",
          method:'GET',
          data:{
            dataid:dataid,
            amount:amount
          },
          dataType:'json',
          success:function(data)
          {
            $('#amountchange').modal('hide');
            $('#amount').text(data.amount);


            Swal.fire(
              'Saved',
              'Amount successfully saved',
              'success'
            );
          }
        });
      });

      $(document).on('click', '.e-transdate', function(){
        status = $('#_status').text();

        if(status == 'PENDING')
        {
            $('#datechange').modal('show');
        }
        else{
            callEditreturn(status)
        }

      });

      $(document).on('click', '#savechangedate', function(){
        var curdate = $('#txtdate').val();
        var dataid = $('#btnapprove').attr('data-id');
        $.ajax({
          url:"{{route('saveolDate')}}",
          method:'GET',
          data:{
            dataid:dataid,
            curdate:curdate
          },
          dataType:'json',
          success:function(data)
          {
            $('#datechange').modal('hide');
            $('#transdate').text(data.date);


            Swal.fire(
              'Saved',
              'Date successfully saved',
              'success'
            );
          }
        });
      });

      $(document).on('click', '.e-paymenttype', function(){
        var payid;
        status = $('#_status').text()

        if(status == 'PENDING')
        {
            $('#paytypechange').modal('show');

            $('#txtpaytype option').each(function(){
                payid = $(this).val();
                if($(this).text() == $('#paymenttype').text())
                {
                    $('#txtpaytype').val(payid);
                    $('#txtpaytype').trigger('change');
                }
            });
        }
        else{
            callEditreturn(status)
        }
      });

      $(document).on('click', '#savechangepaytype', function(){
        var paytypeid = $('#txtpaytype').val();
        var dataid = $('#btnapprove').attr('data-id');

        $.ajax({
          url:"{{route('saveolpaytype')}}",
          method:'GET',
          data:{
            dataid:dataid,
            paytypeid:paytypeid
          },
          dataType:'json',
          success:function(data)
          {
            $('#paytypechange').modal('hide');
            $('#paymenttype').text(data.paymenttype);


            Swal.fire(
              'Saved',
              'Payment Type successfully saved',
              'success'
            );
          }
        });
      });

      $(document).on('click', '#btndisapprove', function(){
        $('#modal-remarks').modal('show');
      });

      $(document).on('click', '#saveDisapprove', function(){
        var dataid = $('#btndisapprove').attr('data-id');
        var remarks = $('#txtremarks').val();

        // console.log(dataid + ' ' + remarks);

        $.ajax({
          url:"{{route('saveoldisapprove')}}",
          method:'GET',
          data:{
            dataid:dataid,
            remarks:remarks
          },
          dataType:'',
          success:function(data)
          {
            onlinepaymentlist();
            $('#modal-approve').modal('hide');
            $('#modal-remarks').modal('hide');
            $('#paytypechange').modal('hide');
            $('#paymenttype').text(data.paymenttype);


            Swal.fire(
              'Disapprove',
              'Online payment has been disapproved',
              'success'
            );
          }
        });
      });

      $(document).on('keyup', '#txtremarks', function(){
        if($(this).val() != '')
        {
          $('#saveDisapprove').prop('disabled', false);
        }
        else
        {
          $('#saveDisapprove').prop('disabled', true);
        }
      });

      $(document).on('click', '.e-refnum', function(){
        status = $('#_status').text()
        if(status == 'PENDING')
        {
            $('#refnumchange').modal('show');
            $('#txtrefnum').val('');
        }
        else{
            callEditreturn(status)
        }
      });

      $(document).on('click', '#savechangerefnum', function(){
        var refnum = $('#txtrefnum').val();
        var dataid = $('#btnapprove').attr('data-id');

        $.ajax({
          url:"{{route('saveolrefnum')}}",
          method:'GET',
          data:{
            dataid:dataid,
            refnum:refnum
          },
          dataType:'json',
          success:function(data)
          {
            console.log(data.stat);
            if(data.stat == 0)
            {
              // $('#btnapprove').prop('disabled', false);
              $('#refnumchange').modal('hide');
              $('#refnum').text(data.refnum);

              Swal.fire(
                'Saved',
                'Reference Number successfully saved',
                'success'
              );
            }
            else
            {
              $('#refnumchange').modal('hide');
              // $('#btnapprove').prop('disabled', true);
              Swal.fire(
                'Error',
                'Reference Number already exist',
                'warning'
              );
            }
          }
        });
      });

      $(document).on('change', '.filters', function(){
        onlinepaymentlist();
      })

      $(document).on('click', '.ol_filter', function(){
        onlinepaymentlist();
      })

      $(document).on('click', '#ol_print', function(){
        var syid = $('#ol_syid').val();
        var semid = $('#ol_semid').val();
        var filter = $('#ol_search').val();
        var status = $('#ol_filter_status').val()
        var action = 'print'

        window.open('/finance/onlinepaymentlist?action=' + action + '&filter='+filter+'&status='+status+'&syid=' + syid + '&semid='+ semid,'_blank');
      })

      $(document).on('change', '#ol_filter_status', function(){
        onlinepaymentlist();
      })



    });
  </script>
@endsection
