@extends('finance.layouts.app')

@section('content')
  <section class="content">
    <div class="row mb-2">
      <div class="col-sm-12">
        <h1 class="m-0 text-dark">Finance Setup</h1>
      </div>
    </div>
    <hr>
    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div id="terminalsetup" class="col-md-4" style="cursor: pointer;">
            <div class="small-box bg-info">
              <div class="inner">
                <h3 class="">Terminal <br>Setup</h3>
              </div>
              <div class="icon">
                <i class="fas fa-cash-register"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div id="ue" class="col-md-4" style="cursor: pointer;">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 class="">User<br>Elevation</h3>
              </div>
              <div class="icon">
                <i class="fas fa-users-cog"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <div id="allowdp" class="col-md-4" style="cursor: pointer;">
            <div class="small-box bg-orange">
              <div class="inner">
                <h3 class="text-light">Allow No<br>Downpayment</h3>
              </div>
              <div class="icon">
                <i class="fas fa-thumbs-up"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="row">
          <div id="coa" class="col-md-4" style="cursor: pointer;">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 class="">Chart of <br>Accounts</h3>
              </div>
              <div class="icon">
                <i class="fas fa-money-check"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <div id="mapping" class="col-md-4" style="cursor: pointer;">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 class="">Mapping <br>&nbsp;</h3>
              </div>
              <div class="icon">
                <i class="fas fa-sitemap"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          {{-- <div id="" class="col-md-4" style="cursor: pointer;">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3 class="text-light">Payment Plans<br></h3>
                <div class="form-group">
                  <div class="custom-control custom-switch custom-switch-off-warning custom-switch-on-success">
                    @if(DB::table('schoolinfo')->first()->paymentplan == 1)
                      <input type="checkbox" class="custom-control-input text-xl" id="togglepayplan" checked="">  
                    @else
                      <input type="checkbox" class="custom-control-input text-xl" id="togglepayplan">  
                    @endif
                    
                    <label class="custom-control-label" for="togglepayplan">Deactivate | Activate</label>
                  </div>
                </div>
              </div>
              <div class="icon">
                <i class="fas fa-play"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div> --}}
          <div id="setup_signatories" class="col-md-4" style="cursor: pointer;">
            <div class="small-box bg-olive">
              <div class="inner">
                <h3 class="">Signatories <br>&nbsp;</h3>
              </div>
              <div class="icon">
                <i class="fas fa-signature"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

          <div id="labfeesetup" class="col-md-4" style="cursor: pointer;">
            <div class="small-box bg-olive">
              <div class="inner">
                <h3 class="">Laboratory <br>Fee Setup</h3>
              </div>
              <div class="icon">
                <i class="fas fa-microscope"></i>
              </div>
              <a href="#" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
        </div>
    </div>
  </section>

