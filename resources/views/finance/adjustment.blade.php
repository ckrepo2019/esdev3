@extends('finance.layouts.app')

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <!-- <h1>Finance</h1> -->
          
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Adjustment</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content pt-0">
  	<div class="main-card card">
  		<div class="card-header bg-info">
        <div class="row">
          <div class="text-lg col-md-4">
            <!-- Fees and Collection     -->
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            <b>ADJUSTMENTS</b></h4>
          </div>
          <div class="col-md-4"></div>
          <div class="col-md-4">
                  
          </div>  
        </div>
        <div class="row">
          <div class="col-md-3">
            
          </div>
          <div class="col-md-2">
            <div class="form-group mb-3">
              <select id="cbosy" class="form-control searchcontrol" data-toggle="tooltip" title="School Year">
                @foreach(App\FinanceModel::getSY() as $sy)
                  @if($sy->isactive == 1)
                    <option value="{{$sy->id}}" selected="">{{$sy->sydesc}}</option>
                  @else
                    <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group mb-3">
              <select id="cbosem" class="form-control searchcontrol" data-toggle="tooltip" title="Semester">
                @foreach(App\FinanceModel::getsem() as $sem)
                  @if($sem->isactive == 1)
                    <option value="{{$sem->id}}" selected="">{{$sem->semester}}</option>
                  @else
                    <option value="{{$sem->id}}">{{$sem->semester}}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group mb-3">
              <input type="date" id="datefrom" class="form-control searchcontrol" data-toggle="tooltip" title="Date From" value="{{date('Y-m-01')}}">
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-group mb-3">
              <input type="date" id="dateto" class="form-control searchcontrol" data-toggle="tooltip" title="Date To" value="{{\Carbon\Carbon::now('Asia/Manila')->toDateString()}}">
            </div>
          </div>
          <div class="col-md-1">
            <button class="btn btn-primary" id="btn-adjnew" data-toggle="tooltip" title="Create Adjustment"><i class="far fa-plus-square"></i> New</button>
          </div>
        </div>
  		</div>
      
  		<div class="card-body table-responsive p-0" style="height:380px">
        <table class="table table-striped">
          <thead class="bg-warning">
            <tr>
              <th>REFERENCE NO.</th>
              <th>DESCRIPTION</th>
              <th>AMOUNT</th>
              <th></th>
              <th></th>
            </tr>  
          </thead> 
          <tbody id="adj-list" style="cursor: pointer">
            
          </tbody>             
        </table>
  		</div>
  	</div>
  </section>
@endsection

