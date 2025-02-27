<!-- Font Awesome -->
{{-- <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-daygrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-timegrid/main.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/fullcalendar-bootstrap/main.min.css')}}"> --}}
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
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
                    <h1 class="m-0 text-dark">School Last Attended</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">School Last Attended</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-4">
                    <label>Select Grade Level</label>
                    <select class="form-control" id="select-gradelevel">
                        @foreach($gradelevels as $gradelevel)
                            <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-8">
                    <label>&nbsp;</label><br/>
                    <button type="button" class="btn btn-primary" id="btn-filter"><i class="fa fa-sync"></i> Filter</button>
                </div>
            </div>
        </div>
    </div>
    <div id="container-filter">
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
    @endsection
    @section('footerjavascript')
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        $(document).ready(function(){
            var levelid;
            $('#btn-filter').on('click', function(){
                var selectedlevelid = $('#select-gradelevel').val();
                Swal.fire({
                    title: 'Fetching data...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                $.ajax({
                    url: '/registrar/sla/filter',
                    type:'GET',
                    // dataType: 'json',
                    data: {
                        levelid      :  selectedlevelid
                    },
                    success:function(data) {
                        levelid = selectedlevelid;
                        $('#container-filter').empty()
                        $('#container-filter').append(data)
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown')
                        $('body').removeClass('swal2-height-auto')
                    }
                })
            })
            $(document).on('keypress','.lastschoolatt', function(e) {
                
                if(e.which == 13) {

                    if($(this).val().replace(/^\s+|\s+$/g, "").length == 0){
                        
                        Toast.fire({
                            type: 'error',
                            title: 'Cannot be empty!'
                        })

                        $(this).val('')

                    }
                    else{

                        var thisinput       = $(this);
                        var studentid       = $(this).attr('data-id');
                        var lastschoolatt   = $(this).val();

                        $.ajax({
                            url: '/registrar/sla/updateschoolatt',
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                studentid            : studentid,
                                lastschoolatt        : lastschoolatt
                            }, success:function(data)
                            {
                                if(data == 1)
                                {

                                    Toast.fire({
                                        type: 'success',
                                        title: 'Updated successfully!'
                                    })
                                    thisinput.prop('readonly', true);

                                }
                                else if(data == 2)
                                {

                                    Toast.fire({
                                        type: 'error',
                                        title: 'Something went wrong!'
                                    })
                                    thisinput.val('')
                                }
                            }
                        })
                        
                    }
                
                }
            });
            $(document).on('click','#btn-exportexcel', function(){
                window.open('/registrar/sla/filter?exporttoexcel=1&levelid='+levelid)
            })
        })
    </script>
    
@endsection
