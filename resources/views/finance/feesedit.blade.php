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
            <li class="breadcrumb-item"><a href="/finance/fees">Fees and Collection</a></li>
            <li class="breadcrumb-item active">Edit</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
    <div class="container-fluid">
    	<div class="card card-default">
    		<div class="card-header text-lg bg-info">
    			<!-- Fees and Collection - Edit -->
          <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            <b>FEES AND COLLECTION - EDIT</b></h4>
          <div class="float-right">
            <span id="saveFC" class="btn btn-primary" data-id="{{$headerid}}">Save</span>
            <button class="btn btn-danger" onclick="window.location.href='/finance/fees'">Cancel</button>  
          </div>
          
    		</div>
    		<div class="card-body">
          
          <form role="form">
            <div class="card-body">
              <div class="row">
                <div class="col-3">
                  <div class="form-group">
                    <label for="txtdesc">Description</label>
                    <input type="text" class="form-control is-invalid is-val" id="txtdesc" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                  </div>    
                </div>

                <div class="col-3">
                  <div class="form-group">
                    <label for="txtnopay">Grade level</label>
                    <select class="form-control is-invalid is-val" id="cboglevel">
                      <option></option>
                      @foreach($glevel as $level)
                        <option value="{{$level->id}}">{{($level->levelname)}}</option>
                      @endforeach
                    </select>
                  </div>    
                </div>

                <div class="col-2">
                  <div class="form-group">
                    <label for="txtnopay">Semester</label>
                    <select class="form-control" id="cbosem">
                      @foreach($semester as $sem)
                        @if($sem->isactive == 1)
                          <option selected="" value="{{$sem->id}}">{{($sem->semester)}}</option>
                        @else
                          <option value="{{$sem->id}}">{{($sem->semester)}}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>    
                </div>

                <div class="col-2">
                  <div class="form-group">
                    <label for="txtnopay">School Year</label>
                    <select class="form-control" id="cbosy">
                      @foreach($schoolyear as $sy)
                        <option value="{{$sy->id}}">{{($sy->sydesc)}}</option>
                      @endforeach
                    </select>
                  </div>    
                </div>
                

                <div class="col-2">
                  <div class="form-group">
                    <label for="txtnopay">Grantee</label>
                    <select class="form-control" id="grantee">
                      <option value="1">REGULAR</option>
                      <option value="2">ESC</option>
                      <option value="3">VOUCHER</option>
                    </select>
                  </div>    
                </div>
              </div>
              <div class="row">
                <div class="col-3 mt-1">
                  <div class="form-group">
                    <label class="strand-ui" hidden="">Strand <i>(For Senior High Only)</i></label>
                    <select id="strand" hidden="" class="form-control strand-ui">
                      <option value="0"></option>
                      @foreach(App\FinanceModel::strandlist() as $strand)
                        <option value="{{$strand->id}}">{{$strand->strandcode}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>           
              </div>
              <div class="row course-ui">
                <div class="col-6 mt-1">
                  <div class="form-group">
                    <label class="">Course</i></label>
                    <select id="course" class="form-control select2bs4">
                      <option value="0"></option>
                      @foreach(App\FinanceModel::loadCourses() as $course)
                        <option value="{{$course->id}}" data-value="{{$course->courseabrv}}">{{$course->courseDesc}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>           
              </div>
          </form>

        </div>
    </div>
    	</div>

    <div class="row basic-ed">

      <div class="col-6">
        <div class="card card-info">
          <div class="card-header text-lg bg-primary">
            Payment Classification
            <div class="float-right">
              <button class="btn btn-primary" id="addClassification" data-toggle="modal" data-target="#modal-class-new" data-id="0">
                <i class="fas fa-plus"></i>
              </button>  
            </div>
          </div>
          <div class="card-body">
            <div class="table-responisve">
              <table class="table table-striped table-success" style="cursor: pointer;">
                <thead>
                  <tr class="bg-olive">
                    <th>DESCRIPTION</th>
                    <th>PAYMENT SCHEME</th>
                    <th class="text-center">AMOUNT</th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="payclass-list">
                  
                </tbody>
                <tfoot>
                  <tr>
                    <td id="payTotal" colspan="3" class="text-bold text-right">TOTAL: 0.00</td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-6">
        <div class="card card-prima">
          <div class="card-header text-lg bg-success">
            ITEMS <span id="payDesc"></span> 
            <div class="float-right">
              <button class="btn btn-primary" id="additem" data-toggle="modal" data-target="#modal-item-new" disabled="">
                <i class="fas fa-plus"></i>
              </button>  
            </div>
          </div>
          <div class="card-body">
             <div class="table-responisve">
              <table class="table table-striped">
                <thead class="bg-warning">
                  <tr class="">
                    <th>DESCRIPTION</th>
                    <th class="text-center">AMOUNT</th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="item-list">
                  
                </tbody>
                <tfoot>
                  <tr>
                    <td class="text-right">
                      
                    </td>
                    <td id="itemTotal" class="text-bold text-right">TOTAL: 0.00</td>
                    <td></td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row college-ed">
      <div class="col-md-7">
        <div class="card">
          <div class="card-header bg-primary">
            COLLEGE PAYMENT CLASSIFICATION
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <button id="coladdclass" class="btn btn-primary">ADD CLASSIFICATION</button>
              </div>
            </div>
            <div class="row mt-1">
              <div class="col-md-12 table-responisve">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <tH>DESCRIPTION</th>
                      <th>MODE OF PAYMENT</th>
                      <th>AMOUNT</th>
                    </tr>
                  </thead>
                  <tbody id="col-classlist" style="cursor: pointer;">
                    
                  </tbody>
                  <tfoot id="col-classlist-foot">
                    
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-5" hidden>
        <div class="card">
          <div class="card-header bg-success">
            LABORATORY FEES
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <button class="btn btn-primary">ADD SUBJECT</button>
              </div>
            </div>
            <div class="row mt-1">
              <div class="col-md-12 table-responisve">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>SUBJECT</th>
                      <th>AMOUNT</th>
                    </tr>
                  </thead>
                  <tbody id="labsubjlist">
                    <tr>
                      <td>COMPUTER PROGRAMMING 1</td>
                      <td>1,360.00</td>
                    </tr>
                    <tr>
                      <td>CHEMISTRY 1</td>
                      
                      <td>952.00</td>
                    </tr>
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

  <div class="modal fade show" id="modal-class-new" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Payment Classification - Add</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="modal-classification" class="col-sm-4 col-form-label">Classification</label>
                <div class="col-sm-8">
                  <select id="modal-classification" class="form-control">
                    
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="modal-classification" class="col-sm-4 col-form-label">Mode of payment</label>
                <div class="col-sm-8">
                  <select id="modal-mop" class="form-control">
                    
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="modal-classification" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-8">
                  <div class="icheck-primary d-inline">
                    <input type="checkbox" id="isDP-new" >
                    <label for="isDP-new">
                      Downpayment
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
          <button id="savePayClass" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-item-new" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Items - Add</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="modal-classification" class="col-sm-4 col-form-label">Items</label>
                <div class="col-sm-8">
                  <select id="modal-items" class="form-control select2bs4">
                    
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="modal-classification" class="col-sm-4 col-form-label">Amount</label>
                <div class="col-sm-8">
                  {{-- <input id="txtamount" class="form-control" type="text" disabled=""> --}}

                  <input type="text" class="form-control" name="currency-field" id="txtamount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency">


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

  <div class="modal fade show" id="modal-pay-edit" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Payment Classification - Edit</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="modal-classification" class="col-sm-4 col-form-label">Classification</label>
                <div class="col-sm-8">
                  <select id="modal-classification-edit" class="form-control">
                    
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="modal-classification" class="col-sm-4 col-form-label">Mode of payment</label>
                <div class="col-sm-8">
                  <select id="modal-mop-edit" class="form-control">
                    
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="modal-classification" class="col-sm-4 col-form-label"></label>
                <div class="col-sm-8">
                  <div class="icheck-primary d-inline">
                    <input type="checkbox" id="isDP-edit" >
                    <label for="isDP-edit">
                      Downpayment
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
          <button id="updatePayClass" type="button" class="btn btn-primary" data-dismiss="modal">Update</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>




  <div class="modal fade show" id="modal-item-edit" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Items - Edit</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="modal-classification" class="col-sm-4 col-form-label">Items</label>
                <div class="col-sm-8">
                  <select id="modal-items-edit" class="form-control select2bs4">
                    
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label for="modal-classification" class="col-sm-4 col-form-label">Amount</label>
                <div class="col-sm-8">

                  <input type="text" class="form-control" name="currency-field" id="txtamount-edit" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency">


                </div>
              </div>
          
              
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="updateItem" type="button" class="btn btn-primary" data-dismiss="modal">Update</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-col-class" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Payment Classification - Add</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal">
            <div class="card-body">
              <div class="form-group row">
                <label for="col-classification" class="col-sm-4 col-form-label">Classification</label>
                <div class="col-sm-8">
                  <select id="col-classification" class="form-control select2bs4">
                    
                  </select>
                </div>
                
              </div>

              <div class="form-group row">
                <label for="" class="col-sm-4 col-form-label">Mode of payment</label>
                <div class="col-sm-8">
                  <select id="col-mop" class="form-control">
                    
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-4">    
              </div>
              <div class="icheck-primary d-inline ml-3">
                <input type="checkbox" id="istuition">
                <label for="istuition">
                  Per Unit
                </label>
              </div>
            </div>
            <div class="row">
                <div class="col-md-12 table-responisve overflow-auto" style="height: 243px">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>DESCRIPTION</th>
                        <th>AMOUNT</th>
                      </tr>
                    </thead>
                    <tbody id="col-payclass-list" style="cursor: pointer;">
                      
                    </tbody>
                    <tfoot>
                      <tr>
                        <td><span id="col-add-item" class="text-primary cursor-pointer"><u>Add Item</u></span></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
          </form>
        </div>
        <div class="">
          <div class="row">
            <div class="col-md-8">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
            </div>
            <div class="col-md-2">
              <button id="col-delPayClass" type="button" class="btn btn-danger btn-block" data-id="0">Delete</button>  
            </div>
            <div class="col-md-2">
              <button id="col-savePayClass" type="button" class="btn btn-primary btn-block" data-id="0" data-dismiss="modal">Save</button>  
            </div>
          </div>
        </div>
        {{-- <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="col-savePayClass" type="button" class="btn btn-primary" data-id="0" data-dismiss="modal">Save</button>
        </div> --}}
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show mt-5" id="modal-payitem" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Items - <span id="col-item-action">ADD</span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body">
            <div class="row">
              <div class="form-group col-md-11">
                <label for="modal-classification" class="col-form-label">Items</label>
                <div class="">
                  <select id="fc-item" class="form-control select2bs4">
                    
                  </select>
                </div>
              </div>
              <div class="form-group col-md-1 mt-1">
                <label for="fc-btnadditem" class="col-form-label">&nbsp;</label>
                <button id="fc-btnadditem" class="btn btn-primary btn-sm" data-toggle="tooltip" title="Add new item">
                  <i class="fas fa-external-link-square-alt"></i>
                </button>
              </div>
            </div>

            <div class="row">
              <div class="form-group col-md-12">
                <label for="modal-classification" class="col-form-label">Amount</label>
                <div class="">
                  <input type="text" class="form-control" name="currency-field" id="fc-txtamount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency">
                </div>
              </div> 
            </div>
        
            
          </div>
          <hr>
          <div class="row">
            <div class="col-md-8">
              <button id="close_modal-payitem" type="button" class="btn btn-default">Close</button>  
            </div>
            <div class="col-md-2">
              <button id="fc-deleteItem" type="button" class="btn btn-danger btn-block" data-id=0>Delete</button>  
            </div>
            <div class="col-md-2">
              <button id="fc-appendItem" type="button" class="btn btn-primary btn-block" data-id=0>Save</button>  
            </div>
          </div>
        </div>

        {{-- <div class="modal-footer justify-content-between">
          <button id="close_modal-payitem" type="button" class="btn btn-default">Close</button>
          <button id="fc-appendItem" type="button" class="btn btn-primary" data-id=0>Save</button>
        </div> --}}
      </div>
    </div> {{-- dialog --}}
  </div>

@endsection


@section('js')
  <script type="text/javascript">
    
    $(document).ready(function(){
      var val = 0;

      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      loadEditVal();
      shUI($('#cboglevel').val());
      colUI($('#glevel').val());
      FCClasList($('#saveFC').attr('data-id'))

      colValidation();

      function shUI(levelid)
      {
        if(levelid == 14 || levelid == 15)
        {
          $('.strand-ui').prop('hidden', false)
        }
        else
        {
          $('.strand-ui').prop('hidden', true) 
        }
      }

      function colUI(levelid)
      {
        if(levelid >= 17 && levelid <= 20)
        {
          $('.course-ui').show();
          $('.basic-ed').hide();
          $('.college-ed').show();
        }
        else
        {
          $('.course-ui').hide(); 
          $('.basic-ed').show();
          $('.college-ed').hide();
        }
      }


      function validation()
      {

        if($('#txtdesc').val() != '' && $('#cboglevel').val() != '')
        {
          $('#addClassification').prop('disabled', false);
        }
        else
        {
          $('#addClassification').prop('disabled', true);  
        }

        if($('#cboglevel').val() == 14 || $(cboglevel).val() == 15)
        {
          $('#cbosem').prop('disabled', false);
        }
        else if($('#cboglevel').val() >= 17 && $(cboglevel).val() <= 20)
        {
          $('#cbosem').prop('disabled', false); 
        }
        else
        {
          $('#cbosem').prop('disabled', true); 
          $('#cbosem').val('');
        }
        
      }
      
      function FCClasList(headid)
      {
        $.ajax({
          url:"{{route('FCClasList')}}",
          method:'GET',
          data:{
            headid:headid
          },
          dataType:'json',
          success:function(data)
          {
            $('#col-classlist').html(data.list)
            $('#col-classlist-foot').html(data.listfoot)
          }
        });
      }

      function FCItemList(detailid)
      {
        $.ajax({
          url:"{{route('FCItemList')}}",
          method:'GET',
          data:{
            detailid:detailid
          },
          dataType:'json',
          success:function(data)
          {
            $('#col-payclass-list') .html(data.items);
          }
        }); 
      }

      function colValidation(classification)
      {
        console.log($('#col-classification').val())

        if($('#col-classification').val() == '' || $('#col-classification').val() == null)
        {
          $('#col-add-item').removeClass('cursor-pointer text-primary');
        }
        else
        {
          $('#col-add-item').addClass('cursor-pointer text-primary');
        }
      }

      // $(document).on('change', '#cboglevel', function(){
      //   console.log($(this).val());
      // })

      $(document).on('keyup', '#txtdesc', function(){
        validation();
        if($(this).val() == '')
        {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid');
        }
        else
        {
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid'); 
        }
        
      });

      $(document).on('change', '#cboglevel', function(){
        shUI($(this).val());
        colUI($(this).val());
        if($(this).val() == '')
        {
          $(this).removeClass('is-valid');
          $(this).addClass('is-invalid');
        }
        else
        {
          $(this).addClass('is-valid');
          $(this).removeClass('is-invalid'); 
        }
        validation();

      });

      $(document).on('click', '#addClassification', function(){
        $('#modal-classification').empty();
        $('#modal-mop').empty();

        $.ajax({
          url:"{{route('loadClass')}}",
          method:'GET',
          data:{
            
          },
          dataType:'json',
          success:function(data)
          {
            // console.log(data.output);
            $('#modal-classification').html(data.classification);
            $('#modal-mop').html(data.payscheme);
          }
        });
      });

      $(document).on('click', '#savePayClass', function(){
        var desc = $('#txtdesc').val();
        var levelid = $('#cboglevel').val();
        var semid = $('#cbosem').val();
        var syid = $('#cbosy').val();

        var esc = '';

        if($('#chkESC').prop('checked') == true)
        {
          esc = 1;
        }
        else
        {
          esc = 0;
        }

        var classID = $('#modal-classification').val();
        var mopid = $('#modal-mop').val();
        var headid = $('#saveFC').attr('data-id');

        $.ajax({
          url:"{{route('savePayClass')}}",
          method:'GET',
          data:{
            desc:desc,
            levelid:levelid,
            semid:semid,
            syid:syid,
            esc:esc,
            classID:classID,
            mopid:mopid,
            headid:headid
          },
          dataType:'json',
          success:function(data)
          {
            console.log(data.headID);
            $('#saveFC').attr('data-id', data.headID);
            $('#payclass-list').html(data.tdetail);
            $('#payTotal').text(data.payclassamount);

            var curID = $('#addClassification').attr('data-id');
            $('.payClass').each(function(){
              var trID = $(this).attr('data-id');

              if(curID == trID)
              {
                $(this).addClass('text-bold');
              }
            })


          }
        });
      });
      
      $(document).on('click', '.payClass', function(){

        $('.payClass').each(function(){
          $(this).removeClass('bg-info');
        });

        $(this).addClass('bg-info');
        $('#addClassification').attr('data-id', $(this).attr('data-id'));
        
        var descVal = $(this).find('.descval').text();

        $('#payDesc').text('FOR ' + descVal);

        var detailID = $(this).attr('data-id');

        if($('#addClassification').attr('data-id') != 0) 
        {
          $('#additem').prop('disabled', false);
        }
        else
        {
          $('#additem').prop('disabled', true);
        }

        $.ajax({
          url:"{{route('getFCItem')}}",
          method:'GET',
          data:{
            detailID:detailID
          },
          dataType:'json',
          success:function(data)
          {
            $('#item-list').html(data.itemList);
            $('#itemTotal').text('TOTAL: ' + data.itemTotal);
          }
        });

        
      });

      $(document).on('click', '#additem', function(){
        $('#txtamount').val('');

        $.ajax({
          url:"{{route('loadItems')}}",
          method:'GET',
          data:{

          },
          dataType:'json',
          success:function(data)
          {
            console.log(data.itemlist);
            $('#modal-items').html(data.itemlist);
            
          }
        });
      });

      $(document).on('change', '#modal-items', function(){
        var itemid = $(this).val();
        $.ajax({
          url:"{{route('getItemInfo')}}",
          method:'GET',
          data:{
            itemid:itemid
          },
          dataType:'',
          success:function(data)
          {
            $('#txtamount').val(data) ;
            $('#txtamount').attr('data-value', data.replace(',', ''));
          }
        });

      })

      $(document).on('keyup', '#txtamount', function(){
        var conAmount = $(this).val().replace(',', '');

        $(this).attr('data-value', conAmount);
      });

      $(document).on('click', '#saveItem', function(){
        var itemid = $('#modal-items').val();
        var amount = $('#txtamount').attr('data-value');
        var detailID = $('#addClassification').attr('data-id');
        var headerid = $('#saveFC').attr('data-id');

        $.ajax({
          url:"{{route('saveFCItem')}}",
          method:'GET',
          data:{
            itemid:itemid,
            amount:amount,
            detailID:detailID,
            headerid:headerid
          },
          dataType:'json',
          success:function(data)
          {
            $('#item-list').html(data.items);
            $('#payclass-' + detailID).text(data.curAmount);
            $('#itemTotal').text('TOTAL: ' + data.itemTotal);
            $('#payTotal').text('TOTAL: ' + data.payclassTotal);
          }
        });
      });

      $(document).on('click', '#saveFC', function(){
        var headerid = $(this).attr('data-id');
        var desc = $('#txtdesc').val();
        var levelid = $('#cboglevel').val();
        var semid = $('#cbosem').val();
        var classID = $('#modal-classification').val();
        var mopid = $('#modal-mop').val();
        var syid = $('#cbosy').val();
        var grantee = $('#grantee').val();
        var strandid = $("#strand").val();
        var courseid = $('#course').val();

        $.ajax({
          url:"{{route('saveFC')}}",
          method:'GET',
          data:{
            headerid:headerid,
            desc:desc,
            levelid:levelid,
            syid:syid,
            semid:semid,
            grantee:grantee,
            strandid:strandid,
            courseid:courseid
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
              title: 'Fees and Collection successfully saved.'
            })

            window.location = "/finance/fees"
          }
        });
      });

      function loadEditVal()
      {

        var headerid = $('#saveFC').attr('data-id');
        // console.log(headerid);
        $.ajax({
          url:"{{route('editFC')}}",
          method:'GET',
          data:{
            headerid:headerid
          },
          dataType:'json',
          success:function(data)
          {
            $('#txtdesc').val(data.desc);
            $('#payclass-list').html(data.payDetail);
            $('#payTotal').text('TOTAL: ' + data.payTotal);
            $('#cboglevel').val(data.levelid);

            $('#grantee').val(data.grantee);
            $('#strand').val(data.strand)
            // var levelid = data.levelid;
            $('#txtdesc').trigger('keyup');
            $('#cboglevel').trigger('change');
            $('#cbosy').val(data.syid);
            $('#course').val(data.courseid);
            $('#course').trigger('change');
            validation();
          }
        }); 
      }

      function FCClasList(headid)
      {
        $.ajax({
          url:"{{route('FCClasList')}}",
          method:'GET',
          data:{
            headid:headid
          },
          dataType:'json',
          success:function(data)
          {
            $('#col-classlist').html(data.list)
            $('#col-classlist-foot').html(data.listfoot)
          }
        });
      }

      $(document).on('click', '.btnpayedit', function(){
        var descid = $(this).attr('data-desc');
        var mopid = $(this).attr('data-mop');
        var isdp = $(this).attr('data-dp');

        $.ajax({
          url:"{{route('loadClass')}}",
          method:'GET',
          data:{
            
          },
          dataType:'json',
          success:function(data)
          {
            
            $('#modal-classification-edit').html(data.classification);
            $('#modal-mop-edit').html(data.payscheme);

            $('#modal-classification-edit').val(descid);
            $('#modal-mop-edit').val(mopid);

            if(isdp == 1)
            {
              $('#isDP-edit').prop('checked', true);  
            }
            else
            {
              $('#isDP-edit').prop('checked', false);
            }
          }
        });
      });

      $(document).on('click', '#updatePayClass', function(){
        var classID = $('#modal-classification-edit').val();
        var mopid = $('#modal-mop-edit').val();
        var headerid = $('#saveFC').attr('data-id');
        var detailid = $('#addClassification').attr('data-id');
        var isdp = 0;


        if($('#isDP-edit').prop('checked') == true)  
        {
          isdp = 1;
        }
        else
        {
          isdp = 0;
        }

        console.log(isdp);

        $.ajax({
          url:"{{route('updateFCpayclass')}}",
          method:'GET',
          data:{
            headerid:headerid,
            detailid:detailid,
            classID:classID,
            mopid:mopid,
            isdp:isdp
          },
          dataType:'json',
          success:function(data)
          {
            $('#payclass-list').html(data.tDetail);
            $('#payTotal').text('TOTAL: ' + data.pAmount);

            var curID = $('#addClassification').attr('data-id');
            $('.payClass').each(function(){
              var trID = $(this).attr('data-id');

              if(curID == trID)
              {
                $(this).addClass('text-bold');
              }
            })
          }
        });
      });

      $(document).on('click', '.btnpaydelete', function(){
        var headerid = $('#saveFC').attr('data-id');
        var classID = $(this).attr('data-id');

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
              url:"{{route('deleteFCpayclass')}}",
              method:'GET',
              data:{
                headerid:headerid,
                classID:classID
              },
              dataType:'json',
              success:function(data)
              {

                $('#payclass-list').html(data.tDetail);
                $('#payTotal').text('TOTAL: ' + data.pAmount);
                $('#item-list').html(data.itemList);
                $('#itemTotal').text('TOTAL: ' + data.itemTotal);


                Swal.fire(
                  'Deleted!',
                  'Item has been deleted.',
                  'success'
                );
                
              }
            }); 
          }
        });
      });

      $(document).on('click', '.btnitemedit', function(){

        var itemID = $(this).attr('item-id');
        var tItemID = $(this).attr('data-id');
        var itemAmount = $(this).attr('item-amount');
        var classID = $('#addClassification').attr('data-id');

        console.log(itemID);
        $.ajax({
          url:"{{route('loadItems')}}",
          method:'GET',
          data:{

          },
          dataType:'json',
          success:function(data)
          {
            console.log(data.itemlist);
            $('#modal-items-edit').html(data.itemlist);
            $('#modal-items-edit').val(itemID);
            $('#txtamount-edit').val(itemAmount);
            $('#updateItem').attr('data-id', tItemID);
          }
        });
      });

      $(document).on('click', '#updateItem', function(){
        var headerid = $('#saveFC').attr('data-id');
        var classID = $('#addClassification').attr('data-id');
        var tItemID = $(this).attr('data-id');
        var itemID = $('#modal-items-edit').val();
        var itemAmount = $('#txtamount-edit').val();

        console.log(itemAmount);

        $.ajax({
          url:"{{route('updateFCItem')}}",
          method:'GET',
          data:{
            headerid:headerid,
            classID:classID,
            tItemID:tItemID,
            itemID:itemID,
            itemAmount:itemAmount
          },
          dataType:'json',
          success:function(data)
          {
            $('#payclass-list').html(data.tDetail);
            $('#payTotal').text('TOTAL: ' + data.pAmount);
            $('#item-list').html(data.itemList);
            $('#itemTotal').text('TOTAL: ' + data.itemTotal);

            var curID = $('#addClassification').attr('data-id');
            $('.payClass').each(function(){
              var trID = $(this).attr('data-id');

              if(curID == trID)
              {
                $(this).addClass('text-bold');
              }
            });
          }
        });
      });

      $(document).on('change', '#modal-items-edit', function(){
        var itemid = $(this).val();
        $.ajax({
          url:"{{route('getItemInfo')}}",
          method:'GET',
          data:{
            itemid:itemid
          },
          dataType:'',
          success:function(data)
          {
            $('#txtamount-edit').val(data) ;
            $('#txtamount-edit').attr('data-value', data.replace(',', ''));
          }
        });

      });

      $(document).on('click', '.btnitemdelete', function(){
        var headerid = $('#saveFC').attr('data-id');
        var classID = $('#addClassification').attr('data-id');
        var itemid = $(this).attr('data-id');

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
              url:"{{route('deleteFCItem')}}",
              method:'GET',
              data:{
                headerid:headerid,
                classID:classID,
                itemid:itemid
              },
              dataType:'json',
              success:function(data)
              {

                $('#payclass-list').html(data.tDetail);
                $('#payTotal').text('TOTAL: ' + data.pAmount);
                $('#item-list').html(data.itemList);
                $('#itemTotal').text('TOTAL: ' + data.itemTotal);

                var curID = $('#addClassification').attr('data-id');
                $('.payClass').each(function(){
                  var trID = $(this).attr('data-id');

                  if(curID == trID)
                  {
                    $(this).addClass('text-bold');
                  }
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
      });

      $(document).on('mouseover', '#col-classlist tr', function(){
        $(this).addClass('bg-primary');
      });

      $(document).on('mouseout', '#col-classlist tr', function(){
        $(this).removeClass('bg-primary');
      });

      $(document).on('click', '#col-classlist tr', function(){
        $('#col-classification').empty();
        $('#col-mop').empty();
        $('#col-payclass-list').empty();

        $('#col-classification').append('<option value=""></option>')
        @foreach(App\FinanceModel::loadItemClass() as $class)
          $('#col-classification').append('<option value="{{$class->id}}">{{$class->description}}</option>')
        @endforeach

        $('#col-mop').append('<option value=""></option>')
        @foreach(App\FinanceModel::loadMOP() as $mop)

          $('#col-mop').append('<option value="{{$mop->id}}">{{$mop->paymentdesc}}</option>')
        @endforeach

        $('#modal-col-class').modal('show');
        var detailid = $(this).attr('data-id');

        $('#col-savePayClass').attr('data-id', detailid);

        $.ajax({
          url:"{{route('editcolFCdetail')}}",
          method:'GET',
          data:{
            detailid:detailid,
          },
          dataType:'json',
          success:function(data)
          {
            $('#col-classification').val(data.classid);
            $('#col-classification').trigger('change');

            $('#col-mop').val(data.mopid);
            if(data.istuition == 1)
            {
              $('#istuition').prop('checked', true)
            }
            else
            {
              $('#istuition').prop('checked', false)
            }

            $('#col-payclass-list').html(data.items);

          }
        }); 
      });

      $(document).on('click', '#coladdclass', function(){

        $('#istuition').prop('checked', false);
        $('#col-savePayClass').attr('data-id', 0);

        $('#col-classification').empty();
        $('#col-mop').empty();
        $('#col-payclass-list').empty();

        $('#col-classification').append('<option value=""></option>')
        @foreach(App\FinanceModel::loadItemClass() as $class)
          $('#col-classification').append('<option value="{{$class->id}}">{{$class->description}}</option>')
        @endforeach

        $('#col-mop').append('<option value=""></option>')
        @foreach(App\FinanceModel::loadMOP() as $mop)

          $('#col-mop').append('<option value="{{$mop->id}}">{{$mop->paymentdesc}}</option>')
        @endforeach

        $('#modal-col-class').modal('show');
        // $('#modal-create-new-classification').modal('show');

      });

      $(document).on('change', '#col-classification', function(){

        colValidation();
      });

      $(document).on('click', '#col-add-item', function(){
        if($('#col-classification').val() != '' && $('#col-classification').val() != null)
        {
          $('#col-item-action').text('ADD');
          $('#fc-appendItem').attr('data-id', 0);
          $('#modal-payitem').modal('show');
          $('#fc-txtamount').val('');
          $('#fc-item').empty();
          $('#fc-item').append('<option></option>');

          $('#fc-deleteItem').prop('disabled', true);

          @foreach(App\FinanceModel::receivableitems() as $receivable)
            $('#fc-item').append('<option value="{{$receivable->id}}">{{$receivable->description}}</option>');
          @endforeach
        }
      });

      $(document).on('click', '#fc-btnadditem', function(){
        $('#modal-fc-itemcreate').modal('show');
        $('#fc-item-code').val('');
        $('#fc-item-desc').val('');
        $('#fc-item-class').val('');
        $('#fc-item-amount').val('');
        $('#fc-item-class').trigger('change');
        $('#fc-item-class').empty();
        $('#fc-item-class').append('<option></option>');        
        @foreach(App\FinanceModel::loadItemClass() as $itemclass)
          $('#fc-item-class').append('<option value="{{$itemclass->id}}">{{$itemclass->description}}</option>')
        @endforeach
      });

      $(document).on('click', '#close-modal-fc-itemcreate', function(){
        $('#modal-fc-itemcreate').modal('hide');
      });

      $(document).on('click', '#close_modal-payitem', function(){
        $('#modal-payitem').modal('hide');
      });

      $(document).on('click', '#fc-appendItem', function(){
        var desc = $('#txtdesc').val();
        var levelid = $('#cboglevel').val();
        var semid = $('#cbosem').val();
        var syid = $('#cbosy').val();
        var grantee = $('#grantee').val();
        var courseid = $('#course').val();
        var headid = $('#saveFC').attr('data-id');
        var appendAct = $(this).attr('data-id');

        var detailid = $('#col-savePayClass').attr('data-id');
        var classid = $('#col-classification').val();
        var mopid = $('#col-mop').val();

        var itemid = $('#fc-item').val();
        var itemamount = $('#fc-txtamount').val();
        
        if($('#istuition').prop('checked') == true)
        {
          var istuition = 1;
        }
        else
        {
          var istuition = 0;
        }

        var _route;

        if(appendAct == 0)
        {
          $.ajax({
            url:"{{route('appendcolFC')}}",
            method:'GET',
            data:{
              desc:desc,
              levelid:levelid,
              semid:semid,
              syid:syid,
              grantee:grantee,
              courseid:courseid,
              headid:headid,
              detailid:detailid,
              classid:classid,
              mopid:mopid,
              itemid:itemid,
              itemamount:itemamount,
              istuition:istuition,
            },
            dataType:'json',
            beforesend: function(){
              $('#fc-appendItem').prop('disabled', true);
            },
            success:function(data)
            {
              console.log(data.itemlist);
              $(this).prop('disabled', false);
              $('#modal-payitem').modal('hide');

              $('#saveFC').attr('data-id', data.headid);
              $('#col-savePayClass').attr('data-id', data.detailid);
              $('#col-payclass-list').html(data.itemlist);
            }
          });
        } 
        else
        {
          var itemid = $('#fc-item').val();
          var amount = $('#fc-txtamount').val();
          var datailid = $('#col-savePayClass').attr('data-id');

          $.ajax({
            url:"{{route('updatecolFCitem')}}",
            method:'GET',
            data:{
              itemid:itemid,
              amount:amount,
              appendAct:appendAct,
              datailid:datailid
            },
            dataType:'json',
            beforesend: function(){
              $('#fc-appendItem').prop('disabled', true);
            },
            success:function(data)
            {
              $('#col-payclass-list').html(data.items);
              $('#modal-payitem').modal('hide');
            }
          });
        }
      });

      $(document).on('click', '#col-savePayClass', function(){
        var headid = $('#saveFC').attr('data-id');
        var detailid = $(this).attr('data-id');
        var classid = $('#col-classification').val();
        var mopid = $('#col-mop').val();

        if($('#istuition').prop('checked') == true)
        {
          var istuition = 1;
        }
        else
        {
          var istuition = 0;
        }

        console.log(headid);

        $.ajax({
          url:"{{route('appendcolFCdetail')}}",
          method:'GET',
          data:{
            headid:headid,
            detailid:detailid,
            classid:classid,
            mopid:mopid,
            istuition:istuition
          },
          dataType:'json',
          success:function(data)
          {
            $('#col-classlist').html(data.list);
            $('#col-classlist-foot').html(data.listfoot);
            $(this).attr('data-id', 0);
          }
        }); 
      });

      $(document).on('change', '#course', function(){
        var course = $('option:selected', this).attr('data-value');
        var levelid = $('#cboglevel').val();

        if(levelid >= 17 && levelid <= 20)
        {
          $('#txtdesc').val(course);
          $('#txtdesc').trigger('keyup');
        }
      });

      $(document).on('mouseover', '.col-item-list', function(){
        $(this).addClass('bg-info');
      });

      $(document).on('mouseout', '.col-item-list', function(){
        $(this).removeClass('bg-info');
      });

      $(document).on('click', '.col-item-list', function(){
        $('#fc-item').empty();
        $('#col-item-action').text('EDIT');
        $('#fc-deleteItem').prop('disabled', false);


        @foreach(App\FinanceModel::receivableitems() as $receivable)
          $('#fc-item').append('<option value="{{$receivable->id}}">{{$receivable->description}}</option>');
        @endforeach

        $('#modal-payitem').modal('show');

        var itemid = $(this).attr('data-id');

        $('#fc-appendItem').attr('data-id', itemid);

        $.ajax({
          url:"{{route('editcolFCitem')}}",
          method:'GET',
          data:{
            itemid:itemid
          },
          dataType:'json',
          success:function(data)
          { 
            $('#fc-item').val(data.itemid);
            $('#fc-item').trigger('change');
            $("#fc-txtamount").val(data.amount);
          }
        }); 

      });

      $(document).on('click', '#fc-deleteItem', function(){
        var itemid = $('#fc-appendItem').attr('data-id');
        Swal.fire({
          title: 'Are you sure?',
          text: "Enter you password to delete.",
          type: 'warning',
          input: 'password',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Delete'
        }).then((result) => {
          if (result.value) {
            
            $.ajax({
              url:"{{route('deletecolFCitem')}}",
              method:'GET',
              data:{
                itemid:itemid,
                pword:result.value
              },
              dataType:'',
              success:function(data)
              {
                if(data == 1)
                {
                  Swal.fire(
                    'Deleted!',
                    'Your data has been deleted',
                    'success'
                  );
                  FCItemList($('#col-savePayClass').attr('data-id'))
                  $('#modal-payitem').modal('hide');

                }
                else
                {
                  
                  Swal.fire(
                    'Error!',
                    'Invalid password',
                    'warning'
                  );
                }
              }
            }); 

            
          }
        })
      });

      $(document).on('click', '#col-delPayClass', function(){
        var detailid = $('#col-savePayClass').attr('data-id');
        Swal.fire({
          title: 'Are you sure?',
          text: "Enter you password to delete.",
          type: 'warning',
          input: 'password',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Delete'
        }).then((result) => {
          if (result.value) {
            
            $.ajax({
              url:"{{route('deletecolFCdetail')}}",
              method:'GET',
              data:{
                detailid:detailid,
                pword:result.value
              },
              dataType:'',
              success:function(data)
              {
                if(data == 1)
                {
                  Swal.fire(
                    'Deleted!',
                    'Your data has been deleted',
                    'success'
                  );
                  FCClasList($('#saveFC').attr('data-id'))
                  $('#modal-col-class').modal('hide');

                }
                else
                {
                  
                  Swal.fire(
                    'Error!',
                    'Invalid password',
                    'warning'
                  );
                }
              }
            }); 

            
          }
        })
      });

    });

  </script>

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



  </script>
  <style type="text/css">
    .cursor-pointer{
      cursor: pointer;
    }
  </style>


@endsection