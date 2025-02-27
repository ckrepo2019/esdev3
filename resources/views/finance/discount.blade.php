@extends('finance.layouts.app')

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Finance</h1> -->
          
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Discounts</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="row">
    <div class="col-md-4">
        <div class="main-card card">
          <div class="card-header bg-success">
          <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            <b>DISCOUNTS</b></h4>
          </div>
          <div class="card-body">
            <div class="float-right">
              <button id="creatediscount" class="btn btn-primary" data-toggle="modal" data-target="#modal-discount">Create Discount</button>
            </div>
          </div>
          <div class="card-body table-responsive p-0" style="height: 330px">
            <table class="col-md-12 table  table-striped">
              <thead class="bg-warning">
                <th>PARTICULARS</th>
                <th>DISCOUNT</th>
              </thead>
              <tbody id="discList">
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="main-card card">
      		<div class="card-header bg-info">
      			<!-- Discount Transactions  -->
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            <b>DISCOUNTS TRANSACTIONS</b></h4>
      		</div>
      		<div class="card-body">
            <div class="row">
              <div class="col-md-3 mt-1">

              </div>
              <div class="col-md-9 text-right">
                <button id="btn-studDiscount" class="btn btn-primary btn-outline" data-toggle="modal" data-target="#modal-studDiscount">
                  Add Student to Avail for Discount &nbsp;
                  <span id="unposted" class="text-bold badge bg-danger"></span>
                </button>
              </div>
            </div>
      		</div>

          <div class="card-body table-responsive p-0" style="height: 330px">
            <table class="col-md-12 table table-striped">
              <thead class="bg-warning">
                <th>STUDENT NAME</th>
                <th>GRADE LEVEL</th>
                <th>PARTICULARS</th>
                <th>DISCOUNT</th>
                <th></th>
                <th></th>
              </thead>
              <tbody id="listStudDiscount">
                
              </tbody>
            </table>
          </div>
        </div>
      </div>

      
    </div>
  </section>

@endsection
@section('modal')
  <div class="modal fade show" id="modal-discount" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Discount - <span id="stat"></span></h4>
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
                  <label for="chkpercent">
                    Percent
                  </label>
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
            <button id="deleteDiscount" type="button" class="btn btn-danger" data-dismiss="modal" data-id="" action-id="">Delete</button>
            <button id="saveDiscount" type="button" class="btn btn-primary" data-dismiss="modal" data-id="" action-id="">Save</button>
          </div>

            

        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-studDiscount" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Student Discount</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            
          <div class="row">
            <div class="form-group col-md-12">
              <label>Student</label>
              <select id="studName" name="studid" class="text-secondary form-control select2bs4 updq is-invalid" value="">
                
              </select>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label id="lbldisctemplate">Discount</label>
              <select id="discTemplate" name="" class="text-secondary form-control select2bs4 updq is-invalid" value="">
                
              </select>
            </div>
            <div class="form-group col-md-6">
              <label id="disckind">Percent/Amount</label>
              <input id="stud-percent" type="" name="" class="form-control" disabled="" placeholder="0.00">
            </div>

          </div>

          <div class="row">
            <div class="form-group col-md-6">
              <label>Discount to</label>
              <select id="classification" name="" class="text-secondary form-control select2bs4 updq is-invalid" value="">
                  
              </select>
            </div>
  
            <div class="form-group col-md-6">
              <label>Discount Amount</label>
              <input id="discamount" type="" name="" class="form-control" disabled="" placeholder="0.00">
            </div>
                
            

            {{-- <div class="form-group col-md-6">
              <label>Scheme</label>
              <select id="mop" name="" class="text-secondary form-control select2bs4 updq" value="">
                  <option value="0" selected=""></option>
                @foreach(App\FinanceModel::loadMOP() as $mop)
                  <option value="{{$mop->id}}">{{$mop->paymentdesc}}</option>
                @endforeach
              </select>
            </div> --}}
          </div>

        </div>

        <div class="modal-footer justify-content-between">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
          <div>
            <button id="studDiscDelete" type="button" class="btn btn-danger" data-dismiss="modal" data-id="" action-id="" disabled="">Delete</button>
            <button id="studDiscSave" type="button" class="btn btn-primary" data-dismiss="modal" data-id="" action-id="">Save</button>
          </div>

            

        </div>
        
      </div>
    </div> {{-- dialog --}}
  </div>
