@php

    $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid','resourcepath')->first();

      if(!Auth::check()){ 
            header("Location: " . URL::to('/'), true, 302);
            exit();
      }

      if(Session::get('currentPortal') == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(Session::get('currentPortal') == 6){
            $extend = 'adminPortal.layouts.app2';
      }else{
        if(isset($check_refid->refid)){
			if($check_refid->refid == 29){
                $extend = 'idmanagement.layouts.app2';
            }
        }else{
            header("Location: " . URL::to('/'), true, 302);
            exit();
        }
      }
@endphp

@extends($extend)

@section('pagespecificscripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/croppie/croppie.css')}}">

      <style>
            .dropdown-toggle::after {
                  display: none;
                  margin-left: .255em;
                  vertical-align: .255em;
                  content: "";
                  border-top: .3em solid;
                  border-right: .3em solid transparent;
                  border-bottom: 0;
                  border-left: .3em solid transparent;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                margin-top: -9px;
            }
            .btn-group.special {
                display: flex;
            }
            .special .btn {
                flex: 1
            }

            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }

            td {
                padding: 2px !important;
            }

            .btn-app-1 {
                border-radius: 3px;
                background-color: #f8f9fa;
                color: #6c757d;
                font-size: 12px;
                position: relative;
                text-align: center;
                padding: 0 !important;
                cursor: pointer;
            }

            .btn-app-1>.badge {
                font-size: 10px;
                font-weight: 400;
                position: absolute;
                right: -10px;
                top: -3px;
            }

            .btn-app-1>.fa, .btn-app-1>.fab, .btn-app-1>.far, .btn-app-1>.fas, .btn-app-1>.glyphicon, .btn-app-1>.ion {
                display: block;
                font-size: 20px;
            }

            .view_image:hover,
            .view_image:focus {
                color: #bbb;
                text-decoration: none;
                cursor: pointer;
            }
      </style>
    @php
        $schoolinfo = DB::table('schoolinfo')->first();
    @endphp
@endsection


@section('modalSection')
  <div class="modal fade" id="passModal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <form id="checkPassForm" method="POST" action="/matchPassword">
        <div class="modal-content">
                <div class="modal-body">
                    <div class="message"></div>
                    <div class="form-group">
                        <label>Enter Password</label>
                        <input type="password"  id="password"  name="password" class="form-control">
                        <span class="invalid-feedback" role="alert">
                            <strong>Password does not match</strong>
                        </span>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary">RESET</button>
                </div>
          </div>
      </form>
    </div>
  </div>
  <div class="modal fade" id="rfidhistory" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row form-group">
                    <div class="col-md-12">
                        <label for="">Student</label>
                        <input readonly="readonly" class="form-control form-control-sm" id="rfid_hisname">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="">History</label>
                        <table class="table table-sm table-bordered" style="font-size:.9rem !important">
                            <thead>
                                <tr>
                                    <th width="15%">Code</th>
                                    <th width="30%">Registration Date</th>
                                    <th width="14%">Status</th>
                                    <th width="30%">Expiration Date</th>
                                </tr>
                            </thead>
                            <tbody id="rfidhis_body">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="view_image_modal" style="display: none; padding-right: 17px;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
          <div class="modal-body">
              <div class="row">
                <div class="col-md-10 p-1" >
                  <label for="" id="fn_image_label"></label>
                </div>
                <div class="col-md-2 text-right">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <img src="" alt="" id="view_image" width="100%">
                </div>
              </div>
          </div>
        
      </div>
    </div>
</div>
@endsection


