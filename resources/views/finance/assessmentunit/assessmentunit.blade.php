@extends('finance.layouts.app')

@section('content')
	{{-- <section class="content-header">
        <div class="container-fluid p-0">
          <div class="row">
            <div class="col-sm-6">
              <!-- <h1>Finance</h1> -->
              
            </div>
            <div class="col-sm-6 p-0">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Assessment Unit</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
    </section> --}}
    <section class="content p-0">
        <div class="row">
            <div class="col-md-12">
                <h1 class="">
                    Assessment Unit
                </h1>
            </div>
        </div>
      	<div class="main-card card">
      		<div class="card-header">
                <div class="row">
                    <div class="text-lg col-md-4">
                        
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                          
                    </div>  
                </div>
                <div class="row">
                    <div class="col-md-7">
                    
                    </div>
                    <div class="col-md-3">
                        <input id="au_search" type="search" class="form-control" placeholder="Search">
                    </div>
                  
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-block" id="btn-new" data-toggle="tooltip" title=""><i class="far fa-plus-square"></i> Create</button>
                    </div>
                </div>
      		</div>
          
      		<div class="card-body table-responsive p-0" style="height:380px">
                <table class="table table-hover table-sm text-sm">
                    <thead class="">
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Description</th>
                            <th class="text-center">Lec Unit</th>
                            <th class="text-center">Lab Unit</th>
                            <th class="text-center">Asseessment Unit</th>
                        </tr>  
                    </thead> 
                    <tbody id="au_list" style="cursor: pointer">
                    
                    </tbody>             
                </table>
      		</div>
      	</div>
    </section>
@endsection

@section('modal')
    <div class="modal fade show" id="modal-au" data-backdrop="static" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content text-sm">
                <div id="modal-adj-header" class="modal-header bg-primary">
                    <h4 class="modal-title" data-action="">Assessment Unit</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body overflow-auto">
                    <div class="row">
                        <select id="au_subj" class="select2bs4 form-control" style="width: 100%">
                            <option value="0">Select Subject</option>
                            @foreach(DB::table('college_subjects')->where('deleted', 0)->get() as $subj)
                                <option value="{{$subj->id}}">{{$subj->subjCode . ' - ' . $subj->subjDesc}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mt-3">
                        <label>Lecture Units</label>
                        <input id="au_lecunits" class="form-control" type="" name="" disabled="">
                    </div>
                    <div class="row mt-3">
                        <label>Laboratory Units</label>
                        <input id="au_labunits" class="form-control" type="" name="" disabled="">
                    </div>
                    <div class="row mt-3">
                        <label>Assessment Unit</label>
                        <input id="au_units" class="form-control" type="number" name="">
                    </div>
                </div>
                <div class="modal-body bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                        </div>
                        <div class="col-md-6 text-right">
                            <button id="deleteau" type="button" class="btn btn-danger" style="display: none;"><i class="fas fa-trash-alt"></i> Delete</button>  
                            <button id="saveau" type="button" class="btn btn-primary" data-id="0"><i class="fas fa-save"></i> Save</button>  
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>
@endsection

@section('js')

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

            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });

            au_load();

            function au_load()
            {
                var filter = $('#au_search').val();

                $.ajax({
                    url: '{{route('au_search')}}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        filter:filter
                    },
                    success:function(data)
                    {
                        $('#au_list').html(data.list);
                    }
                });
                
            }

            $(document).on('keyup', '#au_search', function(){
                au_load();
            })

            $(document).on('change', '#au_subj', function(){
                var subjid = $(this).val();

                $.ajax({
                    url: '{{route('au_subjinfo')}}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        subjid:subjid
                    },
                    success:function(data)
                    {
                        $('#au_lecunits').val(data.lecunits);
                        $('#au_labunits').val(data.labunits);
                        $('#au_units').focus();
                    }
                });
            });

            $(document).on('click', '#saveau', function(){
                var subjid = $('#au_subj').val();
                var units = $('#au_units').val();
                var action = $('.modal-title').attr('data-action');

                if(action == 'new')
                {
                    $.ajax({
                        url: '{{route('au_savesubj')}}',
                        type: 'GET',
                        dataType: '',
                        data: {
                            subjid:subjid,
                            units:units,
                            action:action
                        },
                        success:function(data)
                        {
                            if(data == 'done')
                            {
                                au_load();
                                $('#modal-au').modal('hide');
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
                    var dataid = $(this).attr('data-id');

                    $.ajax({
                        url: '{{route('au_update')}}',
                        type: 'GET',
                        dataType: '',
                        data: {
                            subjid:subjid,
                            units:units,
                            dataid:dataid
                        },
                        success:function(data)
                        {
                            if(data == 'done')
                            {
                                au_load();
                                $('#modal-au').modal('hide');
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
            });

            $(document).on('click', '#btn-new', function(){
                $('#modal-au').modal('show');
                $('#au_lecunits').val('');
                $('#au_labunits').val('');
                $('#au_units').val('');
                $('#au_subj').val(0);
                $('#au_subj').trigger('change');
                $('#deleteau').hide();
                $('.modal-title').attr('data-action', 'new');
            });
            
            $(document).on('click', '#au_list tr', function(){
                var dataid = $(this).attr('data-id');

                $.ajax({
                    url: '{{route('au_edit')}}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        dataid:dataid      
                    },
                    success:function(data)
                    {
                        $('#au_subj').val(data.subjid)
                        $('#au_subj').trigger('change');
                        $('#au_lecunits').val(data.lecunits);
                        $('#au_labunits').val(data.labunits);
                        $('#au_units').val(data.units);

                        $('.modal-title').attr('data-action', 'edit');
                        $('#saveau').attr('data-id', dataid);
                        $('#deleteau').show();
                        $('#modal-au').modal();
                    }
                });
                
            });

            $(document).on('click', '#deleteau', function(){
                var dataid = $('#saveau').attr('data-id');

                Swal.fire({
                    title: 'Are you sure?',   
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value == true) {
                        $.ajax({
                            url: '{{route('au_delete')}}',
                            type: 'GET',
                            dataType: '',
                            data: {
                                dataid:dataid
                            },
                            success:function(data)
                            {
                                Swal.fire(
                                  'Deleted!',
                                  '',
                                  'success'
                                );

                                au_load();
                                $('#modal-au').modal('hide');
                            }
                        });
                    }
                })
            })
        });
    </script>
@endsection