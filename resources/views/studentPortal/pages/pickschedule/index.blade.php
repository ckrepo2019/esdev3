@extends('studentPortal.layouts.app2')


@section('content')
<link rel="stylesheet" href="{{asset('css/pagination.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<style>
    .modal {
position: fixed;
top: 0;
right: 0;
bottom: 0;
left: 0;
/* top: 5%;
right: 5%;
bottom: 5%;
left: 5%; */
overflow: hidden;
}

.modal-dialog {
position: fixed;
margin: 0;
/* width: 90%;
height: 90%; */
width: 100%;
height: 100%;
padding: 0;
}
@media (min-width: 576px)
{
    .modal-dialog {
        max-width:  unset !important;
        margin: unset !important;
    }
}
.modal-content {
position: absolute;
top: 0;
right: 0;
bottom: 0;
left: 0;
border: 2px solid #3c7dcf;
border-radius: 0;
box-shadow: none;
}

.modal-header {
position: absolute;
top: 0;
right: 0;
left: 0;
/* height: 50px; */
padding: 10px;
background: #6598d9;
border: 0;
}

.modal-title {
font-weight: 300;
font-size: 2em;
color: #fff;
line-height: 30px;
}

.modal-body {
position: absolute;
top: 50px;
bottom: 60px;
width: 100%;
font-weight: 300;
overflow: auto;
     background-color: rgba(0,0,0,.0001) !important;
}

.modal-footer {
position: absolute;
right: 0;
bottom: 0;
left: 0;
height: 60px;
padding: 10px;
background: #f1f3f5;
}
::-webkit-scrollbar {
-webkit-appearance: none;
width: 10px;
background: #f1f3f5;
border-left: 1px solid darken(#f1f3f5, 10%);
}

::-webkit-scrollbar-thumb {
background: darken(#f1f3f5, 20%);
}

</style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-7">
                    <h4>Custom Pick Scheduling</h4>
                </div>
                <div class="col-sm-5">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Custom Pick Scheduling</li>
                        {{-- <li class="breadcrumb-item active"></li> --}}
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content ">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-7">
                    <div class="card" >
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 p-1">
                                    Picked Schedules
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="height: 700px; overflow: scroll;" id="pickedschedulescontainer">

                        </div>
                    </div>
                </div>
                <div class="col-5">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 text-right p-0">
                                    <input class="filter form-control" placeholder="Search Subject" />
                                </div>
                            </div>
                        </div>
                        <div class="card-body" style="height: 700px; overflow: scroll;">
                            <div class="row">
                                <div class="col-12">
                                    @if(count($subjects) >0)
                                    {{-- <div id="accordion" > --}}
                                        @foreach ($subjects as $subject)
                                            <div class="card eachsubject m-0" style="border: none; box-shadow: unset !important;"  data-string="{{$subject->subjectcode}} {{$subject->subjectname}} {{$subject->levelname}} 
                                                {{-- @if($subject->semid == 1) 1ST SEMESTER @elseif($subject->semid == 2) 2ND SEMESTER @endif    --}}
                                                 <">
                                                <div class="card-body p-0">
                                                    <button type="button" class="btn btn-block text-left m-0 btn-view-schedule" style="border: 1px solid #ddd" data-id="{{$subject->subjectid}}" data-subjectname="{{$subject->subjectcode}} - {{$subject->subjectname}}">
                                                    {{$subject->subjectcode}} - {{$subject->subjectname}}<br/>
                                                    <span class="badge badge-info">{{$subject->levelname}}</span> 
                                                    {{-- <span class="badge badge-info">
                                                        @if($subject->semid == 1)
                                                            1ST SEMESTER
                                                        @elseif($subject->semid == 2)
                                                            2ND SEMESTER
                                                        @endif    
                                                    </span> --}}
                                                </button>
                                                </div>
                                                {{-- <div class="card-header">
                                                    <a class="a-view" data-toggle="collapse" data-parent="#accordion" href="#collasesubjectid{{$subject->subjectid}}" data-id="{{$subject->subjectid}}">
                                                    {{$subject->subjectcode}} - {{$subject->subjectname}}
                                                    </a>
                                                </div>
                                                <div id="collasesubjectid{{$subject->subjectid}}" class="panel-collapse collapse in">
                                                    <div class="card-body" id="eachsubject-schedcontainer{{$subject->subjectid}}">
                                                        
                                                    </div>
                                                </div> --}}
                                            </div>
                                        @endforeach
                                    {{-- </div> --}}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal-schedule" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-subject-name"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row"  id="select-schedule-container">
                        
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-modal-close">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-modal-submit">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

{{-- @section('footerjavascript') --}}

    <script src="{{asset('js/pagination.js')}}"></script>
    <!-- DataTables -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <!-- Toastr -->
    <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
    <script>
        $(document).ready(function(){

            function getpickedschedules()
            {
                
                $.ajax({
                    url: '/student/pickschedule/viewpickedschedules',
                    type: 'GET',
                    success:function(data)
                    {
                        $('#pickedschedulescontainer').empty()
                        $('#pickedschedulescontainer').append(data)
                    }
                })
            }

            getpickedschedules();

            $('.btn-view-schedule').on('click', function(){
                $('#modal-schedule').modal('show')
                $('#modal-subject-name').text($(this).attr('data-subjectname'))
                $.ajax({
                    url: '/student/pickschedule/getschedules',
                    type: 'GET',
                    data:{
                        selectedsubject : $(this).attr('data-id')
                    },
                    success:function(data)
                    {
                        $('#select-schedule-container').empty()
                        $('#select-schedule-container').append(data)
                    }
                })
            })

            $(document).on('click', '#btn-modal-submit', function(){
                var schedids = [];
                $('.checkbox-select-sched:checked').each(function(){
                    schedids.push($(this).val())
                })
                $.ajax({
                    url: '/student/pickschedule/addschedule',
                    type: 'GET',
                    data: {
                        schedids            :   schedids
                    },
                    success:function(data)
                    {
                        $('#btn-modal-close').click()
                        getpickedschedules();
                        toastr.success('Updated successfully!', 'Schedule')
                    }
                })
            })
            $(document).on('click', '.btn-delete-sched', function(){
                var schedid = $(this).attr('data-id');
                Swal.fire({
                        title: 'Are you sure you want to delete this schedule?',
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Delete!'
                })
                .then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: '/student/pickschedule/deleteschedule',
                                type: 'GET',
                                data: {
                                    schedid             :   schedid
                                },
                                dataType: 'json',
                                success:function(data)
                                {
                                    $(".swal2-container").remove();
                                    $('body').removeClass('swal2-shown')
                                    $('body').removeClass('swal2-height-auto')
                                    if(data == 1)
                                    {
                                        getpickedschedules();
                                        toastr.success('Deleted successfully!', 'Schedule')
                                    }else{
                                        toastr.error(data, 'Schedule')
                                    }
                                }
                            })
                        }
                })
            })

        })
    </script>
@endsection



{{-- @endsection --}}


