@extends('principalsportal.layouts.app2')

@section('pagespecificscripts')
  
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    
@endsection

@section('modalSection')

<div class="modal fade" id="modal-default" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Grade Setup Form</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <form method="GET" action="@if (\Session::has('edit')) /updategradesetup  @else /principalstoregradesetup @endif ">
            <div class="modal-body">
                <input id="si" name="si" type="hidden">
                <div class="form-group">
                    <label>Grade Level</label>
                    <select class="form-control select2  @error('gradelevel') is-invalid @enderror" id="gradelevel" name=gradelevel[] multiple="multiple" data-placeholder="Select grade level" style="width: 100%;">
                        @foreach (App\Models\Principal\SPP_Gradelevel::getGradeLevel(null,null,null,null,$apid)[0]->data as $item)
                            @php
                                $withData = false;
                            @endphp
                            @if($errors->any())
                                @if(old('gradelevel')!=null)
                                    @foreach(old('gradelevel') as $glitem)
                                        @if($glitem==$item->id)
                                            <option selected value="{{$item->id}}">{{$item->levelname}}</option>
                                            @php
                                                $withData = true;
                                            @endphp
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                            @if(!$withData)
                                <option value="{{$item->id}}">{{$item->levelname}}</option>
                            @endif
                        @endforeach
                    </select>
                    @if($errors->has('gradelevel'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('gradelevel') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label>Subjects</label>
                    <select class="form-control select2  @error('subject') is-invalid @enderror" id="subject" name=subject[] multiple="multiple" data-placeholder="Select subject" style="width: 100%;">
                        @foreach (App\Models\Principal\SPP_Subject::getAllSubject(null,null,null,null,$apid)[0]->data as $item)
                            
                            @php
                                $withData = false;
                            @endphp
                            @if($errors->any())
                                @if(old('subject')!=null)
                                    @foreach(old('subject') as $glitem)
                                        @if($glitem==$item->id)
                                            @if(Crypt::decrypt($apid)==5)
                                                <option selected value="{{$item->id}}">{{$item->subjtitle}}</option>
                                            @else
                                                <option selected value="{{$item->id}}">{{$item->subjdesc}}</option>
                                            @endif
                                            @php
                                                $withData = true;
                                            @endphp
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                            @if(!$withData)
                                @if(Crypt::decrypt($apid)==5)
                                    <option value="{{$item->id}}">{{$item->subjtitle}}</option>
                                @else
                                    <option value="{{$item->id}}">{{$item->subjdesc}}</option>
                                @endif
                            @endif
                      
                        @endforeach
                    </select>
                    @if($errors->has('subject'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('subject') }}</strong>
                        </span>
                    @endif
                </div>

               
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label>WW</label>
                            <input value="{{old('ww')}}" type="text" class="form-control" id="ww" name="ww" placeholder="Written Works" placeholder="Performance Test" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label>PT</label>
                            <input value="{{old('pt')}}" type="text" class="form-control" id="pt"  name="pt"  placeholder="Performance Test" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label>QA</label>
                            <input value="{{old('qa')}}" type="text" class="form-control" id="qa"  name="qa"  placeholder="Quarterly Assessment" placeholder="Performance Test" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        </div>
                    </div>
                </div>
                <input class=" form-control @error('total') is-invalid @enderror" hidden>
                @if($errors->has('total'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('total') }}</strong>
                    </span>
                @endif
             
                <label class="mt-2">Quarter</label>
                
                <div class="form-group clearfix mb-1">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="q1" name="q[]" value="1" checked>
                                <label for="q1">Q1
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="q2"  name="q[]" value="2" checked>
                                <label for="q2">Q2
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="q3"  name="q[]" value="3" checked>
                                <label for="q3">Q3
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="icheck-success d-inline">
                                <input type="checkbox" id="q4"  name="q[]" value="4" checked>
                                <label for="q4">Q4
                                </label>
                            </div>
                        </div>
                    </div>
                    <input type="text" class="form-control @if (\Session::has('q')) is-invalid @endif" hidden>
                    @if (\Session::has('q'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{\Session::get('q')->message}}</strong>
                        </span>
                    @endif
                </div>
                <input class=" form-control @error('q') is-invalid @enderror" hidden>
                @if($errors->has('q'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('q') }}</strong>
                    </span>
                @endif
               
            </div>
            <div class="modal-footer justify-content-between sb">
                <button onClick="this.form.submit(); this.disabled=true;" type="submit" class="btn btn-info savebutton">Save</button>
            </div>
        <form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection

@section('content')

<section class="content-header">
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Grade Setup</li>
        </ol>
        </div>
    </div>
    </div>
</section>
    <section>
        <div class="row">
            <div class="col-md-12">
                <div class="card main-card principalgradesetup">
                    <div class="card-header">
                        <button class="btn btn-sm btn-info" data-toggle="modal"  data-target="#modal-default" title="Contacts" data-widget="chat-pane-toggle"><i class="fas fa-plus"></i> Add Grade Setup</button>
                        <div class="input-group input-group-sm float-right" style="width: 200px;">
                            <input type="text" id="search" name="table_search" class="form-control float-right" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                   
                    </div>
                    <div class="card-body p-0 table-responsive" id="gradsetupholder">
                        @include('search.principal.gradesetup')
                    </div>
                    <div class="card-footer">
                        <div id="data-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection


@section('footerjavascript')

    <script src="{{asset('js/pagination.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function(){
        pagination('{{$data[0]->count}}',false);
        function pagination(itemCount,pagetype){
            var result = [];
            for (var i = 0; i < itemCount; i++) {
            result.push(i);
            }
            $('#data-container').pagination({
            dataSource: result,
            callback: function(data, pagination) {
                if(pagetype){
                $.ajax({
                    type:'GET',
                    url:'/searchgradesetup',
                    data:{
                    data:$("#search").val(),
                    pagenum:pagination.pageNumber,
                    apid:'{{$apid}}'},
                    success:function(data) {
                    $('#gradsetupholder').empty();
                    $('#gradsetupholder').append(data);
                    }
                })
                }
                pagetype=true;
            },
                hideWhenLessThanOnePage: true,
                pageSize: 10,
            })
        }
        $("#search" ).keyup(function() {
            $.ajax({
            type:'GET',
            url:'/searchgradesetup',
            data:{data:$(this).val(),pagenum:'1',apid:'{{$apid}}'},
            success:function(data) {
                $('#gradsetupholder').empty();
                $('#gradsetupholder').append(data);
                pagination($('#searchCount').val())
            }
            })
        });
        })
    </script>


    <script>


       
    $( document ).ready(function() {


        @if ($errors->any())
            $('#modal-default').modal('show');
        @endif
    

        $(function () {
            $('#gradelevel').select2()
            $('.select2bs4').select2({
            theme: 'bootstrap4'
            })
        })

        $(function () {
            $('#subject').select2()
            $('.select2bs4').select2({
            theme: 'bootstrap4'
            })
        })


    // @if (\Session::has('Inputstatus') && \Session::get('Inputstatus')==false)


    //     $('#modal-default').modal('show');
       
        // $('.savebutton').text('Update Grade Setup');
        //     @if (\Session::has('scs'))

        //         $('#sc').val('{{\Session::get('scs')->message}}')

        //         var gradelevel = '{{\Session::get('scs')->message}}'

        //         $.ajax({
        //             type:'GET',
        //             url:'/getgradelevelwithoutgradesetup',
        //             data:{sc:$('#sc').val()},
        //             success:function(data) {
        //                 $('#su').empty();
        //                 $('#su').append(data)

        //                 @if (\Session::has('sus'))
        //                     $('#su').val('{{\Session::get('sus')->message}}')
        //                 @endif
                        
        //             }
        //         });

        //     @endif

        //     @if (\Session::has('wws'))
        //         $('#ww').val('{{\Session::get('wws')->message}}')
        //     @endif

        //     @if (\Session::has('si'))
        //         $('#si').val('{{\Session::get('si')->message}}')
        //     @endif


        //     @if (\Session::has('pts'))
        //         $('#pt').val('{{\Session::get('pts')->message}}')
        //     @endif
            
        //     @if (\Session::has('qas'))
        //         $('#qa').val('{{\Session::get('qas')->message}}')
        //     @endif

        //     @if (\Session::has('q1'))
        //         $('#q1').prop('checked',true)
        //     @endif

        //     @if (\Session::has('q2'))
        //         $('#q2').prop('checked',true)
        //     @endif

        //     @if (\Session::has('q3'))
        //         $('#q3').prop('checked',true)   
        //     @endif

        //     @if (\Session::has('q4'))
        //         $('#q4').prop('checked',true)
        //     @endif   
        

        // @endif
   
  
            
        $(document).on('change','#sc',function(){
            console.log('sdfsdf');
            // displayPrereq()
            // $.ajax({
            //     type:'GET',
            //     url:'/prinicipalGetSubject',
            //     data:{
            //         sc:$(this).val(),
            //         acad:'{{$apid}}'
            //     },
            //     success:function(data) {
            //         console.log(data);
            //     }
            // })
        })

        $(document).on('change','.savebutton',function(){

            $.ajax({
                type:'GET',
                url:'/principalstoregradesetup',
                data:{
                    sc:$('#sc').val(),
                    su:$('#su').val(),
                    ww:$('#ww').val(),
                    pt:$('#pt').val(),
                    qa:$('#qa').val(),
                    },
                success:function(data) {
                }
            })
        })

        $(document).on('click','.ee',function(){
       
            $('#modal-default').modal('show');
            $('#sc').val($(this).closest('tr')[0].children[1].id)
            $('#ww').val($(this).closest('tr')[0].children[3].innerHTML)
            $('#pt').val($(this).closest('tr')[0].children[4].innerHTML)
            $('#qa').val($(this).closest('tr')[0].children[5].innerHTML)
            $('#si').val($(this)[0].id)
  
            if($(this).closest('tr')[0].children[6].id == 1){
                $("#q1").prop("checked", true);
            }
            if($(this).closest('tr')[0].children[7].id == 1){
                $("#q2").prop("checked", true);
            }
            if($(this).closest('tr')[0].children[8].id == 1){
                $("#q3").prop("checked", true);
            }
            if($(this).closest('tr')[0].children[9].id == 1){
                $("#q4").prop("checked", true);
            }

            var susel = $(this).closest('tr')[0].children[2].innerHTML;
            var suselid = $(this).closest('tr')[0].children[2].id;

            $.ajax({
                type:'GET',
                url:'/getgradelevelwithoutgradesetup',
                data:{sc:$(this).closest('tr')[0].children[1].id},
                success:function(data) {
                    $('#su').empty();
                    data+='<option selected value="'+suselid+'">'+susel+'</option>';
                    $('#su').append(data)
                }
            })

            $('.savebutton').text('Update Grade Setup');
            $('.savebutton').removeClass('bg-info');
            $('.savebutton').addClass('bg-success');
            $("form").attr('action', '/updategradesetup');


        })

        $('#modal-default').on('hidden.bs.modal', function () {

                $(this).find('form').trigger('reset');
                $('#su').empty();
                $('#su').append('<option value="" selected disabled>Select Subjet</option>')
                $('.savebutton').text('Save');
                $("form").attr('action', '/principalstoregradesetup');
                $('.invalid-feedback').remove()
                $('.is-invalid').removeClass('is-invalid')
                
            
        })

        

    });

    </script>

    

@endsection

