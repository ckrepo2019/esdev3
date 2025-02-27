@extends('finance.layouts.app')

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Finance</h1> -->
          <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            MODE OF PAYMENTS - NEW</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/finance/modeofpayment">Mode of payment</a></li>
            <li class="breadcrumb-item active">New</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
  	<div class="main-card card">
  		<div class="card-header text-lg bg-info">
  			Mode of Payments - New
        <div class="float-right">
          <span id="saveMOP" class="btn btn-primary">Save</span>
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
                  <input type="text" class="form-control" id="txtdesc" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                </div>    
              </div>

              <div class="col-2">
                <div class="form-group">
                  <label for="txtnopay">Number of Payments</label>
                  <input type="" class="form-control" id="txtnopay" placeholder="Number of payments" maxlength="2" disabled >
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
                  <input type="checkbox" id="isdp" >
                  <label for="isdp">One time Payment</label>
                </div>
              </div>
              <div id="divOpt" class="col-3 mt-3">
                <div class="form-group clearfix mt-4">
                  <div class="icheck-primary d-inline">
                      <input type="radio" id="radDivided" name="r1" checked>
                    <label for="radDivided">
                      Divided &nbsp;
                    </label>
                  </div>
                  <div class="icheck-primary d-inline">
                      <input type="radio" id="radPecentage" name="r1">
                    <label for="radPecentage">
                      Percentage
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead class="bg-warning">
                    <tr>
                      <td style="width: 25px">NO.</td>
                      <td style="width: 130px">DUE DATE</td>
                      <td colspan="2"></td>
                    </tr>
                  </thead>
                  <tbody id="dues">
                    
                  </tbody>
                </table>
              </div>
            </div>
            
        </form>

      </div>
  			
  	</div>
  </section>
@endsection


@section('js')
  <script type="text/javascript">
    
    $(document).ready(function(){

      
      
      searchMOP();
      function searchMOP(query)
      {
        $.ajax({
          url:"{{route('searchMOP')}}",
          method:'GET',
          data:{
            query:query
          },
          dataType:'json',
          success:function(data)
          {
            $('#paymethod-list').html(data.output);
          }
        });   
      }


      function setInputFilter(textbox, inputFilter) {
        ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
          textbox.addEventListener(event, function() {
            if (inputFilter(this.value)) {
              this.oldValue = this.value;
              this.oldSelectionStart = this.selectionStart;
              this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
              this.value = this.oldValue;
              this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
              this.value = "";
            }
          });
        });
      }

      setInputFilter(document.getElementById("txtnopay"), function(value) 
      {
        return /^\d*$/.test(value); 
      });


      $(document).on('keydown', function(e){
        if(e.which==13)
        {
          e.preventDefault();
        }
      });

      var payCount = 0;

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
        var paytype = '';

        if($('#radDivided').prop('checked') == true)
        {
          paytype = 'disabled';
        }
        else
        {
          paytype = '';
        }


        payCount +=1;
        
        $('#dues').append(`
            <tr id="`+payCount+`">
              <td class="text-center row-no" style="width:30px"></td>
              <td style="width:130px">
                <input type="date" name="" class="form-control due-date">
              </td>
              <td style="width:130px">
                <input id="txtpercentAmount" value="" type="number" class="form-control percent-control" name="" placeholder="%" `+paytype+` data-id=""> 
              </td>
              <td>
                <span data-id="`+payCount+`" class="btn btn-warning delRow"><i class="fas fa-times"></i></span>
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
        $('#' + sel).remove();

        payCount -= 1;
        $('#txtnopay').val(payCount);

        $('#dues tr').each(function(){
          rowCount += 1;
          $(this).find('.row-no').each(function(){
            $(this).text(rowCount);
          });
        });

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
          // payCount = 0;
        }
        else
        {
          $('#addPayment').removeClass('disabled');
          $('#addPayment').prop('disabled', false);
        }
      });

      $(document).on('click', '#saveMOP', function(){
        var desc = $('#txtdesc').val();
        var noofpayment = $('#txtnopay').val();
        var isdp = 0;
        var valCheck = $('#isdp').prop('checked');

        var valPercent = 0;

        var payopt = '';

        if(valCheck == true)
        {
          isdp = 1;
        }
        else
        {
          isdp = 0;
        }

        if($('#radDivided').prop('checked') == true)
        {
          payopt = 'divided';
        }
        else
        {
          payopt = 'percentage';
        }


        var duedates = [];

        $('.due-date').each(function(){
            duedates.push({
              'due':$(this).val(),
              'pAmount':$(this).closest('td').next('td').find('.percent-control').val()
            });

            valPercent += parseFloat($(this).closest('td').next('td').find('.percent-control').val());

          });

        console.log(valPercent);

        if(payopt == 'percentage')
        {
          if(valPercent == 100)
          {
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
    });


    $(document).on('change', 'tr .percent-control', function(){
        var mopid = $(this).attr('data-id');
        var percentamount = $(this).val();
        var percent = 0;


        $('.percent-control').each(function(){
          if($(this).val() != '')
          {
            percent += parseFloat($(this).val());
          }
        });

        console.log(percent);
        if(percent <= 100)
        {
          
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
      
      
    });

  </script>
@endsection