@php
      if(!Auth::check()){
            header("Location: " . URL::to('/'), true, 302);
            exit();
      }


      $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }
      else if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
      }
      else if(Session::get('currentPortal') == 4){
         $extend = 'finance.layouts.app';
      }else if(Session::get('currentPortal') == 15){
            $extend = 'finance.layouts.app';
      }else if(Session::get('currentPortal') == 14){
            $extend =  'deanportal.layouts.app2';
      }else if(Session::get('currentPortal') == 8){
            $extend =  'admission.layouts.app2';
      }
      //else if(auth()->user()->type == 3 || auth()->user()->type == 8 ){
            //$extend = 'registrar.layouts.app';
      //}else if(auth()->user()->type == 4){
            //$extend = 'finance.layouts.app';
      //}else if(auth()->user()->type == 15 ){
            //$extend = 'finance.layouts.app';
      //}else if(auth()->user()->type == 14 ){
            //$extend =  'deanportal.layouts.app2';
      else if(auth()->user()->type == 6 ){
            $extend =  'adminportal.layouts.app2';
      }else{
            if(isset($check_refid->refid)){
                  if($check_refid->refid == 26){
                        $extend = 'registrar.layouts.app';
                  }else if($check_refid->refid == 28){
                        $extend = 'officeofthestudentaffairs.layouts.app2';
                  }else if($check_refid->refid == 29){
                        $extend = 'idmanagement.layouts.app2';
                  }else if($check_refid->refid == 31){
                        $extend = 'guidance.layouts.app2';
                  }else if($check_refid->refid == 30){
                        $extend = 'encoder.layouts.app2';
                  }
            }
            
      }

      $refid = $check_refid->refid;
     

@endphp

@extends($extend)

@section('content')

