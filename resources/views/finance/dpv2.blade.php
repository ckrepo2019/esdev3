@extends('finance.layouts.app')

@section('content')
  <section class="content">
  	<div class="main-card card">
  		<div class="card-header text-lg bg-info">
  			<!-- Payment Items -->
        <h4 class="text-warning" style="text-shadow: 1px 1px 1px gray">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            <b>DOWNPAYMENT SETUP V2</b></h4>
  		</div>
  		<div class="card-body">
        <div class="row">
          <div class="col-4">
            
          </div>
          <div class="col-md-2">
            <select id="dpv2_filter-sy" class="form-control select2bs4 dpv2-filter">
              <option value="0">School Year</option>
              @foreach(db::table('sy')->get() as $sy)
                @if($sy->id == App\FinanceModel::getSYID())
                  <option value="{{$sy->id}}" selected="">{{$sy->sydesc}}</option>
                @else
                  <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                @endif
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <select id="dpv2_filter-sem" class="form-control select2bs4 dpv2-filter">
              <option value="0">Semester</option>
              @foreach(db::table('semester')->where('deleted', 0)->get() as $sem)
                @if($sem->id == App\FinanceModel::getSemID())
                  <option value="{{$sem->id}}" selected="">{{$sem->semester}}</option>
                @else
                  <option value="{{$sem->id}}">{{$sem->semester}}</option>
                @endif
              @endforeach
            </select>
          </div>
          <div class="col-4 p-0">
            <div class="input-group mb-3 p-0">
              {{-- <input id="txtsearchitem" type="text" class="form-control" placeholder="Search Item" onkeyup="this.value = this.value.toUpperCase();"> --}}
              <select class="select2bs4 form-control search-level dpv2-filter">
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
            <div id="table_main" class="table-responsive p-0">
              <table class="table table-striped table-head-fixed p-0">
                <thead class=" p-0">
                  <tr class="p-0">
                    <th>DESCRIPTION</th>
                    <th>GRADE LEVEL</th>
                    <th>AMOUNT</th>
                  </tr>  
                </thead> 
                <tbody id="dplist" style="cursor: pointer;">
                  
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
  <div class="modal fade" id="modal-item" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content" style="height: 37em">
        <div class="modal-header bg-info">
          <h4 class="modal-title"><span id="spantitle">Downpayment Setup</span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <input id="dpdesc" class="form-control" onkeyup="this.value = this.value.toUpperCase();" placeholder="Description">
            </div>
            <div class="col-md-3">
              <select id="dpv2_sy" class="form-control select2bs4">
                <option value="0">School Year</option>
                @foreach(db::table('sy')->get() as $sy)
                  @if($sy->id == App\FinanceModel::getSYID())
                    <option value="{{$sy->id}}" selected="">{{$sy->sydesc}}</option>
                  @else
                    <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <select id="dpv2_sem" class="form-control select2bs4">
                <option value="0">Semester</option>
                @foreach(db::table('semester')->where('deleted', 0)->get() as $sem)
                  @if($sem->id == App\FinanceModel::getSemID())
                    <option value="{{$sem->id}}" selected="">{{$sem->semester}}</option>
                  @else
                    <option value="{{$sem->id}}">{{$sem->semester}}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-3">
              <label>Grade Level</label>
              <div class="input-group">
                <select id="gradelevel" class="form-control select2bs4">
                  <option value="0">Select Grade Level</option>
                    @foreach(App\FinanceModel::loadGlevel() as $glevel)
                      <option value="{{$glevel->id}}">{{$glevel->levelname}}</option>
                    @endforeach
                </select>
              </div>
            </div>  
            <div class="col-md-3">
              <label>Classification</label>
              <div class="input-group">
                <select id="classid" class="form-control select2bs4">
                  <option value="0">Select Classification</option>
                </select>
              </div>
            </div>
          </div>
          <hr>
          <div class="row mt-3">
            <div class="col-md-6">
              <div class="row mt-2">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header bg-primary">
                      Classification Items
                    </div>
                    <div class="card-body table-responsive" style="overflow-y: auto; height: 12em; padding-top: 0px">
                      <table class="table table-striped table-sm text-sm">
                        <thead>
                          <tr>
                            <th style="position: sticky; top:0; z-index: 5; background: #fff">Item</th>
                            <th class="text-center" style="position: sticky; top:0; z-index: 5; background: #fff">Amount</th>
                          </tr>
                        </thead>
                        <tbody id="tuitionitemlist">
                          
                        </tbody>
                        <tfoot>
                          <tr>
                            <td class="text-right">TOTAL: </td>
                            <td id="item_total" class="text-right text-bold text-success">0.00</td>
                          </tr>
                        </tfoot>
                      </table>    
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row mt-2">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header bg-success">
                      Downpayment Items
                    </div>
                    <div class="card-body table-responsive" style="overflow-y: auto; height: 12em; padding-top: 0px">
                      <table class="table table-striped table-sm text-sm">
                        <thead>
                          <tr>
                            <th style="position: sticky; top:0; z-index: 5; background: #fff">Item</th>
                            <th class="text-center" style="position: sticky; top:0; z-index: 5; background: #fff">Amount</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody id="dpitemlist">
                        </tbody>
                        <tfoot>
                          <tr>
                            <td class="text-right">TOTAL: </td>
                            <td id="dp_total" class="text-right text-bold text-success">0.00</td>
                            <th></th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>        
        <div class="p-3">
          <div class="row">
            <div class="col-md-6">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>    
            </div>
            <div class="col-md-6">
              {{-- <button id="saveDPItem" type="button" class="btn btn-primary float-right ml-1" data-dismiss="modal"><i class="fas fa-save"></i> Save</button>     --}}
              {{-- <button id="delDPItem" type="button" class="btn btn-danger float-right"><i class="fas fa-trash"></i> Remove</button>     --}}
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

  <div class="modal fade show" id="modal-item-update" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-md mt-5">
      <div class="modal-content">
        <div class="modal-header bg-secondary">
          <h4 class="modal-title">Change Amount</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="text" placeholder="0.00" name="currency-field" id="txtitemamount" class="form-control form-control-lg text-xl" height="60px" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" autocomplete="off" data-toggle="tooltip" title="Enter Amount">
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="savedpamount" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