@endsection
@section('modal')
  <div class="modal fade show" id="modal-terminalsetup" aria-modal="true" style="display: none; margin-top: -25px;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Terminal Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="mb-1 float-right">
              
            </div>
          </div>
          <div class="row">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <th>Terminal</th>
                  <th>Owner</th>
                  <th></th>
                </thead>
                <tbody id="terminallist">
                  
                </tbody>
                <tfoot>
                  <tr>
                    <td></td>
                    <td></td>
                    <td>
                      <button id="createTerminal" class="btn btn-outline-primary btn-flat btn-sm btn-block">Create</button>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer float-right">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
          </div>
          <div>
            {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
              <i class="fas fa-thumbs-down"></i>
            </button>
            <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
              <i class="fas fa-thumbs-up"></i>
            </button> --}}
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-coa" aria-modal="true" style="display: none; margin-top: -25px;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h4 class="modal-title">Chart of Accounts</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body p-0" style="overflow-y: auto; height: 480px">
            <div class="row">
              <div class="input-group ml-4 mt-2 mb-2 p-0 col-md-5">
                <input id="coasearch" type="text" name="" class="form-control" placeholder="Search">
                <div class="input-group-append">
                  <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <div class="input-group-append">
                  <button id="newcoa" class="btn btn-success" data-toggle="tooltip" title="Create"><i class="fas fa-external-link-alt"></i></button>
                </div>
              </div>
            </div>
            <table class="table table-striped table-head-fixed p-0">
              <thead class="">
                <th>CODE</th>
                <th>ACCOUNT</th>
                <th>GROUP</th>
              </thead>
              <tbody id="coalist" style="cursor: pointer">
                
              </tbody>
            </table>
          
        </div>
        <div class="modal-footer float-right">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
          </div>
          <div>
            {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
              <i class="fas fa-thumbs-down"></i>
            </button>
            <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
              <i class="fas fa-thumbs-up"></i>
            </button> --}}
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show mt-5" id="modal-actionCOA" aria-modal="true" style="display: none; margin-top: -25px;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Chart of Accounts - <span id="action"></span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group row">
                <div class="col-md-2">Code</div>
                <div class="col-md-10">
                  <input id="txtcode" type="number" name="" class="form-control is-invalid validation">  
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-2">Account</div>
                <div class="col-md-10">
                  <input id="txtaccount" type="text" name="" class="form-control is-invalid validation">  
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-2">Group</div>
                <div class="col-md-10">
                  <div class="input-group">
                    <select id="cbogroup" class="select2bs4 form-control is-invalid validation">
                      <option value=""></option>
                      @foreach(App\FinanceModel::getCOAGroup() as $group)
                        <option value="{{$group->group}}">{{$group->group}}</option>
                      @endforeach
                    </select>
                    <div class="input-group-append">
                      <button id="addGroup" class="btn btn-primary btn-sm"><i class="fas fa-external-link-alt"></i></i></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
        </div>
        <div class="modal-footer justify-content-between">
          <button id="btngroupclose" type="button" class="btn btn-default float-left" data-dismiss="modal" >Close</button>
          <button id="btngroupdelete" type="button" class="btn btn-danger float-right"><i class="fas fa-trash-alt"></i> Delete</button>
          <button id="btngroupsave" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show mt-5" id="modal-groupAction" aria-modal="true" style="display: none; margin-top: -25px;">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h4 class="modal-title">Group - Create</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <input id="txtaddGroup" type="text" name="" class="form-control" placeholder="Enter Group name">
              </div>
            </div>
          </div>
          
        </div>
        <div class="modal-footer justify-content-between">
          <button id="btngClose" type="button" class="btn btn-default float-left" data-dismiss="modal" >Close</button>
          <button id="btngSave" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show mt-5" id="modal-userElevation" aria-modal="true" style="display: none; margin-top: -25px;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h4 class="modal-title">User Elevation</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <th>Name</th>
                <th>Status</th>
              </thead>
              <tbody id="uelist">
                
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button id="btngClose" type="button" class="btn btn-default float-left" data-dismiss="modal" >Close</button>
          {{-- <button id="btngSave" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Save</button> --}}
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show mt-1" id="modal-acadprogdp" aria-modal="true" style="display: none; margin-top: -25px;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-orange">
          <h4 class="modal-title text-light">No Downpayment Setup</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="list-acadprog" class="row">
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header bg-secondary">
                  NO DOWNPAYMENT
                </div>
                <div class=" card-body">
                  <div class="table-responsive">
                    <table class="table table-striped">
                      <thead id="tbhead" class="bg-olive">
                        <tr>
                          <th>GRADE LEVEL</th>
                          <th class="text-center">ALL</th>
                          <th class="text-center">ESC</th>
                          <th class="text-center">VOUCHER</th>
                        </tr>
                      </thead>
                      <tbody id="levels">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
          
        
        <div class="modal-footer justify-content-between">
          <button id="btngClose" type="button" class="btn btn-default float-left" data-dismiss="modal" >Close</button>
          {{-- <button id="btngSave" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Save</button> --}}
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show" id="modal-signatories" aria-modal="true" style="display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" style="height: 500px;">
        <div class="modal-header bg-olive">
          <h4 class="modal-title">Signatories</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row mb-2">
            <div class="col-md-10">
              
            </div>
            <div class="col-md-2">
              <button class="btn btn-primary btn-block">
                <i class="fas fa-plus"></i> Create
              </button>
            </div>
          </div>
          <div class="row">
            <div class="table-responsive">
              <table class="table table-striped table-sm text-sm">
                <thead>
                  <th>Name</th>
                  <th class="text-center">Designation</th>
                </thead>
                <tbody id="sig_list">
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer float-right">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
          </div>
          <div>
            {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
              <i class="fas fa-thumbs-down"></i>
            </button>
            <button id="btnapprove" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="tooltip" title="Approve">
              <i class="fas fa-thumbs-up"></i>
            </button> --}}
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>
  
  <div class="modal fade show" id="modal-signatories_detail" aria-modal="true" style="display: none; margin-top: -27px;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Signatories <span></span></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <div class="col-md-12">
             <select id="sig_reporttype" class="select2bs4" style="width: 100%;">
               <option value="0">Report Type</option>
               @foreach(db::table('finance_sigs')->where('deleted', 0)->get() as $sigs)
                <option value="{{$sigs->id}}">{{$sigs->report_description}}</option>
               @endforeach
             </select>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <input id="sig_title1" class="form-control form-control-sm text-sm" placeholder="Title_1">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <input id="sig_name1" class="form-control form-control-sm text-sm" placeholder="Name_1">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <input id="sig_designation1" class="form-control form-control-sm text-sm" placeholder="Designation_1">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <input id="sig_title2" class="form-control form-control-sm text-sm" placeholder="Title_2">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <input id="sig_name2" class="form-control form-control-sm text-sm" placeholder="Name_2">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <input id="sig_designation2" class="form-control form-control-sm text-sm" placeholder="Designation_2">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <input id="sig_title3" class="form-control form-control-sm text-sm sig_group3" placeholder="Title_3">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <input id="sig_name3" class="form-control form-control-sm text-sm sig_group3" placeholder="Name_3">
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <input id="sig_designation3" class="form-control form-control-sm text-sm sig_group3" placeholder="Designation_3">
            </div>
          </div>
        </div>
        <div class="modal-footer float-right">
          <div class="">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
          </div>
          <div>
            {{-- <button id="btndisapprove" type="button" class="btn btn-danger" data-dismiss="modal" data-toggle="tooltip" title="Disapprove">
              <i class="fas fa-thumbs-down"></i>
            </button> --}}
            <button id="sig_save" type="button" class="btn btn-primary" data-id="0" data-toggle="tooltip" title="Save">
              Save
            </button>
          </div>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

  <div class="modal fade show mt-5" id="modal-labfeesetup" aria-modal="true" style="display: none; margin-top: -25px;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-olive">
          <h4 class="modal-title">Laboratory Fee</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <label>CLASSIFICATION</label>
            </div>
            <div class="col-md-4">
              <label>MODE OF PAYMENT</label>
            </div>
            <div class="col-md-3">
              <label>SEMESTER</label>
            </div>
          </div>
          <hr>
          <div class="row">
            <div id="labfee_list" class="col-md-12">
              
            </div>
          </div>
          <div class="row">
            <div class="col-md-3 mt-2">
              <u id="labfee_addlist" class="text-primary" style="cursor: pointer">Add List</u>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button id="btngClose" type="button" class="btn btn-default float-left" data-dismiss="modal" >Close</button>
          {{-- <button id="labfee_save" type="button" class="btn bg-primary"><i class="fas fa-save"></i> Save</button> --}}
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

