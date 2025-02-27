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
            <li class="breadcrumb-item active">Downpayment Setup</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content pt-0">
  	<div class="main-card card">
  		<div class="card-header text-lg bg-info">
  			<!-- Payment Items -->
        <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            <b>DOWNPAYMENT SETUP</b></h4>
  		</div>
  		<div class="card-body">
        <div class="row">
          <div class="col-8">
            
          </div>
          <div class="col-4 p-0">
            <div class="input-group mb-3 p-0">
              {{-- <input id="txtsearchitem" type="text" class="form-control" placeholder="Search Item" onkeyup="this.value = this.value.toUpperCase();"> --}}
              <select class="select2bs4 form-control search-level">
                <option value="0">Grade Level</option>
                @foreach(App\FinanceModel::loadGlevel() as $glevel)
                  <option value="{{$glevel->id}}">{{$glevel->levelname}}</option>
                @endforeach
              </select>
              <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <div class="input-group-append">
                  <button class="btn btn-success" id="btnitem-new" data-toggle="" data-target="">New</button>
                </div>
              </div>
          </div>

        </div>
  			<div class="row p-0">
          <div class="col-12">
            <div class="table-responsive p-0" style="height: 340px;">
              <table class="table table-striped table-head-fixed p-0">
                <thead class=" p-0">
                  <tr class="p-0">
                    <th>ITEM CODE</th>
                    <th>DESCRIPTION</th>
                    <th>CLASSIFICATION</th>
                    <th>GRADE LEVEL</th>
                    <th>AMOUNT</th>
                    <th class="text-center">ALLOW LESS</th>
                    
                  </tr>  
                </thead> 
                <tbody id="dplist">
                  
                </tbody>             
              </table>
              <div id="#demo"></div>
            </div>
          </div>          
        </div>
  		</div>
  	</div>
  </section>
@endsection

@section('modal')
  <div class="modal fade show" id="modal-item" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title"><span id="spantitle">Downpayment Setup - New</span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="row">
            <div class="col-md-12">
              <label>Grade Level</label>
              <div class="input-group">
                <select id="gradelevel" class="form-control select2bs4">
                  <option value="0">Grade Level</option>
                    @foreach(App\FinanceModel::loadGlevel() as $glevel)
                      <option value="{{$glevel->id}}">{{$glevel->levelname}}</option>
                    @endforeach
                </select>
              </div>
                
            </div>
            
          </div>
          <div class="row mt-2">
            <div class="col-md-12">
              <label>Item</label>
              <div class="input-group">
                <select id="item-list" class="form-control select2bs4">
                  
                </select>
                <span class="input-group-append">
                  <button id="createitem" class="btn btn-primary" data-toggle="tooltip" title="Create Items"><i class="fas fa-external-link-alt"></i></button>
                </span>
              </div>
                
            </div>
            
          </div>
          <div class="row mt-2">
            <div class="col-md-12">
              <label>Classification</label>
              <div class="input-group">
                <select id="item-class" class="form-control select2bs4">
                  
                </select>
              </div>
                
            </div>
            
          </div>
          <div class="row mt-2">
            <div class="col-md-12">
              <label>Amount</label>
              <div class="input-group">
                <input type="" name="" id="amount" class="form-control" placeholder="0.00">
              </div>
                
            </div>
            
          </div>
          <div class="row mt-3">
            <div class="col-md-12">
              <div class="icheck-primary d-inline">
                <input type="checkbox" id="allowless" class="">
                <label for="allowless" class="">
                  Allow Less than amount
                </label>
              </div>
            </div>
            
          </div>
        </div>


        
        <div class="p-3">
          <hr>
          <div class="row">
            <div class="col-md-6">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>    
            </div>
            <div class="col-md-6">
              <button id="saveDPItem" type="button" class="btn btn-primary float-right ml-1" data-dismiss="modal"><i class="fas fa-save"></i> Save</button>    
              <button id="delDPItem" type="button" class="btn btn-danger float-right"><i class="fas fa-trash"></i> Remove</button>    
            </div>
          </div>
        </div>
        