@endsection
@section('js')
  <script type="text/javascript">
    $(document).ready(function(){

      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });


      $(document).on('mouseover', '#discList tr', function(){
        $(this).addClass('bg-secondary');
      });

      $(document).on('mouseout', '#discList tr', function(){
        $(this).removeClass('bg-secondary');
      });


      discountSearch();
      searchStudDiscount();
      searchStud();
      loaddiscount();

      function loaddiscount()
      {
        $.ajax({
          url:"{{route('loaddiscount')}}",
          method:'GET',
          data:{
            
          },
          dataType:'json',
          success:function(data)
          {
            $('#discTemplate').html(data.list);
          }
        }); 
      }

      function searchStud(text='')
      {
        // var query = $('#txtsearch').val();
        $.ajax({
          url:"{{route('searchStud')}}",
          method:'GET',
          data:{
            query:text
          },
          dataType:'json',
          success:function(data)
          {
            // $('#stud-list').html(data.list);

            $('#studName').html(data.list);
            
            $('#studName').val('');

          }
        }); 
      }

      function discountSearch()
      {
        $.ajax({
          url:"{{route('discountSearch')}}",
          method:'GET',
          data:{
            
          },
          dataType:'json',
          success:function(data)
          {
            $('#discList').html(data.lists);
          }
        });
      }

      function discountClear()
      {
        $('#particulars').val('');
        $('#amount').val('');
        $('#percent').prop('checked', true);
      }

      function loadClass()
      {
        var studid = $('#studName').val();
        
        $.ajax({
          url:"{{route('loadDiscClass')}}",
          method:'GET',
          data:{
            studid:studid
          },
          dataType:'json',
          success:function(data)
          {
            $('#classification').html(data.lists);
            $('#classification').val('');
          }
        });
      }

      function searchStudDiscount()
      {
        $.ajax({
          url:"{{route('searchStudDiscount')}}",
          method:'GET',
          data:{
            // studid:studid
          },
          dataType:'json',
          success:function(data)
          {
            $('#listStudDiscount').html(data.lists);
            $('#unposted').text(data.unposted);
          }
        });
      }

      function validate()
      {
        var vCount = 0;
        console.log('vCount ' + vCount);
        $('.updq').each(function(){
          if($(this).hasClass('is-invalid'))
          {
            vCount += 1;
          }
        });
        console.log('vCount ' + vCount);

        if(vCount > 0)
        {
          $('#studDiscSave').prop('disabled', true);
        }
        else
        {
          $('#studDiscSave').prop('disabled', false);
        }
      }

      $(document).on('change', '.updq', function(){
        // console.log($(this).val());

        // if($(this).val() == null)
        // {
        //   $(this).val('');
        //   $(this).trigger('change');
        // }


        if($(this).val() != null && $(this).val() != 0)
        {
          $(this).removeClass('is-invalid');
          $(this).addClass('is-valid');
        }
        else
        {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid');
        }

        validate();
      });

      $(document).on('click', '#creatediscount', function(){
        $('#stat').text('Create');
        $('#saveDiscount').attr('data-id', 0);
        $('#saveDiscount').attr('action-id', 1);
        $('#saveDiscount').text('Save');
        discountClear();
      });

      $(document).on('click', '#saveDiscount', function(){
        var particulars = $('#particulars').val();
        var amount = $('#txtpercent').val();
        var percent = 0;

        if($('#chkpercent').prop('checked') == true)
        {
          percent = 1
        }
        else
        {
          percent = 0
        }

        if($(this).attr('action-id') == 1)
        {
          $.ajax({
            url:"{{route('discnew')}}",
            method:'GET',
            data:{
              particulars:particulars,
              amount:amount,
              percent:percent
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
                title: 'Discount successfully saved.'
              })

              discountSearch($('#txtsearchitem').val());

            }
          });
        }
        else
        {
          var discID = $(this).attr('data-id');
          $.ajax({
            url:"{{route('discountupdate')}}",
            method:'GET',
            data:{
              discID:discID,
              particulars:particulars,
              amount:amount,
              percent:percent
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
                title: 'Discount successfully updated.'
              })

              discountSearch();

            }
          }); 
        }
      });

      $(document).on('click', '#discList tr', function(){
        var discID = $(this).attr('data-id');

        $('#modal-discount').modal('show');
        $('#saveDiscount').attr('data-id', discID);
        $('#saveDiscount').attr('action-id', 0);

        $.ajax({
          url:"{{route('discountedit')}}",
          method:'GET',
          data:{
            discID:discID
          },
          dataType:'json',
          success:function(data)
          {
            $('#particulars').val(data.particulars);
            $('#txtpercent').val(data.amount);
            $('#saveDiscount').text('Update');

            if(data.percent == 1)
            {
              $('#chkpercent').prop('checked', true);  
            }
            else
            {
              $('#chkpercent').prop('checked', false);  
            }
            
          }
        });

      });

      $(document).on('click', '#deleteDiscount', function(){

        var discID = $('#saveDiscount').attr('data-id');

        Swal.fire({
          title: 'Delete selected Discount?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"{{route('discountdelete')}}",
              method:'GET',
              data:{
                discID:discID
              },
              dataType:'',
              success:function(data)
              {
                Swal.fire(
                  'Deleted!',
                  'Discount has been deleted.',
                  'success'
                );

                discountSearch();
              }
            }); 
          }
        });
      });

      $(document).on('change', '#discTemplate', function(){
        var discVal = $('#discTemplate option:selected').attr('data-value');
        var discKind = $('#discTemplate option:selected').attr('data-kind');
        
        $('#stud-percent').val(discVal);
        if(discKind == 1)
        {
          $('#disckind').text('Percent')
        }
        else
        {
          $('#disckind').text('Amount') 
        }
      });

      $(document).on('change', '#studName', function(){
        loadClass();
      });

      $(document).on('change', '#classification', function(){
        var mopid = $('#classification option:selected').attr('mop-id');
        var classid = $(this).val();
        var studid = $('#studName').val();
        var discountid = $('#discTemplate').val();

        // console.log($(this).val());
        $.ajax({
          url:"{{route('discountamount')}}",
          method:'GET',
          data:{
            studid:studid,
            classid:classid,
            discountid:discountid
          },
          dataType:'json',
          success:function(data)
          {
            $('#discamount').val(data.discamount);
          }
        }); 


        
      });

      $(document).on('click', '#studDiscSave', function(){
        var studid = $('#studName').val();
        var discountid = $('#discTemplate').val();
        var classid = $('#classification').val();
        var pschemeid = $('#mop').val();

        $.ajax({
          url:"{{route('saveStudDiscount')}}",
          method:'GET',
          data:{
            studid:studid,
            discountid:discountid,
            classid:classid,
            pschemeid:pschemeid
          },
          dataType:'',
          success:function(data)
          {
            if(data != 0)
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
                  title: 'Discount successfully saved.'
                }) 

                searchStudDiscount();
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
                  title: 'Discount already applied.'
                });
            }


          }
        });

      });

      $(document).on('click', '.btn-posted', function(){
        var discountid = $(this).attr('data-id')
        Swal.fire({
          title: 'Post selected Discount Transaction?',
          text: "You won't be able to revert this!",
          type: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: '<i class="fas fa-check"></i> Post'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"{{route('postStudDiscount')}}",
              method:'GET',
              data:{
                discountid:discountid
              },
              dataType:'',
              success:function(data)
              {
                if(data == 1)
                {
                  Swal.fire(
                    'Posted!',
                    'Discount has been posted.',
                    'success'
                  );

                  searchStudDiscount();
                }
                else
                {
                  Swal.fire(
                    'Post Failed!',
                    'Student is not enrolled.',
                    'warning'
                  );
                }
              }
            }); 
          }
        });
      });

      $(document).on('click', '#btn-studDiscount', function(){
        searchStud();
        loaddiscount();

        $('#studName').val('');
        $('#studName').trigger('change');
        $('#discTemplate').val('');
        $('#discTemplate').trigger('change');
        $('#classification').val('');
        $('#classification').trigger('change');
        $('#stud-percent').val('');
        $('#discamount').val('');
      });

      $(document).on('click', '.btn-del', function(){
        Swal.fire({
          title: 'Delete selected Student Discount?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            var dataid = $(this).attr('data-id');
            $.ajax({
              url:"{{route('delstuddiscount')}}",
              method:'GET',
              data:{
                dataid:dataid
              },
              dataType:'',
              success:function(data)
              {
                Swal.fire(
                  'Deleted!',
                  'Discount has been deleted.',
                  'success'
                );

                searchStudDiscount();
              }
            }); 
          }
        });
      });
	  
	  $(document).on('click', '#chkpercent', function(){
        if($(this).prop('checked') == true)
        {
          $('#percent_label').text('Percent');
        }
        else
        {
          $('#percent_label').text('Amount'); 
        }
      })

    });
  </script>
@endsection
