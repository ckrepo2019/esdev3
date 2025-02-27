@extends('finance.layouts.app')

@section('content')
    <section class="content">
  			<!-- Payment Items -->
        <div class="row mb-2 ml-2">
          <h1 class="m-0 text-dark">Students</h1>
        </div>
        <div class="row form-group">
            <div class="col-md-2">
                <select id="level" class="select2 filters" style="width: 100%;">
                    @foreach(db::table('gradelevel')->where('deleted', 0)->orderBy('sortid')->get() as $level)
                        @if($level->id > 21)
                        <option value="{{$level->id}}">HE - {{$level->levelname}}</option>
                        @else
                            <option value="{{$level->id}}">{{$level->levelname}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div id="div_sections" class="col-md-2" style="display: block;">
                <select id="sections" class="select2 filters" style="width: 100%;">
                    <option value="0">All - Section</option>
                </select>
            </div>
            {{-- <div id="div_strand" class="col-md-2" style="display: block;">
                <select id="strand" class="select2 filters" style="width: 100%;">
                    <option value="0">All - Strand</option>
                    @foreach(db::table('sh_strand')->where('deleted', 0)->get() as $strand)
                        <option value="{{$strand->id}}">{{$strand->strandcode}}</option>
                    @endforeach
                </select>
            </div> --}}
            <div id="div_courses" class="col-md-2" style="display: none;">
                <select id="courses" class="select2 filters" style="width: 100%;">
                    <option value="0">All</option>
                </select>
            </div>
            <div id="" class="col-md-4">
                <div class="row">
                    <div id="div_strand" class="col-md-6" style="display: none;">
                        <select id="strand" class="select2 filters" style="width: 100%;">
                            <option value="0">All - Strand</option>
                            @foreach(db::table('sh_strand')->where('deleted', 0)->get() as $strand)
                                <option value="{{$strand->id}}">{{$strand->strandcode}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="div_grantee" class="col-md-6" style="display: none;">
                        <select id="grantee" class="select2 filters" style="width: 100%;">
                            <option value="0">All - Grantee</option>
                            @foreach(db::table('grantee')->get() as $grantee)
                                <option value="{{$grantee->id}}">{{$grantee->description}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="div_subjects" style="display: none;">
                    <select id="subjects" class="select2 filters" style="width: 100%;">
                        <option value="0">All</option>
                        @foreach(db::table('college_subjects')->where('deleted', 0)->orderBy('subjDesc')->get() as $subj)
                            <option value="{{$subj->id}}">{{$subj->subjCode}} - {{$subj->subjDesc}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
        </div>
        <div class="row form-group">
            <div class="col-md-2">
                <select id="syid" class="select2" style="width: 100%">
                    @foreach(db::table('sy')->orderBy('sydesc')->get() as $sy)
                        @if($sy->isactive == 1)
                            <option value="{{$sy->id}}" selected>{{$sy->sydesc}}</option>
                        @else
                            <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select id="semid" class="select2" style="width: 100%">
                    @foreach(db::table('semester')->get() as $sem)
                        @if($sem->isactive == 1)
                            <option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
                        @else
                            <option value="{{$sem->id}}">{{$sem->semester}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select id="fees" class="select2" style="width: 100%">
                    <option value="">FEE</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="input-group mb-2">
                    <input id="stud_filter" type="text" class="form-control" placeholder="Search Student" onkeyup="this.value = this.value.toUpperCase();">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        
                    </div>
                    <div class="card-body">
                        <div id="main_table" class="table-responsive p-0">
                            <table class="table table-hover table-head-fixed table-sm text-sm" style="table-layout: fixed;">
                                <thead class="bg-warning p-0">
                                    <tr>
                                    <th style="width: 350px;">NAME</th>
                                    <th class="" style="width: 150px;">LEVEL</th>
                                    <th class="" style="width: 150px;">
                                        <button id="update_all" class="btn btn-warning btn-sm btn-block text-sm">Update Ledger All Students</button>
                                    </th>
                                    </tr>
                                </thead> 
                                <tbody id="studlist" style="cursor: pointer;"></tbody>             
                            </table>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
        
    </section>
@endsection

@section('modal')
    <div class="modal fade" id="modal-discount_detail" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-info">
                  <h4 class="modal-title">Discount</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group row">
                          <div class="col-md-12">
                              <label>Description</label>
                              <input id="discount_particulars" class="form-control discount_uppercase">
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <label>Amount/Percentage</label>
                                <input id="discount_amount" class="form-control text-right">
                            </div>
                            <div class="col-md-4 form-group">
                                <label></label>
                                <div class="icheck-primary mt-3">
                                    <input type="checkbox" id="discount_percentage">
                                    <label for="discount_percentage">Percentage</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                              <label>Maximum Unit</label>
                              <input id="discount_maxunit" class="form-control text-right">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button id="discount_remove" type="button" class="btn btn-danger" data-dismiss="modal" style="display: none;">Delete</button>
                  <button id="discount_save" type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
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

@section('jsUP')
    <style type="text/css">
        .discount_uppercase{
            text-transform: uppercase;
        }
    </style>
@endsection

@section('js')
  <script type="text/javascript">

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
    
    $(document).ready(function(){
        
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        screenadjust();
        loadstudents();
        getsection();
        getfees();

        function screenadjust()
        {
            var screen_height = $(window).height();

            $('#main_table').css('height', screen_height - 300);
            // $('.screen-adj').css('height', screen_height - 223);
        }

        $(document).on('change', '#level', function(){
            var levelid = $(this).val()
            getsection()
            getfees()

            if(levelid >= 17 && levelid <= 21)
            {
                $('#div_sections').hide()
                $('#div_courses').show()
                $('#div_subjects').show()
                $('#div_grantee').hide()
                $('#div_strand').hide()
            }
            else if(levelid >= 10 && levelid <= 13)
            {
                $('#div_sections').show()
                $('#div_courses').hide()
                $('#div_subjects').hide()
                $('#div_grantee').show()
                $('#div_strand').hide()
            }
            else if(levelid == 14 || levelid == 15)
            {
                $('#div_sections').show();
                $('#div_courses').hide();
                $('#div_subjects').hide();
                $('#div_grantee').show();
                $('#div_strand').show();
            }
            else
            {
                $('#div_sections').show();
                $('#div_courses').hide();
                $('#div_subjects').hide();
                $('#div_grantee').hide();
                $('#div_strand').hide();
            }

        });

        function getsection()
        {
            levelid = $('#level').val();
            $.ajax({
                url: '{{route('studupdateledgerLoadSections')}}',
                type: 'GET',
                dataType: '',
                data: {
                    levelid:levelid
                },
                success:function(data)
                {
                    if(levelid == 14 || levelid == 15) 
                    {
                        $('#sections').html(data);
                    }
                    else if(levelid >= 17 && levelid <= 21)
                    {
                        $('#courses').html(data);
                    }
                    else
                    {
                        $('#sections').html(data);
                    }
                }
            });
        }

        function getfees()
        {
            var levelid = $('#level').val()
            var syid = $('#syid').val()
            var semid = $('#semid').val()

            $.ajax({
                type: "GET",
                url: "{{route('stud_loadfee')}}",
                data: {
                    levelid:levelid,
                    syid:syid,
                    semid:semid
                },
                dataType: "json",
                success: function (data) {
                    $('#fees').empty()

                    // $.each(data, function (index, val) { 
                    //      var newOption =
                    // });

                    $('#fees').select2({
                        data:data,
                        theme: 'bootstrap4'
                    })
                }
            });
        }

        function loadstudents()
        {
            var levelid = $('#level').val()
            var sectionid = $('#sections').val()
            var courseid = $('#courses').val()
            var subjid = $('#subjects').val()
            var grantee = $('#grantee').val()
            var strand = $('#strand').val()
            var filter = $('#stud_filter').val()
            var syid = $('#syid').val()
            var semid = $('#semid').val()

            $.ajax({
                url: '{{route('studupdateledgerLoadStudents')}}',
                type: 'GET',
                // dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
                data: {
                    levelid:levelid,
                    sectionid:sectionid,
                    courseid:courseid,
                    subjid:subjid,
                    filter:filter,
                    grantee:grantee,
                    strand:strand,
                    syid:syid,
                    semid:semid
                },
                success:function(data)
                {
                    $('#studlist').html(data)
                    btns = $('.btn-reset').length
                    // console.log(btns)
                }
            });
        }

        $(document).on('change', '.filters', function(){
            loadstudents();
        })

        $(document).on('keyup', '#stud_filter', function(){
          loadstudents();  
        })

        $(document).on('change', '#subjects', function(){
            subjid = $(this).val();

            if(subjid != 0)
            {
                $('#courses').val(0);
                $('#courses').trigger('change');
            }
        });

        btns = 0;

        $(document).on('change', '#courses', function(){
            var courseid = $(this).val();

            if(courseid != 0)
            {
                $('#subjects').val(0);
                $('#subjects').trigger('change');
            }
        });

        $(document).on('click', '.btn-reset', function(){
            var studid = $(this).attr("stud-id")
            var syid = $(this).attr('sy')
            var semid = $('#semid').val() //$(this).attr('sem')
            var feesid = $(this).attr('fees-id')
            var tuitionheaderid = $('#fees').val()

            if(tuitionheaderid != 0)
            {
                var feesid = $('#fees').val()
            }
            else{
                var feesid = $(this).attr('fees-id')
            }

            $(this).removeClass('btn-primary')
            $(this).addClass('btn-secondary')

            $.ajax({
                url:"{{route('resetpayment_v3')}}",
                method:'GET',
                data:{
                    studid:studid,
                    feesid:feesid,
                    syid:syid,
                    semid:semid
                },
                dataType:'',
                success:function(data)
                {
                    if(data == 'done')
                    {
                        $('.btn-reset[stud-id="'+studid+'"]').removeClass('btn-secondary');
                        $('.btn-reset[stud-id="'+studid+'"]').addClass('btn-success');
                    }
                }
            });      
            
        });

        $(document).on('click', '#update_all', function(){
            $('.btn-reset').removeClass('btn-primary');
            $('.btn-reset').addClass('btn-secondary');
            updatealllines(0);
        });

        function updatealllines(row, type='')
        {
            var current = $('.btn-reset');

            if(row <= btns-1)
            {
                var studid = current.eq(row).attr("stud-id");
                // var syid = current.eq(row).attr('sy');
                // var semid = current.eq(row).attr('sem');
				var syid = $('#syid').val()
                var semid = $('#semid').val()
                
                if($('#fees').val() == 0)
                {
                    var feesid = current.eq(row).attr('fees-id');
                }
                else{
                    var feesid = $('#fees').val();
                }

                $.ajax({
                    url:"{{route('resetpayment_v3')}}",
                    method:'GET',
                    data:{
                        studid:studid,
                        feesid:feesid,
                        syid:syid,
                        semid:semid
                    },
                    dataType:'',
                    success:function(data)
                    {
                        if(data == 'done')
                        {
                            $('.btn-reset[stud-id="'+studid+'"]').removeClass('btn-secondary');
                            $('.btn-reset[stud-id="'+studid+'"]').addClass('btn-success');
                            
                            row ++;
                            updatealllines(row);
                        }
                    }
                }); 
            }
        }

        $(document).on('change', '#semid', function(){
            getfees()
        })

        

    });

  </script>
  
@endsection