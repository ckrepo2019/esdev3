<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
{{-- <link rel="stylesheet" href="{{asset('plugins/fullcalendar-interaction/main.min.css')}}"> --}}
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}">
@extends('registrar.layouts.app')

@section('content')

    <style>
        
        .donutTeachers{
            margin-top: 90px;
            margin: 0 auto;
            background: transparent url("{{asset('assets/images/corporate-grooming-20140726161024.jpg')}}") no-repeat  28% 60%;
            background-size: 30%;
        }
        .donutStudents{
            margin-top: 90px;
            margin: 0 auto;
            background: transparent url("{{asset('assets/images/student-cartoon-png-2.png')}}") no-repeat  28% 60%;
            background-size: 30%;
        }
        #studentstable{
            font-size: 13px;
        }
        @media (min-width: 768px) {
            .modal-xl {
                width: 90%;
                max-width:1200px;
            }
        }
        
    </style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Student Requirements</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Students' Requirements</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <select class="form-control" id="select-gradelevel">
                                <option value="">ALL</option>
                                @foreach($gradelevels as $gradelevel)
                                    <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-block btn-primary" id="button-filter"><i class="fa fa-sync"></i> Filter</button>
                        </div>
                        <div class="col-md-6 text-right">
                            {{-- <button type="button" class="btn btn-primary" id="btn-export-excel"><i class="fa fa-file-excel"></i> Excel</button> --}}
                            <button type="button" class="btn btn-primary" id="btn-export-pdf"><i class="fa fa-file-pdf"></i> PDF</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {{-- <div class="row">
                        <div class="alert alert-warning alert-dismissible col-12">
                            <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                            Still working on this page.
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-12" id="container-results">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-requirement" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="student-name"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body"  id="reqs-container">
                    
                </div>
                {{-- <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-modal-close">Close</button>
                    <button type="button" class="btn btn-primary" id="submit-newprogram">Submit</button>
                </div> --}}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-viewphoto" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="container-photoview">
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{-- <div class="modal fade" id="modal-uploadphoto" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="student-name"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body"  id="reqs-container">
                    
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-modal-close">Close</button>
                    <button type="button" class="btn btn-primary" id="submit-newprogram">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div> --}}



    <!-- jQuery -->
    {{-- @endsection
    @section('footerjavascript') --}}
                            
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-daygrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-timegrid/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-interaction/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar-bootstrap/main.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- DataTables -->
    {{-- <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script> --}}
    <!-- ChartJS -->
    <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
    {{-- <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script> --}}
    <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
    <script>
        
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        

        $(document).ready(function(){
            $(document).on('click', '.btn-status', function(){
                $('#modal-requirement').modal('show')
                var studentid   = $(this).attr('data-id');
                var sid         = $(this).attr('data-sid');
                var lrn         = $(this).attr('data-lrn');
                var name        = $(this).attr('data-name');
                $.ajax({
                    url: '/registrar/studentrequirementsgetinfo',
                    type: 'GET',
                    data: {
                        studentid   : studentid,
                        sid         : sid,
                        lrn         : lrn
                    },
                    datatype: 'json',
                    success:function(data){
                        $('#student-name').text(name);
                        $('#reqs-container').empty()
                        if(data.length > 0)
                        {
                            $.each(data, function(key, value){
                                if(value.status == 0)
                                {
                                    var status = 'NOT YET SUBMITTED';
                                    var btncolor  = 'btn-default';
                                }else{
                                    var status = 'SUBMITTED';
                                    var btncolor  = 'btn-success';
                                }
                                $('#reqs-container').append(
                                    '<div class="row mb-2">'+
                                        '<div class="col-6"><button type="button" class="btn btn-block btn-default">'+value.description+'</button></div>'+
                                        '<div class="col-6"><button type="button" class="btn btn-block '+btncolor+' btn-changestatus" data-studentid="'+studentid+'" data-reqid="'+value.id+'" data-status="'+value.status+'">'+status+'</button></div>'+
                                    '</div>'
                                )
                            })
                        }
                        // $('#submit-scholarshipclose').click();
                        // $('#selectcontainer').empty()
                        // $('#selectcontainer').append(data)
                    }
                })
            })
            $(document).on('click','.btn-changestatus', function(){
                var studentid = $(this).attr('data-studentid');
                var reqid = $(this).attr('data-reqid');
                var reqstatus = 0;
                var thisbutton = $(this);
                var btncolor = 'btn-default';
                if($(this).attr('data-status') == 0)
                {
                    reqstatus = 1;
                    btncolor = 'btn-success';
                }
                $.ajax({
                    url: '/registrar/studentrequirementsupdatestat',
                    type: 'GET',
                    data: {
                        studentid    : studentid,
                        reqid        : reqid,
                        reqstatus    : reqstatus,
                    },
                    success:function(data){
                        if(data == 1)
                        {
                            Toast.fire({
                                type: 'success',
                                title: 'Status updated successfully!'
                            })
                            if(reqstatus == 0)
                            {
                                thisbutton.text('NOT YET SUBMITTED')
                            }
                            else if(reqstatus == 1)
                            {
                                thisbutton.text('SUBMITTED')
                            }
                            thisbutton.removeClass('btn-default');
                            thisbutton.removeClass('btn-success');
                            thisbutton.addClass(btncolor);
                            thisbutton.attr('data-status', reqstatus)
                            thisbutton.attr('data-status', reqstatus)
                            $('#button-filter').click()
                        }else{
                            Toast.fire({
                                type: 'error',
                                title: 'Something went wrong!'
                            })
                        }
                    }
                })
            })
            $(document).on('click','.btn-uploadphoto', function(){

                var submittedreqid = $(this).attr('data-reqsid');
                var reqid = $(this).attr('data-reqid');
                var studid = $(this).attr('data-studid');
                var queuecoderef = $(this).attr('data-queuecoderef');
                $.ajax({
                    url: '/registrar/studentrequirementsgetphoto',
                    type: 'GET',
                    data: {
                        queuecoderef    : queuecoderef,
                        submittedreqid    : submittedreqid,
                        reqid    : reqid,
                        studid   : studid
                    },
                    // dataType: 'json',
                    success:function(data){
                        $('#container-photoview').empty()
                        $('#container-photoview').append(data)
                    }
                })
            })
            $(document).on('click','.btn-viewdetails', function(){

                var submittedreqid = $(this).attr('data-reqsid');
                var reqid = $(this).attr('data-reqid');
                var studid = $(this).attr('data-studid');
                var queuecoderef = $(this).attr('data-queuecoderef');
                
                $.ajax({
                    url: '/registrar/studentrequirementsgetphotos',
                    type: 'GET',
                    data: {
                        queuecoderef    : queuecoderef,
                        submittedreqid    : submittedreqid,
                        reqid    : reqid,
                        studid   : studid
                    },
                    // dataType: 'json',
                    success:function(data){
                        $('#photos-container'+studid).empty()
                        $('#photos-container'+studid).append(data)
                    }
                })
            })
            $(document).on('click','.delete-subreq', function(){
                var id = $(this).attr('data-id');
                var thisbtn = $(this);
                Swal.fire({
                    title: 'Are you sure you want to delete this submitted requirement?',
                    html: 'You won\'t be able to revert this!<br/>Would you like to continue?',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Continue'
                })
                .then((result) => {
                    if (result.value) {
                        thisbtn.prop('disabled', true)
                        $.ajax({
                            url: '/registrar/studentrequirementsdeletephoto',
                            type:'GET',
                            dataType: 'json',
                            data: {
                                id      :  id
                            },
                            success:function(data) {
                                if(data == 1)
                                {
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Submitted requirement deleted successfully!'
                                    })
                                    window.location.reload()
                                }else{
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Something went wrong!'
                                    })
                                }
                            }
                        })
                    }
                })
            })
            $('#button-filter').on('click', function(){
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/registrar/studentrequirementsresults',
                    type:'GET',
                    // dataType: 'json',
                    data: {
                        levelid      :  $('#select-gradelevel').val()
                    },
                    success:function(data) {
                        // console.log(data)
                        $('#container-results').empty()
                        $('#container-results').append(data)
                        // table.on( 'order.dt search.dt', function () {
                        //     table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                        //         cell.innerHTML = i+1;
                        //     } );
                        // } ).draw();
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                })
            })
            $('#button-filter').click();
            $('#btn-export-excel').on('click', function(){
                window.open('/registrar/studentrequirementsresults?levelid='+$('#select-gradelevel').val()+'&exporttype=excel')
            })
            $('#btn-export-pdf').on('click', function(){
                window.open('/registrar/studentrequirementsresults?levelid='+$('#select-gradelevel').val()+'&exporttype=pdf')
            })
        })
    </script>
@endsection
