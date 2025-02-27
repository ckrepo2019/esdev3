@extends('finance.layouts.app')

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Finance</h1> -->
          <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            MODE OF PAYMENTS - EDIT</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/finance/modeofpayment">Mode of payment</a></li>
            <li class="breadcrumb-item active">Edit</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
  	<div class="main-card card">
  		<div class="card-header text-lg bg-info">
  			Mode of Payments - Edit
        <div class="float-right">
          <span id="updateMOP" class="btn btn-primary">Save</span>
          <button class="btn btn-danger" onclick="window.location.href='/finance/modeofpayment'">Cancel</button>  
        </div>
        
  		</div>
  		<div class="card-body">
        
        <form role="form">
          <div class="card-body">
            <div class="row">
              <div class="col-3">
                <div class="form-group">
                  <label for="txtdesc">Description</label>
                  <input type="text" class="form-control" id="txtdesc" value="{{$mop->paymentdesc}}" data-id="{{$mop->id}}" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                </div>    
              </div>

              <div class="col-2">
                <div class="form-group">
                  <label for="txtnopay">Number of Payments</label>
                  <input type="" class="form-control" id="txtnopay" value="{{$mop->noofpayment}}" placeholder="Number of payments" maxlength="2" disabled >
                </div>    
              </div>
              <div class="col-1">
                <div class="form-group">
                  <div class="mt-2">&nbsp;</div>
                  <span id="addPayment" class="btn btn-info"><i class="fas fa-plus"></i></span>
                </div>    
              </div>

              <div class="col-3">
                <div class="mt-3">&nbsp;</div>
                <div class="icheck-primary d-inline">
                  @if($mop->isdp == 0)
                    <input type="checkbox" id="isdp" >
                  @else()
                    <input type="checkbox" id="isdp" checked="">
                  @endif
                  
                  <label for="isdp">One time Payment</label>
                </div>
              </div>

              <div id="divOpt" class="col-3 mt-3">
                <div class="form-group clearfix mt-4">
                  <div class="icheck-primary d-inline">

                    @if($mop->payopt == 'divided')
                      <input type="radio" id="radDivided" name="r1" checked>
                    @else
                      <input type="radio" id="radDivided" name="r1">
                    @endif
                    <label for="radDivided">
                      Divided &nbsp;
                    </label>
                  </div>
                  <div class="icheck-primary d-inline">
                    @if($mop->payopt == 'percentage')
                      <input type="radio" id="radPecentage" name="r1" checked="">
                    @else
                      <input type="radio" id="radPecentage" name="r1">
                    @endif
                    <label for="radPecentage">
                      Percentage
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div id="duelist" class="row">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead class="bg-warning">
                    <tr>
                      <td style="width:12px">NO.</td>
                      <td style="width: 45px">DUE DATE</td>
                      <td colspan="2"></td>
                    </tr>
                  </thead>
                  <tbody id="dues">
                    @foreach($mopdetail as $mopd)
                      <tr id="{{$mopd->id}}">
                        <td class="text-center row-no">{{$mopd->paymentno}}</td>
                        <td>
                          <input type="date" name="" value="{{$mopd->duedate}}" data-id="{{$mopd->id}}" class="form-control due-date">
                        </td>
                        <td style="width: 130px">
                        @if($mop->payopt == 'percentage')
                          <input id="txtpercentAmount" value="{{$mopd->percentamount}}" type="number"  class="form-control percent-control" name="" placeholder="%" data-id="{{$mopd->id}}"> 
                        @else
                          <input id="txtpercentAmount" value="{{$mopd->percentamount}}" type="number" class="form-control percent-control" name="" placeholder="%" disabled data-id="{{$mopd->id}}"> 
                        @endif
                        </td>
                        <td>
                          <span data-id="{{$mopd->id}}" class="btn btn-warning delRow" data-value="1"><i class="fas fa-times"></i></span>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            
        </form>

      </div>
  			
  	</div>
  </section>
@endsection

@section('modal')
  
@endsection