{{--         <div class="modal-footer justify-content-between" style="width: 300px">
          
          
 --}}        </div>
        
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-item-new" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Downpayment Items - New</h4>
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
                  <input type="text" class="form-control validation" id="newItem-code" placeholder="Item Code" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-desc" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control validation" id="newItem-desc" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                </div>
              </div>
              <div class="form-group row">
                <label for="class-glid" class="col-sm-2 col-form-label">Classification</label>
                <div class="col-sm-10">
                  <select class="form-control select2bs4" id='newItem-class'>
                    @foreach(App\FinanceModel::loadItemClass() as $itemclass)
                      <option value="{{$itemclass->id}}">{{$itemclass->description}}</option>
                    @endforeach
                  </select>
                </div>
              </div>


              <div class="form-group row">
                <label for="class-desc" class="col-sm-2 col-form-label">Amount</label>
                <div class="col-sm-10">
                  <input type="number" class="form-control validation" id="newItem-amount" placeholder="0.00">
                </div>
              </div>

              

              
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="saveNewDPItem" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
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
      var dataid;
      loadDPItems();

      function loadDPItems()
      {

        var levelid = $('.search-level').val();
        console.log(levelid);
        $.ajax({
          url:"{{route('loaddpitems')}}",
          method:'GET',
          data:{
            levelid:levelid
          },
          dataType:'json',
          success:function(data)
          {
            $('#dplist').html(data.list);
          }
        });
      }

      function loadDP(dataid)
      {
        $.ajax({
          url:"{{route('loaddp')}}",
          method:'GET',
          data:{
            dataid:dataid
          },
          dataType:'json',
          success:function(data)
          {
            $('#gradelevel').val(data.levelid);
            $('#gradelevel').trigger('change');
            
            $('#item-list').val(data.itemid);
            $('#item-list').trigger('change');

            
            $('#amount').val(data.amount);

            if(data.allowless == 1)
            {
              $('#allowless').prop('checked', true);
            }
            else
            {
              $('#allowless').prop('checked', false);
            }

            setTimeout(function(){
              $('#item-class').val(data.classid);
              $('#item-class').trigger('change');  
            }, 1500);

          }
        });
      
      }

      function loadItems()
      {
        $.ajax({
          url:"{{route('loadItems')}}",
          method:'GET',
          data:{
            
          },
          dataType:'json',
          success:function(data)
          {
            $('#item-list').html(data.itemlist);
            loadDP(dataid);
            dataid = 0;
          }
        });
      }

      function loaddpClass(levelid)
      {
        $.ajax({
          url:"{{route('loaddpclass')}}",
          method:'GET',
          data:{
            levelid:levelid
          },
          dataType:'json',
          success:function(data)
          {
            $('#item-class').html(data.option);
          }
        }); 
      }

      $(document).on('change', '.search-level', function(){
        // $(this).trigger('change');
        loadDPItems();
      });

      $(document).on('mouseover', '#dplist tr', function(){
        $(this).addClass('bg-info');
      });

      $(document).on('mouseout', '#dplist tr', function(){
        $(this).removeClass('bg-info');
      });

      $(document).on('click', '#dplist tr', function(){
        loadItems();
        dataid = $(this).attr('data-id');
        $('#saveDPItem').attr('data-id', dataid);
        $('#spantitle').text('Downpayment Setup - Edit');
        setTimeout(function(){
          $('#modal-item').modal('show');
        }, 1500);

      });

      $(document).on('change', '#gradelevel', function(){
        loaddpClass($(this).val())
      });

      $(document).on('click', '#saveDPItem', function(){

        dataid = $(this).attr('data-id');
        levelid = $('#gradelevel').val();
        itemid = $('#item-list').val();
        // itemdesc = $('#item-list').find(':selected').text();
        classid = $('#item-class').val();
        amount = $('#amount').val();

        if($('#allowless').prop('checked') == true)
        {
          allowless = 1;
        }
        else
        {
          allowless = 0;
        }


        $.ajax({
          url:"{{route('saveDPItem')}}",
          method:'GET',
          data:{
            dataid:dataid,
            levelid:levelid,
            itemid:itemid,
            classid:classid,
            amount:amount,
            allowless:allowless
          },
          dataType:'',
          success:function(data)
          {
            $('#item-class').html(data.option);
            loadDPItems();
            dataid = 0;
          }
        }); 
      });

      $(document).on('click', '#delDPItem', function(){

        dataid = $('#saveDPItem').attr('data-id');

        Swal.fire({
          title: 'Remove Downpayment?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Remove it!'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"{{route('removeDPItem')}}",
              method:'GET',
              data:{
                dataid:dataid,
              },
              dataType:'',
              success:function(data)
              {
                
                loadDPItems();
                dataid = 0;
                $('#modal-item').modal('hide');
                Swal.fire(
                  'Removed!',
                  'Downpayment has been removed.',
                  'success'
                )
              }
            });  
          }
        })
      });

      $(document).on('click', '#btnitem-new', function(){
        $('#saveDPItem').attr('data-id', '');
        loadItems();
        $('#modal-item').modal('show');
        $('#gradelevel').val('');
        $('#gradelevel').trigger('change');
        $('#item-class').val('');
        $('#item-class').trigger('change');
        $('#amount').val('');
      });

      $(document).on('click', '#createitem', function(){
        $('#modal-item-new').modal('show');
        $('#spantitle').text('Downpayment Setup - New')
      });

      $(document).on('click', '#saveNewDPItem', function(){

        var itemcode = $('#newItem-code').val();
        var description = $('#newItem-desc').val();
        var classid = $('#newItem-class').val();
        var amount = $('#newItem-amount').val();

        $.ajax({
          url:"{{route('saveNewDPItem')}}",
          method:'GET',
          data:{
            itemcode:itemcode,
            description:description,
            classid:classid,
            amount:amount
          },
          dataType:'',
          success:function(data)
          {
            if(data == 0) 
            {
              Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Item is already exist',
                footer: ''
              });
            }
            else
            {
              console.log('dataid: ' + dataid);
              loadItems() ;
              setTimeout(function(){
                $('#item-list').val(data);
                $('#item-list').trigger('change');
              }, 1000);
            }
          }
        });  
      });

    });

  </script>
  
@endsection