@section('content')
      <section class="content-header">
            <div class="container-fluid">
                  <div class="row">
                        <div class="col-sm-6">
                            <h1>FAS RFID Assignment</h1>
                        </div>
                        <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                              <li class="breadcrumb-item"><a href="/home">Home</a></li>
                              <li class="breadcrumb-item active">FAS RFID Assignment</li>
                        </ol>
                        </div>
                  </div>
            </div>
      </section>
      <section class="content pt-0">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="">FAS Status</label>
                                        <select class="form-control select2" id="filter_fasstatus">
                                            <option value="">All</option>
                                            <option value="1" selected="selected">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table id="example2" class="table table-sm table-hover" style="font-size:.9rem !important"">
                                            <thead>
                                                <tr>
                                                    <th width="35%">Employee</th>
                                                    <th width="10%" class="text-center">Status</th>
                                                    <th width="10%"></th>
                                                    <th swidth="20%">Upload Photo</th>
                                                    <th width="20%">RFID</th>
                                                    <th width="5%"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
                 
            </div>
      </section>
      <div id="edit_profile_pic" class="modal custom-modal fade" role="dialog" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                     <h5 class="modal-title">Image Upload</h5><span id="fas_info" class="pl-1 pt-1"></span>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span>
                       </button>
                   </div>
              <div class="modal-body">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="upload-demo"></div>
                                <input type="file" name="upload" id="upload" class="form-control mb-2" accept=".png, .jpg, .jpeg" required>
                            </div>
                        </div>
                    
                        <div class="row">
                            <div class="col-md-12">
                                <span class="mt-4"><i ><b>Allowed File Type:</b> png, jpg, jpeg</i></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button  id="updateimage" class="btn btn-info savebutton">Update</button>
                    </div>
              </div>
           </div>
      </div>
        
@endsection