<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Signatories</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Signatories</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>
<div class="card">
    <div class="card-header">
        <div class="row mb-2">
            <div class="col-md-3">
                <label>Form Selection</label>
                <select class="form-control" id="select-formid">
                    <option value="report_masterlist">Masterlist</option>
                    <option value="report_enrollmentsummary">Enrolled Student List > Enrollment Summary</option>
                    @for($x = 1; $x <= 10; $x++)
                        @if($x != 3 && $x != 8 && $x != 9 )
                        {{-- <option value="form{{$x}}" @if($x == 3 || $x == 7 || $x == 8)disabled @endif>School Form {{$x}}</option> --}}
                            <option value="form{{$x}}">School Form {{$x}}</option>
                            @if($x == 5)
                            <option value="form{{$x}}a">School Form {{$x}}A</option>
                            <option value="form{{$x}}b">School Form {{$x}}B</option>
                            @endif                        
                        @endif
                    @endfor
                    @if(DB::table('academicprogram')->where('id', 6)->count()>0)
                    <option value="tor">Transcript of Records (TOR)</option>
                    @endif
                </select>
            </div>
            <div class="col-md-3">
                <label>School Year</label>
                <select class="form-control" id="select-syid">
                    @foreach(DB::table('sy')->get() as $eachsy)
                        <option value="{{$eachsy->id}}" @if($eachsy->isactive == 1) selected @endif>{{$eachsy->sydesc}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Academic Prog.</label>
                <select class="form-control" id="select-acadprogid">
                    <option value="0">All</option>
                    @foreach(DB::table('academicprogram')->where('id','!=','7')->get() as $eachacadprog)
                        <option value="{{$eachacadprog->id}}">{{$eachacadprog->acadprogcode}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Grade Level</label>
                <select class="form-control" id="select-levelid"></select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="button" class="btn btn-primary btn-sm" id="btn-getsignatories"><i class="fa fa-sync"></i> Get Signatories</button>
            </div>
        </div>
    </div>
</div>
<div id="container-signatories"></div>
@endsection

@section('footerscripts')
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
<script>
    $(document).ready(function(){
        function getlevels()
        {
            $('#select-levelid').empty()
            $.ajax({
                url: '/setup/signatories/getlevelids',
                type:'GET',
                dataType: 'json',
                data: {
                    acadprogid       :  $('#select-acadprogid').val()
                },
                success:function(data) {
                    $('#select-levelid').append(
                        '<option value="0">All</option>'
                    )
                    if(data.length > 0)
                    {
                        $.each(data, function(key,value){
                            $('#select-levelid').append(
                                '<option value="'+value.id+'">'+value.levelname+'</option>'
                            )
                        })
                    }
                }
            })
        }
        function getacadprogs()
        {
            $.ajax({
                url: '/setup/signatories/getacadprogs',
                type:'GET',
                dataType: 'json',
                data: {
                    formid       :  $('#select-formid').val()
                },
                success:function(data) {
                    $('#select-acadprogid').empty()
                    if(data.length > 0)
                    {
                            $('#select-acadprogid').append(
                                '<option value="0">All</option>'
                            )
                        $.each(data, function(key,value){
                            $('#select-acadprogid').append(
                                '<option value="'+value.id+'">'+value.acadprogcode+'</option>'
                            )
                        })
                        if($('#select-formid').val() == 'tor')
                        {
                            $('#select-acadprogid').empty();
                                    $('#select-acadprogid').append(
                                        '<option value="6">COLLEGE</option>'
                                    )
                        }
                    }
                }
            })
            getlevels()
        }
        getacadprogs()
        $('#select-formid').on('change', function(){
            if($('#select-formid').val() != 'form4' && $('#select-formid').val() != 'form7')
            {
                $('#select-acadprogid').closest('div').show()
                if($('#select-formid').val() == 'form6')
                {
                $('#select-levelid').closest('div').hide()
                }else{
                $('#select-levelid').closest('div').show()
                }
                if($('#select-formid').val() == 'form10')
                {
                    $('#select-syid').closest('div').hide()
                $('#select-levelid').closest('div').hide()
                }
                getacadprogs()
            }else{
                $('#select-syid').closest('div').show()
                $('#select-acadprogid').closest('div').hide()
                $('#select-levelid').closest('div').hide()
            }
            $('#container-signatories').empty()
        })
        $('#select-acadprogid').on('change', function(){
            getlevels()
            $('#container-signatories').empty()
        })
        $('#btn-getsignatories').on('click', function(){
            var formid = $('#select-formid').val();
            var syid = $('#select-syid').val();
            var acadprogid = $('#select-acadprogid').val();
            var levelid = $('#select-levelid').val();

            if(formid == 'form10')
            {
                syid = 0;
                levelid = 0;
            }

            $.ajax({
                url: '/setup/signatories/getsignatories',
                type:'GET',
                data: {
                    formid      : formid,
                    syid        : syid,
                    acadprogid  : acadprogid,
                    levelid     : levelid
                },
                success:function(data) {
                    $('#container-signatories').empty()
                    $('#container-signatories').append(data)
                }
            })
        })
        $(document).on('click','#btn-savechanges', function(){
            var formid = $('#select-formid').val();
            var syid = $('#select-syid').val();
            var acadprogid = $('#select-acadprogid').val();
            var levelid = $('#select-levelid').val();

            if(formid == 'form4' || formid == 'form6')
            {
                acadprogid = 0;
                levelid = 0;
            }
            if(formid == 'form10')
            {
                syid = 0;
                levelid = 0;
            }
            var thiscard = $(this).closest('.card');
            var inputs = thiscard.find('input');
            var signatories = [];

            if(inputs.length > 0)
            {
                inputs.each(function(){
                    obj = {
                        id : $(this).attr('data-id'),
                        name : $(this).val(),
                        title : $(this).attr('title'),
                        description : $(this).attr('description')
                    }
                    signatories.push(obj)
                })
            }

            $.ajax({
                url: '/setup/signatories/savechanges',
                type:'GET',
                data: {
                    formid      : formid,
                    syid        : syid,
                    acadprogid  : acadprogid,
                    levelid     : levelid,
                    signatories : JSON.stringify(signatories)
                },
                success:function(data) {
                        
                    if(data == 1)
                    {                        
                        toastr.success('Updated successfully!', 'Signatories')
                    }
                    
                }
            })                        
        })
        $(document).on('click','.btn-add-signatory', function(){
            var displayhtml = '<div class="row mb-2" data-id="0">'+
                                '<div class="col-md-3">'+
                                    '<input type="text" class="form-control input-title" placeholder="E.g. Certified Correct"/>'+
                                '</div>'+
                                '<div class="col-md-4">'+
                                    '<input type="text" class="form-control input-name" placeholder="Name"/>'+
                                '</div>'+
                                '<div class="col-md-3">'+
                                    '<input type="text" class="form-control input-label" placeholder="E.g. School Head"/>'+
                                '</div>'+
                                '<div class="col-md-2 p-1 text-right">'+
                                    '<button type="button" class="btn btn-sm btn-outline-success btn-save"><i class="fa fa-check"></i></button>'+
                                    '<button type="button" class="btn btn-sm btn-outline-danger danger btn-remove ml-2"><i class="fa fa-times"></i></button>'+
                                '</div>'+
                            '</div>';
            var thiscontainer = $(this).closest('.card').find('.container-new-signatories');
            thiscontainer.append(displayhtml)
        })
        $(document).on('click', '.btn-save', function(){
            var thisrow = $(this).closest('.row');
            var dataid = $(this).closest('.row').attr('data-id');
            var title = $(this).closest('.row').find('.input-title').val();
            var name = $(this).closest('.row').find('.input-name').val();
            var label = $(this).closest('.row').find('.input-label').val();
            if(name.replace(/^\s+|\s+$/g, "").length == 0){
                toastr.warning('Fill in required field(s)!', 'Signatories')
                $(this).closest('.row').find('.input-name').css('border','1px solid red')
            }else{
                var formid = $('#select-formid').val();
                var syid = $('#select-syid').val();
                var acadprogid = $('#select-acadprogid').val();
                var levelid = $('#select-levelid').val();

                if(formid == 'form4' || formid == 'form6')
                {
                    acadprogid = 0;
                    levelid = 0;
                }
                if(formid == 'form10')
                {
                    syid = 0;
                    levelid = 0;
                }


                $.ajax({
                    url: '/setup/signatories/savechanges',
                    type:'GET',
                    data: {
                        formid      : formid,
                        syid        : syid,
                        acadprogid  : acadprogid,
                        levelid     : levelid,
                        dataid     : dataid,
                        title     : title,
                        name     : name,
                        label     : label
                        // signatories : JSON.stringify(signatories)
                    },
                    success:function(data) {
                        thisrow.attr('data-id', data);
                        thisrow.find('.btn-remove').remove()
                        toastr.success('Saved successfully!', 'Signatories')
                            
                        // if(data == 0)
                        // {                        
                        //     toastr.success('Saved successfully!', 'Signatories')
                        // }
                        
                    }
                })  

            }
        })
        $(document).on('click','.btn-remove', function(){
            var thisrow = $(this).closest('.row');
            thisrow.remove();
        })
        $(document).on('click','.btn-delete', function(){
            var thisrow = $(this).closest('.row');
            var dataid = $(this).attr('data-id');

            var thisbutton = $(this);
            Swal.fire({
                title: 'Are you sure to delete this signatory?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/setup/signatories/deletesignatory',
                        type:'GET',
                        data: {
                            id      : dataid
                        },
                        success:function(data) {
                            if(data == 1)
                            {                        
                                toastr.success('Deleted successfully!', 'Signatories')
                                thisrow.remove();
                            }                    
                        }
                    })  
                }
            })
        })
    })
</script>
@endsection