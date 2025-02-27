@extends('finance.layouts.app')

@section('content')
  <section class="content">
  			<!-- Payment Items -->
        <div class="row mb-2 ml-2">
            <div class="col-md-6">
                <h1 class="m-0 text-dark">Scholarship Application</h1>
            </div>
            <div class="col-md-6 text-right">
                <button id="scholarship_setup" class="btn btn-default btn-lg" data-toggle="tooltip" title="Scolarship Setup">
                    <i class="fas fa-cogs"></i>
                </button>
            </div>
            
        </div>

		<div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        
                    </div>
                    <div class="card-body">
                        <div id="main_table" class="table-responsive p-0">
                            <table id="class_list" class="table table-hover table-head-fixed table-sm text-sm">
                                <thead class="bg-warning p-0">
                                  <tr>
                                    <th>NAME</th>
                                    <th>LEVEL</th>
                                    <th>SCHOLARSHIP</th>
                                    <th>SCHOOL YEAR</th>
                                    <th>SEMESTER</th>
                                    <th>STATUS</th>
                                    
                                  </tr>
                                </thead> 
                                <tbody id="app_list" style="cursor: pointer;">
                                    
                                </tbody>             
                            </table>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
  </section>
@endsection

@section('modal')
    <div class="modal fade show" id="modal-scholarship" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                <h4 class="modal-title">Scholarship</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-12" id="app_name">
                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12" id="app_level">
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="row text-bold text-primary text-right">
                                    <div class="col-md-12">
                                        <h3 id="app_status"></h3>
                                    </div>
                                </div>
                                <div class="row text-right">
                                    <div class="col-md-12" id="app_syinfo"></div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row p-0">
                            <div class="col-md-12" id="">
                                <h5 class="">SCHOLARSHIP: <b id="app_scholar"></b></h5>
                            </div>
                        </div>
                        <hr>
                        <div class="row form-group">
                            <div class="col-md-12 text-bold">
                                REQUIREMENTS: 
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12 responsive">
                                <table class="table table-sm text-sm">
                                    <tbody style="cursor: pointer" id="app_requirements">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        

                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="app_disapprove" type="button" class="btn btn-danger" style="">Disapprove</button>
                    <button id="app_approve" type="button" class="btn btn-success" data-id="0">Approve</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-app_approve" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                <h4 class="modal-title"><span id="setup_name"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
               
                <form id="req_form" action="/finance/scholarship_setup_upload" method="POST" enctype="multipart/form-data" detail-id="0">
                    @csrf
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="row form-group">
                                <div class="col-md-12 responsive">
                                    <table class="table table-sm text-sm">
                                        <thead>
                                            <tr>
                                                <th colspan="2">CHARGES</th>
                                            </tr>
                                        </thead>
                                        <tbody id="charges_list">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="req_description">CREDIT ADJUSTMENT</label>
                                    <select name="" id="adj_class" class="select2 form-control">
                                    </select>
                                </div>
                                
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="req_description">AMOUNT</label>
                                    <input id="adj_amount" type="text" class="form-control" placeholder="0.00">
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button id="class_delete" type="button" class="btn btn-danger" style="display: none">Delete</button>
                        {{-- <button id="req_save" type="button" class="btn btn-primary" data-id="0">Upload</button> --}}
                        <button id="adj_post" class="btn btn-primary">Proceed</button>
                    </div>
                </form>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-scholarship_setup" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                <h4 class="modal-title">Scholarship Setup</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div class="modal-body">
                    
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-7"></div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input id="setup_search" type="text" class="form-control form-control-sm" placeholder="Search" aria-label="Search" aria-describedby="search-button">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-sm" type="button" id="scholar_search">Search</button>
                                        <button class="btn btn-success btn-sm" type="button" id="scholar_create">Create Scholarship</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12 responsive">
                                <table class="table table-sm text-sm">
                                    <thead class="text-muted">
                                        <tr>
                                            <th>SCHOLARSHIP</th>
                                            <th>END OF SUBMISSION</th>
                                            <th class="text-center">ACTIVE</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="setup_list">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        

                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="class_delete" type="button" class="btn btn-danger" style="display: none">Delete</button>
                    <button id="class_save" type="button" class="btn btn-primary" data-id="0">Save</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-scholarship_setup_detail" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                <h4 class="modal-title">Scholarship Setup</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="setup_description">DESCRIPTION</label>
                                    <input type="text" class="form-control form-control-sm" id="setup_description" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="setup_endofsubmission">END OF SUBMISSION</label>
                                    <input type="date" class="form-control form-control-sm" id="setup_endofsubmission">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="setup_active">
                                        <label for="setup_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="class_delete" type="button" class="btn btn-danger" style="display: none">Delete</button>
                    <button id="setup_save" type="button" class="btn btn-primary" data-id="0">Save</button>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>

    <div class="modal fade show" id="modal-scholarship_setup_upload" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                <h4 class="modal-title"><span id="setup_name"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                </div>
               
                <form id="req_form" action="/finance/scholarship_setup_upload" method="POST" enctype="multipart/form-data" detail-id="0">
                    @csrf
                    <div class="modal-body">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <label for="req_description">REQUIREMENT</label>
                                    <input type="text" class="form-control form-control-sm" id="req_description" name="req_description" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                                <div class="col-md-4" style="margin-top: 2.3em">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="req_active" name="req_active">
                                        <label for="req_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    {{-- <label for="setup_endofsubmission">FILE INPUT</label> --}}
                                    <input type="file" id="req_file" name="req_file">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button id="class_delete" type="button" class="btn btn-danger" style="display: none">Delete</button>
                        {{-- <button id="req_save" type="button" class="btn btn-primary" data-id="0">Upload</button> --}}
                        <button class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div> {{-- dialog --}}
    </div>

    


  

  