@section('footerjavascript')
    <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('plugins/croppie/croppie.js')}}"></script>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <script>

            var onerror_url = @json(asset('dist/img/download.png'));

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click','.view_image',function(){
                var temp_src = $(this).attr('src')
                var temp_id = $(this).attr('data-id')
                var temp_data = fasinfo.filter(x=>x.id == temp_id)
                $('#fn_image_label').text(temp_data[0].lastname+', '+temp_data[0].firstname)
                $('#view_image_modal').modal()
                $('#view_image').attr('src',temp_src)
            })

            $(document).on('click','#updateimage', function (ev) {
                $uploadCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (resp) {

                    console.log(resp)
                    // return false;
                    $.ajax({
                        url: "/adminemployeerfidassign/uploadphoto",
                        type: "POST",
                        data: {
                                "image"     :   resp,
                                empid       : empid
                            },
                        success: function (data) {
                            if(data[0].status == 0){
                                $('#studpic').addClass('is-invalid')
                                $('.invalid-feedback').removeAttr('hidden')
                                $('.invalid-feedback')[0].innerHTML = '<strong>'+data[0].errors.image[0]+'</strong>'
                            }
                            else{
                                Toast.fire({
                                    type: 'success',
                                    title: 'Updated Successfully!'
                                })
                                getemployees()
                            }
                        },
                    });
                });
            });

            $('.select2').select2({
                placeholder:'All',
                allowClear:true
            })

            $(document).on('change','#filter_fasstatus',function(){
                getemployees()
            })

            $(document).on('click','.btn-app-1',function(){
                var tempid = $(this).attr('data-id')
                var tempstud = fasinfo.filter(x=>x.id == tempid)

                
                $('#rfid_hisname').val(tempstud[0].lastname +', '+tempstud[0].firstname)
                $('#rfidhis_body').empty()
                if(tempstud[0].history.length > 0){
                    $.each(tempstud[0].history,function(a,b){
                        $('#rfidhis_body').append('<tr><td>'+b.rfidcode+'</td><td>'+b.regdate+'</td><td>'+b.type.toUpperCase()+'</td><td>'+b.expiredate+'</td></tr>')
                    })
                }else{
                    $('#rfidhis_body').append('<tr><td colspan="4" class="text-center">No Records Found</td></tr>')
                }

                $('#rfidhistory').modal()
            })

            var fasinfo = []

            function getemployees(){
                
                $('#example2').DataTable({
                    paging: true,
                    autoWidth: false,
                    destroy: true,
                    serverSide: true,
                    processing: true,
                    stateSave: true,
                    lengthChange : false,
                    ajax:{
                        url: '/adminemployeesetup/index',
                        type: 'GET',
                        data: {
                            action : 'getemployees',
                            'fasstatus':$('#filter_fasstatus').val()
                        },
                        dataSrc: function ( json ) {
                            fasinfo = json.data
                            return json.data;
                        }
                    },
                    columns: [
                        { "data": null },
                        { "data": null },
                        { "data": null },
                        { "data": null },
                        { "data": "rfid" },
                        { "data": null }
                    ],
                    columnDefs: [
                        {
                            'targets': 0,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = ' <div class="row">'+
                                    '<div class="col-md-3">'+
                                        '<img src="/'+rowData.picurl+'?random='+new Date().getTime()+'" class="view_image" alt="User Image" onerror="this.src=\''+onerror_url+'\'" width="40px" data-id="'+rowData.id+'"/>'+

                                        '</div>'+
                                        '<div class="col-md-9">'+
                                            '<div class="row">'+
                                                '<div class="col-md-12">'+rowData.lastname+', '+rowData.firstname+'</div>   ' +
                                                '<div class="col-md-12">'+ '<small class="text-primary">'+rowData.tid+'</small></div>   ' +
                                            '</div>'+
                                            
                                        
                                        '</div>'+
                                    '</div>'
                            }
                        },
                        {
                            'targets': 1,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td).addClass('align-middle')
                                    $(td).addClass('text-center')
                                    if(rowData.isactive == 1){
                                        $(td)[0].innerHTML = '<span class="badge badge-success">Active</span>'
                                    }else{
                                        $(td)[0].innerHTML = '<span class="badge badge-danger">Inactive</span>'
                                    }
                                   
                            }
                        },
                        {
                            'targets': 3,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td).addClass('align-middle')
                                    $(td)[0].innerHTML = '<button type="button" class="btn btn-outline-primary btn-block edit-pic-icon btn-sm" data-id="'+rowData.id+'">'+
                                        '<i class="fa fa-upload"></i> Upload Photo'+
                                        '</button>';
                                        
                                    $(td).addClass('text-center')
                            }
                        },
                        {
                            'targets': 2,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td).addClass('align-middle')
                                    $(td)[0].innerHTML = `<a class="btn btn-app-1 btn-sm" data-id="`+rowData.id+`">
                                                                <span class="badge bg-primary">`+rowData.history.length+`</span>
                                                                <i class="far fa-clock"></i>
                                                            </a>`;
                                        
                                    $(td).addClass('text-center')
                            }
                        },
                        {
                            'targets': 4,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                var readonly = rowData.rfid != null ? 'readonly="readonly' : ''
                                if(rowData.rfid == null)
                                {
                                    rowData.rfid = '';
                                }
                                // $(td).css('vertical-align','middle !important')
                                $(td)[0].innerHTML = '<input '+readonly+' type="text" class="form-control form-control-sm" data-id="'+rowData.id+'" value="'+rowData.rfid+'"/>';
                                    $(td).addClass('align-middle')
                            }
                        },
                        {
                            'targets': 5,
                            'orderable': false, 
                            'createdCell':  function (td, cellData, rowData, row, col) {
                                $(td)[0].innerHTML = '<button type="button" class="btn btn-sm btn-warning btn-reset" data-id="'+rowData.id+'"><i class="fa fa-undo"></i></button>';
                                    $(td).addClass('align-middle')
                            }
                        },
                    ]
                });
            }

            var empid = null
            getemployees();
            $(document).on('click','.edit-pic-icon', function(){
                empid = $(this).attr('data-id')
                var tempinfo = fasinfo.filter(x=>x.id == empid)
                $('#fas_info').text(' ( '+tempinfo[0].lastname+', '+tempinfo[0].firstname+' )')
                console.log(fasinfo.filter(x=>x.id == empid))

                $('#edit_profile_pic').modal('show')
                $('#upload-demo').empty()
                $('#upload').val('')
                $uploadCrop = $('#upload-demo').croppie({
                    enableExif: true,
                    viewport: {
                        width: 304,
                        height: 289,
                        // type: 'circle'        
                    },
                    boundary: {
                        width: 304,
                        height: 289
                    }
                });
                $('#upload').on('change', function () { 
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $uploadCrop.croppie('bind', {
                            url: e.target.result
                        }).then(function(){
                            console.log('jQuery bind complete');
                        });
                    }
                    reader.readAsDataURL(this.files[0]);
                });
                $('.upload-result').on('click', function (ev) {
                    $uploadCrop.croppie('result', {
                        type: 'canvas',
                        size: 'viewport'
                    }).then(function (resp) {
                        $.ajax({
                            url: "/adminemployeesetup/uploadphoto",
                            type: "POST",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "image"     :   resp,
                                "studid":   studid
                                },
                            success: function (data) {
                                window.location.reload();
                            }
                        });
                    });        
                });
            })
    

        var schoolinfo = @json($schoolinfo);
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        var activeletter = 'A';
       
            $('.btn-eachletter').on('click', function(){
                $('.btn-eachletter').removeClass('active');
                $(this).addClass('active');
                activeletter = $(this).text();
            })
        $(document).on('keypress','input', function(e) {
            if(e.which == 13) {
                process_input($(this))
            }
        });

        function process_input(data,renew=null){

            if(data.val().replace(/^\s+|\s+$/g, "").length == 0){
                    
                    Toast.fire({
                        type: 'error',
                        title: 'Cannot be empty!'
                    })

                    data.val('')

                }
                else{

                    var thisinput = data;
                    var studentid   = data.attr('data-id');
                    var rfid        = data.val();

                    $.ajax({
                        url: '/adminemployeesetup/update',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            employeeid   :studentid,
                            rfid        : rfid,
                            renew       : renew
                        }, success:function(data)
                        {
                            if(data == 1)
                            {
                                
                                if(renew == 'renew'){
                                    Toast.fire({
                                        type: 'success',
                                        title: 'RFID renewed successfully.'
                                    })
                                }else{
                                    Toast.fire({
                                        type: 'success',
                                        title: 'RFID assigned successfully!'
                                    })
                                }
                               
                                getemployees()
                                $('input[data-id='+studentid+']').attr('readonly','readonly')

                            }
                            else if(data == 2)
                            {
                                Toast.fire({
                                    type: 'warning',
                                    title: 'RFID is assigned already!'
                                })
                                thisinput.val('')
                            }
                            else if(data == 4)
                            {

                                Toast.fire({
                                    type: 'warning',
                                    title: 'RFID is already assigned to a different account!'
                                })
                                thisinput.val('')
                            }
                            else if(data == 3)
                            {
                                if(schoolinfo.RFIDRenewal == 1){
                                    Swal.fire({
                                        text: 'RFID is already expired? Do you want to renew?',
                                        type: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Renew RFID'
                                    }).then((result) => {
                                        if (result.value) {
                                            process_input(thisinput,'renew')
                                        }
                                    })
                                }else{
                                    Toast.fire({
                                        type: 'error',
                                        title: 'RFID is already expired!'
                                    })
                                }
                            }
                            else{
                                Toast.fire({
                                    type: 'error',
                                    title: 'RFID not yet registered!'
                                })
                                thisinput.val('')

                            }
                        }
                    })
                    
                }

        }

        $(document).on('click','.btn-reset', function(){
            var employeeid   = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure you want to reset employee\'s RFID?',
                html:
                    "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reset it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '/adminemployeesetup/reset',
                        type:"GET",
                        dataType:"json",
                        data:{
                            employeeid: employeeid
                        },
                        complete: function(){

                            Toast.fire({
                                type: 'success',
                                title: 'RFID reset successfully!'
                            })
                            $('input[data-id='+employeeid+']').val('')
                            $('input[data-id='+employeeid+']').removeAttr('readonly')
                            getemployees()
                        }
                    })
                }
            })
        })
    </script>
  
    
    
@endsection

