
@extends('registrar.layouts.app')
@section('content')

<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<section class="content-header">
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-12">
      <!-- <h1>Student Information</h1> -->
      <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
        <!-- <i class="fa fa-file-invoice nav-icon"></i>  -->
        <b>ADDING / DROPPING</b></h4>
    </div>
    <div class="col-sm-12 pt-0">
      <ol class="breadcrumb" style="font-size: 13px;">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active"><a href="/college/adddrop/index">ADDING / DROPPING</a></li>
        <li class="breadcrumb-item active">{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename}} {{$studinfo->suffix}}</li>
        {{-- <li class="breadcrumb-item active">{{$coursename}}</li> --}}
      </ol>
    </div>
    <div class="col-sm-12">
        <div class="alert alert-warning alert-dismissible">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
            Currently working on this page.
          </div>
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-bordered" style="font-size: 12px;table-layout: fixed;">
            <thead>
                <tr>
                    <th>Subj Code</th>
                    <th>Description</th>
                    <th>Units</th>
                    <th>Schedule</th>
                    <th>Instructor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5">
                        <button type="button" class="btn btn-default btn-sm float-right" id="addsched" >Add sched</button>
                    </td>
                </tr>
                @if(count($schedules)>0)
                    @foreach($schedules as $schedule)
                        <tr id="{{$schedule->subjectinfo->subjectid}} - {{$studinfo->id}}" @if($schedule->dropped == 1) style="background-color: #e9967a6e;"@endif>
                            <td>
                                <button type="button" class="btn btn-sm btn-default p-1 mr-2 dropsubject">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <span class="subjectcode">{{$schedule->subjectinfo->subjectcode}}</span>
                            </td>
                            <td class="subjectname">{{$schedule->subjectinfo->subjectname}}</td>
                            <td class="text-center units">
                                {{$schedule->units}}
                            </td>
                            <td class="schedule">
                                @foreach($schedule->schedules as $sched)
                                    {{$sched->day}} - {{$sched->stime}} - {{$sched->etime}}
                                    <br/>
                                @endforeach
                            </td>
                            <td>
                                @if($schedule->teacher != null)
                                    {{$schedule->teacher->lastname}}, {{$schedule->teacher->firstname}} {{$schedule->teacher->middlename}} {{$schedule->teacher->suffix}}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
  </div>
</div>
</section>
<div class="modal fade" id="modalview" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Schedule</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
            <div id="schedscontainer">

            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Add Schedule</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<script>
    $(document).ready(function(){
        
        $(document).on('click','.dropsubject', function(){

            var subjectcode = $(this).closest('tr').find('.subjectcode').text();
            var subjectname = $(this).closest('tr').find('.subjectname').text();
            var units       = $(this).closest('tr').find('.units').text();
            var schedule    = $(this).closest('tr').find('.schedule').text();
            var ids         = $(this).closest('tr').attr('id');
            var trelement   = $(this).closest('tr');

            Swal.fire({
                title: 'Are you sure you want to drop this subject?',
                type: 'warning',
                html: '<div class="text-left">Subject Code: '+subjectcode+
                        '<br/>'+
                        'Subject Title: '+subjectname+
                        '<br/>'+
                        'Units: '+units+'</div>',
                allowOutsideClick: false,
                confirmButtonText: 'Drop',
                showCancelButton: true
            }).then((confirm) => {
                if (confirm.value) {
                    $.ajax({
                        url: '/college/adddrop/dropsubject',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            ids           :   ids
                        },
                        success: function(data){
                            // console.log(data)
                            if(data == 0)
                            {
                                Swal.fire({
                                    title: 'Cannot be deleted!',
                                    type: 'error',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Close',
                                    allowOutsideClick: false
                                })
                            }
                            else if(data == 1)
                            {
                            
                                trelement.css('background-color','#e9967a6e')
                                Swal.fire({
                                    title: 'Schedule dropped successfully!',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Close',
                                    allowOutsideClick: false
                                })
                            }
                        }
                    })
                }
            })
        })
        $(document).on('click','#addsched', function(){
            $('#modalview').modal('show');
            $.ajax({
                url: '/college/adddrop/getsubjects',
                type: 'get',
                data: {
                    studid   :   '{{$studinfo->id}}'
                },
                success: function(data){
                    $('#schedscontainer').empty();
                    $('#schedscontainer').append(data);
                }
            })
        })
        $(document).on('click','.selectsubject', function(){
            var subjectid = $(this).attr('id');
            $.ajax({
                url: '/college/adddrop/getavailablescheds',
                type: 'get',
                data: {
                    studid   :   '{{$studinfo->id}}',
                    subjectid:   subjectid
                },
                success: function(data){
                    $('#schedscontainer').empty();
                    $('#schedscontainer').append(data);
                }
            })
        })
        $(document).on('click','#backsubjectselection', function(){
            $('#addsched').click()
        })
        $(document).on('click','.addsubject', function(){
            var sectionname = $(this).closest('tr').find('.sectionname').text();
            var schedule    = $(this).closest('tr').find('.schedule').text();
            var id          = $(this).closest('tr').attr('id');
            var trelement   = $(this).closest('tr');

            Swal.fire({
                title: 'Are you sure you want to add this schedule?',
                type: 'warning',
                html: '<div class="text-left">Section: '+sectionname+
                        '<br/>'+
                        'Schedule: '+schedule+
                        '</div>',
                allowOutsideClick: false,
                confirmButtonText: 'Add',
                showCancelButton: true
            }).then((confirm) => {
                if (confirm.value) {
                    $.ajax({
                        url: '/college/adddrop/addschedule',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            studid          :   '{{$studinfo->id}}',
                            classschedid    :   id
                        },
                        success: function(data){
                            // console.log(data)
                            if(data == 0)
                            {
                                Swal.fire({
                                    title: 'Cannot be added!',
                                    type: 'error',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Close',
                                    allowOutsideClick: false
                                })
                            }
                            else if(data == 1)
                            {
                            
                                trelement.css('background-color','#0080004d')
                                trelement.find('button').remove()
                                Swal.fire({
                                    title: 'Schedule added successfully!',
                                    type: 'success',
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Close',
                                    allowOutsideClick: false
                                })
                            }
                        }
                    })
                }
            })
        })
    })
</script>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- fullCalendar 2.2.5 -->
@endsection