@endsection

@section('js')
  
  <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
  <script type="text/javascript">
    $(function () {
        bsCustomFileInput.init()
    })
    
    $(document).ready(function(){
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        

        $(window).resize(function(){
            screenadjust()    
        })

        screenadjust()

        function screenadjust()
        {
            var screen_height = $(window).height();

            $('#main_table').css('height', screen_height - 244)
            // $('.screen-adj').css('height', screen_height - 223);
        }

        // setup_load()

        function setup_load()
        {
            var filter = $('#setup_search').val();

            $.ajax({
                type: "GET",
                url: "{{ route('scholarship_setup_load') }}",
                data: {
                    filter:filter
                },
                success: function (data) {
                    $('#setup_list').html(data)
                }
            });
        }

		$(document).on('click','#setup_save', function(){
            var setup_description = $('#setup_description').val();
            var setup_endofsubmission = $('#setup_endofsubmission').val();
            var setup_active = $('#setup_active').is(':checked');
            var filter = $('#setup_search').val();
            var dataid = $(this).attr('data-id')    
            
            $.ajax({
                type: "POST",
                url: "{{ route('scholarship_setup_create') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    setup_description:setup_description,
                    setup_endofsubmission:setup_endofsubmission,
                    setup_active:setup_active,
                    filter:filter,
                    dataid:dataid
                },
                // dataType: "dataType",
                success: function (data) {
                    if(data == 'exists')
                    {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.onmouseenter = Swal.stopTimer;
                                toast.onmouseleave = Swal.resumeTimer;
                            }
                        });
                        Toast.fire({
                            type: "error",
                            title: "Setup already existed"
                        });
                    }
                    else{
                        setup_load()
                        $("#modal-scholarship_setup_detail").modal('hide');
                    }

                    
                }
            });
        })

        function clearinputs()
        {
            $('#setup_description').val('')
            $('#setup_endofsubmission').val('')
            $('#setup_active').prop('checked', false)
            $('#setup_save').attr('data-id', 0)
        }

        $(document).on('click', '#scholar_create', function(){
            clearinputs()
            $('#modal-scholarship_setup_detail').modal('show')
        })

        $(document).on('click', '.btnedit', function(){
            var dataid = $(this).attr('data-id');
            $.ajax({
                type: "GET",
                url: "{{route('scholarship_setup_read')}}",
                data: {
                    dataid:dataid
                },
                // dataType: "dataType",
                success: function (data) {
                    $('#setup_description').val(data.description)
                    $('#setup_endofsubmission').val(data.endofsubmission)
                    $('#setup_active').prop('checked', data.isactive)
                    $('#setup_save').attr('data-id', dataid)
                    $("#modal-scholarship_setup_detail").modal('show');
                }
            });
        })

        $('#req_form').submit(function(e) {
            e.preventDefault(); // prevent form from submitting normally

            var formData = new FormData(this);

            formData.append('description', $('#req_description').val());
            formData.append('req_active', $('#req_active').is(':checked'));
            formData.append('headerid', $(this).attr('data-id'));
            formData.append('detailid', $(this).attr('detail-id'));

            $.ajax({
                url: '/finance/scholarship_setup_upload',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // handle success response

                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        type: "success",
                        title: "File uploaded successfully"
                    });

                    $('#modal-scholarship_setup_upload').modal('hide')
                    setup_load()
                    
                },
                error: function(xhr, status, error) {
                    // handle error response
                    console.log(error);
                }
            });
        });

        $(document).on('click', '.btnupload', function(){
            var dataid = $(this).attr('data-id')
            $('#modal-scholarship_setup_upload').modal('show')
            $('#req_form').attr('data-id', dataid)
            $('#req_form').attr('detail-id', 0)

            $("#req_description").val('')
            $('#req_active').prop('checked', false)

        })

        $(document).on('click', '#scholarship_setup', function(){
            setup_load()
            $('#modal-scholarship_setup').modal('show')
        })

        $(document).on('change', '#setup_search', function(e){
            setup_load()
        })

        $(document).on('click', '#scholar_search', function(){
            setup_load()
        })

        $(document).on('click', '.btndelete', function(){
            var dataid = $(this).attr('data-id')

            Swal.fire({
                title: "Delete Scholarship setup?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Proceed"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('scholarship_setup_delete') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            dataid:dataid
                        },
                        // dataType: "dataType",
                        success: function (data) {
                            setup_load()

                            if(data == 'done')
                            {
                                const Toast = Swal.mixin({
                                    toast: true,
                                    position: "top-end",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.onmouseenter = Swal.stopTimer;
                                        toast.onmouseleave = Swal.resumeTimer;
                                    }
                                });
                                Toast.fire({
                                    type: "success",
                                    title: "Scolarship has been deleted."
                                });
                            }
                            else{
                                 const Toast = Swal.mixin({
                                    toast: true,
                                    position: "top-end",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.onmouseenter = Swal.stopTimer;
                                        toast.onmouseleave = Swal.resumeTimer;
                                    }
                                });
                                Toast.fire({
                                    type: "error",
                                    title: "The selected scolarship have scholar applicants!"
                                });
                            }
                        }
                    });        
                }
            });

            
        })

        $(document).on('click', '.detailedit', function(){
            var id = $(this).attr('detail-id')

            $.ajax({
                type: "GET",
                url: "{{ route('scholarship_setup_detail_read') }}",
                
                data: {
                    id:id
                },
                // dataType: "dataType",
                success: function (data) {
                    $('#req_description').val(data.description)
                    $("#req_active").prop('checked', data.isactive)
                    $('#req_form').attr('detail-id', id)
                    $('#modal-scholarship_setup_upload').modal('show')
                }
            });
        })

        $(document).on('click', '.detaildelete', function(){
            var id = $(this).attr('detail-id')

            Swal.fire({
                title: "Remove selected requirement?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Proceed"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('scholarship_setup_detail_delete') }}",
                        headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                        data: {
                            id:id
                        },
                        // dataType: "dataType",
                        success: function (data) {
                            setup_load()

                            const Toast = Swal.mixin({
                                    toast: true,
                                    position: "top-end",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.onmouseenter = Swal.stopTimer;
                                        toast.onmouseleave = Swal.resumeTimer;
                                    }
                                });
                                Toast.fire({
                                    type: "success",
                                    title: "Reqirement has been deleted."
                                });
                        }
                    });        
                }
            });  
        })

        applicant_load()

        function applicant_load()
        {
            var filter = $('#app_filter').val()
            var status = $('#app_filter_status').val()
            
            $.ajax({
                type: "GET",
                url: "{{ route('scholarship_app_load') }}",
                data: {
                    filter:filter,
                    status:status
                },
                success: function (data) {
                    $('#app_list').html(data)
                }
            });
        }

        $(document).on('click', '#app_list tr', function(){
            var id = $(this).attr('data-id')

            $.ajax({
                type: "GET",
                url: "{{ route('scholarship_app_read') }}",
                data: {
                    id:id
                },
                success: function (data) {
                    var middlename = (data.applicant.middlename == null) ? '' : data.applicant.middlename
                    var name = data.applicant.lastname + ', ' + data.applicant.firstname + ' ' + middlename

                    $('#app_name').text(name)
                    $('#app_level').text(data.levelname + ' - ' + data.section)
                    $('#app_status').text(data.applicant.scholar_status)
                    $('#app_syinfo').text('SY: ' + data.applicant.sydesc + ' ' + data.sem)
                    $('#app_scholar').text(data.applicant.description)

                    $('#app_requirements').empty();
                    $(data.requirements).each(function (index, val) {
                        $('#app_requirements').append(`
                            <tr data-id="`+val.id+`">
                                <td>`+val.description+`</td>
                                <td>
                                    <a href="`+val.fileurl+`" target="_blank"> `+val.fileurl+` </a>
                                </td>
                            </tr>
                        `)
                        
                    });

                    $('#app_approve').attr('data-id', id)
                    $('#modal-scholarship').modal('show')
                    manageUI(data.applicant.scholar_status)
                }
            });
        })

        function manageUI(status)
        {
            if(status == 'APPROVED')
            {
                $('#app_approve').attr('disabled','disabled')
                $('#app_disapprove').attr('disabled','disabled')
                $('#app_status').addClass('text-success')
                $('#app_status').removeClass('text-primary')
                $('#app_status').removeClass('text-danger')
            }
            else if(status == 'DISAPPROVED')
            {
                $('#app_approve').attr('disabled','disabled')
                $('#app_disapprove').attr('disabled','disabled')
                $('#app_status').addClass('text-danger')
                $('#app_status').removeClass('text-primary')
                $('#app_status').removeClass('text-success')
            }
            else{
                $('#app_approve').prop('disabled', false)
                $('#app_disapprove').prop('disabled', false)
                $('#app_status').addClass('text-primary')
                $('#app_status').removeClass('text-success')
                $('#app_status').removeClass('text-danger')
            }
        }

        $(document).on('click', '#app_approve', function(){

            var id = $('#app_approve').attr('data-id')

            $.ajax({
                type: "GET",
                url: "{{ route('scholarship_app_adj') }}",
                data: {
                    id:id
                },
                // dataType: "dataType",
                success: function (data) {
                    
                }
            });

            $('#modal-app_approve').modal('show')
        })

		$(document).on('click', '#app_approve', function(){
            var id = $(this).attr('data-id')
            Swal.fire({
                title: "Approve Applicant?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Approve"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('scholarship_app_approve') }}",
                        data: {
                            id:id
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            applicant_load()
                            $('#modal-scholarship').modal('hide')

                            const Toast = Swal.mixin({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                            });
                            Toast.fire({
                                type: "success",
                                title: "Applicant has been approved."
                            });
                        }
                    });
                }
            });
        })

        $(document).on('click', '#app_disapprove', function(){
            var id = $('#app_approve').attr('data-id')
            Swal.fire({
                title: "Disapprove Applicant?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Approve"
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('scholarship_app_disapprove') }}",
                        data: {
                            id:id
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            applicant_load()
                            $('#modal-scholarship').modal('hide')

                            const Toast = Swal.mixin({
                                toast: true,
                                position: "top-end",
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.onmouseenter = Swal.stopTimer;
                                    toast.onmouseleave = Swal.resumeTimer;
                                }
                            });
                            Toast.fire({
                                type: "success",
                                title: "Applicant has been disapproved."
                            });
                        }
                    });
                }
            });
        })

	})

  </script>
  
@endsection