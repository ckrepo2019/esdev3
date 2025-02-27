@extends('teacher.layouts.app')
@section('content')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<style>
    .color-palette                  { display: block; height: 35px; line-height: 35px; text-align: left; padding-left: .75rem; }
    .color-palette.disabled         { text-align: center; padding-right: 0; display: block; }
    .color-palette-set              { margin-bottom: 15px; }
    .color-palette.disabled span    { display: block; text-align: left; padding-left: .75rem; }
    .color-palette-box h4           { position: absolute; left: 1.25rem; margin-top: .75rem; color: rgba(255, 255, 255, 0.8); font-size: 12px; display: block; z-index: 7; }
</style>
<div>
    <nav class="" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="active breadcrumb-item" aria-current="page">Virtual Classrooms</li>
        </ol>
    </nav>
</div>
<div class="card">
    <div class="card-header">
        <button type="button" class="btn btn-sm btn-primary" id="virtualclassroom"><i class="fa fa-plus"></i> Add new</button>
    </div>
    @if(count($classrooms)>0)
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" >
                <div class="row">
                    <div class="col-sm-12">
                        <table id="example1" style="font-size: 12px" class="table table-bordered table-striped dataTable text-uppercase" role="grid" aria-describedby="example1_info">
                            <thead>
                                <tr>
                                    <th>Class Name</th>
                                    <th>Date Created</th>
                                    {{-- <th style="width: 20%">&nbsp;</th> --}}
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($classrooms as $classroom)
                                    <tr>
                                        <td>{{$classroom->classroomname}}</td>
                                        <td>{{$classroom->createddatetime}}</td>
                                        {{-- <td>
                                            <div class="input-group">
                                                <input type="password" class="form-control pwd" value="{{$classroom->password}}">
                                                <span class="input-group-append">
                                                  <button class="btn btn-default reveal" type="button"><i class="fa fa-eye"></i></button>
                                                </span>          
                                            </div>
                                        </td> --}}
                                        <td>
                                            <form action="/virtualclassroomvisit" method="get">
                                                @csrf
                                                <input type="hidden" name="classroomid" value="{{Crypt::encrypt($classroom->id)}}"/>
                                                <button type="submit" class="btn btn-warning btn-sm">Visit</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
<script src="{{asset('assets/scripts/gijgo.min.js')}}" ></script>
<!-- DataTables -->
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>
    $(document).ready(function(){
        
        $("#example1").DataTable({
            pageLength : 10,
            lengthMenu: ['Show All'],
            "paging"    : false,
            "bfilter": false
        });
        $(document).on('click', '.classroom',function(){
            var codex = $(this).attr('id');
            window.open('/virtualclassroom'+'/'+codex+'','newwindow','width=700,height=700,top=0, left=960');
        })
        $(document).ready(function(){
            $(".reveal").on('click',function() {
                var $pwd = $(this).closest('.input-group').find('.pwd');
                if ($pwd.attr('type') === 'password') {
                    $pwd.attr('type', 'text');
                } else {
                    $pwd.attr('type', 'password');
                }
            });
                    
            $(document).on('click','#virtualclassroom',function(){
                Swal.fire({
                    title:  'Class Name',
                    html:   '<div style="text-align: left" id="passwordcontainer">'+
                                '<input type="text" class="form-control" name="classroomname" id="classroomname"/>'+
                                // '<br/>'+
                                // '<div class="form-group clearfix">'+
                                //     '<div class="icheck-primary d-inline">'+
                                //         '<input type="checkbox" id="getpassword">'+
                                //         '<label for="getpassword">'+
                                //             'Set password (Optional)'+
                                //         '</label>'+
                                //     '</div>'+
                                // '</div>'+
                                // '<input type="text" name="password" hidden/>'+
                            '</div>',
                    confirmButtonText: 'Create',
                    closeOnConfirm: false,
                    showCancelButton: true,
                    allowOutsideClick: false,
                    preConfirm: () => {
                        if($('input[name="classroomname"]').val().replace(/^\s+|\s+$/g, "").length == 0){
                            Swal.showValidationMessage(
                                "Enter Classroom name"
                            );
                            $('#classroomname').css('border','1px solid red');
                        }else{

                            $('#classroomname').css('border','1px solid green');
                            Swal.showValidationMessage(
                                "..."
                            );
                            
                            $('#classroomname').removeClass('swal2-inputerror')
                            var joinstatus = 0;
                            var classroomid = $(this).attr('id');
                            var classroomname = $('#classroomname').val();
                            $.ajax({
                                url: '/virtualclassroomcheckname',
                                type:"GET",
                                dataType:"json",
                                data:{
                                    classroomname    :   $('#classroomname').val(),
                                    classroompassword:   $('input[name="password"]').val()
                                },
                                success: function(data){
                                    console.log(data)
                                    if(data == '1'){
                                        Swal.showValidationMessage(
                                            "Classroom already exists!"
                                        );
                                    }else{
                                        
                                        // window.open('/virtualclassroom'+'/'+data.id+'','newwindow','width=700,height=700,top=0, left=960');
                                        // // return false
                                        window.location.reload();
                                    }
                                }
                            })
                        }
                    }
                })
                $(document).on('click', '#getpassword', function(){
                    if($(this).prop('checked') == true)
                    {
                        $('input[name="password"]').removeAttr('hidden')
                        $.ajax({
                            url: '/virtualclassroomgeneratepassword',
                            type: 'GET',
                            dataType: 'json',
                            complete:function(data){
                                $('input[name="password"]').val(data.responseText)
                            }   
                        })
                    }else{
                        $('input[name="password"]').val('')
                        $('input[name="password"]').attr('hidden', true)
                    }
                })
            })
        })
    })
    
</script>
@endsection