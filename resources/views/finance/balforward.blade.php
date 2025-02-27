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
            <li class="breadcrumb-item active">Balance Forwarding</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content pt-0">
    <div class="row">
      <div class="col-md-8">
        <div class="main-card card">
      		<div class="card-header text-lg bg-info">
            <div class="row">
              <div class="col-md-8">
              <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            <b>BALANCE FORWARDING</b></h4>
              </div>
              <div class="col-md-4">
                <button id="mbf" class="btn btn-warning " data-toggle="modal" data-target="#modal-mbf" disabled="">
                  Add student for forwarding
                </button>
              </div>
            </div>
      		</div>
      		

          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-6">
                      <select id="glevel" class="form-control select2bs4 bal-field">
                        <option value="0">Grade Level</option>
                        @foreach(App\FinanceModel::loadGlevel() as $glevel)
                          <option value="{{$glevel->id}}">{{$glevel->levelname}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                          <select id="sy" class="form-control select2bs4 bal-field">
                            <option value="0">School Year</option>
                            @foreach(App\FinanceModel::getSY() as $sy)
                              @if($sy->isactive == 1)
                                <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                              @else
                                <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                              @endif
                            @endforeach
                          </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group divsem" hidden="">
                          <select id="sem" class="form-control select2bs4 bal-field">
                            <option value="0">Semester</option>
                            @foreach(App\FinanceModel::getSem() as $sem)
                              @if($sem->isactive == 1)
                                <option selected="" value="{{$sem->id}}">{{$sem->semester}}</option>
                              @else
                                <option value="{{$sem->id}}">{{$sem->semester}}</option>
                              @endif
                            @endforeach
                          </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>  
            </div>
          </div>
          <div class="card-body table-responsive p-0" style="height: 365px; margin-top: -2em">
            <table class="table table-striped">
              <thead class="bg-warning">
                <th>Name</th>
                <th>Due</th>
                <th>Paid</th>
                <th>Balance</th>
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
      <div class="col-md-4">
        <div class="main-card card">
          <div class="card-header text-lg bg-primary">
            Option
          </div>
          <div class="card-body">
            <span class="text-bold">Balance forward to:</span>
            <div class="form-group">
              School Year
              <select id="fsy" name="fsy" class="form-control select2bs4 setup">
                <option value="0">School Year</option>
                @foreach(App\FinanceModel::getSY() as $sy)
                  @if($sy->isactive == 1)
                    <option selected="" value="{{$sy->id}}">{{$sy->sydesc}}</option>
                  @else
                    <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="form-group">
                Semester
                <select id="fsem" name="fsem" class="form-control select2bs4 setup">
                  <option value="0">Semester</option>
                  @foreach(App\FinanceModel::getSem() as $sem)
                    @if($sem->isactive == 1)
                      <option selected="" value="{{$sem->id}}">{{$sem->semester}}</option>
                    @else
                      <option value="{{$sem->id}}">{{$sem->semester}}</option>
                    @endif
                  @endforeach
                </select>
            </div>
            <div class="row">
              <div class="col-12">
                <span class="text-bold">Mode of Payment</span>                
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-12">
                <select id="fmop" class="form-control select2bs4 setup">
                  @foreach(App\FinanceModel::loadMOP() as $mop)
                    <option value="{{$mop->id}}">{{$mop->paymentdesc}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <span class="text-bold">Classification</span>                
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-12">
                <select id="fclass" name="fclass" class="form-control select2bs4 setup">
                  @foreach(App\FinanceModel::loadItemClass() as $class)
                    <option value="{{$class->id}}">{{$class->description}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-4">
                <button id="savesetup" class="btn btn-primary" style="width: 90px !important;"><i class="fas fa-save"></i> Save</button>  
              </div>
              <div class="col-8">
                <button id="listFwdBal" class="btn btn-primary" data-toggle="modal" data-target="#modal-lbf">List of Balance Forwarded </button>  
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
  <div class="modal fade show" id="modal-mbf" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Manual Balance Forwarding</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="particulars" class="col-sm-2 col-form-label">Student</label>
                <div class="col-md-10">
                  <select id="studlist" class="form-control select2bs4">
                  
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
              <span id="studname" class="text-bold">Dhabz Cabrera</span>
            </div>
          </div>
        </div>
        <div class="modal-body" style="height: 380px; overflow-y: auto;">
          <div class="row">
            <div class="col-md-12 table-responsive p-0">
              <table class="table table-striped table-head-fixed">
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

      $('#mop').val('');
      $('#mop').trigger('change');

      loadbalfwdsetup();
      // checksetup();

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
                'Balance successfuly forwarded.',
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

      function checkExist()
      {
        var studid = $('#studlist').val();

        $.ajax({
          url:"{{route('checkExist')}}",
          method:'GET',
          data:{
            studid:studid
          },
          dataType:'',
          success:function(data)
          {
            if(data == 1) 
            {
              $('#modalfwdBal').prop('disabled', true);
            }
            else
            {
              $('#modalfwdBal').prop('disabled', false); 
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

        $('#modal-ledger').modal('show');

        var studid = $(this).attr('data-id');
        var syid = $('#sy').val();
        var semid = $('#sem').val();

        $.ajax({
          url:"{{route('fwdVledger')}}",
          method:'GET',
          data:{
            studid:studid,
            syid:syid,
            semid:semid,
          },
          dataType:'json',
          success:function(data)
          {
            $('#studname').text(data.studname);
            $('#ledger-list').html(data.list);
          }
        });

      });

      $(document).on('click', '#modalfwdBal', function(){
        forwardtype = 1;
        forwardbalance($('#studlist').val(), $('#studlist option:selected').text(), $('#sy').val(), $('#sem').val(), $('#fwdamount').val())
      });

      $(document).on('change', '#studlist', function(){
        checkExist();
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
    });
  </script>
@endsection
