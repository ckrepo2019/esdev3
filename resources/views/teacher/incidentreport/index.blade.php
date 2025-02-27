
@php

    if(!Auth::check()){ 
        header("Location: " . URL::to('/'), true, 302);
        exit();
    }

    if(Session::get('currentPortal') == 1){
        $extend = 'teacher.layouts.app';
    }else if(auth()->user()->type == 6){
        $extend = 'adminPortal.layouts.app2';
    }else if(auth()->user()->type == 17){
        $extend = 'superadmin.layouts.app2';
    }else if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
    }else{
        header("Location: " . URL::to('/'), true, 302);
        exit();
      }
@endphp

@extends($extend)

@section('content')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<style>
    .shadow {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        border: 0 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        margin-top: -9px;
    }
</style>

@php
    $schoolinfo = DB::table('schoolinfo')->first();
    $sy = DB::table('sy')->orderBy('sydesc')->get(); 
@endphp


<div class="modal" id="reset_pass_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Report Behavior</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label>Student <span class="text-danger">*</span></label><br/>
                        <input type="text" class="form-control" id="report-behavior-name" disabled/>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label>Behavior <span class="text-danger">*</span></label><br/>
                        <textarea class="form-control" id="behavior"></textarea>
                    </div>
                    
                    <div class="col-md-6">
                        <label>Date <span class="text-danger">*</span></label><br/>
                        <input type="date" class="form-control" id="behavior-date" value="{{date('Y-m-d')}}"/>
                    </div>
                    <div class="col-md-6">
                        <label>Card <span class="text-danger">*</span></label><br/>
                        <input type="text" class="form-control text-light" id="behavior-card" disabled/>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label>Action Recommended<span class="text-danger"></span></label><br/>
                        <textarea class="form-control" id="behavior-action"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close-addcomplaint">Close</button>
                <button type="button" class="btn btn-primary" id="btn-submit-behavior">Submit</button>
            </div>
        </div>
    </div>
</div>



<section class="content-header pt-0">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Observable Behavior</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Observable Behavior</li>
            </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
      <div class="container-fluid">
        <div class="row" hidden id="online_connection_holder">
            <div class="col-md-12">
              <div class="card shadow">
                <div class="card-body p-1 pl-3">
                  <div class="row">
                    <div class="col-md-6 pt-1" style="font-size:.9rem !important">
                      <label for="" class="mb-0">Online Connection: </label> <i><span id="online_status">Cheking...</span></i>
                    </div>
                    <div class="col-md-4 pt-1 text-right" style="font-size:.9rem !important">
                      <label for="" class="mb-0">Data From: </label> 
                    
                    </div>
                    <div class="col-md-2">
                      <select name="" class="form-control form-control-sm" id="data_from">
                        <option value="local" selected="selected">Local</option>
                        <option value="online">Online</option>
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
                                <div class="col-md-2">
                                    <label for="">School year</label>
                                    <select class="form-control select2" id="input_sy">
                                          {{-- <option value="" selected="selected">School Year</option> --}}
                                          @foreach ($sy as $item)
                                                @php
                                                    $active = $item->isactive == 1 ? 'selected="seleted"' : ''
                                                @endphp
                                                <option value="{{$item->id}}" {{$active}}>{{$item->sydesc}}</option>
                                          @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2" id="section_Holder" hidden>
                                    <label for="">Section</label>
                                    <select class="form-control select2" id="input_section">
                                        <option value="" selected="selected">Select Section</option>
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
                            <div class="col-md-12">
                                <table class="table table-sm" id="datatable_1" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="20%">Student Name</th>
                                            <th width="20%">Contact Number</th>
                                            <th width="20%">Parent</th>
                                            <th width="20%">Parent Contact Number</th>
                                            <th width="20%">Report Behavior</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="col-md-12">
                                <table class="table table-sm" id="datatable_2" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="20%">Student Name</th>
                                            <th width="20%">Contact Number</th>
                                            <th width="20%">Status</th>
                                            <th width="20%">Date Notified</th>
                                            <th width="20%">Remarks</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
      </div>
</section>

@endsection

@section('footerjavascript')

<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>