@section('modal')
  <div class="modal fade show" id="modal-adjustment" data-backdrop="static" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content" style="margin-top: -28px">
        <div id="modal-adj-header" class="modal-header bg-primary">
          <h4 class="modal-title">Adjustment - <span id="action"></span> <span id="txtreferenceno" class="text-bold text-light">| REFERENCE NO.: <span id="refnum"></span></span></h4> 
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body overflow-auto" style="height: 511px">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header bg-info">
                  FILTER
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3">
                      <label>ACADEMIC PROGRAM</label>
                      <select id="cboacadprog" class="select2bs4 form-control filter-edit">
                        <option value="0" selected="">ALL</option>
                        @foreach(App\FinanceModel::loadAcadProg() as $acadprog)
                          <option value="{{$acadprog->id}}">{{$acadprog->progname}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label>GRADE LEVEL</label>
                      <select id="cbogradelevel" class="select2bs4 form-control filter-edit">
                        <option value="0" selected="">ALL</option>
                        @foreach(App\FinanceModel::loadGlevel() as $glevel)
                          <option value="{{$glevel->id}}">{{$glevel->levelname}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label>GRANTEE</label>
                      <select id="cbograntee" class="select2bs4 form-control filter-edit">
                        <option value="0" selected="">ALL</option>
                        @foreach(App\FinanceModel::loadGrantee() as $grantee)
                          <option value="{{$grantee->id}}">{{$grantee->description}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label>MODALITY OF LEARNING</label>
                      <select id="cbomol" class="select2bs4 form-control filter-edit">
                        <option value="0" selected="">ALL</option>
                        @foreach(App\FinanceModel::loadMOL() as $mol)
                          <option value="{{$mol->id}}">{{$mol->description}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="row mt-1">
                    <div class="col-md-3">
                      <label>STUDENT CLASSIFICATION</label>
                      <select id="cbosc" class="select2bs4 form-control filter-edit">
                        <option value="0" selected="">ALL</option>
                      </select>
                    </div>
                    {{-- <div class="col-md-3">
                      <label>STUDENT</label>
                      <input id="txtstudent" type="text" name="" class="form-control filter-edit" placeholder="LRN|STUD ID|LASTNAME">
                    </div> --}}
                    <div class="col-md-2 mt-4 pt-2">  
                      <button id="btnstudlist" class="btn btn-primary btn-block"> <i class="fa fa-search"></i> Select Student</button>
                    </div>
                    <div class="col-md-3 mt-4 pt-2">  
                      <button id="btnclear" class="btn btn-warning"> <i class="fas fa-minus-circle"></i> Clear Student List</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
          </div>
          <div class="row">
            <div class="col-md-8">
              <div class="card card-widget">
                <div class="card-header bg-primary">
                  <div class="row">
                    <div class="col-md-3">
                      STUDENT LIST
                    </div>
                    <div class="col-md-9 text-right">
                      <span class="badge badge-warning">Count: <span id="studcount">0</span></span>
                    </div>
                  </div>
                </div>
                <div class="card-body table-responsive" style="height: 425px">
                  <table id="studlist" class="table table-striped stud-count" data-count="0" data-header="0">
                    <thead>
                      <tr>
                        <th>LRN</th>
                        <th>NAME</th>
                        <th>GRADE LEVEL</th>
                        <th>GRANTEE</th>
                        
                      </tr>
                    </thead>
                    <tbody id="filter-list">
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-widget">
                <div class="card-header bg-success">
                  ADJUSTMENT 
                </div>
                <div class="card-body">
                  <div id="adjoverlay" class="">
                    
                  </div>
                  <div class="row mb-2">
                    <div class="col-md-12">
                      <div class="icheck-primary d-inline">
                        <input type="radio" id="isdebit" name="r1" checked="">
                        <label for="isdebit" class="is-invalid">
                          Debit
                        </label>
                      </div>
                      &nbsp; &nbsp; &nbsp;
                      <div class="icheck-primary d-inline">
                        <input type="radio" id="iscredit" name="r1">
                        <label for="iscredit">
                          Credit
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <label>Particulars</label>
                      <input type="text" id="txtdescription" class="form-control is-invalid validation">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <label>Classification</label>
                      <select id="cboadjclass" class="select2bs4 form-control is-invalid validation">
                        <option value="0">&nbsp;</option>

                        @php
                          $classification = db::table('itemclassification')
							->select('itemclassification.id', 'itemclassification.description')
                            ->join('chrngsetup', 'itemclassification.id', '=', 'chrngsetup.classid')
                            ->where('itemized', 0)
                            ->where('chrngsetup.deleted', 0)
                            ->where('itemclassification.deleted', 0)
                            ->orderBy('itemclassification.description')
                            ->get();
                        @endphp

                        @foreach($classification as $class)
                          <option value="{{$class->id}}">{{$class->description}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="row mt-1">
                    <div class="col-md-12">
                      <label>Amount</label>
                      <input type="text" class="form-control val-item is-invalid validation" name="currency-field" id="txtamount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency">
                    </div>
                  </div>
                  <div class="row mt-1">
                    <div id="divMOP" class="col-md-12" hidden="">
                      <label>Mode of Payment</label>
                      <select id="cbomop" class="form-control select2bs4 is-invalid validation">
                        <option value="0"></option>
                        @foreach(App\FinanceModel::loadMOP() as $mop)
                          <option value="{{$mop->id}}">{{$mop->paymentdesc}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-body bg-light">
          <div class="row">
            <div class="col-md-6">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
            </div>
            
            <div class="col-md-6 text-right">
              <button id="disapproveADJ" type="button" class="btn btn-danger"><i class="fas fa-thumbs-down"></i></i> Disapprove</button>  
              <button id="approveADJ" type="button" class="btn btn-success">
                <span id="adjspinner" class="spinner-grow spinner-grow-sm" hidden="" role="status" aria-hidden="true"></span>
                <i class="fas fa-thumbs-up"></i> Approve
              </button>  
              <button id="deleteADJ" type="button" class="btn btn-secondary" data-dismiss="modal"><i class="far fa-trash-alt"></i> Delete</button>  
              <button id="saveADJ" type="button" class="btn btn-primary" data-dismiss="modal"><i class="fas fa-save"></i> Save</button>  
            </div>
            
            
            
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-selstudent" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header bg-secondary">
              <h4 class="modal-title">Select Student</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">                  
                  <select id="cbostudlist" class="select2bs4 form-control">
                    <option value="0">Select Student</option>
                    @php
                      $studlist = db::table('studinfo')
                        ->select('studinfo.*', 'levelname')
                        ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                        ->where('studstatus', '!=', 0)
                        ->where('studinfo.deleted', 0)
                        ->orderBy('lastname', 'ASC')
                        ->orderBy('firstname', 'ASC')
                        ->get();
                    @endphp
                    @foreach($studlist as $stud)
                      {{$name = $stud->lastname . ', ' . $stud->firstname . ' ' . $stud->middlename . ' ' . $stud->suffix . ' - ' . $stud->levelname}}
                      <option value="{{$stud->id}}">{{$name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" id="btnaddstudent" class="btn btn-primary">Add Student</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>


  
@endsection

@section('js')

  <script>
    // Jquery Dependency

  $("input[data-type='currency']").on({
      keyup: function() {
        formatCurrency($(this));
      },
      blur: function() { 
        formatCurrency($(this), "blur");
      }
  });


  function formatNumber(n) {
    // format number 1000000 to 1,234,567
    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
  }


  function formatCurrency(input, blur) {
    // appends $ to value, validates decimal side
    // and puts cursor back in right position.
    
    // get input value
    var input_val = input.val();
    
    // don't validate empty input
    if (input_val === "") { return; }
    
    // original length
    var original_len = input_val.length;

    // initial caret position 
    var caret_pos = input.prop("selectionStart");
      
    // check for decimal
    if (input_val.indexOf(".") >= 0) {

      // get position of first decimal
      // this prevents multiple decimals from
      // being entered
      var decimal_pos = input_val.indexOf(".");

      // split number by decimal point
      var left_side = input_val.substring(0, decimal_pos);
      var right_side = input_val.substring(decimal_pos);

      // add commas to left side of number
      left_side = formatNumber(left_side);

      // validate right side
      right_side = formatNumber(right_side);
      
      // On blur make sure 2 numbers after decimal
      if (blur === "blur") {
        right_side += "00";
      }
      
      // Limit decimal to only 2 digits
      right_side = right_side.substring(0, 2);

      // join number by .
      input_val = left_side + "." + right_side;

    } else {
      // no decimal entered
      // add commas to number
      // remove all non-digits
      input_val = formatNumber(input_val);
      input_val = input_val;
      
      // final formatting
      if (blur === "blur") {
        input_val += ".00";
      }
    }
    
    // send updated string to input
    input.val(input_val);

    // put caret back in the right position
    var updated_len = input_val.length;
    caret_pos = updated_len - original_len + caret_pos;
    input[0].setSelectionRange(caret_pos, caret_pos);
  }

  function forceKeyPressUppercase(e)
  {
    var charInput = e.keyCode;
    if((charInput >= 97) && (charInput <= 122)) { // lowercase
      if(!e.ctrlKey && !e.metaKey && !e.altKey) { // no modifier key
        var newChar = charInput - 32;
        var start = e.target.selectionStart;
        var end = e.target.selectionEnd;
        e.target.value = e.target.value.substring(0, start) + String.fromCharCode(newChar) + e.target.value.substring(end);
        e.target.setSelectionRange(start+1, start+1);
        e.preventDefault();
      }
    }
  }

  // document.getElementById("txtstudent").addEventListener("keypress", forceKeyPressUppercase, false);
  document.getElementById("txtdescription").addEventListener("keypress", forceKeyPressUppercase, false);



  </script>
  <style type="text/css">
    .cursor-pointer{
      cursor: pointer;

    }

    .Div-hide{
      display: none !important;
    }

    .Div-show{
      display: block;
    }
  </style>


  <script type="text/javascript">
    
    var studlistarray;
    $(document).ready(function(){
        
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      });

    
      $(document).on('click', '#btn-adjnew', function(){
        $('#action').text('New');
        
        $('#approveADJ').hide();
        $('#disapproveADJ').hide();
        $('#saveADJ').show();

        $('#cboacadprog').val(0);
        $('#cboacadprog').trigger('change');

        $('#cbogradelevel').val(0);
        $('#cbogradelevel').trigger('change');

        $('#cbograntee').val(0);
        $('#cbograntee').trigger('change');

        $('#cbomol').val(0);
        $('#cbomol').trigger('change');

        $('#cbosc').val(0);
        $('#cbosc').trigger('change');

        $('#txtstudent').val('');

        $('#txtdescription').val('');
        $('#txtdescription').trigger('keyup');

        $('#cboadjclass').val(0);
        $('#cboadjclass').trigger('change');
        $("#cboadjclass").trigger('select2:close');

        $('#txtamount').val('');
        $('#txtamount').trigger('keyup');

        $('#cbomop').val(0);
        $('#cbomop').trigger('change');
        $("#cbomop").trigger('select2:close');

        $('#filter-list').empty();

        $('#studcount').text(0);

        $('#txtreferenceno').hide();

        $('.filter-edit').addClass('filter');
        $('.filter-edit').removeClass('filter-edit');
        $('.filter').prop('disabled', false);

        $('#adjoverlay').removeClass('overlay');

        $('#studlist').attr('data-header', 0);


        
        $('#modal-adjustment').modal('show');
        setTimeout(function(){
          $('#filter-list').empty();
          $('#btnsearch').show();  
          $('#studcount').text(0);
        }, 2000)

      });

      $(document).on('change', '#cboacadprog', function(){
        var acadprog = $(this).val();

        $.ajax({
          url:"{{route('adjloadglevel')}}",
          method:'GET',
          beforeSend: function(){
            $('#cbogradelevel').prop('disabled', true);
          },
          data:{
            acadprog:acadprog
          },
          dataType:'json',
          success:function(data)
          {
            if($('#cbogradelevel').hasClass('filter-edit'))
            {
              $('#cbogradelevel').prop('disabled', true);
            }
            else
            {
              $('#cbogradelevel').prop('disabled', false);  
            }
            
            $('#cbogradelevel').html(data.list);

            setTimeout(function(){
              $('#cbogradelevel').trigger('change')
            }, 500)
          }
        });  
      });

      checkcredit();
 
      function searchfilter()
      {
        var levelid = $('#cbogradelevel').val();
        var grantee = $('#cbograntee').val();
        var mol = $('#cbomol').val();
        var sc = $('#sc').val();
        var stud = $('#txtstudent').val();
        var acadprog = $('#cboacadprog').val()

        $.ajax({
          url:"{{route('adjfilter')}}",
          method:'GET',
          data:{
            levelid:levelid,
            grantee:grantee,
            mol:mol,
            sc:sc,
            stud:stud,
            acadprog:acadprog
          },
          dataType:'json',
          success:function(data)
          {
            $('#filter-list').html(data.list);
            $('.stud-count').attr('data-count', data.studcount);
            $('#studcount').text(data.studcount);
            // studlistarray = data.studlistarray;
          }
        });  
      }


      searchADJ();
      function searchADJ()  
      {
        
        var syid = $('#cbosy').val();
        var semid = $('#cbosem').val();
        var datefrom = $('#datefrom').val();
        var dateto = $('#dateto').val();

        $.ajax({
          url:"{{route('searchadj')}}",
          method:'GET',
          data:{
            syid:syid,
            semid:semid,
            datefrom:datefrom,
            dateto:dateto
          },
          dataType:'json',
          success:function(data)
          {
            $('#adj-list').html(data.list);
          }
        });   
      }

      $(document).on('change', '.filter', function(){
        searchfilter();
      });

      $(document).on('click', '#btnsearch', function(){
        searchfilter();
      });

      function validation()
      {
        var vcount = 0;

        $('.validation').each(function(){
          if($(this).hasClass('is-invalid'))
          {
            vcount += 1;
            console.log($(this).attr('id'));
          }

          if(vcount == 0 && $('#studlist').attr('data-count') != 0)
          {
            $('#saveADJ').prop('disabled', false);
          }
          else
          {
            $('#saveADJ').prop('disabled', true); 
          } 
        });
      }

      validation();

      $(document).on('keyup', '.validation', function(){
        validation();
      });

      $(document).on('keyup', '#txtdescription', function(){
        if($(this).val() == '' || $(this).val() == 0)
        {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid')
        }
        else
        {
          $(this).removeClass('is-invalid');
          $(this).addClass('is-valid');
        }

        validation();
      });

      $(document).on('select2:close', '#cboadjclass', function(){
        if($(this).val() == '' || $(this).val() == 0)
        {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid')
        }
        else
        {
          $(this).removeClass('is-invalid');
          $(this).addClass('is-valid');
        }

        validation();
      });

      $(document).on('keyup', '#txtamount', function(){
        if($(this).val() == '' || $(this).val() == 0)
        {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid')
        }
        else
        {
          $(this).removeClass('is-invalid');
          $(this).addClass('is-valid');
        }

        validation();
      });

      $(document).on('select2:close', '#cbomop', function(){
        if($(this).val() == '' || $(this).val() == 0)
        {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid')
        }
        else
        {
          $(this).removeClass('is-invalid');
          $(this).addClass('is-valid');
        }

        validation();
      });

      $(document).on('click', '#saveADJ', function(){
        var levelid = $('#cbogradelevel').val();
        var acadprog = $('#cboacadprog').val();
        var grantee = $('#cbograntee').val();
        var mol = $('#cbomol').val();
        var studclass = $('#cbosc').val();
        var studfilter = $('#txtstudent').val();

        var adjdesc = $('#txtdescription').val();
        var classid = $('#cboadjclass').val();
        var amount = $('#txtamount').val();
        var mop = $('#cbomop').val();
        var dataheader = $('#studlist').attr('data-header');

        if($('#isdebit').prop('checked') == true)
        {
          var isdebit = 1;
          var iscredit = 0;
        }
        else
        {
          var isdebit = 0;
          var iscredit = 1
        }

        $('#filter-list tr').each(function(){
          var dataheader = $('#studlist').attr('data-header');
          var studid = $(this).attr('data-id');
          console.log(dataheader);

          if(dataheader == 0)
          {
            $.ajax({
              async:false,
              url:"{{route('appendADJ')}}",
              method:'GET',
              data:{
                levelid:levelid,
                grantee:grantee,
                mol:mol,
                sc:studclass,
                studid:studid,
                acadprog:acadprog,
                adjdesc:adjdesc,
                classid:classid,
                amount:amount,
                mop:mop,
                isdebit:isdebit,
                iscredit:iscredit,
                dataheader:dataheader
              },
              dataType:'',
              success:function(data)
              {
                console.log(data);
                $('#studlist').attr('data-header', data);
              }
            });
          }
          else
          {
            $.ajax({
              url:"{{route('appendADJ')}}",
              method:'GET',
              data:{
                studid:studid,
                dataheader:dataheader
              },
              dataType:'',
              success:function(data)
              {
                console.log(data);
                $('#studlist').attr('data-header', data);
              }
            });
          }
          searchADJ();
        });


        // $.ajax({
        {{-- //   url:"{{route('appendADJ')}}", --}}
        //   method:'GET',
        //   data:{
        //     levelid:levelid,
        //     grantee:grantee,
        //     mol:mol,
        //     sc:studclass,
        //     stud:studfilter,
        //     acadprog:acadprog,
        //     studlistarray:studlistarray,
        //     adjdesc:adjdesc,
        //     classid:classid,
        //     amount:amount,
        //     mop:mop,
        //     isdebit:isdebit,
        //     iscredit:iscredit
        //   },
        //   dataType:'',
        //   success:function(data)
        //   {

        //     const Toast = Swal.mixin({
        //       toast: true,
        //       position: 'top-end',
        //       showConfirmButton: false,
        //       timer: 3000,
        //       timerProgressBar: false,
        //       onOpen: (toast) => {
        //         toast.addEventListener('mouseenter', Swal.stopTimer)
        //         toast.addEventListener('mouseleave', Swal.resumeTimer)
        //       }
        //     })

        //     Toast.fire({
        //       type: 'success',
        //       title: 'Adjustment Saved.'
        //     })

        //     $('#modal-adjustment').modal('hide');
        //     searchADJ();
        //   }
        // });
      });

      $(document).on('change', '.searchcontrol', function(){
        searchADJ();
      });

      $(document).on('mouseenter', '#adj-list tr', function(){
        $(this).addClass('bg-secondary');
      });

      $(document).on('mouseout', '#adj-list tr', function(){
        $(this).removeClass('bg-secondary');
      });

      $(document).on('click', '#adj-list tr', function(){
        $('#action').text('View');
        $('#btnsearch').hide();
        $('.filter').addClass('filter-edit');
        $('.filter').removeClass('filter');
        $('.filter-edit').prop('disabled', true);
        $('#saveADJ').hide();
        $('#modal-adjustment').modal('show');
        $('#txtreferenceno').show();
        


        var adjid = $(this).attr('data-id');

        $.ajax({
          url:"{{route('viewadj')}}",
          method:'GET',
          data:{
            adjid:adjid
          },
          dataType:'json',
          success:function(data)
          {

            if(data.adjstatus == 'SUBMITTED')
            {
              $('#approveADJ').show();
              $('#disapproveADJ').show();
              $('#deleteADJ').show();
              $('#modal-adj-header').removeClass('bg-success bg-danger');
              $('#modal-adj-header').addClass('bg-primary');
              $('#adjoverlay').removeClass('overlay');              
            }
            else if(data.adjstatus == 'APPROVED')
            {
              $('#approveADJ').hide();
              $('#disapproveADJ').hide(); 
              $('#deleteADJ').hide();
              $('#modal-adj-header').removeClass('bg-primary bg-danger');
              $('#modal-adj-header').addClass('bg-success');
              $('#adjoverlay').addClass('overlay');
            }
            else
            {
              $('#approveADJ').hide();
              $('#disapproveADJ').hide(); 
              $('#deleteADJ').hide();
              $('#modal-adj-header').removeClass('bg-primary bg-success');
              $('#modal-adj-header').addClass('bg-danger');
              $('#adjoverlay').addClass('overlay');
            }





            $('#filter-list').html(data.studlist); 
            $('#studcount').text(data.studcount);

            $('#cboacadprog').val(data.acadprog);
            $('#cboacadprog').trigger('change');

            $('#cbogradelevel').val(data.levelid);
            $('#cbogradelevel').trigger('change');

            $('#cbograntee').val(data.grantee);
            $('#cbograntee').trigger('change');

            $('#cbomol').val(data.mol);
            $('#cbomol').trigger('change');

            $('#cbosc').val(data.studclass);
            $('#cbosc').trigger('change');

            $('#txtstudent').val(data.studfilter);

            $('#refnum').text(data.refnum);

            $('#txtdescription').val(data.description);
            $('#txtdescription').trigger('keyup');

            $('#cboadjclass').val(data.classid)
            $('#cboadjclass').trigger('change');
            $('#cboadjclass').trigger('select2:close');

            $('#txtamount').val(data.amount);
            $('#txtamount').trigger('keyup');

            console.log(data.mop);

            $('#cbomop').val(data.mop);
            $('#cbomop').trigger('change');
            $('#cbomop').trigger('select2:close');

            $('#approveADJ').attr('data-id', data.adjid);

            if(data.isdebit == 1)
            {
              $('#isdebit').prop('checked', true);
            }
            else
            {
              $('#iscredit').prop('checked', true);
            }



            checkcredit();
          }
        });   
      });
      
      $(document).on('click', '#approveADJ', function(){
        
        var adjid = $(this).attr('data-id');
        var description = $('#txtdescription').val();
        var classid = $('#cboadjclass').val();
        var amount = $('#txtamount').val();
        var mop = $('#cbomop').val();

        if($('#iscredit').prop('checked') == true)
        {
          var iscredit = 1;
          var isdebit = 0;
        }
        else
        {
          var iscredit = 0;
          var isdebit = 1; 
        }

        Swal.fire({
          title: 'Approve Adjustment?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Approve!'
        }).then((result) => {
          if (result.value) {
            
            $.ajax({
              url:"{{route('approveADJ')}}",
              method:'GET',
              data:{
                adjid:adjid,
                description:description,
                classid:classid,
                amount:amount,
                mop:mop,
                iscredit:iscredit,
                isdebit:isdebit
              },
              beforeSend:function(){
                $('#adjoverlay').addClass('overlay');
                $('#approveADJ i').hide();
                $('#adjspinner').prop('hidden', false);
                $('#approveADJ').prop('disabled', true);
                $('#disapproveADJ').prop('disabled', true);

                $(window).bind('beforeunload',function(){
                    return 'are you sure you want to leave?';    
                });
              },
              dataType:'json',
              success:function(data)
              {
                
                $('#approveADJ i').show();
                $('#adjspinner').prop('hidden', true);
                $('#adjoverlay').removeClass('overlay');
                $('#approveADJ').prop('disabled', false);
                $('#disapproveADJ').prop('disabled', false);

                $(window).unbind('beforeunload');

                if(data != 0)    
                {
                  Swal.fire(
                    'Success!',
                    'Adjustment has been approved.',
                    'success'
                  );
                  searchADJ();
                  $('#modal-adjustment').modal('hide');
                }
                else
                {
                  Swal.fire(
                    'Error',
                    'Something went wrong. Please check Adjustment data.',
                    'error'
                  ); 
                }
                  
              }
            });   
          }
        });  
      });

      $(document).on('click', '#disapproveADJ', function(){
        var adjid = $('#approveADJ').attr('data-id');
        Swal.fire({
          title: 'Disapprove Adjustment?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Disapprove!'
        }).then((result) => {
          if (result.value) {
            
            $.ajax({
              url:"{{route('disapproveADJ')}}",
              method:'GET',
              data:{
                adjid:adjid
              },
              beforeSend:function(){
                $('#adjoverlay').addClass('overlay');
                $('#approveADJ i').hide();
                $('#adjspinner').prop('hidden', false);
                $('#approveADJ').prop('disabled', true);
                $('#disapproveADJ').prop('disabled', true);

                $(window).bind('beforeunload',function(){
                    return 'are you sure you want to leave?';    
                });
              },
              dataType:'json',
              success:function(data)
              {
                
                $('#approveADJ i').show();
                $('#adjspinner').prop('hidden', true);
                $('#adjoverlay').removeClass('overlay');
                $('#approveADJ').prop('disabled', false);
                $('#disapproveADJ').prop('disabled', false);

                $(window).unbind('beforeunload');

                if(data != 0)    
                {
                  Swal.fire(
                    'Success!',
                    'Adjustment has been disapproved.',
                    'success'
                  );
                  searchADJ();
                  $('#modal-adjustment').modal('hide');
                }
                else
                {
                  Swal.fire(
                    'Error',
                    'Something went wrong. Please check Adjustment data.',
                    'error'
                  ); 
                }
                  
              }
            });   
          }
        });
      });

      function checkcredit()
      {
        if($('#iscredit').prop('checked') == true)
        {
          $('#divMOP').prop('hidden', true);
          $('#cbomop').removeClass('validation');
        }
        else
        {
          $('#divMOP').prop('hidden', false);
          $('#cbomop').addClass('validation');
        }
      }

      $(document).on('click', '#iscredit', function(){
        checkcredit();
        validation();

        $.ajax({
          url: '{{route('adj_loadclass')}}',
          type: 'GET',
          data: {
            adjtype:'credit'
          },
          success:function(data)
          {
            $('#cboadjclass').html(data);
          }
        });
      });

      $(document).on('click', '#isdebit', function(){
        checkcredit();
        validation();

        $.ajax({
          url: '{{route('adj_loadclass')}}',
          type: 'GET',
          data: {
            adjtype:'debit'
          },
          success:function(data)
          {
            $('#cboadjclass').html(data);
          }
        });
        
      });

      $(document).on('click', '#deleteADJ', function(){
        var adjid = $('#approveADJ').attr('data-id');
        Swal.fire({
          title: 'Delete Adjustment?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Delete!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url:"{{route('deleteADJ')}}",
              method:'GET',
              data:{
                adjid:adjid
              },
              dataType:'json',
              success:function(data)
              {
                if(data != 0)    
                {
                  Swal.fire(
                    'Success!',
                    'Adjustment has been deleted.',
                    'success'
                  );
                  searchADJ();
                  $('#modal-adjustment').modal('hide');
                }
                else
                {
                  Swal.fire(
                    'Error',
                    'Something went wrong. Please check Adjustment data.',
                    'error'
                  ); 
                }
                  
              }
            });   
          }
        });
      });

      $(document).on('click', '#btnstudlist', function(){
        $('#modal-selstudent').modal('show');
      });

      $(document).on('click', '#btnaddstudent', function(){
        var studid = $('#cbostudlist').val();
        $.ajax({
          url:"{{route('seladdstud')}}",
          method:'GET',
          data:{
            studid:studid
          },
          dataType:'json',
          success:function(data)
          {
            $('#studlist > tbody:last-child').append(data.list);
            $('#studlist').attr('data-count', $('#filter-list tr').length);
            $('#studcount').text($('#filter-list tr').length);
            $('.stud-count').attr('data-id', $('#filter-list tr').length);
            validation();

            $('#modal-selstudent').modal('hide');
          }
        });
      });

      $(document).on('click', '.btn-del', function(){

        var dataid = $(this).attr('data-id');
        console.log(dataid);

        $('#filter-list tr').each(function(){
          if($(this).attr('data-id') == dataid)
          {
            $(this).remove();
            $('#studcount').text($('#filter-list tr').length);
            $('.stud-count').attr('data-id', $('#filter-list tr').length);
          }
        });
      });

      $(document).on('click', '#btnclear', function(){
        Swal.fire({
          title: 'Clear Student List?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Clear!'
        }).then((result) => {
          if (result.value) {
            $('#filter-list tr').remove();
            validation();
          }
        });
      })




    });

  </script>
@endsection













{{-- $(document).on('click', '#saveADJ', function(){
        var levelid = $('#cbogradelevel').val();
        var acadprog = $('#cboacadprog').val();
        var grantee = $('#cbograntee').val();
        var mol = $('#cbomol').val();
        var studclass = $('#cbosc').val();
        var studfilter = $('#txtstudent').val();

        var adjdesc = $('#txtdescription').val();
        var classid = $('#cboadjclass').val();
        var amount = $('#txtamount').val();
        var mop = $('#cbomop').val();

        if($('#isdebit').prop('checked') == true)
        {
          var isdebit = 1;
          var iscredit = 0;
        }
        else
        {
          var isdebit = 0;
          var iscredit = 1
        }



        $.ajax({
          url:"{{route('appendADJ')}}",
          method:'GET',
          data:{
            levelid:levelid,
            grantee:grantee,
            mol:mol,
            sc:studclass,
            stud:studfilter,
            acadprog:acadprog,
            studlistarray:studlistarray,
            adjdesc:adjdesc,
            classid:classid,
            amount:amount,
            mop:mop,
            isdebit:isdebit,
            iscredit:iscredit
          },
          dataType:'',
          success:function(data)
          {

            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000,
              timerProgressBar: false,
              onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            })

            Toast.fire({
              type: 'success',
              title: 'Adjustment Saved.'
            })

            $('#modal-adjustment').modal('hide');
            searchADJ();
          }
        });
      }); --}}