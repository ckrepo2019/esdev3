
@extends('registrar.layouts.app')
@section('content')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <style>
        #table-admission-dates th, #table-admission-dates td {
            padding: 2px;
        }
    </style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Admission Date</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Admission Date</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div>
    </section>
    <section class="content-body">
        {{-- <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3">
                        <label>Select S.Y.</label>
                        <select class="form-control select2" id="select-syid">
                            @foreach(DB::table('sy')->get() as $eachsy)
                                <option value="{{$eachsy->id}}" @if($eachsy->isactive == 1) selected @endif>{{$eachsy->sydesc}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Grade Level</label>
                        <select class="form-control select2" id="select-levelid">
                            @foreach($gradelevels as $gradelevel)
                                <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 text-right align-self-end">
                        <button type="button" class="btn btn-primary" id="btn-generate"><i class="fa fa-sync"></i> Get results</button>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="card shadow" style="box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
            <div class="card-header text-right">
                <button type="button" class="btn btn-primary" id="btn-admissiondate-modal-show"><i class="fa fa-plus"></i> Add Admission date</button>
            </div>
            <div class="card-body" id="container-admission-dates">
                {{-- <table class="table table-bordered" id="table-admission-dates">
                    <thead>
                        <tr>
                            <th>School Year</th>
                            <th>Grade Level</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($admissiondates)>0)
                            @foreach($admissiondates as $eachadmissiondate)
                                <tr>
                                    <td>{{$eachadmissiondate->sydesc ?? ''}}</td>
                                    <td>{{$eachadmissiondate->levelname ?? ''}}</td>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash-alt"></i> Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table> --}}
            </div>
        </div>
    </section>
    
    
    <div class="modal fade" id="modal-admissiondate">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add admission date</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>Select school year</label>
                            <select class="form-control select2" id="select-syid">
                                @foreach($schoolyears as $schoolyear)
                                    <option value="{{$schoolyear->id}}" {{$schoolyear->isactive ==1 ? 'selected' : ''}}>{{$schoolyear->sydesc}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>Select Grade Level</label>
                            <select class="form-control select2" id="select-levelid">
                                @foreach($gradelevels as $gradelevel)
                                    <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>Date of Admission</label>
                            <input type="date" class="form-control" id="input-date"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn-admissiondate-add"><i class="fa fa-plus"></i> &nbsp;Add</button>
                </div>
            </div>
        
        </div>  
    
    </div>
@endsection
@section('footerscripts')
    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        })
        
        function getadmissiondates()
        {
            $.ajax({
                    url: '/setup/admissiondate',
                    type: 'GET',
                    data: {
                        action: 'getadmissiondates'
                    },
                    success:function(data)
                    {
                        $('#container-admission-dates').empty()
                        $('#container-admission-dates').append(data)
                        // $(".swal2-container").remove();
                        // $('body').removeClass('swal2-shown')
                        // $('body').removeClass('swal2-height-auto')
                    }
            }); 

        }
        getadmissiondates()
        $('.select2').select2({
        theme: 'bootstrap4'
        })
        $('#btn-admissiondate-modal-show').on('click', function(){
            $('#modal-admissiondate').modal('show')
        })
        $('#btn-admissiondate-add').on('click', function(){
            var selectsyid = $('#select-syid').val();
            var selectlevelid = $('#select-levelid').val();
            var admissiondate = $('#input-date').val();
            if(admissiondate.replace(/^\s+|\s+$/g, "").length == 0)
            {
                $('#input-date').css('border','1px solid red')
                toastr.warning('Please fill in required field!', 'Admission date')
            }else{
                $('#input-date').removeAttr('style')
                Swal.fire({
                        title: 'Saving...',
                        allowOutsideClick: false,
                        closeOnClickOutside: false,
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        }
                })
                $.ajax({
                        url: '/setup/admissiondate',
                        type: 'GET',
                        data: {
                            action: 'addadmissiondate',
                            syid    : selectsyid,
                            levelid    : selectlevelid,
                            admissiondate    : admissiondate,
                        },
                        success:function(data)
                        {
                            if(data==1)
                            {
                                toastr.success('Added succesfully!', 'Admission date')
                                $(".swal2-container").remove();
                                $('body').removeClass('swal2-shown')
                                $('body').removeClass('swal2-height-auto')
                                $('#modal-admissiondate').modal('toggle')
                                getadmissiondates()
                            }else if(data==2){
                                $('#modal-admissiondate').find('.select2-selection').each(function(){
                                    $(this).css('border','1px solid red')
                                })
                                // $('.select2-selection').css('border','1px solid red !important')
                                toastr.warning('A school year and grade level admission date exists!', 'Admission date')
                                $(".swal2-container").remove();
                                $('body').removeClass('swal2-shown')
                                $('body').removeClass('swal2-height-auto')
                            }
                            // $('#div-results').empty()
                            // $('#div-results').append(data)
                            // $(".swal2-container").remove();
                            // $('body').removeClass('swal2-shown')
                            // $('body').removeClass('swal2-height-auto')
                        }
                }); 
            }
        })
        // $('#btn-generate').on('click', function(){
        //     var selectsyid = $('#select-syid').val();
        //     var selectlevelid = $('#select-levelid').val();
        //     Swal.fire({
        //             title: 'Fetching data...',
        //             allowOutsideClick: false,
        //             closeOnClickOutside: false,
        //             onBeforeOpen: () => {
        //                 Swal.showLoading()
        //             }
        //     })
        //     $.ajax({
        //             url: '/setup/admissiondate',
        //             type: 'GET',
        //             data: {
        //                 action: 'getadmissiondates',
        //                 selectsyid    : selectsyid,
        //                 selectlevelid    : selectlevelid
        //             },
        //             success:function(data)
        //             {
        //                 $('#div-results').empty()
        //                 $('#div-results').append(data)
        //                 $(".swal2-container").remove();
        //                 $('body').removeClass('swal2-shown')
        //                 $('body').removeClass('swal2-height-auto')
        //             }
        //     }); 
        // })
    </script>
@endsection

                                        

                                        
                                        