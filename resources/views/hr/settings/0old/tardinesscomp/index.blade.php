

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
@extends('hr.layouts.app')
@section('content')
<style>
    .mobile{
        display: none;
    }
    @media only screen and (max-width: 600px) {
        .mobile {
            display: block;
        }
        .web {
            display: none;
        }
    }
    .container {padding:20px;}
.popover {width:170px;max-width:170px;}
.popover-content h4 {
  color: #00A1FF;
}
.popover-content h4 small {
  color: black;
}
.popover-content button.btn-primary {
  color: #00A1FF;
  border-color:#00A1FF;
  background:white;
}

.popover-content button.btn-default {
  color: gray;
  border-color:gray;
}

.dataTables_wrapper .dataTables_info {
    clear:none;
    margin-left:10px;
    padding-top:0;
}
.swal2-header {
    border: hidden;
}
</style>

@php
    $activedepts = collect($computations)->where('isactive','1')->unique('departmentid')->values();
@endphp
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Tardiness Computation Setup</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Tardiness Computation Setup</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
   </section>
   <div class="row">
       <div class="col-md-4">
            <div class="card" style="border: none; min-height: 600px;">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Departments</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-1">
                    <div class="row mt-2 mb-2">
                        <div class="col-md-2">
                            <div class="form-group m-0">
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" id="department-0" data-id="0" @if(collect($activedepts)->where('departmentid','0')->count()>0) checked @endif/>
                                    <label class="custom-control-label" for="department-0">&nbsp;</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <button type="button" class="each-department btn btn-sm btn-outline-success btn-block p-0 text-left" data-deptid="0">&nbsp;&nbsp;&nbsp;&nbsp;ALL DEPARTMENTS</button>
                        </div>
                    </div>
                    <hr/>
                    @if(count($departments)>0)
                        @foreach($departments as $department)
                        <div class="row mb-2">
                            <div class="col-md-2">
                                <div class="form-group m-0">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="department-{{$department->id}}" data-id="{{$department->id}}" @if(collect($activedepts)->where('departmentid',$department->id)->count()>0) checked @endif/>
                                        <label class="custom-control-label" for="department-{{$department->id}}">&nbsp;</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <button type="button" class="each-department btn btn-sm btn-outline-success btn-block p-0 text-left" data-deptid="{{$department->id}}">&nbsp;&nbsp;&nbsp;&nbsp;{{$department->department}}</button>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
       </div>
       <div class="col-md-8">
            <div class="card" style="border: none; min-height: 600px;">
                <div class="card-body p-1" id="container-brackets">
                    
                </div>
                <div class="card-footer text-right">
                    <button type="button" class="btn btn-sm btn-primary" id="btn-submit">Submit New Time Brackets</button>
                </div>
            </div>
        </div>
   </div>
@endsection
@section('footerscripts')
    <script>
        $(document).ready(function(){
            $('#btn-submit').hide()
            $('.each-department').on('click', function(){
                var deptid = $(this).attr('data-deptid')
                $('.each-department').removeClass('btn-success')
                $('.each-department').addClass('btn-outline-success')
                $(this).removeClass('btn-outline-success');
                $(this).addClass('btn-success');
                $.ajax({
                    url: '/hr/tardinesscomp/getbrackets',
                    type:"GET",
                    data:{
                        deptid: deptid
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    success: function(data){
                        $('#container-brackets').empty()
                        $('#container-brackets').append(data)
                    }
                })
            })
            $(document).on('click','#btn-addbracket', function(){
                var deptid = $(this).attr('data-deptid');
                var appendhtml = '<div class="row mb-2 each-newbracket" data-id="0" data-deptid="'+deptid+'">'+
                                    '<div class="col-md-3">'+
                                        '<input type="number" class="input-from-new form-control form-control-sm"/>'+
                                    '</div>'+
                                     '<div class="col-md-3">'+
                                        '<input type="number" class="input-to-new form-control form-control-sm"/>'+
                                    '</div>'+
                                    '<div class="col-md-2"hidden>'+
                                        '<select class="select-timetype-new form-control form-control-sm">'+
                                            '<option value="1">mins.</option>'+
                                            '<option value="2">hrs.</option>'+
                                        '</select>'+
                                    '</div>'+
                                    '<div class="col-md-3">'+
                                        '<select class="select-deducttype-new  form-control form-control-sm">'+
                                            '<option value="1">Fixed Amount</option>'+
                                            '<option value="2">Daily Rate %</option>'+
                                        '</select>'+
                                    '</div>'+
                                    '<div class="col-md-2">'+
                                        '<input type="number" class="input-amount-new form-control form-control-sm"/>'+
                                    '</div>'+
                                '<div class="col-md-1 pt-2 text-danger">'+
                                    '<i class="fa fa-times bracket-remove"></i>'
                                '</div>'+
                            '</div>';
                $('#container-addbrackets').append(appendhtml)
                
                $('#btn-submit').show()
            })
            $(document).on('click', '.bracket-remove', function(){
                $(this).closest('.row').remove()
                if($('.each-newbracket').length == 0)
                {
                    $('#btn-submit').hide()
                }
            })
            $('#btn-submit').on('click', function(){
                var newbrackets = [];
                var validation = 0;
                $('.each-newbracket').each(function(){
                    var eachvalidation = 0;

                    if($(this).find('.input-from-new').val().replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        eachvalidation+=1;
                        $(this).find('.input-from-new').css('border','1px solid red')
                    }
                    if($(this).find('.input-to-new').val().replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        eachvalidation+=1;
                        $(this).find('.input-to-new').css('border','1px solid red')
                    }
                    if($(this).find('.input-amount-new').val().replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        eachvalidation+=1;
                        $(this).find('.input-amount-new').css('border','1px solid red')
                    }
                    if(eachvalidation == 0)
                    {
                        obj = {
                            deptid : $(this).attr('data-deptid'),
                            latefrom : $(this).find('.input-from-new').val(),
                            lateto : $(this).find('.input-to-new').val(),
                            timetype : $(this).find('.select-timetype-new').val(),
                            deducttype : $(this).find('.select-deducttype-new').val(),
                            amount : $(this).find('.input-amount-new').val()
                        }
                        newbrackets.push(obj);
                    }else{
                        validation+=1;
                    }

                })
                if(validation == 0)
                {
                    $.ajax({
                        url: '/hr/tardinesscomp/addbrackets',
                        type:"GET",
                        data:{
                            brackets: JSON.stringify(newbrackets)
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){    
                            if(data == 1)
                            {                            
                                toastr.success('Added successfully!', 'Time Braket')
                                window.location.reload()
                            }     
                        }
                    })
                }else{
                    toastr.warning('Please fill in the required fields!', 'New Time Brakets')
                }
            })
            $('.custom-control-input').on('click', function(){
                var deptid = $(this).attr('data-id');
                var isactive = 0;
                if ( $(this).is(':checked') ) {
                    isactive = 1
                } 
                $.ajax({
                    url: '/hr/tardinesscomp/activation',
                    type:"GET",
                    data:{
                        deptid      : deptid,
                        isactive    : isactive
                    },
                    // headers: { 'X-CSRF-TOKEN': token },,
                    success: function(data){
                        if(data == 1)
                        {                            
                            toastr.success('Updated successfully!', 'Activation')
                        }else if(data == 3){
                            toastr.warning('No brackets found!', 'Activation')
                        }
                    }
                })

            });
        })
    </script>
@endsection