@endsection

@section('js')

  <script>
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
  </script>
  
  <script type="text/javascript">
    
    $(document).ready(function(){
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      screenadjust();

      function screenadjust()
      {
          var screen_height = $(window).height();
          
          
          $('#table_main').css('height', screen_height - 280);
          // $('.screen-bg').attr('style', 'width: 99%; top: -8; height: ' + screen_bg + ' !important;');
          // $('#screen-tui').css('height', screen_tui);
          // $('.screen-sidepanel').css('height', (screen_tui / 2) - 27)

      }

      $(window).resize(function(){
          screenadjust();
      })

      function loaddpClass(levelid, syid, semid)
      {
        $.ajax({
          url:"{{route('dpv2_loadclass')}}",
          method:'GET',
          data:{
            levelid:levelid,
            syid:syid,
            semid:semid
          },
          dataType:'json',
          success:function(data)
          {
            $('#classid').html(data.list);
          }
        }); 
      }
      
      function loadclassItems(detailid)
      {
        $.ajax({
          url:"{{route('dpv2_loadclassitems')}}",
          method:'GET',
          data:{
            detailid:detailid
          },
          dataType:'json',
          success:function(data)
          {
            $('#tuitionitemlist').html(data.list);
            $('#item_total').text(data.total);
          }
        });        
      }

      function loaddpitems(view = 0)
      {
        var levelid = $('#gradelevel').val();
        var syid = $('#dpv2_sy').val();
        var semid = $('#dpv2_sem').val();

        $.ajax({
          url:"{{route('dpv2_loaddpitems')}}",
          method:'GET',
          data:{
            levelid:levelid,
            syid:syid,
            semid:semid
          },
          dataType:'json',
          success:function(data)
          {
            $('#dpitemlist').html(data.list);
            $('#dp_total').text(data.total);


            $('#dpitemlist tr').each(function(){
              itemid = $(this).attr('item-id');

              $('#tuitionitemlist tr').each(function(){
                if($(this).attr('item-id') == itemid)
                {
                  $(this).addClass('bg-primary');
                }
              });
            });

            if(view == 1)
            {
              $('#modal-item').modal('show');
            }
          }
        });
      }


      $(document).on('change', '#classid', function(){
        loadclassItems($(this).val());
        loaddpitems();
      });

      // $(document).on('select2:close', '#classid', function(){
      //   var classid = $('#classid').find(':selected').attr('data-id');
      //   console.log(classid)
      // })

      $(document).on('click', '#tuitionitemlist tr', function(){
        
        if($('#dpdesc').val() != '')
        {
          var dataid = $(this).attr('data-id');
          var levelid = $('#gradelevel').val();
          var classid = $('#classid').val();
          var description = $('#dpdesc').val();
          var itemid = $(this).attr('item-id');
          var amount = $(this).attr('data-amount');
          var syid = $('#dpv2_sy').val();
          var semid = $('#dpv2_sem').val();

          console.log(classid);

          $.ajax({
          url:"{{route('dpv2_appenddpitem')}}",
          method:'GET',
          data:{
            dataid:dataid,
            itemid:itemid,
            levelid:levelid,
            classid:classid,
            amount:amount,
            description:description,
            syid:syid,
            semid:semid
          },
          dataType:'',
          success:function(data)
          {
            if(data == 0)
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
                title: 'Item already exist.'
              })     
            }
            else
            {
              loaddpitems();
            }
          }
        });
        }
        else
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
            type: 'warning',
            title: 'Please input Description'
          })
        }
      });

      dplistv2();


      function dplistv2()
      {
        // var levelid = $('.search-level').val();
        var levelid = $('.search-level').val();
        var syid = $('#dpv2_filter-sy').val();
        var semid = $('#dpv2_filter-sem').val();

        $.ajax({
          url:"{{route('dpv2_loaddp')}}",
          method:'GET',
          data:{
            levelid:levelid,
            syid:syid,
            semid:semid
          },
          dataType:'json',
          success:function(data)
          {
            $('#dplist').html(data.list);
          }
        });
      }

      function loadDP(levelid, desc, syid, semid)
      {
        $('#gradelevel').val(levelid);
        $('#gradelevel').trigger('change');
        $('#dpdesc').val(desc);

        $('#dpv2_sy').val(syid);
        $('#dpv2_sem').val(semid);

        $('#dpv2_sy').trigger('change');
        $('#dpv2_sem').trigger('change');

        loaddpitems(1);
        // $('#amount').val();
      }

      $(document).on('change', '.dpv2-filter', function(){
        dplistv2();
      })

      $(document).on('click', '.btn-remove', function(){
        var dataid = $(this).attr('data-id');

        Swal.fire({
          title: 'Delete Item?',
          text: "",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value == true) {
            $.ajax({
              url:"{{route('dpv2_removedpitem')}}",
              method:'GET',
              data:{
                dataid:dataid  
              },
              dataType:'',
              success:function(data)
              {
                loadclassItems
                loadclassItems($('#classid').val());
                loaddpitems();
                Swal.fire(
                  'Deleted!',
                  '',
                  'success'
                )
              }
            });  
          }
        })
      });

      $('#modal-item').on('hidden.bs.modal', function(){
        dplistv2();
      });

      $(document).on('click', '#savedpamount', function(){
        var dataid = $(this).attr('data-id');
        var amount = $('#txtitemamount').val();

        $.ajax({
          url:"{{route('dpv2_updatedpitem')}}",
          method:'GET',
          data:{
            dataid:dataid,
            amount:amount
          },
          dataType:'',
          success:function(data)
          {
            loaddpitems()
          }
        });
      });

      $(document).on('click', '.btn-edit', function(){
        $('#txtitemamount').val($(this).attr('data-value'))
        $('#savedpamount').attr('data-id', $(this).attr('data-id'))
        $('#modal-item-update').modal('show');
      });

      $('#txtitemamount').focus(function(event) {
        $(this).select();
      });

      // function loadItems()
      // {
      //   $.ajax({
      //     url:"route('loadItems')}}",
      //     method:'GET',
      //     data:{
            
      //     },
      //     dataType:'json',
      //     success:function(data)
      //     {
      //       $('#item-list').html(data.itemlist);
      //       loadDP(dataid);
      //       dataid = 0;
      //     }
      //   });
      // }

      

      $(document).on('change', '.search-level', function(){
        // $(this).trigger('change');
        // loadDPItems();
      });

      $(document).on('mouseover', '#dplist tr', function(){
        $(this).addClass('bg-info');
      });

      $(document).on('mouseout', '#dplist tr', function(){
        $(this).removeClass('bg-info');
      });

      $(document).on('click', '#dplist tr', function(){
        loadDP($(this).attr('data-id'), $(this).attr('data-desc'), $(this).attr('data-sy'), $(this).attr('data-sem'))
      });

      $(document).on('change', '#gradelevel', function(){
        setTimeout(function(){
          loaddpClass($('#gradelevel').val(), $('#dpv2_sy').val(), $('#dpv2_sem').val());
        },300)
          
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
        $('#dpdesc').val('');
        $('#gradelevel').val(0);
        $('#gradelevel').trigger('change');
        $('#classid').val(0);
        $('#classid').trigger('change');
        $('#tuitionitemlist').empty();
        $('#dpitemlist').empty();
        $('#item_total').text('0.00');
        $('#dp_total').text('0.00');

        $('#modal-item').modal('show');
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