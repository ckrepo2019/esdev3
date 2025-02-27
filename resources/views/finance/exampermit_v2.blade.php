@extends('finance.layouts.app')

@section('content')
  {{-- <style type="text/css">
    .table thead th  { 
                position: sticky !important; left: 0 !important; 
                width: 150px !important;
                background-color: #fff !important; 
                outline: 2px solid #fff !important;
                outline-offset: -1px !important;
            }
  </style> --}}
	{{-- <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Finance</h1> -->
          
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Payment Items</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section> --}}
  <section class="content">
  			<!-- Payment Items -->
        <div class="row mb-2 ml-2">
            <h1 class="m-0 text-dark">Exam Permit</h1>
        </div>
        <div class="row form-group">
            <div class="col-2">
                <select id="ep_sy" class="select2" style="width: 100%;">
                    @foreach(db::table('sy')->orderBy('sydesc')->get() as $sy)
                        @if($sy->isactive == 1)
                            <option value="{{$sy->id}}" selected>{{$sy->sydesc}}</option>
                        @else
                            <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-2">
                <select id="ep_sem" class="select2" style="width: 100%;">
                    @foreach(db::table('semester')->get() as $sem)
                        @if($sem->isactive == 1)
                            <option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
                        @else
                            <option value="{{$sem->id}}">{{$sem->semester}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-2">
                <select id="ep_level" class="select2" style="width: 100%;">
                    @foreach(db::table('gradelevel')->where('deleted', 0)->orderBy('sortid')->get() as $level)
                        <option value="{{$level->id}}">{{$level->levelname}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-2">
                <select id="ep_course" class="select2" style="width: 100%;" disabled>
                    <option value="0">COURSE | All</option>
                </select>
            </div>
            <div class="col-2">
                <select id="ep_section" class="select2" style="width: 100%;">
                    <option value="0">SECTION | All</option>
                </select>
            </div>
            <div class="col-2">
                <select id="ep_month" class="select2" style="width: 100%;">
                    @foreach(db::table('monthsetup')->get() as $month)
                        @if($month->monthid == date_format(date_create(App\FinanceModel::getServerDateTime()), 'm'))
                            <option value="{{$month->id}}" selected>{{$month->description}}</option>
                        @else
                            <option value="{{$month->id}}">{{$month->description}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                
            </div>
            <div class="col-md-4">
                <input id="ep_search" class="form-control" placeholder="Search">
            </div>
            <div class="col-md-2">
                <button id="ep_gen" class="btn btn-primary btn-block">GENERATE</button>
            </div>
        </div>
		<div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        
                    </div>
                    <div class="card-body">
                        <div id="main_table" class="table-responsive p-0">
                            <table class="table table-hover table-head-fixed table-sm text-sm">
                                <thead class="bg-warning p-0">
                                  <tr>
                                    <th>NAME</th>
                                    <th>LEVEL</th>
                                    <th>SECTION</th>
									<th>COURSE</th>
                                    <th>STATUS</th>
                                  </tr>
                                </thead> 
                                <tbody id="ep_list" style="cursor: pointer;"></tbody>             
                            </table>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
  </section>
@endsection

@section('modal')
  <div class="modal fade show" id="modal-accounts" aria-modal="true" style="padding-right: 17px; display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div id="ac_header" class="modal-header bg-info">
          <h4 class="modal-title">Student Account</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="card-body p-0">
                <div class="form-group row text-sm pt-0">
                    <div class="col-md-1 text-bold">
                        NAME: 
                    </div>
                    <div id="ac_name" class="col-md-6">
                        
                    </div>
                    <div class="col-md-1 text-bold">
                        LEVEL:
                    </div>
                    <div id="ac_level" class="col-md-4">
                        
                    </div>
                </div>
                <div class="row form-group text-sm">
                    <div class="col-md-1 text-bold">
                        STATUS: 
                    </div>
                    <div id="ac_status" class="col-md-5">
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-sm text-sm">
                            <thead>
                                <th>Particulars</th>
                                <th class="text-center">Charges</th>
                                <th class="text-center">Payment</th>
                                <th class="text-center">Balance</th>
                            </thead>
                            <tbody id="ac_list"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="ac_function" type="button" class="btn btn-success" data-dismiss="modal">Allow</button>
        </div>
      </div>
    </div> {{-- dialog --}}
  </div>

    <div class="modal fade show" id="modal-item-edit" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                  <h4 class="modal-title">Payment Items - Edit</h4>
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
                                    <input type="text" class="form-control validation" id="item-code-edit" placeholder="Item Code" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control validation" id="item-desc-edit" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="class-glid" class="col-sm-2 col-form-label">Classification</label>
                                <div class="col-sm-10">
                                  <select class="form-control" id="item-class-edit">
                                    <option></option>
                                  </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="class-desc" class="col-sm-2 col-form-label">Amount</label>
                                <div class="col-sm-10">
                                  <input type="number" class="form-control validation" id="item-amount-edit" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-3">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="isreceivable-edit">
                                        <label for="isreceivable-edit">
                                            Receivable
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="col-md-3">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="expense-edit">
                                            <label for="expense-edit">
                                                Expense
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="updateItem" type="button" class="btn btn-primary" data-dismiss="modal" data-id="">Save</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-items_detail" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-sm" style="height: 38em; margin-top: 4em;">
                <div id="modalhead" class="modal-header bg-info">
                    <h4 class="modal-title">Items <span id="item_action"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="item_code" class="col-sm-2 col-form-label">Item Code</label>
                        <div class="col-sm-5">
                          <input type="text" class="form-control validation" id="item_code" placeholder="Item Code" onkeyup="this.value = this.value.toUpperCase();">
                        </div>
                        <div class="col-sm-5">
                          <select id="item_classcode" class="select2" style="width:100%">
                            <option value="0"></option>
                            @foreach(db::table('items_classcode')->get() as $itemclass)
                              <option value="{{$itemclass->id}}">{{$itemclass->description}}</option>
                            @endforeach
                          </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="item_desc" class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control validation" id="item_desc" placeholder="Description" onkeyup="this.value = this.value.toUpperCase();">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="item_classid" class="col-sm-2 col-form-label">Classification</label>
                        <div class="col-sm-10">
                            <select class="select2 " id="item_classid" style="width: 100%;">
                                <option value="0"></option>
                                @foreach(db::table('itemclassification')->where('deleted', 0)->orderBy('description')->get() as $class)
                                    <option value="{{$class->id}}">{{$class->description}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="item_amount" class="col-sm-2 col-form-label">Amount</label>
                        <div class="col-sm-10">
                          <input type="number" class="form-control validation" id="item_amount" onkeyup="this.value = this.value.toUpperCase();">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-3">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="item_cash">
                                <label for="item_cash">
                                    Cash
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="item_receivable" >
                                <label for="item_receivable">
                                    Receivable
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="item_expense" >
                                <label for="item_expense">
                                    Expense
                                </label>
                            </div>
                        </div>
                        {{-- </div> --}}
                    </div>

                    <hr>
                    <div class="form-group row">
                        <label for="item_glid" class="col-sm-2 col-form-label">GL Account</label>
                        <div class="col-sm-10">
                            <select id="item_glid" class="select2" style="width: 100%;">
                                <option value="0"></option>
                                @foreach(db::table('acc_coa')->where('deleted', 0)->orderBy('code')->get() as $coa)
                                    <option value="{{$coa->id}}">{{$coa->code . ' - ' . $coa->account}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="item_save" type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>


  

  
@endsection

@section('js')
  
  <script type="text/javascript">
    
    $(document).ready(function(){
        var searchVal = $('#txtsearchitem').val();

        $('.select2').select2({
            theme: 'bootstrap4'
        });

        screenadjust();

        function screenadjust()
        {
            var screen_height = $(window).height();

            $('#main_table').css('height', screen_height - 352);
            // $('.screen-adj').css('height', screen_height - 223);
        }

        
        $(document).on('keyup', '#txtsearchitem', function(){
            var qry = $(this).val();
            searchitems(qry);
        });

        $(document).on('change', '#ep_level', function(){
            var levelid = $(this).val();
            var syid = $('#ep_sy').val();

            $.ajax({
                url: '{{route('ep_section')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    levelid:levelid,
                    syid:syid
                },
                success:function(data)
                {
                    $('#ep_section').html(data.sectionlist);


                    if(levelid >= 17 && levelid <= 21)
                    {
                        $('#ep_course').removeAttr('disabled');
                        $('#ep_course').html(data.courselist);
                    }
                    else
                    {
                        $('#ep_course').attr('disabled', 'disabled');
                    }
                }
            });
        });

        $(document).on('click', '#ep_gen', function(){
            var syid = $('#ep_sy').val();
            var semid = $('#ep_sem').val();
            var levelid = $('#ep_level').val();
            var courseid = $('#ep_course').val();
            var sectionid = $('#ep_section').val();
            var monthid = $('#ep_month').val();
            var search = $('#ep_search').val();

            $.ajax({
                url: '{{route('ep_gen')}}',
                type: 'GET',
                // dataType: 'json',
                data: {
                    syid:syid,
                    semid:semid,
                    levelid:levelid,
                    courseid:courseid,
                    sectionid:sectionid,
                    monthid:monthid,
                    search:search
                },
                success:function(data)
                {
                    $('#ep_list').html(data);
                    btns = $('.btnstatus').length; 
                    $('#ep_gen').prop('disabled', true);
                    $('#ep_gen').text('Please Wait...');
                    // console.log(btns)
                    loadaccounts(0);
                }
            });
        });

        btns = 0;

        function checkacc(row)
        {
            var current = $('#ep_list tr');
            var studid = current.eq(row).attr('studid');
            var syid = current.eq(row).attr('syid');
            var semid = current.eq(row).attr('semid');
            var levelid = $('#ep_level').val();
            var monthid = $('#ep_month').val();

            $.ajax({
                url: '{{route('ep_accounts')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    studid:studid,
                    syid:syid,
                    semid:semid,
                    levelid:levelid,
                    monthid:monthid
                },
                success:function(data)
                {
                    $('.btnstatus[studid="'+data.studid+'"]').text('');
                    $('.btnstatus[studid="'+data.studid+'"]').attr('detailid', data.detailid);
                    $('.btnstatus[studid="'+data.studid+'"]').attr('datarow', row);
                    
                    if(data.status == 'nd')
                    {
                        $('.btnstatus[studid="'+data.studid+'"]').text(data.balance);
                        $('.btnstatus[studid="'+data.studid+'"]').removeClass('btn-primary');
                        $('.btnstatus[studid="'+data.studid+'"]').removeClass('btn-success');  
                        $('.btnstatus[studid="'+data.studid+'"]').addClass('btn-danger');
                        $('.btnstatus[studid="'+data.studid+'"]').removeAttr('disabled');   
                    }
                    else if(data.status == 'na')
                    {
                        $('.btnstatus[studid="'+data.studid+'"]').text('Not Allowed. Balance: ' + data.balance);
                        $('.btnstatus[studid="'+data.studid+'"]').removeClass('btn-primary');
                        $('.btnstatus[studid="'+data.studid+'"]').removeClass('btn-success');  
                        $('.btnstatus[studid="'+data.studid+'"]').removeClass('btn-danger');
                        $('.btnstatus[studid="'+data.studid+'"]').addClass('btn-warning');
                        $('.btnstatus[studid="'+data.studid+'"]').removeAttr('disabled');
                    }
                    else if(data.status == 'a')
                    {
                        $('.btnstatus[studid="'+data.studid+'"]').text('Allowed. Balance: ' + data.balance);
                        $('.btnstatus[studid="'+data.studid+'"]').removeClass('btn-primary');
                        $('.btnstatus[studid="'+data.studid+'"]').removeClass('btn-danger');
                        $('.btnstatus[studid="'+data.studid+'"]').removeClass('btn-warning'); 
                        $('.btnstatus[studid="'+data.studid+'"]').addClass('btn-success');  
                        $('.btnstatus[studid="'+data.studid+'"]').removeAttr('disabled');
                    }
                }
            });

        }

        function loadaccounts(row, type='')
        {
            var current = $('#ep_list tr');


            if(row <= btns-1)
            {
                var studid = current.eq(row).attr('studid');
                var syid = current.eq(row).attr('syid');
                var semid = current.eq(row).attr('semid');
                var levelid = $('#ep_level').val();
                var monthid = $('#ep_month').val();

                $.ajax({
                    url: '{{route('ep_accounts')}}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        studid:studid,
                        syid:syid,
                        semid:semid,
                        levelid:levelid,
                        monthid:monthid
                    },
                    success:function(data)
                    {
                        $('.btnstatus[studid="'+data.studid+'"]').text('');
                        $('.btnstatus[studid="'+data.studid+'"]').attr('detailid', data.detailid);
                        $('.btnstatus[studid="'+data.studid+'"]').attr('datarow', row);
                        
                        if(data.status == 'nd')
                        {
                            $('.btnstatus[studid="'+data.studid+'"]').text(data.balance);
                            $('.btnstatus[studid="'+data.studid+'"]').removeClass('btn-primary');
                            $('.btnstatus[studid="'+data.studid+'"]').addClass('btn-danger');
                            $('.btnstatus[studid="'+data.studid+'"]').removeAttr('disabled');   
                        }
                        else if(data.status == 'na')
                        {
                            $('.btnstatus[studid="'+data.studid+'"]').text('Not Allowed. Balance: ' + data.balance);
                            $('.btnstatus[studid="'+data.studid+'"]').removeClass('btn-primary');
                            $('.btnstatus[studid="'+data.studid+'"]').addClass('btn-warning');
                            $('.btnstatus[studid="'+data.studid+'"]').removeAttr('disabled');
                        }
                        else if(data.status == 'a')
                        {
                            $('.btnstatus[studid="'+data.studid+'"]').text('Allowed. Balance: ' + data.balance);
                            $('.btnstatus[studid="'+data.studid+'"]').removeClass('btn-primary');
                            $('.btnstatus[studid="'+data.studid+'"]').addClass('btn-success');   
                            $('.btnstatus[studid="'+data.studid+'"]').removeAttr('disabled');
                        }

                        row ++;
                        loadaccounts(row);
                    }
                });
            }
            else
            {
                Swal.fire({
                  type: 'success',
                  title: 'DONE',
                  text: '',
                  footer: ''
                })

                $('#ep_gen').removeAttr('disabled');
                $('#ep_gen').text('Generate');
            }
        }

        $(document).on('click', '.btnstatus', function(){
            var studid = $(this).attr('studid');
            var syid = $(this).attr('syid');
            var semid = $(this).attr('semid');
            var levelid = $('#ep_level').val();
            var courseid = $('#ep_course').val();
            var sectionid = $('#ep_section').val();
            var monthid = $('#ep_month').val();
            var detailid = $(this).attr('detailid');
            var row = $(this).attr('datarow');

            if($(this).hasClass('btn-warning'))
            {
                status = 0;
                $('#ac_header').removeClass('bg-info');
                $('#ac_header').removeClass('bg-success');
                $('#ac_header').removeClass('bg-danger');
                $('#ac_header').addClass('bg-warning');
                $('#ac_function').removeClass('btn-success');
                $('#ac_function').removeClass('btn-warning');
                $('#ac_function').removeClass('btn-danger');
                $('#ac_function').addClass('btn-success');
                $('#ac_function').text('Allow');
                $('#ac_function').removeAttr('disabled');
            }
            else if($(this).hasClass('btn-success'))
            {
                status = 1;
                $('#ac_header').removeClass('bg-info');
                $('#ac_header').removeClass('bg-warning');
                $('#ac_header').removeClass('bg-danger');
                $('#ac_header').addClass('bg-success');
                $('#ac_function').removeClass('btn-success');
                $('#ac_function').removeClass('btn-warning');
                $('#ac_function').removeClass('btn-danger');
                $('#ac_function').addClass('btn-warning');
                $('#ac_function').text("Don't Allow");
                $('#ac_function').removeAttr('disabled');
            }
            else
            {
                status = 2;
                $('#ac_header').removeClass('bg-info');
                $('#ac_header').removeClass('bg-success');
                $('#ac_header').removeClass('bg-warning');
                $('#ac_header').addClass('bg-danger');
                $('#ac_function').removeClass('btn-success');
                $('#ac_function').removeClass('btn-warning');
                $('#ac_function').removeClass('btn-danger');
                $('#ac_function').addClass('btn-danger');   
                $('#ac_function').text("No Data");
                $('#ac_function').prop('disabled', true);
            }

            $('#ac_function').attr('data-id', detailid);
            $('#ac_function').attr('datarow', row);


            $.ajax({
                url: '{{route('ep_paysched')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    status:status,
                    syid:syid,
                    semid:semid,
                    studid:studid,
                    levelid:levelid,
                    courseid:courseid,
                    sectionid:sectionid,
                    monthid:monthid
                },
                success:function(data)
                {
                    $('#ac_name').text(data.name);
                    $('#ac_level').text(data.level);
                    $('#ac_status').text(data.status);
                    $('#ac_list').html(data.list);
                    $('#modal-accounts').modal('show');
                }
            });
            
        });

        $(document).on('click', '#ac_function', function(){
            var dataid = $(this).attr('data-id');
            var row = $(this).attr('datarow');

            $.ajax({
                url: '{{route('ep_changestatus')}}',
                type: 'GET',
                dataType: '',
                data: {
                    dataid:dataid
                },
                success:function(data)
                {
                    checkacc(row);
                }
            });
            
        });

    });

  </script>
  
@endsection