@endsection
@section('jsUP')
  <style type="text/css">
    .roundbtn{
      border: none;
      color: white;
      padding: 20px;
      text-align: center;
      display: inline-block;
      font-size: 16px;
      margin: 4px 2px;
      cursor: pointer;
      border-radius: 50%;
    }

    .sig_hide{
      display: none; 
    }
  </style>
@endsection
@section('js')
  <script type="text/javascript">
    $(document).ready(function(){

      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      terminals();
      loadAcadprog();

      function terminals()
      {
        $.ajax({
          url:"{{route('loadTerminal')}}",
          method:'GET',
          data:{
            
          },
          dataType:'json',
          success:function(data)
          {
            $('#terminallist').html(data.list);
          }
        });
      }

      function loadCOA(filter)
      {
        $.ajax({
          url:"{{route('loadCOA')}}",
          method:'GET',
          data:{
            filter:filter
          },
          dataType:'json',
          success:function(data)
          {
           $('#coalist').html(data.list) ;
          }
        }); 
      }

      var validate = 0;

      function validation()
      {
        validate = 0;
        $('.validation').each(function(){
          if($(this).hasClass('is-invalid'))
          {
            validate += 1;
          }
        })

        if(validate > 0)
        {
          $('#btngroupsave').prop('disabled', true);
        }
        else
        {
          $('#btngroupsave').prop('disabled', false);
        }
      }

      function getUser()
      {
        $.ajax({
          url:"{{route('loadUE')}}",
          method:'GET',
          data:{
            
          },
          dataType:'json',
          success:function(data)
          {
            $('#uelist').html(data.ue);
          }
        });
      }
      
      function loadAcadprog()
      {
        $.ajax({
          url:"{{route('dploadAcadprog')}}",
          method:'GET',
          data:{
            
          },
          dataType:'json',
          success:function(data)
          {
            $('#list-acadprog').html(data.list);
          }
        });
      }

      function loadglevel(acadprogid)
      {
        $.ajax({
          url:"{{route('dploadglevel')}}",
          method:'GET',
          data:{
            acadprogid:acadprogid
          },
          dataType:'json',
          success:function(data)
          {
            $('#levels').html(data.list);
          }
        });
      }

      function togglenodp(dataid)
      {
        var esc = 0;
        var voucher = 0;
        var nodp = 0;

        $('input[data-id="'+dataid+'"]').each(function(){
          if($(this).attr('data-value') == 'esc')
          {
            if($(this).prop('checked') == true)
              esc = 1;
            else
              esc = 0;
          }
          else if($(this).attr('data-value') == 'voucher')
          {
            if($(this).prop('checked') == true)
              voucher = 1;
            else
              voucher = 0;
          }
          else if($(this).attr('data-value') == 'all')
          {
            if($(this).prop('checked') == true)
            {
              nodp = 1;
              esc = 1;
              voucher = 1;
            }
          }

          $.ajax({
            url:"{{route('togglenodp')}}",
            method:'GET',
            data:{
              dataid:dataid,
              esc:esc,
              voucher:voucher,
              nodp:nodp
            },
            dataType:'',
            success:function(data)
            {
            }
        });


        })
      }

      $(document).on('click', '#terminalsetup', function(){
        $('#modal-terminalsetup').modal('show');
      });

      $(document).on('click', '.oclear', function(){
        dataid = $(this).attr('data-id');
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, clear it!'
        }).then((result) => {
          if (result.value) {
            
            $.ajax({
              url:"{{route('clearTerminal')}}",
              method:'GET',
              data:{
                dataid:dataid
              },
              dataType:'',
              success:function(data)
              {
                Swal.fire(
                  'Cleared!',
                  'Terminal has been cleared',
                  'success'
                );

                terminals();
              }
            });
          }
        })
      });

      $(document).on('click', '#createTerminal', function(){
        $.ajax({
          url:"{{route('createTerminal')}}",
          method:'GET',
          data:{
            
          },
          dataType:'',
          success:function(data)
          {
            terminals();
          }
        });
      });

      $(document).on('click', '#coa', function(){
        // $('#modal-coa').modal('show');
        // loadCOA();

        window.location = '{{route('chartofaccounts')}}';


      });

      $(document).on('click', '#mapping', function(){
        window.location = '{{route('coamapping')}}';
      });

      $(document).on('mouseenter', '#coalist tr', function(){
        $(this).addClass('bg-primary');
      });

      $(document).on('mouseout', '#coalist tr', function(){
        $(this).removeClass('bg-primary');
      });

      $(document).on('keyup', '#coasearch', function(){
        loadCOA($(this).val());
      });
      
      $(document).on('click', '#newcoa', function(){
        $('#modal-actionCOA').modal('show');
        $('#action').text('New');
        $('#txtcode').val('');
        $('#txtaccount').val('');
        $('#cbogroup').val('');
        $('#cbogroup').trigger('change');
        $('#btngroupdelete').hide();
        $('#btngroupclose').show();
      });

      $(document).on('click', '#btngroupsave', function(){
        var code = $('#txtcode').val();
        var account = $('#txtaccount').val();
        var group = $('#cbogroup').val();

        console.log($('#action').text());
        if($('#action').text() == 'New')
        {
          $.ajax({
            url:"{{route('appendCOA')}}",
            method:'GET',
            data:{
              code:code,
              account:account,
              group:group
            },
            dataType:'',
            success:function(data)
            {
              if(data == 1)  
              {
                Swal.fire({
                  position: 'top',
                  type: 'success',
                  title: 'Chart of account saved',
                  showConfirmButton: false,
                  timer: 1500
                })

                $('#modal-actionCOA').modal('hide');
                loadCOA($('#coasearch').val());
              }
              else
              {
                Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Code or Account already exist',
                footer: ''
              })
              }
            }
          });
        }
        else if($('#action').text() == 'Edit')
        {
          var dataid = $('#btngroupsave').attr('data-id');
          $.ajax({
            url:"{{route('updateCOA')}}",
            method:'GET',
            data:{
              dataid:dataid,
              code:code,
              account:account,
              group:group
            },
            dataType:'',
            success:function(data)
            {
              if(data == 1)  
              {
                Swal.fire({
                  position: 'top',
                  type: 'success',
                  title: 'Chart of account saved',
                  showConfirmButton: false,
                  timer: 1500
                })
                $('#modal-actionCOA').modal('hide');
                loadCOA($('#coasearch').val());
              }
              else
              {
                Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Code or Account already exist',
                footer: ''
              })
              }
            }
          }); 
        }
      });

      $(document).on('change', '.validation', function(){
        if($(this).val() != '')
        {
          $(this).removeClass('is-invalid');
          $(this).addClass('is-valid');
        }
        else
        {
          $(this).addClass('is-invalid');
          $(this).removeClass('is-valid');
        }

        validation();
      });

      $(document).on('click', '#coalist tr', function(){
        var dataid = $(this).attr('data-id');

        $.ajax({
          url:"{{route('editCOA')}}",
          method:'GET',
          data:{
            dataid:dataid
          },
          dataType:'json',
          success:function(data)
          {
            $('#modal-actionCOA').modal('show');
            $('#txtcode').val(data.code);
            $('#txtaccount').val(data.account);
            $('#cbogroup').val(data.groupid);
            console.log(data.groupid);
            // $('#cbogroup').trigger('change');
            $('.validation').trigger('change')

            $('#btngroupclose').hide();
            $('#action').text('Edit');

            $('#btngroupsave').attr('data-id', dataid);

            validation();
          }
        });
      });

      $(document).on('click', '#btngroupdelete', function(){
        var dataid = $('#btngroupsave').attr('data-id');
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {

            $.ajax({
              url:"{{route('deleteCOA')}}",
              method:'GET',
              data:{
                dataid:dataid,
              },
              dataType:'',
              success:function(data)
              {

                if(data == 0)
                {
                  Swal.fire(
                    'Deleted!',
                    'Chart of Account has been deleted.',
                    'success'
                  );

                  $('#modal-actionCOA').modal('hide');
                  loadCOA($('#coasearch').val());
                }
                else
                {
                  Swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Chart of account has been used by Item Classification',
                    footer: ''
                  });
                }
              }
            }); 
          }
        })
      });

      $(document).on('click', '#addGroup', function(){
        $('#modal-groupAction').modal('show');
        $('#txtaddGroup').val('');
      });

      $(document).on('click', '#btngSave', function(){
        var groupname = $('#txtaddGroup').val();
        $.ajax({
          url:"{{route('appendCOAGroup')}}",
          method:'GET',
          data:{
            groupname:groupname
          },
          dataType:'json',
          success:function(data)
          {
            console.log('success1')
            if(data.return == 1)
            {
              
              Swal.fire({
                position: 'top',
                type: 'success',
                title: 'Group has been saved',
                showConfirmButton: false,
                timer: 1500
              });

              console.log(data.group);
              $('#cbogroup').html(data.group);
              $('#modal-groupAction').modal('hide');
              $('#cbogroup').val(data.groupname);
              $('#cbogroup').trigger('change');

              
            }
            else
            {
              console.log('errorsuccess1')
              Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Group name already exist.',
                footer: ''
              });
            }
          }
        }); 
      });

      $(document).on('click', '#ue', function(){
        $('#modal-userElevation').modal('show');
        getUser();
      });

      $(document).on('click', '.elevate', function(){
        var dataid = $(this).attr('data-id');
        var status = $(this).text();

        console.log('test');
        $.ajax({
          url:"{{route('processUE')}}",
          method:'GET',
          data:{
            dataid:dataid,
            status:status
          },
          dataType:'',
          success:function(data)
          {
            getUser();
          }
        });
      });
      
      $(document).on('click', '#allowdp', function(){
        loadAcadprog();
        $('.acadprog').trigger('click');
        $('#modal-acadprogdp').modal('show');
      });

      // $(document).on('click', '.chknodp', function(){
        
      //   if($(this).prop('checked') == true)
      //   {
      //     var nodp = 1;
      //   }
      //   else
      //   {
      //     var nodp = 0;
      //   }

      //   levelid = $(this).attr('data-id');

      //   $.ajax({
      //     url:"{{route('togglenodp')}}",
      //     method:'GET',
      //     data:{
      //       levelid:levelid,
      //       nodp:nodp
      //     },
      //     dataType:'',
      //     success:function(data)
      //     {
            
      //     }
      //   });
      // });

      $(document).on('click', '.acad-prog', function(){
        
        if($(this).find('#cardbody').hasClass('bg-primary'))
        {
          $('#tbhead').attr('class', '');
          $('#tbhead').addClass('bg-primary');
        }
        else if($(this).find('#cardbody').hasClass('bg-info'))
        {
          $('#tbhead').attr('class', '');
          $('#tbhead').addClass('bg-info'); 
        }
        else if($(this).find('#cardbody').hasClass('bg-warning'))
        {
          $('#tbhead').attr('class', '');
          $('#tbhead').addClass('bg-warning'); 
        }
        else if($(this).find('#cardbody').hasClass('bg-danger'))
        {
          $('#tbhead').attr('class', '');
          $('#tbhead').addClass('bg-danger'); 
        }
        else if($(this).find('#cardbody').hasClass('bg-success'))
        {
          $('#tbhead').attr('class', '');
          $('#tbhead').addClass('bg-success'); 
        }
        else if($(this).find('#cardbody').hasClass('bg-secondary'))
        {
          $('#tbhead').attr('class', '');
          $('#tbhead').addClass('bg-secondary'); 
        }

        loadglevel($(this).attr('data-id'));
      });

      $(document).on('click', '.chk', function(){

        if($(this).attr('data-value') == 'all')
        {
          if($(this).prop('checked') == true)
          {
            var dataid = $(this).attr('data-id');
            $('input').each(function() {
              if($(this).attr('data-value') == 'esc' && $(this).attr('data-id') == dataid)
              {
                $(this).prop('checked', true);
              }
            });

            $('input').each(function() {
              if($(this).attr('data-value') == 'voucher' && $(this).attr('data-id') == dataid)
              {
                $(this).prop('checked', true);
              }
            });
          }
          else
          {
            var dataid = $(this).attr('data-id');
            $('input').each(function() {
              if($(this).attr('data-value') == 'esc' && $(this).attr('data-id') == dataid)
              {
                $(this).prop('checked', false);
              }
            });

            $('input').each(function() {
              if($(this).attr('data-value') == 'voucher' && $(this).attr('data-id') == dataid)
              {
                $(this).prop('checked', false);
              }
            }); 
          }
        }
        else if($(this).attr('data-value') == 'esc' || $(this).attr('data-value') == 'voucher')
        {
          if($(this).prop('checked') == true)
          {
            var dataid = $(this).attr('data-id');
            $('input').each(function() {

              if($('input[data-value="voucher"][data-id="'+dataid+'"]').prop('checked') == true && $('input[data-value="esc"][data-id="'+dataid+'"]').prop('checked') == true)
              {
                $('input[data-value="all"][data-id="'+dataid+'"]').prop('checked', true);
              }
            });             
          }
          else
          {
            var dataid = $(this).attr('data-id');
            $('input').each(function() {

              if($('input[data-value="voucher"][data-id="'+dataid+'"]').prop('checked') == false || $('input[data-value="esc"][data-id="'+dataid+'"]').prop('checked') == false)
              {
                $('input[data-value="all"][data-id="'+dataid+'"]').prop('checked', false);
              }
            }); 
          }
        }

        togglenodp(dataid);

      });

      $(document).on('click', '#togglepayplan', function(){
        var status;
        if($(this).prop('checked') == true)
        {
          status = 1;
        }
        else
        {
          status = 0;
        }

        $.ajax({
          url:"{{route('togglepayplan')}}",
          method:'GET',
          data:{
            status:status
          },
          dataType:'',
          success:function(data)
          {
            if(data == 1)
            {
              $('#togglepayplan').attr('checked', true);
            }
            else
            {
              $('#togglepayplan').attr('checked', false);
            }
          }
        });
      });

      $(document).on('click', '#setup_signatories', function(){
        $('#sig_reporttype').val(0);
        $('#sig_reporttype').trigger('change');
        $('#modal-signatories_detail').modal('show');
        sig_lockinput();
      });

      $(document).on('change', '#sig_reporttype', function(){
        var sigid = $(this).val();

        $.ajax({
          url: '{{route('sigs_load')}}',
          type: 'GET',
          dataType: 'json',
          data: {
            sigid:sigid
          },
          success:function(data)
          {
            sig_lockinput();

            $('#sig_title1').val(data.title1);
            $('#sig_name1').val(data.sig1);
            $('#sig_designation1').val(data.designation1);

            $('#sig_title2').val(data.title2);
            $('#sig_name2').val(data.sig2);
            $('#sig_designation2').val(data.designation2);

            $('#sig_title3').val(data.title3);
            $('#sig_name3').val(data.sig3);
            $('#sig_designation3').val(data.designation3);

            $('#sig_save').attr('data-id', data.sigid);

            if(data.sig_active3 == 0)
            {
              $('.sig_group3').addClass('sig_hide');
            }
            else
            {
              $('.sig_group3').removeClass('sig_hide'); 
            }
          },
          complete:function(data)
          {
            sig_lockinput();            
          }
        });
      });

      function sig_lockinput()
      {
        if($('#sig_reporttype').val() == 0)
        {
          $('#sig_name1').val('');
          $('#sig_designation1').val('');
          $('#sig_titel1').val('');

          $('#sig_name2').val('');
          $('#sig_designation2').val('');
          $('#sig_titel2').val('');

          $('#sig_name3').val('');
          $('#sig_designation3').val('');
          $('#sig_titel3').val('');

          $('#sig_name1').prop('disabled', true);
          $('#sig_designation1').prop('disabled', true);
          $('#sig_title1').prop('disabled', true);

          $('#sig_name2').prop('disabled', true);
          $('#sig_designation2').prop('disabled', true);
          $('#sig_title2').prop('disabled', true);

          $('#sig_name3').prop('disabled', true);
          $('#sig_designation3').prop('disabled', true);
          $('#sig_title3').prop('disabled', true);
        }
        else
        {
          $('#sig_name1').prop('disabled', false);
          $('#sig_designation1').prop('disabled', false);
          $('#sig_title1').prop('disabled', false);

          $('#sig_name2').prop('disabled', false);
          $('#sig_designation2').prop('disabled', false);
          $('#sig_title2').prop('disabled', false);

          $('#sig_name3').prop('disabled', false);
          $('#sig_designation3').prop('disabled', false);
          $('#sig_title3').prop('disabled', false); 
        }
      }

      $(document).on('click', '#sig_save', function(){
        var sigid = $(this).attr('data-id');
        
        var title1 = $('#sig_title1').val();
        var sig1 = $('#sig_name1').val();
        var designation1 = $('#sig_designation1').val();
        
        var title2 = $('#sig_title2').val();
        var sig2 = $('#sig_name2').val();
        var designation2 = $('#sig_designation2').val();
        
        var title3 = $('#sig_title3').val();
        var sig3 = $('#sig_name3').val();
        var designation3 = $('#sig_designation3').val();
        
        if(sigid > 0)
        {
          $.ajax({
            url: '{{route('sigs_update')}}',
            type: 'GET',
            data: {
              sigid:sigid,
              title1:title1,
              sig1:sig1,
              designation1:designation1,
              title2:title2,
              sig2:sig2,
              designation2:designation2,
              title3:title3,
              sig3:sig3,
              designation3:designation3
            },
            success:function(data)
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
                type: 'success',
                title: 'Successfully Saved'
              })     

              // $('#modal-signatories_detail').modal('hide');
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
            type: 'error',
            title: 'Please select Report Type'
          })
        }
      })

      $(document).on('click', '#labfeesetup', function(){
        loadlabfeesetup(1);
      });

      var labfeeid = 0;
      var labfee_action =0;

      labfeeid = $('.labfee_item').length;

      $(document).on('click', '#labfee_addlist', function(){
        labfeeid += 1;
        $('#labfee_list').append(`
          <div id="`+labfeeid+`" data-id="0" class="row mt-2 labfee_item">
            <div class="col-md-4">
              <select id="labfee_classid`+labfeeid+`" class="select2bs4 labfee_classid labfee_fields" data-sort="`+labfeeid+`" style="width: 100%">
                <option>Classification</option>
                @foreach(db::table('itemclassification')->where('deleted', 0)->get() as $class)
                  <option value="{{$class->id}}">{{$class->description}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4">
              <select id="labfee_mop`+labfeeid+`" class="select2bs4 labfee_mop labfee_fields" data-sort="`+labfeeid+`" style="width: 100%">
                <option>Mode of Payment</option>
                @foreach(db::table('paymentsetup')->where('deleted', 0)->get() as $mop)
                  <option value="{{$mop->id}}">{{$mop->paymentdesc}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <select id="labfee_sem`+labfeeid+`" class="select2bs4 labfee_sem labfee_fields" data-sort="`+labfeeid+`" style="width: 100%">
                <option>Semester</option>
                @foreach(db::table('semester')->where('deleted', 0)->get() as $sem)
                  <option value="{{$sem->id}}">{{$sem->semester}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-1">
              <button class="btn btn-primary labfee_savesetup" data-toggle="tooltip" title="Save" data-sort="`+labfeeid+`"><i class="fas fa-download"></i></button>
            </div>
          </div>
        `);

        $('.select2bs4').select2({
          theme: 'bootstrap4'
        });

        if(labfeeid == 3)
        {
          $('#labfee_addlist').hide();
        }

      });

      $(document).on('click', '.labfee_savesetup', function(){
        var sortid = $(this).attr('data-sort');
        var classid = $('#labfee_classid' + sortid).val();
        var mop = $('#labfee_mop' + sortid).val();
        var semid = $('#labfee_sem' + sortid).val();

        if($(this).hasClass('btn-primary'))
        {
          $.ajax({
            url: '{{route('labfee_setup_append')}}',
            type: 'GET',
            dataType: '',
            data: {
              classid:classid,
              mop:mop,
              semid:semid,
              sortid:sortid
            },
            success:function(data)
            {
              if(data == 'done')
              {
                $('.labfee_savesetup').attr('data-sort', sortid).html('<i class="fas fa-trash-alt"></i>');
                $('.labfee_savesetup').attr('data-sort', sortid).removeClass('btn-primary');
                $('.labfee_savesetup').attr('data-sort', sortid).addClass('btn-danger');
              }
			  else{
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
                  title: "Setup existed"
                });
			  }
            }
          });
        }
        else
        {
          $.ajax({
            url: '{{route('labfee_setup_delete')}}',
            type: 'GET',
            dataType: '',
            data: {
              sortid:sortid
            },
            success:function(data)
            {
              loadlabfeesetup(0);
            }
          });
        }
      });

      $(document).on('change', '.labfee_fields', function(){
          var sortid = $(this).attr('data-sort');
          var classid = $('#labfee_classid' + sortid).val();
          var mop = $('#labfee_mop' + sortid).val();
          var semid = $('#labfee_sem' + sortid).val();

          if($('.labfee_savesetup').hasClass('btn-danger'))
          {
            $.ajax({
              url: '{{route('labfee_setup_edit')}}',
              type: 'GET',
              dataType: '',
              data: {
                classid:classid,
                mop:mop,
                semid:semid,
                sortid:sortid
              },
              success:function(data)
              {

              }
            });
          }  
      });

      function loadlabfeesetup(action)
      {
        $.ajax({
          url: '{{route('labfee_setup_load')}}',
          type: 'GET',
          dataType: 'json',
          data: {

          },
          success:function(data)
          {
            $('#labfee_list').html(data.list);
            labfeeid = data.labfeeid;

            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });

            if(action == 1)
            {
              $('#modal-labfeesetup').modal();
            }

          }
        });
      }
	  
	  $(document).on('click', '.oremove', function(){
        var id = $(this).closest('tr').attr('id')

        $.ajax({
          type: "GET",
          url: "{{route('removeTerminal')}}",
          data: {
            id:id
          },
          // dataType: "dataType",
          success: function (data){
            if(data == 'done')
            {
              terminals();

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
                type: 'success',
                title: 'Terminal successfully removed'
              })
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
                title: 'Terminal has transactions. Cannot be deleted'
              }) 
            }
          }
        })
      })



    });

  </script>
@endsection