@section('js')
  <script type="text/javascript">
    
    $(document).ready(function(){


      if($('#radDivided').prop('checked') == true)
      {
        $('.percent-control').prop('disabled', true);
      }
      else
      {
        $('.percent-control').prop('disabled', false); 
      }

      isdpUI($('#isdp').prop('checked'));

      $(document).on('keydown', function(e){
        if(e.which==13)
        {
          e.preventDefault();
        }
      });

      var payCount = parseInt($('#txtnopay').val());


      function isdpUI(val)
      {
        if(val == true)
        {
          $('#duelist').hide();
        }
        else
        {
          $('duelist').show();
        }
      }


      $(document).on('click', '#addPayment', function(){
        var rowCount = 0;
        payCount +=1;
        
        $('#dues').append(`
            <tr id="`+payCount+`">
              <td id="row-no-`+payCount+`" class="text-center row-no"></td>
              <td>
                <input type="date" id="due-`+payCount+`" name="" class="form-control due-date">
              </td>
              <td>
                <span data-id="`+payCount+`" class="btn btn-primary saveRow"><i class="fas fa-download"></i></span>
                <span data-id="`+payCount+`" class="btn btn-warning delRow" data-value="0"><i class="fas fa-times"></i></span>
              </td>
            </tr>
        `);

        $('#txtnopay').val(payCount);


        $('#dues tr').each(function(){
          rowCount += 1;
          $(this).find('.row-no').each(function(){
            $(this).text(rowCount);
          });
        });

      });


      $(document).on('click', '.delRow', function(){
        var sel = $(this).attr('data-id');
        var rowCount = 0;
        var headerid = $('#txtdesc').attr('data-id');
        var noofpayment = payCount - 1;
        
        

        if($(this).attr('data-value') == 1)
        {
          Swal.fire({
            title: 'Delete selected Item?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.value) {

              $.ajax({
                url:"{{route('mopdetailDel')}}",
                method:'GET',
                data:{
                  mopdid:sel,
                  headerid:headerid,
                  noofpayment:noofpayment
                },
                dataType:'',
                success:function(data)
                {

                  $('#' + sel).remove();

                  payCount -= 1;
                  console.log(payCount);
                  $('#txtnopay').val(payCount);

                  $('#dues tr').each(function(){
                    rowCount += 1;
                    $(this).find('.row-no').each(function(){
                      $(this).text(rowCount);
                    });
                  });  

                  Swal.fire(
                    'Deleted!',
                    'Item has been deleted.',
                    'success'
                  );

                }
              }); 
            }
          });
        }
        else
        {
          $('#' + sel).remove();

          payCount -= 1;
          $('#txtnopay').val(payCount);

          $('#dues tr').each(function(){
            rowCount += 1;
            $(this).find('.row-no').each(function(){
              $(this).text(rowCount);
            });
          });  
        }

      });

      $(document).on('click', '#isdp', function(){
        
        valCheck = $(this).prop('checked');

        isdpUI(valCheck);

        if(valCheck==true)
        {
          $('#addPayment').addClass('disabled');
          $('#addPayment').prop('disabled', true);
          $('#dues').empty();
          $('#txtnopay').val(1);
          $('#divOpt').hide();
        }
        else
        {
          $('#addPayment').removeClass('disabled');
          $('#addPayment').prop('disabled', false);
          $('#duelist').show();
          $('#divOpt').show();
        }
      });

      $(document).on('click', '#saveMOP', function(){
        var desc = $('#txtdesc').val();
        var noofpayment = $('#txtnopay').val();
        var isdp = 0;
        var valCheck = $('#isdp').prop('checked');
        var percentval = 0;

        var payopt = '';

        if($('#radDivided').prop('checked') == true)
        {
          payopt = 'divided';
        }
        else
        {
          payopt = 'percentage';
        }

        if(valCheck == true)
        {
          isdp = 1;
        }
        else
        {
          isdp = 0;
        }

        console.log(payopt);

        if(payopt == percentage)
        {
          $('.percent-control').each(function(){
            percentval += $(this).val();
          });

          console.log(percentval);

          if(percentval == 100)
          {
            var duedates = [];

            $('.due-date').each(function(){
                duedates.push($(this).val());
              });

            $.ajax({
              url:"{{route('mopsave')}}",
              method:'GET',
              data:{
                desc:desc,
                duedate:duedates,
                noofpayment:noofpayment,
                isdp:isdp
              },
              dataType:'',
              success:function(data)
              {
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
                  title: 'Payment Method successfully saved.'
                });

                window.location = '/finance/modeofpayment';
              }
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
              onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })

              Toast.fire({
                type: 'error',
                title: 'Percent value is not equal to 100.'
              });

              window.location = '/finance/modeofpayment';
          }
        }
        else
        {
          var duedates = [];

          $('.due-date').each(function(){

              duedates.push($(this).val());
            });

            $.ajax({
              url:"{{route('mopsave')}}",
              method:'GET',
              data:{
                desc:desc,
                duedate:duedates,
                noofpayment:noofpayment,
                isdp:isdp
              },
              dataType:'',
              success:function(data)
              {
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
                  title: 'Payment Method successfully saved.'
                });

                window.location = '/finance/modeofpayment';
              }
            });
        }

             

      });

      $(document).on('click', '.saveRow', function(){
        var curLoc = $(this).attr('data-id');
        payCount = $('#txtnopay').val();
        var mopid = $('#txtdesc').attr('data-id');
        var paymentno = $('#row-no-' + curLoc).text();
        var duedate = $('#due-' + curLoc).val();

        $(this).remove();

        $.ajax({
          url:"{{route('mopdetailAdd')}}",
          method:'GET',
          data:{
            mopid:mopid,
            paymentno:paymentno,
            duedate:duedate,
            noofpayment:payCount
          },
          dataType:'',
          success:function(data)
          {
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
              title: 'Payment Schedule successfully added.'
            });

          }
        });   

      });
      
      $(document).on('click', '#updateMOP', function(){
        var mopid = $('#txtdesc').attr('data-id');
        var paydesc = $('#txtdesc').val();
        var noofpayment = $('#txtnopay').val();
        var valCheck = $('#isdp').prop('checked');
        var isdp = 0;
        var payopt = '';
        var percentval = 0;

        if(valCheck == false)
        {
          isdp = 0;
        }
        else
        {
          isdp = 1;
        }

        if($('#radDivided').prop('checked') == true)
        {
          payopt = 'divided';
        }

        if($('#radPecentage').prop('checked') == true)
        {
          payopt = 'percentage';
          console.log(payopt);
          $('.percent-control').each(function(){
            percentval += parseFloat($(this).val());
          });
        }

        console.log(percentval);

        if(payopt == 'percentage')
        {
          if(percentval == 100)
          {
            $.ajax({
              url:"{{route('mopupdate')}}",
              method:'GET',
              data:{
                mopid:mopid,
                paydesc:paydesc,
                noofpayment:noofpayment,
                isdp:isdp,
                payopt:payopt
              },
              dataType:'',
              success:function(data)
              {
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
                  title: 'Payment Method has been updated.'
                });

                setTimeout(() => {
                  window.location = '/finance/modeofpayment';
                }, 1000);

              }
            });
          }
          else
          {
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
              type: 'error',
              title: 'Percent value is not equal to 100.'
            });            
          }
        }
        else
        {
          $.ajax({
            url:"{{route('mopupdate')}}",
            method:'GET',
            data:{
              mopid:mopid,
              paydesc:paydesc,
              noofpayment:noofpayment,
              isdp:isdp,
              payopt:payopt
            },
            dataType:'',
            success:function(data)
            {
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
                title: 'Payment Method has been updated.'
              });

              window.location = '/finance/modeofpayment';

            }
          });
        }
      }); 

      $(document).on('change', 'tr .due-date', function(){
        var mopid = $(this).attr('data-id');
        var duedate = $(this).val();

        // console.log(duedate);
        $.ajax({
          url:"{{route('dueEdit')}}",
          method:'GET',
          data:{
            mopid:mopid,
            duedate:duedate,
          },
          dataType:'',
          success:function(data)
          {

          }
        });
      });

      $(document).on('focusin', 'tr .percent-control', function(){
        console.log($(this).val());
        $(this).attr('data-value', $(this).val())
      })

      $(document).on('change', 'tr .percent-control', function(){
        var mopid = $(this).attr('data-id');
        var percentamount = $(this).val();
        var percent = 0;


        $('.percent-control').each(function(){
          percent += parseFloat($(this).val());
        });

        console.log(percent);
        if(percent <= 100)
        {
          $.ajax({
            url:"{{route('percentEdit')}}",
            method:'GET',
            data:{
              mopid:mopid,
              percentamount:percentamount
            },
            dataType:'',
            success:function(data)
            {
              if(percent < 100)
              {
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
                    type: 'warning',
                    title: 'Percentage is less than 100%.'
                  });

                  $(this).val($(this).attr('data-value'))       
              }
            }
          });
        }
        else
        {
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
              type: 'warning',
              title: 'Percentage amount exeeded.'
            });

            $(this).val($(this).attr('data-value'))
        }
      });

      $(document).on('change', '#radDivided', function(){
        if($('#radDivided').prop('checked') == true)
        {
          $('.percent-control').prop('disabled', true);
        }
      })

      $(document).on('change', '#radPecentage', function(){
        if($('#radPecentage').prop('checked') == true)
        {
          $('.percent-control').prop('disabled', false);
        }
      })
    });

  </script>
@endsection