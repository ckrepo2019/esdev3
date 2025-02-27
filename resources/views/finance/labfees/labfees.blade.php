@php
    if(!Auth::check()){
        header("Location: " . URL::to('/'), true, 302);
        exit();
    }
    
    if(auth()->user()->type == 17){
        $extend = 'superadmin.layouts.app2' ?? '';
    }
    else if(Session::get('currentPortal') == 3 || Session::get('currentPortal') == 8){
        $extend = 'registrar.layouts.app' ?? '';
    }
    else if(Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15){
        $extend = 'finance.layouts.app' ?? '';     
    }
@endphp


@extends($extend ?? '')

@section('content')
	<section class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h1>Laboratory Fees</h1>
              
            </div>
            {{-- <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Laboratory Fees</li>
              </ol>
            </div> --}}
          </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content pt-0">
        <div>
            <div class="col-md-4">
                <h3 class="" style="">
                </h3>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-2">
                <select id="filter_sy" class="select2bs4 filter" style="width: 100%">
                    @foreach(DB::table('sy')->orderBy('sydesc')->get() as $sy)
                        @if($sy->isactive == 1)
                        <option value="{{$sy->id}}" selected>{{$sy->sydesc}}</option>
                        @else
                            <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select id="filter_sem" class="select2bs4 filter" style="width: 100%">
                    @foreach(db::table('semester')->get() as $sem)
                        @if($sem->isactive == 1)
                            <option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
                        @else
                            <option value="{{$sem->id}}">{{$sem->semester}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">

            </div>
            <div class="col-md-3">
                <input id="labfee_search" type="search" class="form-control" placeholder="Search">
            </div>
          
            <div class="col-md-1 mt-1">
                <button class="btn btn-primary btn-block btn-sm text-sm" id="btn-new" data-toggle="tooltip" title=""><i class="far fa-plus-square"></i> New</button>
            </div>
            <div class="col-md-1 mt-1">
                <button class="btn btn-warning btn-sm text-sm btn-block" id="btn-dup" data-toggle="tooltip" title="">Duplicate</button>
            </div>
        </div>
      	<div class="main-card card">
      		{{-- <div class="card-header bg-info">
                <div class="row">
                    
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                          
                    </div>  
                </div>
                
      		</div> --}}

            
          
      		<div id="main_table" class="card-body table-responsive p-0" style="height:380px">
                <table class="table table-hover table-sm text-sm">
                    <thead class="">
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th class="text-center">Amount</th>
                        </tr>  
                    </thead> 
                    <tbody id="labfees-list" style="cursor: pointer">
                    
                    </tbody>             
                </table>
      		</div>
      	</div>
    </section>
@endsection

@section('modal')
    <div class="modal fade show" id="modal-labfees" data-backdrop="static" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content text-sm">
                <div id="modal-adj-header" class="modal-header bg-primary">
                    <h4 class="modal-title">Laboratory Fees - <span id="action" data-id="0"></span></h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body overflow-auto" style="height: 230px">
                    <div class="row form-group">
                        <div class="col-md-6">
                            <select id="labfees_sy" class="select2bs4" style="width: 100%">
                                @foreach(db::table('sy')->orderBy('sydesc')->get() as $sy)
                                    @if($sy->isactive == 1)
                                    <option value="{{$sy->id}}" selected>{{$sy->sydesc}}</option>
                                    @else
                                        <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select id="labfees_sem" class="select2bs4" style="width: 100%">
                                @foreach(db::table('semester')->get() as $sem)
                                    @if($sem->isactive == 1)
                                        <option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
                                    @else
                                        <option value="{{$sem->id}}">{{$sem->semester}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <select id="labfees_courseid" class="select2bs4 manageui" style="width: 100%">
                                <option value="0">COURSES - ALL</option>
                                @foreach(db::table('college_courses')->where('deleted', 0)->get() as $course)
                                    <option value="{{$course->id}}">{{$course->courseabrv}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select id="labfees_level" class="select2bs4 manageui" style="width: 100%" disabled>
								<option value="0">GRADE LEVEL</option>
                                @foreach(db::table('gradelevel')->where('acadprogid', 6)->where('deleted', 0)->get() as $level)
                                    <option value="{{$level->id}}">{{$level->levelname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <select id="subj" class="select2bs4 form-control manageui" style="width: 100%">
                                <option value="0">SELECT SUBJECT</option>
                                @foreach(DB::table('college_subjects')->where('deleted', 0)->get() as $subj)
                                    <option value="{{$subj->id}}">{{$subj->subjCode . ' - ' . $subj->subjDesc}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="currency-field" id="amount" pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="Amount">
                        </div>
                    </div>
                </div>
                <div class="modal-body bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                        </div>
                        <div class="col-md-6 text-right">
                            <button id="deletelabfee" type="button" class="btn btn-danger" style="display: none;"><i class="fas fa-save"></i> Delete</button>  
                            <button id="savelabfee" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>  
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>
    <div class="modal fade show" id="modal-duplicate" data-backdrop="static" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content text-sm">
                <div id="modal-adj-header" class="modal-header bg-warning">
                    <h4 class="modal-title">Duplicate Laboratory Fees</h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body bg-light">
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label for="">SOURCE</label>
                            <select id="duplicate_fromsy" class="select2bs4 filter" style="width: 100%">
                                @foreach(db::table('sy')->orderBy('sydesc')->get() as $sy)
                                    @if($sy->isactive == 1)
                                    <option value="{{$sy->id}}" selected>{{$sy->sydesc}}</option>
                                    @else
                                        <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">&nbsp;</label>
                            <select id="duplicate_fromsem" class="select2bs4 filter" style="width: 100%">
                                @foreach(db::table('semester')->get() as $sem)
                                    @if($sem->isactive == 1)
                                        <option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
                                    @else
                                        <option value="{{$sem->id}}">{{$sem->semester}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <hr>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label for="">DESTINATION</label>
                            <select id="duplicate_sy" class="select2bs4 filter" style="width: 100%">
                                @foreach(db::table('sy')->orderBy('sydesc')->get() as $sy)
                                    @if($sy->isactive == 1)
                                    <option value="{{$sy->id}}" selected>{{$sy->sydesc}}</option>
                                    @else
                                        <option value="{{$sy->id}}">{{$sy->sydesc}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">&nbsp;</label>
                            <select id="duplicate_sem" class="select2bs4 filter" style="width: 100%">
                                @foreach(db::table('semester')->get() as $sem)
                                    @if($sem->isactive == 1)
                                        <option value="{{$sem->id}}" selected>{{$sem->semester}}</option>
                                    @else
                                        <option value="{{$sem->id}}">{{$sem->semester}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                        </div>
                        <div class="col-md-6 text-right">
                            <button id="deletelabfee" type="button" class="btn btn-danger" style="display: none;"><i class="fas fa-save"></i> Delete</button>  
                            <button id="labfees_duplicate" type="button" class="btn btn-warning"></i> Duplicate</button>  
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>
{{-- @endsection --}}

{{-- @section('js') --}}

  <script>
    // Jquery Dependency
    var timer, value;
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

        } 
        else {
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
    // document.getElementById("txtdescription").addEventListener("keypress", forceKeyPressUppercase, false);



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

            $(window).resize(function(){
                screenadjust()    
            })

            screenadjust()

            function screenadjust()
            {
                var screen_height = $(window).height();

                $('#main_table').css('height', screen_height - 302)
                // $('#setup_table').css('height', screen_height - 150)
                // $('.screen-adj').css('height', screen_height - 223);
            }

            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });

            function manageui()
            {
                console.log('aaa');
                if($('#labfees_courseid').val() != 0)
                {
                    $('#labfees_level').prop('disabled', false)
                    $('#subj').val(0).trigger('change')
                    $('#subj').prop('disabled', true)
                }
                else{
                    $('#labfees_level').val(0).trigger('change')
                    $('#labfees_level').prop('disabled', true)
                    $('#subj').prop('disabled', false)
                }

                if($('#subj').val() != 0)
                {
                    $('#labfees_courseid').val(0).trigger('change')
                    $('#labfees_courseid').prop('disabled', true)

                    $('#labfees_level').val(0).trigger('change')
                    $('#labfees_level').prop('disabled', true)
                }
                else{
                    $('#labfees_courseid').prop('disabled', false)
                    $('#labfees_level').prop('disabled', false)
                }
                
            }

            $(document).on('select2:close', '.manageui', function(){
                // console.log('aaa');
                manageui();
                
            })

            $(document).on('click', '#btn-new', function(){
                $('#modal-labfees').modal('show');
                $('#action').text('New');
                $('#amount').val('');
                $('#subj').val(0);
                $('#subj').trigger('change');
                $('#deletelabfee').hide();
                manageui()
            });

            $(document).on('select2:close', '#subj', function(){
                $('#amount').focus();
            });

            searchlabfee();

            $(document).on('click', '#savelabfee', function(){
                var subjid = $('#subj').val()
                var amount = $('#amount').val()
                var action = $('#action').text()
                var dataid = $('#action').attr('data-id')
                var courseid =  $('#labfees_courseid').val()
                var syid = $('#labfees_sy').val()
                var semid = $('#labfees_sem').val()
                var levelid = $('#labfees_level').val()

                if(amount != '')
                {   
                    $.ajax({
                        url: '{{route('labfee_append')}}',
                        type: 'GET',
                        dataType: '',
                        data: {
                            subjid:subjid,
                            amount:amount,
                            action:action,
                            dataid:dataid,
                            courseid:courseid,
                            syid:syid,
                            semid:semid,
                            levelid:levelid
                        },
                        success:function(data)
                        {
                            if(data == 'saved')
                            {
                                $('#modal-labfees').modal('hide');
                                searchlabfee();
                            }
                            else
                            {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                })

                                Toast.fire({
                                    type: 'danger',
                                    title: 'Subject already exist.'
                                });               
                            }
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
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        type: 'warning',
                        title: 'Please fill all fields.'
                    });
                }
            });

            function searchlabfee()
            {
                var filter = $('#labfee_search').val();
                var syid = $('#filter_sy').val()
                var semid = $('#filter_sem').val()

                $.ajax({
                    url: '{{route('labfee_search')}}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        filter:filter,
                        syid:syid,
                        semid:semid
                    },
                    success:function(data)
                    {
                        $('#labfees-list').html(data.list);
                    }
                })
            }

            $(document).on('keyup', '#labfee_search', function(){
                var str = $(this).val();
                if(value != str)
                {
                    timer = setTimeout(function(){
                        value = str;
                        searchlabfee();
                    }, 700);
                } 
            });

            $(document).on('change', '.filter', function(){
                searchlabfee()
            })



            $(document).on('click', '#labfees-list tr', function(){
                var dataid = $(this).attr('data-id');

                $.ajax({
                    url: '{{route('labfee_edit')}}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        dataid:dataid
                    },
                    success:function(data)
                    {
                        $('#subj').val(data.subjid);
                        $('#subj').trigger('change');
                        $('#amount').val(data.amount);
                        $('#labfees_sy').val(data.syid).trigger('change')
                        $('#labfees_sem').val(data.semid).trigger('change')
                        $('#labfees_courseid').val(data.courseid).trigger('change')
                        $('#labfees_level').val(data.levelid).trigger('change')

                        $('#modal-labfees').modal('show');
                        $('#action').text('Edit');
                        $('#action').attr('data-id', dataid);
                        $('#deletelabfee').show();
                    }
                });
            });

            $(document).on('click', '#deletelabfee', function(){
                var dataid = $('#action').attr('data-id');


                Swal.fire({
                    title: 'Delete Laboratory Fee?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    console.log(result)
                    if (result.value == true) {
                        $.ajax({
                            url: '{{route('labfee_delete')}}',
                            type: 'GET',
                            dataType: '',
                            data: {
                                dataid:dataid
                            },
                            success:function(data)
                            {
                                $('#modal-labfees').modal('hide');
                                searchlabfee();

                                Swal.fire(
                                    'Deleted!',
                                    '',
                                    'success'
                                );
                            }
                        });
                    }
                });
            });

            $(document).on('click', '#labfees_duplicate', function(){
                var fromsyid = $('#duplicate_fromsy').val()
                var fromsemid = $('#duplicate_fromsem').val()
                var syid = $('#duplicate_sy').val()
                var semid = $('#duplicate_sem').val()

                Swal.fire({
                    title: 'Duplicate Laboratory Fees?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, duplicate it!'
                    }).then((result) => {
                    if (result.value == true) {
                        $.ajax({
                            type: "GET",
                            url: "{{route('labfee_duplicate')}}",
                            data: {
                                syid:syid,
                                semid:semid,
                                fromsyid:fromsyid,
                                fromsemid:fromsemid
                            },
                            // dataType: "dataType",
                            success: function (data) {
                                if(data == 'done')
                                {
                                    Swal.fire(
                                        'DONE',
                                        'Duplicate successful.',
                                        'success'
                                    )        

                                    $('#modal-duplicate').modal('hide')
                                }
                                else if(data == 'nodata')
                                {
                                    Swal.fire(
                                        'Something went wrong',
                                        'Source of laboratory fees has no data',
                                        'error'
                                    )        
                                }
                                else if (data == 'exist'){
                                    Swal.fire(
                                        'Something went wrong',
                                        'Please remove all Laboratory Fees in the current SY/Sem',
                                        'error'
                                    )        
                                }

                                searchlabfee()
                            }
                        })

                    }
                })
            })

            $(document).on('click', '#btn-dup', function(){
                $('#modal-duplicate').modal()
            })


        });
    </script>
@endsection