<script>
    $(document).ready(function(){

        var selected_id = null
        var selected_studid = null
        var selected_type = null
        var selected_passwordtype = null
        var selected_usertype = null
        var selected_gentype = null
        var portal = @json(Session::get('currentPortal'))
        

        var STUDENTID;

        if(portal != 3){
            $('#section_Holder').removeAttr('hidden')
        }

        var school_setup = @json($schoolinfo);
        var isonline = false;

        if(school_setup.projectsetup == 'online' &&  school_setup.processsetup == 'hybrid1'){
            enable_button = false;
        }else{
            if(school_setup.projectsetup == 'offline' && ( school_setup.processsetup == 'hybrid1' || school_setup.processsetup == 'all' ) ){
                isonline = true
                // $('#online_connection_holder').removeAttr('hidden')
                check_online_connection()
            }
        
        }

        function check_online_connection(){
            $.ajax({
                type:'GET',
                url: school_setup.es_cloudurl+'/checkconnection',
                success:function(data) {
                    connected_stat = true
                    // get_last_index('users',true)
                    // get_last_index('teacher',true)
                    // get_last_index('teacheracadprog',true)
                    // get_last_index('faspriv',true)
                    $('#online_status').text('Connected')
                }, 
                error:function(){
                    $('#online_status').text('Not Connected')
                }
            })
         }

        $('.select2').select2()

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })

            var all_sections = []
            var temp_data = []
            if(portal != 3){
                get_sections()
            }
            

            // var table = subjectplot_datatable(temp_data)
            // table.state.clear();
            // table.destroy();

            $(document).on('change','#input_sy',function(){
                // var temp_data = []
                // subjectplot_datatable(temp_data)
                if(portal != 3){
                    get_sections()
                }
                get_student_credentials()
            })

            $(document).on('change','#input_section',function(){
                get_student_credentials()
            })

            function get_sections(){

                var url = '/teacher/student/credential/advisory'

                $.ajax({
					type:'GET',
					url: url,
                    data:{
                        syid:$('#input_sy').val()
                    },
					success:function(data) {
                        all_sections = data
                        if(data.length > 0){
                            $("#input_section").empty()
                            $("#input_section").append('<option value="">Select Section</option>')
                            $("#input_section").select2({
                                    data: all_sections,
                                    allowClear: true,
                                    placeholder: "Select Section",
                            })

                            Toast.fire({
                                type: 'success',
                                title: all_sections.length+' sections found!'
                            })

                        }else{

                            Toast.fire({
                                type: 'warning',
                                title: 'No section found!'
                            })
                            
                            $("#input_section").empty()
                            $("#input_section").select2({
                                    data: all_sections,
                                    allowClear: true,
                                    placeholder: "Select Section",
                            })
                        }
					}
            })
            }

            $(document).on('click','.greencard-button',function(){
                var studname = $(this).data('student');
                console.log(studname);
                $('#report-behavior-name').val(studname);
                $('#behavior-card').val('Green');
                $('#behavior-card').css('background-color', 'green');
                $('#reset_pass_modal').modal()
                STUDENTID =  $(this).data('id');
            })

            $(document).on('click','.redcard-button',function(){
                var studname = $(this).data('student');
                console.log(studname);
                $('#report-behavior-name').val(studname);
                $('#behavior-card').val('Red');
                $('#behavior-card').css('background-color', 'Red');
                $('#reset_pass_modal').modal()
                STUDENTID =  $(this).data('id');

            })

            $(document).on('click','#btn-submit-behavior',function(){


                var date = $('#behavior-date').val();
                var card = $('#behavior-card').val();
                var action = $('#behavior-action').val();
                var behavior = $('#behavior').val();




                if (!date || !card || !action || !behavior) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Please input all fields!'
                    })
                    return;
                }

                
                console.log(date);
                console.log(card);
                console.log(action);
                console.log(behavior);
                console.log(STUDENTID);

                var formData = new FormData();
                formData.append('date', date);
                formData.append('card', card);
                formData.append('action', action);
                formData.append('behavior', behavior);
                formData.append('id', STUDENTID);
                

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                

                                                // Set the CSRF token in the request headers
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                $.ajax({
                    url: "/teacher/behavior/submitBehavior",
                    type: 'POST', // Use lowercase 'type' instead of 'TYPE'
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Handle the success response
                        console.log(response);
                        Toast.fire({
                            type: 'success',
                            title: 'All the changes have been saved'
                        });
                        $("#reset_pass_modal .close").click()
                    },
                    error: function(xhr) {
                        // Handle error here
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong'
                        });

                        $("#reset_pass_modal .close").click()
                    }
                });



            })


            var all_account = []
            var all_account2 = []
            get_student_credentials()
            get_student_reported()

            function get_student_credentials(){

                var url = '/teacher/student/observable/list'

                if(isonline){
                    url = school_setup.es_cloudurl+'/teacher/student/observable/list'
                }

                $("#datatable_1").DataTable({
                    destroy: true,
                    stateSave: true,
                    lengthChange: false,
                    columns: [
                            { "data": "studentname" },
                            { "data": null },
                            { "data": null },
                            { "data": null },
                            { "data": null },
                    ],
                    serverSide: true,
                    processing: true,
                    ajax:{
                            url: url,
                            type: 'GET',
                            data: {
                                syid:$('#input_sy').val(),
                                sectionid:$('#input_section').val(),
                            },
                            dataSrc: function ( json ) {
                                
                                all_account = json.data
                                console.log('this:', all_account);
                                return json.data;
                            }
                    },
                    columnDefs: [
                            {
                                'targets': 0,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        $(td).addClass('align-middle')
                                        $(td)[0].innerHTML = rowData.studentname+'<p class="text-muted mb-0" style="font-size:.7rem">'+rowData.sid+'</p>'
                                }
                            },
                            {
                                'targets': 1,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        $(td)[0].innerHTML =  rowData.contactno
                                        $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 2,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td)[0].innerHTML =  rowData.fathername
                                    $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 3,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td)[0].innerHTML =  rowData.fcontactno
                                    $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 4,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    
                                    var text = `
                                        <button class="btn btn-success greencard-button" data-student="${rowData.studentname}" data-id="${rowData.id}">
                                            Green Card
                                        </button>
                                        <button class="btn btn-danger redcard-button" data-student="${rowData.studentname}" data-id="${rowData.id}">
                                            Red Card
                                        </button>
                                    `;

                                    $(td)[0].innerHTML = text;
                                }
                            }
                            
                    ]
                    
                });



            }

            function get_student_reported(){

                var url = '/teacher/student/observable/status'

                if(isonline){
                    url = school_setup.es_cloudurl+'/teacher/student/observable/status'
                }

                $("#datatable_2").DataTable({
                    destroy: true,
                    stateSave: true,
                    lengthChange: true,
                    columns: [
                            { "data": "studentname" },
                            { "data": null },
                            { "data": null },
                            { "data": null },
                            { "data": null },
                    ],
                    serverSide: true,
                    processing: true,
                    ajax:{
                            url: url,
                            type: 'GET',
                            dataSrc: function ( json ) {
                                
                                all_account2 = json.data
                                console.log(all_account);
                                return json.data;
                            }
                    },
                    columnDefs: [
                            {
                                'targets': 0,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        $(td).addClass('align-middle')
                                        $(td)[0].innerHTML = rowData.studentname+'<p class="text-muted mb-0" style="font-size:.7rem">'+rowData.sid+'</p>'
                                }
                            },
                            {
                                'targets': 1,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                        $(td)[0].innerHTML =  rowData.contactno
                                        $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 2,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {

                                    $(td)[0].innerHTML = `<span class="badge ${rowData.badge}">${rowData.status}</span> <p class="text-muted mb-0" style="font-size:.7rem">${rowData.behavior}</p>`;


                                    $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 3,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {
                                    $(td)[0].innerHTML =  rowData.cdate
                                    $(td).addClass('align-middle')
                                }
                            },
                            {
                                'targets': 4,
                                'orderable': false, 
                                'createdCell':  function (td, cellData, rowData, row, col) {

                                    var text = '<a class="mb-0">'+(rowData.remark ? rowData.remark + '</a>':  ' No Remarks yet </a>')
                                    

                                    $(td)[0].innerHTML = text;
                                }
                            }
                            
                    ]
                    
                });



            }


    })
</script>
    

@endsection